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
    <link rel="stylesheet" href="../../resources/css/registrar.css?v=<?= time() ?>">
</head>
<body>
    <?php include '../layouts/header.php'; ?>

 <section>
 <div id="Quad"> 
<form action="../../controllers/AuthController.php?action=registerInfo" method="post">
<input type="text" id="nome"  placeholder="Nome" name="nome" required></br>
<input type="text" id="cpf"  placeholder="CPF" oninput="mascararCPF()" maxlength="14"  name="cpf"  required>
<div id="lab"><label>Nome & CPF idêntico ao documento oficial.</label></div>
<input type="email" id="email"  placeholder="Email" name="email"required></br>
<input type="text" id="telefone" name="telefone" placeholder="Telefone" maxlength="15"></br>
<div id="lab"><label>Iremos alertar sobre disponibilidade,promoções e novidades!</label></div>
<input type="date" id="nascimento"   name="nascimento" required></br>
<button id="Continuar" > Concordar e continuar </button>
<div id="txt-form">
<div id="txt4">Clicando em Concordar e continuar, o cliente concorda com os</div>
<div id="txt5"><a>Termos de Serviço</a> & <a>Política de Privacidade</a> da <a>ArenaRental©</a></div>
</div>

<input type="hidden" name="tipo" value="<?php echo htmlspecialchars($tipo); ?>">
</form>
</div>

<div id="mockup"></div>
 </section>


 <script src="../../resources/js/telefone.js"></script>
 <script src="../../resources/js/cpf.js"></script>
 <script src="../../resources/js/nome.js"></script>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>
</html>