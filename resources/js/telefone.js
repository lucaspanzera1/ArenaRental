document.addEventListener('DOMContentLoaded', function () {
    const inputTelefone = document.getElementById('telefone');
  
    inputTelefone.addEventListener('input', function (event) {
        let valor = event.target.value;
        
        // Remove caracteres não numéricos
        valor = valor.replace(/\D/g, '');
  
        // Adiciona a máscara
        if (valor.length <= 10) {
            valor = valor.replace(/(\d{2})(\d{0,5})/, '($1) $2');
            valor = valor.replace(/(\d{5})(\d{0,4})/, '$1-$2');
        } else {
            valor = valor.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
        }
  
        event.target.value = valor;
    });
  });