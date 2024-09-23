
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre sua quadra! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/form.quadra1.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<section>
    <div>
    <h2>Etapa 1</h2>
    <h1>Fale um pouco da sua quadra.</h1>
    <form action="../../controllers/OwnerController.php?action=etapa1" method="post">
    <input id="Desc" name="descricao" type="text" placeholder="Descrição"></br>
    <button type="submit" id="Continuar">Continuar</button>
  </form>
    </div>
    <div>
  </div>
</section>
  
</body>
</html>