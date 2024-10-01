<?php
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';
// Verifica se o ID foi fornecido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    die('ID da quadra não fornecido ou inválido.');
}

$id_quadra = (int)$_GET['id'];

// Busca os detalhes da quadra
$quadra = User::getQuadraById($id_quadra);

if (!$quadra) {
    die('Quadra não encontrada.');
}

?>
<?php
function verificarEReservarQuadra($idQuadra, $dataReserva, $horaInicio, $horaFim) {
    try {
        $pdo = Conexao::getInstance();
        
        // Verificar disponibilidade
        $sql = "SELECT * FROM horarios_disponiveis WHERE quadra_id = :quadra_id AND data = :data 
                AND ((hora_inicio <= :hora_inicio AND hora_fim > :hora_inicio) 
                OR (hora_inicio < :hora_fim AND hora_fim >= :hora_fim))";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':quadra_id', $idQuadra, PDO::PARAM_INT);
        $stmt->bindParam(':data', $dataReserva);
        $stmt->bindParam(':hora_inicio', $horaInicio);
        $stmt->bindParam(':hora_fim', $horaFim);
        $stmt->execute();
        
        if ($stmt->rowCount() > 0) {
            return ['status' => false, 'mensagem' => 'Horário não disponível'];
        }
        
        // Buscar valor por hora da quadra
        $sqlValor = "SELECT valor_hora FROM quadra WHERE id = :id";
        $stmtValor = $pdo->prepare($sqlValor);
        $stmtValor->bindParam(':id', $idQuadra, PDO::PARAM_INT);
        $stmtValor->execute();
        $valorHora = $stmtValor->fetchColumn();
        
        // Calcular duração e valor total
        $duracao = (strtotime($horaFim) - strtotime($horaInicio)) / 3600; // em horas
        $valorTotal = $duracao * $valorHora;
        
        // Inserir reserva
        $sqlReserva = "INSERT INTO reservas (quadra_id, data_reserva, hora_inicio, hora_fim, valor_total) 
                       VALUES (:quadra_id, :data_reserva, :hora_inicio, :hora_fim, :valor_total)";
        $stmtReserva = $pdo->prepare($sqlReserva);
        $stmtReserva->bindParam(':quadra_id', $idQuadra, PDO::PARAM_INT);
        $stmtReserva->bindParam(':data_reserva', $dataReserva);
        $stmtReserva->bindParam(':hora_inicio', $horaInicio);
        $stmtReserva->bindParam(':hora_fim', $horaFim);
        $stmtReserva->bindParam(':valor_total', $valorTotal);
        $stmtReserva->execute();
        
        // Atualizar horários disponíveis
        $sqlAtualizar = "INSERT INTO horarios_disponiveis (quadra_id, data, hora_inicio, hora_fim, status) 
                         VALUES (:quadra_id, :data, :hora_inicio, :hora_fim, 'ocupado')";
        $stmtAtualizar = $pdo->prepare($sqlAtualizar);
        $stmtAtualizar->bindParam(':quadra_id', $idQuadra, PDO::PARAM_INT);
        $stmtAtualizar->bindParam(':data', $dataReserva);
        $stmtAtualizar->bindParam(':hora_inicio', $horaInicio);
        $stmtAtualizar->bindParam(':hora_fim', $horaFim);
        $stmtAtualizar->execute();
        
        return ['status' => true, 'mensagem' => 'Reserva realizada com sucesso', 'valor_total' => $valorTotal];
    } catch (PDOException $e) {
        error_log("Erro ao verificar e reservar quadra: " . $e->getMessage());
        return ['status' => false, 'mensagem' => 'Erro ao processar a reserva'];
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($quadra['nome_espaco']); ?> <?php echo htmlspecialchars($quadra['nome']); ?> | © 2024 Arena Rental, Inc.</title>
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
    <h1><?php echo htmlspecialchars($quadra['nome_espaco']); ?> <?php echo htmlspecialchars($quadra['nome']); ?></h1>
    <div class="container">
      
    <div id="images-container">
    <?php if (!empty($quadra['imagem_quadra'])): ?>
            <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        <?php endif; ?>

        <div id="mini-images-container" class="mini-images">
        <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        </div>

        <div id="mini-images-container">
        <div id="mini1"> <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large"></div>
        <div id="mini2"> <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>" alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large"></div>
        </div>

    </div>

    <div>
    <h2><?php echo htmlspecialchars($quadra['esporte']); ?> , <?php echo htmlspecialchars($quadra['localizacao']); ?> - <?php echo htmlspecialchars($quadra['cep']); ?></h2>
    <h3><?php echo $quadra['coberta'] ? 'Quadra coberta' : 'Quadra descoberta'; ?>, <?php echo htmlspecialchars($quadra['descricao_proprietario']); ?></h3>
    <div id="dono-container">
    <img src="../<?php echo htmlspecialchars($quadra['imagem_proprietario']); ?>" alt="Imagem de perfil de <?php echo htmlspecialchars($quadra['nome_proprietario']); ?>" class="imagem-perfil">
    <a href="perfil_dono.php?id=<?php echo htmlspecialchars($quadra['proprietario_id']); ?>" id="client-container">
        <div>
            <h4>Anfitriã(o): <?php echo htmlspecialchars($quadra['nome_proprietario']); ?></h4>
            <h5>Entrou em </h5>
        </div>
    </a>
</div>
<form action="../../controllers/ClientController.php?action=reservarQuadra">
<div class="container-reserva">
    <h2>Verificar horário</h2>
    <div class="date-time">
        <input type="date" id="data_reserva" min="<?= date('Y-m-d') ?>">
        <select id="hora_inicio">
            <option value="">Hora início</option>
            <?php
            for ($i = 6; $i <= 22; $i++) {
                printf("<option value='%02d:00'>%02d:00</option>", $i, $i);
            }
            ?>
        </select>
        <select id="hora_fim">
            <option value="">Hora fim</option>
            <?php
            for ($i = 7; $i <= 23; $i++) {
                printf("<option value='%02d:00'>%02d:00</option>", $i, $i);
            }
            ?>
        </select>
    </div>
    <button class="reserve-button" id="btn_reservar">Reservar</button>
    <div class="price-info">
        <span>Duração: <span id="duracao">0</span> hora(s)</span>
        <span>R$<span id="preco_hora"><?= htmlspecialchars($quadra['valor']) ?></span>/hora</span>
    </div>
    <div class="total">
        <span>Total a pagar</span>
        <span>R$<span id="total_pagar">0</span></span>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const dataReserva = document.getElementById('data_reserva');
    const horaInicio = document.getElementById('hora_inicio');
    const horaFim = document.getElementById('hora_fim');
    const btnReservar = document.getElementById('btn_reservar');
    const duracaoSpan = document.getElementById('duracao');
    const totalPagarSpan = document.getElementById('total_pagar');
    const precoHora = parseFloat(document.getElementById('preco_hora').textContent);

    function calcularDuracaoEPreco() {
        if (horaInicio.value && horaFim.value) {
            const inicio = new Date(`2000-01-01T${horaInicio.value}`);
            const fim = new Date(`2000-01-01T${horaFim.value}`);
            const duracao = (fim - inicio) / (1000 * 60 * 60);
            
            if (duracao > 0) {
                duracaoSpan.textContent = duracao;
                totalPagarSpan.textContent = (duracao * precoHora).toFixed(2);
                btnReservar.disabled = false;
            } else {
                duracaoSpan.textContent = '0';
                totalPagarSpan.textContent = '0';
                btnReservar.disabled = true;
            }
        }
    }

    dataReserva.addEventListener('change', calcularDuracaoEPreco);
    horaInicio.addEventListener('change', calcularDuracaoEPreco);
    horaFim.addEventListener('change', calcularDuracaoEPreco);

    btnReservar.addEventListener('click', function() {
        if (!dataReserva.value || !horaInicio.value || !horaFim.value) {
            alert('Por favor, selecione uma data e horários válidos.');
            return;
        }

        // Aqui você pode adicionar a lógica para enviar a reserva para o servidor
        const dadosReserva = {
            data: dataReserva.value,
            hora_inicio: horaInicio.value,
            hora_fim: horaFim.value,
            duracao: parseFloat(duracaoSpan.textContent),
            total: parseFloat(totalPagarSpan.textContent),
            id_quadra: <?= $id_quadra ?>
        };

        console.log('Dados da reserva:', dadosReserva);
        // Implemente aqui a chamada AJAX para enviar os dados ao servidor
        // e atualizar o status de horarios_disponiveis
    });
});
</script>
</body>
</html>