<?php
require_once '../../config/Conexao.php';
require_once '../../models/User.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID do proprietário não fornecido ou inválido.');
}

$id_proprietario = (int)$_GET['id'];

// Busca os detalhes do proprietário
$proprietario = User::getProprietarioById($id_proprietario);

if (!$proprietario) {
    die('Proprietário não encontrado.');
}

// HTML da página de perfil do proprietário
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de <?php echo htmlspecialchars($proprietario['nome']); ?> <?php echo htmlspecialchars($proprietario['nome_espaco']); ?> | © 2024 Arena Rental, Inc.</title>
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/perfil.dono.css?v=<?= time() ?>">
</head>
<body>
    <?php include '../layouts/header.php'; ?>

<section id="grid-container">
<div id="Corpo">
<div id="Quad">
<div id="Perfil"><img src="../<?php echo htmlspecialchars($proprietario['imagem_perfil']); ?>" alt="Imagem de perfil de <?php echo htmlspecialchars($proprietario['nome']); ?>"></div>
  <h1><?php echo htmlspecialchars($proprietario['nome']); ?></h1>
<h2><?php echo htmlspecialchars($proprietario['email']); ?></h2>
<h2><?php echo htmlspecialchars($proprietario['telefone']); ?></h2>
<h3>Membro desde:<?php echo htmlspecialchars($proprietario['data_registro']); ?></h3>
</div>
</div>

<div>
<div id="Info">
<h1><?php echo htmlspecialchars($proprietario['nome_espaco']); ?></h1>
<h2><?php echo htmlspecialchars($proprietario['localizacao']); ?> <?php echo htmlspecialchars($proprietario['cep']); ?> </h2>
<h3><?php echo htmlspecialchars($proprietario['recursos']); ?></h3>
 </div>
 <div id="grid-quadras">
  <?php if (!empty($proprietario['quadras'])): ?>
    <?php foreach ($proprietario['quadras'] as $quadra): ?>
      <a href="quadra_detalhes.php?id=<?php echo htmlspecialchars($quadra['id']); ?>" class="quadra-link">
        <div id="quadra-item">
          <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="Imagem da quadra <?php echo htmlspecialchars($quadra['nome']); ?>">
          <label for=""></label>
          <h1><?php echo htmlspecialchars($quadra['nome']); ?></h1>
          <h2><?php echo htmlspecialchars($quadra['esporte']) . ", " . ($quadra['coberta'] ? 'coberta' : 'descoberta'); ?></h2>
          <h2><?php echo "<b>R$" . number_format($quadra['valor'], 2, ',', '.') . "</b>/". htmlspecialchars($quadra['tipo_aluguel']) ?></h2>
        </div>
      </a>
    <?php endforeach; ?>
  <?php else: ?>
    <p>Este proprietário não possui quadras cadastradas.</p>
  <?php endif; ?>
</div>
</div>
</section>

</body>
</html>