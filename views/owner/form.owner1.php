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
    <link rel="stylesheet" href="../../resources/css/form.owner1.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<section>
  <h2>Etapa 1</h2>
  <h1>Fale um pouco sobre seu espaço.</h1>
  <form action="../../controllers/ClientController.php?action=registerOwner" method="POST">
        <input type="text" id="nome" name="nome" placeholder="Nome do Espaço" required>
        <div id="inputform">
            <input type="text" id="loc" name="loc" placeholder="Localização" required>
            <input type="text" id="cep" name="cep" placeholder="CEP" required>
        </div>
        <input type="text" id="Desc" name="Desc" placeholder="Descrição" required>
        <button type="submit">Registrar</button>
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