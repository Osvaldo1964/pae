<?php

namespace Controllers;

use Config\Database;
use Config\Config;
use Utils\JWT;
use PDO;
use Exception;

class BeneficiaryController extends BaseController
{
    private $table_name = "beneficiaries";

    /**
     * List all beneficiaries for the current PAE program
     */
    public function index()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            return $this->sendError("Acceso denegado.", 403);
        }

        $query = "SELECT b.*, br.name as branch_name, s.name as school_name, br.school_id as school_id, 
                         dt.name as document_type_name, eg.name as ethnic_group_name,
                         rt.name as ration_type_name
                  FROM " . $this->table_name . " b
                  LEFT JOIN school_branches br ON b.branch_id = br.id
                  LEFT JOIN schools s ON br.school_id = s.id
                  LEFT JOIN document_types dt ON b.document_type_id = dt.id
                  LEFT JOIN ethnic_groups eg ON b.ethnic_group_id = eg.id
                  LEFT JOIN pae_ration_types rt ON b.ration_type_id = rt.id
                  WHERE b.pae_id = :pae_id 
                  ORDER BY b.last_name1 ASC, b.first_name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":pae_id", $pae_id);
        $stmt->execute();

        $beneficiaries = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $this->sendResponse($beneficiaries);
    }

    /**
     * Create a new beneficiary
     */
    public function create()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            return $this->sendError("Acceso denegado.", 403);
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['document_number']) || empty($data['first_name']) || empty($data['last_name1']) || empty($data['branch_id'])) {
            return $this->sendError("Campos obligatorios faltantes (Documento, Nombres, Apellidos, Sede).");
        }

        // Check for duplicates within the same PAE program
        $check_query = "SELECT id FROM " . $this->table_name . " WHERE document_number = :doc AND pae_id = :pae";
        $check_stmt = $this->conn->prepare($check_query);
        $check_stmt->bindParam(":doc", $data['document_number']);
        $check_stmt->bindParam(":pae", $pae_id);
        $check_stmt->execute();
        if ($check_stmt->rowCount() > 0) {
            return $this->sendError("Ya existe un beneficiario registrado con este número de documento en su programa.");
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
                   simat_id, shift, grade, group_name, status, enrollment_date, modality, ration_type, ration_type_id, 
                   medical_restrictions, observations, data_authorization, is_overage) 
                  VALUES 
                  (:pae_id, :branch_id, :document_type_id, :document_number, :first_name, :second_name, :last_name1, :last_name2, 
                   :birth_date, :gender, :ethnic_group_id, :sisben_category, :disability_type, :is_victim, :is_migrant, 
                   :address, :phone, :email, :guardian_name, :guardian_phone, :guardian_relationship, 
                   :simat_id, :shift, :grade, :group_name, :status, :enrollment_date, :modality, :ration_type, :ration_type_id, 
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
        $stmt->bindParam(":ration_type_id", $data['ration_type_id']);
        $stmt->bindParam(":medical_restrictions", $data['medical_restrictions']);
        $stmt->bindParam(":observations", $data['observations']);
        $stmt->bindParam(":data_authorization", $data['data_authorization'], PDO::PARAM_BOOL);
        $stmt->bindParam(":is_overage", $data['is_overage'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->sendResponse(["message" => "Beneficiario registrado exitosamente.", "id" => $this->conn->lastInsertId()]);
        } else {
            $this->sendError("Error al registrar beneficiario.", 500);
        }
    }

    /**
     * Update an existing beneficiary
     */
    public function update($id)
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            return $this->sendError("Acceso denegado.", 403);
        }

        $data = json_decode(file_get_contents("php://input"), true);

        // Security check: ensure beneficiary belongs to the current PAE program
        $security_query = "SELECT id FROM " . $this->table_name . " WHERE id = :id AND pae_id = :pae_id";
        $security_stmt = $this->conn->prepare($security_query);
        $security_stmt->bindParam(":id", $id);
        $security_stmt->bindParam(":pae_id", $pae_id);
        $security_stmt->execute();
        if ($security_stmt->rowCount() == 0) {
            return $this->sendError("Beneficiario no encontrado o acceso denegado.", 404);
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
                  ration_type_id = :ration_type_id, 
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
        $stmt->bindParam(":ration_type_id", $data['ration_type_id']);
        $stmt->bindParam(":medical_restrictions", $data['medical_restrictions']);
        $stmt->bindParam(":observations", $data['observations']);
        $stmt->bindParam(":data_authorization", $data['data_authorization'], PDO::PARAM_BOOL);
        $stmt->bindParam(":is_overage", $data['is_overage'], PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->sendResponse(["message" => "Beneficiario actualizado exitosamente."]);
        } else {
            $this->sendError("Error al actualizar beneficiario.", 500);
        }
    }

    /**
     * Delete a beneficiary
     */
    public function delete($id)
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            return $this->sendError("Acceso denegado.", 403);
        }

        $query = "DELETE FROM " . $this->table_name . " WHERE id = :id AND pae_id = :pae_id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(":id", $id);
        $stmt->bindParam(":pae_id", $pae_id);

        if ($stmt->execute()) {
            $this->sendResponse(["message" => "Beneficiario eliminado exitosamente."]);
        } else {
            $this->sendError("Error al eliminar beneficiario.", 500);
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
        $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Get ethnic groups
     */
    public function getEthnicGroups()
    {
        $query = "SELECT * FROM ethnic_groups ORDER BY name ASC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        $this->sendResponse($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    /**
     * Fetch list for printing (Filtered by Branch/Grade)
     */
    public function printList()
    {
        $pae_id = $this->getPaeIdFromToken();
        if (!$pae_id) {
            return $this->sendError("Acceso denegado.", 403);
        }

        $branch_id = $_GET['branch_id'] ?? null;
        $grade = $_GET['grade'] ?? null;
        $group_name = $_GET['group_name'] ?? null;

        if (!$branch_id) {
            return $this->sendError("La sede es requerida.", 400);
        }

        $query = "SELECT b.document_number, b.first_name, b.second_name, b.last_name1, b.last_name2, 
                         b.grade, b.group_name
                  FROM " . $this->table_name . " b
                  WHERE b.pae_id = :pae_id AND b.branch_id = :branch_id";

        $params = [
            ":pae_id" => $pae_id,
            ":branch_id" => $branch_id
        ];

        if ($grade) {
            $query .= " AND b.grade = :grade";
            $params[":grade"] = $grade;
        }

        if ($group_name) {
            $query .= " AND b.group_name = :group_name";
            $params[":group_name"] = $group_name;
        }

        $query .= " ORDER BY b.group_name ASC, b.last_name1 ASC, b.first_name ASC";

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);

        $list = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Fetch branch info for header
        $stmtBranch = $this->conn->prepare("SELECT b.name as branch_name, s.name as school_name 
                                            FROM school_branches b
                                            JOIN schools s ON b.school_id = s.id 
                                            WHERE b.id = ?");
        $stmtBranch->execute([$branch_id]);
        $branchInfo = $stmtBranch->fetch(PDO::FETCH_ASSOC);

        $this->sendResponse([
            "success" => true,
            "branch" => $branchInfo,
            "data" => $list
        ]);
    }
}
