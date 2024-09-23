<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre sua quadra! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/form.quadra4.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

<section>
<?php $profilePicture = $client->getProfilePicture(); ?>
</section>

<div id="Form">
    <form action="../../controllers/OwnerController.php?action=etapa3" method="POST">

     <h1>Selecione os Dias da Semana em que a Quadra Está Aberta:</h1>
     <h2>Marque os dias em que a quadra está disponível para reserva.</h2>
        <input type="checkbox" id="domingo" name="dias[]" value="domingo">
        <label for="domingo">Domingo</label><br>
        <input type="checkbox" id="segunda" name="dias[]" value="segunda">
        <label for="segunda">Segunda-feira</label><br>
        <input type="checkbox" id="terca" name="dias[]" value="terca">
        <label for="terca">Terça-feira</label><br>
        <input type="checkbox" id="quarta" name="dias[]" value="quarta">
        <label for="quarta">Quarta-feira</label><br>
        <input type="checkbox" id="quinta" name="dias[]" value="quinta">
        <label for="quinta">Quinta-feira</label><br>
        <input type="checkbox" id="sexta" name="dias[]" value="sexta">
        <label for="sexta">Sexta-feira</label><br>
        <input type="checkbox" id="sabado" name="dias[]" value="sabado">
        <label for="sabado">Sábado</label><br>

    <h1>Defina os Horários de Funcionamento:</h1>
    <h2>Escolha o horário de abertura e fechamento da quadra para os dias selecionados.</h2>

    <label for="hora-abre">Horário que Abre</label>
    <select id="hora-abre" name="hora-abre" required></select><br>

    <label for="hora-fecha">Horário que Fecha</label>
    <select id="hora-fecha" name="hora-fecha" required></select>
    <br><br>

    <button type="submit" id="Continuar">Continuar</button>
  </form>

  <script>
    function gerarOpcoesHoras() {
        const selects = ['hora-abre', 'hora-fecha'];

        selects.forEach(id => {
            const select = document.getElementById(id);
            for (let h = 0; h < 24; h++) {
                const hora = String(h).padStart(2, '0');
                const minuto = '00'; // Minuto fixo em 00 para cada hora
                const opcao = document.createElement('option');
                opcao.value = `${hora}:${minuto}`;
                opcao.textContent = `${hora}:${minuto}`;
                select.appendChild(opcao);
            }
        });
    }

    function gerarIntervalos() {
        const horaAbre = document.getElementById('hora-abre');
        const horaFecha = document.getElementById('hora-fecha');

        if (horaAbre.value && horaFecha.value) {
            let [abreHora, abreMinuto] = horaAbre.value.split(':').map(Number);
            let [fechaHora, fechaMinuto] = horaFecha.value.split(':').map(Number);

            const intervaloSelect = document.createElement('select');
            intervaloSelect.id = 'intervalo';
            intervaloSelect.name = 'intervalos[]';
            intervaloSelect.multiple = true;

            while (abreHora < fechaHora || (abreHora === fechaHora && abreMinuto < fechaMinuto)) {
                const fimHora = abreMinuto + 60 >= 60 ? abreHora + 1 : abreHora;
                const fimMinuto = (abreMinuto + 60) % 60;

                if (fimHora > 23) break;

                const intervalo = document.createElement('option');
                intervalo.value = `${String(abreHora).padStart(2, '0')}:${String(abreMinuto).padStart(2, '0')} - ${String(fimHora).padStart(2, '0')}:${String(fimMinuto).padStart(2, '0')}`;
                intervalo.textContent = `${String(abreHora).padStart(2, '0')}:${String(abreMinuto).padStart(2, '0')} - ${String(fimHora).padStart(2, '0')}:${String(fimMinuto).padStart(2, '0')}`;
                intervaloSelect.appendChild(intervalo);

                abreHora = fimHora;
                abreMinuto = fimMinuto;
            }


        }
    }

    document.getElementById('hora-abre').addEventListener('change', gerarIntervalos);
    document.getElementById('hora-fecha').addEventListener('change', gerarIntervalos);

    gerarOpcoesHoras();
  </script>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="../../java/logoff.js"></script>
<script src="../java/dark.js"></script>
</body>
</html>
