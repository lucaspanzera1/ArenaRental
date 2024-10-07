<?php
require_once '../../models/Owner.php';
require_once '../../models/Client.php';
require_once '../../models/User.php';
// Verifica se o ID foi fornecido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
  die('ID da quadra não fornecido ou inválido.');
}

$id_quadra = (int) $_GET['id'];

// Busca os detalhes da quadra
$quadra = User::getQuadraById($id_quadra);

if (!$quadra) {
  die('Quadra não encontrada.');
}

?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?php echo htmlspecialchars($quadra['nome_espaco']); ?> <?php echo htmlspecialchars($quadra['nome']); ?> | ©
    2024 Arena Rental, Inc.</title>
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
          <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>"
            alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        <?php endif; ?>

        <div id="mini-images-container" class="mini-images">
          <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>"
            alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
          <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>"
            alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large">
        </div>

        <div id="mini-images-container">
          <div id="mini1"> <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>"
              alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large"></div>
          <div id="mini2"> <img src="../<?php echo htmlspecialchars($quadra['imagem_quadra']); ?>"
              alt="<?php echo htmlspecialchars($quadra['nome']); ?>" class="quadra-image-large"></div>
        </div>

      </div>

      <h2><?php echo htmlspecialchars($quadra['esporte']); ?> , <?php echo htmlspecialchars($quadra['localizacao']); ?>
        - <?php echo htmlspecialchars($quadra['cep']); ?></h2>
      <h3><?php echo $quadra['coberta'] ? 'Quadra coberta' : 'Quadra descoberta'; ?>, com
      <?php echo htmlspecialchars($quadra['recursos']); ?>
      </h3>
      <div>
        <div id="grid-reserva">
          <div id="dono-container">
            <img src="../<?php echo htmlspecialchars($quadra['imagem_proprietario']); ?>"
              alt="Imagem de perfil de <?php echo htmlspecialchars($quadra['nome_proprietario']); ?>"
              class="imagem-perfil">
            <a href="perfil_dono.php?id=<?php echo htmlspecialchars($quadra['proprietario_id']); ?>"
              id="client-container">
              <div>
                <h4>Anfitriã(o): <?php echo htmlspecialchars($quadra['nome_proprietario']); ?></h4>
                <h5>Entrou em </h5>
              </div>
            </a>
          </div>

          <form action="../../controllers/ClientController.php?action=reservarQuadra" method="POST">
            <div class="container-reserva">
              <h2>Verificar horário</h2>
              <div class="date-time">
                <input type="date" id="data_reserva" name="data_reserva" min="<?= date('Y-m-d') ?>">
                <select id="horario_inicio" name="horario_inicio">
                  <option value="">Início</option>
                </select>
                <select id="horario_fim" name="horario_fim">
                  <option value="">Fim</option>
                </select>
              </div>
              <button class="reserve-button" id="btn_reservar" type="submit">Reservar</button>
              <div class="price-info">
                <span>Duração: <span id="duracao">0</span> hora(s)</span>
                <span>R$<span id="preco_hora"><?= htmlspecialchars($quadra['valor']) ?></span>/hora</span>
              </div>
              <div class="total">
                <span>Total a pagar</span>
                <span>R$<span id="total_pagar">0</span></span>
              </div>
              <input type="hidden" name="id_quadra" value="<?= $id_quadra ?>">
            </div>
          </form>
        </div>
        <script>
document.addEventListener('DOMContentLoaded', function () {
  const dataReserva = document.getElementById('data_reserva');
  const horarioInicio = document.getElementById('horario_inicio');
  const horarioFim = document.getElementById('horario_fim');
  const btnReservar = document.getElementById('btn_reservar');
  const duracaoSpan = document.getElementById('duracao');
  const totalPagarSpan = document.getElementById('total_pagar');
  const precoHora = parseFloat(document.getElementById('preco_hora').textContent);

  let horarioIntervaloInicio = null;
  let horarioIntervaloFim = null;
  const fimDoExpediente = '22:00'; // Ajuste conforme necessário

  dataReserva.addEventListener('change', function () {
    const dataSelecionada = this.value;

    fetch(`../../controllers/AuthController.php?action=getHorariosDisponiveis&quadra_id=<?= $id_quadra ?>&data=${dataSelecionada}`)
      .then(response => response.json())
      .then(data => {
        if (data.length === 0) {
          alert('Nenhum horário disponível para essa data.');
          return;
        }
        identificarIntervalo(data);
        preencherHorarios(data);
      })
      .catch(error => console.error('Erro ao buscar horários:', error));
  });

  function identificarIntervalo(data) {
    horarioIntervaloInicio = null;
    horarioIntervaloFim = null;
    data.forEach(periodo => {
      if (periodo.tipo === 'intervalo') {
        horarioIntervaloInicio = periodo.horario_inicio;
        horarioIntervaloFim = periodo.horario_fim;
      }
    });
    console.log("Intervalo identificado:", horarioIntervaloInicio, horarioIntervaloFim);
  }

  function preencherHorarios(data) {
    horarioInicio.innerHTML = '<option value="">Início</option>';
    horarioFim.innerHTML = '<option value="">Fim</option>';

    data.forEach(periodo => {
      if (periodo.tipo === 'disponível') {
        let inicio = new Date(`2000-01-01T${periodo.horario_inicio}`);
        let fim = new Date(`2000-01-01T${periodo.horario_fim}`);

        while (inicio < fim) {
          let horaFormatada = inicio.toTimeString().slice(0, 5);
          let optionInicio = document.createElement('option');
          optionInicio.value = horaFormatada;
          optionInicio.textContent = horaFormatada;
          horarioInicio.appendChild(optionInicio);

          inicio.setMinutes(inicio.getMinutes() + 30);
        }
      }
    });

    btnReservar.disabled = true;
  }

  horarioInicio.addEventListener('change', function () {
    horarioFim.innerHTML = '<option value="">Fim</option>';

    if (this.value) {
      let inicioSelecionado = new Date(`2000-01-01T${this.value}`);
      let limiteFim = new Date(`2000-01-01T${fimDoExpediente}`);

      // Se há um intervalo e o início selecionado é antes do intervalo,
      // o limite de fim é o início do intervalo
      if (horarioIntervaloInicio && inicioSelecionado < new Date(`2000-01-01T${horarioIntervaloInicio}`)) {
        limiteFim = new Date(`2000-01-01T${horarioIntervaloInicio}`);
      }

      let atual = new Date(inicioSelecionado.getTime() + 30 * 60000); // 30 minutos após o início

      while (atual <= limiteFim) {
        let horaFormatada = atual.toTimeString().slice(0, 5);
        let optionFim = document.createElement('option');
        optionFim.value = horaFormatada;
        optionFim.textContent = horaFormatada;
        horarioFim.appendChild(optionFim);

        atual.setMinutes(atual.getMinutes() + 30);
      }
    }

    atualizarCalculo();
  });

  horarioFim.addEventListener('change', atualizarCalculo);

  function atualizarCalculo() {
    if (horarioInicio.value && horarioFim.value) {
      const duracao = calcularDuracao(horarioInicio.value, horarioFim.value);
      duracaoSpan.textContent = duracao.toFixed(2);
      totalPagarSpan.textContent = (duracao * precoHora).toFixed(2);
      btnReservar.disabled = false;
    } else {
      duracaoSpan.textContent = '0';
      totalPagarSpan.textContent = '0';
      btnReservar.disabled = true;
    }
  }

  function calcularDuracao(inicio, fim) {
    const [horaInicio, minInicio] = inicio.split(':').map(Number);
    const [horaFim, minFim] = fim.split(':').map(Number);
    return ((horaFim * 60 + minFim) - (horaInicio * 60 + minInicio)) / 60;
  }

  btnReservar.addEventListener('click', function (event) {
    if (!dataReserva.value || !horarioInicio.value || !horarioFim.value) {
      alert('Por favor, selecione uma data e horários válidos.');
      event.preventDefault();
    }
  });
});
        </script>
</body>

</html>