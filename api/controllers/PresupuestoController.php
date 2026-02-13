<?php

namespace Controllers;

use Config\Database;
use Utils\JWT;
use PDO;
use Exception;

class PresupuestoController
{
    private $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    private function getPaeIdFromToken()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) $headers = trim($_SERVER["Authorization"]);
        else if (isset($_SERVER['HTTP_AUTHORIZATION'])) $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) $headers = trim($requestHeaders['Authorization']);
        }

        if (!$headers) return null;
        $arr = explode(" ", $headers);
        $jwt = isset($arr[1]) ? $arr[1] : "";
        if ($jwt) {
            try {
                $decoded = JWT::decode($jwt);
                return $decoded['data']['pae_id'] ?? null;
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    public function index()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $query = "SELECT * FROM presupuesto_items WHERE pae_id = :pae_id AND estado = 1 ORDER BY codigo ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->execute();
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($items);
    }

    public function getBranches()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        // Get branches associated with schools of this PAE
        $query = "SELECT sb.*, s.name as school_name 
                  FROM school_branches sb
                  JOIN schools s ON sb.school_id = s.id
                  WHERE s.pae_id = :pae_id
                  ORDER BY s.name, sb.name";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->execute();
        $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($branches);
    }

    public function store()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"));
        if (!$data) {
            http_response_code(400);
            echo json_encode(["message" => "Datos inválidos."]);
            return;
        }

        try {
            $this->conn->beginTransaction();

            // 1. Insert/Update Item
            $query = "INSERT INTO presupuesto_items 
                      (pae_id, codigo, nombre, descripcion, padre_id, unidad_medida, cantidad_global, tiempo_global, valor_unitario_oficial, valor_total_oficial) 
                      VALUES (:pae_id, :codigo, :nombre, :descripcion, :padre_id, :unidad_medida, :cantidad_global, :tiempo_global, :valor_unitario_oficial, :valor_total_oficial)";

            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ":pae_id" => $pae_id,
                ":codigo" => $data->codigo,
                ":nombre" => $data->nombre,
                ":descripcion" => $data->descripcion ?? '',
                ":padre_id" => !empty($data->padre_id) ? $data->padre_id : null,
                ":unidad_medida" => $data->unidad_medida ?? '',
                ":cantidad_global" => $data->cantidad_global ?? 0,
                ":tiempo_global" => $data->tiempo_global ?? 0,
                ":valor_unitario_oficial" => $data->valor_unitario_oficial ?? 0,
                ":valor_total_oficial" => $data->valor_total_oficial ?? 0
            ]);

            $item_id = $this->conn->lastInsertId();

            // 2. Insert Split/Assignments
            if (!empty($data->distribucion)) {
                $queryAsig = "INSERT INTO presupuesto_asignacion 
                              (pae_id, item_id, branch_id, cantidad, meses, valor_unitario, valor_inicial) 
                              VALUES (:pae_id, :item_id, :branch_id, :cantidad, :meses, :valor_unitario, :valor_inicial)";
                $stmtAsig = $this->conn->prepare($queryAsig);

                foreach ($data->distribucion as $dist) {
                    if ($dist->total > 0) {
                        $stmtAsig->execute([
                            ":pae_id" => $pae_id,
                            ":item_id" => $item_id,
                            ":branch_id" => $dist->branch_id,
                            ":cantidad" => $dist->cantidad,
                            ":meses" => $dist->meses,
                            ":valor_unitario" => $dist->valor_unitario,
                            ":valor_inicial" => $dist->total
                        ]);
                    }
                }
            }

            $this->conn->commit();
            echo json_encode(["success" => true, "message" => "Presupuesto guardado exitosamente."]);
        } catch (Exception $e) {
            $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(["message" => $e->getMessage()]);
        }
    }
}
