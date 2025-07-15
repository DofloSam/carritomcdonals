function actualizarTotal() {
    let total = 0;
    let filas = document.querySelectorAll("tbody tr[data-indice]");

    filas.forEach(fila => {
        let precio = parseFloat(fila.querySelector(".precio").textContent.replace(" €", ""));
        let cantidad = parseInt(fila.querySelector(".cantidad").value);
        let subtotal = precio * cantidad;

        fila.querySelector(".subtotal").textContent = subtotal.toFixed(2) + " €";
        total += subtotal;
    });

    document.querySelector(".total-valor").textContent = total.toFixed(2) + " €";
}

document.addEventListener("DOMContentLoaded", function() {
    document.querySelectorAll(".cantidad").forEach(input => {
        input.addEventListener("input", actualizarTotal);
    });
});
