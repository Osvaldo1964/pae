<?php

namespace Controllers;

use Config\Database;

class PublicController
{

    public function submitPQR()
    {
        $data = json_decode(file_get_contents("php://input"));

        if (!empty($data->email) && !empty($data->message) && !empty($data->type)) {
            // Here we would use PHPMailer
            // For Phase 1 MVP, we will simulate success
            // TODO: Install PHPMailer via Composer and implement real sending

            // Mocking logic
            $to = "pae_admin@example.com"; // Configuration value
            $subject = "Nueva PQR Recibida: " . $data->type;

            // Log to local file for debug
            error_log("PQR Received from {$data->email}: {$data->message}");

            http_response_code(200);
            echo json_encode(["message" => "PQR enviada correctamente. Su radicado es #" . time()]);
        } else {
            http_response_code(400);
            echo json_encode(["message" => "Datos incompletos"]);
        }
    }
}
