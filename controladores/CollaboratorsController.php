<?php
require_once '../includes/base_datos.php';

class CollaboratorsController {
    private $conexion;

    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    public function mostrarColaboradores() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['superusuario', 'cliente'])) {
            header("Location: ../vistas/inicio_sesion.php");
            exit();
        }

        $id = trim($_GET['id'] ?? '');
        $numero_identificacion = trim($_GET['numero_identificacion'] ?? '');
        $edad_min = isset($_GET['edad_min']) && $_GET['edad_min'] !== '' ? (int)$_GET['edad_min'] : null;
        $edad_max = isset($_GET['edad_max']) && $_GET['edad_max'] !== '' ? (int)$_GET['edad_max'] : null;
        $genero = trim($_GET['genero'] ?? '');
        $unidad_trabajo = trim($_GET['unidad_trabajo'] ?? '');
        $puesto = trim($_GET['puesto'] ?? '');

        $sql = "SELECT id_colaborador, numero_identificacion, apellido, nombre, fecha_nacimiento, genero, unidad_trabajo, puesto, TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad FROM colaboradores WHERE 1=1";
        $params = [];
        $types = '';

        if ($id !== '') {
            $sql .= " AND id_colaborador = ?";
            $params[] = $id;
            $types .= 'i';
        }
        if ($numero_identificacion !== '') {
            $sql .= " AND LOWER(numero_identificacion) LIKE LOWER(?)";
            $params[] = "%$numero_identificacion%";
            $types .= 's';
        }
        if ($edad_min !== null && $edad_max !== null) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) BETWEEN ? AND ?";
            $params[] = $edad_min;
            $params[] = $edad_max;
            $types .= 'ii';
        } elseif ($edad_min !== null) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) >= ?";
            $params[] = $edad_min;
            $types .= 'i';
        } elseif ($edad_max !== null) {
            $sql .= " AND TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) <= ?";
            $params[] = $edad_max;
            $types .= 'i';
        }
        if ($genero !== '' && $genero !== 'todos') {
            $sql .= " AND genero = ?";
            $params[] = $genero;
            $types .= 's';
        }
        if ($unidad_trabajo !== '' && $unidad_trabajo !== 'todos') {
            $sql .= " AND LOWER(unidad_trabajo) = LOWER(?)";
            $params[] = $unidad_trabajo;
            $types .= 's';
        }
        if ($puesto !== '' && $puesto !== 'todos') {
            $sql .= " AND LOWER(puesto) = LOWER(?)";
            $params[] = $puesto;
            $types .= 's';
        }

        $stmt = $this->conexion->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $resultado = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

        $unidades_trabajo = $this->conexion->query("SELECT DISTINCT unidad_trabajo FROM colaboradores WHERE unidad_trabajo IS NOT NULL ORDER BY unidad_trabajo")->fetch_all(MYSQLI_ASSOC);
        $puestos = $this->conexion->query("SELECT DISTINCT puesto FROM colaboradores WHERE puesto IS NOT NULL ORDER BY puesto")->fetch_all(MYSQLI_ASSOC);

        return [
            'colaboradores' => $resultado,
            'unidades_trabajo' => $unidades_trabajo,
            'puestos' => $puestos,
            'filtros' => compact('id', 'numero_identificacion', 'edad_min', 'edad_max', 'genero', 'unidad_trabajo', 'puesto')
        ];
    }
}
?>