<?php

require_once(__DIR__ . "/../config/config.php");

?>

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
                🌡 <span id="ithPorcentaje">ITH: --</span>
            </div>

            <div class="buscador">

                <input
                    type="text"
                    placeholder="Buscar..."
                >

                <button>
                    ⌕
                </button>

            </div>

            <a
                href="<?= url('pages/login.php') ?>"
                class="user"
            >
                <i class="fa-regular fa-user"></i>
            </a>

        </div>

    </div>

</section>

<script src="<?= url('assets/js/ith.js') ?>"></script>