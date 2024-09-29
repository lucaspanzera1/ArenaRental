<?php
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';
// Verifica se o ID foi fornecido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID da quadra não fornecido ou inválido.');
}

$id_quadra = (int)$_GET['id'];

// Busca os detalhes da quadra
$quadra = User::getQuadraById($id_quadra);

if (!$quadra) {
    die('Quadra não encontrada.');
}

// HTML da página de detalhes
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quadra['nome_espaco']); ?> <?php echo htmlspecialchars($quadra['nome']); ?> | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/detalhes_quadra.css?v=<?= time() ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include '../layouts/header.php'; ?>

<section>
    <h1><?php echo htmlspecialchars($quadra['nome_espaco']); ?> <?php echo htmlspecialchars($quadra['nome']); ?></h1>
    <div class="container">
      
    <div id="images-container">
    <?php if (!empty($quadra['imagem_quadra'])): ?>
            <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        <?php endif; ?>

        <div id="mini-images-container" class="mini-images">
        <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        </div>

        <div id="mini-images-container">
        <div id="mini1"> <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large"></div>
        <div id="mini2"> <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large"></div>
        </div>

    </div>

    <h2><?php echo htmlspecialchars($quadra['esporte']); ?> , <?php echo htmlspecialchars($quadra['localizacao']); ?> - <?php echo htmlspecialchars($quadra['cep']); ?></h2>
    <h3><?php echo $quadra['coberta'] ? 'Quadra coberta' : 'Quadra descoberta'; ?>, <?php echo htmlspecialchars($quadra['descricao_proprietario']); ?></h3>
    <div id="dono-container">
    <img src="../<?php echo htmlspecialchars($quadra['imagem_proprietario']); ?>" alt="Imagem de perfil de <?php echo htmlspecialchars($quadra['nome_proprietario']); ?>" class="imagem-perfil">
    <a href="perfil_dono.php?id=<?php echo htmlspecialchars($quadra['proprietario_id']); ?>" id="client-container">
        <div>
            <h4>Anfitriã(o): <?php echo htmlspecialchars($quadra['nome_proprietario']); ?></h4>
            <h5>Entrou em </h5>
        </div>
    </a>
</div>
</div>
</body>
</html>