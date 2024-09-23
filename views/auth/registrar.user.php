<?php
$tipo = isset($_GET['tipo']) ? $_GET['tipo'] : 'cliente'; // Padrão é 'cliente' se não especificado
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastre-se | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/registrar.user.css?v=<?= time() ?>">
</head>
<body>
    <?php include '../layouts/header.php'; ?>
    <?php include '../layouts/verification.php'; ?>
 <section>
 <div id="Quad"> 

<h1>Conta</h1>
<div id="QuadCinza2"></div>

<form action="../../controllers/AuthController.php?action=registerUser" method="post" onsubmit="return validateForm()">
    <input type="text" id="nomeuser" placeholder="Nome de Usuário" name="nomeuser" required><br>
    <input type="password" id="senha" placeholder="Senha" name="senha" required><br>
    <input type="password" id="confirmarsenha" placeholder="Confirmar senha" name="confirmarsenha" required><br>

    <button id="Continuar">Criar conta</button>

    <div id="txt-form">
        <div id="txt4">Clicando em Concordar e continuar, o cliente concorda com os</div>
        <div id="txt5"><a>Termos de Serviço</a> & <a>Política de Privacidade</a> da <a>ArenaRental©</a></div>
    </div>
</form>

<script>
function validateForm() {
    var username = document.getElementById("nomeuser").value;
    var senha = document.getElementById("senha").value;
    var confirmarSenha = document.getElementById("confirmarsenha").value;

    if (username.length < 3) {
        alert("O nome de usuário deve ter pelo menos 3 caracteres.");
        return false;
    }

    if (senha.length < 8) {
        alert("A senha deve ter pelo menos 8 caracteres.");
        return false;
    }

    if (senha !== confirmarSenha) {
        alert("As senhas não coincidem.");
        return false;
    }

    return true;
}
</script>
</div>
 </section>


 <script src="../../resources/js/telefone.js"></script>
 <script src="../../resources/js/cpf.js"></script>
 <script src="../../resources/js/nome.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>