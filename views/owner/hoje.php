<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Reservas para hoje. | © 2024 Arena Rental, Inc.</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
  <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
  <link rel="stylesheet" href="../../resources/css/hoje.css?v=<?= time() ?>">
  <script>
        function obterDataHoje() {
            const diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
            const dataAtual = new Date();
            const diaSemana = diasSemana[dataAtual.getDay()];
            const dia = String(dataAtual.getDate()).padStart(2, '0');
            const mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
            const ano = dataAtual.getFullYear();
            
            return `${diaSemana}, ${dia}/${mes}/${ano}`;
        }

        window.onload = function() {
            document.getElementById('dataHoje').textContent = obterDataHoje();
        }
    </script>
</head>

<body>

<?php include '../layouts/header.quadra.php'; ?>
<?php include '../layouts/verification.php'; ?>


<section>
<div id="Info">
<?php include '../layouts/mensagem.php'; ?>
        <h1><?php echo htmlspecialchars($owner->getNomeEspaco()) ?> <?php echo htmlspecialchars($quadra['nome']); ?></h1>
        <h2>Reservas para hoje.</h2>
        <h3><span id="dataHoje"></span></h3>

        <?php if (isset($horarios) && !empty($horarios)): ?>
            <table>
                <thead>
                    <tr>
                        <th>Horário Início</th>
                        <th>Horário Fim</th>
                        <th>Status</th>
                        <th>Cliente</th>
                        <th>Valor</th> <!-- Coluna de valor -->
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
                    <input type="hidden" name="data" value="<?php echo $dataHoje; ?>">
                    <input type="hidden" name="horario_inicio" value="<?php echo $horario['horario_inicio']; ?>">
                    <input type="hidden" name="horario_fim" value="<?php echo $horario['horario_fim']; ?>">

                    <select name="cliente_id" required>
                        <option value=""></option>
                        <option value="">Cliente por fora</option>
                    </select>
                    
                    <button type="submit" name="reservar">Reservar</button>
                </form>
            <?php endif; ?>
        </td>
    </tr>
<?php endforeach; ?>
</tbody>

            </table>
        <?php else: ?>
            <p>Não há horários disponíveis para hoje.</p>
        <?php endif; ?>
    </div>
</section>

</body>
</html>