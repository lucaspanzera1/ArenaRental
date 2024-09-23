<?php
// Verificar se o usuário está logado
session_start();

header('Location: views/home/index.php');

require_once 'controllers/AuthController.php';

$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $authController = new AuthController();
        $authController->login();
        break;
    case 'delete';
        $ClientController = new ClientController();
        $ClientController->delete();
}

?>
