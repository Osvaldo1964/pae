<?php

namespace Controllers;

use Config\Database;
use Utils\JWT;
use PDO;
use Exception;

class BranchController
{
    private $conn;
    private $table_name = "school_branches";

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    private function getPaeIdFromToken()
    {
        $headers = null;
        if (isset($_SERVER['Authorization'])) {
            $headers = trim($_SERVER["Authorization"]);
        } else if (isset($_SERVER['HTTP_AUTHORIZATION'])) {
            $headers = trim($_SERVER["HTTP_AUTHORIZATION"]);
        } elseif (function_exists('apache_request_headers')) {
            $requestHeaders = apache_request_headers();
            $requestHeaders = array_combine(array_map('ucwords', array_keys($requestHeaders)), array_values($requestHeaders));
            if (isset($requestHeaders['Authorization'])) {
                $headers = trim($requestHeaders['Authorization']);
            }
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

    public function index($school_id = null)
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $query = "SELECT b.*, s.name as school_name 
                  FROM " . $this->table_name . " b
                  JOIN schools s ON b.school_id = s.id
                  WHERE b.pae_id = :pae_id";

        if ($school_id) {
            $query .= " AND b.school_id = :school_id";
        }

        $query .= " ORDER BY b.name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        if ($school_id) {
            $stmt->bindParam(":school_id", $school_id);
        }
        $stmt->execute();

        $branches = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($branches);
    }

    public function create()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"));
        // Enforce casing
        if (isset($data->name)) $data->name = mb_strtoupper($data->name, 'UTF-8');
        if (isset($data->manager_name)) $data->manager_name = mb_strtoupper($data->manager_name, 'UTF-8');


        if (empty($data->school_id) || empty($data->name)) {
            http_response_code(400);
            echo json_encode(["message" => "ID de colegio y nombre de sede son requeridos."]);
            return;
        }

        // Verify school ownership
        $check = $this->conn->prepare("SELECT id FROM schools WHERE id = :id AND pae_id = :pae_id");
        $check->execute(['id' => $data->school_id, 'pae_id' => $pae_id]);
        if ($check->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(["message" => "Colegio no encontrado."]);
            return;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (school_id, pae_id, name, address, phone, manager_name, area_type) 
                  VALUES (:school_id, :pae_id, :name, :address, :phone, :manager_name, :area_type)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":school_id", $data->school_id);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":address", $data->address);
        $stmt->bindParam(":phone", $data->phone);
        $stmt->bindParam(":manager_name", $data->manager_name);
        $stmt->bindParam(":area_type", $data->area_type);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Sede creada exitosamente.", "id" => $this->conn->lastInsertId()]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al crear sede."]);
        }
    }

    public function update($id)
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"));
        // Enforce casing
        if (isset($data->name)) $data->name = mb_strtoupper($data->name, 'UTF-8');
        if (isset($data->manager_name)) $data->manager_name = mb_strtoupper($data->manager_name, 'UTF-8');


        // Verify ownership
        $check = $this->conn->prepare("SELECT id FROM school_branches WHERE id = :id AND pae_id = :pae_id");
        $check->execute(['id' => $id, 'pae_id' => $pae_id]);
        if ($check->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(["message" => "Sede no encontrada."]);
            return;
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET name = :name, address = :address, phone = :phone, 
                      manager_name = :manager_name, area_type = :area_type 
                  WHERE id = :id AND pae_id = :pae_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":address", $data->address);
        $stmt->bindParam(":phone", $data->phone);
        $stmt->bindParam(":manager_name", $data->manager_name);
        $stmt->bindParam(":area_type", $data->area_type);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pae_id", $pae_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Sede actualizada exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al actualizar sede."]);
        }
    }

    public function delete($id)
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND pae_id = :pae_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pae_id", $pae_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Sede eliminada exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al eliminar sede."]);
        }
    }
}
