<?php

require_once '../models/Conexao.php';
require_once '../models/User.php';

// Verifica se uma ação foi solicitada
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
    
} elseif ($_SERVER['REQUEST_METHOD'] === 'GET' && $action === 'getHorariosDisponiveis') {
    // Processar solicitação de horários disponíveis
    $quadra_id = isset($_GET['quadra_id']) ? intval($_GET['quadra_id']) : 0;
    $data = isset($_GET['data']) ? $_GET['data'] : '';

    if ($quadra_id && $data) {
        $horarios = User::getHorariosDisponiveis($quadra_id, $data);
        header('Content-Type: application/json');
        echo json_encode($horarios);
    } else {
        header('HTTP/1.1 400 Bad Request');
        echo json_encode(['error' => 'Parâmetros inválidos']);
    }
    exit;
}

?>