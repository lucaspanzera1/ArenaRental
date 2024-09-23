<?php
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';

session_start(); // Certifique-se de iniciar a sessão se ainda não foi feito


// Verifique se há dados de cliente na sessão
if (isset($_SESSION['client'])) {
    // Crie uma instância da classe Client com os dados da sessão
    $client = new Client(
        $_SESSION['client']['id'],
        $_SESSION['client']['nome'],
        $_SESSION['client']['email'],
        $_SESSION['client']['tipo'],
        $_SESSION['client']['data_registro'],

    );
    if (isset($_SESSION['client'])) {
        $dataRegistro = $_SESSION['client']['data_registro'];
        // Converte e formata a data para o formato brasileiro
        $dataFormatoBrasileiro = date('d/m/Y', strtotime($dataRegistro));
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
<link rel="stylesheet" href="../../resources/css/header.css?v=<?= time() ?>">

<header>
    <div>
        <h2 id="imgH2"></h2>
        <h1>ArenaRental©</h1>
    </div>

    <?php if (isset($_SESSION['client'])): ?>
        <div class="dropdown">
    <div id="ImgPerfil" class="mainmenubtn">
        <img src="<?php echo htmlspecialchars($client->getProfilePicture()); ?>" alt="AAAA">
    </div>
        <div class="dropdown-child">
            <button id="Name"> <?php echo "" . htmlspecialchars($client->getFirstName()); ?></button>
            <a href="../client/conta.php"><button>Conta</button></a>
            <?php if ($client->getType() === 'cliente'): ?>
                <a href="../owner/form.owner1.php"><button>Anuncie!</button></a>
                <?php endif; ?>
            <form method="POST">
                <button type="submit" name="logoff" class="logoff-btn">Logoff</button>
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

<script>
document.querySelector('.dropdown').addEventListener('click', function() {
    const dropdownChild = document.querySelector('.dropdown-child');

    // Alternar entre block e none
    if (dropdownChild.style.display === 'block') {
        dropdownChild.style.display = 'none';
    } else {
        dropdownChild.style.display = 'block';
    }
});

// Seleciona o elemento h2 pelo ID
const imgH2 = document.getElementById('imgH2');

// Adiciona um evento de clique ao elemento
imgH2.addEventListener('click', function() {
    // Volta uma página no histórico
    window.history.back();
});
</script>