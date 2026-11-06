const ENDPOINT_CLIMA = "https://api.open-meteo.com/v1/forecast?latitude=-31.3833&longitude=-57.9667&current=temperature_2m%2Crelative_humidity_2m&timezone=America%2FMontevideo";

async function cargarClima() {
    const elementoClima = document.getElementById("clima");
    if (!elementoClima) {
        return;
    }

    try {
        const controlador = new AbortController();
        const timeout = window.setTimeout(() => controlador.abort(), 15000);
        let respuesta;
        try {
            respuesta = await fetch(ENDPOINT_CLIMA, {
                signal: controlador.signal,
                headers: { "Accept": "application/json" }
            });
        } finally {
            window.clearTimeout(timeout);
        }
        const tipoContenido = respuesta.headers.get("content-type") || "";
        if (!tipoContenido.includes("application/json")) {
            throw new Error("El servicio del clima devolvió una respuesta inesperada.");
        }
        const datos = await respuesta.json();
        const temperatura = datos.current && datos.current.temperature_2m;
        const humedad = datos.current && datos.current.relative_humidity_2m;
        if (!respuesta.ok || !Number.isFinite(temperatura) || !Number.isFinite(humedad)) {
            throw new Error(`El servicio del clima respondió con HTTP ${respuesta.status} o datos incompletos.`);
        }
        elementoClima.textContent = `🌤 ${Math.round(temperatura)}°C | 💧 ${humedad}%`;
    } catch (error) {
        elementoClima.textContent = "Clima no disponible";
        console.error("No se pudo cargar el clima de Salto.", error);
    }
}

cargarClima();
setInterval(cargarClima, 600000);