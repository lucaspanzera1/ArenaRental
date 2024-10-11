
<?php
$alertType = '';

if (isset($mensagem)) {
    $alertType = 'alert-success';
    echo '<div id="alertBox" class="alert ' . $alertType . ' alert-animate">' . $mensagem . '</div>';
} elseif (isset($erro)) {
    $alertType = 'alert-danger';
    echo '<div id="alertBox" class="alert ' . $alertType . ' alert-animate">' . $erro . '</div>';
}
?>

<style>
.alert {
    padding: 15px;
    margin-bottom: 20px;
    border-radius: 4px;
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.5s ease;
}

.alert.show {
    opacity: 1;
    transform: translateY(0);
}

.alert-success {
    background-color: #dff0d8;
    border-color: #d6e9c6;
    color: #3c763d;
}

.alert-danger {
    background-color: #f2dede;
    border-color: #ebccd1;
    color: #a94442;
}

.alert.hide {
    opacity: 0;
    transform: translateY(-20px);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const alertBox = document.getElementById('alertBox');
    
    // Mostra o alerta
    if (alertBox) {
        setTimeout(() => {
            alertBox.classList.add('show');
        }, 100);

        // Esconde o alerta após 5 segundos
        setTimeout(() => {
            alertBox.classList.add('hide');
            
            // Remove o elemento após a animação
            setTimeout(() => {
                alertBox.remove();
            }, 500);
        }, 2500);
    }
});
</script>
