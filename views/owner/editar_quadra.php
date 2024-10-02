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
  <link rel="stylesheet" href="../../resources/css/quadra.detalhes.css?v=<?= time() ?>">
</head>

<body>

  <?php include '../layouts/header.php'; ?>
  <?php include '../layouts/verification.php'; ?>
  <?php
        // Conexão com o banco de dados
        include_once '../../config/conexao.php';  // ajuste conforme sua estrutura
        
        // Verifica se o ID foi passado pela URL
        if (isset($_GET['id'])) {
            $quadra_id = $_GET['id'];

            // Consulta para buscar os detalhes da quadra com o ID passado
            $query = "SELECT * FROM quadra WHERE id = :id";
            $stmt = $pdo->prepare($query);
            $stmt->bindParam(':id', $quadra_id, PDO::PARAM_INT);
            $stmt->execute();
            $quadra = $stmt->fetch(PDO::FETCH_ASSOC);
            
            
            if ($quadra) {
               
            } else {
                echo "<p>Quadra não encontrada.</p>";
            }
        } else {
            echo "<p>ID da quadra não fornecido.</p>";
        }
        ?>

  <section>
    <div id="form-container">
      <form action="../../controllers/OwnerController.php?action=updateQuadra" method="POST">
        <input type="hidden" name="quadra_id" value="<?php echo htmlspecialchars($quadra['id']); ?>">
        <div class="form-group" id="nome">
          <label for="nome">Nome:</label>
          <input type="text" id="nome" name="nome" required value="<?php echo htmlspecialchars($quadra['nome']); ?>">
        </div>

        <div class="form-group">
          <label for="esporte">Esporte:</label>
          <select id="esporte" name="esporte">
            <option value=""><?php echo htmlspecialchars($quadra['esporte']); ?></option>
            <?php if ($quadra['esporte'] != 'Futebol'): ?>
            <option value="Futebol">Futebol</option>
            <?php endif; ?>
            <?php if ($quadra['esporte'] != 'Futsal'): ?>
            <option value="Futsal">Futsal</option>
            <?php endif; ?>
            <?php if ($quadra['esporte'] != 'Futvôlei'): ?>
            <option value="Futvôlei">Futvôlei</option>
            <?php endif; ?>
          </select>
        </div>

        <!-- Campo Quadra (Coberta ou Descoberta) -->
        <div class="form-group">
          <label for="quadrac">Quadra:</label>
          <select id="quadrac" name="quadrac">
            <option value="">
              <?php echo ($quadra['coberta'] ? 'Coberta' : 'Descoberta'); ?>
            </option>
            <?php if ($quadra['coberta'] != 1): /* Se não for coberta */ ?>
            <option value="1">Coberta</option>
            <?php endif; ?>
            <?php if ($quadra['coberta'] != 0): /* Se não for descoberta */ ?>
            <option value="0">Descoberta</option>
            <?php endif; ?>
          </select>
        </div>

        <!-- Campo Aluguel -->
        <div class="form-group">
          <label for="aluguel">Aluguel por:</label>
          <select id="aluguel" name="aluguel">
            <option value="">
              <?php echo htmlspecialchars($quadra['tipo_aluguel']); ?>
            </option>
            <?php if ($quadra['tipo_aluguel'] != 'day-use'): ?>
            <option id="day-use" name="rental-type" value="day use">Day use</option>
            <?php endif; ?>
            <?php if ($quadra['tipo_aluguel'] != 'hora'): ?>
            <option id="por-hora" name="rental-type" value="por hora">Por hora</option>
            <?php endif; ?>
          </select>
        </div>

        <div class="form-group" id="valor">
          <label for="priceInput">Valor:</label>
          <input type="text" id="priceInput" name="priceInput" required
            value="<?php echo number_format($quadra['valor'], 2, ',', '.'); ?>">
        </div>
        <button type="submit">Atualizar</button>
      </form>
    </div>

    <div id="ImgQuadra">
      <form method="POST" action="../../controllers/ClientController.php?action=FotoPerfil"
        enctype="multipart/form-data">
        <input type="hidden" name="origem" value="editar_perfil">
        <input type="hidden" name="redirect_to" value="alterar">
        <label class="picture" for="picture__input" tabIndex="0">
          <span class="picture__image"></span>
        </label>

        <input name="arquivo" type="file" name="picture__input" id="picture__input">

        <button type="submit" id="foto">Atualizar foto</button>

        <script>
        // Passando a string PHP como valor JavaScript
        var nomeImagem = "<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>";
        </script>
        <script>
        const inputFile = document.querySelector("#picture__input");
        const pictureImage = document.querySelector(".picture__image");
        const pictureImageTxt = "aaaa";

        // Caminho inicial da imagem usando a variável nomeImagem definida pelo PHP
        const caminhoInicial = `../${nomeImagem}`;

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

        inputFile.addEventListener("change", function(e) {
          const inputTarget = e.target;
          const file = inputTarget.files[0];

          if (file) {
            const reader = new FileReader();

            reader.addEventListener("load", function(e) {
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
  </section>
</body>

</html>