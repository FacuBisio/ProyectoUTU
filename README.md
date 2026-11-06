# ProyectoUTU - GoSalto - SIGTUR SALTO
.
Integrantes del equipo: Juan Pablo Rosa, Facundo Bisio, Alejo Mussetti, Santiago Rodriguez y Bruno Varela.

SIGTUR SALTO es una plataforma web turística desarrollada como proyecto final por la empresa GoSalto, diseñada para promocionar y difundir los principales atractivos de la ciudad de Salto, Uruguay.

El sistema permite a los usuarios explorar lugares de interés, eventos, actividades, gastronomía y otros servicios turísticos, facilitando el acceso a información actualizada en un único sitio web.

Tecnologías utilizadas:
- HTML5
- CSS3
- JavaScript
- PHP
- MySQL / MariaDB

"Descubre los mejores lugares, experiencias y sabores de Salto."

## Contenidos del curso aplicados

La documentación de requisitos, casos de uso, historias de usuario, manuales, pruebas y puesta en marcha está en [`docs/proyecto.md`](docs/proyecto.md).

| Módulo | Contenidos | Evidencia en el proyecto |
| --- | --- | --- |
| 1. Diseño web | HTML5, títulos, párrafos, imágenes, enlaces, CSS3, Flexbox, Grid y diseño adaptable | `index.php`; estilos en `assets/css/`; página turística adaptable en `pages/salto.php` y `assets/css/turismo-salto.css` |
| 2. JavaScript | Manipulación del DOM, cambio de imágenes y contenido, estilos dinámicos, condicionales y `fetch()` | `assets/js/busqueda-navbar.js`, `assets/js/evento-formulario.js`, `assets/js/accesibilidad.js` y `assets/js/turismo-salto.js` |
| 3. Base de datos | Modelo Entidad-Relación (MER), modelo relacional, tablas, claves primarias y foráneas, consultas | MER y modelo relacional en `database/modelo-entidad-relacion.md`; esquema y datos de ejemplo en `proyectoutu.sql`; conexión en `conexion.php` |
| 4. PHP | Cliente-servidor, HTTP GET/POST y códigos de estado, conexión MySQL, CRUD, validación, manejo de errores y excepciones, hashing, sesiones y roles | Búsquedas en `pages/buscar.php`; registro e inicio de sesión en `pages/register.php` y `pages/login.php`; administración en `pages/admin/`; autenticación en `pages/verificacionusuario.php` |

La página turística incluye un control accesible de «Mostrar más/menos» para practicar condicionales y actualización del DOM. `assets/js/turismo-salto.js` actualiza el contenido con una plantilla HTML estática; el resto del sitio usa `textContent` y nodos DOM para no insertar datos del usuario con `innerHTML`. El cambio de `src` está presente, por ejemplo, en la vista previa de portadas de eventos (`assets/js/evento-formulario.js`). Los mapas permiten centrar la vista en la ubicación del visitante solo después de que este lo solicite; esa ubicación no se almacena en la aplicación.

Los formularios que modifican datos validan sus solicitudes en PHP y usan tokens CSRF; las acciones de eliminación y cambio de rol se envían mediante POST. `config/sesion.php` establece cookies de sesión HttpOnly, SameSite=Lax, modo estricto y el atributo Secure cuando la conexión usa HTTPS. El volcado SQL no incluye cuentas, comentarios ni eventos de demostración; crea una cuenta desde el registro y asigna el rol administrador desde la base de datos solo para el entorno local.

Los foros, Padlet, reflexiones, presentaciones y archivos ZIP mencionados en la secuencia pertenecen a las actividades del aula virtual; no son funcionalidades del sitio web y no se incluyen como contenido simulado.

## Base de datos

- Para una instalación nueva, importa `proyectoutu.sql` en MariaDB/MySQL.
- En desarrollo con XAMPP, `conexion.php` usa `localhost`, `root` y contraseña vacía si no se configuran variables. En producción establece `APP_ENV=production`, `DB_HOST`, `DB_NAME`, `DB_USER` y `DB_PASSWORD` en el servidor; no guardes credenciales reales en el repositorio.
- En bases anteriores, las contraseñas heredadas se convierten a hash luego de un inicio de sesión válido. Si el repositorio o sus versiones anteriores fueron compartidos con credenciales reales, cámbialas: el historial de Git puede conservar valores antiguos.
- Para actualizar una instalación existente, realiza primero una copia de seguridad y ejecuta una sola vez `database/migrations/20261019_integridad.sql`.
- La migración requiere que `usuario.CORREO` no tenga direcciones duplicadas y que las coordenadas de lugares y eventos estén completas y dentro de sus rangos geográficos.
- Después de la migración de integridad, realiza otra copia de seguridad y ejecuta una sola vez `database/migrations/20261102_planificacion.sql` para habilitar horarios, precios, contacto, accesibilidad física, tipos de evento y lugares guardados.
- Para actualizar una instalación existente, realiza una copia de seguridad y ejecuta una vez `database/migrations/20261105_contacto_consultas.sql` para guardar las consultas del formulario de contacto y revisarlas en el panel administrativo. Las instalaciones nuevas ya incluyen esta tabla en `proyectoutu.sql`.
- Luego de esa migración, ejecuta una vez `database/migrations/20261106_consultas_estado.sql` para habilitar el seguimiento de consultas. Las instalaciones nuevas ya incluyen la columna `ESTADO`; los mensajes comienzan como **Pendiente**.

Las cuentas pueden guardar destinos desde la búsqueda para armar una ruta, consultar el mapa de sus lugares y abrir un recorrido a pie en Google Maps. Los administradores editan los horarios, precios y datos de contacto desde el formulario de cada lugar, revisan y actualizan el estado de las consultas desde **Administración → Consultas** y gestionan usuarios, lugares, eventos y comentarios. El aviso público de [privacidad](pages/privacidad.php) describe la información recogida por el sitio.

## Clima

El navegador consulta las condiciones actuales de Salto directamente a Open-Meteo. No requiere una clave de API. La atribución al proveedor y a su licencia CC BY 4.0 aparece discretamente en el pie de página, no junto al indicador del clima. La clave antigua de OpenWeatherMap que estuvo expuesta en el código debe revocarse desde esa cuenta.
