<?php
session_start();

require_once("../../conexion.php");

if (!isset($_SESSION["id_usuario"])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION["id_rol"] != 3) {
    die("Acceso denegado.");
}

$sql = "SELECT usuario.id_usuario,
               usuario.nombre,
               usuario.correo,
               rol.nombre AS rol
        FROM usuario
        INNER JOIN rol
        ON usuario.id_rol = rol.id_rol
        ORDER BY usuario.id_usuario";

$resultado = $conexion->query($sql);
?>

<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">
<title>Usuarios</title>

<style>

body{

    font-family:Arial;
    background:#f4f4f4;
    padding:40px;

}

table{

    width:100%;
    border-collapse:collapse;
    background:white;

}

th,td{

    padding:12px;
    border:1px solid #ddd;
    text-align:center;

}

th{

    background:#1f2937;
    color:white;

}

a{

    text-decoration:none;

}

</style>

</head>

<body>

<h1>Administrar Usuarios</h1>

<br>

<table>

<tr>

<th>ID</th>

<th>Nombre</th>

<th>Correo</th>

<th>Rol</th>

<th>Acciones</th>

</tr>

<?php while($fila = $resultado->fetch_assoc()) { ?>

<tr>

<td><?= $fila["id_usuario"] ?></td>

<td><?= htmlspecialchars($fila["nombre"]) ?></td>

<td><?= htmlspecialchars($fila["correo"]) ?></td>

<td><?= htmlspecialchars($fila["rol"]) ?></td>

<td>

<form action="cambiar_rol.php" method="POST">

    <input
        type="hidden"
        name="id_usuario"
        value="<?= $fila["id_usuario"] ?>"
    >

    <select name="id_rol">

        <option value="1" <?= $fila["rol"] == "Usuario" ? "selected" : "" ?>>
            Usuario
        </option>

        <option value="2" <?= $fila["rol"] == "Moderador" ? "selected" : "" ?>>
            Moderador
        </option>

        <option value="3" <?= $fila["rol"] == "Administrador" ? "selected" : "" ?>>
            Administrador
        </option>

    </select>

    <button type="submit">
        Guardar
    </button>
    <a 
href="eliminar_usuario.php?id=<?= $fila["id_usuario"] ?>"
onclick="return confirm('¿Seguro que quieres eliminar este usuario?')"
>

<button type="button">
Eliminar
</button>

</a>

</form>

</td>

</tr>

<?php } ?>

</table>

</body>

</html>