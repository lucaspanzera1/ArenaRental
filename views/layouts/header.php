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
<link rel="stylesheet" href="../../resources/css/header.css?v=<?= time() ?>">
<link rel="stylesheet" href="../../resources/css/notifications.css?v=<?= time() ?>">
<script src="../../resources/js/notifications.js"></script>
    <script>
        console.log('Session ID:', <?php echo json_encode($_SESSION['user_id'] ?? null); ?>);
    </script>
    <script>
        console.log('Header carregado');
    </script>
<header>
    <div>
        <a href="../home/index.php"><h2 id="imgH2"></h2></a>
        <h1></h1>
    </div>

<div>
<?php if (isset($_SESSION['client'])): ?>
        <div class="notifications-wrapper">
            <button class="notifications-toggle">
            <svg width="35" height="35" viewBox="0 0 35 35" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M28.1313 25.2146L26.25 23.3333V16.0416C26.25 11.5646 23.8583 7.81665 19.6875 6.82498V5.83331C19.6875 4.6229 18.7104 3.64581 17.5 3.64581C16.2896 3.64581 15.3125 4.6229 15.3125 5.83331V6.82498C11.1271 7.81665 8.75001 11.55 8.75001 16.0416V23.3333L6.86876 25.2146C5.95001 26.1333 6.59168 27.7083 7.8896 27.7083H27.0958C28.4083 27.7083 29.05 26.1333 28.1313 25.2146ZM23.3333 24.7916H11.6667V16.0416C11.6667 12.425 13.8688 9.47915 17.5 9.47915C21.1313 9.47915 23.3333 12.425 23.3333 16.0416V24.7916ZM17.5 32.0833C19.1042 32.0833 20.4167 30.7708 20.4167 29.1666H14.5833C14.5833 30.7708 15.8813 32.0833 17.5 32.0833Z" fill="black"/>
</svg>

            </button>
            <?php include 'notifications.php'; ?>
        </div>
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
</div>
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
        document.addEventListener('DOMContentLoaded', function() {
            const mainMenuBtn = document.querySelector('.mainmenubtn');
            const dropdownChild = document.querySelector('.dropdown-child');

            mainMenuBtn.addEventListener('click', function() {
                dropdownChild.classList.toggle('active');
            });

            // Fechar o dropdown quando clicar fora dele
            document.addEventListener('click', function(event) {
                if (!event.target.closest('.dropdown')) {
                    dropdownChild.classList.remove('active');
                }
            });
        });
    </script>

