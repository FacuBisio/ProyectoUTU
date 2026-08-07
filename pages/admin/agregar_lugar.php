<?php

session_start();

require_once("../../conexion.php");


if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {

    die("Acceso denegado.");

}


// Obtener categorías

$sql = "SELECT * FROM categoria";

$categorias = $conexion->query($sql);



if($_SERVER["REQUEST_METHOD"] == "POST"){


    $id_categoria = $_POST["id_categoria"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $direccion = $_POST["direccion"];
    $imagen = $_POST["imagen"];



    $sql = "INSERT INTO lugar
            (id_categoria,nombre,descripcion,direccion,imagen)
            VALUES (?,?,?,?,?)";


    $stmt = $conexion->prepare($sql);


    $stmt->bind_param(
        "issss",
        $id_categoria,
        $nombre,
        $descripcion,
        $direccion,
        $imagen
    );


    if($stmt->execute()){

        header("Location: lugares.php");
        exit();

    }else{

        echo "Error al guardar: ".$conexion->error;

    }


}


?>


<!DOCTYPE html>

<html lang="es">

<head>

<meta charset="UTF-8">

<title>Agregar Lugar</title>


<style>

body{

font-family:Arial;
background:#f4f4f4;
padding:40px;

}


form{

background:white;
padding:30px;
width:500px;
border-radius:10px;

}


input,textarea,select{

width:100%;
padding:10px;
margin-bottom:15px;

}


button{

padding:10px 20px;

}

</style>


</head>


<body>


<h1>Agregar Lugar</h1>


<form method="POST">


<label>Categoría</label>

<select name="id_categoria">


<?php while($cat = $categorias->fetch_assoc()){ ?>


<option value="<?= $cat["id_categoria"] ?>">

<?= $cat["nombre"] ?>

</option>


<?php } ?>


</select>



<label>Nombre</label>

<input 
type="text"
name="nombre"
required
>



<label>Descripción</label>

<textarea 
name="descripcion"
required></textarea>



<label>Dirección</label>

<input
type="text"
name="direccion"
required
>



<label>Imagen</label>

<input
type="text"
name="imagen"
placeholder="ejemplo.jpg"
required
>


<button type="submit">
Guardar Lugar
</button>


</form>


</body>

</html>