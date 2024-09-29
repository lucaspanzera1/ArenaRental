<?php
include '../models/Conexao.php';
include '../models/Quadra.php';
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
                window.location.href = '../views/owner/quadra_detalhes.php?id=" . $quadraId . "';
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
}