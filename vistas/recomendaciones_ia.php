<?php
$page_title = "Recomendaciones Personalizadas - Bienestar BUAP";
include '../includes/encabezado.php';
include '../includes/barra_navegacion.php';
include '../includes/base_datos.php';
include '../includes/config.php';

// Obtener el ID del colaborador desde la URL
$id_colaborador = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Si no se proporciona un ID, mostrar un formulario para seleccionar un colaborador
if ($id_colaborador === 0) {
    $sql = "SELECT id_colaborador, nombre, apellido FROM colaboradores";
    $resultado = $conexion->query($sql);
?>
    <div class="container mt-4">
        <div class="card shadow-sm">
            <div class="card-body">
                <h1 class="card-title">Seleccionar Colaborador</h1>
                <form action="recomendaciones_ia.php" method="GET">
                    <label for="id" class="form-label">Colaborador:</label>
                    <select name="id" id="id" class="form-select" required>
                        <option value="">Seleccione un colaborador</option>
                        <?php while ($fila = $resultado->fetch_assoc()) { ?>
                            <option value="<?php echo $fila['id_colaborador']; ?>">
                                <?php echo htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']); ?>
                            </option>
                        <?php } ?>
                    </select>
                    <button type="submit" class="btn btn-primary mt-3">Ver Recomendaciones</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
<?php
    $conexion->close();
    exit;
}

// Consulta para obtener los datos del colaborador
$sql = "
    SELECT 
        c.id_colaborador, c.nombre, c.apellido, c.genero, c.fecha_nacimiento,
        TIMESTAMPDIFF(YEAR, c.fecha_nacimiento, CURDATE()) AS edad,
        c.enfermedades_previas,
        db.peso, db.talla, db.perimetro_cintura, db.porcentaje_grasa, db.masa_muscular,
        db.presion_arterial_sistolica, db.presion_arterial_diastolica, db.frecuencia_cardiaca,
        db.glucosa_ayuno, db.colesterol_total, db.trigliceridos,
        p.estres, p.depresion, p.burnout
    FROM colaboradores c
    LEFT JOIN datos_biometricos db ON c.id_colaborador = db.id_colaborador
    LEFT JOIN pruebas_psicometricas p ON c.id_colaborador = p.id_colaborador
    WHERE c.id_colaborador = ?
    ORDER BY db.fecha_medicion DESC LIMIT 1
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_colaborador);
$stmt->execute();
$resultado = $stmt->get_result();
$colaborador = $resultado->fetch_assoc();
$stmt->close();

// Verificar si el colaborador existe
if (!$colaborador) {
    echo "<div class='container mt-4'><h1>Colaborador no encontrado</h1></div>";
    exit;
}

// Consulta para obtener los datos de alimentación de los últimos 7 días
$sql = "
    SELECT 
        AVG(calorias) AS avg_calorias, AVG(proteinas) AS avg_proteinas,
        AVG(carbohidratos) AS avg_carbohidratos, AVG(grasas) AS avg_grasas,
        AVG(fibra) AS avg_fibra, AVG(azucar) AS avg_azucar, AVG(sodio) AS avg_sodio
    FROM alimentacion
    WHERE id_colaborador = ? AND fecha_registro >= DATE_SUB(CURDATE(), INTERVAL 7 DAY)
";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_colaborador);
$stmt->execute();
$resultado = $stmt->get_result();
$alimentacion = $resultado->fetch_assoc();
$stmt->close();

// Preparar los datos para las recomendaciones
$edad = $colaborador['edad'] ?? 30;
$genero = $colaborador['genero'] ?? 'M';
$peso = $colaborador['peso'] ?? 70;
$talla = $colaborador['talla'] ?? 170; // En cm
$perimetro_cintura = $colaborador['perimetro_cintura'] ?? null;
$porcentaje_grasa = $colaborador['porcentaje_grasa'] ?? null;
$masa_muscular = $colaborador['masa_muscular'] ?? null;
$presion_arterial_sistolica = $colaborador['presion_arterial_sistolica'] ?? null;
$presion_arterial_diastolica = $colaborador['presion_arterial_diastolica'] ?? null;
$frecuencia_cardiaca = $colaborador['frecuencia_cardiaca'] ?? null;
$glucosa_ayuno = $colaborador['glucosa_ayuno'] ?? null;
$colesterol_total = $colaborador['colesterol_total'] ?? null;
$trigliceridos = $colaborador['trigliceridos'] ?? null;
$enfermedades_previas = $colaborador['enfermedades_previas'] ?? 'Ninguna';
$estres = $colaborador['estres'] ?? 5;

// Datos de alimentación (promedios diarios)
$avg_calorias = $alimentacion['avg_calorias'] ?? 0;
$avg_proteinas = $alimentacion['avg_proteinas'] ?? 0;
$avg_carbohidratos = $alimentacion['avg_carbohidratos'] ?? 0;
$avg_grasas = $alimentacion['avg_grasas'] ?? 0;
$avg_fibra = $alimentacion['avg_fibra'] ?? 0;
$avg_azucar = $alimentacion['avg_azucar'] ?? 0;
$avg_sodio = $alimentacion['avg_sodio'] ?? 0;

// Calcular necesidades calóricas (fórmula de Harris-Benedict simplificada)
$metabolismo_basal = ($genero == 'M') ?
    88.362 + (13.397 * $peso) + (4.799 * $talla) - (5.677 * $edad) :
    447.593 + (9.247 * $peso) + (3.098 * $talla) - (4.330 * $edad);
$calorias_diarias_necesarias = $metabolismo_basal * 1.55; // Suponiendo un nivel de actividad moderado

// Valores recomendados para alimentación (simplificados)
$proteinas_recomendadas = $peso * 1.2; // 1.2 g/kg de peso
$carbohidratos_recomendados = ($calorias_diarias_necesarias * 0.5) / 4; // 50% de calorías, 4 kcal/g
$grasas_recomendadas = ($calorias_diarias_necesarias * 0.3) / 9; // 30% de calorías, 9 kcal/g
$fibra_recomendada = $genero == 'M' ? 38 : 25; // Recomendaciones generales
$azucar_recomendada = ($calorias_diarias_necesarias * 0.1) / 4; // Máximo 10% de calorías
$sodio_recomendado = 2300; // Máximo recomendado en mg

// Rango saludable para datos biométricos (simplificados)
$imc = $peso / (($talla / 100) * ($talla / 100));
$imc_saludable_min = 18.5;
$imc_saludable_max = 24.9;
$porcentaje_grasa_saludable_min = $genero == 'M' ? 10 : 20;
$porcentaje_grasa_saludable_max = $genero == 'M' ? 20 : 30;
$presion_sistolica_saludable_max = 120;
$presion_diastolica_saludable_max = 80;
$glucosa_ayuno_saludable_max = 100;
$colesterol_total_saludable_max = 200;
$trigliceridos_saludable_max = 150;

// Configurar la API de OpenAI
if (!defined('OPENAI_API_KEY') || empty(OPENAI_API_KEY)) {
    // Si la clave no está definida o está vacía, usar recomendaciones locales
    $imc = $peso / (($talla / 100) * ($talla / 100)); // Calcular IMC
    $recomendaciones = "<p><strong>Advertencia:</strong> No se pudo conectar con la API de OpenAI porque la clave no está configurada. Mostrando recomendaciones básicas.</p>";
    $recomendaciones .= "<h3>Manejo del Peso y Salud Metabólica (como bariatra)</h3>";
    if ($imc >= 25) {
        $recomendaciones .= "<p>Tu IMC ($imc) indica sobrepeso. Considera un déficit calórico de 500 kcal diarias para perder peso de forma saludable.</p>";
    }
    if ($enfermedades_previas && stripos($enfermedades_previas, 'diabetes') !== false) {
        $recomendaciones .= "<p>Debido a la diabetes, es crucial mantener tu glucosa en ayuno ($glucosa_ayuno mg/dL) bajo control. Evita picos de glucosa con comidas regulares y bajas en carbohidratos simples.</p>";
    }
    if ($trigliceridos && $trigliceridos > 150) {
        $recomendaciones .= "<p>Tus triglicéridos ($trigliceridos mg/dL) están elevados. Reduce el consumo de azúcares y grasas saturadas.</p>";
    }

    $recomendaciones .= "<h3>Alimentación (como nutriólogo)</h3>";
    if ($avg_calorias > $calorias_diarias_necesarias) {
        $recomendaciones .= "<p>Tu ingesta calórica ($avg_calorias kcal) es superior a tus necesidades ($calorias_diarias_necesarias kcal). Reduce las porciones y evita alimentos altos en calorías vacías.</p>";
    } else {
        $recomendaciones .= "<p>Tu ingesta calórica ($avg_calorias kcal) está por debajo de tus necesidades ($calorias_diarias_necesarias kcal). Asegúrate de incluir alimentos nutritivos y suficientes.</p>";
    }
    if ($avg_sodio > 2300) {
        $recomendaciones .= "<p>Tu consumo de sodio ($avg_sodio mg) es alto. Reduce el uso de sal y evita alimentos procesados.</p>";
    }
    if ($enfermedades_previas && stripos($enfermedades_previas, 'diabetes') !== false) {
        $recomendaciones .= "<p>Debido a la diabetes, controla tu ingesta de carbohidratos ($avg_carbohidratos g) y azúcar ($avg_azucar g). Prefiere carbohidratos complejos y evita azúcares refinados.</p>";
    }

    $recomendaciones .= "<h3>Actividad Física (como entrenador)</h3>";
    $recomendaciones .= "<p>Realiza caminatas de 30 minutos al día para quemar unas 150-200 kcal. Mantén tu frecuencia cardíaca entre el 50-70% de tu máximo (aproximadamente " . (220 - $edad) * 0.5 . " a " . (220 - $edad) * 0.7 . " lpm).</p>";
    if ($enfermedades_previas && stripos($enfermedades_previas, 'hipertension') !== false) {
        $recomendaciones .= "<p>Debido a la hipertensión, evita ejercicios de alta intensidad. Prefiere actividades como yoga o caminatas suaves.</p>";
    }

    $recomendaciones .= "<h3>Manejo del Estrés</h3>";
    $recomendaciones .= "<p>Practica meditación o yoga durante 10-15 minutos al día para reducir tu nivel de estrés ($estres).</p>";
} else {
    // Si la clave está definida, proceder con la solicitud a OpenAI
    $api_key = OPENAI_API_KEY;
    $prompt = "
    Eres un equipo de expertos en salud y bienestar, compuesto por un bariatra (especialista en obesidad y enfermedades metabólicas), un nutriólogo y un entrenador. Proporciona recomendaciones personalizadas para un colaborador con las siguientes características:

    - Edad: $edad años
    - Género: $genero
    - Peso: $peso kg
    - Talla: $talla cm
    - Perímetro de Cintura: " . ($perimetro_cintura ? "$perimetro_cintura cm" : "No disponible") . "
    - Porcentaje de Grasa: " . ($porcentaje_grasa ? "$porcentaje_grasa %" : "No disponible") . "
    - Masa Muscular: " . ($masa_muscular ? "$masa_muscular kg" : "No disponible") . "
    - Presión Arterial: " . ($presion_arterial_sistolica && $presion_arterial_diastolica ? "$presion_arterial_sistolica/$presion_arterial_diastolica mmHg" : "No disponible") . "
    - Frecuencia Cardíaca: " . ($frecuencia_cardiaca ? "$frecuencia_cardiaca lpm" : "No disponible") . "
    - Glucosa en Ayuno: " . ($glucosa_ayuno ? "$glucosa_ayuno mg/dL" : "No disponible") . "
    - Colesterol Total: " . ($colesterol_total ? "$colesterol_total mg/dL" : "No disponible") . "
    - Triglicéridos: " . ($trigliceridos ? "$trigliceridos mg/dL" : "No disponible") . "
    - Enfermedades Previas: $enfermedades_previas
    - Nivel de Estrés (1-10): $estres

    **Análisis de Alimentación Diaria (promedio de los últimos 7 días):**
    - Calorías: $avg_calorias kcal
    - Proteínas: $avg_proteinas g
    - Carbohidratos: $avg_carbohidratos g
    - Grasas: $avg_grasas g
    - Fibra: $avg_fibra g
    - Azúcar: $avg_azucar g
    - Sodio: $avg_sodio mg

    **Necesidades Calóricas Estimadas:**
    - Calorías diarias necesarias: $calorias_diarias_necesarias kcal

    Proporciona recomendaciones detalladas en los siguientes aspectos, teniendo en cuenta las enfermedades previas y los datos médicos:

    1. **Manejo del Peso y Salud Metabólica (como bariatra):** 
       - Evalúa el peso, IMC (calculado como peso/(talla/100)^2), perímetro de cintura, porcentaje de grasa y enfermedades metabólicas (como diabetes, hipertensión, dislipidemia).
       - Si hay obesidad o sobrepeso, sugiere estrategias para la pérdida de peso (déficit calórico, cambios en el estilo de vida).
       - Si hay enfermedades metabólicas, recomienda ajustes específicos (por ejemplo, control de glucosa, reducción de triglicéridos).

    2. **Alimentación (como nutriólogo):** 
       - Analiza la ingesta actual de calorías, proteínas, carbohidratos, grasas, fibra, azúcar y sodio.
       - Compara con las necesidades calóricas y los rangos saludables.
       - Recomienda una dieta personalizada, incluyendo alimentos a priorizar y evitar, considerando las enfermedades previas (por ejemplo, reducir sodio si hay hipertensión, controlar carbohidratos si hay diabetes).

    3. **Actividad Física (como entrenador):**
       - Recomienda actividades físicas específicas para mejorar su salud, considerando su peso, talla, porcentaje de grasa, masa muscular y enfermedades.
       - Indica cuántas calorías debería quemar diariamente y en qué zona de frecuencia cardíaca debería entrenar.
       - Si hay hipertensión o problemas cardíacos, sugiere ejercicios de baja intensidad.

    4. **Manejo del Estrés:**
       - Proporciona técnicas o actividades para reducir el estrés, considerando su nivel de estrés actual.

    Responde en formato HTML con secciones claras para cada aspecto, usando etiquetas <h3> para los títulos de las secciones y <p> para los párrafos.
    ";

    // Hacer la solicitud a la API de OpenAI
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://api.openai.com/v1/chat/completions");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $api_key",
        "Content-Type: application/json"
    ]);
    $data = [
        "model" => "gpt-3.5-turbo",
        "messages" => [
            ["role" => "system", "content" => "Eres un equipo de expertos en salud y bienestar, compuesto por un bariatra, un nutriólogo y un entrenador."],
            ["role" => "user", "content" => $prompt]
        ],
        "temperature" => 0.7
    ];
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);

    // Manejar errores de cURL
    if ($response === false) {
        $recomendaciones = "<p>Error al conectar con la API de OpenAI: " . curl_error($ch) . "</p>";
    } else {
        $response_data = json_decode($response, true);
        if (isset($response_data['choices'][0]['message']['content'])) {
            $recomendaciones = $response_data['choices'][0]['message']['content'];
        } else {
            $recomendaciones = "<p>Error al obtener recomendaciones: " . (isset($response_data['error']['message']) ? $response_data['error']['message'] : 'Respuesta inválida de la API') . "</p>";
        }
    }
    curl_close($ch);
}
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Recomendaciones Personalizadas para <?php echo htmlspecialchars($colaborador['nombre'] . ' ' . $colaborador['apellido']); ?></h1>
            <div class="section">
                <h2>Datos del Colaborador</h2>
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Edad:</strong> <?php echo $edad; ?> años</p>
                        <p><strong>Género:</strong> <?php echo $genero; ?></p>
                        <p><strong>Peso:</strong> <?php echo $peso; ?> kg</p>
                        <p><strong>Talla:</strong> <?php echo $talla; ?> cm</p>
                        <p><strong>IMC:</strong> <?php echo number_format($imc, 2); ?> (Rango saludable: <?php echo $imc_saludable_min; ?> - <?php echo $imc_saludable_max; ?>)</p>
                        <p><strong>Enfermedades Previas:</strong> <?php echo htmlspecialchars($enfermedades_previas); ?></p>
                    </div>
                    <div class="col-md-6">
                        <canvas id="biometricChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>

                <h2>Análisis de Datos Biométricos</h2>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Indicador</th>
                                <th>Valor Actual</th>
                                <th>Rango Saludable</th>
                                <th>Estado</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>IMC</td>
                                <td><?php echo number_format($imc, 2); ?></td>
                                <td><?php echo $imc_saludable_min; ?> - <?php echo $imc_saludable_max; ?></td>
                                <td><?php echo $imc >= $imc_saludable_min && $imc <= $imc_saludable_max ? '<span class="text-success">Saludable</span>' : '<span class="text-danger">Fuera de rango</span>'; ?></td>
                            </tr>
                            <tr>
                                <td>Porcentaje de Grasa</td>
                                <td><?php echo $porcentaje_grasa ? $porcentaje_grasa . '%' : 'No disponible'; ?></td>
                                <td><?php echo $porcentaje_grasa_saludable_min; ?>% - <?php echo $porcentaje_grasa_saludable_max; ?>%</td>
                                <td><?php echo $porcentaje_grasa ? ($porcentaje_grasa >= $porcentaje_grasa_saludable_min && $porcentaje_grasa <= $porcentaje_grasa_saludable_max ? '<span class="text-success">Saludable</span>' : '<span class="text-danger">Fuera de rango</span>') : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Presión Arterial</td>
                                <td><?php echo $presion_arterial_sistolica && $presion_arterial_diastolica ? "$presion_arterial_sistolica/$presion_arterial_diastolica mmHg" : 'No disponible'; ?></td>
                                <td>Menos de <?php echo $presion_sistolica_saludable_max; ?>/<?php echo $presion_diastolica_saludable_max; ?> mmHg</td>
                                <td><?php echo $presion_arterial_sistolica && $presion_arterial_diastolica ? ($presion_arterial_sistolica <= $presion_sistolica_saludable_max && $presion_arterial_diastolica <= $presion_diastolica_saludable_max ? '<span class="text-success">Saludable</span>' : '<span class="text-danger">Fuera de rango</span>') : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Glucosa en Ayuno</td>
                                <td><?php echo $glucosa_ayuno ? $glucosa_ayuno . ' mg/dL' : 'No disponible'; ?></td>
                                <td>Menos de <?php echo $glucosa_ayuno_saludable_max; ?> mg/dL</td>
                                <td><?php echo $glucosa_ayuno ? ($glucosa_ayuno <= $glucosa_ayuno_saludable_max ? '<span class="text-success">Saludable</span>' : '<span class="text-danger">Fuera de rango</span>') : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Colesterol Total</td>
                                <td><?php echo $colesterol_total ? $colesterol_total . ' mg/dL' : 'No disponible'; ?></td>
                                <td>Menos de <?php echo $colesterol_total_saludable_max; ?> mg/dL</td>
                                <td><?php echo $colesterol_total ? ($colesterol_total <= $colesterol_total_saludable_max ? '<span class="text-success">Saludable</span>' : '<span class="text-danger">Fuera de rango</span>') : 'N/A'; ?></td>
                            </tr>
                            <tr>
                                <td>Triglicéridos</td>
                                <td><?php echo $trigliceridos ? $trigliceridos . ' mg/dL' : 'No disponible'; ?></td>
                                <td>Menos de <?php echo $trigliceridos_saludable_max; ?> mg/dL</td>
                                <td><?php echo $trigliceridos ? ($trigliceridos <= $trigliceridos_saludable_max ? '<span class="text-success">Saludable</span>' : '<span class="text-danger">Fuera de rango</span>') : 'N/A'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2>Análisis de Alimentación (Promedio Diario - Últimos 7 Días)</h2>
                <div class="row">
                    <div class="col-md-6">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Nutriente</th>
                                        <th>Valor Actual</th>
                                        <th>Valor Recomendado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Calorías</td>
                                        <td><?php echo number_format($avg_calorias, 2); ?> kcal</td>
                                        <td><?php echo number_format($calorias_diarias_necesarias, 2); ?> kcal</td>
                                    </tr>
                                    <tr>
                                        <td>Proteínas</td>
                                        <td><?php echo number_format($avg_proteinas, 2); ?> g</td>
                                        <td><?php echo number_format($proteinas_recomendadas, 2); ?> g</td>
                                    </tr>
                                    <tr>
                                        <td>Carbohidratos</td>
                                        <td><?php echo number_format($avg_carbohidratos, 2); ?> g</td>
                                        <td><?php echo number_format($carbohidratos_recomendados, 2); ?> g</td>
                                    </tr>
                                    <tr>
                                        <td>Grasas</td>
                                        <td><?php echo number_format($avg_grasas, 2); ?> g</td>
                                        <td><?php echo number_format($grasas_recomendadas, 2); ?> g</td>
                                    </tr>
                                    <tr>
                                        <td>Fibra</td>
                                        <td><?php echo number_format($avg_fibra, 2); ?> g</td>
                                        <td><?php echo number_format($fibra_recomendada, 2); ?> g</td>
                                    </tr>
                                    <tr>
                                        <td>Azúcar</td>
                                        <td><?php echo number_format($avg_azucar, 2); ?> g</td>
                                        <td>Máximo <?php echo number_format($azucar_recomendada, 2); ?> g</td>
                                    </tr>
                                    <tr>
                                        <td>Sodio</td>
                                        <td><?php echo number_format($avg_sodio, 2); ?> mg</td>
                                        <td>Máximo <?php echo $sodio_recomendado; ?> mg</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <canvas id="nutritionChart" style="max-height: 300px;"></canvas>
                    </div>
                </div>

                <h2>Recomendaciones</h2>
                <?php echo $recomendaciones; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Gráfico de Alimentación (Barras)
const nutritionCtx = document.getElementById('nutritionChart').getContext('2d');
new Chart(nutritionCtx, {
    type: 'bar',
    data: {
        labels: ['Calorías (kcal)', 'Proteínas (g)', 'Carbohidratos (g)', 'Grasas (g)', 'Fibra (g)', 'Azúcar (g)', 'Sodio (mg)'],
        datasets: [
            {
                label: 'Valor Actual',
                data: [
                    <?php echo $avg_calorias; ?>,
                    <?php echo $avg_proteinas; ?>,
                    <?php echo $avg_carbohidratos; ?>,
                    <?php echo $avg_grasas; ?>,
                    <?php echo $avg_fibra; ?>,
                    <?php echo $avg_azucar; ?>,
                    <?php echo $avg_sodio; ?>
                ],
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            },
            {
                label: 'Valor Recomendado',
                data: [
                    <?php echo $calorias_diarias_necesarias; ?>,
                    <?php echo $proteinas_recomendadas; ?>,
                    <?php echo $carbohidratos_recomendados; ?>,
                    <?php echo $grasas_recomendadas; ?>,
                    <?php echo $fibra_recomendada; ?>,
                    <?php echo $azucar_recomendada; ?>,
                    <?php echo $sodio_recomendado; ?>
                ],
                backgroundColor: 'rgba(75, 192, 192, 0.6)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Cantidad'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Nutrientes'
                }
            }
        }
    }
});

// Gráfico de Datos Biométricos (Radar)
const biometricCtx = document.getElementById('biometricChart').getContext('2d');
new Chart(biometricCtx, {
    type: 'radar',
    data: {
        labels: ['IMC', 'Porcentaje de Grasa', 'Presión Sistólica', 'Glucosa en Ayuno', 'Colesterol Total', 'Triglicéridos'],
        datasets: [
            {
                label: 'Valor Actual (Normalizado)',
                data: [
                    <?php echo $imc ? ($imc / $imc_saludable_max) * 100 : 0; ?>,
                    <?php echo $porcentaje_grasa ? ($porcentaje_grasa / $porcentaje_grasa_saludable_max) * 100 : 0; ?>,
                    <?php echo $presion_arterial_sistolica ? ($presion_arterial_sistolica / $presion_sistolica_saludable_max) * 100 : 0; ?>,
                    <?php echo $glucosa_ayuno ? ($glucosa_ayuno / $glucosa_ayuno_saludable_max) * 100 : 0; ?>,
                    <?php echo $colesterol_total ? ($colesterol_total / $colesterol_total_saludable_max) * 100 : 0; ?>,
                    <?php echo $trigliceridos ? ($trigliceridos / $trigliceridos_saludable_max) * 100 : 0; ?>
                ],
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            },
            {
                label: 'Rango Saludable (Normalizado)',
                data: [100, 100, 100, 100, 100, 100], // 100% representa el límite superior del rango saludable
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 1
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            r: {
                beginAtZero: true,
                max: 150, // Permitir valores hasta 150% para mostrar excesos
                ticks: {
                    stepSize: 25
                }
            }
        }
    }
});
</script>

<style>
    .section h3 {
        margin-top: 20px;
        color: var(--primary-color);
    }
    .section p {
        margin-bottom: 10px;
    }
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
</style>

</body>
</html>

<?php
$conexion->close();
?>