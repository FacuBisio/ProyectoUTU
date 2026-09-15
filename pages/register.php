<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulario de Registro</title>
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>

<div class="form-container">

    <h1>Crear cuenta</h1>

    <form action="guardarusuarios.php" method="POST">

        <input type="text" name="nombre" placeholder="Nombre" required>

        <input type="email" name="correo" placeholder="Correo" required>

        <input type="password" name="contrasena" placeholder="Contraseña" required>

        <input type="text" name="telefono" placeholder="Teléfono" required>

        <button type="submit">Registrarse</button>

    </form>

    <p>
        ¿Ya tienes una cuenta?
        <a href="login.php">Iniciar sesión</a>
    </p>

    <button type="button" onclick="history.back()" class="btn-volver">
        Volver
    </button>

</div>
    
</body>
</html>