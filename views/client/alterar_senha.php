<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Alterar senha! | © 2024 Arena Rental, Inc.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="../../resources/css/alterar_senha.css?v=<?= time() ?>">
</head>

<body>

  <?php include '../layouts/header.php'; ?>
  <?php include '../layouts/verification.php'; ?>

  <section>
    <h1>Olá,    <?php  $nomeCompleto = htmlspecialchars($client->getName());
                $primeiroNome = explode(' ', $nomeCompleto)[0];
                echo $primeiroNome; ?>!</h1>
    <h2>Altere sua senha aqui!</h2>
  </section>

  <div id="QuadCinza2"></div>

  <div id="SenhaS">
    <form id="senhaForm" action="../../controllers/ClientController.php?action=senha" method="POST"
      onsubmit="return validateForm()">
      <div>Senha atual:</div>
      <input type="password" name="senha_atual" required>
      <div>Nova senha:</div>
      <input type="password" name="nova_senha" id="nova_senha" required>
      <div>Confirmar nova senha:</div>
      <input type="password" name="confirma_senha" id="confirma_senha" required>
      <br>
      <button id="Continuar" type="submit">Atualizar senha</button>
    </form>
  </div>

  <script>
  function validateForm() {
    var novaSenha = document.getElementById("nova_senha").value;
    var confirmaSenha = document.getElementById("confirma_senha").value;

    if (novaSenha.length < 8) {
      alert("A nova senha deve ter pelo menos 8 caracteres.");
      return false;
    }

    if (novaSenha !== confirmaSenha) {
      alert("As senhas não coincidem.");
      return false;
    }

    return true;
  }
  </script>

</body>

</html>