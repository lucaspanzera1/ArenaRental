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
    <link rel="stylesheet" href="../../resources/css/form.owner2.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<section>
  <h2>Etapa 2</h2>
  <h1>Mais sobre seu espaço.</h1>
  <h3>Oque o seu espaço oferece?</h3>
  <form action="../../controllers/ClientController.php?action=registerOwnerResources" method="POST">
    <div class="options">
        <div class="option">
            <input type="checkbox" id="bar" name="recursos[]" value="bar">
            <label for="bar">Bar</label>
        </div>
        <div class="option">
            <input type="checkbox" id="bebedouro" name="recursos[]" value="bebedouro">
            <label for="bebedouro">Bebedouro</label>
        </div>
        <div class="option">
            <input type="checkbox" id="vestiario" name="recursos[]" value="vestiario">
            <label for="vestiario">Vestiário</label>
        </div>
        <div class="option">
            <input type="checkbox" id="tv" name="recursos[]" value="tv">
            <label for="tv">TV a cabo</label>
        </div>
        <div class="option">
            <input type="checkbox" id="churrasqueira" name="recursos[]" value="churrasqueira">
            <label for="churrasqueira">Churrasqueira</label>
        </div>
        <div class="option">
            <input type="checkbox" id="festas" name="recursos[]" value="festas">
            <label for="festas">Área para festas</label>
        </div>
    </div>
    <button type="submit">Concordar e continuar</button>
</form>
</section>
<script>
        document.getElementById('cep').addEventListener('input', function (event) {
            let cep = event.target.value;

            // Remove qualquer caractere que não seja dígito
            cep = cep.replace(/\D/g, '');

            // Adiciona o hífen no formato XXXXX-XXX
            if (cep.length > 5) {
                cep = cep.replace(/(\d{5})(\d)/, '$1-$2');
            }

            // Limita o tamanho para 9 caracteres (XXXXX-XXX)
            if (cep.length > 9) {
                cep = cep.substring(0, 9);
            }

            // Atualiza o valor do input
            event.target.value = cep;
        });
    </script>
</body>
</html>