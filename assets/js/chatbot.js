function abrirChat() {
    document.getElementById("chatbot").style.display = "block";
    document.getElementById("mensaje").focus();
}

function cerrarChat() {
    document.getElementById("chatbot").style.display = "none";
}

function enviarMensaje() {

    const input = document.getElementById("mensaje");
    const texto = input.value.trim();

    if (texto === "") {
        return;
    }

    // Mostrar mensaje del usuario
    agregarMensaje(texto, "usuario");

    // Limpiar input
    input.value = "";

    // Mostrar "Escribiendo..."
    const cargando = document.createElement("div");

    cargando.classList.add("mensaje", "bot");
    cargando.id = "mensaje-cargando";
    cargando.innerHTML = "🤖 Escribiendo...";

    const mensajes = document.getElementById("chat-mensajes");

    mensajes.appendChild(cargando);
    mensajes.scrollTop = mensajes.scrollHeight;

    // Crear datos para enviar a PHP
    const datos = new FormData();
    datos.append("mensaje", texto);

    // Medir tiempo
    const inicio = performance.now();
    console.log("Enviando mensaje a PHP...");

    // Enviar a PHP
    fetch("api/chatbot.php", {
        method: "POST",
        body: datos
    })
    .then(response => {

        console.log(
            "PHP respondió en:",
            ((performance.now() - inicio) / 1000).toFixed(2),
            "segundos"
        );

        return response.json();
    })
    .then(data => {

        console.log("Respuesta recibida:", data);

        const cargando =
            document.getElementById("mensaje-cargando");

        if (cargando) {
            cargando.remove();
        }

        if (data.error) {

            agregarMensaje(
                "❌ Ocurrió un error: " + data.error,
                "bot"
            );

            return;
        }

        agregarMensaje(
            data.respuesta,
            "bot"
        );

    })
    .catch(error => {

        const cargando =
            document.getElementById("mensaje-cargando");

        if (cargando) {
            cargando.remove();
        }

        agregarMensaje(
            "❌ No pude conectarme con el asistente.",
            "bot"
        );

        console.error("ERROR:", error);
    });
}


function agregarMensaje(texto, tipo) {

    const mensajes =
        document.getElementById("chat-mensajes");

    const mensaje =
        document.createElement("div");

    mensaje.classList.add("mensaje");
    mensaje.classList.add(tipo);

    mensaje.innerText = texto;

    mensajes.appendChild(mensaje);

    mensajes.scrollTop =
        mensajes.scrollHeight;
}


// Enviar con Enter
document.getElementById("mensaje").addEventListener(
    "keydown",
    function(event) {

        if (event.key === "Enter") {

            event.preventDefault();

            enviarMensaje();
        }
    }
);s