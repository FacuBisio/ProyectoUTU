document.querySelectorAll(".slider-container").forEach(slider => {
    const slides = Array.from(slider.querySelectorAll(".slide"));
    const anterior = slider.querySelector(".prev");
    const siguiente = slider.querySelector(".next");
    const contenedorPuntos = slider.querySelector(".slider-dots");

    if (slides.length === 0) {
        anterior?.remove();
        siguiente?.remove();
        contenedorPuntos?.remove();

        const aviso = document.createElement("p");
        aviso.className = "slider-empty";
        aviso.setAttribute("role", "status");
        aviso.textContent = "Todavía no hay lugares disponibles en esta categoría.";
        slider.appendChild(aviso);
        return;
    }

    const puntos = [];
    if (contenedorPuntos) {
        contenedorPuntos.replaceChildren();
        slides.forEach((slide, index) => {
            const punto = document.createElement("button");
            punto.type = "button";
            punto.className = "dot";
            punto.setAttribute("aria-label", `Mostrar elemento ${index + 1}`);
            punto.addEventListener("click", () => mostrarSlide(index));
            contenedorPuntos.appendChild(punto);
            puntos.push(punto);
        });
    }

    let actual = Math.max(0, slides.findIndex(slide => slide.classList.contains("active")));

    function mostrarSlide(indice) {
        if (indice < 0 || indice >= slides.length) {
            return;
        }

        slides.forEach((slide, posicion) => {
            const activo = posicion === indice;
            slide.classList.toggle("active", activo);
            slide.setAttribute("aria-hidden", String(!activo));
        });
        puntos.forEach((punto, posicion) => {
            const activo = posicion === indice;
            punto.classList.toggle("active", activo);
            punto.setAttribute("aria-current", String(activo));
        });
        actual = indice;
    }

    mostrarSlide(actual);

    if (slides.length < 2) {
        anterior?.remove();
        siguiente?.remove();
        contenedorPuntos?.remove();
        return;
    }

    function avanzar(direccion) {
        mostrarSlide((actual + direccion + slides.length) % slides.length);
    }

    anterior?.addEventListener("click", () => avanzar(-1));
    siguiente?.addEventListener("click", () => avanzar(1));
    window.setInterval(() => avanzar(1), 5000);
});