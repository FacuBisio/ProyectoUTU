document.addEventListener("DOMContentLoaded", () => {
    const panel = document.getElementById("chatbot");
    const botonAbrir = document.getElementById("boton-chat");
    const botonCerrar = document.getElementById("cerrar-chat");
    const formulario = document.getElementById("chat-input");
    const botonEnviar = formulario?.querySelector("button");
    const input = document.getElementById("mensaje");
    const mensajes = document.getElementById("chat-mensajes");

    if (!panel || !botonAbrir || !botonCerrar || !formulario || !botonEnviar || !input || !mensajes) {
        return;
    }

    // Mantiene sincronizados el estado visible del panel y los controles accesibles.
    function alternarChat(abierto) {
        panel.classList.toggle("chat-abierto", abierto);
        panel.setAttribute("aria-hidden", String(!abierto));
        botonAbrir.setAttribute("aria-expanded", String(abierto));

        if (abierto) {
            input.focus();
        } else {
            botonAbrir.focus();
        }
    }

    function agregarMensaje(texto, tipo) {
        const mensaje = document.createElement("div");
        mensaje.classList.add("mensaje", tipo);
        mensaje.textContent = texto;
        mensajes.appendChild(mensaje);
        mensajes.scrollTop = mensajes.scrollHeight;
    }

    function agregarEnlaces(enlaces) {
        if (!Array.isArray(enlaces) || enlaces.length === 0) {
            return;
        }

        const lista = document.createElement("div");
        lista.className = "chat-enlaces";
        enlaces.forEach(enlace => {
            if (!enlace || typeof enlace.texto !== "string" || typeof enlace.url !== "string") {
                return;
            }

            const destino = new URL(enlace.url, window.location.href);
            if (destino.origin !== window.location.origin) {
                return;
            }

            const vinculo = document.createElement("a");
            vinculo.href = destino.href;
            vinculo.textContent = enlace.texto;
            lista.appendChild(vinculo);
        });

        if (lista.childElementCount > 0) {
            mensajes.appendChild(lista);
            mensajes.scrollTop = mensajes.scrollHeight;
        }
    }

    function agregarSugerencias(sugerencias) {
        if (!Array.isArray(sugerencias) || sugerencias.length === 0) {
            return;
        }

        const grupo = document.createElement("div");
        grupo.className = "chat-sugerencias";
        grupo.setAttribute("aria-label", "Otras consultas sugeridas");
        sugerencias.forEach(sugerencia => {
            if (typeof sugerencia !== "string" || !sugerencia.trim()) {
                return;
            }
            const boton = document.createElement("button");
            boton.type = "button";
            boton.className = "chat-sugerencia";
            boton.dataset.consulta = sugerencia;
            boton.textContent = sugerencia;
            grupo.appendChild(boton);
        });

        if (grupo.childElementCount > 0) {
            mensajes.appendChild(grupo);
            mensajes.scrollTop = mensajes.scrollHeight;
        }
    }

    botonAbrir.addEventListener("click", () => alternarChat(true));
    botonCerrar.addEventListener("click", () => alternarChat(false));

    document.addEventListener("keydown", evento => {
        if (evento.key === "Escape" && panel.classList.contains("chat-abierto")) {
            alternarChat(false);
        }
    });

    // Envía el mensaje y muestra la respuesta o un error comprensible.
    formulario.addEventListener("submit", async evento => {
        evento.preventDefault();
        const texto = input.value.trim();

        if (!texto) {
            return;
        }

        mensajes.querySelectorAll(".chat-sugerencias").forEach(grupo => grupo.remove());
        agregarMensaje(texto, "usuario");
        input.value = "";
        input.disabled = true;
        botonEnviar.disabled = true;

        const cargando = document.createElement("div");
        cargando.classList.add("mensaje", "bot");
        cargando.textContent = "🤖 Escribiendo...";
        mensajes.appendChild(cargando);
        mensajes.scrollTop = mensajes.scrollHeight;
        const controlador = new AbortController();
        const temporizador = window.setTimeout(() => controlador.abort(), 20000);

        try {
            const datos = new FormData();
            datos.append("mensaje", texto);
            const respuesta = await fetch(panel.dataset.endpoint, {
                method: "POST",
                body: datos,
                headers: { "Accept": "application/json" },
                signal: controlador.signal
            });
            const tipoContenido = respuesta.headers.get("content-type") || "";
            if (!tipoContenido.includes("application/json")) {
                throw new Error("El servidor devolvió una respuesta inesperada.");
            }
            const resultado = await respuesta.json();

            if (!respuesta.ok || resultado.error) {
                throw new Error(resultado.error || `El asistente respondió con HTTP ${respuesta.status}.`);
            }

            agregarMensaje(resultado.respuesta || "No encontré una respuesta para esa consulta.", "bot");
            agregarEnlaces(resultado.enlaces);
            agregarSugerencias(resultado.sugerencias);
        } catch (error) {
            const mensajeError = error.name === "AbortError"
                ? "La consulta está tardando demasiado. Comprueba tu conexión e inténtalo nuevamente."
                : (error.message || "No pude completar la consulta. Inténtalo de nuevo.");
            agregarMensaje(mensajeError, "bot");
            input.value = texto;
            console.error("No se pudo completar la solicitud al chatbot.", error);
        } finally {
            window.clearTimeout(temporizador);
            cargando.remove();
            input.disabled = false;
            botonEnviar.disabled = false;
            input.focus();
        }
    });

    mensajes.addEventListener("click", evento => {
        const botonSugerencia = evento.target.closest(".chat-sugerencia");
        if (!botonSugerencia || input.disabled) {
            return;
        }
        input.value = botonSugerencia.dataset.consulta || "";
        formulario.requestSubmit();
    });
});