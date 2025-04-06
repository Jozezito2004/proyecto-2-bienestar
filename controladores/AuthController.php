<?php
require_once '../includes/base_datos.php';

class AuthController {
    private $conexion;

    // Constructor para inyectar la conexión a la base de datos
    public function __construct($conexion) {
        $this->conexion = $conexion;
    }

    // Método para manejar el inicio de sesión
    public function login() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['iniciar_sesion'])) {
            $usuario = trim($_POST['usuario'] ?? '');
            $contrasena = trim($_POST['contrasena'] ?? '');

            // Validaciones
            if (empty($usuario) || empty($contrasena)) {
                return ['error' => 'Por favor, completa todos los campos.'];
            } elseif (strlen($usuario) > 50) {
                return ['error' => 'El nombre de usuario no puede exceder los 50 caracteres.'];
            } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $usuario)) {
                return ['error' => 'El nombre de usuario solo puede contener letras, números y guiones bajos.'];
            }

            // Buscar el usuario en la base de datos
            $sql = "SELECT * FROM usuarios WHERE usuario = ?";
            $stmt = $this->conexion->prepare($sql);
            $stmt->bind_param("s", $usuario);
            $stmt->execute();
            $resultado = $stmt->get_result();
            $usuario_db = $resultado->fetch_assoc();
            $stmt->close();

            if ($usuario_db && password_verify($contrasena, $usuario_db['contrasena'])) {
                // Regenerar el ID de sesión por seguridad
                session_regenerate_id(true);

                // Guardar datos en la sesión
                $_SESSION['usuario_id'] = $usuario_db['id'];
                $_SESSION['usuario'] = $usuario_db['usuario'];
                $_SESSION['rol'] = $usuario_db['rol'];
                $_SESSION['ip'] = $_SERVER['REMOTE_ADDR'];
                $_SESSION['user_agent'] = $_SERVER['HTTP_USER_AGENT'];

                // Redirigir a inicio.php
                header("Location: ../vistas/inicio.php");
                exit();
            } else {
                return ['error' => 'Usuario o contraseña incorrectos.'];
            }
        }
        // Si no es POST, simplemente retorna null (mostrar el formulario)
        return null;
    }
}

// Instanciar el controlador y manejar la solicitud
$controller = new AuthController($conexion);
$resultado = $controller->login();
?>