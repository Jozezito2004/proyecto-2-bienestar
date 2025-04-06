<?php
require_once '../includes/base_datos.php';

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['usuario_id']) || !in_array($_SESSION['rol'], ['superusuario', 'cliente'])) {
    header("Location: ../vistas/inicio_sesion.php");
    exit();
}

$borrar_no_listados = isset($_POST['borrar_no_listados']);
$sobrescribir = isset($_POST['sobrescribir']);
$no_actualizar_historial = isset($_POST['no_actualizar_historial']);

if (!empty($_POST['datos_pegados'])) {
    $datos = explode("\n", trim($_POST['datos_pegados']));
    foreach ($datos as $linea) {
        if (strpos($linea, '##COLABORADOR##') === 0) {
            $campos = explode("\t", trim(substr($linea, 14)));
            if (count($campos) >= 3) {
                $numero_identificacion = $campos[0];
                $nombre = $campos[1];
                $apellido = $campos[2];

                $sql = $sobrescribir ?
                    "REPLACE INTO colaboradores (numero_identificacion, nombre, apellido) VALUES (?, ?, ?)" :
                    "INSERT IGNORE INTO colaboradores (numero_identificacion, nombre, apellido) VALUES (?, ?, ?)";
                $stmt = $conexion->prepare($sql);
                $stmt->bind_param("sss", $numero_identificacion, $nombre, $apellido);
                $stmt->execute();
                $stmt->close();
            }
        }
    }
} elseif (!empty($_FILES['archivo_csv']['tmp_name'])) {
    $csv = array_map('str_getcsv', file($_FILES['archivo_csv']['tmp_name']));
    foreach ($csv as $row) {
        if (count($row) >= 3) {
            $numero_identificacion = $row[0];
            $nombre = $row[1];
            $apellido = $row[2];

            $sql = $sobrescribir ?
                "REPLACE INTO colaboradores (numero_identificacion, nombre, apellido) VALUES (?, ?, ?)" :
                "INSERT IGNORE INTO colaboradores (numero_identificacion, nombre, apellido) VALUES (?, ?, ?)";
            $stmt = $conexion->prepare($sql);
            $stmt->bind_param("sss", $numero_identificacion, $nombre, $apellido);
            $stmt->execute();
            $stmt->close();
        }
    }
}

if ($borrar_no_listados) {
    // Lógica para borrar registros no listados (requiere más contexto sobre cómo identificarlos)
}

header("Location: ../vistas/cargar_datos.php?success=1");
exit();
?>