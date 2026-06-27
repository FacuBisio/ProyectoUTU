<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>

    <link rel="stylesheet" href="assets/css/login.css">
    <link rel="stylesheet" href="assets/css/var.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>

<body>

    <main>
        <section class="contenedor">
            <h2>Formulario de Registro</h2>

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
        </section>
    </main>

    <script src="assets/js/login.js"></script>

</body>

</html>