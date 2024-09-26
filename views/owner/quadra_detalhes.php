<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de quadras. | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/gerenciador.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>
<section>
<?php
// Conexão com o banco de dados
include_once '../../config/conexao.php';  // ajuste conforme sua estrutura

// Verifica se o ID foi passado pela URL
if (isset($_GET['id'])) {
    $quadra_id = $_GET['id'];
    
    // Consulta para buscar os detalhes da quadra com o ID passado
    $query = "SELECT * FROM quadra WHERE id = :id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':id', $quadra_id, PDO::PARAM_INT);
    $stmt->execute();
    $quadra = $stmt->fetch(PDO::FETCH_ASSOC);

    // Verifica se encontrou a quadra
    if ($quadra) {
        echo "<h1>" . htmlspecialchars($quadra['nome']) . "</h1>";
        echo "<p>Esporte: " . htmlspecialchars($quadra['esporte']) . "</p>";
        echo "<p>Coberta: " . ($quadra['coberta'] ? 'Sim' : 'Não') . "</p>";
        echo "<p>Tipo de Aluguel: " . htmlspecialchars($quadra['tipo_aluguel']) . "</p>";
        echo "<p>Valor: R$ " . number_format($quadra['valor'], 2, ',', '.') . "</p>";
        echo "<img src='../" . htmlspecialchars($quadra['imagem_quadra']) . "' alt='Imagem da Quadra'>";
        // Outras informações que desejar exibir
    } else {
        echo "<p>Quadra não encontrada.</p>";
    }
} else {
    echo "<p>ID da quadra não fornecido.</p>";
}
?>
</section>
</body>
</html>