<?php

session_start();

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION["id_rol"] != 3) {
    die("Acceso denegado.");
}

?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Panel de Administración</title>

<link rel="stylesheet" href="../../assets/css/var.css">
<link rel="stylesheet" href="../../assets/css/styles.css">

<style>

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
    font-family:Montserrat,sans-serif;
}

body{
    background:#f4f6f8;
}

.panel{

    display:flex;
    min-height:100vh;

}

.sidebar{

    width:260px;
    background:#1f2937;
    color:white;
    padding:30px;

}

.sidebar h2{

    margin-bottom:35px;

}

.sidebar a{

    display:block;
    color:white;
    text-decoration:none;
    padding:12px;
    border-radius:8px;
    margin-bottom:10px;

}

.sidebar a:hover{

    background:#374151;

}

.content{

    flex:1;
    padding:40px;

}

.cards{

    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;

}

.card{

    background:white;
    padding:25px;
    border-radius:12px;
    box-shadow:0 4px 10px rgba(0,0,0,.08);

}

.card h3{

    margin-bottom:10px;

}

</style>

</head>

<body>

<div class="panel">

<div class="sidebar">

<h2>SIGTUR</h2>

<a href="usuarios.php">👥 Usuarios</a>

<a href="lugares.php">📍 Lugares</a>

<a href="comentarios.php">💬 Comentarios</a>

<a href="eventos.php">📅 Eventos</a>

<a href="../logout.php">🚪 Cerrar sesión</a>

</div>

<div class="content">

<h1>Bienvenido <?= $_SESSION["nombre"] ?></h1>

<br>

<div class="cards">

<div class="card">

<h3>Usuarios</h3>

<p>Administrar usuarios del sistema.</p>

</div>

<div class="card">

<h3>Lugares</h3>

<p>Agregar y editar lugares turísticos.</p>

</div>

<div class="card">

<h3>Comentarios</h3>

<p>Administrar comentarios.</p>

</div>

<div class="card">

<h3>Eventos</h3>

<p>Gestionar eventos.</p>

</div>

</div>

</div>

</div>

</body>

</html>