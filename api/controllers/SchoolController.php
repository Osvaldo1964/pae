<?php

namespace Controllers;

use Config\Database;
use Config\Config;
use Utils\JWT;
use PDO;
use Exception;

class SchoolController
{
    private $conn;
    private $table_name = "schools";

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

        $schools = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($schools);
    }

    public function create()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        // Support both JSON or FormData (for logo upload)
        $data = $_POST;
        // Enforce casing
        if (isset($data['name'])) $data['name'] = mb_strtoupper($data['name'], 'UTF-8');
        if (isset($data['rector'])) $data['rector'] = mb_strtoupper($data['rector'], 'UTF-8');
        if (isset($data['email'])) $data['email'] = strtolower($data['email']);

        if (empty($data)) {
            $data = (array) json_decode(file_get_contents("php://input"));
        }

        if (empty($data['name'])) {
            http_response_code(400);
            echo json_encode(["message" => "Nombre del colegio es requerido."]);
            return;
        }

        $logo_path = $this->uploadLogo($_FILES['logo'] ?? null);

        $this->conn->beginTransaction();

        try {
            $query = "INSERT INTO " . $this->table_name . " 
                      (pae_id, dane_code, name, rector, address, phone, email, logo_path, department, municipality, school_type, area_type) 
                      VALUES (:pae_id, :dane_code, :name, :rector, :address, :phone, :email, :logo_path, :department, :municipality, :school_type, :area_type)";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":pae_id", $pae_id);
            $stmt->bindParam(":dane_code", $data['dane_code']);
            $stmt->bindParam(":name", $data['name']);
            $stmt->bindParam(":rector", $data['rector']);
            $stmt->bindParam(":address", $data['address']);
            $stmt->bindParam(":phone", $data['phone']);
            $stmt->bindParam(":email", $data['email']);
            $stmt->bindParam(":logo_path", $logo_path);
            $stmt->bindParam(":department", $data['department']);
            $stmt->bindParam(":municipality", $data['municipality']);
            $stmt->bindParam(":school_type", $data['school_type']);
            $stmt->bindParam(":area_type", $data['area_type']);

            if ($stmt->execute()) {
                $school_id = $this->conn->lastInsertId();

                // AUTOMATICALLY CREATE PRINCIPAL BRANCH
                $queryBranch = "INSERT INTO school_branches 
                                (school_id, pae_id, dane_code, name, address, phone, manager_name, area_type) 
                                VALUES (:school_id, :pae_id, :dane_code, 'PRINCIPAL', :address, :phone, :rector, :area_type)";

                $stmtBranch = $this->conn->prepare($queryBranch);
                $stmtBranch->bindParam(":school_id", $school_id);
                $stmtBranch->bindParam(":pae_id", $pae_id);
                $stmtBranch->bindParam(":dane_code", $data['dane_code']);
                $stmtBranch->bindParam(":address", $data['address']);
                $stmtBranch->bindParam(":phone", $data['phone']);
                $stmtBranch->bindParam(":rector", $data['rector']);
                $stmtBranch->bindParam(":area_type", $data['area_type']);
                $stmtBranch->execute();

                $this->conn->commit();
                http_response_code(201);
                echo json_encode(["message" => "Colegio y sede principal creados exitosamente.", "id" => $school_id]);
            } else {
                throw new Exception("Error al insertar colegio.");
            }
        } catch (Exception $e) {
            $this->conn->rollBack();
            http_response_code(500);
            echo json_encode(["message" => "Error al crear colegio: " . $e->getMessage()]);
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

        $data = $_POST;
        // Enforce casing
        if (isset($data['name'])) $data['name'] = mb_strtoupper($data['name'], 'UTF-8');
        if (isset($data['rector'])) $data['rector'] = mb_strtoupper($data['rector'], 'UTF-8');
        if (isset($data['email'])) $data['email'] = strtolower($data['email']);

        if (empty($data)) {
            $data = (array) json_decode(file_get_contents("php://input"));
        }

        // Verify ownership
        $check = $this->conn->prepare("SELECT id FROM schools WHERE id = :id AND pae_id = :pae_id");
        $check->execute(['id' => $id, 'pae_id' => $pae_id]);
        if ($check->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(["message" => "Colegio no encontrado."]);
            return;
        }

        $query = "UPDATE " . $this->table_name . " 
                  SET dane_code = :dane_code, name = :name, rector = :rector, address = :address, phone = :phone, 
                      email = :email, department = :department, municipality = :municipality, 
                      school_type = :school_type, area_type = :area_type";

        $logo_path = $this->uploadLogo($_FILES['logo'] ?? null);
        if ($logo_path) {
            $query .= ", logo_path = :logo_path";
        }

        $query .= " WHERE id = :id AND pae_id = :pae_id";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":dane_code", $data['dane_code']);
        $stmt->bindParam(":name", $data['name']);
        $stmt->bindParam(":rector", $data['rector']);
        $stmt->bindParam(":address", $data['address']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":department", $data['department']);
        $stmt->bindParam(":municipality", $data['municipality']);
        $stmt->bindParam(":school_type", $data['school_type']);
        $stmt->bindParam(":area_type", $data['area_type']);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pae_id", $pae_id);

        if ($logo_path) {
            $stmt->bindParam(":logo_path", $logo_path);
        }

        if ($stmt->execute()) {
            echo json_encode(["message" => "Colegio actualizado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al actualizar colegio."]);
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
            echo json_encode(["message" => "Colegio eliminado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al eliminar colegio."]);
        }
    }

    private function uploadLogo($file)
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        $uploadDir = Config::getUploadDir();
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'school_' . uniqid() . '.' . $ext;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'assets/img/logos/' . $filename;
        }

        return null;
    }
}
