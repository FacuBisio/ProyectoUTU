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

    // Mostrar mensaje de espera
    const cargando = document.createElement("div");

    cargando.classList.add("mensaje", "bot");
    cargando.id = "mensaje-cargando";

    cargando.innerHTML = "🤖 Escribiendo...";

    const mensajes = document.getElementById("chat-mensajes");

    mensajes.appendChild(cargando);

    mensajes.scrollTop = mensajes.scrollHeight;


    // Enviar mensaje a PHP
    const datos = new FormData();

    datos.append("mensaje", texto);


    fetch("api/chatbot.php", {

        method: "POST",

        body: datos

    })

    .then(response => response.json())

    .then(data => {

        // Eliminar "Escribiendo..."
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

        console.error(error);

    });

}


function agregarMensaje(texto, tipo) {

    const mensajes =
        document.getElementById("chat-mensajes");

    const mensaje =
        document.createElement("div");

    mensaje.classList.add("mensaje");s
    mensaje.classList.add(tipo);

    mensaje.innerText = texto;

    mensajes.appendChild(mensaje);

    mensajes.scrollTop =
        mensajes.scrollHeight;
}