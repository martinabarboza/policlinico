-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: policlinico-db
-- Tiempo de generación: 16-11-2026 a las 13:52:14
-- Versión del servidor: 10.11.19-MariaDB-ubu2204
-- Versión de PHP: 8.3.36

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
(1, 1, 'Vacunación al día.', 'Vive en casa con patio.', 'Apetito y actividad habituales.', 'Sin antecedentes relevantes.', 'Sin cambios recientes.', 'Consulta de rutina.');

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
(1, 1, 'Consulta general', '2026-11-10 12:01:04', 'Finalizada', 'Paciente estable', '2026-11-19', '2026-11-10', 'Control de rutina.'),
(2, 2, 'Consulta equina', '2026-11-12 12:31:04', 'Pendiente', NULL, NULL, NULL, 'Evaluación locomotora de ejemplo.'),
(3, 3, 'Control', '2026-11-13 12:01:04', 'Pendiente', NULL, NULL, NULL, 'Control general de ejemplo.');

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
(1, 1, 'Definitivo', 'Paciente clínicamente estable.');

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
(2, 'Canino'),
(1, 'Equino'),
(3, 'Felino');

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
(1, 1, '24 kg', '5/9', '38.5 °C', '96 lpm', '22 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones', 'Rosadas', 'Normales', 'Sin actitudes anómalas', 'Normal', 'Sin alteraciones', 'Sin alteraciones aparentes');

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
(1, 1, 'Examen clínico general de rutina.', 'Sin hallazgos relevantes.');

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
(1, 2, NULL, 2, '2026-11-12 10:01:04', '2026-11-15 15:40:33', 'Observación clínica de demostración.', 'Finalizada');

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
(1, 1, 17, 'UY-DEMO-001', 'Nala', '2021-04-15', 5, 'Hembra', 'Marrón', NULL, '2026-11-12 15:01:04', '2026-11-12 15:01:04'),
(2, 2, 1, 'UY-DEMO-002', 'Relámpago', '2018-03-09', 8, 'Macho castrado', 'Zaino', NULL, '2026-11-12 15:01:04', '2026-11-12 15:01:04'),
(3, 2, 21, 'UY-DEMO-003', 'Milo', '2022-11-02', 4, 'Macho', 'Gris', NULL, '2026-11-12 15:01:04', '2026-11-12 15:01:04');

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
(1, 1, 'Evaluación clínica', 'Sin estudios adicionales indicados.', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `PRONOSTICO`
--

CREATE TABLE `PRONOSTICO` (
  `id_Pronostico` int(11) NOT NULL,
  `id_Consulta` int(11) DEFAULT NULL,
  `tipo_Pronostico` varchar(80) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `PRONOSTICO`
--

INSERT INTO `PRONOSTICO` (`id_Pronostico`, `id_Consulta`, `tipo_Pronostico`) VALUES
(1, 1, 'Favorable');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RAZA`
--

CREATE TABLE `RAZA` (
  `id_Raza` int(11) NOT NULL,
  `id_Especie` int(11) NOT NULL,
  `nombre_Raza` varchar(130) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `RAZA`
--

INSERT INTO `RAZA` (`id_Raza`, `id_Especie`, `nombre_Raza`) VALUES
(1, 1, 'Sin especificar'),
(17, 2, 'Mestizo'),
(21, 3, 'Mestizo');

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
(1, 2, 'Consulta clínica', 'Evaluación integral de salud, diagnóstico preciso de síntomas y planes de medicina preventiva.', 1800.00, '45 minutos', '/activos/imgs/servicios/18c951dce678053a4e183d3a9110773d.jpg', NULL, '2026-11-12 15:01:04', '2026-11-13 22:18:36'),
(2, 2, 'Control', 'Seguimiento médico de tratamientos, evolución postoperatoria y monitoreo de pacientes crónicos.', 1000.00, '30 minutos', '/activos/imgs/servicios/5980b7843bc04dba5f7b9f62caa6ddaf.jpg', NULL, '2026-11-12 15:01:04', '2026-11-13 22:19:05'),
(3, 1, 'Traumatología y claudicaciones equinas', 'Diagnóstico de rengueras, evaluación del aparato locomotor y terapias de rehabilitación deportiva.', 2500.00, '30 Minutos', '/activos/imgs/servicios/a5d3b7b05bf5bd7ef399a0ed9387a26b.jpg', NULL, '2026-11-13 22:22:38', '2026-11-13 22:31:49'),
(4, 1, 'Consulta Clínica General', 'Evaluación física completa de la mascota (revisión de temperatura, auscultación cardíaca y pulmonar, control de peso, piel, oídos y dentadura) para detectar afecciones de forma temprana o realizar seguimiento de salud.', 1200.00, '30 Minutos', '/activos/imgs/servicios/4936f0e1a754f39b82d21662d3dcec97.jpg', NULL, '2026-11-13 22:30:55', '2026-11-13 22:30:55'),
(5, 1, 'Plan de Vacunación y Desparasitación', 'Aplicación de vacunas esenciales según la edad y especie (quíntuple, séxtuple, triple felina o antirrábica) acompañada de desparasitación interna y externa, con entrega o actualización de carné sanitario.', 1650.00, '20 Minutos', '/activos/imgs/servicios/3a8410e37c77cc773a0a15e34296da51.jpg', NULL, '2026-11-13 22:33:23', '2026-11-13 22:33:23'),
(6, 1, 'Baño Sanitario y Peluquería Canina', 'Baño con shampoo dermatológico hipoalergénico, secado, cepillado profundo para retirar pelo muerto, limpieza de oídos, corte de uñas y recorte higiénico o estético adaptado a la raza.', 1400.00, '1 hora y 30 minutos', '/activos/imgs/servicios/0eb5068b7dbb9a74d5a668c2c663970e.jpg', NULL, '2026-11-13 22:35:24', '2026-11-13 22:35:24');

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
(1, 1, 'Mantener plan sanitario habitual. Control segun indicacion profesional.');

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
(1, '22334455', 'Laura Benítez', '099123456', 'Av. Italia 1234', 'laura@example.com', 'Tutora de Nala.', '2026-11-12 15:01:04', '2026-11-12 15:01:04'),
(2, '87654321', 'Sergio Medina', '098765432', 'Camino Maldonado 456', 'sergio@example.com', 'Tutor de Relámpago y Milo.', '2026-11-12 15:01:04', '2026-11-12 15:01:04');

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
(1, '12345678', 'María', 'Rodríguez', 'maria.directora@example.com', '$2y$12$wFPUX1LQOjdWIPcjhLzuve1.7DAkrIwOgUt6TKa.PzedZ.UOUb1nS', 'Director', 'activo', '2026-11-15 16:36:43', '2026-11-15 19:36:43'),
(2, '53456789', 'Ana', 'Silva', 'ana.profesora@example.com', '$2y$12$wFPUX1LQOjdWIPcjhLzuve1.7DAkrIwOgUt6TKa.PzedZ.UOUb1nS', 'Profesor', 'activo', '2026-11-12 20:41:24', '2026-11-13 22:33:30'),
(3, '11111122', 'Carlos', 'González', 'carlos.estudiante@example.com', '$2y$12$wFPUX1LQOjdWIPcjhLzuve1.7DAkrIwOgUt6TKa.PzedZ.UOUb1nS', 'Estudiante', 'activo', NULL, '2026-11-12 23:26:41');

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
(2, 1),
(2, 2),
(2, 3),
(3, 1);

-- --------------------------------------------------------

--
-- Estructura Stand-in para la vista `vw_alertas_consultas_atrasadas`
-- (Véase abajo para la vista actual)
--
CREATE TABLE `vw_alertas_consultas_atrasadas` (
`id_usuario` int(11)
,`id_Consulta` int(11)
,`fecha_hora_Consulta` datetime
,`estado_proceso_Consulta` varchar(50)
,`tipo_Consulta` varchar(100)
,`motivo_Consulta` text
,`nombre_Paciente` varchar(100)
,`nombre_Tutor` varchar(150)
);

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
  ADD KEY `id_Paciente` (`id_Paciente`),
  ADD KEY `idx_consulta_fecha_hora` (`fecha_hora_Consulta`);

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
  ADD UNIQUE KEY `numchip_Paciente` (`numchip_Paciente`),
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
  MODIFY `id_Anamnesis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `CONSULTA`
--
ALTER TABLE `CONSULTA`
  MODIFY `id_Consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `DIAGNOSTICO`
--
ALTER TABLE `DIAGNOSTICO`
  MODIFY `id_Diagnostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `ESPECIE`
--
ALTER TABLE `ESPECIE`
  MODIFY `id_Especie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_EQUINO`
--
ALTER TABLE `EXAMEN_EQUINO`
  MODIFY `id_ExamenEq` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_GENERAL`
--
ALTER TABLE `EXAMEN_GENERAL`
  MODIFY `id_ExamenG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_PARTICULAR`
--
ALTER TABLE `EXAMEN_PARTICULAR`
  MODIFY `id_ExamenP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `HOSPITALIZACION`
--
ALTER TABLE `HOSPITALIZACION`
  MODIFY `id_Hospitalizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  MODIFY `id_Paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  MODIFY `id_Paraclinico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `PRONOSTICO`
--
ALTER TABLE `PRONOSTICO`
  MODIFY `id_Pronostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `RAZA`
--
ALTER TABLE `RAZA`
  MODIFY `id_Raza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT de la tabla `RESEÑA`
--
ALTER TABLE `RESEÑA`
  MODIFY `id_Reseña` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  MODIFY `id_Servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `TRATAMIENTO`
--
ALTER TABLE `TRATAMIENTO`
  MODIFY `id_Tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `TUTOR`
--
ALTER TABLE `TUTOR`
  MODIFY `id_Tutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  MODIFY `id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

-- --------------------------------------------------------

--
-- Estructura para la vista `vw_alertas_consultas_atrasadas`
--
DROP TABLE IF EXISTS `vw_alertas_consultas_atrasadas`;

CREATE ALGORITHM=UNDEFINED DEFINER=`root`@`localhost` SQL SECURITY DEFINER VIEW `vw_alertas_consultas_atrasadas`  AS SELECT `uc`.`id_Usuario` AS `id_usuario`, `c`.`id_Consulta` AS `id_Consulta`, `c`.`fecha_hora_Consulta` AS `fecha_hora_Consulta`, `c`.`estado_proceso_Consulta` AS `estado_proceso_Consulta`, `c`.`tipo_Consulta` AS `tipo_Consulta`, `c`.`motivo_Consulta` AS `motivo_Consulta`, `p`.`nombre_Paciente` AS `nombre_Paciente`, `t`.`nombre_Tutor` AS `nombre_Tutor` FROM (((`USUARIO_CONSULTA` `uc` join `CONSULTA` `c` on(`c`.`id_Consulta` = `uc`.`id_Consulta`)) join `PACIENTE` `p` on(`p`.`id_Paciente` = `c`.`id_Paciente`)) join `TUTOR` `t` on(`t`.`id_Tutor` = `p`.`id_Tutor`)) WHERE `c`.`fecha_hora_Consulta` < current_timestamp() AND lcase(trim(`c`.`estado_proceso_Consulta`)) in ('pendiente','en curso') ;

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
