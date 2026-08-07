<?php

session_start();

require_once("../../conexion.php");


if (!isset($_SESSION["id_usuario"]) || $_SESSION["id_rol"] != 3) {

    die("Acceso denegado.");

}


$id_lugar = $_GET["id"];


// Obtener lugar

$sql = "SELECT * FROM lugar WHERE id_lugar = ?";

$stmt = $conexion->prepare($sql);

$stmt->bind_param("i",$id_lugar);

$stmt->execute();

$resultado = $stmt->get_result();

$lugar = $resultado->fetch_assoc();



// Obtener categorías

$categorias = $conexion->query(
    "SELECT * FROM categoria"
);





// Actualizar

if($_SERVER["REQUEST_METHOD"] == "POST"){


    $id_categoria = $_POST["id_categoria"];
    $nombre = $_POST["nombre"];
    $descripcion = $_POST["descripcion"];
    $direccion = $_POST["direccion"];
    $imagen = $_POST["imagen"];



    $sql = "UPDATE lugar SET
            id_categoria=?,
            nombre=?,
            descripcion=?,
            direccion=?,
            imagen=?

            WHERE id_lugar=?";



    $stmt = $conexion->prepare($sql);


    $stmt->bind_param(
        "issssi",
        $id_categoria,
        $nombre,
        $descripcion,
        $direccion,
        $imagen,
        $id_lugar
    );


    if($stmt->execute()){

        header("Location: lugares.php");
        exit();

    }


}


?>


<!DOCTYPE html>

<html>

<head>

<title>Editar Lugar</title>

<style>

body{

font-family:Arial;
padding:40px;
background:#f4f4f4;

}


form{

background:white;
padding:30px;
width:500px;

}


input,textarea,select{

width:100%;
padding:10px;
margin-bottom:15px;

}

</style>

</head>


<body>


<h1>Editar Lugar</h1>


<form method="POST">


<label>Categoría</label>

<select name="id_categoria">


<?php while($cat=$categorias->fetch_assoc()){ ?>


<option value="<?= $cat["id_categoria"] ?>"
<?= $cat["id_categoria"] == $lugar["id_categoria"] ? "selected":"" ?>
>

<?= $cat["nombre"] ?>

</option>


<?php } ?>


</select>



<label>Nombre</label>

<input 
name="nombre"
value="<?= $lugar["nombre"] ?>"
>



<label>Descripción</label>

<textarea name="descripcion"><?= $lugar["descripcion"] ?></textarea>



<label>Dirección</label>

<input 
name="direccion"
value="<?= $lugar["direccion"] ?>"
>



<label>Imagen</label>

<input 
name="imagen"
value="<?= $lugar["imagen"] ?>"
>



<button>

Guardar cambios

</button>



</form>


</body>

</html>