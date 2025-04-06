<?php
$page_title = "Cargar Datos de Alimentación - Bienestar BUAP";
include_once '../includes/encabezado.php';
include_once '../includes/barra_navegacion.php';
include_once '../includes/base_datos.php';
include_once '../includes/autenticacion.php';
require_once '../controladores/NutritionController.php';

// Las variables $alimentos y $colaboradores ya están definidas por NutritionController.php
$success = isset($_GET['success']) && $_GET['success'] == 1;
?>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Cargar Datos de Alimentación</h1>
            <p class="card-text">Registra los datos de alimentación de un colaborador.</p>

            <?php if ($success): ?>
                <div class="alert alert-success">
                    Registro de alimentación guardado exitosamente.
                </div>
            <?php endif; ?>

            <form method="POST" action="../controladores/procesar_alimentacion.php" class="import-container" id="alimentacionForm">
                <div class="form-group">
                    <label for="id_colaborador">Colaborador:</label>
                    <select id="id_colaborador" name="id_colaborador" required>
                        <option value="">Selecciona un colaborador</option>
                        <?php foreach ($colaboradores as $colaborador): ?>
                            <option value="<?php echo $colaborador['id_colaborador']; ?>">
                                <?php echo htmlspecialchars($colaborador['apellido'] . " " . $colaborador['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="fecha_registro">Fecha de Registro:</label>
                    <input type="date" id="fecha_registro" name="fecha_registro" required value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="form-group">
                    <label for="hora_registro">Hora de Registro:</label>
                    <input type="time" id="hora_registro" name="hora_registro" required value="<?php echo date('H:i'); ?>">
                </div>

                <div class="form-group">
                    <label for="tipo_comida">Tipo de Comida:</label>
                    <select id="tipo_comida" name="tipo_comida" required>
                        <option value="Desayuno">Desayuno</option>
                        <option value="Almuerzo">Almuerzo</option>
                        <option value="Cena">Cena</option>
                        <option value="Merienda">Merienda</option>
                        <option value="Colación">Colación</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="alimento">Alimento:</label>
                    <select id="alimento" name="id_alimento" required>
                        <option value="">Selecciona un alimento</option>
                        <?php foreach ($alimentos as $alimento): ?>
                            <option value="<?php echo $alimento['id_alimento']; ?>">
                                <?php echo htmlspecialchars($alimento['nombre']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="grupo_alimenticio">Grupo Alimenticio:</label>
                    <input type="text" id="grupo_alimenticio" name="grupo_alimenticio" readonly>
                </div>

                <div class="form-group">
                    <label for="cantidad">Cantidad (g/mL):</label>
                    <input type="number" id="cantidad" name="cantidad" step="0.01" min="0" required>
                </div>

                <div class="form-group">
                    <label for="calorias_base">Calorías por 100g:</label>
                    <input type="number" id="calorias_base" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="calorias">Calorías Totales:</label>
                    <input type="number" id="calorias" name="calorias" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="proteinas_base">Proteínas por 100g:</label>
                    <input type="number" id="proteinas_base" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="proteinas">Proteínas Totales:</label>
                    <input type="number" id="proteinas" name="proteinas" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="carbohidratos_base">Carbohidratos por 100g:</label>
                    <input type="number" id="carbohidratos_base" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="carbohidratos">Carbohidratos Totales:</label>
                    <input type="number" id="carbohidratos" name="carbohidratos" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="grasas_base">Grasas por 100g:</label>
                    <input type="number" id="grasas_base" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="grasas">Grasas Totales:</label>
                    <input type="number" id="grasas" name="grasas" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="fibra_base">Fibra por 100g:</label>
                    <input type="number" id="fibra_base" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="fibra">Fibra Total:</label>
                    <input type="number" id="fibra" name="fibra" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="azucar_base">Azúcar por 100g:</label>
                    <input type="number" id="azucar_base" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="azucar">Azúcar Total:</label>
                    <input type="number" id="azucar" name="azucar" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="sodio_base">Sodio por 100g:</label>
                    <input type="number" id="sodio_base" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="sodio">Sodio Total:</label>
                    <input type="number" id="sodio" name="sodio" step="0.01" readonly>
                </div>

                <div class="form-group">
                    <label for="metodo_preparacion">Método de Preparación:</label>
                    <input type="text" id="metodo_preparacion" name="metodo_preparacion">
                </div>

                <div class="form-group">
                    <label for="contexto_comida">Contexto de la Comida:</label>
                    <input type="text" id="contexto_comida" name="contexto_comida">
                </div>

                <div class="form-group">
                    <label for="sensacion_hambre">Sensación de Hambre:</label>
                    <select id="sensacion_hambre" name="sensacion_hambre">
                        <option value="">Selecciona</option>
                        <option value="Bajo">Bajo</option>
                        <option value="Moderado">Moderado</option>
                        <option value="Alto">Alto</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="sensacion_saciedad">Sensación de Saciedad:</label>
                    <select id="sensacion_saciedad" name="sensacion_saciedad">
                        <option value="">Selecciona</option>
                        <option value="Bajo">Bajo</option>
                        <option value="Moderado">Moderado</option>
                        <option value="Alto">Alto</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="notas">Notas:</label>
                    <textarea id="notas" name="notas"></textarea>
                </div>

                <button type="submit" class="btn">Guardar Registro</button>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('alimento').addEventListener('change', function() {
    const alimentoId = this.value;
    if (!alimentoId) {
        resetNutritionalFields();
        return;
    }

    fetch(`../api/get_alimento_data.php?id=${alimentoId}`)
        .then(response => {
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                alert(data.error);
                resetNutritionalFields();
                return;
            }

            document.getElementById('grupo_alimenticio').value = data.grupo_alimenticio || '';
            document.getElementById('calorias_base').value = data.calorias_por_100g || 0;
            document.getElementById('proteinas_base').value = data.proteinas_por_100g || 0;
            document.getElementById('carbohidratos_base').value = data.carbohidratos_por_100g || 0;
            document.getElementById('grasas_base').value = data.grasas_por_100g || 0;
            document.getElementById('fibra_base').value = data.fibra_por_100g || 0;
            document.getElementById('azucar_base').value = data.azucar_por_100g || 0;
            document.getElementById('sodio_base').value = data.sodio_por_100g || 0;

            updateNutritionalTotals();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al obtener los datos del alimento: ' + error.message);
            resetNutritionalFields();
        });
});

document.getElementById('cantidad').addEventListener('input', function() {
    updateNutritionalTotals();
});

function updateNutritionalTotals() {
    const cantidad = parseFloat(document.getElementById('cantidad').value);
    if (isNaN(cantidad) || cantidad <= 0) {
        resetNutritionalTotals();
        return;
    }

    const fields = [
        'calorias', 'proteinas', 'carbohidratos', 'grasas', 'fibra', 'azucar', 'sodio'
    ];

    fields.forEach(field => {
        const baseValue = parseFloat(document.getElementById(`${field}_base`).value);
        if (!isNaN(baseValue)) {
            document.getElementById(field).value = (baseValue * cantidad / 100).toFixed(2);
        } else {
            document.getElementById(field).value = '';
        }
    });
}

function resetNutritionalFields() {
    document.getElementById('grupo_alimenticio').value = '';
    document.getElementById('calorias_base').value = '';
    document.getElementById('proteinas_base').value = '';
    document.getElementById('carbohidratos_base').value = '';
    document.getElementById('grasas_base').value = '';
    document.getElementById('fibra_base').value = '';
    document.getElementById('azucar_base').value = '';
    document.getElementById('sodio_base').value = '';
    resetNutritionalTotals();
}

function resetNutritionalTotals() {
    document.getElementById('calorias').value = '';
    document.getElementById('proteinas').value = '';
    document.getElementById('carbohidratos').value = '';
    document.getElementById('grasas').value = '';
    document.getElementById('fibra').value = '';
    document.getElementById('azucar').value = '';
    document.getElementById('sodio').value = '';
}

document.getElementById('alimentacionForm').addEventListener('submit', function(event) {
    const cantidad = parseFloat(document.getElementById('cantidad').value);
    if (isNaN(cantidad) || cantidad <= 0) {
        event.preventDefault();
        alert('Por favor, ingresa una cantidad válida mayor que 0.');
    }
});
</script>

<style>
    .form-group {
        margin-bottom: 15px;
    }
    .form-group label {
        display: block;
        font-weight: bold;
    }
    .form-group input, .form-group select, .form-group textarea {
        width: 100%;
        max-width: 300px;
        padding: 5px;
    }
    .form-group textarea {
        height: 100px;
    }
    .alert-success {
        background-color: #d4edda;
        color: #155724;
        padding: 10px;
        border-radius: 5px;
        margin-bottom: 15px;
    }
</style>

</body>
</html>
<?php $conexion->close(); ?>