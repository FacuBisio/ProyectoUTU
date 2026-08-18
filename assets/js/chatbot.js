function abrirChat() {
    document.getElementById("chatbot").style.display = "block";
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

    agregarMensaje(texto, "usuario");

    input.value = "";

    setTimeout(function() {

        const respuesta = responder(texto);

        agregarMensaje(respuesta, "bot");

    }, 500);
}


function agregarMensaje(texto, tipo) {

    const mensajes = document.getElementById("chat-mensajes");

    const mensaje = document.createElement("div");

    mensaje.classList.add("mensaje");
    mensaje.classList.add(tipo);

    mensaje.innerHTML = texto;

    mensajes.appendChild(mensaje);

    mensajes.scrollTop = mensajes.scrollHeight;
}


function responder(texto) {

    texto = texto.toLowerCase();

    if (
        texto.includes("hola") ||
        texto.includes("buenas") ||
        texto.includes("buenos días")
    ) {
        return "¡Hola! 👋 Bienvenido a GoSalto. ¿Qué lugar de Salto querés conocer?";
    }

    if (
        texto.includes("terma") ||
        texto.includes("termas")
    ) {
        return "♨️ En GoSalto podés encontrar información sobre las termas de Salto y conocer sus principales características.";
    }

    if (
        texto.includes("parque") ||
        texto.includes("parques")
    ) {
        return "🌳 Salto cuenta con varios espacios naturales y parques para visitar. Podés entrar en la sección de Parques para conocerlos.";
    }

    if (
        texto.includes("paisaje") ||
        texto.includes("naturaleza")
    ) {
        return "🌿 En la sección Paisajes podés encontrar diferentes lugares naturales para visitar en Salto.";
    }

    if (
        texto.includes("comida") ||
        texto.includes("comer") ||
        texto.includes("restaurante") ||
        texto.includes("gastronomia")
    ) {
        return "🍽️ GoSalto también cuenta con información sobre gastronomía y lugares donde podés comer.";
    }

    if (
        texto.includes("evento") ||
        texto.includes("eventos")
    ) {
        return "🎉 Podés consultar la sección de Eventos para conocer las actividades que se realizan en Salto.";
    }

    if (
        texto.includes("alojamiento") ||
        texto.includes("hotel") ||
        texto.includes("dormir")
    ) {
        return "🏨 En la sección Alojamientos podés encontrar información sobre lugares donde hospedarte.";
    }

    if (
        texto.includes("gracias")
    ) {
        return "¡De nada! 😊 Estoy para ayudarte.";
    }

    return "🤔 No estoy seguro de esa pregunta. Probá preguntarme por **termas, parques, paisajes, gastronomía, eventos o alojamientos**.";
}