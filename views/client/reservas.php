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

    <div class="filtro-container">
        <label for="ordenacao" class="filtro-label">Ordenar por:</label>
        <select id="ordenacao" class="filtro-select">
            <option value="recente">Mais recente</option>
            <option value="antiga">Mais antiga</option>
        </select>
    </div>

    <?php if (!empty($reservas)): ?>
        <div id="reservas-container">
            <?php
                $reservasPorData = [];
                foreach ($reservas as $reserva) {
                    $dataFormatada = date('Y-m-d', strtotime($reserva['data']));
                    if (!isset($reservasPorData[$dataFormatada])) {
                        $reservasPorData[$dataFormatada] = [];
                    }
                    $reservasPorData[$dataFormatada][] = $reserva;
                }
                
                // Ordena as datas (inicialmente mais recente primeiro)
                krsort($reservasPorData);

                foreach ($reservasPorData as $data => $reservasDoDia):
                    $dataFormatadaDisplay = date('d/m/Y', strtotime($data));
            ?>
                <div class="reserva-container" data-date="<?= $data ?>">
                    <h2 class="data-titulo" data-id="data-<?= str_replace('/', '-', $dataFormatadaDisplay); ?>">
                        <?= htmlspecialchars($dataFormatadaDisplay); ?> 
                        <span class="seta">&#9654;</span>
                    </h2>
                    <div id="data-<?= str_replace('/', '-', $dataFormatadaDisplay); ?>" class="reserva-tabela">
                        <table>
                            <thead>
                                <tr>
                                    <th>Quadra</th>
                                    <th>Horário de Início</th>
                                    <th>Horário de Fim</th>
                                    <th>Valor</th>
                                    <th>Status</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($reservasDoDia as $reserva): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($reserva['nome_proprietario'] . ' ' . $reserva['nome_quadra']); ?></td>
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
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <p>Você não tem reservas até o momento.</p>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const ordenacaoSelect = document.getElementById('ordenacao');
    const reservasContainer = document.getElementById('reservas-container');

    function ordenarReservas(ordem) {
        const reservas = Array.from(document.querySelectorAll('.reserva-container'));
        
        reservas.sort((a, b) => {
            const dataA = new Date(a.dataset.date);
            const dataB = new Date(b.dataset.date);
            
            return ordem === 'recente' ? dataB - dataA : dataA - dataB;
        });

        // Limpa o container
        while (reservasContainer.firstChild) {
            reservasContainer.removeChild(reservasContainer.firstChild);
        }

        // Adiciona os elementos ordenados
        reservas.forEach(reserva => {
            reservasContainer.appendChild(reserva);
        });
    }

    ordenacaoSelect.addEventListener('change', function() {
        ordenarReservas(this.value);
    });

    // Funcionalidade de toggle das tabelas
    document.querySelectorAll('.data-titulo').forEach(function(dataTitulo) {
        var tabelaId = dataTitulo.getAttribute('data-id');
        var tabela = document.getElementById(tabelaId);

        // Inicializa as tabelas como visíveis e as setas rotacionadas para baixo
        dataTitulo.classList.add('open'); 
        tabela.classList.add('visivel');

        dataTitulo.addEventListener('click', function() {
            if (tabela.classList.contains('visivel')) {
                tabela.classList.remove('visivel');
                tabela.style.display = "none";
                dataTitulo.classList.remove('open');
                dataTitulo.classList.add('closed');
            } else {
                tabela.classList.add('visivel');
                tabela.style.display = "block";
                dataTitulo.classList.remove('closed');
                dataTitulo.classList.add('open');
            }
        });
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