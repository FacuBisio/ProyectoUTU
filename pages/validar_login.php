<?php

session_start();

include("../conexion.php");


$correo = $_POST["correo"];
$contrasena = $_POST["password"];


$sql = "SELECT * FROM usuario WHERE correo = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();

$resultado = $stmt->get_result();


if ($resultado->num_rows == 1) {

    $usuario = $resultado->fetch_assoc();


    if (password_verify($contrasena, $usuario["contrasena"])) {


        $_SESSION["id_usuario"] = $usuario["id_usuario"];
        $_SESSION["id_rol"] = $usuario["id_rol"];
        $_SESSION["nombre"] = $usuario["nombre"];


        header("Location: ../index.php");
            exit;

    } else {

        echo "Contraseña incorrecta";

    }


} else {

    echo "Usuario no encontrado";

}


$stmt->close();
$conexion->close();

?>