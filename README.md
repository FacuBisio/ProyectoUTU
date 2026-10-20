# ProyectoUTU - GoSalto - SIGTUR SALTO
.
Integrantes del equipo: Juan Pablo Rosa, Facundo Bisio, Alejo Mussetti, Santiago Rodriguez y Bruno Varela.

SIGTUR SALTO es una plataforma web turística desarrollada como proyecto final por la empresa GoSalto, diseñada para promocionar y difundir los principales atractivos de la ciudad de Salto, Uruguay.

El sistema permite a los usuarios explorar lugares de interés, eventos, actividades, gastronomía y otros servicios turísticos, facilitando el acceso a información actualizada en un único sitio web.

Tecnologías utilizadas:
- CSS3
- JavaScript
- PHP

"Descubre los mejores lugares, experiencias y sabores de Salto."

## Contenidos del curso aplicados

| Módulo | Contenidos | Evidencia en el proyecto |
| --- | --- | --- |
| 1. Diseño web | HTML5, títulos, párrafos, imágenes, enlaces, CSS3, Flexbox, Grid y diseño adaptable | `index.php`; estilos en `assets/css/`; página turística adaptable en `pages/salto.php` y `assets/css/turismo-salto.css` |
| 2. JavaScript | Manipulación del DOM, cambio de imágenes y contenido, estilos dinámicos, condicionales y `fetch()` | `assets/js/busqueda-navbar.js`, `assets/js/evento-formulario.js`, `assets/js/accesibilidad.js` y `assets/js/turismo-salto.js` |
| 3. Base de datos | Modelo Entidad-Relación (MER), modelo relacional, tablas, claves primarias y foráneas, consultas | MER y modelo relacional en `database/modelo-entidad-relacion.md`; esquema y datos de ejemplo en `proyectoutu.sql`; conexión en `conexion.php` |
| 4. PHP | Cliente-servidor, HTTP GET/POST y códigos de estado, conexión MySQL, CRUD, validación, manejo de errores y excepciones, hashing, sesiones y roles | Búsquedas en `pages/buscar.php`; registro e inicio de sesión en `pages/register.php` y `pages/login.php`; administración en `pages/admin/`; autenticación en `pages/verificacionusuario.php` |

La página turística incluye un control accesible de «Mostrar más/menos» para practicar condicionales y actualización del DOM. `assets/js/turismo-salto.js` actualiza el contenido con una plantilla HTML estática; el resto del sitio usa `textContent` y nodos DOM para no insertar datos del usuario con `innerHTML`. El cambio de `src` está presente, por ejemplo, en la vista previa de portadas de eventos (`assets/js/evento-formulario.js`).

Los foros, Padlet, reflexiones, presentaciones y archivos ZIP mencionados en la secuencia pertenecen a las actividades del aula virtual; no son funcionalidades del sitio web y no se incluyen como contenido simulado.

## Base de datos

- Para una instalación nueva, importa `proyectoutu.sql` en MariaDB/MySQL.
- Para actualizar una instalación existente, realiza primero una copia de seguridad y ejecuta una sola vez `database/migrations/20261019_integridad.sql`.
- La migración requiere que `usuario.CORREO` no tenga direcciones duplicadas y que las coordenadas de lugares y eventos estén completas y dentro de sus rangos geográficos.
