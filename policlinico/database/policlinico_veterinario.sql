-- --------------------------------------------------------
-- Base de datos: `policlinico_veterinario`
-- --------------------------------------------------------

-- --------------------------------------------------------
-- Estructura y datos para el Policlínico Veterinario
-- --------------------------------------------------------

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

-- --------------------------------------------------------
-- Crear la base de datos si no existe
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS `policlinico_veterinario` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `policlinico_veterinario`;

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `usuarios`
-- --------------------------------------------------------

CREATE TABLE `usuarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `rol` enum('admin','vet','assistant','receptionist') NOT NULL DEFAULT 'assistant',
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  `ultimo_login` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `usuarios`
-- --------------------------------------------------------

INSERT INTO `usuarios` (`id`, `username`, `password`, `nombre_completo`, `email`, `rol`, `activo`) VALUES
(1, 'admin', '$2y$10$qT8JfE.wj3qJvV0EJ4gIK.nUdKjglsJoYlptmZxTaZCH9Ea/P2bZq', 'Administrador Sistema', 'admin@policlinicoveterinario.edu.uy', 'admin', 1),
(2, 'drlopez', '$2y$10$qT8JfE.wj3qJvV0EJ4gIK.nUdKjglsJoYlptmZxTaZCH9Ea/P2bZq', 'Dr. Carlos López', 'clopez@policlinicoveterinario.edu.uy', 'vet', 1),
(3, 'drasilva', '$2y$10$qT8JfE.wj3qJvV0EJ4gIK.nUdKjglsJoYlptmZxTaZCH9Ea/P2bZq', 'Dra. Ana Silva', 'asilva@policlinicoveterinario.edu.uy', 'vet', 1),
(4, 'mrodriguez', '$2y$10$qT8JfE.wj3qJvV0EJ4gIK.nUdKjglsJoYlptmZxTaZCH9Ea/P2bZq', 'María Rodríguez', 'mrodriguez@policlinicoveterinario.edu.uy', 'receptionist', 1),
(5, 'pgonzalez', '$2y$10$qT8JfE.wj3qJvV0EJ4gIK.nUdKjglsJoYlptmZxTaZCH9Ea/P2bZq', 'Pablo González', 'pgonzalez@policlinicoveterinario.edu.uy', 'assistant', 1);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `personal`
-- --------------------------------------------------------

CREATE TABLE `personal` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `especialidad` varchar(100) DEFAULT NULL,
  `bio` text DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `horario` text DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `personal_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `personal`
-- --------------------------------------------------------

INSERT INTO `personal` (`id`, `usuario_id`, `especialidad`, `bio`, `telefono`, `foto`, `horario`, `activo`) VALUES
(1, 2, 'Medicina General y Cirugía', 'El Dr. Carlos López es un veterinario con más de 15 años de experiencia en medicina y cirugía de pequeños animales. Se especializa en ortopedia y traumatología veterinaria.', '598-4733-1259', 'assets/images/staff/dr-lopez.jpg', 'Lunes a Viernes: 9:00 - 17:00', 1),
(2, 3, 'Diagnóstico por Imágenes', 'La Dra. Ana Silva es especialista en diagnóstico por imágenes. Cuenta con formación avanzada en ecografía y radiología veterinaria, con particular interés en cardiopatías de pequeños animales.', '598-4733-1260', 'assets/images/staff/dra-silva.jpg', 'Martes, Jueves y Viernes: 10:00 - 18:00', 1),
(3, 4, 'Recepción y Atención al Cliente', 'María Rodríguez es la cara visible del Policlínico, encargada de la recepción y coordinación de citas. Su amabilidad y eficiencia son fundamentales para el buen funcionamiento del centro.', '598-4733-1258', 'assets/images/staff/maria-rodriguez.jpg', 'Lunes a Viernes: 8:00 - 16:00', 1),
(4, 5, 'Asistente Veterinario', 'Pablo González es asistente veterinario con formación en enfermería veterinaria. Apoya a los profesionales en consultas, cirugías y cuidados postoperatorios.', '598-4733-1261', 'assets/images/staff/pablo-gonzalez.jpg', 'Lunes a Viernes: 9:00 - 17:00', 1);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `propietarios`
-- --------------------------------------------------------

CREATE TABLE `propietarios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_completo` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) NOT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `ciudad` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `documento` varchar(20) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `propietarios`
-- --------------------------------------------------------

INSERT INTO `propietarios` (`id`, `nombre_completo`, `email`, `telefono`, `direccion`, `ciudad`, `documento`, `notas`) VALUES
(1, 'Juan Pérez', 'jperez@example.com', '598-99123456', 'Av. Uruguay 1234', 'Salto', '12345678', 'Cliente frecuente'),
(2, 'Laura Martínez', 'lmartinez@example.com', '598-99234567', 'Calle Rivera 567', 'Salto', '23456789', 'Propietaria de múltiples mascotas'),
(3, 'Roberto Fernández', 'rfernandez@example.com', '598-99345678', 'Av. Blandengues 890', 'Salto', '34567890', 'Nuevo cliente'),
(4, 'Carmen Rodríguez', 'crodriguez@example.com', '598-99456789', 'Calle Brasil 432', 'Salto', '45678901', 'Prefiere citas por la tarde');

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `mascotas`
-- --------------------------------------------------------

CREATE TABLE `mascotas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `propietario_id` int(11) NOT NULL,
  `nombre` varchar(50) NOT NULL,
  `especie` varchar(50) NOT NULL,
  `raza` varchar(50) DEFAULT NULL,
  `sexo` enum('M','H') NOT NULL,
  `color` varchar(50) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `esterilizado` tinyint(1) DEFAULT 0,
  `microchip` varchar(50) DEFAULT NULL,
  `foto` varchar(255) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `propietario_id` (`propietario_id`),
  CONSTRAINT `mascotas_ibfk_1` FOREIGN KEY (`propietario_id`) REFERENCES `propietarios` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `mascotas`
-- --------------------------------------------------------

INSERT INTO `mascotas` (`id`, `propietario_id`, `nombre`, `especie`, `raza`, `sexo`, `color`, `fecha_nacimiento`, `peso`, `esterilizado`, `microchip`, `foto`, `notas`) VALUES
(1, 1, 'Rocky', 'Perro', 'Labrador', 'M', 'Dorado', '2019-05-15', 28.50, 1, '123456789012345', 'assets/images/pets/rocky.jpg', 'Alérgico a algunos antibióticos'),
(2, 2, 'Luna', 'Gato', 'Siamés', 'H', 'Blanco y Marrón', '2020-03-10', 4.20, 1, '234567890123456', 'assets/images/pets/luna.jpg', 'Muy activa y juguetona'),
(3, 2, 'Max', 'Perro', 'Bulldog Francés', 'M', 'Gris', '2018-07-22', 12.30, 0, '345678901234567', 'assets/images/pets/max.jpg', 'Problemas respiratorios ocasionales'),
(4, 3, 'Simba', 'Gato', 'Común Europeo', 'M', 'Naranja', '2021-01-05', 3.80, 0, NULL, 'assets/images/pets/simba.jpg', 'Primera visita realizada'),
(5, 4, 'Toby', 'Perro', 'Beagle', 'M', 'Tricolor', '2017-11-30', 15.70, 1, '456789012345678', 'assets/images/pets/toby.jpg', 'Controlado por sobrepeso'),
(6, 4, 'Mía', 'Perro', 'Caniche', 'H', 'Blanco', '2020-09-12', 5.10, 1, '567890123456789', 'assets/images/pets/mia.jpg', 'Vacunación al día');

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `servicios`
-- --------------------------------------------------------

CREATE TABLE `servicios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `descripcion` text DEFAULT NULL,
  `precio` decimal(10,2) DEFAULT NULL,
  `duracion` int(11) DEFAULT NULL COMMENT 'Duración en minutos',
  `icono` varchar(50) DEFAULT 'fa-stethoscope',
  `imagen` varchar(255) DEFAULT NULL,
  `activo` tinyint(1) NOT NULL DEFAULT 1,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `servicios`
-- --------------------------------------------------------

INSERT INTO `servicios` (`id`, `nombre`, `descripcion`, `precio`, `duracion`, `icono`, `imagen`, `activo`) VALUES
(1, 'Consulta General', 'Evaluación completa del estado de salud de su mascota, incluyendo examen físico, control de peso, revisión de vacunas y desparasitaciones.', 800.00, 30, 'fa-stethoscope', 'assets/images/servicios/consulta.jpg', 1),
(2, 'Vacunación', 'Administración de vacunas esenciales y no esenciales según la edad, especie y estilo de vida de su mascota, siguiendo los protocolos recomendados.', 600.00, 20, 'fa-syringe', 'assets/images/servicios/vacunacion.jpg', 1),
(3, 'Cirugía Menor', 'Procedimientos quirúrgicos menores como esterilizaciones, castraciones, extracciones de tumores superficiales y otros procedimientos que no requieren hospitalización prolongada.', 2500.00, 60, 'fa-cut', 'assets/images/servicios/cirugia.jpg', 1),
(4, 'Análisis de Laboratorio', 'Servicios completos de laboratorio clínico incluyendo hemogramas, bioquímica sanguínea, análisis de orina, coprológicos y citologías.', 1200.00, 45, 'fa-microscope', 'assets/images/servicios/laboratorio.jpg', 1),
(5, 'Limpieza Dental', 'Procedimiento para mantener la salud bucal de su mascota, incluye remoción de sarro, pulido dental y evaluación de la cavidad oral.', 1800.00, 50, 'fa-tooth', 'assets/images/servicios/dental.jpg', 1),
(6, 'Ecografía', 'Diagnóstico por imágenes mediante ultrasonido para evaluar órganos internos y detectar posibles anomalías.', 1500.00, 40, 'fa-wave-square', 'assets/images/servicios/ecografia.jpg', 1);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `citas`
-- --------------------------------------------------------

CREATE TABLE `citas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mascota_id` int(11) NOT NULL,
  `servicio_id` int(11) NOT NULL,
  `veterinario_id` int(11) DEFAULT NULL,
  `fecha` date NOT NULL,
  `hora` time NOT NULL,
  `motivo` text DEFAULT NULL,
  `estado` enum('pendiente','completada','cancelada') NOT NULL DEFAULT 'pendiente',
  `notas` text DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `mascota_id` (`mascota_id`),
  KEY `servicio_id` (`servicio_id`),
  KEY `veterinario_id` (`veterinario_id`),
  CONSTRAINT `citas_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `citas_ibfk_2` FOREIGN KEY (`servicio_id`) REFERENCES `servicios` (`id`),
  CONSTRAINT `citas_ibfk_3` FOREIGN KEY (`veterinario_id`) REFERENCES `personal` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `citas`
-- --------------------------------------------------------

INSERT INTO `citas` (`id`, `mascota_id`, `servicio_id`, `veterinario_id`, `fecha`, `hora`, `motivo`, `estado`, `notas`) VALUES
(1, 1, 1, 1, CURDATE(), '10:00:00', 'Control de rutina', 'pendiente', 'Primera visita del año'),
(2, 2, 2, 2, CURDATE(), '11:30:00', 'Vacunación anual', 'pendiente', 'Traer cartilla de vacunación'),
(3, 3, 4, 1, CURDATE(), '15:00:00', 'Análisis pre-quirúrgico', 'pendiente', 'Ayuno de 8 horas'),
(4, 5, 1, 2, DATE_ADD(CURDATE(), INTERVAL 1 DAY), '09:30:00', 'Problemas digestivos', 'pendiente', 'Ha presentado vómitos'),
(5, 4, 6, 2, DATE_ADD(CURDATE(), INTERVAL 2 DAY), '14:00:00', 'Control abdominal', 'pendiente', 'Seguimiento por tratamiento anterior'),
(6, 6, 5, 1, DATE_ADD(CURDATE(), INTERVAL 3 DAY), '16:30:00', 'Limpieza dental rutinaria', 'pendiente', 'Anestesia general requerida');

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `registros_medicos`
-- --------------------------------------------------------

CREATE TABLE `registros_medicos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mascota_id` int(11) NOT NULL,
  `veterinario_id` int(11) NOT NULL,
  `fecha` date NOT NULL,
  `tipo` enum('consulta','vacunacion','cirugia','analisis','tratamiento','otro') NOT NULL DEFAULT 'consulta',
  `motivo` text NOT NULL,
  `peso` decimal(5,2) DEFAULT NULL,
  `temperatura` decimal(3,1) DEFAULT NULL,
  `diagnostico` text DEFAULT NULL,
  `tratamiento` text DEFAULT NULL,
  `observaciones` text DEFAULT NULL,
  `proxima_visita` date DEFAULT NULL,
  `cita_id` int(11) DEFAULT NULL,
  `archivos` text DEFAULT NULL COMMENT 'URLs separadas por comas de archivos adjuntos',
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `mascota_id` (`mascota_id`),
  KEY `veterinario_id` (`veterinario_id`),
  KEY `cita_id` (`cita_id`),
  CONSTRAINT `registros_medicos_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `registros_medicos_ibfk_2` FOREIGN KEY (`veterinario_id`) REFERENCES `personal` (`id`),
  CONSTRAINT `registros_medicos_ibfk_3` FOREIGN KEY (`cita_id`) REFERENCES `citas` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `registros_medicos`
-- --------------------------------------------------------

INSERT INTO `registros_medicos` (`id`, `mascota_id`, `veterinario_id`, `fecha`, `tipo`, `motivo`, `peso`, `temperatura`, `diagnostico`, `tratamiento`, `observaciones`, `proxima_visita`, `cita_id`) VALUES
(1, 1, 1, DATE_SUB(CURDATE(), INTERVAL 6 MONTH), 'consulta', 'Control de rutina', 27.80, 38.5, 'Animal en buen estado general', 'Ninguno necesario', 'Se recomienda continuar con la dieta actual', DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), INTERVAL 6 MONTH), NULL),
(2, 2, 2, DATE_SUB(CURDATE(), INTERVAL 3 MONTH), 'vacunacion', 'Vacunación anual', 4.10, 38.7, 'Sin hallazgos patológicos', 'Administración de vacuna triple felina', 'Reacción normal post-vacunación', DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), INTERVAL 1 YEAR), NULL),
(3, 3, 1, DATE_SUB(CURDATE(), INTERVAL 2 MONTH), 'consulta', 'Problemas respiratorios', 12.50, 39.2, 'Síndrome braquicefálico', 'Antiinflamatorios: Prednisolona 0.5mg/kg/12h durante 5 días', 'Evaluar evolución para posible cirugía correctiva', DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), INTERVAL 1 MONTH), NULL),
(4, 5, 2, DATE_SUB(CURDATE(), INTERVAL 1 MONTH), 'analisis', 'Control de rutina geriátrico', 16.10, 38.6, 'Leve elevación de enzimas hepáticas', 'Suplemento hepatoprotector: 1 comprimido cada 24h durante 30 días', 'Repetir análisis en próxima visita', DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), INTERVAL 1 MONTH), NULL),
(5, 4, 1, DATE_SUB(CURDATE(), INTERVAL 2 WEEK), 'consulta', 'Primera revisión', 3.60, 38.4, 'Animal sano', 'Plan de vacunación y desparasitación', 'Iniciar socialización progresiva', DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 WEEK), INTERVAL 1 MONTH), NULL);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `vacunas`
-- --------------------------------------------------------

CREATE TABLE `vacunas` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mascota_id` int(11) NOT NULL,
  `tipo_vacuna` varchar(100) NOT NULL,
  `fecha_aplicacion` date NOT NULL,
  `fecha_proxima` date DEFAULT NULL,
  `lote` varchar(50) DEFAULT NULL,
  `veterinario_id` int(11) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `registro_medico_id` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `mascota_id` (`mascota_id`),
  KEY `veterinario_id` (`veterinario_id`),
  KEY `registro_medico_id` (`registro_medico_id`),
  CONSTRAINT `vacunas_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `vacunas_ibfk_2` FOREIGN KEY (`veterinario_id`) REFERENCES `personal` (`id`),
  CONSTRAINT `vacunas_ibfk_3` FOREIGN KEY (`registro_medico_id`) REFERENCES `registros_medicos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `vacunas`
-- --------------------------------------------------------

INSERT INTO `vacunas` (`id`, `mascota_id`, `tipo_vacuna`, `fecha_aplicacion`, `fecha_proxima`, `lote`, `veterinario_id`, `notas`, `registro_medico_id`) VALUES
(1, 1, 'Polivalente Canina', DATE_SUB(CURDATE(), INTERVAL 6 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 6 MONTH), INTERVAL 1 YEAR), 'VC2023-456', 1, 'Aplicación sin complicaciones', 1),
(2, 2, 'Triple Felina', DATE_SUB(CURDATE(), INTERVAL 3 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), INTERVAL 1 YEAR), 'TF2023-789', 2, 'Aplicación sin complicaciones', 2),
(3, 3, 'Polivalente Canina', DATE_SUB(CURDATE(), INTERVAL 9 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 9 MONTH), INTERVAL 1 YEAR), 'VC2022-987', 1, 'Aplicación sin complicaciones', NULL),
(4, 5, 'Antirrábica', DATE_SUB(CURDATE(), INTERVAL 7 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 7 MONTH), INTERVAL 1 YEAR), 'AR2023-654', 2, 'Aplicación sin complicaciones', NULL),
(5, 6, 'Polivalente Canina', DATE_SUB(CURDATE(), INTERVAL 4 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 4 MONTH), INTERVAL 1 YEAR), 'VC2023-321', 1, 'Aplicación sin complicaciones', NULL);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `desparasitaciones`
-- --------------------------------------------------------

CREATE TABLE `desparasitaciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `mascota_id` int(11) NOT NULL,
  `producto` varchar(100) NOT NULL,
  `tipo` enum('interna','externa','ambas') NOT NULL,
  `fecha_aplicacion` date NOT NULL,
  `fecha_proxima` date DEFAULT NULL,
  `dosis` varchar(50) DEFAULT NULL,
  `peso_aplicacion` decimal(5,2) DEFAULT NULL,
  `veterinario_id` int(11) DEFAULT NULL,
  `notas` text DEFAULT NULL,
  `registro_medico_id` int(11) DEFAULT NULL,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `mascota_id` (`mascota_id`),
  KEY `veterinario_id` (`veterinario_id`),
  KEY `registro_medico_id` (`registro_medico_id`),
  CONSTRAINT `desparasitaciones_ibfk_1` FOREIGN KEY (`mascota_id`) REFERENCES `mascotas` (`id`) ON DELETE CASCADE,
  CONSTRAINT `desparasitaciones_ibfk_2` FOREIGN KEY (`veterinario_id`) REFERENCES `personal` (`id`),
  CONSTRAINT `desparasitaciones_ibfk_3` FOREIGN KEY (`registro_medico_id`) REFERENCES `registros_medicos` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `desparasitaciones`
-- --------------------------------------------------------

INSERT INTO `desparasitaciones` (`id`, `mascota_id`, `producto`, `tipo`, `fecha_aplicacion`, `fecha_proxima`, `dosis`, `peso_aplicacion`, `veterinario_id`, `notas`, `registro_medico_id`) VALUES
(1, 1, 'NexGard', 'externa', DATE_SUB(CURDATE(), INTERVAL 1 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 1 MONTH), INTERVAL 3 MONTH), '1 comprimido (25-50kg)', 28.50, 1, 'Aplicación sin complicaciones', NULL),
(2, 2, 'Advocate', 'ambas', DATE_SUB(CURDATE(), INTERVAL 2 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), INTERVAL 3 MONTH), '0.4ml (2-4kg)', 4.10, 2, 'Aplicación sin complicaciones', NULL),
(3, 3, 'Drontal Plus', 'interna', DATE_SUB(CURDATE(), INTERVAL 3 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 3 MONTH), INTERVAL 6 MONTH), '1 comprimido (10-15kg)', 12.30, 1, 'Aplicación sin complicaciones', NULL),
(4, 4, 'Broadline', 'ambas', DATE_SUB(CURDATE(), INTERVAL 2 WEEK), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 WEEK), INTERVAL 3 MONTH), '0.3ml (<4kg)', 3.60, 1, 'Primera desparasitación', 5),
(5, 5, 'NexGard Spectra', 'ambas', DATE_SUB(CURDATE(), INTERVAL 2 MONTH), DATE_ADD(DATE_SUB(CURDATE(), INTERVAL 2 MONTH), INTERVAL 3 MONTH), '1 comprimido (15-30kg)', 15.70, 2, 'Aplicación sin complicaciones', NULL);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `testimonios`
-- --------------------------------------------------------

CREATE TABLE `testimonios` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `propietario_id` int(11) DEFAULT NULL,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) DEFAULT NULL,
  `comentario` text NOT NULL,
  `calificacion` int(11) NOT NULL DEFAULT 5,
  `fecha` date NOT NULL,
  `aprobado` tinyint(1) NOT NULL DEFAULT 0,
  `fecha_creacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `propietario_id` (`propietario_id`),
  CONSTRAINT `testimonios_ibfk_1` FOREIGN KEY (`propietario_id`) REFERENCES `propietarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `testimonios`
-- --------------------------------------------------------

INSERT INTO `testimonios` (`id`, `propietario_id`, `nombre`, `email`, `comentario`, `calificacion`, `fecha`, `aprobado`) VALUES
(1, 1, 'Juan Pérez', 'jperez@example.com', 'Excelente atención para mi perro Rocky. El Dr. López es un profesional excepcional y muy dedicado. Recomiendo ampliamente el Policlínico Veterinario.', 5, DATE_SUB(CURDATE(), INTERVAL 2 MONTH), 1),
(2, 2, 'Laura Martínez', 'lmartinez@example.com', 'Mis mascotas siempre reciben el mejor cuidado. El personal es amable y las instalaciones están impecables. La Dra. Silva realizó una ecografía a mi gata Luna con mucha precisión y profesionalismo.', 5, DATE_SUB(CURDATE(), INTERVAL 1 MONTH), 1),
(3, 4, 'Carmen Rodríguez', 'crodriguez@example.com', 'Muy conforme con la atención recibida. Mi perrita Mía tuvo una limpieza dental y quedó perfecta. Los precios son razonables para la calidad del servicio.', 4, DATE_SUB(CURDATE(), INTERVAL 2 WEEK), 1),
(4, NULL, 'Miguel Torres', 'mtorres@example.com', 'Primera vez que visito la clínica y quedé muy satisfecho. Atención rápida y profesional. Sin duda volveré para el seguimiento de mi mascota.', 5, DATE_SUB(CURDATE(), INTERVAL 1 WEEK), 1);

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `contactos`
-- --------------------------------------------------------

CREATE TABLE `contactos` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `nombre` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `asunto` varchar(100) NOT NULL,
  `mensaje` text NOT NULL,
  `fecha` datetime NOT NULL DEFAULT current_timestamp(),
  `estado` enum('pendiente','respondido','archivado') NOT NULL DEFAULT 'pendiente',
  `respuesta` text DEFAULT NULL,
  `fecha_respuesta` datetime DEFAULT NULL,
  `usuario_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `contactos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `contactos`
-- --------------------------------------------------------

INSERT INTO `contactos` (`id`, `nombre`, `email`, `telefono`, `asunto`, `mensaje`, `fecha`, `estado`) VALUES
(1, 'Elena Gómez', 'egomez@example.com', '598-99567890', 'Consulta sobre vacunación', 'Quisiera saber qué vacunas necesita mi cachorro de 3 meses y cuánto cuesta el plan de vacunación completo. Gracias.', DATE_SUB(CURDATE(), INTERVAL 3 DAY), 'pendiente'),
(2, 'Pedro Suárez', 'psuarez@example.com', '598-99678901', 'Disponibilidad de citas', '¿Tienen disponibilidad para una consulta de emergencia el día de hoy? Mi gato está decaído y no quiere comer.', DATE_SUB(CURDATE(), INTERVAL 2 DAY), 'respondido');

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `log_acciones`
-- --------------------------------------------------------

CREATE TABLE `log_acciones` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) DEFAULT NULL,
  `accion` varchar(50) NOT NULL,
  `detalles` text DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `fecha_hora` datetime NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  CONSTRAINT `log_acciones_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `log_acciones`
-- --------------------------------------------------------

INSERT INTO `log_acciones` (`id`, `usuario_id`, `accion`, `detalles`, `ip_address`) VALUES
(1, 1, 'login', 'Login exitoso - admin', '127.0.0.1'),
(2, 2, 'login', 'Login exitoso - drlopez', '127.0.0.1'),
(3, 3, 'login', 'Login exitoso - drasilva', '127.0.0.1');

-- --------------------------------------------------------
-- Estructura de tabla para la tabla `configuracion`
-- --------------------------------------------------------

CREATE TABLE `configuracion` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `clave` varchar(50) NOT NULL,
  `valor` text NOT NULL,
  `descripcion` text DEFAULT NULL,
  `tipo` enum('texto','numero','booleano','json','html') NOT NULL DEFAULT 'texto',
  `fecha_actualizacion` datetime DEFAULT NULL ON UPDATE current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------
-- Datos para la tabla `configuracion`
-- --------------------------------------------------------

INSERT INTO `configuracion` (`id`, `clave`, `valor`, `descripcion`, `tipo`) VALUES
(1, 'sitio_nombre', 'Policlínico Veterinario', 'Nombre del sitio web', 'texto'),
(2, 'sitio_descripcion', 'Centro de atención veterinaria de la Universidad de la República', 'Descripción corta del sitio', 'texto'),
(3, 'sitio_email', 'contacto@policlinicoveterinario.edu.uy', 'Email principal de contacto', 'texto'),
(4, 'sitio_telefono', '+(598) 4733-1258', 'Teléfono principal de contacto', 'texto'),
(5, 'sitio_direccion', 'Rivera 1350, Salto, Uruguay', 'Dirección física', 'texto'),
(6, 'horario_atencion', 'Lunes a Viernes: 8:00 - 18:00', 'Horario de atención', 'texto'),
(7, 'redes_sociales', '{"facebook":"https://www.facebook.com/policlinicoveterinario","instagram":"https://www.instagram.com/policlinicoveterinario","twitter":"https://twitter.com/policlinicoveterinario"}', 'URLs de redes sociales', 'json'),
(8, 'mostrar_testimonios', '1', 'Mostrar sección de testimonios en la página principal', 'booleano'),
(9, 'duracion_cita', '30', 'Duración predeterminada de las citas en minutos', 'numero'),
(10, 'mensaje_bienvenida', '<p>Bienvenido al <strong>Policlínico Veterinario</strong>, donde el cuidado de su mascota es nuestra prioridad.</p>', 'Mensaje de bienvenida en la página principal', 'html');

COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;