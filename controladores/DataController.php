<?php
require_once '../includes/base_datos.php';

class DataController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function mostrarCargarDatos() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['superusuario', 'cliente'])) {
            header("Location: ../vistas/inicio_sesion.php");
            exit();
        }
        return []; // Por ahora no necesitamos datos adicionales para la vista
    }
}

$controller = new DataController($conexion);
$data = $controller->mostrarCargarDatos();
?>