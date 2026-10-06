document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.querySelector(".buscador[role='search']");
    const input = formulario?.querySelector("input[name='q']");
    const lista = formulario?.querySelector(".buscador-sugerencias");

    if (!formulario || !input || !lista) {
        return;
    }

    let temporizador = null;
    let solicitudActiva = null;
    let indiceActivo = -1;

    function posicionarSugerencias() {
        if (window.innerWidth <= 768 && !lista.hidden) {
            lista.style.left = `${14 - formulario.getBoundingClientRect().left}px`;
            lista.style.width = `${Math.max(0, window.innerWidth - 28)}px`;
        } else {
            lista.style.removeProperty("left");
            lista.style.removeProperty("width");
        }
    }

    function cerrarSugerencias() {
        lista.hidden = true;
        lista.replaceChildren();
        input.setAttribute("aria-expanded", "false");
        input.removeAttribute("aria-activedescendant");
        indiceActivo = -1;
        lista.style.removeProperty("left");
        lista.style.removeProperty("width");
    }

    function seleccionarIndice(indice) {
        const opciones = [...lista.querySelectorAll("[role='option']")];
        if (!opciones.length) {
            return;
        }
        indiceActivo = (indice + opciones.length) % opciones.length;
        opciones.forEach((opcion, posicion) => {
            const seleccionado = posicion === indiceActivo;
            opcion.setAttribute("aria-selected", String(seleccionado));
            if (seleccionado) {
                input.setAttribute("aria-activedescendant", opcion.id);
                opcion.scrollIntoView({ block: "nearest" });
            }
        });
    }

    function mostrarEstado(texto) {
        const estado = document.createElement("div");
        estado.className = "buscador-sugerencias-estado";
        estado.setAttribute("role", "option");
        estado.setAttribute("aria-selected", "false");
        estado.textContent = texto;
        lista.replaceChildren(estado);
        lista.hidden = false;
        input.setAttribute("aria-expanded", "true");
        posicionarSugerencias();
    }

    function crearOpcion(lugar, posicion) {
        const opcion = document.createElement("button");
        opcion.type = "button";
        opcion.className = "buscador-sugerencia";
        opcion.id = `busqueda-sugerencia-${posicion}`;
        opcion.setAttribute("role", "option");
        opcion.setAttribute("aria-selected", "false");

        const cajaImagen = document.createElement("span");
        cajaImagen.className = "buscador-sugerencia-imagen";
        const imagen = document.createElement("img");
        imagen.src = lugar.imagen_url || "";
        imagen.alt = "";
        imagen.loading = "eager";
        imagen.addEventListener("error", () => {
            const icono = document.createElement("i");
            icono.className = "fa-solid fa-location-dot";
            icono.setAttribute("aria-hidden", "true");
            cajaImagen.replaceChildren(icono);
        }, { once: true });
        cajaImagen.appendChild(imagen);

        const informacion = document.createElement("span");
        informacion.className = "buscador-sugerencia-info";
        const nombre = document.createElement("strong");
        nombre.textContent = lugar.nombre;
        const detalle = document.createElement("small");
        detalle.textContent = [lugar.categoria, lugar.direccion].filter(Boolean).join(" · ") || "Lugar turístico";
        informacion.append(nombre, detalle);

        const flecha = document.createElement("i");
        flecha.className = "fa-solid fa-arrow-right buscador-sugerencia-flecha";
        flecha.setAttribute("aria-hidden", "true");
        opcion.append(cajaImagen, informacion, flecha);

        opcion.addEventListener("click", () => {
            input.value = lugar.nombre;
            cerrarSugerencias();
            formulario.requestSubmit();
        });
        return opcion;
    }

    async function cargarSugerencias() {
        const consulta = input.value.trim();
        if (consulta.length < 2) {
            solicitudActiva?.abort();
            cerrarSugerencias();
            return;
        }

        solicitudActiva?.abort();
        solicitudActiva = new AbortController();
        mostrarEstado("Buscando lugares...");

        const url = new URL(input.dataset.suggestionsUrl, window.location.origin);
        url.searchParams.set("sugerencias", "1");
        url.searchParams.set("q", consulta);

        try {
            const respuesta = await fetch(url, {
                headers: { Accept: "application/json" },
                signal: solicitudActiva.signal
            });
            if (!respuesta.ok) {
                throw new Error(`La búsqueda respondió con HTTP ${respuesta.status}.`);
            }
            const datos = await respuesta.json();
            if (input.value.trim() !== consulta) {
                return;
            }

            const resultados = Array.isArray(datos.resultados) ? datos.resultados : [];
            if (!resultados.length) {
                mostrarEstado("No encontramos lugares con ese texto.");
                return;
            }

            lista.replaceChildren(...resultados.map(crearOpcion));
            lista.hidden = false;
            input.setAttribute("aria-expanded", "true");
            indiceActivo = -1;
            posicionarSugerencias();
        } catch (error) {
            if (error.name === "AbortError") {
                return;
            }
            console.error("No se pudieron cargar las sugerencias de búsqueda.", error);
            mostrarEstado("No se pudieron cargar sugerencias. Podés presionar Enter para buscar.");
        }
    }

    input.addEventListener("input", () => {
        window.clearTimeout(temporizador);
        temporizador = window.setTimeout(cargarSugerencias, 180);
    });

    input.addEventListener("focus", () => {
        if (input.value.trim().length >= 2) {
            window.clearTimeout(temporizador);
            temporizador = window.setTimeout(cargarSugerencias, 0);
        }
    });

    input.addEventListener("keydown", evento => {
        if (evento.key === "ArrowDown" && !lista.hidden) {
            evento.preventDefault();
            seleccionarIndice(indiceActivo + 1);
        } else if (evento.key === "ArrowUp" && !lista.hidden) {
            evento.preventDefault();
            seleccionarIndice(indiceActivo < 0 ? lista.querySelectorAll("[role='option']").length - 1 : indiceActivo - 1);
        } else if (evento.key === "Enter" && indiceActivo >= 0 && !lista.hidden) {
            evento.preventDefault();
            lista.querySelectorAll("[role='option']")[indiceActivo]?.click();
        } else if (evento.key === "Escape" && !lista.hidden) {
            cerrarSugerencias();
        }
    });

    document.addEventListener("pointerdown", evento => {
        if (!formulario.contains(evento.target)) {
            cerrarSugerencias();
        }
    });

    window.addEventListener("resize", posicionarSugerencias);
    window.addEventListener("scroll", posicionarSugerencias, { passive: true });

    formulario.addEventListener("submit", evento => {
        if (!input.value.trim()) {
            evento.preventDefault();
            input.focus();
        }
    });

    const busquedaPagina = new URLSearchParams(window.location.search).get("q");
    if (!input.value && busquedaPagina) {
        input.value = busquedaPagina;
    }
});
