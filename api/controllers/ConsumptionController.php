<?php

namespace Controllers;

use Config\Database;
use Utils\JWT;
use PDO;
use Exception;

class ConsumptionController
{
    private $conn;
    private $table_name = "daily_consumptions";

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

    private function getAuthData()
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
                // Return full data payload
                return isset($decoded['data']) ? $decoded['data'] : (isset($decoded->data) ? (array) $decoded->data : null);
            } catch (Exception $e) {
                return null;
            }
        }
        return null;
    }

    /**
     * POST /api/consumptions
     * Records a new consumption
     */
    public function store()
    {
        $auth = $this->getAuthData();
        if (!$auth || empty($auth['pae_id'])) {
            http_response_code(401);
            echo json_encode(["message" => "No autorizado."]);
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);

        if (empty($data['beneficiary_id']) || empty($data['meal_type']) || empty($data['branch_id'])) {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos (Beneficiario, Tipo Comida, Sede)."]);
            return;
        }

        $date = date('Y-m-d'); // Always record for TODAY by server time

        // 1. Verify Beneficiary exists in this PAE and Branch
        // Note: We allow consuming in a different branch? Usually yes, PAE is the boundary.
        // But for strict control, maybe warn? For now, just check PAE.
        $stmtCheck = $this->conn->prepare("SELECT id, first_name, last_name1 FROM beneficiaries WHERE id = ? AND pae_id = ?");
        $stmtCheck->execute([$data['beneficiary_id'], $auth['pae_id']]);
        $beneficiary = $stmtCheck->fetch(PDO::FETCH_ASSOC);

        if (!$beneficiary) {
            http_response_code(404);
            echo json_encode(["message" => "Beneficiario no encontrado en este programa."]);
            return;
        }

        // 2. Check for Duplicate
        $stmtDup = $this->conn->prepare("SELECT id, created_at FROM daily_consumptions WHERE beneficiary_id = ? AND date = ? AND meal_type = ?");
        $stmtDup->execute([$data['beneficiary_id'], $date, $data['meal_type']]);
        $existing = $stmtDup->fetch(PDO::FETCH_ASSOC);

        if ($existing) {
            http_response_code(409); // Conflict
            $time = date('H:i', strtotime($existing['created_at']));
            echo json_encode([
                "message" => "El beneficiario ya recibió {$data['meal_type']} hoy a las {$time}.",
                "duplicate" => true
            ]);
            return;
        }

        // 3. Insert
        try {
            $query = "INSERT INTO daily_consumptions (pae_id, branch_id, beneficiary_id, date, meal_type, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                $auth['pae_id'],
                $data['branch_id'],
                $data['beneficiary_id'],
                $date,
                $data['meal_type']
            ]);

            echo json_encode([
                "success" => true,
                "message" => "Entrega registrada correctamente",
                "beneficiary_name" => $beneficiary['first_name'] . ' ' . $beneficiary['last_name1']
            ]);

        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(["message" => "Error al guardar el consumo: " . $e->getMessage()]);
        }
    }

    /**
     * GET /api/consumptions/stats
     * Returns stats for the current user/branch for today
     */
    public function stats()
    {
        $auth = $this->getAuthData();
        if (!$auth) {
            http_response_code(401);
            echo json_encode(["message" => "No autorizado."]);
            return;
        }

        $branch_id = $_GET['branch_id'] ?? null;
        $date = date('Y-m-d');

        $query = "SELECT COUNT(*) as total FROM daily_consumptions WHERE pae_id = ? AND date = ?";
        $params = [$auth['pae_id'], $date];

        if ($branch_id) {
            $query .= " AND branch_id = ?";
            $params[] = $branch_id;
        }

        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Calculate progress (Mocked for now or complex query)
        // For simple progress, let's assume we want % of total active beneficiaries in that branch
        $progress = 0;
        if ($branch_id) {
            $stmtCount = $this->conn->prepare("SELECT COUNT(*) as total FROM beneficiaries WHERE branch_id = ? AND status = 'active'");
            $stmtCount->execute([$branch_id]);
            $totalBen = $stmtCount->fetch(PDO::FETCH_ASSOC)['total'];

            if ($totalBen > 0) {
                $progress = round(($result['total'] / $totalBen) * 100);
            }
        }

        echo json_encode([
            "success" => true,
            "today_count" => $result['total'],
            "progress" => $progress
        ]);
    }
}
