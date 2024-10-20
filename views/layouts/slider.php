<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Filtros de Quadras</title>
    <style>
        .filter-container {
            max-width: 1000px;
            margin: 20px auto;
            padding: 20px;
            background-color: #f5f5f5;
            border-radius: 8px;
        }

        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 15px;
        }

        .filter-label {
            font-size: 16px;
            font-weight: 500;
            min-width: 80px;
        }

        select {
            padding: 8px 12px;
            font-size: 16px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: white;
            width: 200px;
            cursor: pointer;
        }

        select:focus {
            outline: none;
            border-color: #007bff;
        }

        .price-inputs {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .price-inputs input {
            width: 100px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }

        .price-inputs input:focus {
            outline: none;
            border-color: #007bff;
        }

        #filter-btn {
            padding: 8px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-left: 10px;
        }

        #filter-btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="filter-container">
        <div class="filter-group">
            <span class="filter-label">Região</span>
            <select id="region-select">
                <option value="todos">Todas as regiões</option>
                <option value="Centro">Centro</option>
                <option value="Noroeste">Noroeste</option>
                <option value="Norte">Norte</option>
                <option value="Nordeste">Nordeste</option>
                <option value="Leste">Leste</option>
                <option value="Oeste">Oeste</option>
                <option value="Barreiro">Barreiro</option>
                <option value="Pampulha">Pampulha</option>
            </select>
        </div>

        <div class="filter-group">
            <span class="filter-label">Esporte</span>
            <select id="sport-select">
                <option value="todos">Todos os esportes</option>
                <option value="Futebol Society">Futebol Society</option>
                <option value="Futebol de Campo">Futebol de Campo</option>
                <option value="Futvolei">Futvolei</option>
                <option value="Futsal">Futsal</option>
                <option value="Basquete">Basquete</option>
                <option value="Vôlei">Vôlei</option>
            </select>
        </div>

        <div class="filter-group">
            <span class="filter-label">Preço</span>
            <div class="price-inputs">
                <input type="number" id="min-price" placeholder="Min" value="0" min="0" max="1000">
                <span>até</span>
                <input type="number" id="max-price" placeholder="Max" value="1000" min="0" max="1000">
                <button id="filter-btn">Filtrar</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const regionSelect = document.getElementById('region-select');
            const sportSelect = document.getElementById('sport-select');
            const minPriceInput = document.getElementById('min-price');
            const maxPriceInput = document.getElementById('max-price');
            const filterBtn = document.getElementById('filter-btn');

            filterBtn.addEventListener('click', function() {
                const regiao = regionSelect.value;
                const esporte = sportSelect.value;
                const minPrice = parseInt(minPriceInput.value);
                const maxPrice = parseInt(maxPriceInput.value);

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

            // Set initial values based on URL parameters
            const urlParams = new URLSearchParams(window.location.search);
            const regiaoParam = urlParams.get('regiao');
            const esporteParam = urlParams.get('esporte');
            const valorMinParam = urlParams.get('valor_min');
            const valorMaxParam = urlParams.get('valor_max');

            if (regiaoParam) {
                regionSelect.value = regiaoParam;
            }
            if (esporteParam) {
                sportSelect.value = esporteParam;
            }
            if (valorMinParam) {
                minPriceInput.value = valorMinParam;
            }
            if (valorMaxParam) {
                maxPriceInput.value = valorMaxParam;
            }
        });
    </script>
</body>
</html>