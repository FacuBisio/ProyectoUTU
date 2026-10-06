document.addEventListener("DOMContentLoaded", () => {
    const panel = document.getElementById("chatbot");
    const botonAbrir = document.getElementById("boton-chat");
    const botonCerrar = document.getElementById("cerrar-chat");
    const formulario = document.getElementById("chat-input");
    const input = document.getElementById("mensaje");
    const mensajes = document.getElementById("chat-mensajes");

    if (!panel || !botonAbrir || !botonCerrar || !formulario || !input || !mensajes) {
        return;
    }

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

    botonAbrir.addEventListener("click", () => alternarChat(true));
    botonCerrar.addEventListener("click", () => alternarChat(false));

    document.addEventListener("keydown", evento => {
        if (evento.key === "Escape" && panel.classList.contains("chat-abierto")) {
            alternarChat(false);
        }
    });

    formulario.addEventListener("submit", async evento => {
        evento.preventDefault();
        const texto = input.value.trim();

        if (!texto) {
            return;
        }

        agregarMensaje(texto, "usuario");
        input.value = "";
        input.disabled = true;
        formulario.querySelector("button").disabled = true;

        const cargando = document.createElement("div");
        cargando.classList.add("mensaje", "bot");
        cargando.textContent = "🤖 Escribiendo...";
        mensajes.appendChild(cargando);
        mensajes.scrollTop = mensajes.scrollHeight;

        try {
            const datos = new FormData();
            datos.append("mensaje", texto);
            const respuesta = await fetch(panel.dataset.endpoint, {
                method: "POST",
                body: datos,
                headers: { "Accept": "application/json" }
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
        } catch (error) {
            agregarMensaje(error.message || "No pude completar la consulta. Intentá nuevamente.", "bot");
            console.error("No se pudo completar la solicitud al chatbot.", error);
        } finally {
            cargando.remove();
            input.disabled = false;
            formulario.querySelector("button").disabled = false;
            input.focus();
        }
    });
});