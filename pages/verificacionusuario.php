<?php

session_start();

require_once("../conexion.php");

$correo = $_POST["correo"];
$contrasena = $_POST["contrasena"];

// Buscar usuario por correo y contraseña
$sql = "SELECT * FROM usuario
        WHERE CORREO = '$correo'
        AND CONTRASENA = '$contrasena'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {

    // Obtener los datos del usuario
    $usuario = $resultado->fetch_assoc();

    // Guardar datos en la sesión
    $_SESSION["id_usuario"] = $usuario["ID_USUARIO"];
    $_SESSION["nombre"] = $usuario["NOMBRE"];
    $_SESSION["correo"] = $usuario["CORREO"];
    $_SESSION["id_rol"] = $usuario["ID_ROL"];

    // Redireccionar dependiendo del rol

    if ($usuario["ID_ROL"] == 1) {

        header("Location: ../pages/admin/panel.php");

    } else {

        header("Location: ../index.php");

    }

    exit();

} else {

    echo "El correo o la contraseña son incorrectos.";

}

?>
