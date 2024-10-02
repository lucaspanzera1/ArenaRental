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
  <link rel="stylesheet" href="../../resources/css/form.quadra1.css?v=<?= time() ?>">
</head>

<body>

  <?php include '../layouts/header.php'; ?>
  <?php include '../layouts/verification.php'; ?>
  <form action="../../controllers/OwnerController.php?action=registerQuadra" method="POST">
    <section>
      <h1>Cadastre sua quadra!</h1>
      <input type="text" placeholder="Nome" id="nome" name="nome">
      <select id="esporte" name="esporte" required>
        <option value="" disabled selected>Esporte</option>
        <option value="Futebol">Futebol</option>
        <option value="Futsal">Futsal</option>
        <option value="Futvôlei">Futvôlei</option>
      </select>
      <select id="quadrac" name="quadrac" required>
        <option value="coberta">Quadra coberta</option>
        <option value="descoberta">Quadra descoberta</option>
      </select>
      <div class="radio-group">
        <h3>Aluguel por:</h3>
        <div class="radio-option">
          <input type="radio" id="day-use" name="rental-type" value="day use" checked>
          <h3>Day use</h3>
        </div>
        <div class="radio-option">
          <input type="radio" id="por-hora" name="rental-type" value="por hora">
          <h3>Por hora</h3>
        </div>
      </div>
      <div id="priceText">Determine o preço <span id="selectedOption">por hora</span>.</div>
      <div id="priceValue">R$<input type="number" id="priceInput" name="priceInput" value="99" min="0"></div>
      <button type="submit">Registrar</button>
    </section>
  </form>
  <script>
  const radioButtons = document.querySelectorAll('input[name="rental-type"]');
  const selectedOption = document.getElementById('selectedOption');
  const priceInput = document.getElementById('priceInput');

  radioButtons.forEach(radio => {
    radio.addEventListener('change', function() {
      selectedOption.textContent = this.value;
    });
  });

  // Inicializar com o valor padrão
  document.getElementById('por-hora').checked = true;
  </script>
</body>

</html>