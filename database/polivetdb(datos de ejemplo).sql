-- phpMyAdmin SQL Dump
-- version 5.2.1deb1+deb12u1
-- https://www.phpmyadmin.net/
--
-- Servidor: localhost:3306
-- Tiempo de generación: 02-09-2026 a las 22:37:08
-- Versión del servidor: 10.11.18-MariaDB-0+deb12u1
-- Versión de PHP: 8.2.32

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
CREATE DATABASE IF NOT EXISTS `policlinico_db` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_general_ci;
USE `policlinico_db`;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `anamnesis`
--

CREATE TABLE `anamnesis` (
  `id_anamnesis` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL,
  `motivo_anamnesis` varchar(500) NOT NULL,
  `sintomas_anamnesis` varchar(500) NOT NULL,
  `habitat_anamnesis` varchar(500) NOT NULL,
  `alimentación_anamnesis` varchar(500) NOT NULL,
  `plan_sanitario_anamnesis` varchar(500) NOT NULL,
  `datecreate_anamnesis` datetime NOT NULL,
  `dateupdate_anamnesis` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `consulta`
--

CREATE TABLE `consulta` (
  `id_consulta` int(11) NOT NULL,
  `id_paciente` int(11) NOT NULL,
  `id_anamnesis` int(11) NOT NULL,
  `tipo_consulta` varchar(25) NOT NULL,
  `estado_consulta` varchar(9) NOT NULL,
  `fecha_hora_consulta` datetime NOT NULL,
  `fecha_proximo_control` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `paciente`
--

CREATE TABLE `paciente` (
  `id_paciente` int(11) NOT NULL,
  `id_tutor` int(11) NOT NULL,
  `nombre_paciente` varchar(50) NOT NULL,
  `color_paciente` varchar(50) NOT NULL,
  `foto_paciente` varchar(2048) NOT NULL,
  `fecha_nac_paciente` date NOT NULL,
  `sexo_paciente` varchar(6) NOT NULL,
  `peso_paciente` float(6,2) NOT NULL,
  `esterilizado_paciente` varchar(2) NOT NULL,
  `datecreate_paciente` datetime NOT NULL,
  `dateupdate_paciente` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `paciente`
--

INSERT INTO `paciente` (`id_paciente`, `id_tutor`, `nombre_paciente`, `color_paciente`, `foto_paciente`, `fecha_nac_paciente`, `sexo_paciente`, `peso_paciente`, `esterilizado_paciente`, `datecreate_paciente`, `dateupdate_paciente`) VALUES
(1, 2, 'Bondiola', 'Marrón', 'wadasw', '2026-08-17', 'Macho', 100.50, 'No', '2026-09-01 20:54:37', '2026-09-01 20:54:37'),
(2, 1, 'Juancho', 'Blanco', 'awsaw', '2025-07-02', 'Macho', 7.50, 'Si', '2026-09-01 23:54:37', '2026-09-01 23:54:37'),
(3, 1, 'Trapecia', 'Negro', 'wadsdw', '2023-09-15', 'Hembra', 10.70, 'Si', '2026-09-01 21:01:58', '2026-09-01 21:01:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `servicio`
--

CREATE TABLE `servicio` (
  `id_servicio` int(11) NOT NULL,
  `id_usuario` int(11) NOT NULL,
  `nombre_servicio` varchar(30) NOT NULL,
  `descripcion_servicio` varchar(500) NOT NULL,
  `precio_servicio` float(7,2) NOT NULL,
  `imagenURL_servicio` varchar(2048) NOT NULL,
  `iconoURL_servicio` varchar(2048) NOT NULL,
  `datecreate_servicio` datetime NOT NULL,
  `dateupdate_servicio` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `servicio`
--

INSERT INTO `servicio` (`id_servicio`, `id_usuario`, `nombre_servicio`, `descripcion_servicio`, `precio_servicio`, `imagenURL_servicio`, `iconoURL_servicio`, `datecreate_servicio`, `dateupdate_servicio`) VALUES
(1, 1, 'Consulta', 'Se realiza una consulta médica al animal', 999.99, 'awsdasaws', 'wasdwa', '2026-09-02 21:21:58', '2026-09-02 21:21:58');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tutor`
--

CREATE TABLE `tutor` (
  `id_tutor` int(11) NOT NULL,
  `documento_tutor` int(8) NOT NULL,
  `nombre_tutor` varchar(50) NOT NULL,
  `apellido_tutor` varchar(50) NOT NULL,
  `telefono_tutor` varchar(16) NOT NULL,
  `dirección_tutor` varchar(50) NOT NULL,
  `email_tutor` varchar(320) NOT NULL,
  `notas_tutor` varchar(500) DEFAULT NULL,
  `datecreate_tutor` datetime NOT NULL,
  `dateupdate_tutor` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `tutor`
--

INSERT INTO `tutor` (`id_tutor`, `documento_tutor`, `nombre_tutor`, `apellido_tutor`, `telefono_tutor`, `dirección_tutor`, `email_tutor`, `notas_tutor`, `datecreate_tutor`, `dateupdate_tutor`) VALUES
(1, 73268451, 'Martina', 'Barboza', '+59891234567', 'En su casa', 'martina@gmail.com', NULL, '2026-09-01 20:46:33', '2026-09-01 20:46:33'),
(2, 10007818, 'Andrés', 'Ferreira', '+59812345678', 'En otra casa', 'andrés@email.com', NULL, '2026-09-01 21:46:33', '2026-09-01 21:46:33');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id_usuario` int(11) NOT NULL,
  `cedula_usuario` int(8) NOT NULL,
  `nombre_usuario` varchar(50) NOT NULL,
  `apellido_usuario` varchar(50) NOT NULL,
  `rol_usuario` varchar(11) NOT NULL,
  `email_usuario` varchar(320) NOT NULL,
  `passwd_usuario` varchar(256) NOT NULL,
  `lastlogin_usuario` datetime NOT NULL,
  `dateupdate_usuario` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id_usuario`, `cedula_usuario`, `nombre_usuario`, `apellido_usuario`, `rol_usuario`, `email_usuario`, `passwd_usuario`, `lastlogin_usuario`, `dateupdate_usuario`) VALUES
(1, 23885203, 'Hector', 'Dir', 'director', 'dirhector@polivet.com', '$2y$10$7oZTaVgRnCUWre82w1JE0usixwMRZhuXBwJB.LdnvK78JH4S581H.', '2026-09-01 20:17:55', '2026-09-01 20:17:55'),
(2, 97454155, 'Roberta', 'Hernandez', 'Estudiante', 'robertah@polivet.com', '$2y$10$L3pa1ANJtX0Bzf6pm2PXWOdmAfDNwrSqiXSnxeT/lBhBdx8aTlNbi', '2026-09-01 23:17:55', '2026-09-01 23:17:55');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_consulta`
--

CREATE TABLE `usuario_consulta` (
  `id_usuario` int(11) NOT NULL,
  `id_consulta` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  ADD PRIMARY KEY (`id_anamnesis`),
  ADD KEY `fk_anamnesis_consulta` (`id_consulta`);

--
-- Indices de la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD PRIMARY KEY (`id_consulta`),
  ADD KEY `fk_consulta_paciente` (`id_paciente`),
  ADD KEY `fk_consulta_anamnesis` (`id_anamnesis`);

--
-- Indices de la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD PRIMARY KEY (`id_paciente`),
  ADD KEY `fk_paciente_tutor` (`id_tutor`);

--
-- Indices de la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD PRIMARY KEY (`id_servicio`),
  ADD KEY `servicio_usuario` (`id_usuario`);

--
-- Indices de la tabla `tutor`
--
ALTER TABLE `tutor`
  ADD PRIMARY KEY (`id_tutor`),
  ADD UNIQUE KEY `documento_tutor` (`documento_tutor`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id_usuario`),
  ADD UNIQUE KEY `cedula_usuario` (`cedula_usuario`);

--
-- Indices de la tabla `usuario_consulta`
--
ALTER TABLE `usuario_consulta`
  ADD PRIMARY KEY (`id_usuario`,`id_consulta`),
  ADD KEY `fk_usuario_id_consulta` (`id_consulta`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  MODIFY `id_anamnesis` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `consulta`
--
ALTER TABLE `consulta`
  MODIFY `id_consulta` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `paciente`
--
ALTER TABLE `paciente`
  MODIFY `id_paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `servicio`
--
ALTER TABLE `servicio`
  MODIFY `id_servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `tutor`
--
ALTER TABLE `tutor`
  MODIFY `id_tutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id_usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `anamnesis`
--
ALTER TABLE `anamnesis`
  ADD CONSTRAINT `fk_anamnesis_consulta` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`);

--
-- Filtros para la tabla `consulta`
--
ALTER TABLE `consulta`
  ADD CONSTRAINT `fk_consulta_anamnesis` FOREIGN KEY (`id_anamnesis`) REFERENCES `anamnesis` (`id_anamnesis`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_consulta_paciente` FOREIGN KEY (`id_paciente`) REFERENCES `paciente` (`id_paciente`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `paciente`
--
ALTER TABLE `paciente`
  ADD CONSTRAINT `fk_paciente_tutor` FOREIGN KEY (`id_tutor`) REFERENCES `tutor` (`id_tutor`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Filtros para la tabla `servicio`
--
ALTER TABLE `servicio`
  ADD CONSTRAINT `servicio_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`);

--
-- Filtros para la tabla `usuario_consulta`
--
ALTER TABLE `usuario_consulta`
  ADD CONSTRAINT `fk_id_usuario_consulta` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id_usuario`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_usuario_id_consulta` FOREIGN KEY (`id_consulta`) REFERENCES `consulta` (`id_consulta`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
