<?php
$page_title = "Ver Colaboradores - Bienestar BUAP";
include '../includes/encabezado.php';
include '../includes/barra_navegacion.php';
include '../includes/base_datos.php';
include '../includes/autenticacion.php';

// Mostrar mensaje de éxito o error si existe
$mensaje = isset($_GET['mensaje']) ? htmlspecialchars($_GET['mensaje']) : '';

$sql = "SELECT id_colaborador, nombre, apellido, email, genero, fecha_nacimiento, enfermedades_previas, telefono, departamento 
        FROM colaboradores ORDER BY apellido, nombre";
$resultado = $conexion->query($sql);
?>

<div class="container-fluid mt-4 full-height">
    <div class="card h-100 shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Lista de Colaboradores</h1>
            <?php if ($mensaje): ?>
                <div class="alert alert-success" role="alert">
                    <?php echo $mensaje; ?>
                </div>
            <?php endif; ?>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Apellido</th>
                            <th>Email</th>
                            <th>Género</th>
                            <th>Fecha de Nacimiento</th>
                            <th>Teléfono</th>
                            <th>Departamento</th>
                            <th>Enfermedades Previas</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($fila = $resultado->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($fila['nombre'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila['apellido'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila['email'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila['genero'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila['fecha_nacimiento'] ?? ''); ?></td>
                                <td><?php echo htmlspecialchars($fila['telefono'] ?? 'No registrado'); ?></td>
                                <td><?php echo htmlspecialchars($fila['departamento'] ?? 'No registrado'); ?></td>
                                <td><?php echo htmlspecialchars($fila['enfermedades_previas'] ?? 'Ninguna'); ?></td>
                                <td>
                                    <a href="editar_colaborador.php?id=<?php echo $fila['id_colaborador']; ?>" class="btn btn-primary btn-sm">Editar</a>
                                    <a href="recomendaciones_ia.php?id=<?php echo $fila['id_colaborador']; ?>" class="btn btn-info btn-sm">Ver Recomendaciones</a>
                                    <a href="historial_biometrico.php?id=<?php echo $fila['id_colaborador']; ?>" class="btn btn-success btn-sm">Ver Historial</a>
                                    <a href="../controladores/procesar_eliminar_colaborador.php?id=<?php echo $fila['id_colaborador']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar a este colaborador? Esta acción no se puede deshacer.');">Eliminar</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<style>
    .container-fluid {
        padding: 0 15px;
        height: calc(100vh - 100px);
        margin-top: 20px;
    }
    .card {
        height: 100%;
        margin-bottom: 0;
    }
    .card-body {
        padding: 20px;
        display: flex;
        flex-direction: column;
    }
    .table-responsive {
        flex: 1;
        overflow-x: auto;
        overflow-y: auto;
    }
    .table {
        width: 100%;
        margin-bottom: 0;
    }
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
        white-space: nowrap;
    }
    .table th {
        background-color: #003087;
        color: white;
        position: sticky;
        top: 0;
        z-index: 1;
    }
    .table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }
    .btn-sm {
        margin: 0 5px;
    }
    .alert {
        margin-bottom: 20px;
    }
    html, body {
        height: 100%;
        margin: 0;
        padding: 0;
        overflow: hidden;
    }
</style>

</body>
</html>

<?php
$conexion->close();
?>