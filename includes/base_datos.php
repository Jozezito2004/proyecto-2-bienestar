<?php
$host = 'localhost';
$usuario = 'root';
$contrasena = 'root'; // Cambia según tu configuración de MAMP
$base_datos = 'bienestar_integral_buap';

$conexion = new mysqli($host, $usuario, $contrasena, $base_datos);
if ($conexion->connect_error) {
    die("Error de conexión: " . $conexion->connect_error);
}
$conexion->set_charset("utf8");
?>