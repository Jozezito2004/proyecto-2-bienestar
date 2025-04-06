<?php
$page_title = "Test de Burnout de Maslach - Bienestar BUAP";
include_once '../includes/encabezado.php';
include_once '../includes/barra_navegacion.php';
include_once '../includes/base_datos.php';
include_once '../includes/autenticacion.php';

$id_colaborador = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_colaborador <= 0) {
    echo "<div class='container mt-4'><div class='card'><div class='card-body'>";
    echo "<h1 class='card-title'>Error</h1>";
    echo "<p>No se ha seleccionado un colaborador válido.</p>";
    echo "<a href='cargar_pruebas_psicometricas.php' class='btn'>Volver</a>";
    echo "</div></div></div>";
    echo '</body></html>';
    exit();
}

$stmt = $conexion->prepare("SELECT nombre, apellido FROM colaboradores WHERE id_colaborador = ?");
$stmt->bind_param("i", $id_colaborador);
$stmt->execute();
$result = $stmt->get_result();
$colaborador = $result->fetch_assoc();
$stmt->close();

if (!$colaborador) {
    echo "<div class='container mt-4'><div class='card'><div class='card-body'>";
    echo "<h1 class='card-title'>Error</h1>";
    echo "<p>No se encontró el colaborador con ID: " . htmlspecialchars($id_colaborador) . ".</p>";
    echo "<a href='cargar_pruebas_psicometricas.php' class='btn'>Volver</a>";
    echo "</div></div></div>";
    echo '</body></html>';
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_colaborador = isset($_POST['id_colaborador']) ? (int)$_POST['id_colaborador'] : 0;
    $respuestas = [];

    for ($i = 1; $i <= 22; $i++) {
        $respuestas[$i] = isset($_POST["pregunta_$i"]) ? (int)$_POST["pregunta_$i"] : 0;
    }

    $ee_items = [1, 2, 3, 6, 8, 13, 14, 16, 20];
    $d_items = [5, 10, 11, 15, 22];
    $pa_items = [4, 7, 9, 12, 17, 18, 19, 21];

    $ee_score = 0;
    $d_score = 0;
    $pa_score = 0;

    foreach ($ee_items as $item) {
        $ee_score += $respuestas[$item];
    }
    foreach ($d_items as $item) {
        $d_score += $respuestas[$item];
    }
    foreach ($pa_items as $item) {
        $pa_score += $respuestas[$item];
    }

    $ee_normalized = ($ee_score / 54) * 100;
    $d_normalized = ($d_score / 30) * 100;
    $pa_normalized = 100 - (($pa_score / 48) * 100);

    $burnout_score = (0.4 * $ee_normalized + 0.4 * $d_normalized + 0.2 * $pa_normalized) / 10;
    $burnout_level = round(min(max($burnout_score, 1), 10));

    $sql = "INSERT INTO pruebas_psicometricas (id_colaborador, fecha_evaluacion, estres, depresion, burnout, ansiedad) 
            VALUES (?, CURDATE(), NULL, NULL, ?, NULL)
            ON DUplicate KEY UPDATE fecha_evaluacion = CURDATE(), burnout = ?";
    $stmt = $conexion->prepare($sql);
    $stmt->bind_param("iii", $id_colaborador, $burnout_level, $burnout_level);
    $stmt->execute();
    $stmt->close();

    $resultado = [
        'ee_score' => $ee_score,
        'd_score' => $d_score,
        'pa_score' => $pa_score,
        'burnout_level' => $burnout_level
    ];
}
?>

<style>
    #burnout-table {
        width: 100%;
        border-collapse: collapse;
        margin-bottom: 20px;
    }
    #burnout-table th, #burnout-table td {
        border: 1px solid #ddd;
        padding: 8px;
        text-align: center;
    }
    #burnout-table th {
        background-color: #f2f2f2;
    }
    #burnout-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    @media (max-width: 768px) {
        #burnout-table thead {
            display: none;
        }
        #burnout-table tr {
            display: block;
            margin-bottom: 15px;
        }
        #burnout-table td {
            display: block;
            text-align: left;
            position: relative;
            padding-left: 50%;
        }
        #burnout-table td:before {
            content: attr(data-label);
            position: absolute;
            left: 10px;
            width: 45%;
            font-weight: bold;
        }
    }
</style>

<div class="container mt-4">
    <div class="card">
        <div class="card-body">
            <h1 class="card-title">Test de Burnout de Maslach</h1>
            <p class="card-text">Colaborador: <strong><?php echo htmlspecialchars($colaborador['nombre'] . " " . $colaborador['apellido']); ?></strong></p>
            <p class="card-text">A continuación, encontrarás una serie de enunciados acerca de tu trabajo y de tus sentimientos en él. No existen respuestas malas ni buenas. El objetivo de este cuestionario es conocer tu nivel de satisfacción en tu trabajo. A cada una de las frases debes responder expresando la frecuencia con que tienes ese sentimiento.</p>

            <?php if (isset($resultado)): ?>
                <div class="section">
                    <h2>Resultados del Test de Burnout</h2>
                    <p><strong>Agotamiento Emocional (EE):</strong> <?php echo htmlspecialchars($resultado['ee_score']); ?> / 54 
                        (<?php 
                            if ($resultado['ee_score'] <= 18) echo "Bajo";
                            elseif ($resultado['ee_score'] <= 26) echo "Medio";
                            else echo "Alto";
                        ?>)
                    </p>
                    <p><strong>Despersonalización (D):</strong> <?php echo htmlspecialchars($resultado['d_score']); ?> / 30 
                        (<?php 
                            if ($resultado['d_score'] <= 5) echo "Bajo";
                            elseif ($resultado['d_score'] <= 9) echo "Medio";
                            else echo "Alto";
                        ?>)
                    </p>
                    <p><strong>Realización Personal (PA):</strong> <?php echo htmlspecialchars($resultado['pa_score']); ?> / 48 
                        (<?php 
                            if ($resultado['pa_score'] <= 25) echo "Bajo";
                            elseif ($resultado['pa_score'] <= 31) echo "Medio";
                            else echo "Alto";
                        ?>)
                    </p>
                    <p><strong>Nivel de Burnout (1-10):</strong> <?php echo htmlspecialchars($resultado['burnout_level']); ?></p>
                    <a href="cargar_pruebas_psicometricas.php" class="btn">Volver</a>
                </div>
            <?php else: ?>
                <form method="POST" action="test_burnout.php?id=<?php echo $id_colaborador; ?>" class="import-container">
                    <input type="hidden" name="id_colaborador" value="<?php echo $id_colaborador; ?>">
                    <table id="burnout-table">
                        <tr>
                            <th>Enunciado</th>
                            <th>Nunca (0)</th>
                            <th>Alguna vez en el año o menos (1)</th>
                            <th>Alguna vez al mes o menos (2)</th>
                            <th>Varias veces al mes (3)</th>
                            <th>Una vez a la semana (4)</th>
                            <th>Varias veces a la semana (5)</th>
                            <th>Todos los días (6)</th>
                        </tr>
                        <?php
                        $enunciados = [
                            1 => "Debido a mi trabajo me siento emocionalmente agotado o agotada.",
                            2 => "Al final del día me siento agotado o agotada.",
                            3 => "Me encuentro cansado o cansada cuando me levanto por la mañana y tengo que enfrentarme a otro día de trabajo.",
                            4 => "Puedo entender con facilidad lo que piensan las personas con quienes trabajo.",
                            5 => "Creo que trato a las personas como si fueran objetos.",
                            6 => "Trabajar con personas todos los días es una tensión para mí.",
                            7 => "Me enfrento muy bien a los problemas de trabajo que se me presentan.",
                            8 => "Me siento “quemado” o \"quemada\" por mi trabajo.",
                            9 => "Siento que con mi trabajo estoy influyendo positivamente en la vida de otros.",
                            10 => "Creo que tengo un trato más insensible con las personas desde que tengo este trabajo.",
                            11 => "Me preocupa que este trabajo me esté endureciendo emocionalmente.",
                            12 => "Me encuentro con mucha vitalidad.",
                            13 => "Me siento frustrado por mi trabajo.",
                            14 => "Siento que estoy haciendo un trabajo muy duro.",
                            15 => "Realmente no me importa lo que pueda suceder a las personas que me rodean.",
                            16 => "Trabajar directamente con personas me produce estrés.",
                            17 => "Tengo facilidad para crear un ambiente de confianza con las personas con quienes trabajo.",
                            18 => "Me encuentro relajado después de una junta de trabajo.",
                            19 => "He realizado muchas cosas que valen la pena en este trabajo.",
                            20 => "En el trabajo siento que estoy al límite de mis posibilidades.",
                            21 => "Siento que sé tratar de forma adecuada los problemas emocionales en el trabajo.",
                            22 => "Siento que las personas en mi trabajo me culpan de algunos de sus problemas."
                        ];

                        $opciones = [
                            0 => "Nunca (0)",
                            1 => "Alguna vez en el año o menos (1)",
                            2 => "Alguna vez al mes o menos (2)",
                            3 => "Varias veces al mes (3)",
                            4 => "Una vez a la semana (4)",
                            5 => "Varias veces a la semana (5)",
                            6 => "Todos los días (6)"
                        ];

                        foreach ($enunciados as $num => $enunciado): ?>
                            <tr>
                                <td data-label="Enunciado"><?php echo $num . ". " . htmlspecialchars($enunciado); ?></td>
                                <?php foreach ($opciones as $valor => $etiqueta): ?>
                                    <td data-label="<?php echo htmlspecialchars($etiqueta); ?>">
                                        <input type="radio" name="pregunta_<?php echo $num; ?>" value="<?php echo $valor; ?>" required>
                                    </td>
                                <?php endforeach; ?>
                            </tr>
                        <?php endforeach; ?>
                    </table>
                    <button type="submit" class="btn">Enviar Test</button>
                </form>
            <?php endif; ?>
        </div>
    </div>
</div>

</body>
</html>
<?php $conexion->close(); ?>