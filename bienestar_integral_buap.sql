-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:8889
-- Tiempo de generación: 29-03-2025 a las 07:06:24
-- Versión del servidor: 8.0.40
-- Versión de PHP: 8.3.14

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `bienestar_buap`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `actividad_fisica`
--

CREATE TABLE `actividad_fisica` (
  `id_actividad` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `fecha_registro` date NOT NULL,
  `promedio_pasos_diarios` int DEFAULT NULL,
  `minutos_actividad_moderada` int DEFAULT NULL,
  `frecuencia_entrenamiento` int DEFAULT NULL,
  `deportes_practicados` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `actividad_fisica`
--

INSERT INTO `actividad_fisica` (`id_actividad`, `id_colaborador`, `fecha_registro`, `promedio_pasos_diarios`, `minutos_actividad_moderada`, `frecuencia_entrenamiento`, `deportes_practicados`) VALUES
(2, 4, '2025-03-20', 8000, 30, 3, 'Natación'),
(3, 5, '2025-03-21', 6000, 20, 2, 'Yoga'),
(4, 6, '2025-03-22', 5000, 15, 1, 'Ciclismo'),
(5, 7, '2025-03-23', 10000, 40, 4, 'Correr'),
(6, 8, '2025-03-24', 7000, 25, 2, 'Fútbol'),
(7, 9, '2025-03-25', 5500, 15, 1, 'Pilates'),
(8, 10, '2025-03-26', 4000, 10, 1, 'Caminar'),
(9, 11, '2025-03-27', 9000, 35, 3, 'Danza'),
(10, 12, '2025-03-28', 6500, 20, 2, 'Tenis'),
(11, 13, '2025-03-29', 7500, 25, 3, 'Zumba');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimentacion`
--

CREATE TABLE `alimentacion` (
  `id_alimentacion` int NOT NULL,
  `id_colaborador` int DEFAULT NULL,
  `fecha_registro` datetime DEFAULT NULL,
  `peso` double DEFAULT NULL,
  `talla` double DEFAULT NULL,
  `imc` double DEFAULT NULL,
  `metabolismo_basal` double DEFAULT NULL,
  `calorias_diarias` double DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `alimentacion`
--

INSERT INTO `alimentacion` (`id_alimentacion`, `id_colaborador`, `fecha_registro`, `peso`, `talla`, `imc`, `metabolismo_basal`, `calorias_diarias`) VALUES
(1, 4, '2025-03-22 12:33:47', 73, 1.69, 25.55932915514163, 1636.25, 2822.53125),
(2, 8, '2025-03-25 13:34:36', 76, 1.69, 26.609712545078956, 1666.25, 1999.5);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `alimentacion_diaria`
--

CREATE TABLE `alimentacion_diaria` (
  `id_alimentacion` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `numero_identificacion` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `desayuno` text COLLATE utf8mb4_general_ci,
  `comida` text COLLATE utf8mb4_general_ci,
  `cena` text COLLATE utf8mb4_general_ci,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `asistencia`
--

CREATE TABLE `asistencia` (
  `id_asistencia` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `id_sesion` int NOT NULL,
  `evaluacion` int DEFAULT NULL,
  `estado_salud` text COLLATE utf8mb4_general_ci
) ;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `colaboradores`
--

CREATE TABLE `colaboradores` (
  `id_colaborador` int NOT NULL,
  `numero_identificacion` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `apellido` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_nacimiento` date NOT NULL,
  `genero` enum('M','F','Otro') COLLATE utf8mb4_general_ci NOT NULL,
  `unidad_trabajo` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `puesto` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `anos_servicio` int NOT NULL,
  `email` varchar(100) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `colaboradores`
--

INSERT INTO `colaboradores` (`id_colaborador`, `numero_identificacion`, `apellido`, `nombre`, `fecha_nacimiento`, `genero`, `unidad_trabajo`, `puesto`, `anos_servicio`, `email`, `fecha_registro`) VALUES
(4, '20001', 'Pérez', 'Juan', '1990-05-15', 'M', 'Facultad de Medicina', 'Profesor', 10, 'juan.perez@buap.mx', '2025-03-22 02:09:06'),
(5, '20002', 'Gómez', 'María', '1985-08-20', 'F', 'Facultad de Derecho', 'Secretaria', 5, 'maria.gomez@buap.mx', '2025-03-22 02:09:06'),
(6, '20003', 'Ramírez', 'Carlos', '1992-11-10', 'M', 'Facultad de Ingeniería', 'Investigador', 8, 'carlos.ramirez@buap.mx', '2025-03-22 02:09:06'),
(7, '20004', 'López', 'Ana', '1988-03-25', 'F', 'Facultad de Ciencias', 'Profesora', 7, 'ana.lopez@buap.mx', '2025-03-22 02:09:06'),
(8, '20005', 'Martínez', 'Luis', '1995-07-30', 'M', 'Facultad de Arquitectura', 'Asistente', 3, 'luis.martinez@buap.mx', '2025-03-22 02:09:06'),
(9, '20006', 'Hernández', 'Sofía', '1990-12-05', 'F', 'Facultad de Psicología', 'Psicóloga', 9, 'sofia.hernandez@buap.mx', '2025-03-22 02:09:06'),
(10, '20007', 'González', 'Pedro', '1983-09-15', 'M', 'Facultad de Economía', 'Profesor', 12, 'pedro.gonzalez@buap.mx', '2025-03-22 02:09:06'),
(11, '20008', 'Díaz', 'Laura', '1993-02-20', 'F', 'Facultad de Artes', 'Artista', 6, 'laura.diaz@buap.mx', '2025-03-22 02:09:06'),
(12, '20009', 'Torres', 'José', '1987-06-10', 'M', 'Facultad de Informática', 'Programador', 8, 'jose.torres@buap.mx', '2025-03-22 02:09:06'),
(13, '20010', 'Vega', 'Clara', '1991-04-18', 'F', 'Facultad de Educación', 'Maestra', 7, 'clara.vega@buap.mx', '2025-03-22 02:09:06');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `datos_biometricos`
--

CREATE TABLE `datos_biometricos` (
  `id_dato_biometrico` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `fecha_medicion` date DEFAULT NULL,
  `peso` decimal(5,2) NOT NULL,
  `talla` decimal(5,2) NOT NULL,
  `perimetro_cintura` decimal(5,2) DEFAULT NULL,
  `porcentaje_grasa` decimal(5,2) DEFAULT NULL,
  `masa_muscular` decimal(5,2) DEFAULT NULL,
  `presion_arterial_sistolica` int DEFAULT NULL,
  `presion_arterial_diastolica` int DEFAULT NULL,
  `frecuencia_cardiaca` int DEFAULT NULL,
  `glucosa_ayuno` int DEFAULT NULL,
  `colesterol_total` int DEFAULT NULL,
  `trigliceridos` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `datos_biometricos`
--

INSERT INTO `datos_biometricos` (`id_dato_biometrico`, `id_colaborador`, `fecha_medicion`, `peso`, `talla`, `perimetro_cintura`, `porcentaje_grasa`, `masa_muscular`, `presion_arterial_sistolica`, `presion_arterial_diastolica`, `frecuencia_cardiaca`, `glucosa_ayuno`, `colesterol_total`, `trigliceridos`) VALUES
(2, 4, '2025-03-20', 75.50, 170.00, 80.00, 25.50, 40.00, 120, 80, 70, 90, 180, 150),
(3, 5, '2025-03-21', 60.00, 165.00, 75.00, 22.00, 35.00, 115, 75, 65, 85, 170, 130),
(4, 6, '2025-03-22', 82.00, 175.00, 85.00, 28.00, 42.00, 130, 85, 72, 95, 190, 160),
(5, 7, '2025-03-23', 55.00, 160.00, 70.00, 20.00, 32.00, 110, 70, 60, 80, 160, 120),
(6, 8, '2025-03-24', 78.00, 172.00, 82.00, 26.00, 38.00, 125, 82, 68, 92, 185, 155),
(7, 9, '2025-03-25', 62.00, 168.00, 76.00, 23.00, 36.00, 118, 78, 66, 88, 175, 140),
(8, 10, '2025-03-26', 85.00, 178.00, 88.00, 29.00, 41.00, 135, 88, 75, 98, 195, 165),
(9, 11, '2025-03-27', 58.00, 162.00, 72.00, 21.00, 34.00, 112, 72, 62, 82, 165, 125),
(10, 12, '2025-03-28', 80.00, 174.00, 84.00, 27.00, 39.00, 128, 84, 70, 94, 188, 158),
(11, 13, '2025-03-29', 64.00, 166.00, 77.00, 24.00, 37.00, 116, 76, 64, 86, 172, 135);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `examenes_medicos`
--

CREATE TABLE `examenes_medicos` (
  `id_examen` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `fecha_examen` date NOT NULL,
  `electrocardiograma` text COLLATE utf8mb4_general_ci,
  `prueba_esfuerzo` text COLLATE utf8mb4_general_ci,
  `perfil_lipidico` text COLLATE utf8mb4_general_ci,
  `hemoglobina_glicosilada` decimal(4,2) DEFAULT NULL,
  `creatinina` decimal(4,2) DEFAULT NULL,
  `urea` decimal(4,2) DEFAULT NULL,
  `tgo` decimal(5,2) DEFAULT NULL,
  `tgp` decimal(5,2) DEFAULT NULL,
  `densitometria_osea` text COLLATE utf8mb4_general_ci,
  `vitamina_d` decimal(5,2) DEFAULT NULL,
  `evaluacion_postura` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `examenes_medicos`
--

INSERT INTO `examenes_medicos` (`id_examen`, `id_colaborador`, `fecha_examen`, `electrocardiograma`, `prueba_esfuerzo`, `perfil_lipidico`, `hemoglobina_glicosilada`, `creatinina`, `urea`, `tgo`, `tgp`, `densitometria_osea`, `vitamina_d`, `evaluacion_postura`) VALUES
(1, 4, '2025-03-20', 'Normal', 'Normal', 'Normal', 5.50, 0.90, 20.00, 30.00, 25.00, 'Normal', 30.00, 'Sin anomalías'),
(2, 5, '2025-03-21', 'Normal', 'Normal', 'Normal', 5.20, 0.80, 18.00, 28.00, 22.00, 'Normal', 35.00, 'Sin anomalías'),
(3, 6, '2025-03-22', 'Anormal', 'Normal', 'Alto', 6.00, 1.00, 22.00, 35.00, 30.00, 'Normal', 25.00, 'Postura incorrecta'),
(4, 7, '2025-03-23', 'Normal', 'Normal', 'Normal', 5.00, 0.70, 15.00, 25.00, 20.00, 'Normal', 40.00, 'Sin anomalías'),
(5, 8, '2025-03-24', 'Normal', 'Normal', 'Normal', 5.40, 0.90, 19.00, 32.00, 27.00, 'Normal', 32.00, 'Sin anomalías'),
(6, 9, '2025-03-25', 'Normal', 'Normal', 'Normal', 5.30, 0.80, 17.00, 29.00, 23.00, 'Normal', 38.00, 'Sin anomalías'),
(7, 10, '2025-03-26', 'Anormal', 'Normal', 'Alto', 6.50, 1.10, 23.00, 38.00, 32.00, 'Normal', 20.00, 'Postura incorrecta'),
(8, 11, '2025-03-27', 'Normal', 'Normal', 'Normal', 5.10, 0.70, 16.00, 26.00, 21.00, 'Normal', 42.00, 'Sin anomalías'),
(9, 12, '2025-03-28', 'Normal', 'Normal', 'Normal', 5.60, 0.90, 20.00, 33.00, 28.00, 'Normal', 31.00, 'Sin anomalías'),
(10, 13, '2025-03-29', 'Normal', 'Normal', 'Normal', 5.20, 0.80, 18.00, 27.00, 22.00, 'Normal', 36.00, 'Sin anomalías');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos_alimenticios`
--

CREATE TABLE `grupos_alimenticios` (
  `id` int NOT NULL,
  `nombre` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `color` varchar(50) COLLATE utf8mb4_general_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `grupos_alimenticios`
--

INSERT INTO `grupos_alimenticios` (`id`, `nombre`, `color`) VALUES
(1, 'Cereales y tubérculos sin grasa', 'Amarillo'),
(2, 'Cereales y tubérculos con grasa', 'Amarillo oscuro'),
(3, 'Verduras I: Consumo libre', 'Verde oscuro'),
(4, 'Verduras II', 'Verde'),
(5, 'Frutas: Ricas en fibra y bajas en azúcares', 'Verde claro'),
(6, 'Alimentos de origen animal muy bajos en grasa', 'Rosa claro'),
(7, 'Alimentos de origen animal bajos en grasa', 'Rosa'),
(8, 'Alimentos de origen animal con contenido moderado en grasa', 'Rosa medio'),
(9, 'Alimentos de origen animal con alto contenido de grasa', 'Rosa oscuro'),
(10, 'Leche descremada', 'Blanco'),
(11, 'Leguminosas', 'Marrón'),
(12, 'Grasas: Fuente de monoinsaturadas', 'Naranja'),
(13, 'Grasas: Fuente de poliinsaturadas', 'Naranja claro'),
(14, 'Grasas: Fuente de saturadas y trans', 'Naranja oscuro'),
(15, 'Azúcares', 'Morado'),
(16, 'Azúcares con grasa', 'Morado oscuro'),
(17, 'Alimentos libres de energía', 'Gris'),
(18, 'Alimentos preparados altos en energía', 'Rojo');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `historial_salud`
--

CREATE TABLE `historial_salud` (
  `id_historial` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `enfermedades_diagnosticadas` text COLLATE utf8mb4_general_ci,
  `historial_medicamentos` text COLLATE utf8mb4_general_ci,
  `alergias` text COLLATE utf8mb4_general_ci,
  `cirugias_previas` text COLLATE utf8mb4_general_ci,
  `historial_familiar` text COLLATE utf8mb4_general_ci,
  `nivel_estres` enum('Bajo','Medio','Alto') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `ansiedad` int DEFAULT NULL,
  `depresion` int DEFAULT NULL,
  `calidad_sueno_horas` decimal(4,2) DEFAULT NULL,
  `calidad_sueno_nivel` enum('Mala','Regular','Buena') COLLATE utf8mb4_general_ci DEFAULT NULL,
  `recuperacion_fisica` text COLLATE utf8mb4_general_ci
) ;

--
-- Volcado de datos para la tabla `historial_salud`
--

INSERT INTO `historial_salud` (`id_historial`, `id_colaborador`, `enfermedades_diagnosticadas`, `historial_medicamentos`, `alergias`, `cirugias_previas`, `historial_familiar`, `nivel_estres`, `ansiedad`, `depresion`, `calidad_sueno_horas`, `calidad_sueno_nivel`, `recuperacion_fisica`) VALUES
(2, 4, 'Ninguna', 'Ninguno', 'Ninguna', 'Ninguna', 'Ninguno', 'Medio', 3, 2, 7.50, 'Regular', 'Normal'),
(3, 5, 'Asma', 'Inhalador', 'Polen', 'Apendicectomía', 'Diabetes', 'Bajo', 2, 1, 8.00, 'Buena', 'Normal'),
(4, 6, 'Hipertensión', 'Losartán', 'Ninguna', 'Cirugía de rodilla', 'Hipertensión', 'Alto', 5, 4, 6.50, 'Mala', 'Lenta'),
(5, 7, 'Ninguna', 'Ninguno', 'Ninguna', 'Ninguna', 'Ninguno', 'Bajo', 1, 1, 8.50, 'Buena', 'Rápida'),
(6, 8, 'Ninguna', 'Ninguno', 'Ninguna', 'Ninguna', 'Colesterol', 'Medio', 4, 3, 7.00, 'Regular', 'Normal'),
(7, 9, 'Ansiedad', 'Sertralina', 'Ninguna', 'Ninguna', 'Ninguno', 'Alto', 6, 5, 6.00, 'Mala', 'Lenta'),
(8, 10, 'Diabetes', 'Metformina', 'Ninguna', 'Ninguna', 'Diabetes', 'Medio', 3, 3, 7.00, 'Regular', 'Normal'),
(9, 11, 'Ninguna', 'Ninguno', 'Ninguna', 'Ninguna', 'Ninguno', 'Bajo', 2, 2, 8.00, 'Buena', 'Rápida'),
(10, 12, 'Ninguna', 'Ninguno', 'Ninguna', 'Ninguna', 'Ninguno', 'Medio', 4, 3, 6.50, 'Regular', 'Normal'),
(11, 13, 'Migraña', 'Paracetamol', 'Ninguna', 'Ninguna', 'Ninguno', 'Bajo', 2, 1, 8.00, 'Buena', 'Normal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ip_autorizadas`
--

CREATE TABLE `ip_autorizadas` (
  `id_ip` int NOT NULL,
  `direccion_ip` varchar(45) COLLATE utf8mb4_general_ci NOT NULL,
  `descripcion` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `fecha_registro` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ip_autorizadas`
--

INSERT INTO `ip_autorizadas` (`id_ip`, `direccion_ip`, `descripcion`, `fecha_registro`) VALUES
(1, '192.168.1.85', 'IPHONE JOSE', '2025-03-25 17:25:58'),
(2, '192.168.1.107', 'MAC ADMIN', '2025-03-25 18:05:51'),
(3, '::1', 'localhost IPv6', '2025-03-25 18:08:20'),
(4, '127.0.0.1', 'localhost IPv4', '2025-03-25 18:08:34');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pruebas_actividad_fisica`
--

CREATE TABLE `pruebas_actividad_fisica` (
  `id` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `fecha_evaluacion` date NOT NULL,
  `flexiones_pecho` int DEFAULT NULL,
  `flexiones_pecho_hr` int DEFAULT NULL,
  `sentadillas_peso_corporal` int DEFAULT NULL,
  `sentadillas_peso_corporal_hr` int DEFAULT NULL,
  `plancha_isometrica` int DEFAULT NULL,
  `plancha_isometrica_hr` int DEFAULT NULL,
  `remo_mancuerna` int DEFAULT NULL,
  `remo_mancuerna_hr` int DEFAULT NULL,
  `test_1rm` int DEFAULT NULL,
  `test_1rm_hr` int DEFAULT NULL,
  `test_rockport` float DEFAULT NULL,
  `test_rockport_hr` int DEFAULT NULL,
  `test_rockport_tiempo` float DEFAULT NULL,
  `test_recuperacion_hr` int DEFAULT NULL,
  `test_recuperacion_hr_peak` int DEFAULT NULL,
  `test_recuperacion_hrv` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pruebas_actividad_fisica`
--

INSERT INTO `pruebas_actividad_fisica` (`id`, `id_colaborador`, `fecha_evaluacion`, `flexiones_pecho`, `flexiones_pecho_hr`, `sentadillas_peso_corporal`, `sentadillas_peso_corporal_hr`, `plancha_isometrica`, `plancha_isometrica_hr`, `remo_mancuerna`, `remo_mancuerna_hr`, `test_1rm`, `test_1rm_hr`, `test_rockport`, `test_rockport_hr`, `test_rockport_tiempo`, `test_recuperacion_hr`, `test_recuperacion_hr_peak`, `test_recuperacion_hrv`) VALUES
(1, 11, '2025-03-26', 18, 137, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pruebas_psicometricas`
--

CREATE TABLE `pruebas_psicometricas` (
  `id_prueba` int NOT NULL,
  `id_colaborador` int DEFAULT NULL,
  `fecha_evaluacion` date DEFAULT NULL,
  `estres` int DEFAULT NULL,
  `depresion` int DEFAULT NULL,
  `burnout` int DEFAULT NULL,
  `ansiedad` int DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `pruebas_psicometricas`
--

INSERT INTO `pruebas_psicometricas` (`id_prueba`, `id_colaborador`, `fecha_evaluacion`, `estres`, `depresion`, `burnout`, `ansiedad`) VALUES
(1, 11, '2025-03-25', NULL, NULL, 6, NULL),
(2, 11, '2025-03-25', NULL, 15, NULL, NULL),
(3, 11, '2025-03-25', NULL, 15, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `recomendaciones`
--

CREATE TABLE `recomendaciones` (
  `id_recomendacion` int NOT NULL,
  `id_colaborador` int NOT NULL,
  `recomendaciones` text COLLATE utf8mb4_general_ci NOT NULL,
  `fecha_generacion` datetime DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sesiones`
--

CREATE TABLE `sesiones` (
  `id_sesion` int NOT NULL,
  `fecha` date NOT NULL,
  `turno` enum('Matutino','Vespertino','Nocturno') COLLATE utf8mb4_general_ci NOT NULL,
  `tipo_actividad` varchar(50) COLLATE utf8mb4_general_ci NOT NULL,
  `duracion` int NOT NULL,
  `descripcion` text COLLATE utf8mb4_general_ci
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `actividad_fisica`
--
ALTER TABLE `actividad_fisica`
  ADD PRIMARY KEY (`id_actividad`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `alimentacion`
--
ALTER TABLE `alimentacion`
  ADD PRIMARY KEY (`id_alimentacion`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `alimentacion_diaria`
--
ALTER TABLE `alimentacion_diaria`
  ADD PRIMARY KEY (`id_alimentacion`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD PRIMARY KEY (`id_asistencia`),
  ADD KEY `id_colaborador` (`id_colaborador`),
  ADD KEY `id_sesion` (`id_sesion`);

--
-- Indices de la tabla `colaboradores`
--
ALTER TABLE `colaboradores`
  ADD PRIMARY KEY (`id_colaborador`),
  ADD UNIQUE KEY `numero_identificacion` (`numero_identificacion`);

--
-- Indices de la tabla `datos_biometricos`
--
ALTER TABLE `datos_biometricos`
  ADD PRIMARY KEY (`id_dato_biometrico`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `examenes_medicos`
--
ALTER TABLE `examenes_medicos`
  ADD PRIMARY KEY (`id_examen`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `grupos_alimenticios`
--
ALTER TABLE `grupos_alimenticios`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `historial_salud`
--
ALTER TABLE `historial_salud`
  ADD PRIMARY KEY (`id_historial`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `ip_autorizadas`
--
ALTER TABLE `ip_autorizadas`
  ADD PRIMARY KEY (`id_ip`),
  ADD UNIQUE KEY `direccion_ip` (`direccion_ip`);

--
-- Indices de la tabla `pruebas_actividad_fisica`
--
ALTER TABLE `pruebas_actividad_fisica`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `pruebas_psicometricas`
--
ALTER TABLE `pruebas_psicometricas`
  ADD PRIMARY KEY (`id_prueba`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  ADD PRIMARY KEY (`id_recomendacion`),
  ADD KEY `id_colaborador` (`id_colaborador`);

--
-- Indices de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  ADD PRIMARY KEY (`id_sesion`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `actividad_fisica`
--
ALTER TABLE `actividad_fisica`
  MODIFY `id_actividad` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `alimentacion`
--
ALTER TABLE `alimentacion`
  MODIFY `id_alimentacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `alimentacion_diaria`
--
ALTER TABLE `alimentacion_diaria`
  MODIFY `id_alimentacion` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT de la tabla `asistencia`
--
ALTER TABLE `asistencia`
  MODIFY `id_asistencia` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `colaboradores`
--
ALTER TABLE `colaboradores`
  MODIFY `id_colaborador` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `datos_biometricos`
--
ALTER TABLE `datos_biometricos`
  MODIFY `id_dato_biometrico` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT de la tabla `examenes_medicos`
--
ALTER TABLE `examenes_medicos`
  MODIFY `id_examen` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT de la tabla `grupos_alimenticios`
--
ALTER TABLE `grupos_alimenticios`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `historial_salud`
--
ALTER TABLE `historial_salud`
  MODIFY `id_historial` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `ip_autorizadas`
--
ALTER TABLE `ip_autorizadas`
  MODIFY `id_ip` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `pruebas_actividad_fisica`
--
ALTER TABLE `pruebas_actividad_fisica`
  MODIFY `id` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `pruebas_psicometricas`
--
ALTER TABLE `pruebas_psicometricas`
  MODIFY `id_prueba` int NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  MODIFY `id_recomendacion` int NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sesiones`
--
ALTER TABLE `sesiones`
  MODIFY `id_sesion` int NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `actividad_fisica`
--
ALTER TABLE `actividad_fisica`
  ADD CONSTRAINT `actividad_fisica_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);

--
-- Filtros para la tabla `alimentacion`
--
ALTER TABLE `alimentacion`
  ADD CONSTRAINT `alimentacion_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);

--
-- Filtros para la tabla `alimentacion_diaria`
--
ALTER TABLE `alimentacion_diaria`
  ADD CONSTRAINT `alimentacion_diaria_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`) ON DELETE CASCADE;

--
-- Filtros para la tabla `asistencia`
--
ALTER TABLE `asistencia`
  ADD CONSTRAINT `asistencia_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`),
  ADD CONSTRAINT `asistencia_ibfk_2` FOREIGN KEY (`id_sesion`) REFERENCES `sesiones` (`id_sesion`);

--
-- Filtros para la tabla `datos_biometricos`
--
ALTER TABLE `datos_biometricos`
  ADD CONSTRAINT `datos_biometricos_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);

--
-- Filtros para la tabla `examenes_medicos`
--
ALTER TABLE `examenes_medicos`
  ADD CONSTRAINT `examenes_medicos_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);

--
-- Filtros para la tabla `historial_salud`
--
ALTER TABLE `historial_salud`
  ADD CONSTRAINT `historial_salud_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);

--
-- Filtros para la tabla `pruebas_actividad_fisica`
--
ALTER TABLE `pruebas_actividad_fisica`
  ADD CONSTRAINT `pruebas_actividad_fisica_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);

--
-- Filtros para la tabla `pruebas_psicometricas`
--
ALTER TABLE `pruebas_psicometricas`
  ADD CONSTRAINT `pruebas_psicometricas_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);

--
-- Filtros para la tabla `recomendaciones`
--
ALTER TABLE `recomendaciones`
  ADD CONSTRAINT `recomendaciones_ibfk_1` FOREIGN KEY (`id_colaborador`) REFERENCES `colaboradores` (`id_colaborador`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
