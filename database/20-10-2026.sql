-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: policlinico-db
-- Tiempo de generación: 20-10-2026 a las 23:07:14
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
(1, 3, 'Vacunación al día', 'Vive en departamento', 'Come normalmente', 'No presentó antecedentes', 'Se observa normal', 'No se reportan antecedentes'),
(2, 2, 'Vacunación al día', 'Se encuentra en establo', 'Pasa el día en pastoreo', 'No presenta alergias', 'Movimiento normal', 'Le cuesta levantar la pata');

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
(1, 1, 'Consulta general', '2026-10-15 09:30:00', 'Pendiente', NULL, NULL, NULL, 'Control de rutina'),
(2, 2, 'Consulta equina', '2026-10-15 11:00:00', 'En curso', NULL, NULL, NULL, 'Claudicación del miembro anterior'),
(3, 3, 'Control', '2026-10-15 15:15:00', 'Finalizada', 'Tratamiento indicado', '2026-10-22', NULL, 'Revisión dermatológica'),
(4, 1, 'Hospitalización', '2026-10-15 18:00:00', 'En curso', NULL, NULL, NULL, 'Observación postoperatoria');

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
(1, 3, 'Presuntivo', 'Dermatitis alérgica'),
(2, 2, 'Definitivo', 'Claudicación de miembro anterior');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `ESPECIE`
--

CREATE TABLE `ESPECIE` (
  `id_Especie` int(11) NOT NULL,
  `nombre_Especie` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(1, 3, '4.2 kg', '4', '38.3 °C', '150 lpm', '30 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones', 'Rosadas', NULL, NULL, 'Normal', NULL, NULL),
(2, 2, '480 kg', '5', '37.8 °C', '42 lpm', '16 rpm', '2 s', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL, 'Normal', NULL, NULL);

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `HOSPITALIZACION`
--

CREATE TABLE `HOSPITALIZACION` (
  `id_Hospitalizacion` int(11) NOT NULL,
  `id_Paciente` int(11) NOT NULL,
  `id_Consulta` int(11) DEFAULT NULL,
  `id_Usuario` int(11) DEFAULT NULL,
  `fecha_ingreso_Hospitalizacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_egreso_Hospitalizacion` datetime DEFAULT NULL,
  `motivo_Hospitalizacion` text DEFAULT NULL,
  `estado_Hospitalizacion` varchar(30) NOT NULL DEFAULT 'Activa'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `HOSPITALIZACION`
--

INSERT INTO `HOSPITALIZACION` (`id_Hospitalizacion`, `id_Paciente`, `id_Consulta`, `id_Usuario`, `fecha_ingreso_Hospitalizacion`, `fecha_egreso_Hospitalizacion`, `motivo_Hospitalizacion`, `estado_Hospitalizacion`) VALUES
(1, 1, 4, 3, '2026-10-15 18:00:00', NULL, 'Observación postoperatoria', 'Activa'),
(2, 2, 2, 2, '2026-10-15 11:00:00', NULL, 'Manejo de claudicación', 'Activa');

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
(1, 1, NULL, 'ARG-1001', 'Nala', '2021-04-15', 4, 'Hembra', 'Marrón', NULL, '2026-10-15 23:45:16', '2026-10-15 23:45:16'),
(2, 2, NULL, 'ARG-2002', 'Relámpago', '2018-03-09', 7, 'Macho castrado', 'Zaino', NULL, '2026-10-15 23:45:16', '2026-10-15 23:45:16'),
(3, 3, NULL, 'ARG-3003', 'Milo', '2022-11-02', 3, 'Macho', 'Gris', NULL, '2026-10-15 23:45:16', '2026-10-15 23:45:16');

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
(1, 3, 'Antihistamínico por 5 días y control en 7 días'),
(2, 2, 'Descarga y observación por 48 horas');

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
(1, '12345678', 'Laura Benítez', '099123456', 'Calle Rivera 154', 'laura.benitez@example.com', 'Tutor principal', '2026-10-15 23:45:16', '2026-10-15 23:45:16'),
(2, '87654321', 'Sergio Medina', '098765432', 'Avenida del Sol 23', 'sergio.medina@example.com', 'Tutor del caballo', '2026-10-15 23:45:16', '2026-10-15 23:45:16'),
(3, '45678912', 'Ana Costa', '091234567', 'Camino Real 99', 'ana.costa@example.com', 'Tutor del gato', '2026-10-15 23:45:16', '2026-10-15 23:45:16');

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
(1, '51234567', 'Juan', 'Pérez', 'juan.perez@polivet.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Veterinario', 'Activo', NULL, '2026-10-13 12:13:50'),
(2, '12345678', 'María', 'Rodríguez', 'maria.rodriguez@polivet.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Veterinario', 'Activo', '2026-10-20 13:23:42', '2026-10-20 13:23:42'),
(3, '53456789', 'Ana', 'Silva', 'ana.silva@polivet.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Recepción', 'Activo', '2026-10-15 13:24:45', '2026-10-15 13:24:45');

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
(1, 3),
(2, 2),
(3, 4);

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
-- Indices de la tabla `HOSPITALIZACION`
--
ALTER TABLE `HOSPITALIZACION`
  ADD PRIMARY KEY (`id_Hospitalizacion`),
  ADD KEY `id_Paciente` (`id_Paciente`),
  ADD KEY `id_Consulta` (`id_Consulta`),
  ADD KEY `id_Usuario` (`id_Usuario`);

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
  MODIFY `id_Anamnesis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `CONSULTA`
--
ALTER TABLE `CONSULTA`
  MODIFY `id_Consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `DIAGNOSTICO`
--
ALTER TABLE `DIAGNOSTICO`
  MODIFY `id_Diagnostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `ESPECIE`
--
ALTER TABLE `ESPECIE`
  MODIFY `id_Especie` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_EQUINO`
--
ALTER TABLE `EXAMEN_EQUINO`
  MODIFY `id_ExamenEq` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_GENERAL`
--
ALTER TABLE `EXAMEN_GENERAL`
  MODIFY `id_ExamenG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_PARTICULAR`
--
ALTER TABLE `EXAMEN_PARTICULAR`
  MODIFY `id_ExamenP` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `HOSPITALIZACION`
--
ALTER TABLE `HOSPITALIZACION`
  MODIFY `id_Hospitalizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  MODIFY `id_Paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  MODIFY `id_Paraclinico` int(11) NOT NULL AUTO_INCREMENT;

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
  MODIFY `id_Servicio` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `TRATAMIENTO`
--
ALTER TABLE `TRATAMIENTO`
  MODIFY `id_Tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

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
-- Filtros para la tabla `HOSPITALIZACION`
--
ALTER TABLE `HOSPITALIZACION`
  ADD CONSTRAINT `HOSPITALIZACION_ibfk_1` FOREIGN KEY (`id_Paciente`) REFERENCES `PACIENTE` (`id_Paciente`),
  ADD CONSTRAINT `HOSPITALIZACION_ibfk_2` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`),
  ADD CONSTRAINT `HOSPITALIZACION_ibfk_3` FOREIGN KEY (`id_Usuario`) REFERENCES `USUARIO` (`id_Usuario`);

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
