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



$sql = "SELECT 
            lugar.id_lugar,
            lugar.nombre,
            lugar.descripcion,
            lugar.direccion,
            lugar.imagen,
            categoria.nombre AS categoria

        FROM lugar

        INNER JOIN categoria

        ON lugar.id_categoria = categoria.id_categoria

        ORDER BY lugar.id_lugar DESC";


$resultado = $conexion->query($sql);

?>


<!DOCTYPE html>
<html lang="es">

<head>

<meta charset="UTF-8">

<title>Lugares</title>


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


img{

width:100px;
height:70px;
object-fit:cover;

}

</style>


</head>


<body>


<h1>Administrar Lugares</h1>


<br>


<a href="agregar_lugar.php">

<button>
Agregar Lugar
</button>

</a>


<br><br>


<table>


<tr>

<th>ID</th>

<th>Imagen</th>

<th>Nombre</th>

<th>Categoría</th>

<th>Dirección</th>

<th>Acciones</th>


</tr>



<?php while($fila = $resultado->fetch_assoc()){ ?>


<tr>


<td>
<?= $fila["id_lugar"] ?>
</td>


<td>

<img src="../../assets/img/<?= $fila["imagen"] ?>">

</td>


<td>
<?= $fila["nombre"] ?>
</td>


<td>
<?= $fila["categoria"] ?>
</td>


<td>
<?= $fila["direccion"] ?>
</td>



<td>

<a href="editar_lugar.php?id=<?= $fila["id_lugar"] ?>">

<button>
Editar
</button>

</a>

<a 
href="eliminar_lugar.php?id=<?= $fila["id_lugar"] ?>"
onclick="return confirm('¿Seguro que quieres eliminar este lugar?')"
>

<button type="button">
Eliminar
</button>

</a>


</td>


</tr>



<?php } ?>


</table>


</body>

</html>