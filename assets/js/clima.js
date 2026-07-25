const API_KEY = "8d0ad8180d7dea9356b595b7f6bd1f62";
const CIUDAD = "Salto,UY";

async function cargarClima() {

    try {

        const respuesta = await fetch(
            `https://api.openweathermap.org/data/2.5/weather?q=${CIUDAD}&units=metric&lang=es&appid=${API_KEY}`
        );

        const datos = await respuesta.json();

        document.getElementById("clima").innerHTML =
            `🌤 ${Math.round(datos.main.temp)}°C | 💧 ${datos.main.humidity}%`;

    } catch (error) {

        document.getElementById("clima").innerHTML =
            "Clima no disponible";

        console.error(error);

    }

}

cargarClima();

// Actualiza cada 10 minutos
setInterval(cargarClima, 600000);