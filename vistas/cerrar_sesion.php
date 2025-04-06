<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Destruir la sesión
session_unset();
session_destroy();

// Redirigir a la página de inicio de sesión
header("Location: inicio_sesion.php");
exit();
?>