document.getElementById("nome").addEventListener("input", function(event) {
    let input = event.target;
    let value = input.value;
    if (value.length > 0) {
        input.value = value.charAt(0).toUpperCase() + value.slice(1);
    }
});