<nav class="navbar navbar-expand-lg navbar-dark" style="background-color: var(--primary-color);">
    <div class="container-fluid">
        <a class="navbar-brand" href="#"><i class="fas fa-heartbeat me-2"></i>Bienestar BUAP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="cargar_datos.php"><i class="fas fa-upload me-1"></i>Cargar Datos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="ver_colaboradores.php"><i class="fas fa-users me-1"></i>Ver Colaboradores</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="generar_reportes.php"><i class="fas fa-chart-bar me-1"></i>Generar Reportes</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="colaboradoresDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fas fa-user-plus me-1"></i>Colaboradores
                    </a>
                    <ul class="dropdown-menu" aria-labelledby="colaboradoresDropdown">
                        <li><a class="dropdown-item" href="agregar_colaborador.php">Agregar Colaborador</a></li>
                        <li><a class="dropdown-item" href="historial_biometrico.php">Historial Biométrico</a></li>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pruebas_psicometricas.php"><i class="fas fa-brain me-1"></i>Pruebas Psicométricas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="alimentacion.php"><i class="fas fa-utensils me-1"></i>Alimentación</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="recomendaciones_ia.php"><i class="fas fa-robot me-1"></i>Recomendaciones IA</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="administrar_ip.php"><i class="fas fa-shield-alt me-1"></i>Administrar IPs</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .navbar {
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .nav-link {
        transition: color 0.3s;
    }
    .nav-link:hover {
        color: var(--accent-color) !important;
    }
    .dropdown-menu {
        border-radius: 8px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
    }
    .dropdown-item:hover {
        background-color: var(--secondary-color);
    }
</style>