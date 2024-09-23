<link rel="stylesheet" href="../../resources/css/search.css?v=<?= time() ?>">
    <div class="search-container">
        <input type="text" class="search-input" id="searchInput" placeholder="Pesquisar">
        <button class="search-button" onclick="performSearch()">
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="11" cy="11" r="8"></circle>
                <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
            </svg>
        </button>
    </div>

    <script>
        function performSearch() {
            const searchTerm = document.getElementById('searchInput').value;
            alert('Você pesquisou por: ' + searchTerm);
            // Aqui você pode adicionar a lógica para enviar a pesquisa para o backend PHP
        }

        // Adiciona funcionalidade de pesquisa ao pressionar Enter
        document.getElementById('searchInput').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                performSearch();
            }
        });
    </script>
