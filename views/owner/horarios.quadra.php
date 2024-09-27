
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de quadras. | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/horarios.quadra.css?v=<?= time() ?>">
</head>
<body>

<?php include '../layouts/header.php'; ?>
<?php include '../layouts/verification.php'; ?>

    <div class="container">
        <h2>Selecione os dias da semana e seus respectivos horários </br> que sua quadra está aberta.</h2>
        <form id="horarioForm">
        <div class="checkbox-wrapper">
            <div class="dia-row">
                <input type="checkbox" id="domingo" name="domingo">
                <label for="domingo">Domingo</label>
                <select name="domingo-inicio"></select>
                <span id="ate">até</span>
                <select name="domingo-fim"></select>
                <span id="intervalo">intervalo</span>
                <select name="domingo-intervalo-inicio"></select>
                <span id="ate">até</span>
                <select name="domingo-intervalo-fim"></select>
            </div>
            </div>
            <div class="checkbox-wrapper">
            <div class="dia-row">
                <input type="checkbox" id="segunda" name="segunda">
                <label for="segunda">Segunda-feira</label>
                <select name="segunda-inicio"></select>
                <span id="ate">até</span>
                <select name="segunda-fim"></select>
                <span id="intervalo">intervalo</span>
                <select name="segunda-intervalo-inicio"></select>
                <span id="ate">até</span>
                <select name="segunda-intervalo-fim"></select>
            </div>
            </div>
            <div class="checkbox-wrapper">
            <div class="dia-row">
                <input type="checkbox" id="terca" name="terca">
                <label for="terca">Terça-feira</label>
                <select name="terca-inicio"></select>
                <span id="ate">até</span>
                <select name="terca-fim"></select>
                <span id="intervalo">intervalo</span>
                <select name="terca-intervalo-inicio"></select>
                <span id="ate">até</span>
                <select name="terca-intervalo-fim"></select>
            </div>
            </div>
            <div class="checkbox-wrapper">
            <div class="dia-row">
                <input type="checkbox" id="quarta" name="quarta">
                <label for="quarta">Quarta-feira</label>
                <select name="quarta-inicio"></select>
                <span id="ate">até</span>
                <select name="quarta-fim"></select>
                <span id="intervalo">intervalo</span>
                <select name="quarta-intervalo-inicio"></select>
                <span id="ate">até</span>
                <select name="quarta-intervalo-fim"></select>
            </div>
            </div>
            <div class="checkbox-wrapper">
            <div class="dia-row">
                <input type="checkbox" id="quinta" name="quinta">
                <label for="quinta">Quinta-feira</label>
                <select name="quinta-inicio"></select>
                <span id="ate">até</span>
                <select name="quinta-fim"></select>
                <span id="intervalo">intervalo</span>
                <select name="quinta-intervalo-inicio"></select>
                <span id="ate">até</span>
                <select name="quinta-intervalo-fim"></select>
            </div>
            </div>
            <div class="checkbox-wrapper">
            <div class="dia-row">
                <input type="checkbox" id="sexta" name="sexta">
                <label for="sexta">Sexta-feira</label>
                <select name="sexta-inicio"></select>
                <span id="ate">até</span>
                <select name="sexta-fim"></select>
                <span id="intervalo">intervalo</span>
                <select name="sexta-intervalo-inicio"></select>
                <span id="ate">até</span>
                <select name="sexta-intervalo-fim"></select>
            </div>
            </div>
            <div class="checkbox-wrapper">
            <div class="dia-row">
                <input type="checkbox" id="sabado" name="sabado">
                <label for="sabado">Sábado</label>
                <select name="sabado-inicio"></select>
                <span id="ate">até</span>
                <select name="sabado-fim"></select>
                <span id="intervalo">intervalo</span>
                <select name="sabado-intervalo-inicio"></select>
                <span id="ate">até</span>
                <select name="sabado-intervalo-fim"></select>
            </div>
            </div>
            <button type="submit">Registrar</button>
        </form>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('horarioForm');
    const dias = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];

    function gerarOpcoesDeTempo() {
        const opcoes = [];
        for (let hora = 0; hora < 24; hora++) {
            for (let minuto = 0; minuto < 60; minuto += 30) {
                opcoes.push(`${hora.toString().padStart(2, '0')}:${minuto.toString().padStart(2, '0')}`);
            }
        }
        return opcoes;
    }

    function preencherSelect(select, opcoes, valorPadrao) {
        opcoes.forEach(opcao => {
            const elemento = document.createElement('option');
            elemento.value = opcao;
            elemento.textContent = opcao;
            select.appendChild(elemento);
        });
        select.value = valorPadrao;
    }

    const opcoesDeTempo = gerarOpcoesDeTempo();

    dias.forEach(dia => {
        const inicio = document.querySelector(`select[name="${dia}-inicio"]`);
        const fim = document.querySelector(`select[name="${dia}-fim"]`);
        const intervaloInicio = document.querySelector(`select[name="${dia}-intervalo-inicio"]`);
        const intervaloFim = document.querySelector(`select[name="${dia}-intervalo-fim"]`);

        preencherSelect(inicio, opcoesDeTempo, dia === 'domingo' ? '14:00' : '13:00');
        preencherSelect(fim, opcoesDeTempo, dia === 'domingo' ? '19:00' : '22:00');
        preencherSelect(intervaloInicio, opcoesDeTempo, dia === 'domingo' ? '15:00' : '14:00');
        preencherSelect(intervaloFim, opcoesDeTempo, dia === 'domingo' ? '16:00' : '15:00');
    });

    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        const horarios = [];

        dias.forEach(dia => {
            const checkbox = document.querySelector(`input[name="${dia}"]`);
            if (checkbox.checked) {
                horarios.push({
                    dia: dia,
                    inicio: document.querySelector(`select[name="${dia}-inicio"]`).value,
                    fim: document.querySelector(`select[name="${dia}-fim"]`).value,
                    intervaloInicio: document.querySelector(`select[name="${dia}-intervalo-inicio"]`).value,
                    intervaloFim: document.querySelector(`select[name="${dia}-intervalo-fim"]`).value
                });
            }
        });

        console.log('Horários selecionados:', horarios);
        // Aqui você pode enviar os dados para um servidor ou processá-los conforme necessário
    });
});
    </script>
</body>
</html>