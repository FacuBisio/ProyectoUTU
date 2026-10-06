document.addEventListener("DOMContentLoaded", () => {
    const formulario = document.querySelector(".evento-formulario");
    if (!formulario) {
        return;
    }

    const direccion = document.querySelector("#evento-direccion");
    const latitud = document.querySelector("#evento-latitud");
    const longitud = document.querySelector("#evento-longitud");
    const estadoUbicacion = document.querySelector("#evento-mapa-estado");
    const botonLimpiarUbicacion = document.querySelector("#limpiar-ubicacion-evento");
    const contenedorMapa = document.querySelector("#mapaEvento");

    if (contenedorMapa && window.L) {
        const mapa = L.map(contenedorMapa).setView([-31.3833, -57.9667], 13);
        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: "&copy; OpenStreetMap contributors",
            maxZoom: 19
        }).addTo(mapa);

        let marcador = null;
        mapa.on("click", evento => {
            const punto = evento.latlng;
            latitud.value = punto.lat.toFixed(7);
            longitud.value = punto.lng.toFixed(7);
            if (marcador) {
                marcador.setLatLng(punto);
            } else {
                marcador = L.marker(punto).addTo(mapa);
            }
            estadoUbicacion.textContent = `Punto marcado: ${latitud.value}, ${longitud.value}. Si querés, agregá también una dirección.`;
            botonLimpiarUbicacion.hidden = false;
        });

        botonLimpiarUbicacion.addEventListener("click", () => {
            if (marcador) {
                mapa.removeLayer(marcador);
                marcador = null;
            }
            latitud.value = "";
            longitud.value = "";
            botonLimpiarUbicacion.hidden = true;
            estadoUbicacion.textContent = direccion.value.trim()
                ? "Se usará la dirección que escribiste."
                : "Podés marcar el mapa, escribir una dirección o completar ambas opciones.";
        });

        window.setTimeout(() => mapa.invalidateSize(), 200);
    } else if (contenedorMapa) {
        contenedorMapa.hidden = true;
        estadoUbicacion.textContent = "No se pudo cargar el mapa. Escribí la dirección del evento.";
        console.error("No se pudo cargar Leaflet para elegir la ubicación del evento.");
    }

    direccion.addEventListener("input", () => {
        if (!latitud.value && !longitud.value) {
            estadoUbicacion.textContent = direccion.value.trim()
                ? "Se guardará la dirección que escribiste."
                : "Podés marcar el mapa, escribir una dirección o completar ambas opciones.";
        }
    });

    formulario.addEventListener("submit", evento => {
        const puntoParcial = Boolean(latitud.value) !== Boolean(longitud.value);
        if (puntoParcial || (!direccion.value.trim() && (!latitud.value || !longitud.value))) {
            evento.preventDefault();
            estadoUbicacion.textContent = puntoParcial
                ? "La ubicación del mapa está incompleta. Marcá nuevamente el punto."
                : "Escribí una dirección o marcá el mapa antes de publicar.";
            (direccion.value.trim() ? contenedorMapa : direccion).focus();
            return;
        }

        const archivo = document.querySelector("#evento-imagen").files[0];
        if (archivo && archivo.size > 5 * 1024 * 1024) {
            evento.preventDefault();
            window.alert("La imagen de portada debe pesar como máximo 5 MB.");
            document.querySelector("#evento-imagen").focus();
        }
    });

    const inputImagen = document.querySelector("#evento-imagen");
    const preview = document.querySelector("#evento-imagen-preview");
    const imagenPreview = document.querySelector("#evento-imagen-preview-img");
    const botonQuitarImagen = document.querySelector("#quitar-imagen-evento");
    let urlPreview = null;

    inputImagen.addEventListener("change", () => {
        const archivo = inputImagen.files[0];
        if (urlPreview) {
            URL.revokeObjectURL(urlPreview);
            urlPreview = null;
        }

        if (!archivo) {
            preview.hidden = true;
            imagenPreview.removeAttribute("src");
            return;
        }

        if (!["image/jpeg", "image/png", "image/webp"].includes(archivo.type)) {
            inputImagen.value = "";
            preview.hidden = true;
            window.alert("Elegí una imagen JPG, PNG o WebP.");
            return;
        }

        urlPreview = URL.createObjectURL(archivo);
        imagenPreview.src = urlPreview;
        preview.hidden = false;
    });

    botonQuitarImagen.addEventListener("click", () => {
        inputImagen.value = "";
        preview.hidden = true;
        imagenPreview.removeAttribute("src");
        if (urlPreview) {
            URL.revokeObjectURL(urlPreview);
            urlPreview = null;
        }
        inputImagen.focus();
    });

    window.addEventListener("beforeunload", () => {
        if (urlPreview) {
            URL.revokeObjectURL(urlPreview);
        }
    }, { once: true });
});
