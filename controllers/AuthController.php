<?php

require_once '../models/Conexao.php';
require_once '../models/User.php';

// Verifica se uma ação foi solicitada (login, registro ou registerUser)
$action = isset($_GET['action']) ? $_GET['action'] : '';

$user = new User();

// Verifica qual ação deve ser executada
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if ($action === 'login') {
        // Processar login
        $user->login($_POST);
    } elseif ($action === 'registerInfo') {
        // Processar o primeiro passo do registro
        $user->register($_POST);
    } elseif ($action === 'registerUser') {
        // Processar o segundo passo do registro (username e senha)
        $user->registerAdditionalInfo($_POST);
    }
}

?>
