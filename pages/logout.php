<?php

// Invalida la sesión actual antes de volver al inicio de sesión.
session_start();
session_destroy();

header("Location: login.php");
exit();