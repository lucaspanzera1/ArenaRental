
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
        <h2>Selecione os dias da semana e seus respectivos horários que sua quadra está aberta.</h2>
        <form id="horarioForm" action="../../controllers/HorarioController.php?action=registrar" method="POST">
            <input type="hidden" name="quadra_id" value="<?php echo $_GET['quadra_id']; ?>">
            <?php
            $dias = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
            foreach ($dias as $dia) :
            ?>
            <div class="checkbox-wrapper">
                <div class="dia-row">
                    <input type="checkbox" id="<?php echo $dia; ?>" name="dias[]" value="<?php echo $dia; ?>">
                    <label for="<?php echo $dia; ?>"><?php echo ucfirst($dia); ?>-feira</label>
                    <select name="<?php echo $dia; ?>_inicio">
                        <?php echo gerarOpcoesHorario(); ?>
                    </select>
                    <span>até</span>
                    <select name="<?php echo $dia; ?>_fim">
                        <?php echo gerarOpcoesHorario(); ?>
                    </select>
                    <span>intervalo</span>
                    <select name="<?php echo $dia; ?>_intervalo_inicio">
                        <?php echo gerarOpcoesHorario(); ?>
                    </select>
                    <span>até</span>
                    <select name="<?php echo $dia; ?>_intervalo_fim">
                        <?php echo gerarOpcoesHorario(); ?>
                    </select>
                </div>
            </div>
            <?php endforeach; ?>
            <button type="submit">Registrar</button>
        </form>
    </div>
    <script>
        // Script para preencher os selects com valores padrão
        document.addEventListener('DOMContentLoaded', function() {
            const dias = ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado'];
            dias.forEach(dia => {
                document.querySelector(`select[name="${dia}_inicio"]`).value = dia === 'domingo' ? '14:00' : '13:00';
                document.querySelector(`select[name="${dia}_fim"]`).value = dia === 'domingo' ? '19:00' : '22:00';
                document.querySelector(`select[name="${dia}_intervalo_inicio"]`).value = dia === 'domingo' ? '15:00' : '14:00';
                document.querySelector(`select[name="${dia}_intervalo_fim"]`).value = dia === 'domingo' ? '16:00' : '15:00';
            });
        });
    </script>
</body>
</html>

<?php
function gerarOpcoesHorario() {
    $options = '';
    for ($hora = 0; $hora < 24; $hora++) {
        for ($minuto = 0; $minuto < 60; $minuto += 30) {
            $time = sprintf("%02d:%02d", $hora, $minuto);
            $options .= "<option value=\"$time\">$time</option>";
        }
    }
    return $options;
}
?>
</body>
</html>