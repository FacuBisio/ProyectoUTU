document.addEventListener("DOMContentLoaded", () => {
    const boton = document.getElementById("turismo-mostrar-mas");
    const ampliacion = document.getElementById("turismo-ampliacion");
    const contenidoAmpliacion = "<p>La visita también puede combinar paseos por la costanera, recorridos por espacios culturales y una pausa para probar la gastronomía local. Consulta las categorías y los eventos de GoSalto para elegir actividades según tus intereses.</p>";

    if (!boton || !ampliacion) {
        return;
    }

    ampliacion.hidden = true;
    boton.hidden = false;
    boton.setAttribute("aria-expanded", "false");
    boton.textContent = "Mostrar más";

    boton.addEventListener("click", () => {
        const mostrarAmpliacion = ampliacion.hidden;
        if (mostrarAmpliacion) {
            ampliacion.innerHTML = contenidoAmpliacion;
        }
        ampliacion.hidden = !mostrarAmpliacion;
        boton.setAttribute("aria-expanded", String(mostrarAmpliacion));
        boton.textContent = mostrarAmpliacion ? "Mostrar menos" : "Mostrar más";
    });
});
