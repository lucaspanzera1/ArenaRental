<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bem vindo! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/foto_perfil.css?v=<?= time() ?>">
</head>
<body>
  <?php include '../layouts/header.php'; ?>
  <?php include '../layouts/verification.php'; ?>
    
    <section>
    <div id="Quad"> 
       <h1>Quase lá!</h1>
       <div id="QuadCinza2"></div>
      <nav>
      <h2>Bem-vindo a ArenaRental!</h2>
      <h2>Primeiro, adicione uma foto no seu perfil!</h2>
      </nav>
      <form method="POST" action="../../controllers/ClientController.php?action=FotoPerfil" enctype="multipart/form-data">
      
    <label class="picture" for="picture__input" tabIndex="0">
        <span class="picture__image"></span>
    </label>
      <input name="arquivo" type="file" name="picture__input" id="picture__input">
      <input type="submit" id="Continuar" value="Enviar">
        </div>
        </form>
    </section>

      <script>
        const inputFile = document.querySelector("#picture__input");
const pictureImage = document.querySelector(".picture__image");
const pictureImageTxt = "Foto";

// Caminho inicial da imagem usando a variável nomeImagem definida pelo PHP
const caminhoInicial = `../../views/img/bola.png`;

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
       </form>

</body>
</html>