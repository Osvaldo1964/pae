<?php

namespace Controllers;

use Config\Database;
use PDO;
use Exception;

class RecipeController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    private function getPaeIdFromToken()
    {
        $headers = getallheaders();
        $auth = $headers['Authorization'] ?? $headers['authorization'] ?? '';
        if (preg_match('/Bearer\s(\S+)/', $auth, $matches)) {
            try {
                $decoded = \Utils\JWT::decode($matches[1]);
                if (is_object($decoded)) return $decoded->data->pae_id ?? null;
                if (is_array($decoded)) return $decoded['data']['pae_id'] ?? null;
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * GET /api/recipes - Listar recetas
     */
    public function index()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();

            // Auto-recalculación para recetas con valores en 0 (corrección de legacy)
            $stmtFixed = $this->conn->prepare("SELECT id FROM recipes WHERE pae_id = :pae AND total_calories = 0");
            $stmtFixed->execute([':pae' => $pae_id]);
            while ($row = $stmtFixed->fetch(PDO::FETCH_ASSOC)) {
                $this->recalculateNutrition($row['id']);
            }

            $query = "SELECT * FROM recipes WHERE pae_id = :pae_id ORDER BY name";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':pae_id', $pae_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * GET /api/recipes/{id} - Detalle de receta
     */
    public function show($id)
    {
        try {
            // Asegurar que los datos estén frescos antes de mostrar
            $this->recalculateNutrition($id);

            $query = "SELECT * FROM recipes WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $recipe = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$recipe) throw new Exception("Receta no encontrada");

            // Ingredientes
            $queryItems = "SELECT ri.*, i.name as item_name, i.code as item_code, mu.abbreviation as unit 
                           FROM recipe_items ri 
                           JOIN items i ON ri.item_id = i.id 
                           JOIN measurement_units mu ON i.measurement_unit_id = mu.id
                           WHERE ri.recipe_id = :id";
            $stmtItems = $this->conn->prepare($queryItems);
            $stmtItems->bindValue(':id', $id);
            $stmtItems->execute();
            $recipe['items'] = $stmtItems->fetchAll(PDO::FETCH_ASSOC);

            echo json_encode(['success' => true, 'data' => $recipe]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * POST /api/recipes - Crear receta
     */
    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();

            $this->conn->beginTransaction();

            $query = "INSERT INTO recipes (pae_id, name, meal_type, description) VALUES (:pae_id, :name, :type, :desc)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':pae_id' => $pae_id,
                ':name' => $data['name'],
                ':type' => $data['meal_type'],
                ':desc' => $data['description'] ?? ''
            ]);
            $recipe_id = $this->conn->lastInsertId();

            if (isset($data['items']) && is_array($data['items'])) {
                $queryItem = "INSERT INTO recipe_items (recipe_id, item_id, quantity, preparation_method) VALUES (:rid, :iid, :qty, :prep)";
                $stmtItem = $this->conn->prepare($queryItem);
                foreach ($data['items'] as $item) {
                    $stmtItem->execute([
                        ':rid' => $recipe_id,
                        ':iid' => $item['item_id'],
                        ':qty' => $item['quantity'],
                        ':prep' => $item['preparation'] ?? ''
                    ]);
                }
            }

            $this->conn->commit();

            // Recalcular DESPUÉS del commit para asegurar que recipe_items ya existan en la DB
            $this->recalculateNutrition($recipe_id);

            echo json_encode(['success' => true, 'message' => 'Receta creada exitosamente', 'id' => $recipe_id]);
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    private function recalculateNutrition($recipe_id)
    {
        // Calcular los totales de una vez uniendo recipe_items con items
        $query = "SELECT 
                    SUM(ri.quantity * i.calories / 100) as calories,
                    SUM(ri.quantity * i.proteins / 100) as proteins,
                    SUM(ri.quantity * i.carbohydrates / 100) as carbohydrates,
                    SUM(ri.quantity * i.fats / 100) as fats
                  FROM recipe_items ri
                  JOIN items i ON ri.item_id = i.id
                  WHERE ri.recipe_id = :id";

        $stmtCalc = $this->conn->prepare($query);
        $stmtCalc->execute([':id' => $recipe_id]);
        $totals = $stmtCalc->fetch(PDO::FETCH_ASSOC);

        if ($totals) {
            $queryUpd = "UPDATE recipes SET 
                         total_calories = :cal, 
                         total_proteins = :pro, 
                         total_carbohydrates = :car, 
                         total_fats = :fat 
                         WHERE id = :id";
            $stmtUpd = $this->conn->prepare($queryUpd);
            $stmtUpd->execute([
                ':cal' => $totals['calories'] ?? 0,
                ':pro' => $totals['proteins'] ?? 0,
                ':car' => $totals['carbohydrates'] ?? 0,
                ':fat' => $totals['fats'] ?? 0,
                ':id' => $recipe_id
            ]);
        }
    }

    /**
     * PUT /api/recipes/{id} - Actualizar receta
     */
    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $this->conn->beginTransaction();

            $query = "UPDATE recipes SET name = :name, meal_type = :type, description = :desc WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':type' => $data['meal_type'],
                ':desc' => $data['description'] ?? ''
            ]);

            // Actualizar ingredientes: Borrar y re-insertar
            $stmtDel = $this->conn->prepare("DELETE FROM recipe_items WHERE recipe_id = :rid");
            $stmtDel->execute([':rid' => $id]);

            if (isset($data['items']) && is_array($data['items'])) {
                $queryItem = "INSERT INTO recipe_items (recipe_id, item_id, quantity, preparation_method) VALUES (:rid, :iid, :qty, :prep)";
                $stmtItem = $this->conn->prepare($queryItem);
                foreach ($data['items'] as $item) {
                    $stmtItem->execute([
                        ':rid' => $id,
                        ':iid' => $item['item_id'],
                        ':qty' => $item['quantity'],
                        ':prep' => $item['preparation'] ?? ''
                    ]);
                }
            }

            $this->conn->commit();

            // Forzar recalculación con los nuevos datos
            $this->recalculateNutrition($id);

            echo json_encode(['success' => true, 'message' => 'Receta actualizada exitosamente']);
        } catch (Exception $e) {
            if ($this->conn->inTransaction()) $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    /**
     * DELETE /api/recipes/{id} - Eliminar receta
     */
    public function delete($id)
    {
        try {
            $query = "DELETE FROM recipes WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Receta eliminada']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
