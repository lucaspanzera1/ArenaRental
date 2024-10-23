<?php
require_once 'Conexao.php';

class Notification {
    private $conn;

    public function __construct() {
        $this->conn = Conexao::getInstance();
    }

    public function criarNotificacao($destinatario_id, $remetente_id, $tipo, $mensagem, $reserva_id) {
        try {
            $query = "INSERT INTO notificacoes (destinatario_id, remetente_id, tipo, mensagem, reserva_id) 
                      VALUES (:destinatario, :remetente, :tipo, :mensagem, :reserva)";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([
                ':destinatario' => $destinatario_id,
                ':remetente' => $remetente_id,
                ':tipo' => $tipo,
                ':mensagem' => $mensagem,
                ':reserva' => $reserva_id
            ]);
            return true;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function buscarNotificacoesUsuario($usuario_id) {
        try {
            $query = "SELECT n.*, c.nome as remetente_nome, r.data, r.horario_inicio 
                      FROM notificacoes n 
                      JOIN cliente c ON n.remetente_id = c.id 
                      LEFT JOIN reservas r ON n.reserva_id = r.id 
                      WHERE n.destinatario_id = :usuario_id 
                      ORDER BY n.data_criacao DESC";
            
            $stmt = $this->conn->prepare($query);
            $stmt->execute([':usuario_id' => $usuario_id]);
            
            // Removi os var_dump e mantive apenas o retorno
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Modificando para não exibir o erro diretamente
            error_log("Erro ao buscar notificações: " . $e->getMessage());
            return [];
        }
    }
    public function marcarComoLida($notificacao_id) {
        try {
            // Adicionando log para debug
            error_log("Executando marcarComoLida para notificação ID: $notificacao_id");
            
            $query = "UPDATE notificacoes SET lida = TRUE WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $result = $stmt->execute([':id' => $notificacao_id]);
            
            // Log do resultado
            error_log("Resultado da atualização: " . ($result ? 'sucesso' : 'falha'));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro no marcarComoLida: " . $e->getMessage());
            return false;
        }
    }
}