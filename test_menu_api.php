<?php
require_once 'api/config/Database.php';
require_once 'api/controllers/AuthController.php';

// Mocking the environment if necessary, but AuthController->getMenu() seems independent
$auth = new \Controllers\AuthController();
$auth->getMenu();
