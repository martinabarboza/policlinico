/*M!999999\- enable the sandbox mode */ 
-- MariaDB dump 10.19  Distrib 10.11.19-MariaDB, for debian-linux-gnu (x86_64)
--
-- Host: localhost    Database: policlinico_db
-- ------------------------------------------------------
-- Server version	10.11.19-MariaDB-ubu2204

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `ANAMNESIS`
--

DROP TABLE IF EXISTS `ANAMNESIS`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ANAMNESIS` (
  `id_Anamnesis` int(11) NOT NULL AUTO_INCREMENT,
  `id_Consulta` int(11) NOT NULL,
  `sanitaria_Anamnesis` text DEFAULT NULL,
  `ambiental_Anamnesis` text DEFAULT NULL,
  `remota_fisiologica_Anamnesis` text DEFAULT NULL,
  `remota_patologica_Anamnesis` text DEFAULT NULL,
  `proxima_fisiologica_Anamnesis` text DEFAULT NULL,
  `proxima_patologica_Anamnesis` text DEFAULT NULL,
  PRIMARY KEY (`id_Anamnesis`),
  UNIQUE KEY `id_Consulta` (`id_Consulta`),
  CONSTRAINT `id_FK_A_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ANAMNESIS`
--

LOCK TABLES `ANAMNESIS` WRITE;
/*!40000 ALTER TABLE `ANAMNESIS` DISABLE KEYS */;
INSERT INTO `ANAMNESIS` VALUES
(1,1,'Vacunación al día.','Vive en casa con patio.','Apetito y actividad habituales.','Sin antecedentes relevantes.','Sin cambios recientes.','Consulta de rutina.');
/*!40000 ALTER TABLE `ANAMNESIS` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `CONSULTA`
--

DROP TABLE IF EXISTS `CONSULTA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `CONSULTA` (
  `id_Consulta` int(11) NOT NULL AUTO_INCREMENT,
  `id_Paciente` int(11) NOT NULL,
  `tipo_Consulta` varchar(100) DEFAULT NULL,
  `fecha_hora_Consulta` datetime NOT NULL,
  `estado_proceso_Consulta` varchar(50) DEFAULT 'Pendiente',
  `resultado_final_Consulta` varchar(100) DEFAULT NULL,
  `fecha_proximo_control_Consulta` date DEFAULT NULL,
  `fecha_fin_Consulta` date DEFAULT NULL,
  `motivo_Consulta` text DEFAULT NULL,
  PRIMARY KEY (`id_Consulta`),
  KEY `id_Paciente` (`id_Paciente`),
  KEY `idx_consulta_fecha_hora` (`fecha_hora_Consulta`),
  CONSTRAINT `id_FK_C_PACIENTE` FOREIGN KEY (`id_Paciente`) REFERENCES `PACIENTE` (`id_Paciente`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `CONSULTA`
--

LOCK TABLES `CONSULTA` WRITE;
/*!40000 ALTER TABLE `CONSULTA` DISABLE KEYS */;
INSERT INTO `CONSULTA` VALUES
(1,1,'Consulta general','2026-11-10 12:01:04','Finalizada','Paciente estable','2026-11-19','2026-11-10','Control de rutina.'),
(2,2,'Consulta equina','2026-11-12 12:31:04','Pendiente',NULL,NULL,NULL,'Evaluación locomotora de ejemplo.'),
(3,3,'Control','2026-11-13 12:01:04','Pendiente',NULL,NULL,NULL,'Control general de ejemplo.');
/*!40000 ALTER TABLE `CONSULTA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `DIAGNOSTICO`
--

DROP TABLE IF EXISTS `DIAGNOSTICO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `DIAGNOSTICO` (
  `id_Diagnostico` int(11) NOT NULL AUTO_INCREMENT,
  `id_Consulta` int(11) NOT NULL,
  `tipo_Diagnostico` varchar(50) NOT NULL,
  `descripcion_Diagnostico` text NOT NULL,
  PRIMARY KEY (`id_Diagnostico`),
  KEY `id_Consulta` (`id_Consulta`),
  CONSTRAINT `id_FK_D_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `DIAGNOSTICO`
--

LOCK TABLES `DIAGNOSTICO` WRITE;
/*!40000 ALTER TABLE `DIAGNOSTICO` DISABLE KEYS */;
INSERT INTO `DIAGNOSTICO` VALUES
(1,1,'Definitivo','Paciente clínicamente estable.');
/*!40000 ALTER TABLE `DIAGNOSTICO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `ESPECIE`
--

DROP TABLE IF EXISTS `ESPECIE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `ESPECIE` (
  `id_Especie` int(11) NOT NULL AUTO_INCREMENT,
  `nombre_Especie` varchar(50) NOT NULL,
  PRIMARY KEY (`id_Especie`),
  UNIQUE KEY `nombre_Especie` (`nombre_Especie`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `ESPECIE`
--

LOCK TABLES `ESPECIE` WRITE;
/*!40000 ALTER TABLE `ESPECIE` DISABLE KEYS */;
INSERT INTO `ESPECIE` VALUES
(2,'Canino'),
(1,'Equino'),
(3,'Felino');
/*!40000 ALTER TABLE `ESPECIE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EXAMEN_EQUINO`
--

DROP TABLE IF EXISTS `EXAMEN_EQUINO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `EXAMEN_EQUINO` (
  `id_ExamenEq` int(11) NOT NULL AUTO_INCREMENT,
  `id_ExamenG` int(11) NOT NULL,
  `temperatura_cascos_ExamenEq` varchar(50) DEFAULT NULL,
  `pulso_digital_ExamenEq` varchar(50) DEFAULT NULL,
  `senos_ExamenEq` varchar(100) DEFAULT NULL,
  `csd_ExamenEq` varchar(50) DEFAULT NULL,
  `cid_ExamenEq` varchar(50) DEFAULT NULL,
  `csi_ExamenEq` varchar(50) DEFAULT NULL,
  `cii_ExamenEq` varchar(50) DEFAULT NULL,
  PRIMARY KEY (`id_ExamenEq`),
  UNIQUE KEY `id_ExamenG` (`id_ExamenG`),
  CONSTRAINT `id_FK_EQ_EXAMENGENERAL` FOREIGN KEY (`id_ExamenG`) REFERENCES `EXAMEN_GENERAL` (`id_ExamenG`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EXAMEN_EQUINO`
--

LOCK TABLES `EXAMEN_EQUINO` WRITE;
/*!40000 ALTER TABLE `EXAMEN_EQUINO` DISABLE KEYS */;
/*!40000 ALTER TABLE `EXAMEN_EQUINO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EXAMEN_GENERAL`
--

DROP TABLE IF EXISTS `EXAMEN_GENERAL`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `EXAMEN_GENERAL` (
  `id_ExamenG` int(11) NOT NULL AUTO_INCREMENT,
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
  `aparentes_ExamenG` varchar(100) DEFAULT NULL,
  PRIMARY KEY (`id_ExamenG`),
  UNIQUE KEY `id_Consulta` (`id_Consulta`),
  CONSTRAINT `id_FK_EG_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EXAMEN_GENERAL`
--

LOCK TABLES `EXAMEN_GENERAL` WRITE;
/*!40000 ALTER TABLE `EXAMEN_GENERAL` DISABLE KEYS */;
INSERT INTO `EXAMEN_GENERAL` VALUES
(1,1,'24 kg','5/9','38.5 °C','96 lpm','22 rpm','2 s','Alerta','Normal','Sin alteraciones','Rosadas','Normales','Sin actitudes anómalas','Normal','Sin alteraciones','Sin alteraciones aparentes');
/*!40000 ALTER TABLE `EXAMEN_GENERAL` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `EXAMEN_PARTICULAR`
--

DROP TABLE IF EXISTS `EXAMEN_PARTICULAR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `EXAMEN_PARTICULAR` (
  `id_ExamenP` int(11) NOT NULL AUTO_INCREMENT,
  `id_ExamenG` int(11) DEFAULT NULL,
  `texto_libre_ExamenP` text DEFAULT NULL,
  `observaciones_ExamenP` text DEFAULT NULL,
  PRIMARY KEY (`id_ExamenP`),
  KEY `id_FK_EP_ExamenG` (`id_ExamenG`),
  CONSTRAINT `id_FK_EP_ExamenG` FOREIGN KEY (`id_ExamenG`) REFERENCES `EXAMEN_GENERAL` (`id_ExamenG`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `EXAMEN_PARTICULAR`
--

LOCK TABLES `EXAMEN_PARTICULAR` WRITE;
/*!40000 ALTER TABLE `EXAMEN_PARTICULAR` DISABLE KEYS */;
INSERT INTO `EXAMEN_PARTICULAR` VALUES
(1,1,'Examen clínico general de rutina.','Sin hallazgos relevantes.');
/*!40000 ALTER TABLE `EXAMEN_PARTICULAR` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `HOSPITALIZACION`
--

DROP TABLE IF EXISTS `HOSPITALIZACION`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `HOSPITALIZACION` (
  `id_Hospitalizacion` int(11) NOT NULL AUTO_INCREMENT,
  `id_Paciente` int(11) NOT NULL,
  `id_Consulta` int(11) DEFAULT NULL,
  `id_Usuario` int(11) DEFAULT NULL,
  `fecha_ingreso_Hospitalizacion` datetime NOT NULL DEFAULT current_timestamp(),
  `fecha_egreso_Hospitalizacion` datetime DEFAULT NULL,
  `motivo_Hospitalizacion` text DEFAULT NULL,
  `estado_Hospitalizacion` varchar(30) NOT NULL DEFAULT 'Activa',
  PRIMARY KEY (`id_Hospitalizacion`),
  KEY `id_Paciente` (`id_Paciente`),
  KEY `id_Consulta` (`id_Consulta`),
  KEY `id_Usuario` (`id_Usuario`),
  CONSTRAINT `HOSPITALIZACION_ibfk_1` FOREIGN KEY (`id_Paciente`) REFERENCES `PACIENTE` (`id_Paciente`),
  CONSTRAINT `HOSPITALIZACION_ibfk_2` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`),
  CONSTRAINT `HOSPITALIZACION_ibfk_3` FOREIGN KEY (`id_Usuario`) REFERENCES `USUARIO` (`id_Usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `HOSPITALIZACION`
--

LOCK TABLES `HOSPITALIZACION` WRITE;
/*!40000 ALTER TABLE `HOSPITALIZACION` DISABLE KEYS */;
INSERT INTO `HOSPITALIZACION` VALUES
(1,2,NULL,2,'2026-11-12 10:01:04','2026-11-15 15:40:33','Observación clínica de demostración.','Finalizada');
/*!40000 ALTER TABLE `HOSPITALIZACION` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PACIENTE`
--

DROP TABLE IF EXISTS `PACIENTE`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `PACIENTE` (
  `id_Paciente` int(11) NOT NULL AUTO_INCREMENT,
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
  `dateupdate_Paciente` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_Paciente`),
  UNIQUE KEY `numchip_Paciente` (`numchip_Paciente`),
  KEY `id_Tutor` (`id_Tutor`),
  KEY `id_FK_P_RAZA` (`id_Raza`),
  CONSTRAINT `id_FK_P_RAZA` FOREIGN KEY (`id_Raza`) REFERENCES `RAZA` (`id_Raza`),
  CONSTRAINT `id_FK_P_TUTOR` FOREIGN KEY (`id_Tutor`) REFERENCES `TUTOR` (`id_Tutor`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PACIENTE`
--

LOCK TABLES `PACIENTE` WRITE;
/*!40000 ALTER TABLE `PACIENTE` DISABLE KEYS */;
INSERT INTO `PACIENTE` VALUES
(1,1,17,'UY-DEMO-001','Nala','2021-04-15',5,'Hembra','Marrón',NULL,'2026-11-12 15:01:04','2026-11-12 15:01:04'),
(2,2,1,'UY-DEMO-002','Relámpago','2018-03-09',8,'Macho castrado','Zaino',NULL,'2026-11-12 15:01:04','2026-11-12 15:01:04'),
(3,2,21,'UY-DEMO-003','Milo','2022-11-02',4,'Macho','Gris',NULL,'2026-11-12 15:01:04','2026-11-12 15:01:04');
/*!40000 ALTER TABLE `PACIENTE` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PARACLINICO`
--

DROP TABLE IF EXISTS `PARACLINICO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `PARACLINICO` (
  `id_Paraclinico` int(11) NOT NULL AUTO_INCREMENT,
  `id_Consulta` int(11) NOT NULL,
  `tipo_Paraclinico` varchar(100) NOT NULL,
  `resultado_Paraclinico` text DEFAULT NULL,
  `archivoURL_Paraclinico` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_Paraclinico`),
  KEY `id_Consulta` (`id_Consulta`),
  CONSTRAINT `id_FK_PARA_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PARACLINICO`
--

LOCK TABLES `PARACLINICO` WRITE;
/*!40000 ALTER TABLE `PARACLINICO` DISABLE KEYS */;
INSERT INTO `PARACLINICO` VALUES
(1,1,'Evaluación clínica','Sin estudios adicionales indicados.',NULL);
/*!40000 ALTER TABLE `PARACLINICO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `PRONOSTICO`
--

DROP TABLE IF EXISTS `PRONOSTICO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `PRONOSTICO` (
  `id_Pronostico` int(11) NOT NULL AUTO_INCREMENT,
  `id_Consulta` int(11) DEFAULT NULL,
  `tipo_Pronostico` varchar(80) DEFAULT NULL,
  PRIMARY KEY (`id_Pronostico`),
  KEY `id_FK_PRONO_CONSULTA` (`id_Consulta`),
  CONSTRAINT `id_FK_PRONO_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `PRONOSTICO`
--

LOCK TABLES `PRONOSTICO` WRITE;
/*!40000 ALTER TABLE `PRONOSTICO` DISABLE KEYS */;
INSERT INTO `PRONOSTICO` VALUES
(1,1,'Favorable');
/*!40000 ALTER TABLE `PRONOSTICO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RAZA`
--

DROP TABLE IF EXISTS `RAZA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `RAZA` (
  `id_Raza` int(11) NOT NULL AUTO_INCREMENT,
  `id_Especie` int(11) NOT NULL,
  `nombre_Raza` varchar(130) NOT NULL,
  PRIMARY KEY (`id_Raza`),
  KEY `id_FK_RAZA_ESPECIE` (`id_Especie`),
  CONSTRAINT `id_FK_RAZA_ESPECIE` FOREIGN KEY (`id_Especie`) REFERENCES `ESPECIE` (`id_Especie`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RAZA`
--

LOCK TABLES `RAZA` WRITE;
/*!40000 ALTER TABLE `RAZA` DISABLE KEYS */;
INSERT INTO `RAZA` VALUES
(1,1,'Sin especificar'),
(17,2,'Mestizo'),
(21,3,'Mestizo');
/*!40000 ALTER TABLE `RAZA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `RESEÑA`
--

DROP TABLE IF EXISTS `RESEÑA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `RESEÑA` (
  `id_Reseña` int(11) NOT NULL AUTO_INCREMENT,
  `id_ExamenG` int(11) DEFAULT NULL,
  `archivoURL_RESEÑA` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id_Reseña`),
  KEY `id_FK_RESEÑA_EXAMENGENERAL` (`id_ExamenG`),
  CONSTRAINT `id_FK_RESEÑA_EXAMENGENERAL` FOREIGN KEY (`id_ExamenG`) REFERENCES `EXAMEN_GENERAL` (`id_ExamenG`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `RESEÑA`
--

LOCK TABLES `RESEÑA` WRITE;
/*!40000 ALTER TABLE `RESEÑA` DISABLE KEYS */;
/*!40000 ALTER TABLE `RESEÑA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `SERVICIO`
--

DROP TABLE IF EXISTS `SERVICIO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `SERVICIO` (
  `id_Servicio` int(11) NOT NULL AUTO_INCREMENT,
  `id_Usuario` int(11) NOT NULL,
  `nombre_Servicio` varchar(100) NOT NULL,
  `descripcion_Servicio` text DEFAULT NULL,
  `precio_Servicio` decimal(10,2) NOT NULL,
  `duracion_Servicio` varchar(50) DEFAULT NULL,
  `imagenURL_Servicio` varchar(255) DEFAULT NULL,
  `iconoURL_Servicio` varchar(255) DEFAULT NULL,
  `datecreate_Servicio` timestamp NULL DEFAULT current_timestamp(),
  `dateupdate_Servicio` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_Servicio`),
  KEY `id_Usuario` (`id_Usuario`),
  CONSTRAINT `id_FK_SERVICIO_USUARIO` FOREIGN KEY (`id_Usuario`) REFERENCES `USUARIO` (`id_Usuario`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `SERVICIO`
--

LOCK TABLES `SERVICIO` WRITE;
/*!40000 ALTER TABLE `SERVICIO` DISABLE KEYS */;
INSERT INTO `SERVICIO` VALUES
(1,2,'Consulta clínica','Evaluación integral de salud, diagnóstico preciso de síntomas y planes de medicina preventiva.',1800.00,'45 minutos','/activos/imgs/servicios/18c951dce678053a4e183d3a9110773d.jpg',NULL,'2026-11-12 15:01:04','2026-11-13 22:18:36'),
(2,2,'Control','Seguimiento médico de tratamientos, evolución postoperatoria y monitoreo de pacientes crónicos.',1000.00,'30 minutos','/activos/imgs/servicios/5980b7843bc04dba5f7b9f62caa6ddaf.jpg',NULL,'2026-11-12 15:01:04','2026-11-13 22:19:05'),
(3,1,'Traumatología y claudicaciones equinas','Diagnóstico de rengueras, evaluación del aparato locomotor y terapias de rehabilitación deportiva.',2500.00,'30 Minutos','/activos/imgs/servicios/a5d3b7b05bf5bd7ef399a0ed9387a26b.jpg',NULL,'2026-11-13 22:22:38','2026-11-13 22:31:49'),
(4,1,'Consulta Clínica General','Evaluación física completa de la mascota (revisión de temperatura, auscultación cardíaca y pulmonar, control de peso, piel, oídos y dentadura) para detectar afecciones de forma temprana o realizar seguimiento de salud.',1200.00,'30 Minutos','/activos/imgs/servicios/4936f0e1a754f39b82d21662d3dcec97.jpg',NULL,'2026-11-13 22:30:55','2026-11-13 22:30:55'),
(5,1,'Plan de Vacunación y Desparasitación','Aplicación de vacunas esenciales según la edad y especie (quíntuple, séxtuple, triple felina o antirrábica) acompañada de desparasitación interna y externa, con entrega o actualización de carné sanitario.',1650.00,'20 Minutos','/activos/imgs/servicios/3a8410e37c77cc773a0a15e34296da51.jpg',NULL,'2026-11-13 22:33:23','2026-11-13 22:33:23'),
(6,1,'Baño Sanitario y Peluquería Canina','Baño con shampoo dermatológico hipoalergénico, secado, cepillado profundo para retirar pelo muerto, limpieza de oídos, corte de uñas y recorte higiénico o estético adaptado a la raza.',1400.00,'1 hora y 30 minutos','/activos/imgs/servicios/0eb5068b7dbb9a74d5a668c2c663970e.jpg',NULL,'2026-11-13 22:35:24','2026-11-13 22:35:24');
/*!40000 ALTER TABLE `SERVICIO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TRATAMIENTO`
--

DROP TABLE IF EXISTS `TRATAMIENTO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `TRATAMIENTO` (
  `id_Tratamiento` int(11) NOT NULL AUTO_INCREMENT,
  `id_Consulta` int(11) NOT NULL,
  `descripcion_Tratamiento` text NOT NULL,
  PRIMARY KEY (`id_Tratamiento`),
  KEY `id_Consulta` (`id_Consulta`),
  CONSTRAINT `id_FK_TRATAMIENTO_CONSULTA` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TRATAMIENTO`
--

LOCK TABLES `TRATAMIENTO` WRITE;
/*!40000 ALTER TABLE `TRATAMIENTO` DISABLE KEYS */;
INSERT INTO `TRATAMIENTO` VALUES
(1,1,'Mantener plan sanitario habitual. Control segun indicacion profesional.');
/*!40000 ALTER TABLE `TRATAMIENTO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `TUTOR`
--

DROP TABLE IF EXISTS `TUTOR`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `TUTOR` (
  `id_Tutor` int(11) NOT NULL AUTO_INCREMENT,
  `documento_Tutor` varchar(20) NOT NULL,
  `nombre_Tutor` varchar(150) NOT NULL,
  `telefono_Tutor` varchar(50) DEFAULT NULL,
  `direccion_Tutor` varchar(255) DEFAULT NULL,
  `email_Tutor` varchar(150) DEFAULT NULL,
  `notas_Tutor` text DEFAULT NULL,
  `datecreate_Tutor` timestamp NULL DEFAULT current_timestamp(),
  `dateupdate_Tutor` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_Tutor`),
  UNIQUE KEY `documento_Tutor` (`documento_Tutor`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `TUTOR`
--

LOCK TABLES `TUTOR` WRITE;
/*!40000 ALTER TABLE `TUTOR` DISABLE KEYS */;
INSERT INTO `TUTOR` VALUES
(1,'22334455','Laura Benítez','099123456','Av. Italia 1234','laura@example.com','Tutora de Nala.','2026-11-12 15:01:04','2026-11-12 15:01:04'),
(2,'87654321','Sergio Medina','098765432','Camino Maldonado 456','sergio@example.com','Tutor de Relámpago y Milo.','2026-11-12 15:01:04','2026-11-12 15:01:04');
/*!40000 ALTER TABLE `TUTOR` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USUARIO`
--

DROP TABLE IF EXISTS `USUARIO`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `USUARIO` (
  `id_Usuario` int(11) NOT NULL AUTO_INCREMENT,
  `cedula_Usuario` varchar(20) NOT NULL,
  `nombre_Usuario` varchar(100) NOT NULL,
  `apellido_Usuario` varchar(100) NOT NULL,
  `email_Usuario` varchar(150) NOT NULL,
  `passw_Usuario` varchar(255) NOT NULL,
  `rol_Usuario` varchar(50) NOT NULL,
  `estado_Usuario` varchar(20) DEFAULT 'Activo',
  `lastlogin_Usuario` datetime DEFAULT NULL,
  `dateupdate_Usuario` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  PRIMARY KEY (`id_Usuario`),
  UNIQUE KEY `cedula_Usuario` (`cedula_Usuario`),
  UNIQUE KEY `email_Usuario` (`email_Usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USUARIO`
--

LOCK TABLES `USUARIO` WRITE;
/*!40000 ALTER TABLE `USUARIO` DISABLE KEYS */;
INSERT INTO `USUARIO` VALUES
(1,'12345678','María','Rodríguez','maria.directora@example.com','$2y$12$wFPUX1LQOjdWIPcjhLzuve1.7DAkrIwOgUt6TKa.PzedZ.UOUb1nS','Director','activo','2026-11-15 16:36:43','2026-11-15 19:36:43'),
(2,'53456789','Ana','Silva','ana.profesora@example.com','$2y$12$wFPUX1LQOjdWIPcjhLzuve1.7DAkrIwOgUt6TKa.PzedZ.UOUb1nS','Profesor','activo','2026-11-12 20:41:24','2026-11-13 22:33:30'),
(3,'11111122','Carlos','González','carlos.estudiante@example.com','$2y$12$wFPUX1LQOjdWIPcjhLzuve1.7DAkrIwOgUt6TKa.PzedZ.UOUb1nS','Estudiante','activo',NULL,'2026-11-12 23:26:41');
/*!40000 ALTER TABLE `USUARIO` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `USUARIO_CONSULTA`
--

DROP TABLE IF EXISTS `USUARIO_CONSULTA`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!40101 SET character_set_client = utf8mb4 */;
CREATE TABLE `USUARIO_CONSULTA` (
  `id_Usuario` int(11) NOT NULL,
  `id_Consulta` int(11) NOT NULL,
  PRIMARY KEY (`id_Usuario`,`id_Consulta`),
  KEY `id_Consulta` (`id_Consulta`),
  CONSTRAINT `USUARIO_CONSULTA_ibfk_1` FOREIGN KEY (`id_Usuario`) REFERENCES `USUARIO` (`id_Usuario`) ON DELETE CASCADE,
  CONSTRAINT `USUARIO_CONSULTA_ibfk_2` FOREIGN KEY (`id_Consulta`) REFERENCES `CONSULTA` (`id_Consulta`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `USUARIO_CONSULTA`
--

LOCK TABLES `USUARIO_CONSULTA` WRITE;
/*!40000 ALTER TABLE `USUARIO_CONSULTA` DISABLE KEYS */;
INSERT INTO `USUARIO_CONSULTA` VALUES
(2,1),
(2,2),
(2,3),
(3,1);
/*!40000 ALTER TABLE `USUARIO_CONSULTA` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Temporary table structure for view `vw_alertas_consultas_atrasadas`
--

DROP TABLE IF EXISTS `vw_alertas_consultas_atrasadas`;
/*!50001 DROP VIEW IF EXISTS `vw_alertas_consultas_atrasadas`*/;
SET @saved_cs_client     = @@character_set_client;
SET character_set_client = utf8mb4;
/*!50001 CREATE VIEW `vw_alertas_consultas_atrasadas` AS SELECT
 NULL AS `id_usuario`,
 NULL AS `id_Consulta`,
 NULL AS `fecha_hora_Consulta`,
 NULL AS `estado_proceso_Consulta`,
 NULL AS `tipo_Consulta`,
 NULL AS `motivo_Consulta`,
 NULL AS `nombre_Paciente`,
 NULL AS `nombre_Tutor` */;
SET character_set_client = @saved_cs_client;

--
-- Final view structure for view `vw_alertas_consultas_atrasadas`
--

/*!50001 DROP VIEW IF EXISTS `vw_alertas_consultas_atrasadas`*/;
/*!50001 SET @saved_cs_client          = @@character_set_client */;
/*!50001 SET @saved_cs_results         = @@character_set_results */;
/*!50001 SET @saved_col_connection     = @@collation_connection */;
/*!50001 SET character_set_client      = utf8mb4 */;
/*!50001 SET character_set_results     = utf8mb4 */;
/*!50001 SET collation_connection      = utf8mb4_general_ci */;
/*!50001 CREATE ALGORITHM=UNDEFINED */
/*!50013 DEFINER=`root`@`localhost` SQL SECURITY DEFINER */
/*!50001 VIEW `vw_alertas_consultas_atrasadas` AS select `uc`.`id_Usuario` AS `id_usuario`,`c`.`id_Consulta` AS `id_Consulta`,`c`.`fecha_hora_Consulta` AS `fecha_hora_Consulta`,`c`.`estado_proceso_Consulta` AS `estado_proceso_Consulta`,`c`.`tipo_Consulta` AS `tipo_Consulta`,`c`.`motivo_Consulta` AS `motivo_Consulta`,`p`.`nombre_Paciente` AS `nombre_Paciente`,`t`.`nombre_Tutor` AS `nombre_Tutor` from (((`USUARIO_CONSULTA` `uc` join `CONSULTA` `c` on(`c`.`id_Consulta` = `uc`.`id_Consulta`)) join `PACIENTE` `p` on(`p`.`id_Paciente` = `c`.`id_Paciente`)) join `TUTOR` `t` on(`t`.`id_Tutor` = `p`.`id_Tutor`)) where `c`.`fecha_hora_Consulta` < current_timestamp() and lcase(trim(`c`.`estado_proceso_Consulta`)) in ('pendiente','en curso') */;
/*!50001 SET character_set_client      = @saved_cs_client */;
/*!50001 SET character_set_results     = @saved_cs_results */;
/*!50001 SET collation_connection      = @saved_col_connection */;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2026-11-16 13:58:25
