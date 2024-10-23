<?php
// mark_notification_read.php
session_start();
require_once '../models/Notification.php';
header('Content-Type: application/json');

error_log('Requisição recebida em mark_notification_read.php');

if (!isset($_SESSION['client']['id'])) {
    error_log('Usuário não autenticado');
    echo json_encode(['success' => false, 'error' => 'Usuário não autenticado']);
    exit;
}

if (!isset($_POST['notification_id'])) {
    error_log('ID da notificação não fornecido');
    echo json_encode(['success' => false, 'error' => 'ID da notificação não fornecido']);
    exit;
}

try {
    $notificationId = $_POST['notification_id'];
    error_log("Tentando marcar notificação $notificationId como lida");
    
    $notification = new Notification();
    $success = $notification->marcarComoLida($notificationId);
    
    error_log("Resultado da marcação: " . ($success ? 'sucesso' : 'falha'));
    
    if ($success) {
        echo json_encode([
            'success' => true,
            'message' => 'Notificação marcada como lida'
        ]);
    } else {
        echo json_encode([
            'success' => false,
            'error' => 'Falha ao marcar notificação como lida'
        ]);
    }
} catch (Exception $e) {
    error_log("Erro ao marcar notificação: " . $e->getMessage());
    echo json_encode([
        'success' => false,
        'error' => 'Erro ao processar requisição',
        'details' => $e->getMessage()
    ]);
}
?>