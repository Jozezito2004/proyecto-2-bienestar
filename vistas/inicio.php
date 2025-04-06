<?php
$page_title = "Inicio - Bienestar BUAP";

// Iniciar sesión y autenticación primero
include_once '../includes/base_datos.php';
include_once '../includes/autenticacion.php'; // Esto debe ir antes de cualquier salida
include_once '../controladores/HomeController.php';

// Instanciar el controlador y obtener datos
$controller = new HomeController($conexion);
$data = $controller->mostrarInicio();

// Incluir encabezado y barra de navegación después de la autenticación
include_once '../includes/encabezado.php';
include_once '../includes/barra_navegacion.php';
?>

<div class="container">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Bienvenido a Bienestar BUAP</h1>
            <p class="card-text">Gestiona el bienestar de los colaboradores de la BUAP.</p>
            <div class="section">
                <h2>Opciones Disponibles</h2>
                <div>
                    <?php if ($data['rol'] === 'superusuario' || $data['rol'] === 'cliente'): ?>
                        <a href="ver_colaboradores.php" class="btn">Ver Colaboradores</a>
                        <a href="cargar_pruebas_psicometricas.php" class="btn">Cargar Pruebas Psicométricas</a>
                        <a href="seguimiento_actividad_fisica.php" class="btn">Seguimiento de Actividad Física</a>
                        <a href="cargar_alimentacion.php" class="btn">Cargar Alimentación</a>
                    <?php endif; ?>
                    <?php if ($data['rol'] === 'superusuario'): ?>
                        <a href="administrar_ip.php" class="btn">Administrar IPs Autorizadas</a>
                    <?php endif; ?>
                </div>
            </div>

            <?php if ($data['rol'] === 'superusuario' || $data['rol'] === 'cliente'): ?>
                <div class="section mt-4">
                    <h2>Resumen de Alimentación</h2>
                    <p>Total de registros: <?php echo htmlspecialchars($data['total_registros']); ?></p>
                    <p>Registros de hoy: <?php echo htmlspecialchars($data['registros_hoy']); ?></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$conexion->close();
include_once '../includes/pie_pagina.php'; // Si tienes un pie de página, agrégalo aquí
?>
</body>
</html>
