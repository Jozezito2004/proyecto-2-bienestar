<?php
$page_title = "Agregar Colaborador - Bienestar BUAP";
include '../includes/encabezado.php';
include '../includes/barra_navegacion.php';
include '../includes/base_datos.php';
include '../includes/autenticacion.php';

// Lista de clases disponibles
$clases_disponibles = [
    "Cardio Box Alto Impacto", "Cardio Box Bajo Impacto", "Baile Fitness Alto Impacto", "Baile Fitness Bajo Impacto",
    "Ritmos Latinos", "Bachata", "Entrenamiento Funcional", "Box", "Baloncesto", "Aprendiendo a Correr",
    "Acondicionamiento Físico Básico", "Acondicionamiento Físico Intermedio", "Acondicionamiento Físico Avanzado",
    "Yoga", "Natación Principiantes", "Natación Intermedio", "Natación Avanzado", "Aquagym",
    "Alimentación para la Salud", "Nutrición para la Salud",
    "Inteligencia Emocional para el Manejo Efectivo de las Relaciones Humanas",
    "Resiliencia y Bienestar", "Técnicas de Meditación", "Manejo de Estrés", "Viaje al Centro de las Emociones"
];
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Agregar Nuevo Colaborador</h1>
            <form action="../controladores/procesar_agregar_colaborador.php" method="POST">
                <!-- Datos Personales -->
                <h3>Datos Personales</h3>
                <div class="mb-3">
                    <label for="nombre" class="form-label">Nombre:</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="mb-3">
                    <label for="apellido" class="form-label">Apellido:</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>
                <div class="mb-3">
                    <label for="genero" class="form-label">Género:</label>
                    <select class="form-select" id="genero" name="genero" required>
                        <option value="">Seleccione una opción</option>
                        <option value="M">Masculino</option>
                        <option value="F">Femenino</option>
                        <option value="O">Otro</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento:</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" required>
                </div>
                <div class="mb-3">
                    <label for="telefono" class="form-label">Teléfono (opcional):</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="Ejemplo: 555-123-4567">
                </div>
                <div class="mb-3">
                    <label for="departamento" class="form-label">Departamento (opcional):</label>
                    <input type="text" class="form-control" id="departamento" name="departamento" placeholder="Ejemplo: Recursos Humanos">
                </div>
                <div class="mb-3">
                    <label for="enfermedades_previas" class="form-label">Enfermedades Previas (opcional):</label>
                    <textarea class="form-control" id="enfermedades_previas" name="enfermedades_previas" rows="3" placeholder="Ejemplo: Hipertensión, Diabetes tipo 2"></textarea>
                </div>

                <!-- Datos Biométricos -->
                <h3>Datos Biométricos (opcional)</h3>
                <div class="mb-3">
                    <label for="peso" class="form-label">Peso (kg):</label>
                    <input type="number" step="0.1" class="form-control" id="peso" name="peso" placeholder="Ejemplo: 70.5">
                </div>
                <div class="mb-3">
                    <label for="talla" class="form-label">Talla (cm):</label>
                    <input type="number" step="0.1" class="form-control" id="talla" name="talla" placeholder="Ejemplo: 170">
                </div>
                <div class="mb-3">
                    <label for="perimetro_cintura" class="form-label">Perímetro de Cintura (cm):</label>
                    <input type="number" step="0.1" class="form-control" id="perimetro_cintura" name="perimetro_cintura" placeholder="Ejemplo: 80">
                </div>
                <div class="mb-3">
                    <label for="porcentaje_grasa" class="form-label">Porcentaje de Grasa (%):</label>
                    <input type="number" step="0.1" class="form-control" id="porcentaje_grasa" name="porcentaje_grasa" placeholder="Ejemplo: 25">
                </div>
                <div class="mb-3">
                    <label for="masa_muscular" class="form-label">Masa Muscular (kg):</label>
                    <input type="number" step="0.1" class="form-control" id="masa_muscular" name="masa_muscular" placeholder="Ejemplo: 30">
                </div>
                <div class="mb-3">
                    <label for="presion_arterial_sistolica" class="form-label">Presión Arterial Sistólica (mmHg):</label>
                    <input type="number" class="form-control" id="presion_arterial_sistolica" name="presion_arterial_sistolica" placeholder="Ejemplo: 120">
                </div>
                <div class="mb-3">
                    <label for="presion_arterial_diastolica" class="form-label">Presión Arterial Diastólica (mmHg):</label>
                    <input type="number" class="form-control" id="presion_arterial_diastolica" name="presion_arterial_diastolica" placeholder="Ejemplo: 80">
                </div>
                <div class="mb-3">
                    <label for="frecuencia_cardiaca" class="form-label">Frecuencia Cardíaca (lpm):</label>
                    <input type="number" class="form-control" id="frecuencia_cardiaca" name="frecuencia_cardiaca" placeholder="Ejemplo: 70">
                </div>
                <div class="mb-3">
                    <label for="glucosa_ayuno" class="form-label">Glucosa en Ayuno (mg/dL):</label>
                    <input type="number" step="0.1" class="form-control" id="glucosa_ayuno" name="glucosa_ayuno" placeholder="Ejemplo: 90">
                </div>
                <div class="mb-3">
                    <label for="colesterol_total" class="form-label">Colesterol Total (mg/dL):</label>
                    <input type="number" step="0.1" class="form-control" id="colesterol_total" name="colesterol_total" placeholder="Ejemplo: 180">
                </div>
                <div class="mb-3">
                    <label for="trigliceridos" class="form-label">Triglicéridos (mg/dL):</label>
                    <input type="number" step="0.1" class="form-control" id="trigliceridos" name="trigliceridos" placeholder="Ejemplo: 120">
                </div>

                <!-- Clases Inscritas -->
                <h3>Clases Inscritas (Mes Actual)</h3>
                <div class="mb-3">
                    <label for="clases" class="form-label">Seleccione las clases a las que se inscribe:</label>
                    <select class="form-select" id="clases" name="clases[]" multiple size="5">
                        <?php foreach ($clases_disponibles as $clase): ?>
                            <option value="<?php echo htmlspecialchars($clase); ?>"><?php echo htmlspecialchars($clase); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <small class="form-text text-muted">Mantén presionada la tecla Ctrl (o Cmd en Mac) para seleccionar múltiples clases.</small>
                </div>

                <button type="submit" class="btn btn-primary">Agregar Colaborador</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>

<?php
$conexion->close();
?>