<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/index.css?v=<?= time() ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <?php
    require_once '../../models/User.php';
    ?>


    <?php include '../layouts/header.php'; ?>
    
    <div id="search-container">
    <?php include '../layouts/search.php'; ?>
    <?php include '../layouts/slider.php'; ?>
  </div>
    
  <section class="quadra-list-container">
    <?php if (!empty($quadras)): ?>
    <div class="quadra-list">
        <?php foreach ($quadras as $quadra): ?>
        <div class="quadra-item">
            <a  href="quadra_detalhes.php?id=<?php echo htmlspecialchars($quadra['id']); ?>">
                <?php if (!empty($quadra['nome_imagem'])): ?>
                <img src="../../upload/quadra_img/<?php echo htmlspecialchars($quadra['nome_imagem']); ?>"
                    alt="<?php echo htmlspecialchars($quadra['nome_quadra']); ?>" class="quadra-image">
                <?php else: ?>
                <div class="quadra-image">Sem imagem disponível</div>
                <?php endif; ?>
                <h2><?php echo htmlspecialchars($quadra['nome_quadra']); ?></h2>
                <p><?php echo htmlspecialchars($quadra['esporte']); ?></p>
                <p><b>R$<?php echo number_format($quadra['valor'], 2, ',', '.'); ?></b>/hora</p>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php else: ?>
    <p>Nenhuma quadra disponível no momento.</p>
    <?php endif; ?>
</section>



    <?php include '../layouts/footer.php'; ?>
</body>

</html>