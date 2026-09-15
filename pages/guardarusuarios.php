<?php

session_start();

require_once("../conexion.php");

$nombre = $_POST["nombre"];
$correo = $_POST["correo"];
$contrasena = $_POST["contrasena"];
$telefono = $_POST["telefono"];

// Comprobar si el correo ya existe
$sql = "SELECT * FROM usuario WHERE correo = '$correo'";

$resultado = $conexion->query($sql);

if ($resultado->num_rows > 0) {

    echo "Ese correo ya está registrado.";

} else {

    // Registrar usuario como usuario normal
    $sql = "INSERT INTO usuario (ID_ROL, NOMBRE, CONTRASENA, CORREO)
            VALUES (3, '$nombre', '$contrasena', '$correo')";

    if ($conexion->query($sql)) {

        // Obtener ID del usuario recién creado
        $idUsuario = $conexion->insert_id;

        // Guardar teléfono
        $sqlTelefono = "INSERT INTO usuario_telefono (ID_USUARIO, TELEFONO)
                        VALUES ($idUsuario, '$telefono')";

        $conexion->query($sqlTelefono);

        // Iniciar sesión automáticamente
        $_SESSION["id_usuario"] = $idUsuario;
        $_SESSION["nombre"] = $nombre;
        $_SESSION["correo"] = $correo;
        $_SESSION["id_rol"] = 3;

        // Volver al index
        header("Location: ../index.php");
        exit();

    } else {

        echo "Error al registrar el usuario.";

    }
}

?>