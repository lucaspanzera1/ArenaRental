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
  <?php
    include_once '../../config/conexao.php';  

    if (isset($_GET['id'])) {
        $quadra_id = $_GET['id'];

        $query = "SELECT * FROM quadra WHERE id = :id";
        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':id', $quadra_id, PDO::PARAM_INT);
        $stmt->execute();
        $quadra = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($quadra) {
            // Buscar horários disponíveis para hoje
            $dataHoje = date('Y-m-d');
            $horarios = Owner::getHorariosDisponiveis($quadra_id, $dataHoje);
        } else {
            echo "<p>Quadra não encontrada.</p>";
        }
    } else {
        echo "<p>ID da quadra não fornecido.</p>";
    }
    ?>
 <section>
        <div id="Info">
            <h1><?php echo "" . htmlspecialchars($owner->getNomeEspaco()) ?> <?php echo htmlspecialchars($quadra['nome']); ?></h1>
            <h2>Reservas para hoje.</h2>
            <h3><span id="dataHoje"></span></h3>
            <p>ID da Quadra: <?php echo htmlspecialchars($quadra['id']); ?></p>

            <?php if (isset($horarios) && !empty($horarios)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Horário Início</th>
                            <th>Horário Fim</th>
                            <th>Status</th>
                            <th>Cliente</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($horarios as $horario): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($horario['horario_inicio']); ?></td>
                                <td><?php echo htmlspecialchars($horario['horario_fim']); ?></td>
                                <td><?php echo htmlspecialchars($horario['status']); ?></td>
                                <td>
                                    <?php 
                                    if ($horario['status'] == 'reservado' && !empty($horario['nome_cliente'])) {
                                        echo  '@' . htmlspecialchars($horario['username_cliente']);
                                    } else {
                                        echo '-';
                                    }
                                    ?>
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