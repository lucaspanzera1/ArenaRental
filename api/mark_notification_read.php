<?php
session_start();
require_once '../models/Notification.php';
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_POST['notification_id'])) {
    echo json_encode(['error' => 'ID da notificação não fornecido']);
    exit;
}

try {
    $notification = new Notification();
    $success = $notification->marcarComoLida($_POST['notification_id']);
    
    echo json_encode([
        'success' => $success,
        'message' => $success ? 'Notificação marcada como lida' : 'Erro ao marcar notificação'
    ]);
} catch (Exception $e) {
    echo json_encode([
        'error' => 'Erro ao processar requisição',
        'message' => $e->getMessage()
    ]);
}