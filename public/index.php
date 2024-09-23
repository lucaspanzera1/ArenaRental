<?php

require_once '../controller/AuthController.php';

$authController = new AuthController();
if (isset($_GET['action']) && $_GET['action'] === 'login') {
    $authController->login();
}

?>

