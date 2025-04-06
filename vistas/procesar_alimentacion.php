<?php
require_once '../includes/base_datos.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['superusuario', 'cliente'])) {
    header("Location: ../vistas/inicio_sesion.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../vistas/cargar_alimentacion.php?error=Método no permitido");
    exit();
}

// Validar datos requeridos
$required_fields = ['id_colaborador', 'fecha_registro', 'hora_registro', 'tipo_comida', 'id_alimento', 'cantidad'];
foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || empty($_POST[$field])) {
        header("Location: ../vistas/cargar_alimentacion.php?error=Faltan datos requeridos: $field");
        exit();
    }
}

try {
    $id_colaborador = (int)$_POST['id_colaborador'];
    $fecha_registro = $_POST['fecha_registro'];
    $hora_registro = $_POST['hora_registro'];
    $tipo_comida = $_POST['tipo_comida'];
    $id_alimento = (int)$_POST['id_alimento'];
    $cantidad = (float)$_POST['cantidad'];

    // Validar tipo_comida
    $valid_tipos_comida = ['Desayuno', 'Almuerzo', 'Cena', 'Merienda', 'Colación'];
    if (!in_array($tipo_comida, $valid_tipos_comida)) {
        header("Location: ../vistas/cargar_alimentacion.php?error=Tipo de comida inválido");
        exit();
    }

    // Obtener el nombre y grupo alimenticio del alimento
    $sql = "SELECT nombre, grupo_alimenticio FROM alimentos_referencia WHERE id_alimento = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_alimento);
    $stmt->execute();
    $result = $stmt->get_result();
    $alimento_data = $result->fetch_assoc();
    $stmt->close();

    if (!$alimento_data) {
        header("Location: ../vistas/cargar_alimentacion.php?error=Alimento no encontrado");
        exit();
    }

    $alimento = $alimento_data['nombre'];
    $grupo_alimenticio = $alimento_data['grupo_alimenticio'];

    // Validar y asignar valores opcionales
    $calorias = isset($_POST['calorias']) && $_POST['calorias'] !== '' ? (float)$_POST['calorias'] : null;
    $proteinas = isset($_POST['proteinas']) && $_POST['proteinas'] !== '' ? (float)$_POST['proteinas'] : null;
    $carbohidratos = isset($_POST['carbohidratos']) && $_POST['carbohidratos'] !== '' ? (float)$_POST['carbohidratos'] : null;
    $grasas = isset($_POST['grasas']) && $_POST['grasas'] !== '' ? (float)$_POST['grasas'] : null;
    $fibra = isset($_POST['fibra']) && $_POST['fibra'] !== '' ? (float)$_POST['fibra'] : null;
    $azucar = isset($_POST['azucar']) && $_POST['azucar'] !== '' ? (float)$_POST['azucar'] : null;
    $sodio = isset($_POST['sodio']) && $_POST['sodio'] !== '' ? (float)$_POST['sodio'] : null;
    $metodo_preparacion = !empty($_POST['metodo_preparacion']) ? $_POST['metodo_preparacion'] : null;
    $contexto_comida = !empty($_POST['contexto_comida']) ? $_POST['contexto_comida'] : null;
    $sensacion_hambre = !empty($_POST['sensacion_hambre']) && in_array($_POST['sensacion_hambre'], ['Bajo', 'Moderado', 'Alto']) ? $_POST['sensacion_hambre'] : null;
    $sensacion_saciedad = !empty($_POST['sensacion_saciedad']) && in_array($_POST['sensacion_saciedad'], ['Bajo', 'Moderado', 'Alto']) ? $_POST['sensacion_saciedad'] : null;
    $notas = !empty($_POST['notas']) ? $_POST['notas'] : null;

    // Insertar el registro
    $sql = "INSERT INTO alimentacion (
        id_colaborador, fecha_registro, hora_registro, tipo_comida, grupo_alimenticio, alimento, cantidad, calorias, proteinas, carbohidratos, grasas, fibra, azucar, sodio, metodo_preparacion, contexto_comida, sensacion_hambre, sensacion_saciedad, notas
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param(
        "issssssdddddddsssss",
        $id_colaborador, $fecha_registro, $hora_registro, $tipo_comida, $grupo_alimenticio, $alimento, $cantidad, $calorias, $proteinas, $carbohidratos, $grasas, $fibra, $azucar, $sodio, $metodo_preparacion, $contexto_comida, $sensacion_hambre, $sensacion_saciedad, $notas
    );
    $stmt->execute();
    $stmt->close();

    header("Location: ../vistas/cargar_alimentacion.php?success=1");
    exit();
} catch (Exception $e) {
    header("Location: ../vistas/cargar_alimentacion.php?error=Error al guardar el registro: " . urlencode($e->getMessage()));
    exit();
}

$conexion->close();
?>