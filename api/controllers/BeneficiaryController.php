<?php

namespace Controllers;

use Config\Database;
use Config\Config;
use Utils\JWT;
use PDO;
use Exception;

class BeneficiaryController
{
    private $conn;
    private $table_name = "beneficiaries";

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

    /**
     * List all beneficiaries for the current PAE program
     */
    public function index()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $query = "SELECT b.*, br.name as branch_name, s.name as school_name, br.school_id as school_id, 
                         dt.name as document_type_name, eg.name as ethnic_group_name
                  FROM " . $this->table_name . " b
                  LEFT JOIN school_branches br ON b.branch_id = br.id
                  LEFT JOIN schools s ON br.school_id = s.id
                  LEFT JOIN document_types dt ON b.document_type_id = dt.id
                  LEFT JOIN ethnic_groups eg ON b.ethnic_group_id = eg.id
                  WHERE b.pae_id = :pae_id 
                  ORDER BY b.last_name1 ASC, b.first_name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->execute();

        $beneficiaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode($beneficiaries);
    }

    /**
     * Create a new beneficiary
     */
    public function create()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['document_number']) || empty($data['first_name']) || empty($data['last_name1']) || empty($data['branch_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "Campos obligatorios faltantes (Documento, Nombres, Apellidos, Sede)."]);
            return;
        }

        // Check for duplicates within the same PAE program
        $check_query = "SELECT id FROM " . $this->table_name . " WHERE document_number = :doc AND pae_id = :pae";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":doc", $data['document_number']);
        $check_stmt->bindParam(":pae", $pae_id);
        $check_stmt->execute();
        if ($check_stmt->rowCount() > 0) {
            http_response_code(400);
            echo json_encode(["message" => "Ya existe un beneficiario registrado con este número de documento en su programa."]);
            return;
        }

        // Process data (Standard Casing)
        $data['first_name'] = mb_strtoupper($data['first_name'], 'UTF-8');
        $data['second_name'] = mb_strtoupper($data['second_name'] ?? '', 'UTF-8');
        $data['last_name1'] = mb_strtoupper($data['last_name1'], 'UTF-8');
        $data['last_name2'] = mb_strtoupper($data['last_name2'] ?? '', 'UTF-8');
        $data['email'] = strtolower($data['email'] ?? '');
        $data['guardian_name'] = mb_strtoupper($data['guardian_name'] ?? '', 'UTF-8');

        $query = "INSERT INTO " . $this->table_name . " 
                  (pae_id, branch_id, document_type_id, document_number, first_name, second_name, last_name1, last_name2, 
                   birth_date, gender, ethnic_group_id, sisben_category, disability_type, is_victim, is_migrant, 
                   address, phone, email, guardian_name, guardian_phone, guardian_relationship, 
                   simat_id, shift, grade, group_name, status, enrollment_date, modality, ration_type, 
                   medical_restrictions, observations, data_authorization, is_overage) 
                  VALUES 
                  (:pae_id, :branch_id, :document_type_id, :document_number, :first_name, :second_name, :last_name1, :last_name2, 
                   :birth_date, :gender, :ethnic_group_id, :sisben_category, :disability_type, :is_victim, :is_migrant, 
                   :address, :phone, :email, :guardian_name, :guardian_phone, :guardian_relationship, 
                   :simat_id, :shift, :grade, :group_name, :status, :enrollment_date, :modality, :ration_type, 
                   :medical_restrictions, :observations, :data_authorization, :is_overage)";

        $stmt = $this->conn->prepare($query);

        // Bind params
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->bindParam(":branch_id", $data['branch_id']);
        $stmt->bindParam(":document_type_id", $data['document_type_id']);
        $stmt->bindParam(":document_number", $data['document_number']);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":second_name", $data['second_name']);
        $stmt->bindParam(":last_name1", $data['last_name1']);
        $stmt->bindParam(":last_name2", $data['last_name2']);
        $stmt->bindParam(":birth_date", $data['birth_date']);
        $stmt->bindParam(":gender", $data['gender']);
        $stmt->bindParam(":ethnic_group_id", $data['ethnic_group_id']);
        $stmt->bindParam(":sisben_category", $data['sisben_category']);
        $stmt->bindParam(":disability_type", $data['disability_type']);
        $stmt->bindParam(":is_victim", $data['is_victim'], PDO::PARAM_BOOL);
        $stmt->bindParam(":is_migrant", $data['is_migrant'], PDO::PARAM_BOOL);
        $stmt->bindParam(":address", $data['address']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":guardian_name", $data['guardian_name']);
        $stmt->bindParam(":guardian_phone", $data['guardian_phone']);
        $stmt->bindParam(":guardian_relationship", $data['guardian_relationship']);
        $stmt->bindParam(":simat_id", $data['simat_id']);
        $stmt->bindParam(":shift", $data['shift']);
        $stmt->bindParam(":grade", $data['grade']);
        $stmt->bindParam(":group_name", $data['group_name']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":enrollment_date", $data['enrollment_date']);
        $stmt->bindParam(":modality", $data['modality']);
        $stmt->bindParam(":ration_type", $data['ration_type']);
        $stmt->bindParam(":medical_restrictions", $data['medical_restrictions']);
        $stmt->bindParam(":observations", $data['observations']);
        $stmt->bindParam(":data_authorization", $data['data_authorization'], PDO::PARAM_BOOL);
        $stmt->bindParam(":is_overage", $data['is_overage'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Beneficiario registrado exitosamente.", "id" => $this->conn->lastInsertId()]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al registrar beneficiario."]);
        }
    }

    /**
     * Update an existing beneficiary
     */
    public function update($id)
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            http_response_code(403);
            echo json_encode(["message" => "Acceso denegado."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        // Security check: ensure beneficiary belongs to the current PAE program
        $security_query = "SELECT id FROM " . $this->table_name . " WHERE id = :id AND pae_id = :pae_id";
        $security_stmt = $this->conn->prepare($security_query);
        $security_stmt->bindParam(":id", $id);
        $security_stmt->bindParam(":pae_id", $pae_id);
        $security_stmt->execute();
        if ($security_stmt->rowCount() == 0) {
            http_response_code(404);
            echo json_encode(["message" => "Beneficiario no encontrado o acceso denegado."]);
            return;
        }

        // Process data
        if (isset($data['first_name']))
            $data['first_name'] = mb_strtoupper($data['first_name'], 'UTF-8');
        if (isset($data['second_name']))
            $data['second_name'] = mb_strtoupper($data['second_name'], 'UTF-8');
        if (isset($data['last_name1']))
            $data['last_name1'] = mb_strtoupper($data['last_name1'], 'UTF-8');
        if (isset($data['last_name2']))
            $data['last_name2'] = mb_strtoupper($data['last_name2'], 'UTF-8');
        if (isset($data['email']))
            $data['email'] = strtolower($data['email']);
        if (isset($data['guardian_name']))
            $data['guardian_name'] = mb_strtoupper($data['guardian_name'], 'UTF-8');

        $query = "UPDATE " . $this->table_name . " SET 
                  branch_id = :branch_id, 
                  document_type_id = :document_type_id, 
                  document_number = :document_number, 
                  first_name = :first_name, 
                  second_name = :second_name, 
                  last_name1 = :last_name1, 
                  last_name2 = :last_name2, 
                  birth_date = :birth_date, 
                  gender = :gender, 
                  ethnic_group_id = :ethnic_group_id, 
                  sisben_category = :sisben_category, 
                  disability_type = :disability_type, 
                  is_victim = :is_victim, 
                  is_migrant = :is_migrant, 
                  address = :address, 
                  phone = :phone, 
                  email = :email, 
                  guardian_name = :guardian_name, 
                  guardian_phone = :guardian_phone, 
                  guardian_relationship = :guardian_relationship, 
                  simat_id = :simat_id, 
                  shift = :shift, 
                  grade = :grade, 
                  group_name = :group_name, 
                  status = :status, 
                  enrollment_date = :enrollment_date, 
                  modality = :modality, 
                  ration_type = :ration_type, 
                  medical_restrictions = :medical_restrictions, 
                  observations = :observations, 
                  data_authorization = :data_authorization,
                  is_overage = :is_overage
                  WHERE id = :id AND pae_id = :pae_id";

        $stmt = $this->conn->prepare($query);

        // Bind params...
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":branch_id", $data['branch_id']);
        $stmt->bindParam(":document_type_id", $data['document_type_id']);
        $stmt->bindParam(":document_number", $data['document_number']);
        $stmt->bindParam(":first_name", $data['first_name']);
        $stmt->bindParam(":second_name", $data['second_name']);
        $stmt->bindParam(":last_name1", $data['last_name1']);
        $stmt->bindParam(":last_name2", $data['last_name2']);
        $stmt->bindParam(":birth_date", $data['birth_date']);
        $stmt->bindParam(":gender", $data['gender']);
        $stmt->bindParam(":ethnic_group_id", $data['ethnic_group_id']);
        $stmt->bindParam(":sisben_category", $data['sisben_category']);
        $stmt->bindParam(":disability_type", $data['disability_type']);
        $stmt->bindParam(":is_victim", $data['is_victim'], PDO::PARAM_BOOL);
        $stmt->bindParam(":is_migrant", $data['is_migrant'], PDO::PARAM_BOOL);
        $stmt->bindParam(":address", $data['address']);
        $stmt->bindParam(":phone", $data['phone']);
        $stmt->bindParam(":email", $data['email']);
        $stmt->bindParam(":guardian_name", $data['guardian_name']);
        $stmt->bindParam(":guardian_phone", $data['guardian_phone']);
        $stmt->bindParam(":guardian_relationship", $data['guardian_relationship']);
        $stmt->bindParam(":simat_id", $data['simat_id']);
        $stmt->bindParam(":shift", $data['shift']);
        $stmt->bindParam(":grade", $data['grade']);
        $stmt->bindParam(":group_name", $data['group_name']);
        $stmt->bindParam(":status", $data['status']);
        $stmt->bindParam(":enrollment_date", $data['enrollment_date']);
        $stmt->bindParam(":modality", $data['modality']);
        $stmt->bindParam(":ration_type", $data['ration_type']);
        $stmt->bindParam(":medical_restrictions", $data['medical_restrictions']);
        $stmt->bindParam(":observations", $data['observations']);
        $stmt->bindParam(":data_authorization", $data['data_authorization'], PDO::PARAM_BOOL);
        $stmt->bindParam(":is_overage", $data['is_overage'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            echo json_encode(["message" => "Beneficiario actualizado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al actualizar beneficiario."]);
        }
    }

    /**
     * Delete a beneficiary
     */
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
            echo json_encode(["message" => "Beneficiario eliminado exitosamente."]);
        } else {
            http_response_code(500);
            echo json_encode(["message" => "Error al eliminar beneficiario."]);
        }
    }

    /**
     * Get document types
     */
    public function getDocumentTypes()
    {
        $query = "SELECT * FROM document_types ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get ethnic groups
     */
    public function getEthnicGroups()
    {
        $query = "SELECT * FROM ethnic_groups ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
}
