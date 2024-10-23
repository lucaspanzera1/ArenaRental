<?php
require_once '../../models/Notification.php';


if (isset($_SESSION['user_id'])) {
    $notification = new Notification();
    $notificacoes = $notification->buscarNotificacoesUsuario($_SESSION['user_id']);
    
    // Debug para verificar as notificações
    var_dump($notificacoes);
}
?>

<div id="notifications-container" class="notifications-dropdown">
    <div class="notifications-header">
        <span>Notificações</span>
        <?php if (!empty($notificacoes)): ?>
            <span class="notification-count"><?= count($notificacoes) ?></span>
        <?php endif; ?>
    </div>
    <div class="notifications-list">
        <?php if (empty($notificacoes)): ?>
            <div class="no-notifications">
                Nenhuma notificação no momento
            </div>
        <?php else: ?>
            <?php foreach ($notificacoes as $notif): ?>
                <div class="notification-item <?= $notif['lida'] ? '' : 'unread' ?>" 
                     data-id="<?= $notif['id'] ?>">
                    <div class="notification-content">
                        <?= htmlspecialchars($notif['mensagem']) ?>
                    </div>
                    <div class="notification-time">
                        <?= date('d/m/Y H:i', strtotime($notif['data_criacao'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>