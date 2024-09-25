
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
<h1>Bem-vindo, <?php echo htmlspecialchars($client->getName()); ?></h1>
        <p>Tipo de usuário: <?php echo htmlspecialchars($client->getType()); ?></p>
    <?php 
         if ($owner) {
            // Exiba informações adicionais do proprietário
            echo "<h2>Informações do Proprietário</h2>";
            echo "<p>Nome do Espaço: " . htmlspecialchars($owner->getNomeEspaco()) . "</p>";
            echo "<p>Localização: " . htmlspecialchars($owner->getLocalizacao()) . "</p>";
            echo "<p>CEP: " . htmlspecialchars($owner->getCep()) . "</p>";
            echo "<p>Descrição: " . htmlspecialchars($owner->getDescricao()) . "</p>";
            echo "<p>Recursos: " . htmlspecialchars($owner->getRecursos()) . "</p>"; // Exibe recursos
        }
    
    ?>
</section>


</body>
</html>