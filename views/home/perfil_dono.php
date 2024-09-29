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
    <title>Perfil de <?php echo htmlspecialchars($proprietario['nome']); ?> | © 2024 Arena Rental, Inc.</title>
    <link rel="stylesheet" href="../../resources/css/perfil_dono.css">
</head>
<body>
    <?php include '../layouts/header.php'; ?>

    <main>
        <h1>Perfil de <?php echo htmlspecialchars($proprietario['nome']); ?></h1>
        <img src="../<?php echo htmlspecialchars($proprietario['imagem_perfil']); ?>" alt="Imagem de perfil de <?php echo htmlspecialchars($proprietario['nome']); ?>">
        <p>Nome do Espaço: <?php echo htmlspecialchars($proprietario['nome_espaco']); ?></p>
        <p>Localização: <?php echo htmlspecialchars($proprietario['localizacao']); ?></p>
        <p>CEP: <?php echo htmlspecialchars($proprietario['cep']); ?></p>
        <p>Descrição: <?php echo htmlspecialchars($proprietario['descricao']); ?></p>
        <p>Recursos: <?php echo htmlspecialchars($proprietario['recursos']); ?></p>
        <p>Email: <?php echo htmlspecialchars($proprietario['email']); ?></p>
        <p>Telefone: <?php echo htmlspecialchars($proprietario['telefone']); ?></p>
        <p>Data de Registro: <?php echo htmlspecialchars($proprietario['data_registro']); ?></p>

        <h2>Quadras</h2>
        <?php if (!empty($proprietario['quadras'])): ?>
            <ul>
            <?php foreach ($proprietario['quadras'] as $quadra): ?>
                <li>
                    <h3><?php echo htmlspecialchars($quadra['nome']); ?></h3>
                    <p>Esporte: <?php echo htmlspecialchars($quadra['esporte']); ?></p>
                    <p>Tipo: <?php echo $quadra['coberta'] ? 'Coberta' : 'Descoberta'; ?></p>
                    <p>Aluguel: <?php echo htmlspecialchars($quadra['tipo_aluguel']); ?></p>
                    <p>Valor: R$ <?php echo htmlspecialchars($quadra['valor']); ?></p>
                    <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="Imagem da quadra <?php echo htmlspecialchars($quadra['nome']); ?>">
                </li>
            <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <p>Este proprietário não possui quadras cadastradas.</p>
        <?php endif; ?>
    </main>

    <?php include '../layouts/footer.php'; ?>
</body>
</html>