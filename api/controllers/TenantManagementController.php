<?php

namespace Controllers;

class TenantManagementController
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    /**
     * List all PAE programs (Super Admin only)
     */
    public function listAll($user)
    {
        // Only Super Admin can list all programs
        if ($user['role_id'] != 1) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            return;
        }

        try {
            $stmt = $this->db->prepare("
                SELECT 
                    id, name, entity_name, nit, department, city, email,
                    operator_name, operator_nit, operator_address, operator_phone, operator_email,
                    entity_logo_path, operator_logo_path, created_at
                FROM pae_programs
                ORDER BY created_at DESC
            ");
            $stmt->execute();
            $programs = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            // Validate logo existence
            foreach ($programs as &$pae) {
                $pae['entity_logo_path'] = $this->validateLogoPath($pae['entity_logo_path']);
                $pae['operator_logo_path'] = $this->validateLogoPath($pae['operator_logo_path']);
            }

            echo json_encode($programs);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al obtener programas']);
        }
    }

    /**
     * Update PAE program (Super Admin only)
     */
    public function update($id, $user)
    {
        // Only Super Admin can update programs
        if ($user['role_id'] != 1) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            return;
        }

        try {
            // Get current program data
            $stmt = $this->db->prepare("SELECT entity_logo_path, operator_logo_path FROM pae_programs WHERE id = ?");
            $stmt->execute([$id]);
            $currentProgram = $stmt->fetch(\PDO::FETCH_ASSOC);

            if (!$currentProgram) {
                http_response_code(404);
                echo json_encode(['success' => false, 'message' => 'Programa no encontrado']);
                return;
            }

            // Handle logo uploads
            $entityLogoPath = $currentProgram['entity_logo_path'];
            $operatorLogoPath = $currentProgram['operator_logo_path'];

            if (isset($_FILES['entity_logo']) && $_FILES['entity_logo']['error'] === UPLOAD_ERR_OK) {
                $entityLogoPath = $this->uploadLogo($_FILES['entity_logo'], 'entity');
            }

            if (isset($_FILES['operator_logo']) && $_FILES['operator_logo']['error'] === UPLOAD_ERR_OK) {
                $operatorLogoPath = $this->uploadLogo($_FILES['operator_logo'], 'operator');
            }

            // Update program
            $stmt = $this->db->prepare("
                UPDATE pae_programs SET
                    name = ?,
                    entity_name = ?,
                    nit = ?,
                    department = ?,
                    city = ?,
                    email = ?,
                    operator_name = ?,
                    operator_nit = ?,
                    operator_address = ?,
                    operator_phone = ?,
                    operator_email = ?,
                    entity_logo_path = ?,
                    operator_logo_path = ?
                WHERE id = ?
            ");

            $stmt->execute([
                $_POST['name'],
                $_POST['entity_name'],
                $_POST['nit'],
                $_POST['department'] ?? null,
                $_POST['city'] ?? null,
                $_POST['email'] ?? null,
                $_POST['operator_name'],
                $_POST['operator_nit'],
                $_POST['operator_address'] ?? null,
                $_POST['operator_phone'] ?? null,
                $_POST['operator_email'] ?? null,
                $entityLogoPath,
                $operatorLogoPath,
                $id
            ]);

            echo json_encode(['success' => true, 'message' => 'Programa actualizado exitosamente']);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al actualizar programa: ' . $e->getMessage()]);
        }
    }

    /**
     * Delete PAE program (Super Admin only)
     */
    public function delete($id, $user)
    {
        // Only Super Admin can delete programs
        if ($user['role_id'] != 1) {
            http_response_code(403);
            echo json_encode(['success' => false, 'message' => 'Acceso denegado']);
            return;
        }

        try {
            // Check if program has users
            $stmt = $this->db->prepare("SELECT COUNT(*) as user_count FROM users WHERE pae_id = ?");
            $stmt->execute([$id]);
            $result = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($result['user_count'] > 0) {
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'No se puede eliminar un programa con usuarios asociados']);
                return;
            }

            // Delete program
            $stmt = $this->db->prepare("DELETE FROM pae_programs WHERE id = ?");
            $stmt->execute([$id]);

            echo json_encode(['success' => true, 'message' => 'Programa eliminado exitosamente']);
        } catch (\PDOException $e) {
            http_response_code(500);
            echo json_encode(['success' => false, 'message' => 'Error al eliminar programa']);
        }
    }

    /**
     * Upload logo file
     */
    private function uploadLogo($file, $prefix)
    {
        $uploadDir = __DIR__ . '/../../uploads/logos/';
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = $prefix . '_' . time() . '.' . $extension;
        $filepath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $filepath)) {
            return 'uploads/logos/' . $filename;
        }

        return null;
    }

    private function validateLogoPath($path)
    {
        if (!$path) return null;
        $fullPath = __DIR__ . '/../../' . $path;
        return file_exists($fullPath) ? $path : null;
    }
}
