document.addEventListener("DOMContentLoaded", () => {
    const menuBoton = document.getElementById("menuUsuarioBoton");
    const menu = document.getElementById("menuUsuario");

    if (!menuBoton || !menu) {
        return;
    }

    const root = document.documentElement;
    const controles = {
        tema: document.getElementById("alternarTema"),
        contraste: document.getElementById("alternarContraste"),
        reducirTexto: document.getElementById("reducirTexto"),
        aumentarTexto: document.getElementById("aumentarTexto"),
        nivelTexto: document.getElementById("nivelTexto"),
        restablecer: document.getElementById("restablecerAccesibilidad")
    };
    const clavePreferencias = "gosalto-preferencias-accesibilidad";
    const valoresIniciales = {
        temaOscuro: false,
        altoContraste: false,
        escalaTexto: 100
    };
    let preferencias = { ...valoresIniciales };

    try {
        const guardadas = JSON.parse(localStorage.getItem(clavePreferencias) || "{}");
        preferencias = {
            temaOscuro: guardadas.temaOscuro === true,
            altoContraste: guardadas.altoContraste === true,
            escalaTexto: [100, 110, 120, 130].includes(guardadas.escalaTexto)
                ? guardadas.escalaTexto
                : 100
        };
    } catch (error) {
        console.warn("No se pudieron cargar las preferencias de accesibilidad.", error);
    }

    function guardarPreferencias() {
        try {
            localStorage.setItem(clavePreferencias, JSON.stringify(preferencias));
        } catch (error) {
            console.warn("No se pudieron guardar las preferencias de accesibilidad.", error);
        }
    }

    function actualizarInterfaz() {
        root.dataset.tema = preferencias.temaOscuro ? "oscuro" : "claro";
        root.dataset.altoContraste = String(preferencias.altoContraste);
        root.style.fontSize = `${preferencias.escalaTexto}%`;

        controles.tema.setAttribute("aria-checked", String(preferencias.temaOscuro));
        controles.contraste.setAttribute("aria-checked", String(preferencias.altoContraste));
        controles.nivelTexto.value = `${preferencias.escalaTexto}%`;
        controles.reducirTexto.disabled = preferencias.escalaTexto <= 100;
        controles.aumentarTexto.disabled = preferencias.escalaTexto >= 130;
    }

    function alternarMenu(abierto) {
        menu.hidden = !abierto;
        menuBoton.setAttribute("aria-expanded", String(abierto));
        menuBoton.setAttribute(
            "aria-label",
            abierto ? "Cerrar menú de cuenta y accesibilidad" : "Abrir menú de cuenta y accesibilidad"
        );
    }

    menuBoton.addEventListener("click", () => {
        const hoverDisponible = window.matchMedia("(hover: hover) and (pointer: fine)").matches;
        alternarMenu(hoverDisponible ? true : menu.hidden);
    });

    const menuContenedor = menuBoton.closest(".menu-usuario");
    if (menuContenedor) {
        menuContenedor.addEventListener("pointerenter", evento => {
            if (evento.pointerType === "mouse") {
                alternarMenu(true);
            }
        });

        menuContenedor.addEventListener("pointerleave", evento => {
            if (evento.pointerType === "mouse" && !menu.contains(document.activeElement)) {
                alternarMenu(false);
            }
        });
    }

    document.addEventListener("click", evento => {
        if (!menu.contains(evento.target) && !menuBoton.contains(evento.target)) {
            alternarMenu(false);
        }
    });

    document.addEventListener("keydown", evento => {
        if (evento.key === "Escape" && !menu.hidden) {
            alternarMenu(false);
            menuBoton.focus();
        }

        if (evento.key === "Tab" && !menu.hidden) {
            const elementosEnFoco = menu.querySelectorAll("a[href], button:not(:disabled)");
            const primero = elementosEnFoco[0];
            const ultimo = elementosEnFoco[elementosEnFoco.length - 1];

            if (evento.shiftKey && document.activeElement === primero) {
                evento.preventDefault();
                ultimo.focus();
            } else if (!evento.shiftKey && document.activeElement === ultimo) {
                evento.preventDefault();
                primero.focus();
            }
        }
    });

    controles.tema.addEventListener("click", () => {
        preferencias.temaOscuro = !preferencias.temaOscuro;
        actualizarInterfaz();
        guardarPreferencias();
    });

    controles.contraste.addEventListener("click", () => {
        preferencias.altoContraste = !preferencias.altoContraste;
        actualizarInterfaz();
        guardarPreferencias();
    });

    controles.reducirTexto.addEventListener("click", () => {
        preferencias.escalaTexto = Math.max(100, preferencias.escalaTexto - 10);
        actualizarInterfaz();
        guardarPreferencias();
    });

    controles.aumentarTexto.addEventListener("click", () => {
        preferencias.escalaTexto = Math.min(130, preferencias.escalaTexto + 10);
        actualizarInterfaz();
        guardarPreferencias();
    });

    controles.restablecer.addEventListener("click", () => {
        preferencias = { ...valoresIniciales };
        actualizarInterfaz();
        guardarPreferencias();
    });

    actualizarInterfaz();
});
