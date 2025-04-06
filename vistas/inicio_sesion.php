<?php
$page_title = "Iniciar Sesión - Bienestar BUAP";
include_once '../includes/encabezado.php';
require_once '../controladores/AuthController.php';

// Iniciar la conexión y el controlador
include_once '../includes/base_datos.php';
$controller = new AuthController($conexion);
$resultado = $controller->login();
?>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Iniciar Sesión</h1>
            <p class="card-text">Ingresa tus credenciales para acceder al sistema.</p>
            <?php if ($resultado && isset($resultado['error'])): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($resultado['error']); ?></div>
            <?php endif; ?>
            <div class="import-container">
                <form method="POST" action="inicio_sesion.php">
                    <label for="usuario">Usuario:</label>
                    <input type="text" id="usuario" name="usuario" required placeholder="Ej. cliente" maxlength="50" pattern="[a-zA-Z0-9_]+">
                    <label for="contrasena">Contraseña:</label>
                    <input type="password" id="contrasena" name="contrasena" required placeholder="Tu contraseña">
                    <button type="submit" name="iniciar_sesion" class="btn">Iniciar Sesión</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php $conexion->close(); ?>
</body>
</html>