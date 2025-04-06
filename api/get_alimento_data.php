<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

require_once '../includes/base_datos.php';

$id_alimento = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_alimento <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID de alimento inválido']);
    exit();
}

try {
    $sql = "SELECT * FROM alimentos_referencia WHERE id_alimento = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("i", $id_alimento);
    $stmt->execute();
    $result = $stmt->get_result();
    $alimento = $result->fetch_assoc();
    $stmt->close();

    if ($alimento) {
        echo json_encode($alimento);
    } else {
        http_response_code(404);
        echo json_encode(['error' => 'Alimento no encontrado']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'Error en el servidor: ' . $e->getMessage()]);
}

$conexion->close();
?>