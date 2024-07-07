<?php include 'model/User/funcao.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem vindo! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="resources/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="view/User/css/styles.css?v=<?= time() ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>

<header>
    <h2 id="imgH2"></h2>
    <h1>ArenaRental©</h1>

    <?php if (isset($_SESSION['nome'])): ?>
        <div class="dropdown">
            <button class="mainmenubtn"><?php FotoPerfil()?></button>
            <?php if ($_SESSION['tipo'] == 'Atleta'): ?>
                <div class="dropdown-child"><a href="View/Atleta/html/conta.php"><button>Conta</button></a></div>
                <?php elseif ($_SESSION['tipo'] == 'Dono'): ?>
                    <div class="dropdown-child"><a href="View/Dono/html/conta.php"><button>Conta</button></a></div>
                <?php endif; ?>

            <div class="dropdown-child"><button class="logoff-btn">Logoff</button></div>
            <div class="dropdown-child"><button id="toggle-theme">Alterar tema</button></div>
            
        </div>
    <?php else: ?>
        <div class="dropdown">
            <button class="mainmenubtn"></button>
            <div class="dropdown-child">
                <a href="view/User/html/login.php"><button>Login</button></a>
                <a href="view/User/html/registrar.php"><button>Registrar</button></a>
                <button id="toggle-theme">Alterar tema</button>
            </div>
        </div>
    <?php endif; ?>
</header>

<div id="QuadCinza"></div>

<script src="view/User/java/script.js"></script>
</body>
</html>