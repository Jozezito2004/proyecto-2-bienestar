<?php
include '../includes/base_datos.php';

// Obtener el ID del colaborador desde la URL
$id_colaborador = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id_colaborador === 0) {
    die("Error: ID de colaborador no válido.");
}

// Iniciar una transacción para asegurar consistencia
$conexion->begin_transaction();

try {
    // Eliminar datos relacionados en otras tablas
    $sql = "DELETE FROM datos_biometricos WHERE id_colaborador = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_colaborador);
    if (!$stmt->execute()) {
        throw new Exception("Error al eliminar datos biométricos: " . $stmt->error);
    }

    $sql = "DELETE FROM alimentacion WHERE id_colaborador = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_colaborador);
    if (!$stmt->execute()) {
        throw new Exception("Error al eliminar datos de alimentación: " . $stmt->error);
    }

    $sql = "DELETE FROM pruebas_psicometricas WHERE id_colaborador = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_colaborador);
    if (!$stmt->execute()) {
        throw new Exception("Error al eliminar pruebas psicométricas: " . $stmt->error);
    }

    // Eliminar el colaborador
    $sql = "DELETE FROM colaboradores WHERE id_colaborador = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_colaborador);
    if (!$stmt->execute()) {
        throw new Exception("Error al eliminar el colaborador: " . $stmt->error);
    }

    // Confirmar la transacción
    $conexion->commit();

    // Redirigir a la lista de colaboradores con un mensaje de éxito
    header("Location: ../vistas/ver_colaboradores.php?mensaje=Colaborador eliminado con éxito");
    exit;
} catch (Exception $e) {
    // Revertir la transacción en caso de error
    $conexion->rollback();
    die("Error: " . $e->getMessage());
}

// Cerrar la declaración y la conexión
$stmt->close();
$conexion->close();
?>