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
        exit();
    }
    
    if ($action === 'update' && $client) {
        $name = $_POST['nome'];
        $email = $_POST['email'];
        $client->updateClient($name, $email);
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
        $bairro = $_POST['bairro'];
        $regiao = $_POST['regiao'];
    
        $client->registerOwner($nomeEspaco, $localizacao, $cep, $descricao, $bairro, $regiao);
    }

    if ($action === 'registerOwnerResources' && $client) {
        $recursos = isset($_POST['recursos']) ? $_POST['recursos'] : [];
        $client->registerOwnerResources($recursos);
    }
    if ($action === 'reservarQuadra') {
        if (!$client) {
            $quadraId = $_POST['id_quadra'];
            $_SESSION['erro'] = "Você precisa estar logado para fazer uma reserva.";
            header("Location: ../views/home/quadra_detalhes.php?id=" . $quadraId);
            exit();
        }
    
        $quadraId = $_POST['id_quadra'];
        $dataReserva = $_POST['data_reserva'];
        $horarioInicio = $_POST['horario_inicio'];
        $horarioFim = $_POST['horario_fim'];
        $valorTotal = $_POST['valor_total']; // Recebe o valor total enviado pelo formulário
    
        $mensagem = $client->reserveCourt($quadraId, $dataReserva, $horarioInicio, $horarioFim, $valorTotal);
    
        $_SESSION['mensagem'] = $mensagem;
    
        header("Location: ../views/home/quadra_detalhes.php?id=" . $quadraId);
        exit();
    }
    if ($action === 'cancelarReserva') {
        if (!$client) {
            $quadraId = $_POST['id_quadra'];
            $_SESSION['erro'] = "Você precisa estar logado para fazer uma reserva.";
            header("Location: ../views/home/quadra_detalhes.php?id=" . $quadraId);
            exit();
        }
    
        $reservaId = $_POST['reserva_id'];
    
        $mensagem = $client->cancelarReserva($reservaId);
    
        $_SESSION['mensagem'] = $mensagem;
    
        header("Location: ../views/client/reservas.php");
        exit();
    }
    if ($action === 'uploadPropertyImages' && $client) {
        $origem = isset($_POST['origem']) ? $_POST['origem'] : null;
        $client->uploadPropertyImages($origem);
        exit();
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