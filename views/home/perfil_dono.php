<?php
require_once '../../models/Conexao.php';
require_once '../../models/User.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pdo = Conexao::getInstance();

    $sql = "SELECT c.*, ip.nome_imagem AS dono_imagem
            FROM cadastro c
            LEFT JOIN imagem ip ON c.id = ip.id_user
            WHERE c.id = :id";
    
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    $dono = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$dono) {
        echo "Perfil não encontrado.";
        exit;
    }
} else {
    echo "ID do dono não foi fornecido.";
    exit;
}

function getPrimeiroUltimoNome($nomeCompleto) {
  $nomes = explode(' ', $nomeCompleto);
  if (count($nomes) > 1) {
      return $nomes[0] . ' ' . end($nomes);
  }
  return $nomeCompleto;
}

// Função para formatar a data
function formatarData($data) {
  $dataObj = new DateTime($data);
  return $dataObj->format('d/m/Y'); // Formato: dia/mês/ano
}

if (isset($_GET['id'])) {
  $id = intval($_GET['id']);
  $pdo = Conexao::getInstance();

  $sql = "SELECT c.*, ip.nome_imagem AS dono_imagem
          FROM cadastro c
          LEFT JOIN imagem ip ON c.id = ip.id_user
          WHERE c.id = :id";
  
  $statement = $pdo->prepare($sql);
  $statement->bindValue(':id', $id, PDO::PARAM_INT);
  $statement->execute();

  $dono = $statement->fetch(PDO::FETCH_ASSOC);
  if (!$dono) {
      echo "Perfil não encontrado.";
      exit;
  }

  $nomeFormatado = getPrimeiroUltimoNome($dono['nome']);
  $dataFormatada = formatarData($dono['data_registro']);
} else {
  echo "ID do dono não foi fornecido.";
  exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<title>Perfil <?php echo htmlspecialchars($nomeFormatado); ?> | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/perfil.css?v=<?= time() ?>">
</head>
<body>
<?php include '../layouts/header.php'; ?>

<div id="Corpo">
<div id="Quad">
<?php if (!empty($dono['dono_imagem'])): ?>
<div id="Perfil"><img src="../../upload/user_pfp/<?php echo htmlspecialchars($dono['dono_imagem']); ?>" alt="Foto de perfil"></div>
<?php else: ?><p>Sem foto de perfil disponível</p><?php endif; ?>
  <h1><?php echo htmlspecialchars($nomeFormatado); ?></h1>
<h2><?php echo htmlspecialchars($dono['email']); ?></h2>
<h3>Membro desde:</strong> <?php echo htmlspecialchars($dataFormatada); ?></h3>
</div>
</div>

</body>
</html>