<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas. | © 2024 Arena Rental, Inc.</title>
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

    
</body>
</html>