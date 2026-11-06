<?php

// Invalida la sesión actual antes de volver al inicio de sesión.
require_once(__DIR__ . "/../config/sesion.php");
iniciarSesionSegura();
session_destroy();

header("Location: login.php");
exit();