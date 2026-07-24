// Temperatura y humedad de ejemplo
let temperatura = 30;
let humedad = 70;

// Calcula el ITH
function calcularITH(temp, hum) {
    return temp - ((0.55 - (0.0055 * hum)) * (temp - 14.5));
}

// Muestra el ITH en el navbar
function mostrarITH() {

    const ith = calcularITH(temperatura, humedad);

    document.getElementById("ithPorcentaje").textContent =
        "ITH: " + ith.toFixed(1);

}

// Ejecutar cuando cargue la página
window.onload = mostrarITH;