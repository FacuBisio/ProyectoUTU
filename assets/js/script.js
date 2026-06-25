document.addEventListener("DOMContentLoaded", () => {

    const boton = document.getElementById("btnMostrar");
    const texto = document.getElementById("textoExtra");

    boton.addEventListener("click", () => {

        if (texto.style.display === "none" || texto.style.display === "") {

            texto.style.display = "inline";
            boton.textContent = "Mostrar menos";

        } else {

            texto.style.display = "none";
            boton.textContent = "Mostrar más";

        }

    });

});