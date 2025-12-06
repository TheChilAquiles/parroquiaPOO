-- --------------------------------------------------------
-- Host:                         127.0.0.1
-- Versión del servidor:         8.4.3 - MySQL Community Server - GPL
-- SO del servidor:              Win64
-- HeidiSQL Versión:             12.8.0.6908
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;


-- Volcando estructura de base de datos para parroquia
DROP DATABASE IF EXISTS `parroquia`;
CREATE DATABASE IF NOT EXISTS `parroquia` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `parroquia`;

-- Volcando estructura para tabla parroquia.certificados
DROP TABLE IF EXISTS `certificados`;
CREATE TABLE IF NOT EXISTS `certificados` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_generador_id` bigint DEFAULT NULL,
  `solicitante_id` bigint NOT NULL COMMENT 'Feligr├®s que solicita el certificado',
  `parentesco_id` bigint DEFAULT NULL COMMENT 'Tipo de parentesco si es familiar (NULL si es propio)',
  `feligres_certificado_id` bigint DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL COMMENT 'Fecha y hora del pago completado',
  `fecha_generacion` datetime DEFAULT NULL COMMENT 'Fecha y hora de generaci├│n del PDF',
  `fecha_expiracion` date DEFAULT NULL COMMENT 'Fecha de expiraci├│n (30 d├¡as desde generaci├│n)',
  `tipo_certificado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `motivo_solicitud` varchar(500) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL COMMENT 'Motivo por el cual solicita el certificado',
  `fecha_solicitud` datetime NOT NULL COMMENT 'Fecha y hora de la solicitud',
  `sacramento_id` bigint DEFAULT NULL,
  `ruta_archivo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado` enum('pendiente_pago','generado','descargado','expirado','vigente') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT 'pendiente_pago' COMMENT 'Estado del certificado: pendiente_pago, generado, descargado, expirado',
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_generador_id` (`usuario_generador_id`),
  KEY `feligres_certificado_id` (`feligres_certificado_id`),
  KEY `sacramento_id` (`sacramento_id`),
  KEY `certificados_parentesco_fk` (`parentesco_id`),
  KEY `idx_certificados_solicitante` (`solicitante_id`),
  KEY `idx_certificados_estado` (`estado`),
  KEY `idx_certificados_expiracion` (`fecha_expiracion`),
  KEY `idx_certificados_feligres` (`feligres_certificado_id`),
  CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`usuario_generador_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `certificados_ibfk_2` FOREIGN KEY (`feligres_certificado_id`) REFERENCES `feligreses` (`id`),
  CONSTRAINT `certificados_ibfk_3` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramentos` (`id`),
  CONSTRAINT `certificados_parentesco_fk` FOREIGN KEY (`parentesco_id`) REFERENCES `parentescos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT `certificados_solicitante_fk` FOREIGN KEY (`solicitante_id`) REFERENCES `feligreses` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.certificados: ~6 rows (aproximadamente)
DELETE FROM `certificados`;
INSERT INTO `certificados` (`id`, `usuario_generador_id`, `solicitante_id`, `parentesco_id`, `feligres_certificado_id`, `fecha_emision`, `fecha_pago`, `fecha_generacion`, `fecha_expiracion`, `tipo_certificado`, `motivo_solicitud`, `fecha_solicitud`, `sacramento_id`, `ruta_archivo`, `estado`, `estado_registro`) VALUES
	(1, NULL, 1, NULL, 1, '2025-12-06', NULL, NULL, NULL, 'Bautismo', NULL, '2025-12-06 13:49:22', 1, NULL, 'generado', NULL),
	(2, 1, 1, NULL, 1, NULL, '2025-12-06 15:57:30', '2025-12-06 15:57:39', '2026-01-05', 'Bautizo', 'Generación directa', '2025-12-06 15:57:30', 1, 'certificados_generados/cert_2_bautismo_20251206_205739.pdf', 'descargado', NULL),
	(3, 1, 1, NULL, 1, NULL, '2025-12-06 15:58:28', '2025-12-06 15:58:29', '2026-01-05', 'Bautizo', 'Generación directa', '2025-12-06 15:58:28', 1, 'certificados_generados/cert_3_bautismo_20251206_205829.pdf', 'descargado', NULL),
	(4, 1, 1, NULL, 1, NULL, '2025-12-06 16:23:31', '2025-12-06 16:23:31', '2026-01-05', 'Bautizo', 'Generación directa', '2025-12-06 16:01:19', 1, 'certificados_generados/cert_4_bautismo_20251206_212331.pdf', 'generado', NULL),
	(5, 1, 1, NULL, 1, NULL, '2025-12-06 16:07:48', '2025-12-06 16:07:48', '2026-01-05', 'Bautizo', 'Generación directa', '2025-12-06 16:07:47', 1, 'certificados_generados/cert_5_bautismo_20251206_210748.pdf', 'generado', NULL),
	(6, 1, 1, NULL, 1, NULL, '2025-12-06 16:10:31', '2025-12-06 16:10:32', '2026-01-05', 'Bautizo', 'Generación directa', '2025-12-06 16:10:31', 1, 'certificados_generados/cert_6_bautismo_20251206_211032.pdf', 'generado', NULL);

-- Volcando estructura para tabla parroquia.configuraciones
DROP TABLE IF EXISTS `configuraciones`;
CREATE TABLE IF NOT EXISTS `configuraciones` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL COMMENT 'Clave única de configuración',
  `valor` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci COMMENT 'Valor de la configuración',
  `tipo` enum('texto','numero','booleano','json','email','url') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT 'texto' COMMENT 'Tipo de dato',
  `categoria` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT 'general' COMMENT 'Categoría de la configuración',
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci COMMENT 'Descripción de la configuración',
  `editable` tinyint(1) DEFAULT '1' COMMENT 'Si es editable por los administradores',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_actualizacion` bigint DEFAULT NULL COMMENT 'ID del usuario que actualizó',
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`),
  KEY `categoria` (`categoria`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci COMMENT='Configuraciones generales del sistema';

-- Volcando datos para la tabla parroquia.configuraciones: ~9 rows (aproximadamente)
DELETE FROM `configuraciones`;
INSERT INTO `configuraciones` (`id`, `clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`, `fecha_creacion`, `fecha_actualizacion`, `usuario_actualizacion`) VALUES
	(1, 'parroquia_nombre', 'Parroquia San Francisco de Asís', 'texto', 'parroquia', 'Nombre de la parroquia', 1, '2025-12-06 13:49:15', '2025-12-06 13:49:15', NULL),
	(2, 'parroquia_telefono', '601 402 3526', 'texto', 'parroquia', 'Teléfono principal', 1, '2025-12-06 13:49:16', '2025-12-06 13:49:16', NULL),
	(3, 'cert_precio_bautismo', '25000', 'numero', 'certificados', 'Precio Bautismo', 1, '2025-12-06 13:49:16', '2025-12-06 13:49:16', NULL),
	(4, 'cert_precio_matrimonio', '35000', 'numero', 'certificados', 'Precio Matrimonio', 1, '2025-12-06 13:49:16', '2025-12-06 13:49:16', NULL),
	(5, 'pago_moneda', 'COP', 'texto', 'pagos', 'Moneda', 1, '2025-12-06 13:49:16', '2025-12-06 13:49:16', NULL),
	(6, 'pago_modo', 'sandbox', 'texto', 'pagos', 'Modo pasarela', 1, '2025-12-06 13:49:16', '2025-12-06 13:49:16', NULL),
	(7, 'sistema_registro_abierto', '1', 'booleano', 'sistema', 'Registro abierto', 1, '2025-12-06 13:49:16', '2025-12-06 13:49:16', NULL),
	(8, 'cert_precio_confirmacion', '20000', 'numero', 'certificados', 'Precio Confirmación', 1, '2025-12-06 16:35:44', '2025-12-06 16:35:44', NULL),
	(9, 'cert_precio_defuncion', '15000', 'numero', 'certificados', 'Precio Defunción', 1, '2025-12-06 16:35:44', '2025-12-06 16:35:44', NULL);

-- Volcando estructura para tabla parroquia.documento_tipos
DROP TABLE IF EXISTS `documento_tipos`;
CREATE TABLE IF NOT EXISTS `documento_tipos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.documento_tipos: ~6 rows (aproximadamente)
DELETE FROM `documento_tipos`;
INSERT INTO `documento_tipos` (`id`, `tipo`, `estado_registro`) VALUES
	(1, 'Cédula de Ciudadanía', NULL),
	(2, 'Tarjeta de Identidad', NULL),
	(3, 'Cédula de Extranjería', NULL),
	(4, 'Registro Civil', NULL),
	(5, 'Permiso Especial', NULL),
	(6, 'NIT', NULL);

-- Volcando estructura para tabla parroquia.feligreses
DROP TABLE IF EXISTS `feligreses`;
CREATE TABLE IF NOT EXISTS `feligreses` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint DEFAULT NULL,
  `tipo_documento_id` bigint DEFAULT NULL,
  `numero_documento` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `telefono` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `direccion` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `primer_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `segundo_nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `primer_apellido` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `segundo_apellido` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `tipo_documento_id` (`tipo_documento_id`),
  CONSTRAINT `feligreses_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.feligreses: ~15 rows (aproximadamente)
DELETE FROM `feligreses`;
INSERT INTO `feligreses` (`id`, `usuario_id`, `tipo_documento_id`, `numero_documento`, `telefono`, `direccion`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `fecha_nacimiento`, `estado_registro`) VALUES
	(1, 10, 1, '1001', '3001234567', 'Calle Falsa 123', 'Juan', '', 'Perez', '', NULL, NULL),
	(2, 11, 1, '1002', '3001234567', 'Calle Falsa 123', 'Maria', '', 'Gomez', '', NULL, NULL),
	(3, 12, 1, '1003', '3001234567', 'Calle Falsa 123', 'Pedro', '', 'Rodriguez', '', NULL, NULL),
	(4, 13, 1, '1004', '3001234567', 'Calle Falsa 123', 'Ana', '', 'Martinez', '', NULL, NULL),
	(5, 14, 1, '1005', '3001234567', 'Calle Falsa 123', 'Roberto', '', 'Diaz', '', NULL, NULL),
	(6, NULL, 1, '2001', '0000000', 'N/A', 'Carlos', '', 'Perez', '', NULL, NULL),
	(7, NULL, 1, '2002', '0000000', 'N/A', 'Luisa', '', 'Mendez', '', NULL, NULL),
	(8, NULL, 1, '2003', '0000000', 'N/A', 'Andres', '', 'Lopez', '', NULL, NULL),
	(9, NULL, 1, '2004', '0000000', 'N/A', 'Elena', '', 'Gacia', '', NULL, NULL),
	(10, NULL, 1, '2005', '0000000', 'N/A', 'Marcos', '', 'Ruiz', '', NULL, NULL),
	(11, NULL, 1, '2006', '0000000', 'N/A', 'Sofia', '', 'Vargas', '', NULL, NULL),
	(12, NULL, 1, '12395', '3111111', 'sad asd asd assd ', 'asd', 'asdsad', 'sadsad', 'asdsad', NULL, NULL),
	(13, NULL, 1, '12395', '3111111', 'sad asd asd assd ', 'asd', 'asdsad', 'sadsad', 'asdsad', NULL, NULL),
	(14, 15, 1, '11', '3111111', 'sad asd asd assd ', 'asd', '', 'asd', '', NULL, NULL),
	(15, 1, 1, '1231233', '123123123', 'sad asd asd assd ', 'asdqwe', '12', 'asqwe', 'Rusbel', '1997-03-01', NULL);

-- Volcando estructura para tabla parroquia.grupos
DROP TABLE IF EXISTS `grupos`;
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.grupos: ~2 rows (aproximadamente)
DELETE FROM `grupos`;
INSERT INTO `grupos` (`id`, `nombre`, `estado_registro`) VALUES
	(1, 'Legión de María', NULL),
	(2, 'Grupo Juvenil', NULL);

-- Volcando estructura para tabla parroquia.grupo_roles
DROP TABLE IF EXISTS `grupo_roles`;
CREATE TABLE IF NOT EXISTS `grupo_roles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.grupo_roles: ~3 rows (aproximadamente)
DELETE FROM `grupo_roles`;
INSERT INTO `grupo_roles` (`id`, `rol`, `estado_registro`) VALUES
	(1, 'Miembro', NULL),
	(2, 'Coordinador', NULL),
	(3, 'Tesorero', NULL);

-- Volcando estructura para tabla parroquia.libros
DROP TABLE IF EXISTS `libros`;
CREATE TABLE IF NOT EXISTS `libros` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `libro_tipo_id` bigint DEFAULT NULL,
  `numero` int DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `libro_tipo_id` (`libro_tipo_id`),
  CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`libro_tipo_id`) REFERENCES `libro_tipo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.libros: ~4 rows (aproximadamente)
DELETE FROM `libros`;
INSERT INTO `libros` (`id`, `libro_tipo_id`, `numero`, `estado_registro`) VALUES
	(1, 1, 1, NULL),
	(2, 2, 1, NULL),
	(3, 3, 1, NULL),
	(4, 4, 1, NULL);

-- Volcando estructura para tabla parroquia.libro_tipo
DROP TABLE IF EXISTS `libro_tipo`;
CREATE TABLE IF NOT EXISTS `libro_tipo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.libro_tipo: ~4 rows (aproximadamente)
DELETE FROM `libro_tipo`;
INSERT INTO `libro_tipo` (`id`, `tipo`, `estado_registro`) VALUES
	(1, 'Bautizos', NULL),
	(2, 'Confirmaciones', NULL),
	(3, 'Defunciones', NULL),
	(4, 'Matrimonios', NULL);

-- Volcando estructura para tabla parroquia.noticias
DROP TABLE IF EXISTS `noticias`;
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint NOT NULL,
  `titulo` varchar(150) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `imagen` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.noticias: ~1 rows (aproximadamente)
DELETE FROM `noticias`;
INSERT INTO `noticias` (`id`, `id_usuario`, `titulo`, `descripcion`, `imagen`, `fecha_publicacion`, `estado_registro`) VALUES
	(1, 1, 'Inscripciones Abiertas', 'Ya pueden inscribirse para confirmaciones.', 'confirmacion.jpg', '2025-12-06 13:49:22', NULL);

-- Volcando estructura para tabla parroquia.pagos
DROP TABLE IF EXISTS `pagos`;
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `certificado_id` bigint DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `estado` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `tipo_pago_id` int DEFAULT NULL,
  `transaction_id` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL COMMENT 'ID de transacción del gateway de pago (Stripe, PayPal, etc.)',
  `tipo_concepto` enum('certificado','donacion','evento','otro') CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT 'certificado' COMMENT 'Concepto del pago',
  PRIMARY KEY (`id`),
  KEY `certificado_id` (`certificado_id`),
  KEY `tipo_pago_id` (`tipo_pago_id`),
  KEY `idx_transaction_id` (`transaction_id`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificados` (`id`),
  CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipos_pago` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.pagos: ~2 rows (aproximadamente)
DELETE FROM `pagos`;
INSERT INTO `pagos` (`id`, `certificado_id`, `valor`, `estado`, `fecha_pago`, `tipo_pago_id`, `transaction_id`, `tipo_concepto`) VALUES
	(1, 1, 25000, 'pagado', '2025-12-06 13:49:22', 1, NULL, 'certificado'),
	(2, 4, 10000, 'pagado', '2025-12-06 16:23:31', 3, NULL, 'certificado');

-- Volcando estructura para tabla parroquia.pago_ordenes
DROP TABLE IF EXISTS `pago_ordenes`;
CREATE TABLE IF NOT EXISTS `pago_ordenes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `certificado_id` bigint NOT NULL,
  `order_number` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado','cancelado') COLLATE utf8mb4_unicode_ci DEFAULT 'pendiente',
  `transaction_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `metadata` text COLLATE utf8mb4_unicode_ci COMMENT 'JSON con datos adicionales del pago',
  `fecha_creacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order_number` (`order_number`),
  KEY `idx_order_number` (`order_number`),
  KEY `idx_certificado` (`certificado_id`),
  KEY `idx_estado` (`estado`),
  KEY `idx_transaction` (`transaction_id`),
  CONSTRAINT `pago_ordenes_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificados` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='??rdenes de pago procesadas a trav??s de PaymentsWay (VePay)';

-- Volcando datos para la tabla parroquia.pago_ordenes: ~1 rows (aproximadamente)
DELETE FROM `pago_ordenes`;
INSERT INTO `pago_ordenes` (`id`, `certificado_id`, `order_number`, `amount`, `estado`, `transaction_id`, `metadata`, `fecha_creacion`, `fecha_actualizacion`) VALUES
	(1, 1, 'ORD-1765046962', 25000.00, 'pendiente', NULL, NULL, '2025-12-06 18:49:22', '2025-12-06 18:49:22');

-- Volcando estructura para tabla parroquia.parentescos
DROP TABLE IF EXISTS `parentescos`;
CREATE TABLE IF NOT EXISTS `parentescos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `parentesco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.parentescos: ~6 rows (aproximadamente)
DELETE FROM `parentescos`;
INSERT INTO `parentescos` (`id`, `parentesco`, `estado_registro`) VALUES
	(1, 'Padre', NULL),
	(2, 'Madre', NULL),
	(3, 'Hermano/a', NULL),
	(4, 'Abuelo/a', NULL),
	(5, 'Tío/a', NULL),
	(6, 'Esposo/a', NULL);

-- Volcando estructura para tabla parroquia.parientes
DROP TABLE IF EXISTS `parientes`;
CREATE TABLE IF NOT EXISTS `parientes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `parentesco_id` bigint DEFAULT NULL,
  `feligres_sujeto_id` bigint DEFAULT NULL,
  `feligres_pariente_id` bigint DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `parentesco_id` (`parentesco_id`),
  KEY `feligres_sujeto_id` (`feligres_sujeto_id`),
  KEY `feligres_pariente_id` (`feligres_pariente_id`),
  CONSTRAINT `parientes_ibfk_1` FOREIGN KEY (`parentesco_id`) REFERENCES `parentescos` (`id`),
  CONSTRAINT `parientes_ibfk_2` FOREIGN KEY (`feligres_sujeto_id`) REFERENCES `feligreses` (`id`),
  CONSTRAINT `parientes_ibfk_3` FOREIGN KEY (`feligres_pariente_id`) REFERENCES `feligreses` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.parientes: ~0 rows (aproximadamente)
DELETE FROM `parientes`;

-- Volcando estructura para tabla parroquia.participantes
DROP TABLE IF EXISTS `participantes`;
CREATE TABLE IF NOT EXISTS `participantes` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `feligres_id` bigint DEFAULT NULL,
  `sacramento_id` bigint DEFAULT NULL,
  `rol_participante_id` bigint DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `feligres_id` (`feligres_id`),
  KEY `sacramento_id` (`sacramento_id`),
  KEY `rol_participante_id` (`rol_participante_id`),
  CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`feligres_id`) REFERENCES `feligreses` (`id`),
  CONSTRAINT `participantes_ibfk_2` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramentos` (`id`),
  CONSTRAINT `participantes_ibfk_3` FOREIGN KEY (`rol_participante_id`) REFERENCES `participantes_rol` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.participantes: ~12 rows (aproximadamente)
DELETE FROM `participantes`;
INSERT INTO `participantes` (`id`, `feligres_id`, `sacramento_id`, `rol_participante_id`, `estado_registro`) VALUES
	(1, 1, 1, 1, NULL),
	(2, 6, 1, 4, NULL),
	(3, 7, 1, 5, NULL),
	(4, 8, 1, 2, NULL),
	(5, 9, 1, 3, NULL),
	(6, 2, 2, 6, NULL),
	(7, 8, 2, 2, NULL),
	(8, 5, 3, 10, NULL),
	(9, 3, 4, 7, NULL),
	(10, 4, 4, 8, NULL),
	(11, 10, 4, 9, NULL),
	(12, 11, 4, 9, NULL);

-- Volcando estructura para tabla parroquia.participantes_rol
DROP TABLE IF EXISTS `participantes_rol`;
CREATE TABLE IF NOT EXISTS `participantes_rol` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.participantes_rol: ~10 rows (aproximadamente)
DELETE FROM `participantes_rol`;
INSERT INTO `participantes_rol` (`id`, `rol`, `estado_registro`) VALUES
	(1, 'Bautizado', NULL),
	(2, 'Padrino', NULL),
	(3, 'Madrina', NULL),
	(4, 'Padre', NULL),
	(5, 'Madre', NULL),
	(6, 'Confirmando', NULL),
	(7, 'Esposo', NULL),
	(8, 'Esposa', NULL),
	(9, 'Testigo', NULL),
	(10, 'Difunto', NULL);

-- Volcando estructura para tabla parroquia.reportes
DROP TABLE IF EXISTS `reportes`;
CREATE TABLE IF NOT EXISTS `reportes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pagos` bigint NOT NULL,
  `titulo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `categoria` varchar(100) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pagos` (`id_pagos`),
  CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_pagos`) REFERENCES `pagos` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.reportes: ~0 rows (aproximadamente)
DELETE FROM `reportes`;

-- Volcando estructura para tabla parroquia.sacramentos
DROP TABLE IF EXISTS `sacramentos`;
CREATE TABLE IF NOT EXISTS `sacramentos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `libro_id` bigint DEFAULT NULL,
  `tipo_sacramento_id` bigint DEFAULT NULL,
  `acta` int NOT NULL,
  `folio` int NOT NULL,
  `fecha_generacion` date NOT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `libro_id` (`libro_id`),
  KEY `tipo_sacramento_id` (`tipo_sacramento_id`),
  CONSTRAINT `sacramentos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`),
  CONSTRAINT `sacramentos_ibfk_2` FOREIGN KEY (`tipo_sacramento_id`) REFERENCES `sacramento_tipo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.sacramentos: ~4 rows (aproximadamente)
DELETE FROM `sacramentos`;
INSERT INTO `sacramentos` (`id`, `libro_id`, `tipo_sacramento_id`, `acta`, `folio`, `fecha_generacion`, `estado_registro`) VALUES
	(1, 1, 1, 101, 10, '2000-01-15', NULL),
	(2, 2, 2, 201, 20, '2015-06-20', NULL),
	(3, 3, 3, 301, 30, '2023-11-01', NULL),
	(4, 4, 4, 401, 40, '2024-02-14', NULL);

-- Volcando estructura para tabla parroquia.sacramento_tipo
DROP TABLE IF EXISTS `sacramento_tipo`;
CREATE TABLE IF NOT EXISTS `sacramento_tipo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.sacramento_tipo: ~4 rows (aproximadamente)
DELETE FROM `sacramento_tipo`;
INSERT INTO `sacramento_tipo` (`id`, `tipo`, `estado_registro`) VALUES
	(1, 'Bautizo', NULL),
	(2, 'Confirmación', NULL),
	(3, 'Defunción', NULL),
	(4, 'Matrimonio', NULL);

-- Volcando estructura para tabla parroquia.tipos_pago
DROP TABLE IF EXISTS `tipos_pago`;
CREATE TABLE IF NOT EXISTS `tipos_pago` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.tipos_pago: ~4 rows (aproximadamente)
DELETE FROM `tipos_pago`;
INSERT INTO `tipos_pago` (`id`, `descripcion`) VALUES
	(1, 'Efectivo'),
	(2, 'Tarjeta Crédito'),
	(3, 'Transferencia'),
	(4, 'Pasarela Web');

-- Volcando estructura para tabla parroquia.usuarios
DROP TABLE IF EXISTS `usuarios`;
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_rol_id` bigint DEFAULT NULL,
  `email` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `email_confirmed` tinyint(1) DEFAULT '0',
  `contraseña` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `datos_completos` tinyint(1) DEFAULT '0',
  `estado_registro` datetime DEFAULT NULL,
  `reset_token` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_rol_id` (`usuario_rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`usuario_rol_id`) REFERENCES `usuario_roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.usuarios: ~8 rows (aproximadamente)
DELETE FROM `usuarios`;
INSERT INTO `usuarios` (`id`, `usuario_rol_id`, `email`, `email_confirmed`, `contraseña`, `datos_completos`, `estado_registro`, `reset_token`, `reset_token_expires`) VALUES
	(1, 2, 'admin@parroquia.com', 1, '$2y$12$u.R9bZ9oK8ksMXCK/wJ0ne5DxMuRrC4WqFPKMQWBx9j7l2QTuo6Fu', 1, NULL, NULL, NULL),
	(2, 3, 'secretaria@parroquia.com', 1, '$2y$12$NE1zMPwgcWnWzRkCEM14s.qPhQrgEZ/PDeztg.JSzBDZAWaBVR07G', 1, NULL, NULL, NULL),
	(10, 1, 'juan@mail.com', 1, '$2y$12$NE1zMPwgcWnWzRkCEM14s.qPhQrgEZ/PDeztg.JSzBDZAWaBVR07G', 1, NULL, NULL, NULL),
	(11, 1, 'maria@mail.com', 1, '$2y$12$NE1zMPwgcWnWzRkCEM14s.qPhQrgEZ/PDeztg.JSzBDZAWaBVR07G', 1, NULL, NULL, NULL),
	(12, 1, 'pedro@mail.com', 1, '$2y$12$NE1zMPwgcWnWzRkCEM14s.qPhQrgEZ/PDeztg.JSzBDZAWaBVR07G', 1, NULL, NULL, NULL),
	(13, 1, 'ana@mail.com', 1, '$2y$12$NE1zMPwgcWnWzRkCEM14s.qPhQrgEZ/PDeztg.JSzBDZAWaBVR07G', 1, NULL, NULL, NULL),
	(14, 1, 'roberto@mail.com', 1, '$2y$12$NE1zMPwgcWnWzRkCEM14s.qPhQrgEZ/PDeztg.JSzBDZAWaBVR07G', 1, NULL, NULL, NULL),
	(15, 1, 'rusbelit1@gmail.com', 0, '$2y$12$Vr.5brjeui14Qf1Q94LZfexLhdPlj1WELtkX/8h4/wB7pNdVb6tqW', 1, NULL, NULL, NULL);

-- Volcando estructura para tabla parroquia.usuario_grupos
DROP TABLE IF EXISTS `usuario_grupos`;
CREATE TABLE IF NOT EXISTS `usuario_grupos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint DEFAULT NULL,
  `grupo_parroquial_id` bigint DEFAULT NULL,
  `grupo_rol_id` bigint DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `grupo_parroquial_id` (`grupo_parroquial_id`),
  KEY `grupo_rol_id` (`grupo_rol_id`),
  CONSTRAINT `usuario_grupos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  CONSTRAINT `usuario_grupos_ibfk_2` FOREIGN KEY (`grupo_parroquial_id`) REFERENCES `grupos` (`id`),
  CONSTRAINT `usuario_grupos_ibfk_3` FOREIGN KEY (`grupo_rol_id`) REFERENCES `grupo_roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.usuario_grupos: ~1 rows (aproximadamente)
DELETE FROM `usuario_grupos`;
INSERT INTO `usuario_grupos` (`id`, `usuario_id`, `grupo_parroquial_id`, `grupo_rol_id`, `estado_registro`) VALUES
	(1, 11, 2, 1, NULL);

-- Volcando estructura para tabla parroquia.usuario_roles
DROP TABLE IF EXISTS `usuario_roles`;
CREATE TABLE IF NOT EXISTS `usuario_roles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.usuario_roles: ~3 rows (aproximadamente)
DELETE FROM `usuario_roles`;
INSERT INTO `usuario_roles` (`id`, `rol`, `estado_registro`) VALUES
	(1, 'Feligres', NULL),
	(2, 'Administrador', NULL),
	(3, 'Secretario', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
