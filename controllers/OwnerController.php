<?php
include '../models/Conexao.php';
include '../models/Owner.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Registro da quadra
    if ($action === 'registerQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $ownerId = $clientData['id'];

        // Captura os dados do formulário
        $nome = $_POST['nome'];
        $esporte = $_POST['esporte'];
        $quadrac = $_POST['quadrac'] === 'coberta' ? 1 : 0;
        $rentalType = $_POST['rental-type'];
        $price = $_POST['priceInput'];

        // Registro da quadra e captura do ID
        $quadraId = Owner::registerQuadra($ownerId, $nome, $esporte, $quadrac, $rentalType, $price);

        if ($quadraId) {
            $_SESSION['quadra_id'] = $quadraId;
            echo "<script type=\"text/javascript\">
            alert(\"Quadra registrada com sucesso!\");
            window.location.href = '../views/owner/imagem.quadra.php?id=" . $quadraId . "';
            </script>";
            exit();
        } else {
            echo "<script type=\"text/javascript\">
            alert(\"Erro ao registrar a quadra. Por favor, tente novamente.\");
            window.location.href = '../views/owner/register.quadra.php';
            </script>";
            exit();
        }
    }
    
    // Upload da foto da quadra
    if ($action === 'FotoQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $quadraId = $_SESSION['quadra_id'];
        $owner = Owner::getOwnerById($clientData['id']);

        if ($owner) {
            $origem = isset($_POST['origem']) ? $_POST['origem'] : null;
            $owner->uploadFotoPerfilOwner($quadraId, $origem);

            echo "<script type=\"text/javascript\">
            alert(\"Imagem da quadra enviada com sucesso!\");
            window.location.href = '../views/owner/horarios.quadra.php?id=" . $quadraId . "';
            </script>";
            exit();
        }
    }
    
    // Registro dos horários da quadra
    if ($action === 'registrarHorarios' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $ownerId = $clientData['id'];
        $quadraId = $_POST['quadra_id']; // Assumindo que este campo está no formulário

        $owner = Owner::getOwnerById($ownerId);

        if ($owner) {
            $dias = $_POST['dias'] ?? [];
            $horarios = [];

            foreach ($dias as $dia) {
                $horarios[$dia] = [
                    'inicio' => $_POST["{$dia}_inicio"],
                    'fim' => $_POST["{$dia}_fim"],
                    'intervalo_inicio' => $_POST["{$dia}_intervalo_inicio"],
                    'intervalo_fim' => $_POST["{$dia}_intervalo_fim"],
                ];
            }

            try {
                $owner->salvarHorarios($quadraId, $horarios);
                echo "<script type=\"text/javascript\">
                alert(\"Horários registrados com sucesso!\");
                window.location.href = '../views/owner/editar_quadra.php?id=" . $quadraId . "';
                </script>";
                exit();
            } catch (Exception $e) {
                echo "<script type=\"text/javascript\">
                alert(\"Erro ao registrar os horários: " . $e->getMessage() . "\");
                window.location.href = '../views/owner/horarios.quadra.php?id=" . $quadraId . "';
                </script>";
                exit();
            }
        } else {
            echo "<script type=\"text/javascript\">
            alert(\"Erro: Proprietário não encontrado.\");
            window.location.href = '../views/owner/dashboard.php';
            </script>";
            exit();
        }
    }
    if ($action === 'updateQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $quadraId = $_POST['quadra_id']; // Assumindo que o ID da quadra está no formulário
        $nome = $_POST['nome'];
        $esporte = $_POST['esporte'];
        $quadrac = $_POST['quadrac'] === 'coberta' ? 1 : 0;
        $rentalType = $_POST['rental-type'];
        $price = $_POST['priceInput'];
    
        // Atualizar a quadra
        $sucesso = Owner::updateQuadra($quadraId, $nome, $esporte, $quadrac, $rentalType, $price);
    
        if ($sucesso) {
            echo "<script type=\"text/javascript\">
            alert(\"Quadra atualizada com sucesso!\");
            window.location.href = '../views/owner/gerenciador.php';
            </script>";
        } else {
            echo "<script type=\"text/javascript\">
            alert(\"Erro ao atualizar a quadra. Por favor, tente novamente.\");
            window.location.href = '../views/owner/editar_quadra.php?id=" . $quadraId . "';
            </script>";
        }
        exit();
    }
    if ($_SERVER["REQUEST_METHOD"] === "POST") {
        if ($action === 'reservar') {
            $quadra_id = $_POST['quadra_id'];
            $data = $_POST['data'];
            $horario_inicio = $_POST['horario_inicio'];
            $horario_fim = $_POST['horario_fim'];
    
            // Chama a função reservarQuadra na classe Owner
            $mensagem = Owner::reservarQuadra($quadra_id, $data, $horario_inicio, $horario_fim);
    
            // Armazena a mensagem de sucesso ou erro na sessão
            $_SESSION['mensagem'] = $mensagem;
    
            // Redireciona para a página onde a mensagem será exibida
            header("Location: ../views/owner/reservas.php?id=" . $quadra_id . "&data=" .$data);
            exit();
        }
        if ($action === 'cancelarReserva') {
            $clientData = $_SESSION['client'];
            $owner = Owner::getOwnerById($clientData['id']);

            $reservaId = $_POST['reserva_id'];
        
            $mensagem = $owner->cancelarReserva($reservaId);
        
            $_SESSION['mensagem'] = $mensagem;
        
            header("Location: ../views/owner/gerenciador.php");
            exit();
        }
        if ($action === 'confirmarReserva') {
            $clientData = $_SESSION['client'];
            $owner = Owner::getOwnerById($clientData['id']);
            
            $reservaId = $_POST['reserva_id'];
            
            $mensagem = $owner->confirmarReserva($reservaId);
            
            $_SESSION['mensagem'] = $mensagem;
            
            
            exit();
        }

    if ($action === 'UpdateFotoQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $quadraId = $_POST['quadra_id'];
        $owner = Owner::getOwnerById($clientData['id']);

        if ($owner) {
            $origem = isset($_POST['origem']) ? $_POST['origem'] : null;
            $owner->uploadFotoPerfilOwner($quadraId, $origem);

            echo "<script type=\"text/javascript\">
            alert(\"Imagem da quadra enviada com sucesso!\");
             window.location.href = '../views/owner/gerenciador.php';
            </script>";
            exit();
        }
    }
}

}