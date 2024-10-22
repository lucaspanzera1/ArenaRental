<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicione fotos da quadra. | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/imagens.css?v=<?= time() ?>">
    <style>
        /* Adicione este estilo para ocultar os inputs de arquivo */
        input[type="file"] {
            display: none;
        }
    </style>
</head>
<body>
  <?php include '../layouts/header.php'; ?>
  <?php include '../layouts/verification.php'; ?>
    
<div id="Info">
<h1>Adicione algumas fotos da sua quadra</h1>
<h2>Você deve ter pelo menos 4 fotos para publicar seu anúncio. Você pode alterar mais tarde.</h2>
<form method="POST" action="../../controllers/ClientController.php?action=uploadPropertyImages" enctype="multipart/form-data">
   <div>
   <label class="picture" for="picture__input1" tabIndex="0">
        <span class="picture__image" id="picture__image1"></span>
    </label>
    <input name="arquivo1" type="file" id="picture__input1">
   </div>

        <div>
        <label class="picture" for="picture__input2" tabIndex="0">
        <span class="picture__image" id="picture__image2"></span>
    </label>
    <input name="arquivo2" type="file" id="picture__input2">
        </div>

    <div>
    <label class="picture" for="picture__input3" tabIndex="0">
        <span class="picture__image" id="picture__image3"></span>
    </label>
    <input name="arquivo3" type="file" id="picture__input3">
    </div>

<div>
<label class="picture" for="picture__input4" tabIndex="0">
        <span class="picture__image" id="picture__image4"></span>
    </label>
    <input name="arquivo4" type="file" id="picture__input4">
</div>

<button type="submit">Enviar</button>
    </form>
</div>

    <script>
    const inputFiles = document.querySelectorAll('input[type="file"]');
    const pictureImages = document.querySelectorAll(".picture__image");
    const pictureImageTxt = "Foto";
    const caminhoInicial = `../../views/img/bola.png`;

    function carregarImagemInicial() {
        pictureImages.forEach((pictureImage) => {
            const img = document.createElement("img");
            img.src = caminhoInicial;
            img.classList.add("picture__img");

            pictureImage.innerHTML = "";
            pictureImage.appendChild(img);
        });
    }

    carregarImagemInicial();

    inputFiles.forEach((inputFile, index) => {
        inputFile.addEventListener("change", function (e) {
            const inputTarget = e.target;
            const file = inputTarget.files[0];
            const pictureImage = pictureImages[index];

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
                pictureImage.innerHTML = pictureImageTxt;
            }
        });
    });
    </script>
</body>
</html>