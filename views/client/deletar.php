<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desativar conta. | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/deletar.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<section>
   <div> 
    <h1>Olá,  <?php echo "" . htmlspecialchars($client->getName()); ?>!</h1>
    <h2>Lamentamos ver você partir!</h2>
    </div> 
</section>

<div id="QuadCinza2"></div>

<div id="TT">

    <div><h1>Desativar a conta?</h1>
    <h2><?php echo "" . htmlspecialchars($_SESSION['client']['email']); ?> </h2>
    <form id="deleteForm" action="../../controllers/ClientController.php?action=delete" method="post">
    <input id="Continuar" type="submit" value="Deletar conta" onclick="return confirm('Tem certeza que deseja deletar a conta?');">
    </form> </div>


    

</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../java/logoff.js"></script>
<script src="../java/dark.js"></script>
</body>
</html>