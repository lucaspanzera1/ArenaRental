<?php
// check_notifications.php
session_start();
require_once '../models/Notification.php';
header('Content-Type: application/json');

// Para debug
error_log('check_notifications.php foi chamado');

if (!isset($_SESSION['client']['id'])) {
    error_log('Usuário não está logado');
    echo json_encode(['error' => 'Usuário não autenticado']);
    exit;
}

try {
    $notification = new Notification();
    // Removido o parêntese extra que estava aqui
    $notificacoes = $notification->buscarNotificacoesUsuario($_SESSION['client']['id']);
    
    // Para debug
    error_log('Notificações encontradas: ' . count($notificacoes));
    
    echo json_encode([
        'success' => true,
        'notifications' => $notificacoes
    ]);
} catch (Exception $e) {
    error_log('Erro ao buscar notificações: ' . $e->getMessage());
    echo json_encode([
        'error' => 'Erro ao buscar notificações',
        'message' => $e->getMessage()
    ]);
}
?>