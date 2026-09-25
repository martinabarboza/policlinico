-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: policlinico-db
-- Tiempo de generación: 25-09-2026 a las 01:27:15
-- Versión del servidor: 10.11.19-MariaDB-ubu2204
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
  `fecha_proximo_control` date DEFAULT NULL,
  `motivo_Consulta` text DEFAULT NULL,
  `pronostico_Consulta` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `CONSULTA`
--

INSERT INTO `CONSULTA` (`id_Consulta`, `id_Paciente`, `tipo_Consulta`, `fecha_hora_Consulta`, `estado_proceso_Consulta`, `resultado_final_Consulta`, `fecha_proximo_control`, `motivo_Consulta`, `pronostico_Consulta`) VALUES
(1, 1, 'Consulta general', '2026-09-20 10:30:00', 'Finalizada', 'Tratamiento ambulatorio', '2026-10-04', 'Vómitos y falta de apetito', 'Favorable'),
(2, 3, 'Consulta equina', '2026-09-21 15:00:00', 'Finalizada', 'Seguimiento', '2026-10-21', 'Claudicación del miembro anterior', 'Reservado'),
(3, 2, 'Control', '2026-09-22 09:30:00', 'En curso', NULL, NULL, 'Control dermatológico', NULL);

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
  `id_ExamenEquino` int(11) NOT NULL,
  `id_ExamenG` int(11) NOT NULL,
  `temperatura_cascos_ExamenEquino` varchar(50) DEFAULT NULL,
  `pulso_digital_ExamenEquino` varchar(50) DEFAULT NULL,
  `senos_ExamenEquino` varchar(100) DEFAULT NULL,
  `csd_ExamenEquino` varchar(50) DEFAULT NULL,
  `cid_ExamenEquino` varchar(50) DEFAULT NULL,
  `csi_ExamenEquino` varchar(50) DEFAULT NULL,
  `cii_ExamenEquino` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `EXAMEN_EQUINO`
--

INSERT INTO `EXAMEN_EQUINO` (`id_ExamenEquino`, `id_ExamenG`, `temperatura_cascos_ExamenEquino`, `pulso_digital_ExamenEquino`, `senos_ExamenEquino`, `csd_ExamenEquino`, `cid_ExamenEquino`, `csi_ExamenEquino`, `cii_ExamenEquino`) VALUES
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
  `pliegue_cutaneo_ExamenG` varchar(50) DEFAULT NULL,
  `sensorio_ExamenG` varchar(50) DEFAULT NULL,
  `facies_ExamenG` varchar(50) DEFAULT NULL,
  `piel_subcutaneo_ExamenG` varchar(100) DEFAULT NULL,
  `mucosas_ExamenG` varchar(50) DEFAULT NULL,
  `grandes_funciones_ExamenG` text DEFAULT NULL,
  `actitudes_anomalas_ExamenG` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `EXAMEN_GENERAL`
--

INSERT INTO `EXAMEN_GENERAL` (`id_ExamenG`, `id_Consulta`, `peso_ExamenG`, `CC_ExamenG`, `TR_ExamenG`, `FC_ExamenG`, `FR_ExamenG`, `TLLC_ExamenG`, `pliegue_cutaneo_ExamenG`, `sensorio_ExamenG`, `facies_ExamenG`, `piel_subcutaneo_ExamenG`, `mucosas_ExamenG`, `grandes_funciones_ExamenG`, `actitudes_anomalas_ExamenG`) VALUES
(1, 1, '18.5 kg', '5', '38.5 °C', '110 lpm', '24 rpm', '2 s', 'Normal', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL),
(2, 2, '480 kg', '5', '37.8 °C', '42 lpm', '16 rpm', '2 s', 'Normal', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL),
(3, 3, '4.2 kg', '4', '38.2 °C', '150 lpm', '30 rpm', '2 s', 'Normal', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `EXAMEN_PARTICULAR`
--

CREATE TABLE `EXAMEN_PARTICULAR` (
  `id_ExamenP` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  `texto_libre_ExamenP` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `EXAMEN_PARTICULAR`
--

INSERT INTO `EXAMEN_PARTICULAR` (`id_ExamenP`, `id_Consulta`, `texto_libre_ExamenP`) VALUES
(1, 1, 'Dolor leve a la palpación abdominal.'),
(2, 2, 'Claudicación de miembro anterior izquierdo. Dolor a la flexión.'),
(3, 3, 'Lesiones eritematosas y pruriginosas en región cervical.');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PACIENTE`
--

CREATE TABLE `PACIENTE` (
  `id_Paciente` int(11) NOT NULL,
  `id_Tutor` int(11) NOT NULL,
  `id_Especie` int(11) NOT NULL,
  `nombre_Paciente` varchar(100) NOT NULL,
  `fecha_nac_paciente` date DEFAULT NULL,
  `sexo_Paciente` varchar(30) DEFAULT NULL,
  `color_Paciente` varchar(50) DEFAULT NULL,
  `foto_Paciente` varchar(255) DEFAULT NULL,
  `datecreate_Paciente` timestamp NULL DEFAULT current_timestamp(),
  `dateupdate_Paciente` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `PACIENTE`
--

INSERT INTO `PACIENTE` (`id_Paciente`, `id_Tutor`, `id_Especie`, `nombre_Paciente`, `fecha_nac_paciente`, `sexo_Paciente`, `color_Paciente`, `foto_Paciente`, `datecreate_Paciente`, `dateupdate_Paciente`) VALUES
(1, 1, 1, 'Rocky', '2021-05-12', 'Macho', 'Marrón', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(2, 2, 2, 'Luna', '2022-08-20', 'Hembra', 'Gris', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(3, 3, 3, 'Relámpago', '2018-03-15', 'Macho castrado', 'Zaino', NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50');

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
  `datecreate_Servicios` timestamp NULL DEFAULT current_timestamp(),
  `dateupdate_Servicios` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `SERVICIO`
--

INSERT INTO `SERVICIO` (`id_Servicio`, `id_Usuario`, `nombre_Servicio`, `descripcion_Servicio`, `precio_Servicio`, `duracion_Servicio`, `imagenURL_Servicio`, `iconoURL_Servicio`, `datecreate_Servicios`, `dateupdate_Servicios`) VALUES
(1, 1, 'Consulta general', 'Evaluación clínica general', 1200.00, '30 min', NULL, NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(2, 1, 'Consulta equina', 'Evaluación clínica de equinos', 1800.00, '45 min', NULL, NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50'),
(3, 2, 'Control veterinario', 'Control posterior al tratamiento', 900.00, '20 min', NULL, NULL, '2026-09-25 01:22:50', '2026-09-25 01:22:50');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `TRATAMIENTO`
--

CREATE TABLE `TRATAMIENTO` (
  `id_Tratamiento` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  `tipo_Tratamiento` varchar(50) NOT NULL,
  `descripcion_Tratamiento` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `TRATAMIENTO`
--

INSERT INTO `TRATAMIENTO` (`id_Tratamiento`, `id_Consulta`, `tipo_Tratamiento`, `descripcion_Tratamiento`) VALUES
(1, 1, 'Farmacológico', 'Omeprazol 20 mg cada 24 horas durante 7 días.'),
(2, 1, 'Dieta', 'Dieta blanda durante 3 días.'),
(3, 2, 'Farmacológico', 'Antiinflamatorio según indicación veterinaria.'),
(4, 3, 'Tópico', 'Limpieza de lesiones y aplicación de tratamiento tópico.');

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
  ADD PRIMARY KEY (`id_ExamenEquino`),
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
  ADD KEY `id_Consulta` (`id_Consulta`);

--
-- Indices de la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  ADD PRIMARY KEY (`id_Paciente`),
  ADD KEY `id_Tutor` (`id_Tutor`),
  ADD KEY `id_Especie` (`id_Especie`);

--
-- Indices de la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  ADD PRIMARY KEY (`id_Paraclinico`),
  ADD KEY `id_Consulta` (`id_Consulta`);

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
  MODIFY `id_ExamenEquino` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

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
  ADD CONSTRAINT `ANAMNESIS_ibfk_1` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `CONSULTA`
--
ALTER TABLE `CONSULTA`
  ADD CONSTRAINT `CONSULTA_ibfk_1` FOREIGN KEY (`id_Paciente`) REFERENCES `PACIENTE` (`id_Paciente`) ON DELETE CASCADE;

--
-- Filtros para la tabla `DIAGNOSTICO`
--
ALTER TABLE `DIAGNOSTICO`
  ADD CONSTRAINT `DIAGNOSTICO_ibfk_1` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `EXAMEN_EQUINO`
--
ALTER TABLE `EXAMEN_EQUINO`
  ADD CONSTRAINT `EXAMEN_EQUINO_ibfk_1` FOREIGN KEY (`id_ExamenG`) REFERENCES `EXAMEN_GENERAL` (`id_ExamenG`) ON DELETE CASCADE;

--
-- Filtros para la tabla `EXAMEN_GENERAL`
--
ALTER TABLE `EXAMEN_GENERAL`
  ADD CONSTRAINT `EXAMEN_GENERAL_ibfk_1` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `EXAMEN_PARTICULAR`
--
ALTER TABLE `EXAMEN_PARTICULAR`
  ADD CONSTRAINT `EXAMEN_PARTICULAR_ibfk_1` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  ADD CONSTRAINT `PACIENTE_ibfk_1` FOREIGN KEY (`id_Tutor`) REFERENCES `TUTOR` (`id_Tutor`) ON DELETE CASCADE,
  ADD CONSTRAINT `PACIENTE_ibfk_2` FOREIGN KEY (`id_Especie`) REFERENCES `ESPECIE` (`id_Especie`);

--
-- Filtros para la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  ADD CONSTRAINT `PARACLINICO_ibfk_1` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

--
-- Filtros para la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  ADD CONSTRAINT `SERVICIO_ibfk_1` FOREIGN KEY (`id_Usuario`) REFERENCES `USUARIO` (`id_Usuario`) ON DELETE CASCADE;

--
-- Filtros para la tabla `TRATAMIENTO`
--
ALTER TABLE `TRATAMIENTO`
  ADD CONSTRAINT `TRATAMIENTO_ibfk_1` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE;

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
