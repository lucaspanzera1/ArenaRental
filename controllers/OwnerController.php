<?php
include '../models/Conexao.php';
include '../models/Quadra.php';
include '../models/Owner.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    echo "<pre>";
    print_r($_POST);  // Verificar o conteúdo do POST
    echo "</pre>";

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

        // Redireciona para a página de sucesso
        header("Location: ../views/success.php");
        exit();
    }
}
