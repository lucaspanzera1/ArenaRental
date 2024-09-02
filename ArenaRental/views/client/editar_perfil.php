<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações pessoais | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/editar_perfil.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<section>
    <h1>Olá, <?php echo "" . htmlspecialchars($client->getFirstName()); ?>!</h1>
    <h2>Edite seu perfil aqui.</h2>
</section>

<div id="QuadCinza2"></div>

<div class="form-container">
    <h2>Atualizar Perfil</h2>
    <form action="../../controllers/clientcontroller.php?action=update" method="POST">
        <div class="form-group">
            <label for="nome">Nome:</label>
            <input type="text" id="nome" name="nome" required value="<?php echo isset($_SESSION['client']['nome']) ? $_SESSION['client']['nome'] : ''; ?>">
</div>
        <div class="form-group">
            <label for="email">Email:</label>
            <input type="email" id="email" name="email" required value="<?php echo isset($_SESSION['client']['email']) ? $_SESSION['client']['email'] : ''; ?>">
        </div>

        <div class="form-group">
            <button type="submit">Atualizar</button>
        </div>
    </form>
</div>

</body>
</html>
