document.addEventListener("DOMContentLoaded", () => {
    const elementoITH = document.getElementById("ithPorcentaje");
    if (!elementoITH) {
        return;
    }

    const temperatura = 30;
    const humedad = 70;
    const indice = temperatura - ((0.55 - (0.0055 * humedad)) * (temperatura - 14.5));
    elementoITH.textContent = `ITH: ${indice.toFixed(1)}`;
});