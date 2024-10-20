<?php
// Verifica os filtros aplicados (caso existam) usando a URL
$regiaoSelecionada = isset($_GET['regiao']) ? $_GET['regiao'] : 'todos';
$esporteSelecionado = isset($_GET['esporte']) ? $_GET['esporte'] : 'todos';
$minPrice = isset($_GET['valor_min']) ? $_GET['valor_min'] : 0;
$maxPrice = isset($_GET['valor_max']) ? $_GET['valor_max'] : 1000;
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtros de Quadras - Popup</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">
    <link rel="stylesheet" href="../../resources/css/slider.css?v=<?= time() ?>">
</head>
<body>

    <!-- Botão para abrir o pop-up -->
    <button id="filter-button">
        <svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M20 0C8.96 0 0 8.96 0 20C0 31.04 8.96 40 20 40C31.04 40 40 31.04 40 20C40 8.96 31.04 0 20 0ZM22 6.6L24.7 4.7C28.34 5.82 31.44 8.22 33.46 11.38L32.68 14.06L29.98 14.98L22 9.4V6.6ZM15.3 4.7L18 6.6V9.4L10.02 14.98L7.32 14.06L6.54 11.38C8.56 8.24 11.66 5.84 15.3 4.7ZM10.16 30.22L7.88 30.42C5.46 27.62 4 23.98 4 20C4 19.76 4.02 19.54 4.04 19.3L6.04 17.84L8.8 18.8L11.72 27.48L10.16 30.22ZM25 35.18C23.42 35.7 21.74 36 20 36C18.26 36 16.58 35.7 15 35.18L13.62 32.2L14.9 30H25.12L26.4 32.22L25 35.18ZM24.54 26H15.46L12.76 17.96L20 12.88L27.26 17.96L24.54 26ZM32.12 30.42L29.84 30.22L28.26 27.48L31.18 18.8L33.96 17.86L35.96 19.32C35.98 19.54 36 19.76 36 20C36 23.98 34.54 27.62 32.12 30.42Z" fill="black"/>
        </svg>
    </button>

    <!-- Estrutura do pop-up -->
    <div class="filter-overlay">
        <div class="filter-container">
            <span class="close-btn">&times;</span>

            <h2>Filtrar Quadras</h2>
            <div class="filter-group">
                <span class="filter-label">Região</span>
                <select id="region-select">
                    <option value="todos" <?= ($regiaoSelecionada == 'todos') ? 'selected' : ''; ?>>Todas as regiões</option>
                    <option value="Centro" <?= ($regiaoSelecionada == 'Centro') ? 'selected' : ''; ?>>Centro</option>
                    <option value="Noroeste" <?= ($regiaoSelecionada == 'Noroeste') ? 'selected' : ''; ?>>Noroeste</option>
                    <option value="Norte" <?= ($regiaoSelecionada == 'Norte') ? 'selected' : ''; ?>>Norte</option>
                    <option value="Nordeste" <?= ($regiaoSelecionada == 'Nordeste') ? 'selected' : ''; ?>>Nordeste</option>
                    <option value="Leste" <?= ($regiaoSelecionada == 'Leste') ? 'selected' : ''; ?>>Leste</option>
                    <option value="Oeste" <?= ($regiaoSelecionada == 'Oeste') ? 'selected' : ''; ?>>Oeste</option>
                    <option value="Barreiro" <?= ($regiaoSelecionada == 'Barreiro') ? 'selected' : ''; ?>>Barreiro</option>
                    <option value="Pampulha" <?= ($regiaoSelecionada == 'Pampulha') ? 'selected' : ''; ?>>Pampulha</option>
                </select>
            </div>

            <div class="filter-group">
                <span class="filter-label">Esporte</span>
                <select id="sport-select">
                    <option value="todos" <?= ($esporteSelecionado == 'todos') ? 'selected' : ''; ?>>Todos os esportes</option>
                    <option value="Futebol Society" <?= ($esporteSelecionado == 'Futebol Society') ? 'selected' : ''; ?>>Futebol Society</option>
                    <option value="Futebol de Campo" <?= ($esporteSelecionado == 'Futebol de Campo') ? 'selected' : ''; ?>>Futebol de Campo</option>
                    <option value="Futvolei" <?= ($esporteSelecionado == 'Futvolei') ? 'selected' : ''; ?>>Futvolei</option>
                    <option value="Futsal" <?= ($esporteSelecionado == 'Futsal') ? 'selected' : ''; ?>>Futsal</option>
                    <option value="Basquete" <?= ($esporteSelecionado == 'Basquete') ? 'selected' : ''; ?>>Basquete</option>
                    <option value="Vôlei" <?= ($esporteSelecionado == 'Vôlei') ? 'selected' : ''; ?>>Vôlei</option>
                </select>
            </div>

            <div class="filter-group">
                <span class="filter-label">Preço</span>
                <input type="number" id="min-price" placeholder="Min" value="<?= $minPrice ?>" min="0">
                <span>até</span>
                <input type="number" id="max-price" placeholder="Max" value="<?= $maxPrice ?>" min="0" max="1000">
            </div>

            <button id="filter-btn">Filtrar</button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const filterButton = document.getElementById('filter-button');
            const filterOverlay = document.querySelector('.filter-overlay');
            const closeBtn = document.querySelector('.close-btn');
            const filterBtn = document.getElementById('filter-btn');

            // Abrir o pop-up ao clicar no botão
            filterButton.addEventListener('click', function () {
                filterOverlay.style.display = 'flex';
            });

            // Fechar o pop-up ao clicar no botão "fechar"
            closeBtn.addEventListener('click', function () {
                filterOverlay.style.display = 'none';
            });

            // Fechar o pop-up ao clicar fora da caixa de filtros
            filterOverlay.addEventListener('click', function (e) {
                if (e.target === filterOverlay) {
                    filterOverlay.style.display = 'none';
                }
            });

            // Função de filtro ao clicar no botão "Filtrar"
            filterBtn.addEventListener('click', function () {
                const regiao = document.getElementById('region-select').value;
                const esporte = document.getElementById('sport-select').value;
                const minPrice = parseInt(document.getElementById('min-price').value);
                const maxPrice = parseInt(document.getElementById('max-price').value);

                if (minPrice > maxPrice) {
                    alert('O preço mínimo não pode ser maior que o preço máximo');
                    return;
                }

                const queryParams = new URLSearchParams();

                if (regiao && regiao !== 'todos') {
                    queryParams.append('regiao', regiao);
                }
                if (esporte && esporte !== 'todos') {
                    queryParams.append('esporte', esporte);
                }
                queryParams.append('valor_min', minPrice);
                queryParams.append('valor_max', maxPrice);

                window.location.search = queryParams.toString();
            });
        });
    </script>

</body>
</html>
