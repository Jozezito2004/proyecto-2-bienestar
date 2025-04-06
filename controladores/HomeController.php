<?php
require_once '../includes/base_datos.php';

class HomeController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function mostrarInicio() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        // Verificar autenticación (esto podría estar en un middleware, pero lo dejamos aquí por ahora)
        if (!isset($_SESSION['usuario_id'])) {
            header("Location: inicio_sesion.php");
            exit();
        }

        $rol = $_SESSION['rol'] ?? null;
        $fecha_actual = date('Y-m-d');

        // Consultas para el resumen de alimentación
        $total_registros = 0;
        $registros_hoy = 0;

        if ($rol === 'superusuario' || $rol === 'cliente') {
            $sql_total = "SELECT COUNT(*) as total FROM alimentacion_diaria";
            $stmt_total = $this->conexion->prepare($sql_total);
            $stmt_total->execute();
            $total_registros = $stmt_total->get_result()->fetch_assoc()['total'];
            $stmt_total->close();

            $sql_hoy = "SELECT COUNT(*) as hoy FROM alimentacion_diaria WHERE DATE(fecha_registro) = ?";
            $stmt_hoy = $this->conexion->prepare($sql_hoy);
            $stmt_hoy->bind_param("s", $fecha_actual);
            $stmt_hoy->execute();
            $registros_hoy = $stmt_hoy->get_result()->fetch_assoc()['hoy'];
            $stmt_hoy->close();
        }

        return [
            'rol' => $rol,
            'total_registros' => $total_registros,
            'registros_hoy' => $registros_hoy
        ];
    }
}

// Instanciar y ejecutar (esto podría manejarse con un enrutador en el futuro)
$controller = new HomeController($conexion);
$data = $controller->mostrarInicio();
?>