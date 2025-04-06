<?php
require_once '../includes/base_datos.php';

class PsychometricController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function mostrarCargarPruebas() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['superusuario', 'cliente'])) {
            header("Location: ../vistas/inicio_sesion.php");
            exit();
        }

        $colaboradores = $this->conexion->query("SELECT id_colaborador, numero_identificacion, nombre, apellido FROM colaboradores ORDER BY apellido, nombre")->fetch_all(MYSQLI_ASSOC);

        $selected_colaborador = isset($_POST['id_colaborador']) ? (int)$_POST['id_colaborador'] : null;
        $test_results = null;
        if ($selected_colaborador) {
            $stmt = $this->conexion->prepare("SELECT fecha_evaluacion, estres, depresion, burnout, ansiedad FROM pruebas_psicometricas WHERE id_colaborador = ?");
            $stmt->bind_param("i", $selected_colaborador);
            $stmt->execute();
            $test_results = $stmt->get_result()->fetch_assoc();
            $stmt->close();
        }

        return [
            'colaboradores' => $colaboradores,
            'selected_colaborador' => $selected_colaborador,
            'test_results' => $test_results
        ];
    }
}
?>