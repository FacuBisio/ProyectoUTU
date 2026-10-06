<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<?php

require_once(__DIR__ . "/../config/config.php");

?>
<link rel="stylesheet" href="<?= htmlspecialchars(url('assets/css/accesibilidad.css?v=5'), ENT_QUOTES, 'UTF-8') ?>">
<link rel="stylesheet" href="<?= htmlspecialchars(url('assets/css/busqueda-navbar.css?v=3'), ENT_QUOTES, 'UTF-8') ?>">

<section id="navbar">

    <div class="navbar-container">

        <div class="logo">
            <a href="<?= url('index.php') ?>">
                <h1>SIGTUR</h1>
            </a>
        </div>

        <div class="nav-links">

            <a class="link-btn" href="<?= url('index.php') ?>">
                Inicio
            </a>

            <div class="dropdown">

                <button class="link-btn">
                    Lugares ▾
                </button>

                <div class="menu-desplegable">

                    <div>

                        <h3>Naturaleza</h3>

                        <a href="<?= url('pages/lugares/parques.php') ?>">
                            Parques
                        </a>

                        <a href="<?= url('pages/lugares/paisajes.php') ?>">
                            Paisajes y Espacios Naturales
                        </a>

                    </div>

                    <div>

                        <h3>Historia y Cultura</h3>

                        <a href="<?= url('pages/lugares/patrimonio.php') ?>">
                            Patrimonio Histórico
                        </a>

                        <a href="<?= url('pages/lugares/museos.php') ?>">
                            Museos y Arte
                        </a>

                    </div>

                    <div>

                        <h3>Turismo y Experiencias</h3>

                        <a href="<?= url('pages/lugares/termas.php') ?>">
                            Termas y Bienestar
                        </a>

                        <a href="<?= url('pages/lugares/ocio.php') ?>">
                            Ocio y Vida Nocturna
                        </a>

                    </div>

                </div>

            </div>

            <a class="link-btn" href="<?= url('pages/eventos/eventos.php') ?>">
                Eventos
            </a>

            <div class="dropdown">

                <button class="link-btn">
                    Gastronomía ▾
                </button>

                <div class="menu-desplegable">

                    <div>

                        <h3>Comer</h3>

                        <a href="<?= url('pages/gastronomia/restaurantes.php') ?>">
                            Restaurantes
                        </a>

                        <a href="<?= url('pages/gastronomia/comida-rapida.php') ?>">
                            Comida Rápida
                        </a>

                    </div>

                    <div>

                        <h3>Postres</h3>

                        <a href="<?= url('pages/gastronomia/heladerias.php') ?>">
                            Heladerías
                        </a>

                        <a href="<?= url('pages/gastronomia/cafeterias.php') ?>">
                            Cafeterías
                        </a>

                    </div>

                    <div>

                        <h3>Destacados</h3>

                        <a href="<?= url('pages/gastronomia/locales-top.php') ?>">
                            Locales Top
                        </a>

                        <a href="<?= url('pages/gastronomia/recomendados.php') ?>">
                            Recomendados
                        </a>

                    </div>

                </div>

            </div>

        </div>

        <div class="nav-extra">

            <!-- Indicador del ITH -->
         <div class="ith-navbar">
             <span id="clima">
             🌡 Cargando...
             </span>
            </div>
<form class="buscador" action="<?= htmlspecialchars(url('pages/buscar.php'), ENT_QUOTES, 'UTF-8') ?>" method="GET" role="search">
    <label class="buscador-sr-only" for="busquedaNavbar">Buscar lugares</label>
    <input
        id="busquedaNavbar"
        type="search"
        name="q"
        placeholder="Buscar..."
        autocomplete="off"
        role="combobox"
        aria-autocomplete="list"
        aria-expanded="false"
        aria-controls="sugerenciasBusqueda"
        data-suggestions-url="<?= htmlspecialchars(url('pages/buscar.php'), ENT_QUOTES, 'UTF-8') ?>"
        required
    >
    <button type="submit" aria-label="Buscar">
        <i class="fa-solid fa-magnifying-glass" aria-hidden="true"></i>
    </button>
    <div class="buscador-sugerencias" id="sugerenciasBusqueda" role="listbox" aria-label="Resultados sugeridos" hidden></div>
</form>

            <div class="menu-usuario">
                <button
                    type="button"
                    class="user menu-usuario-boton"
                    id="menuUsuarioBoton"
                    aria-label="Abrir menú de cuenta y accesibilidad"
                    aria-expanded="false"
                    aria-controls="menuUsuario"
                >
                    <i class="fa-regular fa-user" aria-hidden="true"></i>
                </button>

                <div class="menu-usuario-panel" id="menuUsuario" role="region" aria-label="Opciones de cuenta y accesibilidad" hidden>
                    <?php if (isset($_SESSION["nombre"])): ?>
                        <div class="menu-usuario-cuenta">
                            <span class="menu-usuario-avatar" aria-hidden="true">
                                <?= htmlspecialchars(mb_strtoupper(mb_substr($_SESSION["nombre"], 0, 1, "UTF-8"), "UTF-8"), ENT_QUOTES, "UTF-8") ?>
                            </span>
                            <div>
                                <strong><?= htmlspecialchars($_SESSION["nombre"], ENT_QUOTES, "UTF-8") ?></strong>
                                <span>Tu cuenta</span>
                            </div>
                        </div>
                        <a class="menu-usuario-enlace" href="<?= htmlspecialchars(url('pages/perfil.php'), ENT_QUOTES, 'UTF-8') ?>">
                            <i class="fa-regular fa-id-card" aria-hidden="true"></i>
                            Configurar perfil
                        </a>
                        <?php if (($_SESSION["id_rol"] ?? null) == 1): ?>
                            <a class="menu-usuario-enlace" href="<?= htmlspecialchars(url('pages/admin/panel.php'), ENT_QUOTES, 'UTF-8') ?>">Panel de administración</a>
                        <?php endif; ?>
                        <a class="menu-usuario-enlace" href="<?= htmlspecialchars(url('pages/logout.php'), ENT_QUOTES, 'UTF-8') ?>">Cerrar sesión</a>
                    <?php else: ?>
                        <div class="menu-usuario-cuenta menu-usuario-cuenta--invitado">
                            <span class="menu-usuario-avatar" aria-hidden="true"><i class="fa-regular fa-user"></i></span>
                            <div>
                                <strong>Hola, visitante</strong>
                                <span>Accedé a tu cuenta</span>
                            </div>
                        </div>
                        <a class="menu-usuario-enlace" href="<?= htmlspecialchars(url('pages/login.php'), ENT_QUOTES, 'UTF-8') ?>">Iniciar sesión</a>
                        <a class="menu-usuario-enlace" href="<?= htmlspecialchars(url('pages/register.php'), ENT_QUOTES, 'UTF-8') ?>">Crear cuenta</a>
                    <?php endif; ?>

                    <div class="menu-usuario-separador"></div>
                    <div class="accesibilidad-controles">
                        <h2>Accesibilidad</h2>

                        <div class="accesibilidad-opcion">
                            <span id="etiquetaTema">Modo oscuro</span>
                            <button type="button" class="accesibilidad-interruptor" id="alternarTema" role="switch" aria-checked="false" aria-labelledby="etiquetaTema">
                                <span></span>
                            </button>
                        </div>

                        <div class="accesibilidad-opcion accesibilidad-opcion--texto">
                            <span id="etiquetaTexto">Tamaño del texto</span>
                            <div class="accesibilidad-tamano">
                                <button type="button" id="reducirTexto" aria-label="Reducir tamaño del texto">A−</button>
                                <output id="nivelTexto" aria-live="polite">100%</output>
                                <button type="button" id="aumentarTexto" aria-label="Aumentar tamaño del texto">A+</button>
                            </div>
                        </div>

                        <div class="accesibilidad-opcion">
                            <span id="etiquetaContraste">Alto contraste</span>
                            <button type="button" class="accesibilidad-interruptor" id="alternarContraste" role="switch" aria-checked="false" aria-labelledby="etiquetaContraste">
                                <span></span>
                            </button>
                        </div>

                        <button type="button" class="accesibilidad-restablecer" id="restablecerAccesibilidad">Restablecer preferencias</button>
                    </div>
                </div>
            </div>

        </div>

    </div>

</section>

<script src="<?= url('assets/js/ith.js?v=2') ?>"></script>
<script src="<?= url('assets/js/clima.js') ?>"></script>
<script src="<?= htmlspecialchars(url('assets/js/accesibilidad.js?v=3'), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<script src="<?= htmlspecialchars(url('assets/js/busqueda-navbar.js?v=3'), ENT_QUOTES, 'UTF-8') ?>" defer></script>
<?php include(__DIR__ . "/chat-widget.php"); ?>