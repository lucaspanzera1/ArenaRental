<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login | © 2024 Arena Rental, Inc.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../../resources/css/login.css?v=<?= time() ?>">
</head>

<body>

  <?php include '../layouts/header.php'; ?>


  <section>
    <div>
      <div id="imgMockup"></div>
    </div>
  <div>
  <div id="Quad">
    <div id="arenatext"></div>
      <form action="../../controllers/AuthController.php?action=login" method="post">
        <input type="text" id="cpf" placeholder="CPF" oninput="mascararCPF()" maxlength="14" name="cpf" required>
        <input type="password" id="senha" placeholder="Senha" name="password" required>
        <button id="Continuar">Continuar</button>
        <p>Ainda não tem cadastro? <a href="registrar.php">Registre-se</a> </p>
      </form>
    </div>
  </div>
  </section>
  <script src="../../resources/js/cpf.js"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</body>

</html>