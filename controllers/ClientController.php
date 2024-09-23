<?php
include '../models/Conexao.php';
include '../models/Client.php';

session_start();

$action = isset($_GET['action']) ? $_GET['action'] : '';

$client = null;

if (isset($_SESSION['client'])) {
    $clientData = $_SESSION['client'];
    $client = Client::fromUserData($clientData);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if ($action === 'delete' && $client) {
        $client->deleteAccount();
        header("Location: ../index.php?cod=1");
        exit();
    }
    
    if ($action === 'FotoPerfil' && $client) {
        $origem = isset($_POST['origem']) ? $_POST['origem'] : null;
        $client->uploadFotoPerfil($origem);
    }
    
    if ($action === 'update' && $client) {
        $name = $_POST['nome'];
        $email = $_POST['email'];
        $client->updateClient($name, $email);
        header("Location: ../index.php?cod=1");
        exit();
    }
    
    if ($action === 'senha' && $client) {
        $currentPassword = $_POST['senha_atual'];
        $newPassword = $_POST['nova_senha'];
        $confirmPassword = $_POST['confirma_senha'];
        
        $result = $client->changePassword($currentPassword, $newPassword, $confirmPassword);
        
        echo "<script type='text/javascript'>alert('$result'); window.location.href='../views/client/alterar_senha.php';</script>";
        exit();
    }
    
    if ($action === 'registerOwner' && $client) {
        $nomeEspaco = $_POST['nome'];
        $localizacao = $_POST['loc'];
        $cep = $_POST['cep'];
        $descricao = $_POST['Desc'];

        $client->registerOwner($nomeEspaco, $localizacao, $cep, $descricao);
        // O redirecionamento agora é tratado dentro da função registerOwner
    }
} else {
    // Handle GET requests or invalid actions
    if ($action === 'logoff' && $client) {
        $client->logoff();
    } else {
        header("Location: ../views/html/index.php?error=invalid_action");
        exit();
    }
}

// If we reach this point, it means no valid action was performed
header("Location: ../views/html/index.php");
exit();
?>