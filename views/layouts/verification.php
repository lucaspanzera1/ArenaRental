<?php
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';

// Verifique se há dados de cliente na sessão
if (isset($_SESSION['client']['id'])) {
    // Crie uma instância da classe Client com os dados da sessão
    $client = new Client(
        $_SESSION['client']['id'],
        $_SESSION['client']['nome'],
        $_SESSION['client']['email'],
        $_SESSION['client']['tipo'],
        $_SESSION['client']['data_registro'],
    );
    
    // Verifique se o campo 'data_registro' existe na sessão
    if (isset($_SESSION['client']['data_registro'])) {
        $dataRegistro = $_SESSION['client']['data_registro'];
        // Converte e formata a data para o formato brasileiro
        $dataFormatoBrasileiro = date('d/m/Y', strtotime($dataRegistro));
    }
    // Verifique se o botão de logoff foi pressionado
    if (isset($_POST['logoff'])) {
        $client->logoff(); // Chame a função de logoff
    }
    // Exiba o nome do cliente
    // echo "Bem-vindo, " . htmlspecialchars($_SESSION['client']['nome']) . "!";
} else {
  echo "<script>
    alert('Você não tem acesso a esta página. Faça login para continuar.');
   window.location.href = '../../index.php';
  </script>";
  header("refresh: 1.5; url=../../index.php");
    exit(); // Pare a execução do script para garantir que o redirecionamento aconteça
}

$reservas = $client->getReservas();
?>
