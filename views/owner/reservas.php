<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas para <?php echo isset($dataFormatada) ? htmlspecialchars($dataFormatada) : 'hoje'; ?> | © 2024 Arena Rental, Inc.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="../../resources/css/hoje.css?v=<?= time() ?>">
  <script>
    function obterDataDaURL() {
        const urlParams = new URLSearchParams(window.location.search);
        const dataUrl = urlParams.get('data');

        if (dataUrl) {
            const [ano, mes, dia] = dataUrl.split('-');
            return `${dia}/${mes}/${ano}`; // Formato dd/mm/yyyy
        } else {
            const diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            const dataAtual = new Date();
            const diaSemana = diasSemana[dataAtual.getDay()];
            const dia = String(dataAtual.getDate()).padStart(2, '0');
            const mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
            const ano = dataAtual.getFullYear();
            return `${diaSemana}, ${dia}/${mes}/${ano}`; // Formato dd/mm/yyyy
        }
    }

    window.onload = function() {
        document.getElementById('dataHoje').textContent = obterDataDaURL();
    }
</script>
</head>

<?php
// Recuperar a data passada pela URL, ou usar a data atual como padrão
$dataHoje = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');

// Converter a data do formato yyyy-mm-dd para dd/mm/yyyy
$dataFormatada = date('d/m/Y', strtotime($dataHoje));
?>
<body>

<?php include '../layouts/header.quadra.php'; ?>
<?php include '../layouts/verification.php'; ?>

<section>
<div id="Info">
<?php include '../layouts/mensagem.php'; ?>
        <h1><?php echo htmlspecialchars($owner->getNomeEspaco()) ?> <?php echo htmlspecialchars($quadra['nome']); ?></h1>
        <h2>Reservas para <?php echo isset($dataFormatada) ? htmlspecialchars($dataFormatada) : 'hoje'; ?>.</h2>

        <?php
// Agora usar $dataHoje para buscar os horários no banco de dados
$quadraId = $quadra['id']; // Certifique-se de que $quadra['id'] está definido corretamente

// Chame a função que busca os horários disponíveis
$dataHoje = isset($_GET['data']) ? $_GET['data'] : date('Y-m-d');
$horarios = Owner::getHorariosDisponiveis($quadraId, $dataHoje);
if (isset($horarios) && !empty($horarios)): ?>
    <table>
        <thead>
            <tr>
                <th>Horário Início</th>
                <th>Horário Fim</th>
                <th>Status</th>
                <th>Cliente</th>
                <th>Valor</th>
                <th>Ação</th>
            </tr>
        </thead>
        <tbody>
    <?php foreach ($horarios as $horario): ?>
        <tr class="<?php 
    if ($horario['status'] == 'pendente') {
        echo 'pendente';
    } elseif ($horario['status'] == 'reservado') {
        echo 'reservado';
    }
?>">
            <td><?php echo htmlspecialchars($horario['horario_inicio']); ?></td>
            <td><?php echo htmlspecialchars($horario['horario_fim']); ?></td>
            <td><?php echo htmlspecialchars($horario['status']); ?></td>
            <td><?php echo !empty($horario['username_cliente']) ? '@' . htmlspecialchars($horario['username_cliente']) : '-'; ?></td>
            <td><?php echo isset($horario['valor_reserva']) ? 'R$ ' . number_format($horario['valor_reserva'], 2, ',', '.') : '-'; ?></td>
            <td>
                <?php if ($horario['status'] == 'disponível'): ?>
                    <form action="../../controllers/OwnerController.php?action=reservar" method="POST">
                        <input type="hidden" name="quadra_id" value="<?php echo htmlspecialchars($quadra['id']); ?>">
                        <input type="hidden" name="data" value="<?php echo htmlspecialchars($dataHoje); ?>">
                        <input type="hidden" name="horario_inicio" value="<?php echo htmlspecialchars($horario['horario_inicio']); ?>">
                        <input type="hidden" name="horario_fim" value="<?php echo htmlspecialchars($horario['horario_fim']); ?>">
                        <button type="submit" name="reservar">Reservar</button>
                    </form>
                <?php endif; ?>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    </table>
<?php else: ?>
    <p>Não há horários disponíveis para a data selecionada.</p>
<?php endif; ?>
    </div>
</section>

</body>
</html>
 