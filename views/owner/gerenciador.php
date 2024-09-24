<?php
require_once '../../models/Owner.php';

$owner = null;
if (isset($_SESSION['owner']) && isset($_SESSION['client'])) {
    $owner = Owner::fromClientData($_SESSION['client'], $_SESSION['owner']);
}

// Verifique se o botão de logoff foi pressionado
if (isset($_POST['logoff'])) {
    session_destroy();
    header("Location: index.php"); // Redireciona para a página inicial após o logoff
    exit;
}
?>
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
<?php if ($owner): ?>
<div id="Info">
    <h1>Conta</h1>
    <h2><p>Nome do Espaço: <?php echo htmlspecialchars($owner->getNomeEspaco()); ?></p></h2>
</div>
<?php else: ?>
        <p>Informações do proprietário não disponíveis.</p>
    <?php endif; ?>
</section>

</body>
</html>