<?php
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';

session_start(); // Iniciar sessão

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

// Processar a reserva quando o formulário for enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservar'])) {
    $quadra_id = $_POST['quadra_id'];
    $data = $_POST['data'];
    $horario_inicio = $_POST['horario_inicio'];
    $horario_fim = $_POST['horario_fim'];
    
    try {
        $pdo = Conexao::getInstance();
        $pdo->beginTransaction();

        // Primeiro, verificar se já existe um cliente "por fora" no sistema
        $stmt = $pdo->prepare("SELECT id FROM cliente WHERE nome = 'Cliente por fora' LIMIT 1");
        $stmt->execute();
        $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$cliente) {
            // Se não existe, criar o cliente "por fora"
            $stmt = $pdo->prepare("INSERT INTO cliente (cpf, nome, email, senha, tipo, username, imagem_perfil) 
                                  VALUES ('00000000000', 'Cliente por fora', 'cliente@porfora.com', 
                                         '', 'cliente', 'clienteporfora', 'default.jpg')");
            $stmt->execute();
            $cliente_id = $pdo->lastInsertId();
        } else {
            $cliente_id = $cliente['id'];
        }

        // Inserir na tabela reservas
        $stmt = $pdo->prepare("INSERT INTO reservas (cliente_id, quadra_id, data, horario_inicio, horario_fim, status) 
                              VALUES (:cliente_id, :quadra_id, :data, :horario_inicio, :horario_fim, 'confirmada')");
        $stmt->execute([
            ':cliente_id' => $cliente_id,
            ':quadra_id' => $quadra_id,
            ':data' => $data,
            ':horario_inicio' => $horario_inicio,
            ':horario_fim' => $horario_fim
        ]);

        // Atualizar status na tabela horarios_disponiveis
        $stmt = $pdo->prepare("UPDATE horarios_disponiveis 
                              SET status = 'reservado' 
                              WHERE quadra_id = :quadra_id 
                              AND data = :data 
                              AND horario_inicio = :horario_inicio 
                              AND horario_fim = :horario_fim");
        $stmt->execute([
            ':quadra_id' => $quadra_id,
            ':data' => $data,
            ':horario_inicio' => $horario_inicio,
            ':horario_fim' => $horario_fim
        ]);

        $pdo->commit();
        $mensagem = "Reserva realizada com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        $erro = "Erro ao realizar a reserva: " . $e->getMessage();
    }
}

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
<script src="../../resources/js/dark.js"></script>

