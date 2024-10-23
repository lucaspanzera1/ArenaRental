

class NotificationSystem {
    constructor() {
        this.checkInterval = 30000; // 30 segundos
        this.init();
    }

    init() {
        // Checa imediatamente ao iniciar
        this.checkNotifications();
        // Configura o intervalo de checagem
        setInterval(() => this.checkNotifications(), this.checkInterval);
        
        // Para debug
        console.log('Sistema de notificações iniciado');
    }

    async checkNotifications() {
        try {
            console.log('Verificando notificações...'); // Debug
            const response = await fetch('/api/check_notifications.php');
            const data = await response.json();
            
            console.log('Resposta recebida:', data); // Debug
            
            if (data.notifications && data.notifications.length > 0) {
                this.updateNotificationsUI(data.notifications);
            }
        } catch (error) {
            console.error('Erro ao verificar notificações:', error);
        }
    }

    updateNotificationsUI(notifications) {
        const container = document.querySelector('.notifications-list');
        if (!container) return;

        container.innerHTML = ''; // Limpa o container

        notifications.forEach(notif => {
            const notificationHTML = `
                <div class="notification-item ${notif.lida ? '' : 'unread'}" data-id="${notif.id}">
                    <div class="notification-content">
                        ${notif.mensagem}
                    </div>
                    <div class="notification-time">
                        ${this.formatDate(notif.data_criacao)}
                    </div>
                </div>
            `;
            container.innerHTML += notificationHTML;
        });

        // Atualiza o contador
        const count = notifications.filter(n => !n.lida).length;
        this.updateNotificationCount(count);
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
}

// Inicia o sistema de notificações quando a página carregar
document.addEventListener('DOMContentLoaded', () => {
    console.log('DOM carregado, iniciando sistema de notificações'); // Debug
    new NotificationSystem();
});