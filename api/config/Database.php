<?php
namespace Config;

use PDO;
use PDOException;

class Database {
    private $host = "localhost";
    private $db_name = "db-pae";
    private $username = "root";
    private $password = "";
    public $conn;

    // Singleton instance
    private static $instance = null;

    private function __construct() {
        // Private constructor for Singleton
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        $this->conn = null;

        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8mb4");
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch(PDOException $exception) {
            // Emitting JSON error directly in case of connect failure
            header("Content-Type: application/json");
            http_response_code(500);
            echo json_encode(["error" => "Connection error: " . $exception->getMessage()]);
            exit;
        }

        return $this->conn;
    }
}
