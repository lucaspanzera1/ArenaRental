<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Adicione fotos à sua quadra | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel='shortcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/imagem.quadra.css?v=<?= time() ?>">
</head>
<body>

<?php 
include '../layouts/header.php';
include '../layouts/verification.php';

// Verifica se o ID da quadra foi passado na URL
$quadraId = isset($_GET['id']) ? intval($_GET['id']) : null;

if (!$quadraId) {
    echo "<p>Erro: Nenhum ID de quadra válido foi fornecido.</p>";
    exit;
}

// Aqui você pode adicionar uma verificação para garantir que esta quadra pertence ao usuário logado
// Por exemplo:
// if (!verificaQuadraPertenceAoUsuario($quadraId, $_SESSION['id_usuario'])) {
//     echo "<p>Erro: Você não tem permissão para adicionar fotos a esta quadra.</p>";
//     exit;
// }
?>

<section>
    <div>
        <h1>Adicione algumas fotos da sua quadra</h1>
        <h2>Você deve ter pelo menos 1 foto para publicar seu anúncio. Você pode adicionar ou alterar mais tarde.</h2>

        <form method="POST" action="../../controllers/OwnerController.php?action=FotoQuadra" enctype="multipart/form-data">
            <input type="hidden" name="quadra_id" value="<?php echo $quadraId; ?>">
            <label class="picture" for="picture__input" tabIndex="0">
                <span class="picture__image">Clique para selecionar uma foto</span>
            </label>
            
            <input type="file" name="arquivo" id="picture__input" accept="image/*">

            <button type="submit" id="Continuar">Continuar</button>
        </form>
    </div>
</section>

<script>
const inputFile = document.querySelector("#picture__input");
const pictureImage = document.querySelector(".picture__image");
const pictureImageTxt = "Clique para selecionar uma foto";

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
        pictureImage.innerHTML = pictureImageTxt;
    }
});
</script>

</body>
</html>