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
        // Ao invés de criar um novo objeto Owner, vamos usar um método estático
        $quadraId = Owner::registerQuadra($ownerId, $nome, $esporte, $quadrac, $rentalType, $price);

        if ($quadraId) {
            // Armazena o ID da quadra na sessão
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
    if ($action === 'FotoQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $quadraId = $_SESSION['quadra_id']; // Captura o ID da quadra armazenado na sessão
        $owner = Owner::getOwnerById($clientData['id']);

        if ($owner) {
            $origem = isset($_POST['origem']) ? $_POST['origem'] : null;
            // Associa a foto à quadra usando o ID da quadra
            $owner->uploadFotoPerfilOwner($quadraId, $origem);

            echo "<script type=\"text/javascript\">
            alert(\"Imagem da quadra enviada com sucesso!\");
            window.location.href = '../views/owner/horarios.quadra.php?id=" . $quadraId . "';
            </script>";
            exit();
        }
    }
}
