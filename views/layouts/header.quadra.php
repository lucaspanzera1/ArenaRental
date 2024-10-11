<?php
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';

session_start(); // Iniciar sessão

if (isset($_SESSION['mensagem'])):
    $mensagem = $_SESSION['mensagem'];
    unset($_SESSION['mensagem']); // Remove a mensagem para evitar repetição ao recarregar a página
endif;

// Verifica se há um erro armazenado na sessão (se necessário)
if (isset($_SESSION['erro'])):
    $erro = $_SESSION['erro'];
    unset($_SESSION['erro']); // Remove o erro para evitar repetição ao recarregar a página
endif;

// Verifique se há dados de cliente na sessão
if (isset($_SESSION['client'])) {
    // Crie uma instância da classe Client com os dados da sessão
    $client = new Client(
        $_SESSION['client']['id'],
        $_SESSION['client']['nome'],
        $_SESSION['client']['email'],
        $_SESSION['client']['tipo'],
        $_SESSION['client']['data_registro']
    );

    // Formatar a data de registro
    $dataRegistro = $_SESSION['client']['data_registro'];
    $dataFormatoBrasileiro = date('d/m/Y', strtotime($dataRegistro));

    // Verifique se o cliente é do tipo "Dono"
    if ($client->getType() === 'Dono') {
        // Carregue as informações do proprietário usando o ID do cliente
        $owner = Owner::getOwnerById($client->getId());
    }
    // Verifique se o botão de logoff foi pressionado
    if (isset($_POST['logoff'])) {
        $client->logoff(); // Chame a função de logoff
    }
    // Exiba o nome do cliente
    //echo "Bem-vindo, " . htmlspecialchars($_SESSION['client']['nome']) . "!";
} else {
    //echo "Bem-vindo!";
}
?>

<?php
include_once '../../config/conexao.php';  
// Resto do seu código existente para buscar quadra e horários
if (isset($_GET['id'])) {
    $quadra_id = $_GET['id'];

        $query = "SELECT * FROM quadra WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $quadra_id, PDO::PARAM_INT);
        $stmt->execute();
        $quadra = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($quadra) {
            // Buscar horários disponíveis para hoje
            $dataHoje = date('Y-m-d');
            $horarios = Owner::getHorariosDisponiveis($quadra_id, $dataHoje);
        } else {
            echo "<p>Quadra não encontrada.</p>";
        }
    } else {
        echo "<p>ID da quadra não fornecido.</p>";
    }
    ?>
<link rel="stylesheet" href="../../resources/css/header.quadra.css?v=<?= time() ?>">

<header>
    <div>
        <a href="../home/index.php"><h2 id="imgH2"></h2></a>
    </div>

    <nav class="center-nav">
        <?php echo "<a href='editar_quadra.php?id=" . $quadra['id'] . "' class='quadra-link'>Espaço</a>"; ?>
        <?php echo "<a href='hoje.php?id=" . $quadra['id'] . "' class='quadra-link'>Hoje</a>"; ?>
        <a href="">Calendário</a>
    </nav>

    <?php if (isset($_SESSION['client'])): ?>
        <div class="dropdown">
    <div id="ImgPerfil" class="mainmenubtn">
        <img src="<?php echo htmlspecialchars($client->getProfilePicture()); ?>" alt="AAAA">
    </div>
        <div class="dropdown-child">
            <button id="Name">
                 <?php  $nomeCompleto = htmlspecialchars($client->getName());
                $primeiroNome = explode(' ', $nomeCompleto)[0];
                echo $primeiroNome; ?></button>
            <a href="../client/conta.php"><button>Conta</button></a>
            <?php if ($client->getType() === 'cliente'): ?>
                <a href="../client/form.owner1.php"><button>Anuncie!</button></a>
                <?php endif; ?>
            <form method="POST">
                <a><button type="submit" name="logoff" class="logoff-btn">Logoff</button></a>
            </form>
            <button id="toggleButton">Tema</button>
        </div>
    </div>


    <?php else: ?>
    <div class="dropdown">
        <button class="mainmenubtn"></button>
        <div class="dropdown-child">
            <a href="../auth/login.php"><button>Login</button></a>
            <a href="../auth/registrar.php"><button>Registrar</button></a>
            <a href="../auth/registrar.php"><button>Anuncie!</button></a>
            <button id="toggle-theme">Tema</button>
        </div>
    </div>
    <?php endif; ?>
</header>

