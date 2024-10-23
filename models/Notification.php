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
            
            // Adicione este debug para verificar o resultado
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            var_dump($usuario_id); // Verificar o ID do usuário
            var_dump($result);     // Verificar o resultado da consulta
            return $result;
        } catch (PDOException $e) {
            echo "Erro: " . $e->getMessage(); // Debug do erro
            return [];
        }
    }

    public function marcarComoLida($notificacao_id) {
        try {
            $query = "UPDATE notificacoes SET lida = TRUE WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            return $stmt->execute([':id' => $notificacao_id]);
        } catch (PDOException $e) {
            return false;
        }
    }
}