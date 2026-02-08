<?php

namespace Controllers;

use Config\Database;
use PDO;
use Exception;

class RationTypeController
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

    public function index()
    {
        try {
            $pae_id = $this->getPaeIdFromToken();
            $query = "SELECT * FROM pae_ration_types WHERE pae_id = :pae_id AND status = 'ACTIVO' ORDER BY name";
            $stmt = $this->conn->prepare($query);
            $stmt->bindValue(':pae_id', $pae_id);
            $stmt->execute();
            echo json_encode(['success' => true, 'data' => $stmt->fetchAll(PDO::FETCH_ASSOC)]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function store()
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $pae_id = $this->getPaeIdFromToken();

            $query = "INSERT INTO pae_ration_types (pae_id, name, description, status) 
                      VALUES (:pae_id, :name, :description, :status)";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':pae_id' => $pae_id,
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null,
                ':status' => $data['status'] ?? 'ACTIVO'
            ]);

            echo json_encode(['success' => true, 'message' => 'Tipo de ración creado correctamente', 'id' => $this->conn->lastInsertId()]);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function update($id)
    {
        try {
            $data = json_decode(file_get_contents("php://input"), true);
            $query = "UPDATE pae_ration_types SET name = :name, description = :description, status = :status WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':id' => $id,
                ':name' => $data['name'],
                ':description' => $data['description'] ?? null,
                ':status' => $data['status'] ?? 'ACTIVO'
            ]);

            echo json_encode(['success' => true, 'message' => 'Tipo de ración actualizado']);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function delete($id)
    {
        try {
            // Check usage (beneficiaries, recipes, etc)
            // For now, simple check on recipes
            $stmtCheck = $this->conn->prepare("SELECT COUNT(*) FROM recipes WHERE ration_type_id = ?");
            $stmtCheck->execute([$id]);
            if ($stmtCheck->fetchColumn() > 0) {
                throw new Exception("No se puede eliminar porque está siendo usado en recetas.");
            }

            $query = "DELETE FROM pae_ration_types WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':id' => $id]);
            echo json_encode(['success' => true, 'message' => 'Tipo de ración eliminado']);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
