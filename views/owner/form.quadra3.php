<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre sua quadra! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/form.quadra3.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>
  
<form action="../../controllers/OwnerController.php?action=etapa2" method="POST">
    <section>
        <h1>Agora, dê um título e informe a localização da sua quadra.</h1>
        <input id="Titulo" name="Titulo" type="text" placeholder="Título" required>
        
        <select id="esporte" name="esporte" required>
            <option value="" disabled selected>Esporte</option>
            <option value="Futebol">Futebol</option>
            <option value="Futsal">Futsal</option>
            <option value="Futvôlei">Futvôlei</option>
        </select>

        <input id="Localizacao" name="Localizacao" type="text" placeholder="Localização" required>

        <h1>Determine o preço por hora.</h1>
        <h2>Você pode alterar mais tarde.</h2>
        <div id="RS">
            <h1>R$ </h1>
            <input id="Valor" name="Valor" type="number" value="99" min="0" step="0.01" required>
        </div> 
        <h3 id="PrecoCliente">Preço para o cliente: R$109</h3>

        <button type="submit" id="Continuar">Continuar</button>
    </section>
</form>

<script>
    // Função para calcular e atualizar o preço
    function atualizarPreco() {
        // Obtém o valor do input
        const valorInput = parseFloat(document.getElementById('Valor').value);
        
        // Verifica se o valor é um número válido
        if (!isNaN(valorInput)) {
            // Calcula 103% do valor
            const precoCliente = valorInput * 1.03;
            
            // Atualiza o conteúdo do h3 com o novo valor, arredondado para duas casas decimais
            document.getElementById('PrecoCliente').innerText = `Preço para o cliente: R$${precoCliente.toFixed(2)}`;
        }
    }

    // Adiciona um listener para atualizar o preço sempre que o valor do input mudar
    document.getElementById('Valor').addEventListener('input', atualizarPreco);

    // Chama a função uma vez ao carregar a página para definir o preço inicial
    atualizarPreco();
</script>

</body>
</html>