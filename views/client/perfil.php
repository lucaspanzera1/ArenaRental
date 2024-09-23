<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Meu perfil! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/perfil.css?v=<?= time() ?>">
</head>
<body>
 
<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<div id="Corpo">
<div id="Quad">
<div id="Perfil"><img src="<?php echo htmlspecialchars($client->getProfilePicture()); ?>" alt="AAAA"></div>
<h1><?php echo "" . htmlspecialchars($client->getFirstName()); ?></h1>
<h2><?php echo "" .  htmlspecialchars($_SESSION['client']['email']);  ?></h2>

<h3>Entrou em <?php echo htmlspecialchars($dataFormatoBrasileiro);  ?></h3>
</div>
</div>

</body>
</html>