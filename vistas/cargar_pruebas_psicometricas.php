<?php
$page_title = "Cargar Pruebas Psicométricas - Bienestar BUAP";
include_once '../includes/encabezado.php';
include_once '../includes/barra_navegacion.php';
include_once '../includes/base_datos.php';
include_once '../includes/autenticacion.php';
require_once '../controladores/PsychometricController.php';

$controller = new PsychometricController($conexion);
$data = $controller->mostrarCargarPruebas();
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Cargar Pruebas Psicométricas</h1>
            <p class="card-text">Selecciona un colaborador y registra sus niveles o realiza tests psicométricos.</p>

            <div class="import-container">
                <form method="POST" action="cargar_pruebas_psicometricas.php">
                    <label for="id_colaborador">Colaborador:</label>
                    <select id="id_colaborador" name="id_colaborador" onchange="this.form.submit()" required>
                        <option value="">Selecciona un colaborador</option>
                        <?php foreach ($data['colaboradores'] as $colaborador): ?>
                            <option value="<?php echo $colaborador['id_colaborador']; ?>" <?php echo $data['selected_colaborador'] == $colaborador['id_colaborador'] ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($colaborador['apellido'] . " " . $colaborador['nombre'] . " (" . $colaborador['numero_identificacion'] . ")"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>

                <?php if ($data['selected_colaborador'] && $data['test_results']): ?>
                    <h3>Resultados Registrados</h3>
                    <p>Fecha de evaluación: <?php echo htmlspecialchars($data['test_results']['fecha_evaluacion']); ?></p>
                    <?php if ($data['test_results']['estres'] !== null): ?><p>Estrés: <?php echo htmlspecialchars($data['test_results']['estres']); ?>/10</p><?php endif; ?>
                    <?php if ($data['test_results']['depresion'] !== null): ?><p>Depresión: <?php echo htmlspecialchars($data['test_results']['depresion']); ?>/10</p><?php endif; ?>
                    <?php if ($data['test_results']['burnout'] !== null): ?><p>Burnout: <?php echo htmlspecialchars($data['test_results']['burnout']); ?>/10</p><?php endif; ?>
                    <?php if ($data['test_results']['ansiedad'] !== null): ?><p>Ansiedad: <?php echo htmlspecialchars($data['test_results']['ansiedad']); ?>/10</p><?php endif; ?>
                <?php endif; ?>
            </div>

            <!-- Test de Estrés Laboral -->
            <div class="import-container">
                <h2>Realizar Test de Estrés Laboral (OMS)</h2>
                <form method="GET" action="test_estres.php" class="import-container">
                    <label for="id_colaborador_estres">Colaborador:</label>
                    <select id="id_colaborador_estres" name="id" required>
                        <option value="">Selecciona un colaborador</option>
                        <?php foreach ($data['colaboradores'] as $colaborador): ?>
                            <option value="<?php echo $colaborador['id_colaborador']; ?>">
                                <?php echo htmlspecialchars($colaborador['apellido'] . " " . $colaborador['nombre'] . " (" . $colaborador['numero_identificacion'] . ")"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn">Realizar Test de Estrés</button>
                </form>
            </div>

            <!-- Test de Burnout -->
            <div class="import-container">
                <h2>Realizar Test de Burnout de Maslach</h2>
                <form method="GET" action="test_burnout.php" class="import-container">
                    <label for="id_colaborador_burnout">Colaborador:</label>
                    <select id="id_colaborador_burnout" name="id" required>
                        <option value="">Selecciona un colaborador</option>
                        <?php foreach ($data['colaboradores'] as $colaborador): ?>
                            <option value="<?php echo $colaborador['id_colaborador']; ?>">
                                <?php echo htmlspecialchars($colaborador['apellido'] . " " . $colaborador['nombre'] . " (" . $colaborador['numero_identificacion'] . ")"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn">Realizar Test de Burnout</button>
                </form>
            </div>

            <!-- Test de Ansiedad Laboral -->
            <div class="import-container">
                <h2>Realizar Test de Ansiedad Laboral</h2>
                <form method="GET" action="test_ansiedad.php" class="import-container">
                    <label for="id_colaborador_ansiedad">Colaborador:</label>
                    <select id="id_colaborador_ansiedad" name="id" required>
                        <option value="">Selecciona un colaborador</option>
                        <?php foreach ($data['colaboradores'] as $colaborador): ?>
                            <option value="<?php echo $colaborador['id_colaborador']; ?>">
                                <?php echo htmlspecialchars($colaborador['apellido'] . " " . $colaborador['nombre'] . " (" . $colaborador['numero_identificacion'] . ")"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn">Realizar Test de Ansiedad</button>
                </form>
            </div>

            <!-- Test de Depresión (PHQ-9) -->
            <div class="import-container">
                <h2>Realizar Test de Depresión (PHQ-9)</h2>
                <form method="GET" action="test_depresion.php" class="import-container">
                    <label for="id_colaborador_depresion">Colaborador:</label>
                    <select id="id_colaborador_depresion" name="id" required>
                        <option value="">Selecciona un colaborador</option>
                        <?php foreach ($data['colaboradores'] as $colaborador): ?>
                            <option value="<?php echo $colaborador['id_colaborador']; ?>">
                                <?php echo htmlspecialchars($colaborador['apellido'] . " " . $colaborador['nombre'] . " (" . $colaborador['numero_identificacion'] . ")"); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <button type="submit" class="btn">Realizar Test de Depresión</button>
                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>
<?php $conexion->close(); ?>