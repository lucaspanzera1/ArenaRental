<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filter Pop-up Component</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.css">
    <link rel="stylesheet" href="../../resources/css/slider.css?v=<?= time() ?>">
</head>
<body>
    <button id="filter-button"><svg width="40" height="40" viewBox="0 0 40 40" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M20 0C8.96 0 0 8.96 0 20C0 31.04 8.96 40 20 40C31.04 40 40 31.04 40 20C40 8.96 31.04 0 20 0ZM22 6.6L24.7 4.7C28.34 5.82 31.44 8.22 33.46 11.38L32.68 14.06L29.98 14.98L22 9.4V6.6ZM15.3 4.7L18 6.6V9.4L10.02 14.98L7.32 14.06L6.54 11.38C8.56 8.24 11.66 5.84 15.3 4.7ZM10.16 30.22L7.88 30.42C5.46 27.62 4 23.98 4 20C4 19.76 4.02 19.54 4.04 19.3L6.04 17.84L8.8 18.8L11.72 27.48L10.16 30.22ZM25 35.18C23.42 35.7 21.74 36 20 36C18.26 36 16.58 35.7 15 35.18L13.62 32.2L14.9 30H25.12L26.4 32.22L25 35.18ZM24.54 26H15.46L12.76 17.96L20 12.88L27.26 17.96L24.54 26ZM32.12 30.42L29.84 30.22L28.26 27.48L31.18 18.8L33.96 17.86L35.96 19.32C35.98 19.54 36 19.76 36 20C36 23.98 34.54 27.62 32.12 30.42Z" fill="black"/>
</svg>
</button>

    <div class="filter-overlay">
        <div class="filter-container">
            <span class="close-btn">&times;</span>
            <h2>Filtro</h2>
            <div>
                <h3>Esporte:</h3>
                <div class="sport-options">
                    <div class="sport-option" data-sport="todos">Todos</div>
                    <div class="sport-option" data-sport="Futebol">Futebol</div>
                    <div class="sport-option" data-sport="Futvolei">Futvolei</div>
                    <div class="sport-option" data-sport="Futsal">Futsal</div>
                </div>
            </div>
            <div class="price-range">
                <h3>Faixa de preço:</h3>
                <div id="price-slider"></div>
                <div class="price-inputs">
                    <div><label for="min-price">R$</label><input type="number" id="min-price" value="0" min="0" max="300"></div>
                    <div><label for="max-price">R$</label><input type="number" id="max-price" value="300" min="0" max="300"></div>
                </div>
            </div>
            <button id="filter-btn">Filtrar</button>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/14.6.3/nouislider.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterButton = document.getElementById('filter-button');
            const filterOverlay = document.querySelector('.filter-overlay');
            const sportOptions = document.querySelectorAll('.sport-option');
            const minPriceInput = document.getElementById('min-price');
            const maxPriceInput = document.getElementById('max-price');
            const filterBtn = document.getElementById('filter-btn');
            const priceSlider = document.getElementById('price-slider');
            const closeBtn = document.querySelector('.close-btn');

            // Open filter pop-up
            filterButton.addEventListener('click', function() {
                filterOverlay.style.display = 'block';
            });

            // Close filter pop-up
            closeBtn.addEventListener('click', function() {
                filterOverlay.style.display = 'none';
            });

            // Close filter pop-up when clicking outside
            filterOverlay.addEventListener('click', function(e) {
                if (e.target === filterOverlay) {
                    filterOverlay.style.display = 'none';
                }
            });

            // Initialize noUiSlider
            noUiSlider.create(priceSlider, {
                start: [0, 300],
                connect: true,
                range: {
                    'min': 0,
                    'max': 300
                },
                step: 10
            });

            // Update input fields when slider changes
            priceSlider.noUiSlider.on('update', function (values, handle) {
                const value = Math.round(values[handle]);
                if (handle === 0) {
                    minPriceInput.value = value;
                } else {
                    maxPriceInput.value = value;
                }
            });

            // Update slider when input fields change
            minPriceInput.addEventListener('change', function() {
                priceSlider.noUiSlider.set([this.value, null]);
            });

            maxPriceInput.addEventListener('change', function() {
                priceSlider.noUiSlider.set([null, this.value]);
            });

            // Sport selection
            sportOptions.forEach(option => {
                option.addEventListener('click', function() {
                    sportOptions.forEach(opt => opt.classList.remove('selected'));
                    this.classList.add('selected');
                });
            });

            // Filter button
            filterBtn.addEventListener('click', function() {
                const selectedSport = document.querySelector('.sport-option.selected');
                const esporte = selectedSport ? selectedSport.dataset.sport : '';
                const minPrice = parseInt(minPriceInput.value);
                const maxPrice = parseInt(maxPriceInput.value);

                const queryParams = new URLSearchParams();
                if (esporte) {
                    queryParams.append('esporte', esporte);
                }
                queryParams.append('valor_min', minPrice);
                queryParams.append('valor_max', maxPrice);

                // Update URL with query parameters and reload the page
                window.location.search = queryParams.toString();
            });

            // Set initial values based on URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const esporteParam = urlParams.get('esporte');
            const valorMinParam = urlParams.get('valor_min');
            const valorMaxParam = urlParams.get('valor_max');

            if (esporteParam) {
                const sportOption = document.querySelector(`.sport-option[data-sport="${esporteParam}"]`);
                if (sportOption) {
                    sportOption.classList.add('selected');
                }
            }

            if (valorMinParam && valorMaxParam) {
                priceSlider.noUiSlider.set([valorMinParam, valorMaxParam]);
            }
        });
    </script>
</body>
</html>