<?php

namespace Controllers;

use Config\Database;
use Utils\JWT;
use PDO;
use Exception;

class SupplierController
{
    private $conn;
    private $table_name = "suppliers";

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

        if (!$headers)
            return null;

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

        $query = "SELECT * FROM " . $this->table_name . " WHERE pae_id = :pae_id ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->execute();

        $suppliers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "data" => $suppliers]);
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
        if (isset($data->name))
            $data->name = mb_strtoupper($data->name, 'UTF-8');
        if (isset($data->contact_person))
            $data->contact_person = mb_strtoupper($data->contact_person, 'UTF-8');
        if (isset($data->city))
            $data->city = mb_strtoupper($data->city, 'UTF-8');
        if (isset($data->email))
            $data->email = strtolower($data->email);

        if (empty($data->nit) || empty($data->name)) {
            http_response_code(400);
            echo json_encode(["message" => "NIT y Nombre son obligatorios."]);
            return;
        }

        // Check if NIT exists for this PAE
        $check = $this->conn->prepare("SELECT id FROM suppliers WHERE nit = :nit AND pae_id = :pae_id");
        $check->execute(['nit' => $data->nit, 'pae_id' => $pae_id]);
        if ($check->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(["message" => "Ya existe un proveedor con este NIT en su programa."]);
            return;
        }

        $query = "INSERT INTO " . $this->table_name . " 
                  (pae_id, nit, name, contact_person, phone, email, address, city, type) 
                  VALUES (:pae_id, :nit, :name, :contact_person, :phone, :email, :address, :city, :type)";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->bindParam(":nit", $data->nit);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":contact_person", $data->contact_person);
        $stmt->bindParam(":phone", $data->phone);
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":address", $data->address);
        $stmt->bindParam(":city", $data->city);
        $stmt->bindParam(":type", $data->type);

        if ($stmt->execute()) {
            http_response_code(201);
            echo json_encode(["message" => "Proveedor creado exitosamente.", "id" => $this->conn->lastInsertId()]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al crear proveedor."]);
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
        if (isset($data->name))
            $data->name = mb_strtoupper($data->name, 'UTF-8');
        if (isset($data->contact_person))
            $data->contact_person = mb_strtoupper($data->contact_person, 'UTF-8');
        if (isset($data->city))
            $data->city = mb_strtoupper($data->city, 'UTF-8');
        if (isset($data->email))
            $data->email = strtolower($data->email);

        // Verify ownership
        $check = $this->conn->prepare("SELECT id FROM suppliers WHERE id = :id AND pae_id = :pae_id");
        $check->execute(['id' => $id, 'pae_id' => $pae_id]);
        if ($check->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(["message" => "Proveedor no encontrado."]);
            return;
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET nit = :nit, name = :name, contact_person = :contact_person, 
                      phone = :phone, email = :email, address = :address, 
                      city = :city, type = :type, status = :status
                  WHERE id = :id AND pae_id = :pae_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":nit", $data->nit);
        $stmt->bindParam(":name", $data->name);
        $stmt->bindParam(":contact_person", $data->contact_person);
        $stmt->bindParam(":phone", $data->phone);
        $stmt->bindParam(":email", $data->email);
        $stmt->bindParam(":address", $data->address);
        $stmt->bindParam(":city", $data->city);
        $stmt->bindParam(":type", $data->type);
        $stmt->bindParam(":status", $data->status);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pae_id", $pae_id);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Proveedor actualizado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al actualizar proveedor."]);
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
            echo json_encode(["message" => "Proveedor eliminado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al eliminar proveedor."]);
        }
    }
}
