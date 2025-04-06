<?php
include '../includes/base_datos.php';

// Obtener datos del formulario
$nombre = isset($_POST['nombre']) ? trim($_POST['nombre']) : '';
$apellido = isset($_POST['apellido']) ? trim($_POST['apellido']) : '';
$email = isset($_POST['email']) ? trim($_POST['email']) : '';
$genero = isset($_POST['genero']) ? trim($_POST['genero']) : '';
$fecha_nacimiento = isset($_POST['fecha_nacimiento']) ? trim($_POST['fecha_nacimiento']) : '';
$telefono = isset($_POST['telefono']) ? trim($_POST['telefono']) : null;
$departamento = isset($_POST['departamento']) ? trim($_POST['departamento']) : null;
$enfermedades_previas = isset($_POST['enfermedades_previas']) ? trim($_POST['enfermedades_previas']) : null;

// Datos biométricos
$peso = isset($_POST['peso']) && $_POST['peso'] !== '' ? floatval($_POST['peso']) : null;
$talla = isset($_POST['talla']) && $_POST['talla'] !== '' ? floatval($_POST['talla']) : null;
$perimetro_cintura = isset($_POST['perimetro_cintura']) && $_POST['perimetro_cintura'] !== '' ? floatval($_POST['perimetro_cintura']) : null;
$porcentaje_grasa = isset($_POST['porcentaje_grasa']) && $_POST['porcentaje_grasa'] !== '' ? floatval($_POST['porcentaje_grasa']) : null;
$masa_muscular = isset($_POST['masa_muscular']) && $_POST['masa_muscular'] !== '' ? floatval($_POST['masa_muscular']) : null;
$presion_arterial_sistolica = isset($_POST['presion_arterial_sistolica']) && $_POST['presion_arterial_sistolica'] !== '' ? intval($_POST['presion_arterial_sistolica']) : null;
$presion_arterial_diastolica = isset($_POST['presion_arterial_diastolica']) && $_POST['presion_arterial_diastolica'] !== '' ? intval($_POST['presion_arterial_diastolica']) : null;
$frecuencia_cardiaca = isset($_POST['frecuencia_cardiaca']) && $_POST['frecuencia_cardiaca'] !== '' ? intval($_POST['frecuencia_cardiaca']) : null;
$glucosa_ayuno = isset($_POST['glucosa_ayuno']) && $_POST['glucosa_ayuno'] !== '' ? floatval($_POST['glucosa_ayuno']) : null;
$colesterol_total = isset($_POST['colesterol_total']) && $_POST['colesterol_total'] !== '' ? floatval($_POST['colesterol_total']) : null;
$trigliceridos = isset($_POST['trigliceridos']) && $_POST['trigliceridos'] !== '' ? floatval($_POST['trigliceridos']) : null;

// Clases inscritas
$clases = isset($_POST['clases']) ? $_POST['clases'] : [];
$mes_actual = date('n'); // Mes actual (1-12)
$anio_actual = date('Y'); // Año actual

// Validar datos obligatorios
if (empty($nombre) || empty($apellido) || empty($email) || empty($genero) || empty($fecha_nacimiento)) {
    header("Location: ../vistas/agregar_colaborador.php?error=Por favor, completa todos los campos obligatorios.");
    exit;
}

// Insertar el colaborador
$sql = "INSERT INTO colaboradores (nombre, apellido, email, genero, fecha_nacimiento, telefono, departamento, enfermedades_previas) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("ssssssss", $nombre, $apellido, $email, $genero, $fecha_nacimiento, $telefono, $departamento, $enfermedades_previas);
$stmt->execute();
$id_colaborador = $stmt->insert_id;
$stmt->close();

// Insertar datos biométricos si existen
if ($peso || $talla || $perimetro_cintura || $porcentaje_grasa || $masa_muscular || $presion_arterial_sistolica || $presion_arterial_diastolica || $frecuencia_cardiaca || $glucosa_ayuno || $colesterol_total || $trigliceridos) {
    $sql = "INSERT INTO datos_biometricos (id_colaborador, peso, talla, perimetro_cintura, porcentaje_grasa, masa_muscular, presion_arterial_sistolica, presion_arterial_diastolica, frecuencia_cardiaca, glucosa_ayuno, colesterol_total, trigliceridos, fecha_medicion) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("idddddddddddd", $id_colaborador, $peso, $talla, $perimetro_cintura, $porcentaje_grasa, $masa_muscular, $presion_arterial_sistolica, $presion_arterial_diastolica, $frecuencia_cardiaca, $glucosa_ayuno, $colesterol_total, $trigliceridos);
    $stmt->execute();
    $stmt->close();
}

// Insertar clases inscritas
if (!empty($clases)) {
    $sql = "INSERT INTO inscripciones_clases (id_colaborador, clase, mes, anio) VALUES (?, ?, ?, ?)";
    $stmt = $conexion->prepare($sql);
    foreach ($clases as $clase) {
        $stmt->bind_param("isii", $id_colaborador, $clase, $mes_actual, $anio_actual);
        $stmt->execute();
    }
    $stmt->close();
}

// Redirigir con mensaje de éxito
header("Location: ../vistas/ver_colaboradores.php?mensaje=Colaborador agregado exitosamente");
exit;

$conexion->close();
?>