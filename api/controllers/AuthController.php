<?php

namespace Controllers;

use Config\Database;
use Utils\JWT;
use PDO;

class AuthController
{
    private $db;
    private $conn;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->conn = $this->db->getConnection();
    }

    public function login()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->username) && !empty($data->password)) {
            $query = "SELECT u.id, u.username, u.password_hash, u.full_name, r.id as role_id, r.name as role_name 
                      FROM users u 
                      JOIN roles r ON u.role_id = r.id 
                      WHERE u.username = :username LIMIT 1";

            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(":username", $data->username);
            $stmt->execute();
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($data->password, $user['password_hash'])) {
                // Generate Token
                $payload = [
                    "iss" => "http://localhost/pae",
                    "aud" => "http://localhost/pae",
                    "iat" => time(),
                    "exp" => time() + (60 * 60 * 24), // 24 hours
                    "data" => [
                        "id" => $user['id'],
                        "username" => $user['username'],
                        "role_id" => $user['role_id'],
                        "role_name" => $user['role_name'],
                        "full_name" => $user['full_name']
                    ]
                ];

                $jwt = JWT::encode($payload);

                http_response_code(200);
                echo json_encode([
                    "message" => "Login successful",
                    "token" => $jwt,
                    "user" => [
                        "full_name" => $user['full_name'],
                        "role" => $user['role_name']
                    ]
                ]);
            } else {
                http_response_code(401);
                echo json_encode(["message" => "Invalid credentials"]);
            }
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Incomplete data"]);
        }
    }

    public function getMenu()
    {
        // In a real app, we would validate the JWT Authorization header here first
        // For now, we assume public access or frontend sends role_id (not secure, but good for MVP structure test)
        // Ideally: $user_role = AuthMiddleware::getRole();

        // For Phase 1, let's fetch ALL groups and their modules (Super Admin View)
        // Later we filter by 'module_permissions'

        $sql = "SELECT 
                    mg.id as group_id, mg.name as group_name, mg.icon as group_icon, 
                    m.id as module_id, m.name as module_name, m.route_key, m.icon as module_icon, m.description
                FROM module_groups mg
                JOIN modules m ON m.group_id = mg.id
                ORDER BY mg.order_index, m.id";

        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Structure the flat list into nested JSON
        $menu = [];
        $groups = [];

        foreach ($rows as $row) {
            $gId = $row['group_id'];
            if (!isset($groups[$gId])) {
                $groups[$gId] = [
                    'id' => $gId,
                    'name' => $row['group_name'],
                    'icon' => $row['group_icon'],
                    'modules' => []
                ];
            }

            $groups[$gId]['modules'][] = [
                'id' => $row['module_id'],
                'name' => $row['module_name'],
                'route' => $row['route_key'],
                'icon' => $row['module_icon'],
                'description' => $row['description']
            ];
        }

        http_response_code(200);
        echo json_encode(array_values($groups));
    }
}
