<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Museos y Arte</title>

    <link rel="stylesheet" href="../css/style-secciones.css">
    <link rel="stylesheet" href="../css/var.css">
    <link rel="stylesheet" href="../css/slider.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>

<!-- NAVBAR -->
<section id="navbar">

    <div class="navbar-container">

        <div class="logo">
            <h1>SIGTUR</h1>
        </div>

        <div class="nav-links">

                <a class="link-btn" href="../../index.php">
                Inicio
                </a>

            <div class="dropdown">

                <button class="link-btn">
                    Lugares ▾
                </button>

                <div class="menu-desplegable">

                    <div>
                        <h3>Naturaleza</h3>

                        <a href="parques.php">Parques</a>
                        <a href="paisajes.php">Paisajes y Espacios Naturales</a>
                    </div>

                    <div>
                        <h3>Historia y Cultura</h3>

                        <a href="patrimonio.php">Patrimonio Histórico</a>
                        <a href="museos.php">Museos y Arte</a>
                    </div>

                    <div>
                        <h3>Turismo y Experiencias</h3>

                        <a href="termas.php">Termas y Bienestar</a>
                        <a href="ocio.php">Ocio y Vida Nocturna</a>
                        <a href="alojamientos.php">Alojamientos</a>

                    </div>

                </div>

            </div>

            <a class="link-btn" href="eventos.php">
                Eventos
            </a>

            <div class="dropdown">

                <button class="link-btn">
                    Gastronomía ▾
                </button>

                <div class="menu-desplegable">

                    <div>
                        <h3>Comer</h3>

                        <a href="#">Restaurantes</a>
                        <a href="#">Comida Rápida</a>
                    </div>

                    <div>
                        <h3>Postres</h3>

                        <a href="#">Heladerías</a>
                        <a href="#">Cafeterías</a>
                    </div>

                    <div>
                        <h3>Destacados</h3>

                        <a href="#">Locales Top</a>
                        <a href="#">Recomendados</a>
                    </div>

                </div>

            </div>

        </div>

        <div class="nav-extra">

            <div class="buscador">

                <input type="text" placeholder="Buscar...">

                <button>⌕</button>

            </div>

            <div class="user">
                <span><i class="fa-regular fa-user"></i></span>
            </div>

        </div>

    </div>

</section>

<section class="eventos-container">

    <div class="evento-form">

        <h2>Nuevo Evento</h2>

            <form>

                <label for="usuario">Usuario</label>
                <input type="text" id="usuario" placeholder="Ej: juan123">

                <label for="password">Contraseña</label>
                <input type="password" id="password" placeholder="Ingrese su contraseña">

                <label for="correo">Correo Electrónico</label>
                <input type="email" id="correo" placeholder="Ej: juan@gmail.com">

                <label for="contacto">Contacto</label>
                <input type="text" id="contacto" placeholder="Ej: 099123456">

                <label for="departamento">Departamento</label>
                <input type="text" id="departamento" placeholder="Ej: Salto">

                <label for="comentario">Datos Registrados</label>
                <textarea id="comentario" cols="40" rows="6"></textarea>

                <button type="button" id="btnAgregar">Registrar</button>

            </form>

    </div>



    <div class="panel-derecho">

        <div class="calendario">

            <h2>Julio 2026</h2>

            <table>

                <tr>
                    <th>L</th>
                    <th>M</th>
                    <th>Mi</th>
                    <th>J</th>
                    <th>V</th>
                    <th>S</th>
                    <th>D</th>
                </tr>

                <tr>
                    <td></td>
                    <td></td>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                </tr>

                <tr>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                    <td>9</td>
                    <td>10</td>
                    <td>11</td>
                    <td>12</td>
                </tr>

                <tr>
                    <td>13</td>
                    <td>14</td>
                    <td>15</td>
                    <td>16</td>
                    <td>17</td>
                    <td>18</td>
                    <td class="activo">19</td>
                </tr>

            </table>

        </div>



        <div class="horarios">

            <h2>Horarios</h2>

            <div class="horas">

                <button>08:00</button>
                <button>09:00</button>
                <button>10:00</button>
                <button>11:00</button>

                <button>12:00</button>
                <button>13:00</button>
                <button>14:00</button>
                <button>15:00</button>

                <button>16:00</button>
                <button>17:00</button>
                <button>18:00</button>
                <button>19:00</button>

            </div>

        </div>

    </div>

</section>



<section class="destacados">

    <h2>Destacados</h2>

    <div class="cards">

        <div class="card-evento">

            <img src="../img/exposalto.jpg">

            <div class="card-info">

                <h3>Expo Salto - Hipódromo de Salto</h3>

                <p>Jueves 1 de Octubre - Domingo 4 de Octubre</p>

            </div>

        </div>



        <div class="card-evento">

            <img src="../img/racanegra.jpg">

            <div class="card-info">

                <h3>Concierto Raça Negra - Parque Harriague</h3>

                <p>22 de Noviembre - 20:00 hs</p>

            </div>

        </div>



        <div class="card-evento">

            <img src="../img/catherinevergnes.jpg">

            <div class="card-info">

                <h3>Fiesta de San Juan - Termas del Dayman</h3>

                <p>20 de Junio - 20:00 hs</p>

            </div>

        </div>

    </div>

</section>

<!-- FOOTER -->
<footer id="footer">

    <div class="footer-container">

        <div class="footer-logo">

            <h2>SIGTUR</h2>

            <p>
                Descubrí los mejores lugares, experiencias y sabores de Salto.
            </p>

        </div>

        <div class="footer-links">

            <div>

                <h3>Navegación</h3>

                <a href="#">Inicio</a>
                <a href="#">Lugares</a>
                <a href="#">Eventos</a>
                <a href="#">Gastronomía</a>

            </div>

            <div>

                <h3>Explorar</h3>

                <a href="#">Termas</a>
                <a href="#">Naturaleza</a>
                <a href="#">Cultura</a>
                <a href="#">Parques</a>

            </div>

        </div>

    </div>

    <div class="footer-copy">
        <p>© 2026 GoSalto — Todos los derechos reservados.</p>
    </div>

</footer>

<!-- CHAT PERSONAS -->
<div class="chat-widget">

    <button class="chat-btn">

        <i class="fa-regular fa-message"></i>

        <div class="chat-notification"></div>

    </button>

</div>

<script src="assets/js/eventos.js"></script>

</body>
</html>