<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registre sua quadra! | © 2024 Arena Rental, Inc.</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap" rel="stylesheet">
    <link rel='shorcut icon' href="../../resources/images/favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="../../resources/css/form.owner1.css?v=<?= time() ?>">
</head>
<body>
    <?php include '../layouts/header.php'; ?>
    <?php include '../layouts/verification.php'; ?>

    <section>
        <h2>Etapa 1</h2>
        <h1>Fale um pouco sobre seu espaço.</h1>
        <form action="../../controllers/ClientController.php?action=registerOwner" method="POST" id="registerForm">
            <input type="text" id="nome" name="nome" placeholder="Nome do Espaço" required>
            <div id="inputform">
                <input type="text" id="loc" name="loc" placeholder="Localização" required>
                <input type="text" id="cep" name="cep" placeholder="CEP" required maxlength="9">
            </div>
            <input type="text" id="Desc" name="Desc" placeholder="Descrição" required>
            <input type="hidden" id="bairro" name="bairro">
            <input type="hidden" id="regiao" name="regiao">
            <div id="cepError" style="color: red; display: none;">CEP inválido ou fora de Belo Horizonte.</div>
            <button type="submit">Registrar</button>
        </form>
    </section>

    <script>
    // Função para aplicar a máscara ao CEP
    function maskCEP(input) {
        let value = input.value.replace(/\D/g, ''); // Remove caracteres não numéricos
        value = value.replace(/^(\d{5})(\d)/, '$1-$2'); // Adiciona o hífen após os primeiros 5 dígitos
        input.value = value;
    }

    // Função para garantir que o CEP esteja no formato correto
    function formatCEP(value) {
        value = value.replace(/\D/g, ''); // Remove caracteres não numéricos
        return value.replace(/^(\d{5})(\d{3})$/, '$1-$2'); // Formata como 00000-000
    }

    document.addEventListener('DOMContentLoaded', function() {
        const cepInput = document.getElementById('cep');
        const form = document.getElementById('registerForm');
        
        // Aplica a máscara enquanto o usuário digita
        cepInput.addEventListener('input', function() {
            maskCEP(this);
        });

        // Garante que o CEP esteja formatado corretamente antes de enviar o formulário
        form.addEventListener('submit', function(event) {
            cepInput.value = formatCEP(cepInput.value);
        });

        // Validação do CEP
        cepInput.addEventListener('blur', function() {
            var cep = this.value.replace(/\D/g, '');
            if (cep.length != 8) {
                document.getElementById('cepError').style.display = 'block';
                return;
            }

            fetch(`https://viacep.com.br/ws/${cep}/json/`)
                .then(response => response.json())
                .then(data => {
                    if (data.erro) {
                        document.getElementById('cepError').style.display = 'block';
                        return;
                    }

                    if (data.localidade !== 'Belo Horizonte') {
                        document.getElementById('cepError').style.display = 'block';
                        return;
                    }

                    document.getElementById('cepError').style.display = 'none';
                    document.getElementById('bairro').value = data.bairro;
                    document.getElementById('regiao').value = mapearRegiaoBH(data.bairro);
                    this.value = formatCEP(cep); // Garante que o CEP esteja formatado após a validação
                })
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById('cepError').style.display = 'block';
                });
        });

        function mapearRegiaoBH(bairro) {
            var regioesBH = {
                'Centro': ['Centro', 'Savassi', 'Barro Preto', 'Lourdes'],
                'Noroeste': ['Caiçara', 'Caiçara-Adelaide', 'Padre Eustáquio', 'Coração Eucarístico'],
                'Norte': ['Venda Nova', 'Floramar', 'Planalto'],
                'Nordeste': ['Cidade Nova', 'União', 'Silveira'],
                'Leste': ['Santa Efigênia', 'Santa Tereza', 'Sagrada Família'],
                'Oeste': ['Calafate', 'Gameleira', 'Nova Suíssa', 'Prado'],
                'Barreiro': ['Barreiro', 'Milionários', 'Vale do Jatobá'],
                'Pampulha': ['Pampulha', 'São Luiz', 'Jaraguá']
            };

            for (var regiao in regioesBH) {
                if (regioesBH[regiao].includes(bairro)) {
                    return regiao;
                }
            }

            return 'Região não identificada';
        }
    });
    </script>
</body>
</html>