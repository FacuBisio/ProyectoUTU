<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login</title>

    <link rel="stylesheet" href="../assets/css/login.css">
    <link rel="stylesheet" href="../assets/css/var.css">

    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@100..900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

</head>


<body>


<main>


<section class="contenedor">


<h2>Formulario de Registro</h2>


<form action="guardarusuarios.php" method="POST">


<label for="usuario">
Usuario
</label>

<input 
type="text" 
id="usuario" 
name="usuario"
placeholder="Ej: juan123"
required>


<label for="password">
Contraseña
</label>

<input 
type="password" 
id="password" 
name="password"
placeholder="Ingrese su contraseña"
required>


<label for="correo">
Correo Electrónico
</label>

<input 
type="email" 
id="correo" 
name="correo"
placeholder="Ej: juan@gmail.com"
required>


<button type="submit">
Registrar
</button>


</form>
<!-- BOTÓN VOLVER -->
<button type="button" onclick="history.back()" class="btn-volver">
    <i class="fa-solid fa-arrow-left"></i> Volver
</button>

</section>





<section class="contenedor">


<h2>Iniciar sesión</h2>


<form action="validar_login.php" method="POST">


<label for="correoLogin">
Correo Electrónico
</label>

<input 
type="email"
id="correoLogin"
name="correo"
placeholder="Ingrese su correo"
required>



<label for="passwordLogin">
Contraseña
</label>

<input 
type="password"
id="passwordLogin"
name="password"
placeholder="Ingrese su contraseña"
required>



<button type="submit">
Ingresar
</button>


</form>


</section>


</main>


</body>

</html>