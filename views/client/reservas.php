<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Minhas reservas | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shortcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/reservas.css?v=<?= time() ?>">
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.3/dist/sweetalert2.all.min.js"></script>
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<div id="Info">
<?php include '../layouts/mensagem.php'; ?>
    <h1>Minhas Reservas</h1>

    <?php if (!empty($reservas)): ?>
        <?php
            $dataAtual = null;
            foreach ($reservas as $reserva):
                $dataFormatada = date('d/m/Y', strtotime($reserva['data']));
                
                if ($dataAtual !== $dataFormatada):
                    if ($dataAtual !== null): // Fecha a tabela da data anterior se houver uma
                        echo '</tbody></table></div>';
                    endif;
                    $dataAtual = $dataFormatada;
        ?>
                   <h2 class="data-titulo" data-id="data-<?= str_replace('/', '-', $dataAtual); ?>">
    <?= htmlspecialchars($dataAtual); ?> 
    <span class="seta">&#9654;</span> <!-- Seta -->
</h2>
 <!-- Exibe a data como título -->
                    <div id="data-<?= str_replace('/', '-', $dataAtual); ?>" class="reserva-tabela">
                    <table>
                        <thead>
                            <tr>
                                <th>Quadra</th>
                                <th>Horário de Início</th>
                                <th>Horário de Fim</th>
                                <th>Valor</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                <?php endif; ?>
                            <tr>
                                <td><?= htmlspecialchars($reserva['nome_proprietario'] . '  ' . $reserva['nome_quadra']); ?></td>
                                <td><?= htmlspecialchars($reserva['horario_inicio']); ?></td>
                                <td><?= htmlspecialchars($reserva['horario_fim']); ?></td>
                                <td>R$<?= htmlspecialchars($reserva['valor']); ?></td>
                                <td><?= htmlspecialchars($reserva['status']); ?></td>
                                <td id="cancelar-btn">
                    <form onsubmit="return confirmarCancelamento(event, <?= $reserva['id']; ?>)">
                        <input type="hidden" name="reserva_id" value="<?= $reserva['id']; ?>">
                        <button type="submit" class="btn-cancelar">Cancelar</button>
                    </form>
            </td>
                            </tr>
                <?php endforeach; ?>
                        </tbody>
                    </table>
                    </div> <!-- Fecha a div -->
    <?php else: ?>
        <p>Você não tem reservas até o momento.</p>
    <?php endif; ?>
</div>

<script>
    // Seleciona todos os elementos com a classe 'data-titulo'
    document.querySelectorAll('.data-titulo').forEach(function(dataTitulo) {
        var tabelaId = dataTitulo.getAttribute('data-id');
        var tabela = document.getElementById(tabelaId);

        // Inicializa as tabelas como visíveis e as setas rotacionadas para baixo
        dataTitulo.classList.add('open'); 
        tabela.classList.add('visivel'); // Define as tabelas como visíveis por padrão

        dataTitulo.addEventListener('click', function() {
            // Alterna a visibilidade da tabela e a rotação da seta ao clicar
            if (tabela.classList.contains('visivel')) {
                tabela.classList.remove('visivel'); // Esconde a tabela
                tabela.style.display = "none"; // Define o display como none
                dataTitulo.classList.remove('open'); // Gira a seta para a direita
                dataTitulo.classList.add('closed'); // Adiciona a classe closed
            } else {
                tabela.classList.add('visivel'); // Mostra a tabela
                tabela.style.display = "block"; // Define o display como block
                dataTitulo.classList.remove('closed'); // Gira a seta para baixo
                dataTitulo.classList.add('open'); // Adiciona a classe open
            }
        });
    });

    function confirmarCancelamento(event, reservaId) {
    event.preventDefault();
    
    Swal.fire({
        title: 'Tem certeza?',
        text: "Você realmente deseja cancelar esta reserva?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sim, cancelar!',
        cancelButtonText: 'Não, manter',
        customClass: {
            popup: 'swal-wide',
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Se confirmou, envia o formulário
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '../../controllers/ClientController.php?action=cancelarReserva';
            
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'reserva_id';
            input.value = reservaId;
            
            form.appendChild(input);
            document.body.appendChild(form);
            form.submit();
        }
    });
    
    return false;
}

</script>



</body>
</html>
