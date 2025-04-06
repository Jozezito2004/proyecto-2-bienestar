<?php
$page_title = "Historial Biométrico - Bienestar BUAP";
include '../includes/encabezado.php';
include '../includes/barra_navegacion.php';
include '../includes/base_datos.php';
include '../includes/autenticacion.php';

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
                <form action="historial_biometrico.php" method="GET">
                    <div class="mb-3">
                        <label for="id" class="form-label">Colaborador:</label>
                        <select name="id" id="id" class="form-select" required>
                            <option value="">Seleccione un colaborador</option>
                            <?php while ($fila = $resultado->fetch_assoc()) { ?>
                                <option value="<?php echo $fila['id_colaborador']; ?>">
                                    <?php echo htmlspecialchars($fila['nombre'] . ' ' . $fila['apellido']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Ver Historial</button>
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
$sql = "SELECT nombre, apellido, genero, fecha_nacimiento, 
        TIMESTAMPDIFF(YEAR, fecha_nacimiento, CURDATE()) AS edad 
        FROM colaboradores WHERE id_colaborador = ?";
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

// Consulta para obtener el historial de datos biométricos
$sql = "SELECT fecha_medicion, peso, talla, perimetro_cintura, porcentaje_grasa, masa_muscular, 
        presion_arterial_sistolica, presion_arterial_diastolica, frecuencia_cardiaca, 
        glucosa_ayuno, colesterol_total, trigliceridos 
        FROM datos_biometricos 
        WHERE id_colaborador = ? 
        ORDER BY fecha_medicion ASC";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id_colaborador);
$stmt->execute();
$resultado = $stmt->get_result();
$historial = [];
while ($fila = $resultado->fetch_assoc()) {
    $historial[] = $fila;
}
$stmt->close();

// Calcular IMC para cada registro
foreach ($historial as &$registro) {
    $registro['imc'] = $registro['peso'] && $registro['talla'] ? 
        $registro['peso'] / (($registro['talla'] / 100) * ($registro['talla'] / 100)) : null;
}
?>

<div class="container mt-4">
    <div class="card shadow-sm">
        <div class="card-body">
            <h1 class="card-title">Historial Biométrico de <?php echo htmlspecialchars($colaborador['nombre'] . ' ' . $colaborador['apellido']); ?></h1>
            <div class="row mb-4">
                <div class="col-md-6">
                    <p><strong>Edad:</strong> <?php echo $colaborador['edad']; ?> años</p>
                    <p><strong>Género:</strong> <?php echo $colaborador['genero']; ?></p>
                </div>
            </div>

            <!-- Gráficos -->
            <h2>Evolución de Indicadores Biométricos</h2>
            <div class="row">
                <div class="col-md-6 mb-4">
                    <canvas id="weightChart" style="max-height: 300px;"></canvas>
                </div>
                <div class="col-md-6 mb-4">
                    <canvas id="imcChart" style="max-height: 300px;"></canvas>
                </div>
                <div class="col-md-6 mb-4">
                    <canvas id="glucoseChart" style="max-height: 300px;"></canvas>
                </div>
                <div class="col-md-6 mb-4">
                    <canvas id="cholesterolChart" style="max-height: 300px;"></canvas>
                </div>
            </div>

            <!-- Tabla de Historial -->
            <h2>Historial de Mediciones</h2>
            <div class="table-responsive">
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Peso (kg)</th>
                            <th>Talla (cm)</th>
                            <th>IMC</th>
                            <th>Porcentaje de Grasa (%)</th>
                            <th>Presión Arterial (mmHg)</th>
                            <th>Glucosa (mg/dL)</th>
                            <th>Colesterol (mg/dL)</th>
                            <th>Triglicéridos (mg/dL)</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($historial as $registro): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($registro['fecha_medicion']); ?></td>
                                <td><?php echo $registro['peso'] ? number_format($registro['peso'], 1) : 'N/A'; ?></td>
                                <td><?php echo $registro['talla'] ? number_format($registro['talla'], 1) : 'N/A'; ?></td>
                                <td><?php echo $registro['imc'] ? number_format($registro['imc'], 2) : 'N/A'; ?></td>
                                <td><?php echo $registro['porcentaje_grasa'] ? number_format($registro['porcentaje_grasa'], 1) : 'N/A'; ?></td>
                                <td><?php echo ($registro['presion_arterial_sistolica'] && $registro['presion_arterial_diastolica']) ? 
                                    $registro['presion_arterial_sistolica'] . '/' . $registro['presion_arterial_diastolica'] : 'N/A'; ?></td>
                                <td><?php echo $registro['glucosa_ayuno'] ? number_format($registro['glucosa_ayuno'], 1) : 'N/A'; ?></td>
                                <td><?php echo $registro['colesterol_total'] ? number_format($registro['colesterol_total'], 1) : 'N/A'; ?></td>
                                <td><?php echo $registro['trigliceridos'] ? number_format($registro['trigliceridos'], 1) : 'N/A'; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
// Preparar datos para los gráficos
const fechas = [<?php echo "'" . implode("','", array_column($historial, 'fecha_medicion')) . "'"; ?>];
const pesos = [<?php echo implode(',', array_map(function($r) { return $r['peso'] ?? 'null'; }, $historial)); ?>];
const imcs = [<?php echo implode(',', array_map(function($r) { return $r['imc'] ? number_format($r['imc'], 2) : 'null'; }, $historial)); ?>];
const glucosa = [<?php echo implode(',', array_map(function($r) { return $r['glucosa_ayuno'] ?? 'null'; }, $historial)); ?>];
const colesterol = [<?php echo implode(',', array_map(function($r) { return $r['colesterol_total'] ?? 'null'; }, $historial)); ?>];

// Gráfico de Peso
const weightCtx = document.getElementById('weightChart').getContext('2d');
new Chart(weightCtx, {
    type: 'line',
    data: {
        labels: fechas,
        datasets: [{
            label: 'Peso (kg)',
            data: pesos,
            borderColor: 'rgba(54, 162, 235, 1)',
            backgroundColor: 'rgba(54, 162, 235, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: false,
                title: {
                    display: true,
                    text: 'Peso (kg)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            }
        }
    }
});

// Gráfico de IMC
const imcCtx = document.getElementById('imcChart').getContext('2d');
new Chart(imcCtx, {
    type: 'line',
    data: {
        labels: fechas,
        datasets: [{
            label: 'IMC',
            data: imcs,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: false,
                title: {
                    display: true,
                    text: 'IMC'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            }
        }
    }
});

// Gráfico de Glucosa
const glucoseCtx = document.getElementById('glucoseChart').getContext('2d');
new Chart(glucoseCtx, {
    type: 'line',
    data: {
        labels: fechas,
        datasets: [{
            label: 'Glucosa (mg/dL)',
            data: glucosa,
            borderColor: 'rgba(255, 99, 132, 1)',
            backgroundColor: 'rgba(255, 99, 132, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: false,
                title: {
                    display: true,
                    text: 'Glucosa (mg/dL)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            }
        }
    }
});

// Gráfico de Colesterol
const cholesterolCtx = document.getElementById('cholesterolChart').getContext('2d');
new Chart(cholesterolCtx, {
    type: 'line',
    data: {
        labels: fechas,
        datasets: [{
            label: 'Colesterol Total (mg/dL)',
            data: colesterol,
            borderColor: 'rgba(255, 206, 86, 1)',
            backgroundColor: 'rgba(255, 206, 86, 0.2)',
            fill: true,
            tension: 0.3
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: false,
                title: {
                    display: true,
                    text: 'Colesterol (mg/dL)'
                }
            },
            x: {
                title: {
                    display: true,
                    text: 'Fecha'
                }
            }
        }
    }
});
</script>

<style>
    .table th, .table td {
        vertical-align: middle;
        text-align: center;
    }
    .table th {
        background-color: #003087;
        color: white;
    }
    .table tbody tr:nth-child(odd) {
        background-color: #f8f9fa;
    }
</style>

</body>
</html>

<?php
$conexion->close();
?>