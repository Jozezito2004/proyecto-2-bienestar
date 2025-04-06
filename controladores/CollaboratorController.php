<?php
class CollaboratorController {
    private $conexion;

    // Constructor para inicializar la conexión a la base de datos
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Método para mostrar el formulario de agregar colaborador
    public function mostrarFormularioAgregar() {
        // Aquí podrías cargar datos adicionales si el formulario los necesita
        // Por ejemplo, una lista de unidades de trabajo o puestos predefinidos
        $data = [
            'unidades_trabajo' => ['Unidad 1', 'Unidad 2', 'Unidad 3'], // Ejemplo
            'puestos' => ['Puesto 1', 'Puesto 2', 'Puesto 3'] // Ejemplo
        ];

        return $data;
    }

    // Método para procesar el formulario de agregar colaborador (opcional, si no usas procesar_agregar_colaborador.php)
    public function procesarAgregarColaborador() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: ../vistas/agregar_colaborador.php?error=Método no permitido");
            exit();
        }

        // Validar datos requeridos
        $required_fields = [
            'numero_identificacion', 'nombre', 'apellido', 'fecha_nacimiento',
            'genero', 'unidad_trabajo', 'puesto', 'anos_servicio',
            'fecha_medicion', 'peso', 'talla'
        ];
        foreach ($required_fields as $field) {
            if (!isset($_POST[$field]) || empty($_POST[$field])) {
                header("Location: ../vistas/agregar_colaborador.php?error=Faltan datos requeridos: $field");
                exit();
            }
        }

        try {
            // Datos personales
            $numero_identificacion = $_POST['numero_identificacion'];
            $nombre = $_POST['nombre'];
            $apellido = $_POST['apellido'];
            $fecha_nacimiento = $_POST['fecha_nacimiento'];
            $genero = $_POST['genero'];
            $unidad_trabajo = $_POST['unidad_trabajo'];
            $puesto = $_POST['puesto'];
            $anos_servicio = (int)$_POST['anos_servicio'];
            $email = !empty($_POST['email']) ? $_POST['email'] : null;

            // Datos biométricos
            $fecha_medicion = $_POST['fecha_medicion'];
            $peso = (float)$_POST['peso'];
            $talla = (float)$_POST['talla'];
            $perimetro_cintura = !empty($_POST['perimetro_cintura']) ? (float)$_POST['perimetro_cintura'] : null;
            $porcentaje_grasa = !empty($_POST['porcentaje_grasa']) ? (float)$_POST['porcentaje_grasa'] : null;
            $masa_muscular = !empty($_POST['masa_muscular']) ? (float)$_POST['masa_muscular'] : null;
            $presion_arterial_sistolica = !empty($_POST['presion_arterial_sistolica']) ? (int)$_POST['presion_arterial_sistolica'] : null;
            $presion_arterial_diastolica = !empty($_POST['presion_arterial_diastolica']) ? (int)$_POST['presion_arterial_diastolica'] : null;
            $frecuencia_cardiaca = !empty($_POST['frecuencia_cardiaca']) ? (int)$_POST['frecuencia_cardiaca'] : null;
            $glucosa_ayuno = !empty($_POST['glucosa_ayuno']) ? (int)$_POST['glucosa_ayuno'] : null;
            $colesterol_total = !empty($_POST['colesterol_total']) ? (int)$_POST['colesterol_total'] : null;
            $trigliceridos = !empty($_POST['trigliceridos']) ? (int)$_POST['trigliceridos'] : null;

            // Iniciar una transacción para asegurar consistencia
            $this->conexion->begin_transaction();

            // Insertar en la tabla colaboradores
            $sql = "INSERT INTO colaboradores (
                numero_identificacion, nombre, apellido, fecha_nacimiento, genero, unidad_trabajo, puesto, anos_servicio, email
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param(
                "sssssssiss",
                $numero_identificacion, $nombre, $apellido, $fecha_nacimiento, $genero, $unidad_trabajo, $puesto, $anos_servicio, $email
            );
            $stmt->execute();
            $id_colaborador = $this->conexion->insert_id; // Obtener el ID del colaborador recién insertado
            $stmt->close();

            // Insertar en la tabla datos_biometricos
            $sql = "INSERT INTO datos_biometricos (
                id_colaborador, fecha_medicion, peso, talla, perimetro_cintura, porcentaje_grasa, masa_muscular,
                presion_arterial_sistolica, presion_arterial_diastolica, frecuencia_cardiaca, glucosa_ayuno, colesterol_total, trigliceridos
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param(
                "isddddddddddd",
                $id_colaborador, $fecha_medicion, $peso, $talla, $perimetro_cintura, $porcentaje_grasa, $masa_muscular,
                $presion_arterial_sistolica, $presion_arterial_diastolica, $frecuencia_cardiaca, $glucosa_ayuno, $colesterol_total, $trigliceridos
            );
            $stmt->execute();
            $stmt->close();

            // Confirmar la transacción
            $this->conexion->commit();

            header("Location: ../vistas/agregar_colaborador.php?success=Colaborador agregado exitosamente");
            exit();
        } catch (Exception $e) {
            // Revertir la transacción en caso de error
            $this->conexion->rollback();
            header("Location: ../vistas/agregar_colaborador.php?error=Error al agregar el colaborador: " . urlencode($e->getMessage()));
            exit();
        }
    }
}

// Manejo de rutas/acciones (opcional, si decides usar el controlador para procesar el formulario)
if (isset($_GET['action'])) {
    $controller = new CollaboratorController($conexion);
    switch ($_GET['action']) {
        case 'procesarAgregarColaborador':
            $controller->procesarAgregarColaborador();
            break;
        default:
            $controller->mostrarFormularioAgregar();
            break;
    }
}
?>