class NotificationSystem {
    constructor() {
        this.checkInterval = 10000;
        this.init();
        this.bindEvents();
    }

    init() {
        this.checkNotifications();
        setInterval(() => this.checkNotifications(), this.checkInterval);
        console.log('Sistema de notificações iniciado');
    }

    bindEvents() {
        // Melhorando a captura do evento de clique
        const notificationsList = document.querySelector('.notifications-list');
        if (notificationsList) {
            notificationsList.addEventListener('click', (e) => {
                const notificationItem = e.target.closest('.notification-item');
                if (notificationItem) {
                    const notificationId = notificationItem.dataset.id;
                    console.log('Clique na notificação:', notificationId);
                    
                    if (!notificationItem.classList.contains('read')) {
                        this.markAsRead(notificationId, notificationItem);
                    }
                }
            });
        }
    }

    async markAsRead(notificationId, notificationElement) {
        try {
            console.log('Iniciando marcação como lida:', notificationId);
            
            const formData = new FormData();
            formData.append('notification_id', notificationId);

            const response = await fetch('../../api/mark_notification_read.php', {
                method: 'POST',
                body: formData
            });

            const data = await response.json();
            console.log('Resposta do servidor:', data);

            if (data.success) {
                console.log('Notificação marcada como lida com sucesso');
                
                // Atualiza visual da notificação
                notificationElement.classList.remove('unread');
                notificationElement.classList.add('read');

                // Atualiza contador
                const unreadCount = document.querySelectorAll('.notification-item.unread').length;
                this.updateNotificationCount(unreadCount);

                // Força uma verificação das notificações
                this.checkNotifications();
            } else {
                console.error('Erro ao marcar como lida:', data.error || 'Erro desconhecido');
            }
        } catch (error) {
            console.error('Erro ao marcar notificação como lida:', error);
        }
    }

    async checkNotifications() {
        try {
            const response = await fetch('../../api/check_notifications.php');
            const data = await response.json();

            if (data.success && data.notifications) {
                this.updateNotificationsUI(data.notifications);
            }
        } catch (error) {
            console.error('Erro ao verificar notificações:', error);
        }
    }

    updateNotificationsUI(notifications) {
        const container = document.querySelector('.notifications-list');
        if (!container) return;

        if (notifications.length === 0) {
            container.innerHTML = '<div class="no-notifications">Nenhuma notificação no momento</div>';
            this.updateNotificationCount(0);
            return;
        }

        container.innerHTML = notifications.map(notif => `
            <div class="notification-item ${notif.lida ? 'read' : 'unread'}" data-id="${notif.id}">
                <div class="notification-content">
                    ${this.escapeHtml(notif.mensagem)}
                </div>
                <div class="notification-time">
                    ${this.formatDate(notif.data_criacao)}
                </div>
            </div>
        `).join('');

        const unreadCount = notifications.filter(n => !n.lida).length;
        this.updateNotificationCount(unreadCount);
    }

    updateNotificationCount(count) {
        const countElement = document.querySelector('.notification-count');
        if (countElement) {
            countElement.textContent = count;
            countElement.style.display = count > 0 ? 'block' : 'none';
        }
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return date.toLocaleDateString('pt-BR', {
            day: '2-digit',
            month: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    escapeHtml(unsafe) {
        return unsafe
            .replace(/&/g, "&amp;")
            .replace(/</g, "&lt;")
            .replace(/>/g, "&gt;")
            .replace(/"/g, "&quot;")
            .replace(/'/g, "&#039;");
    }
}

// Inicia o sistema
document.addEventListener('DOMContentLoaded', () => {
    console.log('Iniciando sistema de notificações');
    new NotificationSystem();
});