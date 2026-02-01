<?php

namespace Controllers;

use Config\Database;
use Utils\JWT;
use PDO;
use Exception;

class UserController
{

    private $conn;
    private $table_name = "users";

    public function __construct()
    {
        $db = Database::getInstance();
        $this->conn = $db->getConnection();
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

    public function index()
    {
        ini_set('display_errors', 0); // Prevent HTML error leakage

        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            // Debug info
            $debug = ["token_sent" => isset($_SERVER['HTTP_AUTHORIZATION'])];
            echo json_encode(["message" => "Acceso denegado. Token inválido o sin PAE ID.", "debug" => $debug]);
            return;
        }

        // Fetch users for this PAE, EXCLUDING Super Admin (role_id = 1)
        // Also assuming checking against role_id!=1 is redundant if we check pae_id, 
        // as Super Admins usually have NULL pae_id. 
        // BUT, if a Super Admin logs in AS a tenant, they have a pae_id in the token.
        // We still don't want them listed in the "manageable users" list if they are effectively external?
        // Actually, if the Super Admin is masquerading, they ARE user ID 1.
        // We should exclude ID 1 specifically to be safe, or role_id 1.

        $query = "SELECT u.id, u.username, u.full_name, u.role_id, r.name as role_name, u.created_at 
                  FROM " . $this->table_name . " u
                  LEFT JOIN roles r ON u.role_id = r.id
                  WHERE u.pae_id = :pae_id AND u.role_id != 1
                  ORDER BY u.full_name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->execute();

        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($users);
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

        if (!empty($data->username) && !empty($data->password) && !empty($data->full_name) && !empty($data->role_id)) {

            // Security: Prevent creating Super Admin
            if ($data->role_id == 1) {
                http_response_code(403);
                echo json_encode(["message" => "No tiene permisos para crear Super Administradores."]);
                return;
            }

            // check if username exists
            $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE username = :username";
            $checkStmt = $this->conn->prepare($checkQuery);
            $checkStmt->bindParam(":username", $data->username);
            $checkStmt->execute();
            if ($checkStmt->rowCount() > 0) {
                http_response_code(400);
                echo json_encode(["message" => "El nombre de usuario ya existe."]);
                return;
            }

            $query = "INSERT INTO " . $this->table_name . " 
                      (username, password, full_name, role_id, pae_id) 
                      VALUES (:username, :password, :full_name, :role_id, :pae_id)";

            $stmt = $this->conn->prepare($query);

            $password_hash = password_hash($data->password, PASSWORD_BCRYPT);

            $stmt->bindParam(":username", $data->username);
            $stmt->bindParam(":password", $password_hash);
            $stmt->bindParam(":full_name", $data->full_name);
            $stmt->bindParam(":role_id", $data->role_id);
            $stmt->bindParam(":pae_id", $pae_id);

            if ($stmt->execute()) {
                http_response_code(201);
                echo json_encode(["message" => "Usuario creado exitosamente."]);
            } else {
                http_response_code(503);
                echo json_encode(["message" => "No se pudo crear el usuario."]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos."]);
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

        // Verify User Ownership & Existence
        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE id = :id AND pae_id = :pae_id AND role_id != 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":id", $id);
        $checkStmt->bindParam(":pae_id", $pae_id);
        $checkStmt->execute();

        if ($checkStmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(["message" => "Usuario no encontrado o no tiene permisos."]);
            return;
        }

        // Prevent escalation
        if (isset($data->role_id) && $data->role_id == 1) {
            http_response_code(403);
            echo json_encode(["message" => "No puede asignar rol de Super Administrador."]);
            return;
        }

        $query = "UPDATE " . $this->table_name . " SET full_name = :full_name, role_id = :role_id";

        // Only update password if provided
        if (!empty($data->password)) {
            $query .= ", password = :password";
        }

        $query .= " WHERE id = :id AND pae_id = :pae_id";

        $stmt = $this->conn->prepare($query);

        $stmt->bindParam(":full_name", $data->full_name);
        $stmt->bindParam(":role_id", $data->role_id);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pae_id", $pae_id);

        if (!empty($data->password)) {
            $password_hash = password_hash($data->password, PASSWORD_BCRYPT);
            $stmt->bindParam(":password", $password_hash);
        }

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Usuario actualizado."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo actualizar el usuario."]);
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

        // Verify Ownership
        $checkQuery = "SELECT id FROM " . $this->table_name . " WHERE id = :id AND pae_id = :pae_id AND role_id != 1";
        $checkStmt = $this->conn->prepare($checkQuery);
        $checkStmt->bindParam(":id", $id);
        $checkStmt->bindParam(":pae_id", $pae_id);
        $checkStmt->execute();

        if ($checkStmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(["message" => "Usuario no encontrado o permisos insuficientes."]);
            return;
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);

        if ($stmt->execute()) {
            http_response_code(200);
            echo json_encode(["message" => "Usuario eliminado."]);
        } else {
            http_response_code(503);
            echo json_encode(["message" => "No se pudo eliminar el usuario."]);
        }
    }
}
