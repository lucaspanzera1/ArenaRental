<?php
include '../models/Conexao.php';
include '../models/Quadra.php';
include '../models/Owner.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($action === 'registerQuadra' && isset($_SESSION['client'])) {
        $clientData = $_SESSION['client'];
        $ownerId = $clientData['id']; // Supondo que o ID do proprietário está na sessão

        $nome = $_POST['nome'];
        $esporte = $_POST['esporte'];
        $quadrac = $_POST['quadrac'] === 'coberta' ? 1 : 0; // Converte para booleano (coberta = 1)
        $rentalType = $_POST['rental-type'];
        $price = $_POST['priceInput'];
        
        // Crie a instância da classe Quadra e salve no banco de dados
        $quadra = new Quadra();
        $quadra->registerQuadra($ownerId, $nome, $esporte, $quadrac, $rentalType, $price);

        // Redirecionar ou mostrar mensagem de sucesso
        header("Location: ../views/success.php");
        exit();
    }
}
?>
