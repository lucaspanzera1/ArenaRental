
<?php 
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $pdo = Conexao::getInstance();

    // Consulta para buscar os detalhes da quadra e o responsável
    $sql = "SELECT q.*, iq.nome_imagem, c.nome AS dono_nome, c.email AS dono_email, c.cpf AS dono_cpf, c.data_registro AS dono_data_registro, ip.nome_imagem AS dono_imagem
            FROM quadra q
            LEFT JOIN imagem_quadra iq ON q.id = iq.id_dono
            LEFT JOIN cadastro c ON q.id_user = c.id
            LEFT JOIN imagem ip ON c.id = ip.id_user -- Aqui obtemos a imagem do perfil do usuário
            WHERE q.id = :id";
    
    $statement = $pdo->prepare($sql);
    $statement->bindValue(':id', $id, PDO::PARAM_INT);
    $statement->execute();

    // Verifica se a quadra foi encontrada
    $quadra = $statement->fetch(PDO::FETCH_ASSOC);
    if (!$quadra) {
        echo "Quadra não encontrada.";
        exit;
    }
} else {
    echo "ID da quadra não foi fornecido.";
    exit;
}

    // Função para obter primeiro e último nome
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

    $nomeFormatado = getPrimeiroUltimoNome($quadra['dono_nome']);
    $dataFormatada = formatarData($quadra['dono_data_registro']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quadra['nome_quadra']); ?> | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/detalhes_quadra.css?v=<?= time() ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<?php include '../layouts/header.php'; ?>

<section>
    <h1><?php echo htmlspecialchars($quadra['nome_quadra']); ?></h1>
    
    <div id="images-container">
        <?php if (!empty($quadra['nome_imagem'])): ?>
        <img src="../../upload/quadra_img/<?php echo htmlspecialchars($quadra['nome_imagem']); ?>" alt="Imagem da quadra">
        <?php else: ?>
        <p>Sem imagem disponível</p>
        <?php endif; ?>

        <div id="mini-images-container" class="mini-images">
        <img src="../../upload/quadra_img/CELPE.JPG">
        <img src="../../upload/quadra_img/CELPE.JPG">
        </div>

        <div id="mini-images-container">
        <div id="mini1"><img src="../../upload/quadra_img/CELPE.JPG"></div>
        <div id="mini2"><img src="../../upload/quadra_img/CELPE.JPG"></div>
        </div>

    </div>

    <h2><?php echo htmlspecialchars($quadra['esporte']); ?> , <?php echo htmlspecialchars($quadra['localizacao']); ?></h2>
    <h3><?php echo htmlspecialchars($quadra['descricao']); ?></h3>

    <div id="dono-container" data-dono-id="<?php echo htmlspecialchars($quadra['id_user']); ?>">
        <?php if (!empty($quadra['dono_imagem'])): ?>
            <img src="../../upload/user_pfp/<?php echo htmlspecialchars($quadra['dono_imagem']); ?>" alt="Foto de perfil do dono">
        <?php else: ?>
            <p>Sem foto de perfil disponível</p>
        <?php endif; ?>
        <div id="client-container">
            <h4>Anfitriã(o): <span class="nome-anfitriao"><?php htmlspecialchars($quadra['nome_dono']); ?></span></h4>
            <h5>Entrou em <span class="data-registro"><?php echo htmlspecialchars($quadra['dono_imagem']); ?></span></h5>
        </div>
    </div>

    <h2>Localização</h2>
    <h3><?php echo htmlspecialchars($quadra['localizacao']); ?></h3>
</section>
<script> 
document.addEventListener('DOMContentLoaded', function() {
    const donoContainer = document.getElementById('dono-container');
    
    if (donoContainer) {
        donoContainer.addEventListener('click', function() {
            const donoId = this.getAttribute('data-dono-id');
            if (donoId) {
                window.location.href = 'perfil_dono.php?id=' + donoId;
            }
        });

        // Adiciona estilo de cursor para indicar que é clicável
        donoContainer.style.cursor = 'pointer';
    }
});
</script>
</body>
</html>