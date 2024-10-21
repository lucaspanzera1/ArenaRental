<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Calendário de Reservas. | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/calendario.css?v=<?= time() ?>">
    </head>
    <?php include '../layouts/header.quadra.php'; ?>
    <?php include '../layouts/verification.php'; ?>
    
    <script>

const quadraId = <?php echo $quadra['id']; ?>;
// Variáveis globais para controlar o mês e ano atual
let mesAtual = new Date().getMonth();
let anoAtual = new Date().getFullYear();

function obterDataHoje() {
    const diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
    const dataAtual = new Date();
    const diaSemana = diasSemana[dataAtual.getDay()];
    const dia = String(dataAtual.getDate()).padStart(2, '0');
    const mes = String(dataAtual.getMonth() + 1).padStart(2, '0');
    const ano = dataAtual.getFullYear();
    
    return `${diaSemana}, ${dia}/${mes}/${ano}`;
}

function obterNomeMes(mes) {
    const meses = [
        'Janeiro', 'Fevereiro', 'Março', 'Abril', 'Maio', 'Junho',
        'Julho', 'Agosto', 'Setembro', 'Outubro', 'Novembro', 'Dezembro'
    ];
    return meses[mes];
}

function formatarData(dia) {
    // Formata a data para o padrão YYYY-MM-DD
    const mes = String(mesAtual + 1).padStart(2, '0');
    const diaFormatado = String(dia).padStart(2, '0');
    return `${anoAtual}-${mes}-${diaFormatado}`;
}

function redirecionarParaReservas(dia) {
    const dataFormatada = formatarData(dia);
    // Usa a variável `quadraId` no redirecionamento
    window.location.href = `reservas.php?id=${quadraId}&data=${dataFormatada}`;
}

function mudarMes(direcao) {
    mesAtual += direcao;
    
    if (mesAtual > 11) {
        mesAtual = 0;
        anoAtual++;
    } else if (mesAtual < 0) {
        mesAtual = 11;
        anoAtual--;
    }
    
    const calendario = document.querySelector('.calendar');
    calendario.innerHTML = gerarCalendario();
}

function gerarCalendario() {
    const hoje = new Date();
    const diaAtual = hoje.getDate();
    const mesHoje = hoje.getMonth();
    const anoHoje = hoje.getFullYear();

    const primeiroDia = new Date(anoAtual, mesAtual, 1);
    const ultimoDia = new Date(anoAtual, mesAtual + 1, 0);

    const diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
    
    let html = '';
    
    html += `
        <div class="calendar-navigation">
            <button onclick="mudarMes(-1)" class="nav-button">←</button>
            <div class="calendar-header">
                <h2>${obterNomeMes(mesAtual)} ${anoAtual}</h2>
            </div>
            <button onclick="mudarMes(1)" class="nav-button">→</button>
        </div>
    `;
    
    html += '<table>';
    
    html += '<thead><tr>';
    diasSemana.forEach(dia => {
        html += `<th>${dia}</th>`;
    });
    html += '</tr></thead>';

    html += '<tbody>';
    
    let diaCount = 1;
    const totalDias = ultimoDia.getDate();
    const primeiroDiaSemana = primeiroDia.getDay();

    for (let i = 0; i < 6; i++) {
        html += '<tr>';
        for (let j = 0; j < 7; j++) {
            if (i === 0 && j < primeiroDiaSemana) {
                html += '<td class="empty"><div class="content"></div></td>';
            } else if (diaCount > totalDias) {
                html += '<td class="empty"><div class="content"></div></td>';
            } else {
                const isToday = diaCount === diaAtual && mesAtual === mesHoje && anoAtual === anoHoje;
                const classes = [];
                
                if (isToday) classes.push('today');
                
                // Adiciona onclick para redirecionar
                html += `<td class="${classes.join(' ')}" onclick="redirecionarParaReservas(${diaCount})">
                          <div class="content">${diaCount}</div>
                        </td>`;
                diaCount++;
            }
        }
        html += '</tr>';
        if (diaCount > totalDias) break;
    }

    html += '</tbody></table>';
    
    return html;
}

window.onload = function() {
    document.getElementById('dataHoje').textContent = obterDataHoje();
    
    const container = document.createElement('div');
    container.className = 'calendar-container';
    
    const calendario = document.createElement('div');
    calendario.className = 'calendar';
    calendario.innerHTML = gerarCalendario();
    container.appendChild(calendario);
    
    document.body.appendChild(container);
}
    </script>
<body>

   <div id="Info">
   <div id="dataHoje"></div>
   </div>
</body>
</html>