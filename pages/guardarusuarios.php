<?php

include("../conexion.php");

$nombre = trim($_POST["usuario"]);
$contrasena = trim($_POST["password"]);
$correo = trim($_POST["correo"]);

if (empty($nombre) || empty($contrasena) || empty($correo)) {
    die("Complete todos los campos.");
}

// Verificar si el correo ya existe
$sql = "SELECT id_usuario FROM usuario WHERE correo = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("s", $correo);
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    die("El correo ya está registrado.");
}

// Encriptar contraseña
$contrasenaHash = password_hash($contrasena, PASSWORD_DEFAULT);

// Rol Usuario
$id_rol = 1;

// Guardar usuario
$sql = "INSERT INTO usuario (id_rol, nombre, contrasena, correo)
VALUES (?, ?, ?, ?)";

$stmt = $conexion->prepare($sql);
$stmt->bind_param("isss", $id_rol, $nombre, $contrasenaHash, $correo);

if ($stmt->execute()) {

    echo "<h2>Usuario registrado correctamente.</h2>";
    echo "<br>";
    echo "<a href='login.php'>Volver</a>";

} else {

    echo "Error: " . $conexion->error;

}

$stmt->close();
$conexion->close();

?>