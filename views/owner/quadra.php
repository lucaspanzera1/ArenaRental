<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minha quadra! | © 2024 Arena Rental, Inc.</title>
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../../resources/css/quadra.css?v=<?= time() ?>">
</head>
<body>
<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<?php

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Incluir a classe Owner
    require_once 'Owner.php';

    // Criar uma instância da classe Owner
    $owner = new Owner();

    // Obter informações das quadras do usuário (dono)
    $quadras = $owner->getQuadraInfo($userId);

    // Exibir as quadras no formulário HTML
} else {
}
?>

<?php foreach ($quadras as $quadra): ?>
<div id="form-cantainer">
<section>
    <h3>
        <?php if (!empty($quadra['nome_imagem'])): ?>
            <img src="../../upload/quadra_img/<?php echo htmlspecialchars($quadra['nome_imagem']); ?>" alt="Imagem da quadra <?php echo htmlspecialchars($quadra['nome_quadra']); ?>">
        <?php else: ?>
            <img src="caminho/para/imagem/padrao.jpg">
        <?php endif; ?>
    </h3>
    <h1><?php echo htmlspecialchars($quadra['nome_quadra']); ?></h1>
    <h2><?php echo htmlspecialchars($quadra['esporte']); ?></h2>
    <h3><b>R$<?php echo number_format($quadra['valor'], 2, ',', '.'); ?></b>/hora</h3>
</section>

<div>
<div id="dono-container">
<?php $profilePicture = $client->getProfilePicture(); ?>
<div id="client-container">
            <h4>Anfitriã(o): <?php echo "" . htmlspecialchars($client->getFirstName()); ?></h4>
            <h5>Entrou em <?php echo htmlspecialchars($dataFormatoBrasileiro);  ?></h5>
</div></div>     

<div id="info-container">
<h2><?php echo htmlspecialchars($quadra['esporte']); ?> , <?php echo htmlspecialchars($quadra['localizacao']); ?></h2>
<h3><?php echo htmlspecialchars($quadra['descricao']); ?></h3>
</div>
</div>
</div>
<?php endforeach; ?>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../java/logoff.js"></script>
<script src="../java/dark.js"></script>
</body>
</html>