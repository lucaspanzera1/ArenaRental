<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Informações da quadra! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/editar_quadra.css?v=<?= time() ?>">
</head>
<body>
<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>
<?php

if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];

    // Incluir a classe Owner
    require_once 'Owner.php';

    // Criar uma instância da classe Owner
    $owner = new Owner();

    // Obter informações das quadras do usuário (dono)
    $quadras = $owner->getQuadraInfo($userId);

    // Exibir as quadras no formulário HTML
} else {
}
?>

<?php foreach ($quadras as $quadra): ?>

<section>
    <h1>Olá, <?php echo "" . htmlspecialchars($client->getFirstName()); ?>!</h1>
    <h2>Edite seu anúncio da <?php echo htmlspecialchars($quadra['nome_quadra']); ?>.</h2>
</section>


<div id="QuadCinza2"></div>

<div id="ImgPerfil1">
<form method="POST" action="../../controllers/OwnerController.php?action=FotoQuadra" enctype="multipart/form-data">
<input type="hidden" name="origem" value="editar_quadra">
<label class="picture" for="picture__input" tabIndex="0">
    <span class="picture__image"></span>
  </label>
  
  <input name="arquivo" type="file" name="picture__input" id="picture__input">

  <input type="submit" id="Continuar" value="Alterar foto">

<script>
    // Passando a string PHP como valor JavaScript
    var nomeImagem = "<?php echo htmlspecialchars($quadra['nome_imagem']); ?>";
</script>

<script>
const inputFile = document.querySelector("#picture__input");
const pictureImage = document.querySelector(".picture__image");
const pictureImageTxt = "aaaa";

// Caminho inicial da imagem usando a variável nomeImagem definida pelo PHP
const caminhoInicial = `../../upload/quadra_img/${nomeImagem}`;

// Função para carregar a imagem ao iniciar a página
function carregarImagemInicial() {
  const img = document.createElement("img");
  img.src = caminhoInicial;
  img.classList.add("picture__img");

  pictureImage.innerHTML = "";
  pictureImage.appendChild(img);
}

// Chama a função para carregar a imagem ao iniciar a página
carregarImagemInicial();

inputFile.addEventListener("change", function (e) {
  const inputTarget = e.target;
  const file = inputTarget.files[0];

  if (file) {
    const reader = new FileReader();

    reader.addEventListener("load", function (e) {
      const readerTarget = e.target;

      const img = document.createElement("img");
      img.src = readerTarget.result;
      img.classList.add("picture__img");

      pictureImage.innerHTML = "";
      pictureImage.appendChild(img);
    });

    reader.readAsDataURL(file);
  } else {
    // Se não houver arquivo selecionado, mostra o texto padrão
    pictureImage.innerHTML = pictureImageTxt;
  }
});
</script>

    </div>
    </form>

  <div class="form-container">
    <form action="../../controllers/OwnerController.php?action=update" method="POST">
    <input type="hidden" name="id" value="<?php echo htmlspecialchars($quadra['id']); ?>">
        <div class="form-group">
            <label for="Titulo">Título:</label>
            <input id="Titulo" name="Titulo" type="text" required value="<?php echo htmlspecialchars($quadra['nome_quadra']); ?>">
            </div>

            <div class="form-group">
    <label for="esporte">Esporte:</label>
    <select id="esporte" name="esporte">
        <?php
        $esportes = ['Futebol', 'Futsal', 'Futvôlei'];
        $esporte_atual = htmlspecialchars($quadra['esporte']);
        
        // Opção atual
        echo "<option value=\"$esporte_atual\" selected>$esporte_atual</option>";
        
        // Outras opções
        foreach ($esportes as $esporte) {
            if ($esporte !== $esporte_atual) {
                echo "<option value=\"$esporte\">$esporte</option>";
            }
        }
        ?>
    </select>
</div>

        <div class="form-group">
        <label for="Localizacao">Localização:</label>
        <input id="Localizacao" name="Localizacao" type="text" value="<?php echo htmlspecialchars($quadra['localizacao']); ?>" required>
        </div>
        <div class="form-group">
        <label for="CEP">CEP:</label>
        <input id="CEP" name="CEP" type="text" value="300.720.520">
        </div>
        <div class="form-group">
        <label for="Descricao">Descrição:</label>
        <input id="Descricao" name="Descricao" type="text" value="<?php echo htmlspecialchars($quadra['descricao']); ?>" required>
        </div>
        <div class="form-group">
        <label for="Valor">Valor:</label>
        <input id="Valor" name="Valor" type="number" value="<?php echo htmlspecialchars($quadra['valor']); ?>" min="0" step="0.01" required>
        </div>

        <div class="form-group">
            <button type="submit">Atualizar</button>
        </div>
    </form>
</div>
<?php endforeach; ?>
</body>
</html>
