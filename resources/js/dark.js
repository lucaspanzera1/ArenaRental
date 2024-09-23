document.addEventListener("DOMContentLoaded", () => {
    // Obtém o botão de alternar tema, considerando que ele pode ter diferentes IDs
    const toggleButton = document.getElementById("toggleButton") || document.getElementById("toggle-theme");
    const body = document.body;

    // Função para alternar o modo escuro
    const toggleDarkMode = () => {
        body.classList.toggle("dark-mode");
        const darkModeEnabled = body.classList.contains("dark-mode");
        localStorage.setItem("dark-mode", darkModeEnabled);
    };

    // Verifica o modo escuro armazenado no localStorage
    const darkModeStored = localStorage.getItem("dark-mode") === "true";
    if (darkModeStored) {
        body.classList.add("dark-mode");
    }

    // Adiciona o evento de clique ao botão, se ele existir
    if (toggleButton) {
        toggleButton.addEventListener("click", toggleDarkMode);
    }
});

