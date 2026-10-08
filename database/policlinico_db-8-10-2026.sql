-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: policlinico-db
-- Tiempo de generación: 08-10-2026 a las 13:41:13
-- Versión del servidor: 10.11.18-MariaDB-ubu2204
-- Versión de PHP: 8.3.33

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `policlinico_db`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ANAMNESIS`
--

CREATE TABLE `ANAMNESIS` (
  `id_Anamnesis` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  `sanitaria_Anamnesis` text DEFAULT NULL,
  `ambiental_Anamnesis` text DEFAULT NULL,
  `remota_fisiologica_Anamnesis` text DEFAULT NULL,
  `remota_patologica_Anamnesis` text DEFAULT NULL,
  `proxima_fisiologica_Anamnesis` text DEFAULT NULL,
  `proxima_patologica_Anamnesis` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ANAMNESIS`
--

INSERT INTO `ANAMNESIS` (`id_Anamnesis`, `id_Consulta`, `sanitaria_Anamnesis`, `ambiental_Anamnesis`, `remota_fisiologica_Anamnesis`, `remota_patologica_Anamnesis`, `proxima_fisiologica_Anamnesis`, `proxima_patologica_Anamnesis`) VALUES
(1, 1, 'Vacunación al día', 'Vive dentro del hogar', 'Alimentación normal', 'Episodio de gastroenteritis en 2025', 'Apetito disminuido', 'Vómitos desde hace 2 días'),
(2, 2, 'Vacunación y desparasitación al día', 'Vive en establecimiento rural', 'Alimentación con pastura y ración', 'Antecedente de cólico', 'Micción normal', 'Claudicación reciente'),
(3, 3, 'Vacunación al día', 'Vive dentro del hogar', 'Alimentación normal', 'Dermatitis previa', 'Normal', 'Prurito recurrente');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `CONSULTA`
--

CREATE TABLE `CONSULTA` (
  `id_Consulta` int(11) NOT NULL,
  `id_Paciente` int(11) NOT NULL,
  `tipo_Consulta` varchar(100) DEFAULT NULL,
  `fecha_hora_Consulta` datetime NOT NULL,
  `estado_proceso_Consulta` varchar(50) DEFAULT 'Pendiente',
  `resultado_final_Consulta` varchar(100) DEFAULT NULL,
  `fecha_proximo_control_Consulta` date DEFAULT NULL,
  `fecha_fin_Consulta` date DEFAULT NULL,
  `motivo_Consulta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `CONSULTA`
--

INSERT INTO `CONSULTA` (`id_Consulta`, `id_Paciente`, `tipo_Consulta`, `fecha_hora_Consulta`, `estado_proceso_Consulta`, `resultado_final_Consulta`, `fecha_proximo_control_Consulta`, `fecha_fin_Consulta`, `motivo_Consulta`) VALUES
(1, 1, 'Consulta general', '2026-09-20 10:30:00', 'Finalizada', 'Tratamiento ambulatorio', '2026-10-04', NULL, 'Vómitos y falta de apetito'),
(2, 3, 'Consulta equina', '2026-09-21 15:00:00', 'Finalizada', 'Seguimiento', '2026-10-21', NULL, 'Claudicación del miembro anterior'),
(3, 2, 'Control', '2026-09-22 09:30:00', 'En curso', NULL, NULL, NULL, 'Control dermatológico');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `DIAGNOSTICO`
--

CREATE TABLE `DIAGNOSTICO` (
  `id_Diagnostico` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  `tipo_Diagnostico` varchar(50) NOT NULL,
  `descripcion_Diagnostico` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `DIAGNOSTICO`
--

INSERT INTO `DIAGNOSTICO` (`id_Diagnostico`, `id_Consulta`, `tipo_Diagnostico`, `descripcion_Diagnostico`) VALUES
(1, 1, 'Presuntivo', 'Gastritis aguda'),
(2, 2, 'Definitivo', 'Claudicación de miembro anterior'),
(3, 3, 'Presuntivo', 'Dermatitis alérgica');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ESPECIE`
--

CREATE TABLE `ESPECIE` (
  `id_Especie` int(11) NOT NULL,
  `nombre_Especie` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `ESPECIE`
--

INSERT INTO `ESPECIE` (`id_Especie`, `nombre_Especie`) VALUES
(4, 'Bovino'),
(1, 'Canino'),
(3, 'Equino'),
(2, 'Felino');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EXAMEN_EQUINO`
--

CREATE TABLE `EXAMEN_EQUINO` (
  `id_ExamenEq` int(11) NOT NULL,
  `id_ExamenG` int(11) NOT NULL,
  `temperatura_cascos_ExamenEq` varchar(50) DEFAULT NULL,
  `pulso_digital_ExamenEq` varchar(50) DEFAULT NULL,
  `senos_ExamenEq` varchar(100) DEFAULT NULL,
  `csd_ExamenEq` varchar(50) DEFAULT NULL,
  `cid_ExamenEq` varchar(50) DEFAULT NULL,
  `csi_ExamenEq` varchar(50) DEFAULT NULL,
  `cii_ExamenEq` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `EXAMEN_EQUINO`
--

INSERT INTO `EXAMEN_EQUINO` (`id_ExamenEq`, `id_ExamenG`, `temperatura_cascos_ExamenEq`, `pulso_digital_ExamenEq`, `senos_ExamenEq`, `csd_ExamenEq`, `cid_ExamenEq`, `csi_ExamenEq`, `cii_ExamenEq`) VALUES
(1, 2, 'Normal', 'Aumentado', 'Sin secreción', 'Normal', 'Normal', 'Sensible', 'Normal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EXAMEN_GENERAL`
--

CREATE TABLE `EXAMEN_GENERAL` (
  `id_ExamenG` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  `peso_ExamenG` varchar(20) DEFAULT NULL,
  `CC_ExamenG` varchar(20) DEFAULT NULL,
  `TR_ExamenG` varchar(20) DEFAULT NULL,
  `FC_ExamenG` varchar(20) DEFAULT NULL,
  `FR_ExamenG` varchar(20) DEFAULT NULL,
  `TLLC_ExamenG` varchar(20) DEFAULT NULL,
  `sensorio_ExamenG` varchar(100) DEFAULT NULL,
  `facies_ExamenG` varchar(100) DEFAULT NULL,
  `piel_subcutaneo_ExamenG` varchar(100) DEFAULT NULL,
  `mucosas_ExamenG` varchar(100) DEFAULT NULL,
  `grandes_funciones_ExamenG` text DEFAULT NULL,
  `actitudes_anomalas_ExamenG` text DEFAULT NULL,
  `hidratacion_ExamenG` varchar(50) DEFAULT NULL,
  `ganglios_linfaticos_ExamenG` text DEFAULT NULL,
  `aparentes_ExamenG` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `EXAMEN_GENERAL`
--

INSERT INTO `EXAMEN_GENERAL` (`id_ExamenG`, `id_Consulta`, `peso_ExamenG`, `CC_ExamenG`, `TR_ExamenG`, `FC_ExamenG`, `FR_ExamenG`, `TLLC_ExamenG`, `sensorio_ExamenG`, `facies_ExamenG`, `piel_subcutaneo_ExamenG`, `mucosas_ExamenG`, `grandes_funciones_ExamenG`, `actitudes_anomalas_ExamenG`, `hidratacion_ExamenG`, `ganglios_linfaticos_ExamenG`, `aparentes_ExamenG`) VALUES
(1, 1, '18.5 kg', '5', '38.5 °C', '110 lpm', '24 rpm', '2 s', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL, NULL, NULL, NULL),
(2, 2, '480 kg', '5', '37.8 °C', '42 lpm', '16 rpm', '2 s', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL, NULL, NULL, NULL),
(3, 3, '4.2 kg', '4', '38.2 °C', '150 lpm', '30 rpm', '2 s', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EXAMEN_PARTICULAR`
--

CREATE TABLE `EXAMEN_PARTICULAR` (
  `id_ExamenP` int(11) NOT NULL,
  `id_ExamenG` int(11) DEFAULT NULL,
  `texto_libre_ExamenP` text DEFAULT NULL,
  `observaciones_ExamenP` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `EXAMEN_PARTICULAR`
--

INSERT INTO `EXAMEN_PARTICULAR` (`id_ExamenP`, `id_ExamenG`, `texto_libre_ExamenP`, `observaciones_ExamenP`) VALUES
(1, NULL, 'Dolor leve a la palpación abdominal.', NULL),
(2, NULL, 'Claudicación de miembro anterior izquierdo. Dolor a la flexión.', NULL),
(3, NULL, 'Lesiones eritematosas y pruriginosas en región cervical.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PACIENTE`
--

CREATE TABLE `PACIENTE` (
  `id_Paciente` int(11) NOT NULL,
  `id_Tutor` int(11) NOT NULL,
  `id_Raza` int(11) DEFAULT NULL,
  `numchip_Paciente` varchar(26) DEFAULT NULL,
  `nombre_Paciente` varchar(100) NOT NULL,
  `fecha_nac_Paciente` date DEFAULT NULL,
  `edad_Paciente` int(11) DEFAULT NULL,
  `sexo_Paciente` varchar(30) DEFAULT NULL,
  `color_Paciente` varchar(50) DEFAULT NULL,
  `foto_Paciente` varchar(255) DEFAULT NULL,
  `datecreate_Paciente` timestamp NULL DEFAULT current_timestamp(),
  `dateupdate_Paciente` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `PACIENTE`
--

INSERT INTO `PACIENTE` (`id_Paciente`, `id_Tutor`, `id_Raza`, `numchip_Paciente`, `nombre_Paciente`, `fecha_nac_Paciente`, `edad_Paciente`, `sexo_Paciente`, `color_Paciente`, `foto_Paciente`, `datecreate_Paciente`, `dateupdate_Paciente`) VALUES
(1, 1, NULL, NULL, 'Rocky', '2021-05-12', NULL, 'Macho', 'Marrón', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(2, 2, NULL, NULL, 'Luna', '2022-08-20', NULL, 'Hembra', 'Gris', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(3, 3, NULL, NULL, 'Relámpago', '2018-03-15', NULL, 'Macho castrado', 'Zaino', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PARACLINICO`
--

CREATE TABLE `PARACLINICO` (
  `id_Paraclinico` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  `tipo_Paraclinico` varchar(100) NOT NULL,
  `resultado_Paraclinico` text DEFAULT NULL,
  `archivoURL_Paraclinico` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `PARACLINICO`
--

INSERT INTO `PARACLINICO` (`id_Paraclinico`, `id_Consulta`, `tipo_Paraclinico`, `resultado_Paraclinico`, `archivoURL_Paraclinico`) VALUES
(1, 1, 'Hemograma', 'Leucocitos dentro de rango.', '/uploads/paraclinicos/hemograma_001.pdf'),
(2, 2, 'Radiografía', 'Sin evidencia de fractura.', '/uploads/paraclinicos/rx_002.jpg'),
(3, 2, 'Ecografía', 'Inflamación leve de tejidos blandos.', '/uploads/paraclinicos/eco_002.jpg');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PRONOSTICO`
--

CREATE TABLE `PRONOSTICO` (
  `id_Pronostico` int(11) NOT NULL,
  `id_Consulta` int(11) DEFAULT NULL,
  `tipo_Pronostico` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RAZA`
--

CREATE TABLE `RAZA` (
  `id_Raza` int(11) NOT NULL,
  `id_Especie` int(11) NOT NULL,
  `nombre_Raza` varchar(130) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RESEÑA`
--

CREATE TABLE `RESEÑA` (
  `id_Reseña` int(11) NOT NULL,
  `id_ExamenG` int(11) DEFAULT NULL,
  `archivoURL_RESEÑA` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `SERVICIO`
--

CREATE TABLE `SERVICIO` (
  `id_Servicio` int(11) NOT NULL,
  `id_Usuario` int(11) NOT NULL,
  `nombre_Servicio` varchar(100) NOT NULL,
  `descripcion_Servicio` text DEFAULT NULL,
  `precio_Servicio` decimal(10,2) NOT NULL,
  `duracion_Servicio` varchar(50) DEFAULT NULL,
  `imagenURL_Servicio` varchar(255) DEFAULT NULL,
  `iconoURL_Servicio` varchar(255) DEFAULT NULL,
  `datecreate_Servicio` timestamp NULL DEFAULT current_timestamp(),
  `dateupdate_Servicio` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `SERVICIO`
--

INSERT INTO `SERVICIO` (`id_Servicio`, `id_Usuario`, `nombre_Servicio`, `descripcion_Servicio`, `precio_Servicio`, `duracion_Servicio`, `imagenURL_Servicio`, `iconoURL_Servicio`, `datecreate_Servicio`, `dateupdate_Servicio`) VALUES
(1, 1, 'Consulta general', 'Evaluación clínica general', 1200.00, '30 min', NULL, NULL, '2026-09-25 01:22:50', '2026-09-28 13:47:17'),
(2, 1, 'Consulta equina', 'Evaluación clínica de equinos', 1800.00, '45 min', NULL, NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(3, 2, 'Control veterinario', 'Control posterior al tratamiento', 900.00, '20 min', NULL, NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TRATAMIENTO`
--

CREATE TABLE `TRATAMIENTO` (
  `id_Tratamiento` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  `descripcion_Tratamiento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `TRATAMIENTO`
--

INSERT INTO `TRATAMIENTO` (`id_Tratamiento`, `id_Consulta`, `descripcion_Tratamiento`) VALUES
(1, 1, 'Omeprazol 20 mg cada 24 horas durante 7 días.'),
(2, 1, 'Dieta blanda durante 3 días.'),
(3, 2, 'Antiinflamatorio según indicación veterinaria.'),
(4, 3, 'Limpieza de lesiones y aplicación de tratamiento tópico.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TUTOR`
--

CREATE TABLE `TUTOR` (
  `id_Tutor` int(11) NOT NULL,
  `documento_Tutor` varchar(20) NOT NULL,
  `nombre_Tutor` varchar(150) NOT NULL,
  `telefono_Tutor` varchar(50) DEFAULT NULL,
  `direccion_Tutor` varchar(255) DEFAULT NULL,
  `email_Tutor` varchar(150) DEFAULT NULL,
  `notas_Tutor` text DEFAULT NULL,
  `datecreate_Tutor` timestamp NULL DEFAULT current_timestamp(),
  `dateupdate_Tutor` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `TUTOR`
--

INSERT INTO `TUTOR` (`id_Tutor`, `documento_Tutor`, `nombre_Tutor`, `telefono_Tutor`, `direccion_Tutor`, `email_Tutor`, `notas_Tutor`, `datecreate_Tutor`, `dateupdate_Tutor`) VALUES
(1, '45678901', 'Carlos', '099123456', 'Av. Italia 1234', 'carlos@mail.com', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(2, '38901234', 'Laura', '098456789', 'Calle Rivera 567', 'laura@mail.com', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(3, '51234098', 'Martín', '097654321', 'Camino Maldonado 890', 'martin@mail.com', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO`
--

CREATE TABLE `USUARIO` (
  `id_Usuario` int(11) NOT NULL,
  `cedula_Usuario` varchar(20) NOT NULL,
  `nombre_Usuario` varchar(100) NOT NULL,
  `apellido_Usuario` varchar(100) NOT NULL,
  `email_Usuario` varchar(150) NOT NULL,
  `passw_Usuario` varchar(255) NOT NULL,
  `rol_Usuario` varchar(50) NOT NULL,
  `estado_Usuario` varchar(20) DEFAULT 'Activo',
  `lastlogin_Usuario` datetime DEFAULT NULL,
  `dateupdate_Usuario` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `USUARIO`
--

INSERT INTO `USUARIO` (`id_Usuario`, `cedula_Usuario`, `nombre_Usuario`, `apellido_Usuario`, `email_Usuario`, `passw_Usuario`, `rol_Usuario`, `estado_Usuario`, `lastlogin_Usuario`, `dateupdate_Usuario`) VALUES
(1, '51234567', 'Juan', 'Pérez', 'juan.perez@polivet.com', '123456', 'Veterinario', 'Activo', NULL, '2026-09-25 01:22:50'),
(2, '49876543', 'María', 'Rodríguez', 'maria.rodriguez@polivet.com', '123456', 'Veterinario', 'Activo', NULL, '2026-09-25 01:22:50'),
(3, '53456789', 'Ana', 'Silva', 'ana.silva@polivet.com', '123456', 'Recepción', 'Activo', NULL, '2026-09-25 01:22:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `USUARIO_CONSULTA`
--

CREATE TABLE `USUARIO_CONSULTA` (
  `id_Usuario` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `USUARIO_CONSULTA`
--

INSERT INTO `USUARIO_CONSULTA` (`id_Usuario`, `id_Consulta`) VALUES
(1, 1),
(1, 2),
(2, 3);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `ANAMNESIS`
--
ALTER TABLE `ANAMNESIS`
  ADD PRIMARY KEY (`id_Anamnesis`),
  ADD UNIQUE KEY `id_Consulta` (`id_Consulta`);

--
-- Indices de la tabla `CONSULTA`
--
ALTER TABLE `CONSULTA`
  ADD PRIMARY KEY (`id_Consulta`),
  ADD KEY `id_Paciente` (`id_Paciente`);

--
-- Indices de la tabla `DIAGNOSTICO`
--
ALTER TABLE `DIAGNOSTICO`
  ADD PRIMARY KEY (`id_Diagnostico`),
  ADD KEY `id_Consulta` (`id_Consulta`);

--
-- Indices de la tabla `ESPECIE`
--
ALTER TABLE `ESPECIE`
  ADD PRIMARY KEY (`id_Especie`),
  ADD UNIQUE KEY `nombre_Especie` (`nombre_Especie`);

--
-- Indices de la tabla `EXAMEN_EQUINO`
--
ALTER TABLE `EXAMEN_EQUINO`
  ADD PRIMARY KEY (`id_ExamenEq`),
  ADD UNIQUE KEY `id_ExamenG` (`id_ExamenG`);

--
-- Indices de la tabla `EXAMEN_GENERAL`
--
ALTER TABLE `EXAMEN_GENERAL`
  ADD PRIMARY KEY (`id_ExamenG`),
  ADD UNIQUE KEY `id_Consulta` (`id_Consulta`);

--
-- Indices de la tabla `EXAMEN_PARTICULAR`
--
ALTER TABLE `EXAMEN_PARTICULAR`
  ADD PRIMARY KEY (`id_ExamenP`),
  ADD KEY `id_FK_EP_ExamenG` (`id_ExamenG`);

--
-- Indices de la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  ADD PRIMARY KEY (`id_Paciente`),
  ADD KEY `id_Tutor` (`id_Tutor`),
  ADD KEY `id_FK_P_RAZA` (`id_Raza`);

--
-- Indices de la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  ADD PRIMARY KEY (`id_Paraclinico`),
  ADD KEY `id_Consulta` (`id_Consulta`);

--
-- Indices de la tabla `PRONOSTICO`
--
ALTER TABLE `PRONOSTICO`
  ADD PRIMARY KEY (`id_Pronostico`),
  ADD KEY `id_FK_PRONO_CONSULTA` (`id_Consulta`);

--
-- Indices de la tabla `RAZA`
--
ALTER TABLE `RAZA`
  ADD PRIMARY KEY (`id_Raza`),
  ADD KEY `id_FK_RAZA_ESPECIE` (`id_Especie`);

--
-- Indices de la tabla `RESEÑA`
--
ALTER TABLE `RESEÑA`
  ADD PRIMARY KEY (`id_Reseña`),
  ADD KEY `id_FK_RESEÑA_EXAMENGENERAL` (`id_ExamenG`);

--
-- Indices de la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  ADD PRIMARY KEY (`id_Servicio`),
  ADD KEY `id_Usuario` (`id_Usuario`);

--
-- Indices de la tabla `TRATAMIENTO`
--
ALTER TABLE `TRATAMIENTO`
  ADD PRIMARY KEY (`id_Tratamiento`),
  ADD KEY `id_Consulta` (`id_Consulta`);

--
-- Indices de la tabla `TUTOR`
--
ALTER TABLE `TUTOR`
  ADD PRIMARY KEY (`id_Tutor`),
  ADD UNIQUE KEY `documento_Tutor` (`documento_Tutor`);

--
-- Indices de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  ADD PRIMARY KEY (`id_Usuario`),
  ADD UNIQUE KEY `cedula_Usuario` (`cedula_Usuario`),
  ADD UNIQUE KEY `email_Usuario` (`email_Usuario`);

--
-- Indices de la tabla `USUARIO_CONSULTA`
--
ALTER TABLE `USUARIO_CONSULTA`
  ADD PRIMARY KEY (`id_Usuario`,`id_Consulta`),
  ADD KEY `id_Consulta` (`id_Consulta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `ANAMNESIS`
--
ALTER TABLE `ANAMNESIS`
  MODIFY `id_Anamnesis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `CONSULTA`
--
ALTER TABLE `CONSULTA`
  MODIFY `id_Consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `DIAGNOSTICO`
--
ALTER TABLE `DIAGNOSTICO`
  MODIFY `id_Diagnostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `ESPECIE`
--
ALTER TABLE `ESPECIE`
  MODIFY `id_Especie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_EQUINO`
--
ALTER TABLE `EXAMEN_EQUINO`
  MODIFY `id_ExamenEq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_GENERAL`
--
ALTER TABLE `EXAMEN_GENERAL`
  MODIFY `id_ExamenG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_PARTICULAR`
--
ALTER TABLE `EXAMEN_PARTICULAR`
  MODIFY `id_ExamenP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  MODIFY `id_Paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  MODIFY `id_Paraclinico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `PRONOSTICO`
--
ALTER TABLE `PRONOSTICO`
  MODIFY `id_Pronostico` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RAZA`
--
ALTER TABLE `RAZA`
  MODIFY `id_Raza` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `RESEÑA`
--
ALTER TABLE `RESEÑA`
  MODIFY `id_Reseña` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  MODIFY `id_Servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `TRATAMIENTO`
--
ALTER TABLE `TRATAMIENTO`
  MODIFY `id_Tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `TUTOR`
--
ALTER TABLE `TUTOR`
  MODIFY `id_Tutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  MODIFY `id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `ANAMNESIS`
--
ALTER TABLE `ANAMNESIS`
  ADD CONSTRAINT `id_FK_A_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `CONSULTA`
--
ALTER TABLE `CONSULTA`
  ADD CONSTRAINT `id_FK_C_PACIENTE` FOREIGN KEY (`id_Paciente`) REFERENCES `PACIENTE` (`id_Paciente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `DIAGNOSTICO`
--
ALTER TABLE `DIAGNOSTICO`
  ADD CONSTRAINT `id_FK_D_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `EXAMEN_EQUINO`
--
ALTER TABLE `EXAMEN_EQUINO`
  ADD CONSTRAINT `id_FK_EQ_EXAMENGENERAL` FOREIGN KEY (`id_ExamenG`) REFERENCES `EXAMEN_GENERAL` (`id_ExamenG`) ON DELETE CASCADE;

--
-- Filtros para la tabla `EXAMEN_GENERAL`
--
ALTER TABLE `EXAMEN_GENERAL`
  ADD CONSTRAINT `id_FK_EG_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `EXAMEN_PARTICULAR`
--
ALTER TABLE `EXAMEN_PARTICULAR`
  ADD CONSTRAINT `id_FK_EP_ExamenG` FOREIGN KEY (`id_ExamenG`) REFERENCES `EXAMEN_GENERAL` (`id_ExamenG`);

--
-- Filtros para la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  ADD CONSTRAINT `id_FK_P_RAZA` FOREIGN KEY (`id_Raza`) REFERENCES `RAZA` (`id_Raza`),
  ADD CONSTRAINT `id_FK_P_TUTOR` FOREIGN KEY (`id_Tutor`) REFERENCES `TUTOR` (`id_Tutor`) ON DELETE CASCADE;

--
-- Filtros para la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  ADD CONSTRAINT `id_FK_PARA_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `PRONOSTICO`
--
ALTER TABLE `PRONOSTICO`
  ADD CONSTRAINT `id_FK_PRONO_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`);

--
-- Filtros para la tabla `RAZA`
--
ALTER TABLE `RAZA`
  ADD CONSTRAINT `id_FK_RAZA_ESPECIE` FOREIGN KEY (`id_Especie`) REFERENCES `ESPECIE` (`id_Especie`);

--
-- Filtros para la tabla `RESEÑA`
--
ALTER TABLE `RESEÑA`
  ADD CONSTRAINT `id_FK_RESEÑA_EXAMENGENERAL` FOREIGN KEY (`id_ExamenG`) REFERENCES `EXAMEN_GENERAL` (`id_ExamenG`);

--
-- Filtros para la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  ADD CONSTRAINT `id_FK_SERVICIO_USUARIO` FOREIGN KEY (`id_Usuario`) REFERENCES `USUARIO` (`id_Usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `TRATAMIENTO`
--
ALTER TABLE `TRATAMIENTO`
  ADD CONSTRAINT `id_FK_TRATAMIENTO_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `USUARIO_CONSULTA`
--
ALTER TABLE `USUARIO_CONSULTA`
  ADD CONSTRAINT `USUARIO_CONSULTA_ibfk_1` FOREIGN KEY (`id_Usuario`) REFERENCES `USUARIO` (`id_Usuario`) ON DELETE CASCADE,
  ADD CONSTRAINT `USUARIO_CONSULTA_ibfk_2` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
