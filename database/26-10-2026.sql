-- phpMyAdmin SQL Dump
-- version 5.2.3
-- https://www.phpmyadmin.net/
--
-- Servidor: policlinico-db
-- Tiempo de generación: 26-10-2026 a las 22:25:32
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
(1, 3, 'Vacunación al día', 'Vive en departamento', 'Come normalmente', 'No presentó antecedentes', 'Se observa normal', 'No se reportan antecedentes'),
(2, 2, 'Vacunación al día', 'Se encuentra en establo', 'Pasa el día en pastoreo', 'No presenta alergias', 'Movimiento normal', 'Le cuesta levantar la pata'),
(3, 5, 'Vacunación al día', 'Vive en establo', 'Alimentación y actividad habituales', 'Sin antecedentes registrados', 'Movimiento con claudicación', 'Claudicación del miembro anterior'),
(4, 6, 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba'),
(5, 7, 'Vacunación y desparasitación referidas al día.', 'Vive en establo con acceso diario a potrero.', 'Apetito y consumo de agua habituales.', 'Sin antecedentes relevantes referidos.', 'Claudicación intermitente luego del ejercicio.', 'El tutor nota sensibilidad en el miembro anterior izquierdo desde hace tres días.'),
(6, 1, 'Vacunación y desparasitación al día', 'Vive en casa con patio', 'Alimentación balanceada y apetito normal', 'Sin antecedentes patológicos relevantes', 'Actividad y apetito normales', 'Sin signos clínicos; consulta de control de rutina'),
(7, 4, 'Vacunación al día', 'Vive en casa con patio', 'Apetito disminuido en las primeras horas', 'Sin antecedentes patológicos relevantes', 'Recuperación anestésica sin complicaciones', 'Cirugía realizada el mismo día; ingresa para observación y analgesia.'),
(8, 8, 'Vacunación al día; desparasitación hace cuatro meses', 'Vive en potrero con acceso a galpón', 'Pastura y ración; consumo de agua habitual', 'Sin episodios de cólico previos', 'Apetito disminuido durante la mañana', 'Inquietud, mira el flanco y se echa y levanta desde la madrugada'),
(9, 9, 'Vacunación contra influenza equina al día', 'Comparte establo con otros cuatro equinos', 'Alimentación y actividad habituales', 'Episodio similar el año anterior que resolvió solo', 'Apetito conservado; menor rendimiento al trabajo', 'Tos húmeda y secreción nasal bilateral desde hace cinco días'),
(10, 10, 'Vacunación y desparasitación al día', 'Box individual con salida diaria a pista', 'Ración y heno; entrenamiento cinco días por semana', 'Sin antecedentes relevantes referidos', 'Apetito normal; menor impulso en el galope', 'El entrenador refiere acortamiento del tranco en el miembro posterior derecho desde hace dos semanas'),
(11, 11, 'Vacunación y desparasitación al día', 'Box individual con salida diaria a pista', 'Ración y heno; trabajo reducido a paso y trote suave', 'Osteoartritis tarsiana leve diagnosticada en septiembre', 'Apetito normal; mejor tranco en los últimos días', 'Sin claudicación evidente según el entrenador'),
(12, 12, 'Vacunación al día; última desparasitación hace seis meses', 'Pastoreo extensivo en campo con aguada natural', 'Pasa el día en pastoreo; ración ocasional', 'Un episodio de cólico hace dos años', 'Sin ingesta en las últimas 12 horas', 'Dolor abdominal progresivo y sudoración desde la tarde'),
(13, 13, 'Vacunación antitetánica al día', 'Vive en potrero con alambrado', 'Alimentación y actividad habituales', 'Sin antecedentes relevantes', 'Apetito normal', 'Herida en el miembro posterior derecho detectada esta mañana; sin claudicación'),
(14, 14, 'Vacunación antitetánica al día', 'Vive en potrero con alambrado', 'Alimentación y actividad habituales', 'Herida cutánea en el miembro posterior derecho tratada en octubre', 'Apetito normal', 'Sin claudicación; herida en cicatrización según el tutor'),
(15, 15, 'Vacunación al día; desparasitación reciente', 'Box individual en centro hípico', 'Ración, heno y suplemento mineral; trabajo diario de salto', 'Sin antecedentes relevantes', 'Apetito y consumo de agua habituales', 'Sin signos clínicos; examen previo a competencia'),
(16, 16, 'Vacunación al día; desparasitación hace ocho meses', 'Potrero compartido con otros ponis', 'Pastoreo continuo; sin suplementos', 'Sin antecedentes relevantes', 'Apetito conservado', 'Pérdida de peso progresiva y pelaje opaco en los últimos dos meses'),
(17, 17, 'Vacunación al día', 'Pastoreo en campo con abundante pasto verde', 'Aumento reciente de peso; ración con alto contenido de cereal', 'Un episodio leve de laminitis hace tres años', 'Apetito conservado; reticente a caminar', 'Dolor en ambos miembros anteriores y postura de apoyo hacia atrás desde hace dos días'),
(18, 18, 'Vacunación al día; desparasitación reciente', 'Box individual en centro hípico', 'Ración, heno y suplemento mineral; trabajo diario de salto', 'Sin antecedentes relevantes', 'Apetito y consumo de agua habituales', 'El jinete refiere rigidez al inicio del trabajo que mejora con el calentamiento.'),
(19, 19, 'Vacunación al día; desparasitación realizada en octubre', 'Potrero compartido con otros ponis', 'Pastoreo con suplemento mineral y ración ajustada', 'Parasitosis gastrointestinal con delgadez en octubre', 'Apetito conservado; mejor pelaje según el tutor', 'Sin signos digestivos referidos.');

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
(1, 1, 'Consulta general', '2026-10-15 09:30:00', 'Finalizada', NULL, NULL, NULL, 'Control de rutina'),
(2, 2, 'Consulta equina', '2026-10-15 11:00:00', 'Finalizada', NULL, NULL, NULL, 'Claudicación del miembro anterior'),
(3, 3, 'Control', '2026-10-15 15:15:00', 'Finalizada', 'Tratamiento indicado', '2026-10-22', NULL, 'Revisión dermatológica'),
(4, 1, 'Hospitalización', '2026-10-15 18:00:00', 'Finalizada', NULL, NULL, NULL, 'Observación postoperatoria'),
(5, 2, 'Consulta equina', '2026-10-21 13:00:00', 'Finalizada', NULL, NULL, NULL, 'Control clínico equino'),
(6, 2, 'Consulta equina', '2026-10-26 03:33:00', 'Pendiente', NULL, NULL, NULL, 'Prueba'),
(7, 2, 'Consulta equina - evaluación locomotora', '2026-10-28 10:30:00', 'Pendiente', NULL, '2026-11-04', NULL, 'Evaluación por claudicación intermitente del miembro anterior izquierdo.'),
(8, 6, 'Consulta equina', '2026-09-08 09:00:00', 'Finalizada', 'Tratamiento indicado', '2026-09-22', NULL, 'Cólico leve de resolución espontánea'),
(9, 7, 'Consulta equina', '2026-09-10 10:15:00', 'Finalizada', 'Tratamiento indicado', '2026-09-24', NULL, 'Tos y secreción nasal'),
(10, 8, 'Consulta equina - evaluación locomotora', '2026-09-17 08:45:00', 'Finalizada', 'Tratamiento indicado', '2026-10-01', NULL, 'Disminución del rendimiento en el entrenamiento'),
(11, 8, 'Control', '2026-10-01 09:00:00', 'Finalizada', NULL, NULL, NULL, 'Control de evaluación locomotora'),
(12, 9, 'Hospitalización', '2026-10-05 17:30:00', 'Finalizada', 'Tratamiento indicado', NULL, NULL, 'Cólico con dolor abdominal moderado'),
(13, 10, 'Consulta equina', '2026-10-08 11:30:00', 'Finalizada', 'Tratamiento indicado', '2026-10-22', NULL, 'Herida en el miembro posterior derecho'),
(14, 10, 'Control', '2026-10-22 11:00:00', 'Finalizada', NULL, NULL, NULL, 'Control de cicatrización de la herida'),
(15, 11, 'Consulta equina', '2026-10-13 14:00:00', 'Finalizada', NULL, NULL, NULL, 'Examen de rutina previo a competencia'),
(16, 12, 'Consulta equina', '2026-10-19 10:00:00', 'Finalizada', 'Tratamiento indicado', '2026-11-02', NULL, 'Pérdida de peso y pelaje opaco'),
(17, 13, 'Hospitalización', '2026-10-24 19:00:00', 'Pendiente', NULL, NULL, NULL, 'Dolor en miembros anteriores y reticencia a caminar'),
(18, 11, 'Consulta equina - evaluación locomotora', '2026-10-29 09:00:00', 'Pendiente', NULL, '2026-11-05', NULL, 'Rigidez al inicio del trabajo y menor rendimiento en el salto.'),
(19, 12, 'Control', '2026-11-02 10:00:00', 'Pendiente', NULL, NULL, NULL, 'Control de peso y condición corporal tras la desparasitación.');

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
(2, 2, 'Definitivo', 'Claudicación de miembro anterior'),
(3, 5, 'Presuntivo', 'Claudicación del miembro anterior'),
(4, 6, 'Provisorio', 'Prueba'),
(5, 6, 'Definitivo', 'Prueba'),
(6, 7, 'Provisorio', 'Claudicación leve del miembro anterior izquierdo; etiología a determinar.'),
(7, 7, 'Definitivo', 'Pendiente de completar luego de la evaluación y estudios complementarios.'),
(8, 1, 'Definitivo', 'Paciente clínicamente sano'),
(9, 4, 'Definitivo', 'Postoperatorio sin complicaciones'),
(10, 8, 'Presuntivo', 'Cólico espasmódico leve'),
(11, 8, 'Definitivo', 'Cólico espasmódico resuelto con tratamiento médico'),
(12, 9, 'Presuntivo', 'Enfermedad respiratoria infecciosa de vías altas'),
(13, 10, 'Presuntivo', 'Sinovitis del corvejón derecho'),
(14, 10, 'Definitivo', 'Osteoartritis tarsiana leve del corvejón derecho'),
(15, 11, 'Definitivo', 'Osteoartritis tarsiana leve con evolución favorable'),
(16, 12, 'Presuntivo', 'Cólico por impactación del colon menor'),
(17, 12, 'Definitivo', 'Impactación del colon menor resuelta con tratamiento médico'),
(18, 13, 'Definitivo', 'Herida cutánea superficial en el miembro posterior derecho'),
(19, 14, 'Definitivo', 'Herida en cicatrización por segunda intención'),
(20, 15, 'Definitivo', 'Paciente apto para competencia'),
(21, 16, 'Presuntivo', 'Parasitosis gastrointestinal con déficit nutricional'),
(22, 16, 'Presuntivo', 'Alteraciones dentarias que dificultan la masticación'),
(23, 17, 'Presuntivo', 'Laminitis aguda en ambos miembros anteriores'),
(24, 17, 'Provisorio', 'Síndrome metabólico equino a confirmar'),
(25, 18, 'Provisorio', 'Rigidez locomotora de origen a determinar.'),
(26, 18, 'Definitivo', 'Pendiente de completar luego de la evaluación y estudios complementarios.'),
(27, 19, 'Provisorio', 'Delgadez en recuperación; reevaluar tras la desparasitación.'),
(28, 19, 'Definitivo', 'Pendiente de completar luego del control clínico.');

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

--
-- Volcado de datos para la tabla `EXAMEN_EQUINO`
--

INSERT INTO `EXAMEN_EQUINO` (`id_ExamenEq`, `id_ExamenG`, `temperatura_cascos_ExamenEq`, `pulso_digital_ExamenEq`, `senos_ExamenEq`, `csd_ExamenEq`, `cid_ExamenEq`, `csi_ExamenEq`, `cii_ExamenEq`) VALUES
(1, 3, 'Sin registrar', 'Sin registrar', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(2, 4, 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba'),
(3, 5, 'Sin aumento evidente', 'Levemente aumentado en miembro anterior izquierdo', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sensibilidad leve a la exploración', 'Sin alteraciones'),
(4, 6, 'Sin registrar', 'Sin registrar', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(5, 2, 'Sin aumento evidente', 'Levemente aumentado en miembro anterior izquierdo', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sensibilidad leve a la exploración', 'Sin alteraciones'),
(6, 7, 'Sin registrar', 'Sin registrar', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(7, 8, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(8, 9, 'Sin aumento evidente', 'Normal', 'Sin dolor a la percusión', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(9, 10, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sensibilidad leve a la palpación del corvejón', 'Sin alteraciones', 'Sin alteraciones'),
(10, 11, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(11, 12, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(12, 13, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Herida superficial en la región metatarsiana', 'Sin alteraciones', 'Sin alteraciones'),
(13, 14, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(14, 15, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(15, 16, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(16, 17, 'Aumentada en ambos miembros anteriores', 'Aumentado y fuerte en ambos miembros anteriores', 'Sin alteraciones aparentes', 'Sensibilidad marcada a la pinza de tientas', 'Sin alteraciones', 'Sensibilidad marcada a la pinza de tientas', 'Sin alteraciones'),
(17, 18, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones'),
(18, 19, 'Sin aumento evidente', 'Normal', 'Sin alteraciones aparentes', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones', 'Sin alteraciones');

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
(2, 2, '480 kg', '5', '37.8 °C', '42 lpm', '16 rpm', '2 s', 'Alerta', 'Normal', NULL, 'Rosadas', NULL, NULL, 'Normal', NULL, NULL),
(3, 5, '480 kg', '5', '37.8 °C', '42 lpm', '16 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones', 'Rosadas', 'Sin alteraciones registradas', 'Claudicación del miembro anterior', 'Normal', 'Sin alteraciones registradas', 'Sin otras alteraciones aparentes'),
(4, 6, 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba', 'Prueba'),
(5, 7, '480 kg', '5/9', '37.8 °C', '42 lpm', '16 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones aparentes', 'Rosadas', 'Alimentación y eliminación sin cambios referidos.', 'Leve apoyo reducido del miembro anterior izquierdo al trote.', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(6, 1, '24 kg', '5', '38.5 °C', '96 lpm', '22 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones', 'Rosadas', 'Sin alteraciones registradas', 'Sin actitudes anómalas', 'Normal', 'Sin alteraciones registradas', 'Sin alteraciones aparentes'),
(7, 4, '24 kg', '5', '38.7 °C', '104 lpm', '26 rpm', '2 s', 'Decaída, responde a estímulos', 'Normal', 'Sin alteraciones', 'Rosadas', 'Apetito disminuido; micción y defecación sin cambios', 'Protege la zona quirúrgica', 'Normal', 'Sin alteraciones registradas', 'Herida quirúrgica limpia y sin secreción'),
(8, 8, '510 kg', '5/9', '37.9 °C', '48 lpm', '18 rpm', '2 s', 'Alerta', 'Expresión de molestia leve', 'Sin alteraciones aparentes', 'Rosadas', 'Motilidad intestinal levemente disminuida', 'Mira el flanco y escarba con el miembro anterior', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(9, 9, '430 kg', '5/9', '38.6 °C', '40 lpm', '24 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones aparentes', 'Rosadas', 'Respiración con leve esfuerzo luego de la movilización', 'Tos ocasional al manipular', 'Normal', 'Submandibulares levemente aumentados', 'Secreción nasal mucopurulenta bilateral'),
(10, 10, '470 kg', '4/9', '37.7 °C', '38 lpm', '14 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones aparentes', 'Rosadas', 'Sin alteraciones registradas', 'Acorta el tranco del miembro posterior derecho al trote', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(11, 11, '472 kg', '4/9', '37.6 °C', '36 lpm', '14 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones aparentes', 'Rosadas', 'Sin alteraciones registradas', 'Sin actitudes anómalas observadas', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(12, 12, '440 kg', '4/9', '38.1 °C', '64 lpm', '22 rpm', '3 s', 'Decaído', 'Expresión de dolor', 'Sudoración en los flancos', 'Congestivas', 'Motilidad intestinal disminuida en ambos flancos', 'Se echa, se revuelca y patea el abdomen', 'Deshidratación leve', 'Sin alteraciones aparentes', 'Abdomen levemente distendido'),
(13, 13, '400 kg', '5/9', '37.8 °C', '40 lpm', '16 rpm', '2 s', 'Alerta', 'Normal', 'Herida cutánea en la cara lateral del metatarso derecho', 'Rosadas', 'Sin alteraciones registradas', 'Apoya el miembro con normalidad', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(14, 14, '400 kg', '5/9', '37.7 °C', '38 lpm', '14 rpm', '2 s', 'Alerta', 'Normal', 'Cicatrización en curso en el miembro posterior derecho', 'Rosadas', 'Sin alteraciones registradas', 'Sin actitudes anómalas observadas', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(15, 15, '560 kg', '6/9', '37.6 °C', '36 lpm', '12 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones aparentes', 'Rosadas', 'Sin alteraciones registradas', 'Sin actitudes anómalas observadas', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(16, 16, '165 kg', '3/9', '37.5 °C', '44 lpm', '14 rpm', '2 s', 'Alerta', 'Normal', 'Pelaje opaco y sin brillo', 'Rosadas pálidas', 'Sin alteraciones registradas', 'Sin actitudes anómalas observadas', 'Normal', 'Sin alteraciones aparentes', 'Delgadez marcada'),
(17, 17, '520 kg', '7/9', '37.9 °C', '56 lpm', '20 rpm', '2 s', 'Alerta', 'Expresión de dolor', 'Sin alteraciones aparentes', 'Rosadas', 'Sin alteraciones registradas', 'Descarga el peso sobre los miembros posteriores', 'Normal', 'Sin alteraciones aparentes', 'Sobrepeso y cresta cervical engrosada'),
(18, 18, '560 kg', '6/9', '37.6 °C', '36 lpm', '12 rpm', '2 s', 'Alerta', 'Normal', 'Sin alteraciones aparentes', 'Rosadas', 'Alimentación y eliminación sin cambios referidos.', 'Rigidez al inicio de la marcha referida por el jinete.', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes'),
(19, 19, '172 kg', '4/9', '37.5 °C', '42 lpm', '14 rpm', '2 s', 'Alerta', 'Normal', 'Pelaje con mejor brillo', 'Rosadas', 'Alimentación y eliminación sin cambios referidos.', 'Sin actitudes anómalas observadas', 'Normal', 'Sin alteraciones aparentes', 'Sin otras alteraciones aparentes');

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
(1, 5, 'Evaluación locomotora: marcha al paso y al trote, inspección y palpación de miembros.', 'Hallazgos de ejemplo para pruebas. Completar según evaluación clínica real.'),
(2, 6, 'Examen clínico de rutina: auscultación cardiopulmonar y palpación abdominal.', 'Sin hallazgos relevantes.'),
(3, 2, 'Inspección y palpación del miembro anterior; evaluación de la marcha al paso.', 'Hallazgos de ejemplo para pruebas.'),
(4, 1, 'Examen dermatológico: inspección de piel y pelaje en flancos y abdomen.', 'Eritema leve sin lesiones extensas.'),
(5, 7, 'Inspección de la herida quirúrgica y control del dolor.', 'Sin signos de infección ni dehiscencia.'),
(6, 3, 'Evaluación locomotora: inspección y palpación de miembros anteriores; marcha al paso y al trote.', 'Hallazgos de ejemplo para pruebas.'),
(7, 8, 'Auscultación abdominal en los cuatro cuadrantes, palpación transrectal y sondaje nasogástrico.', 'Motilidad disminuida en el cuadrante inferior izquierdo; sin reflujo gástrico.'),
(8, 9, 'Auscultación pulmonar y traqueal, inspección de cavidad nasal y palpación de ganglios linfáticos.', 'Sibilancias leves en los campos pulmonares dorsales.'),
(9, 10, 'Evaluación locomotora: marcha al paso y al trote en línea recta y en círculo, pruebas de flexión de miembros posteriores.', 'Respuesta positiva leve a la flexión del corvejón derecho.'),
(10, 11, 'Control de la marcha al paso y al trote; palpación del corvejón derecho.', 'Evolución favorable; sin respuesta positiva a la flexión.'),
(11, 12, 'Auscultación abdominal, palpación transrectal y sondaje nasogástrico.', 'Sin reflujo gástrico; impactación del colon menor palpable.'),
(12, 13, 'Inspección, limpieza y exploración de la herida; evaluación de estructuras profundas.', 'Herida de 5 cm sin compromiso tendinoso evidente.'),
(13, 14, 'Control de la herida: tejido de granulación, bordes y presencia de secreción.', 'Granulación sana, sin secreción ni calor local.'),
(14, 15, 'Examen clínico completo: auscultación cardiopulmonar, palpación de miembros y evaluación de la marcha.', 'Sin hallazgos que contraindiquen la competencia.'),
(15, 16, 'Examen de cavidad oral, inspección de la condición corporal y palpación abdominal.', 'Puntas dentarias marcadas en la arcada superior; sin lesiones en la mucosa oral.'),
(16, 17, 'Inspección de los cascos, pinza de tientas, marcha en superficie blanda y palpación del pulso digital.', 'Marcha rígida con apoyo de talones.'),
(17, 18, 'Evaluación locomotora: marcha al paso y al trote, pruebas de flexión y palpación de la región dorsolumbar.', 'Pendiente de completar según evaluación clínica real.'),
(18, 19, 'Control de la condición corporal y examen de cavidad oral.', 'Pendiente de completar según evaluación clínica real.');

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
(2, 2, 2, 2, '2026-10-15 11:00:00', NULL, 'Manejo de claudicación', 'Activa'),
(3, 9, 12, 3, '2026-10-05 17:30:00', '2026-10-07 11:00:00', 'Manejo de cólico por impactación', 'Finalizada'),
(4, 13, 17, 3, '2026-10-24 19:00:00', NULL, 'Manejo de laminitis aguda', 'Activa');

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
(1, 1, 17, 'ARG-1001', 'Nala', '2021-04-15', 4, 'Hembra', 'Marrón', NULL, '2026-10-15 23:45:16', '2026-10-26 22:04:20'),
(2, 2, 1, 'ARG-2002', 'Relámpago', '2018-03-09', 7, 'Macho castrado', 'Zaino', NULL, '2026-10-15 23:45:16', '2026-10-21 16:08:48'),
(3, 3, 21, 'ARG-3003', 'Milo', '2022-11-02', 3, 'Macho', 'Gris', NULL, '2026-10-15 23:45:16', '2026-10-26 22:04:20'),
(5, 4, 8, NULL, 'Prueba', '2026-10-01', 0, 'Macho', 'Marron', NULL, '2026-10-26 04:11:43', '2026-10-26 04:11:43'),
(6, 5, 5, 'ARG-4004', 'Tormenta', '2019-09-12', 7, 'Hembra', 'Alazán', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(7, 5, 2, 'ARG-5005', 'Zorzal', '2016-11-03', 9, 'Macho castrado', 'Tordillo', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(8, 6, 3, 'ARG-6006', 'Centella', '2020-02-21', 6, 'Hembra', 'Zaino oscuro', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(9, 6, 2, 'ARG-7007', 'Gaucho', '2014-06-30', 12, 'Macho castrado', 'Bayo', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(10, 7, 4, 'ARG-8008', 'Luna', '2021-01-18', 5, 'Hembra', 'Tordillo', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(11, 8, 8, 'ARG-9009', 'Trueno', '2017-05-05', 9, 'Macho', 'Oscuro', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(12, 8, 14, 'ARG-1010', 'Canela', '2023-03-14', 3, 'Hembra', 'Pangaré', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(13, 9, 16, 'ARG-1111', 'Pampero', '2012-08-22', 14, 'Macho castrado', 'Tostado', NULL, '2026-09-01 10:00:00', '2026-09-01 10:00:00');

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
(1, 5, 'Evaluación clínica', 'Pendiente de completar', NULL),
(2, 6, 'Prueba', 'Prueba', 'dd6a9250b8dce4bdefe15bd7a2ea6734.pdf'),
(3, 7, 'Evaluación locomotora', 'Prueba de flexión y evaluación dinámica pendientes.', NULL),
(4, 7, 'Estudio radiográfico', 'Solicitado; resultado pendiente.', NULL),
(5, 2, 'Estudio radiográfico', 'Sin hallazgos óseos evidentes en la región evaluada.', NULL),
(6, 3, 'Raspado cutáneo', 'Sin presencia de parásitos.', NULL),
(7, 8, 'Hemograma', 'Hematocrito y proteínas totales dentro de valores de referencia.', NULL),
(8, 9, 'Hemograma', 'Leucocitosis leve con neutrofilia.', NULL),
(9, 10, 'Estudio radiográfico', 'Esclerosis leve en la articulación tarsometatarsiana derecha.', NULL),
(10, 11, 'Evaluación clínica', 'Sin hallazgos que requieran estudios adicionales.', NULL),
(11, 12, 'Hemograma y bioquímica', 'Hematocrito y proteínas totales levemente aumentados; lactato dentro de valores de referencia.', NULL),
(12, 15, 'Evaluación clínica', 'Sin hallazgos relevantes.', NULL),
(13, 15, 'Hemograma', 'Valores dentro de los rangos de referencia.', NULL),
(14, 16, 'Coprológico', 'Recuento de huevos por gramo elevado (estrongílidos).', NULL),
(15, 17, 'Estudio radiográfico', 'Solicitado; resultado pendiente.', NULL),
(16, 17, 'Insulina y glucosa basales', 'Muestra enviada; resultado pendiente.', NULL),
(17, 18, 'Evaluación locomotora', 'Prueba de flexión y evaluación dinámica pendientes.', NULL),
(18, 18, 'Estudio radiográfico', 'Solicitado; resultado pendiente.', NULL),
(19, 19, 'Coprológico', 'Control posterior a la desparasitación pendiente.', NULL);

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
(1, 5, 'Reservado hasta completar la evaluación'),
(2, 6, 'Vital'),
(3, 7, 'Vital'),
(4, 7, 'Deportivo'),
(5, 1, 'Vital'),
(6, 2, 'Vital'),
(7, 3, 'Vital'),
(8, 4, 'Vital'),
(9, 8, 'Vital'),
(10, 9, 'Vital'),
(11, 10, 'Deportivo'),
(12, 10, 'Vital'),
(13, 11, 'Deportivo'),
(14, 12, 'Vital'),
(15, 13, 'Vital'),
(16, 13, 'Deportivo'),
(17, 14, 'Vital'),
(18, 15, 'Deportivo'),
(19, 16, 'Vital'),
(20, 17, 'Reservado hasta completar la evaluación'),
(21, 18, 'Deportivo'),
(22, 19, 'Vital');

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
(2, 1, 'Criollo'),
(3, 1, 'Pura Sangre Inglés'),
(4, 1, 'Árabe'),
(5, 1, 'Cuarto de Milla'),
(6, 1, 'Percherón'),
(7, 1, 'Árabe-Criollo'),
(8, 1, 'Silla Argentino'),
(9, 1, 'Silla Francés'),
(10, 1, 'Appaloosa'),
(11, 1, 'Paint Horse'),
(12, 1, 'Andaluz'),
(13, 1, 'Frisón'),
(14, 1, 'Pony Shetland'),
(15, 1, 'Pony Welsh'),
(16, 1, 'Mestizo'),
(17, 2, 'Mestizo'),
(18, 2, 'Labrador Retriever'),
(19, 2, 'Ovejero Alemán'),
(20, 2, 'Cimarrón Uruguayo'),
(21, 3, 'Mestizo'),
(22, 3, 'Siamés'),
(23, 3, 'Persa');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `RESEÑA`
--

CREATE TABLE `RESEÑA` (
  `id_Reseña` int(11) NOT NULL,
  `id_ExamenG` int(11) DEFAULT NULL,
  `archivoURL_RESEÑA` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `RESEÑA`
--

INSERT INTO `RESEÑA` (`id_Reseña`, `id_ExamenG`, `archivoURL_RESEÑA`) VALUES
(1, 4, 'aff232eccbe4bf39296050f8b2e532b7.png');

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
(1, 2, 'Consulta clínica equina', 'Examen clínico general y particular del paciente equino, con anamnesis e indicaciones.', 1800.00, '45 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(2, 2, 'Control clínico', 'Seguimiento de la evolución del paciente y ajuste del tratamiento.', 1000.00, '30 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(3, 3, 'Evaluación locomotora', 'Marcha al paso y al trote, pruebas de flexión y palpación de miembros.', 2500.00, '60 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(4, 3, 'Estudio radiográfico', 'Radiografías digitales de miembros, cascos u otras regiones, con informe.', 3200.00, '45 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(5, 3, 'Ecografía', 'Ecografía de tendones, ligamentos o abdomen, con informe.', 2800.00, '40 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(6, 2, 'Vacunación y desparasitación', 'Plan sanitario con vacunas y antiparasitario según peso y calendario.', 1500.00, '30 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(7, 2, 'Odontología equina', 'Examen de la cavidad oral y limado de puntas dentarias.', 2200.00, '50 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(8, 2, 'Hospitalización (por día)', 'Internación con monitoreo, alimentación y medicación indicada.', 4500.00, '24 horas', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38'),
(9, 3, 'Examen de precompra', 'Evaluación clínica y locomotora completa previa a la compra del equino.', 4000.00, '90 minutos', NULL, NULL, '2026-10-26 22:02:38', '2026-10-26 22:02:38');

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
(2, 2, 'Descarga y observación por 48 horas'),
(3, 5, 'A definir por el profesional tratante'),
(4, 6, 'Prueba'),
(5, 7, 'Reposo relativo y control clínico. Conducta definitiva a establecer por el profesional tratante.'),
(6, 1, 'Sin tratamiento. Continuar con el plan sanitario habitual.'),
(7, 4, 'Analgesia, antibiótico profiláctico y control de la herida cada 12 horas.'),
(8, 8, 'Espasmolítico y analgésico por vía endovenosa; ayuno por 12 horas y reintroducción gradual del forraje.'),
(9, 9, 'Reposo, humidificación del ambiente y antiinflamatorio por 5 días; aislamiento de los demás animales del establo.'),
(10, 10, 'Reducción de la carga de trabajo por 15 días, antiinflamatorio oral por 7 días y control clínico.'),
(11, 11, 'Retomar el trabajo de forma progresiva durante tres semanas.'),
(12, 12, 'Fluidoterapia endovenosa, analgésico, laxante por sonda y reintroducción gradual de la alimentación al egreso.'),
(13, 13, 'Lavado con solución fisiológica, curación y vendaje cada 48 horas, antibiótico por 5 días.'),
(14, 14, 'Continuar con curaciones cada 72 horas durante una semana más y retirar el vendaje al cierre.'),
(15, 15, 'Sin tratamiento. Mantener el plan de trabajo y el control sanitario habitual.'),
(16, 16, 'Desparasitación según peso, ajuste de la ración con suplemento y limado dentario programado.'),
(17, 17, 'Antiinflamatorio, cama profunda de arena o viruta, soporte de la palma y restricción dietética; reevaluación a las 48 horas.'),
(18, 18, 'Reposo relativo y control clínico. Conducta definitiva a establecer por el profesional tratante.'),
(19, 19, 'Continuar con la ración ajustada y el suplemento mineral; control del peso cada 30 días.');

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
(3, '45678912', 'Ana Costa', '091234567', 'Camino Real 99', 'ana.costa@example.com', 'Tutor del gato', '2026-10-15 23:45:16', '2026-10-15 23:45:16'),
(4, '22334455', 'Prueba', '123456789', 'Prueba', 'Prueba@gmail.com', 'Prueba', '2026-10-26 04:11:43', '2026-10-26 04:11:43'),
(5, '31245678', 'Gabriela Techera', '099234567', 'Camino Maldonado 1520', 'gabriela.techera@example.com', 'Tutor de dos equinos', '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(6, '42356789', 'Martín Olivera', '098345678', 'Ruta 8 km 22', 'martin.olivera@example.com', 'Propietario de caballeriza', '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(7, '53467891', 'Florencia Acosta', '091456789', 'Calle Artigas 482', 'florencia.acosta@example.com', 'Tutor del caballo', '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(8, '24578913', 'Ignacio Pereira', '094567891', 'Camino Carrasco 3310', 'ignacio.pereira@example.com', 'Jinete y tutor de dos equinos', '2026-09-01 10:00:00', '2026-09-01 10:00:00'),
(9, '35689124', 'Rosa Cabrera', '092678912', 'Avenida Garzón 1875', 'rosa.cabrera@example.com', 'Tutor del caballo', '2026-09-01 10:00:00', '2026-09-01 10:00:00');

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
(1, '51234567', 'Juan', 'Pérez', 'juan.perez@polivet.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:35:45'),
(2, '12345678', 'María', 'Rodríguez', 'maria.rodriguez@polivet.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Director', 'Activo', '2026-10-26 22:04:32', '2026-10-26 22:04:32'),
(3, '53456789', 'Ana', 'Silva', 'ana.silva@polivet.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Profesor', 'Activo', '2026-10-15 13:24:45', '2026-10-26 02:36:05'),
(14, '00011122', 'Carlos', 'González', 'carlos.gonzalez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(15, '23456789', 'Ana', 'Martínez', 'ana.martinez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(16, '34567890', 'Luis', 'Pérez', 'luis.perez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(17, '45678901', 'Marta', 'Gómez', 'marta.gomez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(18, '56789012', 'Javier', 'Rodríguez', 'javier.rodriguez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(19, '67890123', 'Isabel', 'López', 'isabel.lopez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(20, '78901234', 'Diego', 'Sánchez', 'diego.sanchez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(21, '89012345', 'Ana', 'Fernández', 'ana.fernandez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(22, '90123456', 'Jorge', 'Hernández', 'jorge.hernandez@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29'),
(23, '01234567', 'Sofia', 'García', 'sofia.garcia@example.com', '$2y$10$OUdEYLLCRLbKjl6df6xhZ.HiBVMjyGCOt140qWTN.72PTqBCMBdHi', 'Estudiante', 'Activo', NULL, '2026-10-26 02:59:29');

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
(1, 5),
(1, 7),
(2, 2),
(3, 4),
(3, 5),
(3, 6),
(3, 7),
(3, 8),
(3, 9),
(3, 10),
(3, 11),
(3, 12),
(3, 13),
(3, 14),
(3, 15),
(3, 16),
(3, 17),
(3, 18),
(3, 19),
(14, 8),
(15, 9),
(16, 10),
(16, 11),
(17, 12),
(18, 12),
(19, 13),
(19, 14),
(20, 15),
(20, 18),
(21, 6),
(21, 16),
(21, 19),
(22, 17);

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
  MODIFY `id_Anamnesis` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `CONSULTA`
--
ALTER TABLE `CONSULTA`
  MODIFY `id_Consulta` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `DIAGNOSTICO`
--
ALTER TABLE `DIAGNOSTICO`
  MODIFY `id_Diagnostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT de la tabla `ESPECIE`
--
ALTER TABLE `ESPECIE`
  MODIFY `id_Especie` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_EQUINO`
--
ALTER TABLE `EXAMEN_EQUINO`
  MODIFY `id_ExamenEq` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_GENERAL`
--
ALTER TABLE `EXAMEN_GENERAL`
  MODIFY `id_ExamenG` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `EXAMEN_PARTICULAR`
--
ALTER TABLE `EXAMEN_PARTICULAR`
  MODIFY `id_ExamenP` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `HOSPITALIZACION`
--
ALTER TABLE `HOSPITALIZACION`
  MODIFY `id_Hospitalizacion` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `PACIENTE`
--
ALTER TABLE `PACIENTE`
  MODIFY `id_Paciente` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de la tabla `PARACLINICO`
--
ALTER TABLE `PARACLINICO`
  MODIFY `id_Paraclinico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `PRONOSTICO`
--
ALTER TABLE `PRONOSTICO`
  MODIFY `id_Pronostico` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `RAZA`
--
ALTER TABLE `RAZA`
  MODIFY `id_Raza` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT de la tabla `RESEÑA`
--
ALTER TABLE `RESEÑA`
  MODIFY `id_Reseña` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `SERVICIO`
--
ALTER TABLE `SERVICIO`
  MODIFY `id_Servicio` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `TRATAMIENTO`
--
ALTER TABLE `TRATAMIENTO`
  MODIFY `id_Tratamiento` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `TUTOR`
--
ALTER TABLE `TUTOR`
  MODIFY `id_Tutor` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `USUARIO`
--
ALTER TABLE `USUARIO`
  MODIFY `id_Usuario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

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
