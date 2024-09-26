<?php
include '../models/Conexao.php';
include '../models/Quadra.php';
include '../models/Owner.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if ($action === 'registerQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $ownerId = $clientData['id'];

        // Captura os dados
        $nome = $_POST['nome'];
        $esporte = $_POST['esporte'];
        $quadrac = $_POST['quadrac'] === 'coberta' ? 1 : 0;
        $rentalType = $_POST['rental-type']; // Captura do tipo de aluguel
        $price = $_POST['priceInput'];

        // Registro da quadra
        $quadra = new Quadra();
        $quadra->registerQuadra($ownerId, $nome, $esporte, $quadrac, $rentalType, $price);

        echo "<script type=\"text/javascript\">
        alert(\"Quadra registrada!\");
            </script>";
        header("refresh: 0.4; url=../views/owner/imagem.quadra.php");
        exit();
    }
    
    if ($action === 'FotoQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $owner = Owner::getOwnerById($clientData['id']); // Obtém a instância do Owner

        if ($owner) {
            $origem = isset($_POST['origem']) ? $_POST['origem'] : null;
            $owner->uploadFotoPerfilOwner($origem); // Chama a função de upload
            
            // Redireciona após o upload bem-sucedido
            header("Location: ../views/owner/gerenciador.php");
            exit();
        }
    }
}
