const API_KEY = "8d0ad8180d7dea9356b595b7f6bd1f62";
const CIUDAD = "Salto,UY";

// Consulta y muestra el clima; informa si la solicitud no puede completarse.
async function cargarClima() {
    try {

        const respuesta = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${CIUDAD}&units=metric&lang=es&appid=${API_KEY}`
        );

        const datos = await respuesta.json();
        const elementoClima = document.getElementById("clima");

        elementoClima.textContent = `🌤 ${Math.round(datos.main.temp)}°C | 💧 ${datos.main.humidity}%`;

    } catch (error) {

        document.getElementById("clima").textContent = "Clima no disponible";

        console.error(error);

    }

}

// Carga el valor inicial y lo refresca cada diez minutos.
cargarClima();
setInterval(cargarClima, 600000);