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
CREATE DATABASE IF NOT EXISTS `parroquia` /*!40100 DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci */ /*!80016 DEFAULT ENCRYPTION='N' */;
USE `parroquia`;

-- Volcando estructura para tabla parroquia.certificados
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
  `tipo_certificado` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `motivo_solicitud` varchar(500) COLLATE utf8mb4_spanish2_ci DEFAULT NULL COMMENT 'Motivo por el cual solicita el certificado',
  `fecha_solicitud` datetime NOT NULL COMMENT 'Fecha y hora de la solicitud',
  `sacramento_id` bigint DEFAULT NULL,
  `ruta_archivo` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado` enum('pendiente_pago','generado','descargado','expirado','vigente') COLLATE utf8mb4_spanish2_ci DEFAULT 'pendiente_pago' COMMENT 'Estado del certificado: pendiente_pago, generado, descargado, expirado',
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
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.certificados: ~6 rows (aproximadamente)
INSERT INTO `certificados` (`id`, `usuario_generador_id`, `solicitante_id`, `parentesco_id`, `feligres_certificado_id`, `fecha_emision`, `fecha_pago`, `fecha_generacion`, `fecha_expiracion`, `tipo_certificado`, `motivo_solicitud`, `fecha_solicitud`, `sacramento_id`, `ruta_archivo`, `estado`, `estado_registro`) VALUES
	(101, 10, 1, NULL, 1, '2024-01-15', NULL, NULL, '2025-01-15', 'bautizo', NULL, '2025-11-03 09:53:07', 3, '/archivos/cert101.pdf', 'vigente', '0000-00-00 00:00:00'),
	(102, 11, 1, NULL, 1, '2023-12-10', NULL, NULL, '2024-12-10', 'confirmacion', NULL, '2025-11-03 09:53:07', 4, '/archivos/cert102.pdf', 'vigente', '0000-00-00 00:00:00'),
	(103, 12, 1, NULL, 1, '2023-11-20', NULL, NULL, '2024-11-20', 'matrimonio', NULL, '2025-11-03 09:53:07', 5, '/archivos/cert103.pdf', 'vigente', '0000-00-00 00:00:00'),
	(104, 13, 1, NULL, 1, '2024-02-05', NULL, NULL, '2025-02-05', 'bautizo', NULL, '2025-11-03 09:53:07', 6, '/archivos/cert104.pdf', 'vigente', '0000-00-00 00:00:00'),
	(105, 14, 1, NULL, 1, '2024-03-10', NULL, NULL, '2025-03-10', 'confirmacion', NULL, '2025-11-03 09:53:07', 7, '/archivos/cert105.pdf', 'vigente', '0000-00-00 00:00:00'),
	(106, 15, 1, NULL, 1, '2024-04-15', NULL, NULL, '2025-04-15', 'bautizo', NULL, '2025-11-03 09:53:07', 8, '/archivos/cert106.pdf', 'vigente', '0000-00-00 00:00:00');

-- Volcando estructura para tabla parroquia.documento_tipos
CREATE TABLE IF NOT EXISTS `documento_tipos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.documento_tipos: ~6 rows (aproximadamente)
INSERT INTO `documento_tipos` (`id`, `tipo`, `estado_registro`) VALUES
	(1, 'Cedula Ciudadania ', NULL),
	(2, 'Tarjeta Identidad', NULL),
	(3, 'Cedula extranjeria', NULL),
	(4, 'Registro Civil', NULL),
	(5, 'Permiso Especial', NULL),
	(6, 'Numero Identificación Tributaria', NULL);

-- Volcando estructura para tabla parroquia.feligreses
CREATE TABLE IF NOT EXISTS `feligreses` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_id` bigint DEFAULT NULL,
  `tipo_documento_id` bigint DEFAULT NULL,
  `numero_documento` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `telefono` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `direccion` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `primer_nombre` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `segundo_nombre` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `primer_apellido` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `segundo_apellido` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_id` (`usuario_id`),
  KEY `tipo_documento_id` (`tipo_documento_id`),
  CONSTRAINT `feligreses_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.feligreses: ~12 rows (aproximadamente)
INSERT INTO `feligreses` (`id`, `usuario_id`, `tipo_documento_id`, `numero_documento`, `telefono`, `direccion`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `estado_registro`) VALUES
	(1, NULL, 1, '123', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', NULL),
	(2, NULL, 2, '123', NULL, NULL, 'asd', 'asd', 'asd', 'asd', NULL),
	(3, NULL, 1, '123654', NULL, NULL, 'aas', 'asd', 'asd', 'asd', NULL),
	(4, NULL, 1, '6465', NULL, NULL, 'aas', '6546', 'asdasd', 'asd', NULL),
	(5, NULL, 1, '12354', NULL, NULL, 'Daniel', '12', 'asdasd', 'duran', NULL),
	(6, NULL, 1, '123485', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', NULL),
	(7, NULL, 1, '1236', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', NULL),
	(8, NULL, 1, '1239678', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', NULL),
	(9, NULL, 1, '12395', '3111111', 'sad asd asd assd ', 'asd', 'asdsad', 'sadsad', 'asdsad', NULL),
	(10, NULL, 3, '123', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', '2025-11-03 17:38:47'),
	(11, NULL, 1, '123456', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', '2025-11-03 17:38:42'),
	(12, NULL, 1, '12389', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', '2025-11-03 17:38:30');

-- Volcando estructura para tabla parroquia.grupos
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.grupos: ~2 rows (aproximadamente)
INSERT INTO `grupos` (`id`, `nombre`, `estado_registro`) VALUES
	(1, 'sasda', NULL),
	(2, 'aadsad', NULL);

-- Volcando estructura para tabla parroquia.grupo_roles
CREATE TABLE IF NOT EXISTS `grupo_roles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.grupo_roles: ~0 rows (aproximadamente)

-- Volcando estructura para tabla parroquia.libros
CREATE TABLE IF NOT EXISTS `libros` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `libro_tipo_id` bigint DEFAULT NULL,
  `numero` int DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `libro_tipo_id` (`libro_tipo_id`),
  CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`libro_tipo_id`) REFERENCES `libro_tipo` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.libros: ~11 rows (aproximadamente)
INSERT INTO `libros` (`id`, `libro_tipo_id`, `numero`, `estado_registro`) VALUES
	(2, 1, 1, NULL),
	(3, 1, 2, NULL),
	(4, 1, 3, NULL),
	(5, 1, 4, NULL),
	(6, 1, 5, NULL),
	(7, 1, 6, NULL),
	(8, 1, 7, NULL),
	(9, 1, 8, NULL),
	(10, 2, 1, NULL),
	(11, 3, 1, NULL),
	(12, 4, 1, NULL);

-- Volcando estructura para tabla parroquia.libro_tipo
CREATE TABLE IF NOT EXISTS `libro_tipo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.libro_tipo: ~4 rows (aproximadamente)
INSERT INTO `libro_tipo` (`id`, `tipo`, `estado_registro`) VALUES
	(1, 'Bautizos', NULL),
	(2, 'Confirmaciones', NULL),
	(3, 'Defunciones', NULL),
	(4, 'Matrimonios', NULL);

-- Volcando estructura para tabla parroquia.noticias
CREATE TABLE IF NOT EXISTS `noticias` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `id_usuario` bigint NOT NULL,
  `titulo` varchar(150) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` longtext COLLATE utf8mb4_spanish2_ci NOT NULL,
  `imagen` longtext COLLATE utf8mb4_spanish2_ci NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_usuario` (`id_usuario`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.noticias: ~8 rows (aproximadamente)
INSERT INTO `noticias` (`id`, `id_usuario`, `titulo`, `descripcion`, `imagen`, `fecha_publicacion`, `estado_registro`) VALUES
	(1, 11, 'Hola5', 'Hola Hola Hola Hola Hola Hola Hola', 'assets/img/noticias/68d9814e7604e-Diagrama_Lógico_v11.png', '2025-09-18 00:28:46', '2025-09-28 23:40:09'),
	(2, 11, 'sadasd', 'asdas', 'assets/img/noticias/noticia_68d9bef0721ae5.27953340-MerParroquiav9.png', '2025-09-28 18:04:16', '2025-09-29 01:17:45'),
	(3, 11, 'dsfsf', 'dfsdf asdasd asd', 'assets/img/noticias/noticia_68d9c1d7d61ed6.49839548-Diagrama_Lógico_v11.png', '2025-09-28 18:16:39', '2025-10-24 03:29:02'),
	(4, 20, 'asdsad', 'asdasd  sadasd asdas d asd asda sdad sd', 'assets/img/noticias/noticia_68fad44ea36a88.56064286.png', '2025-10-23 20:20:14', NULL),
	(5, 20, 'dsfsf', 'asdsadasd', 'assets/img/noticias/noticia_68fad496ab18f5.58084807.png', '2025-10-23 20:21:26', NULL),
	(6, 20, 'dsfsf', 'asdsadasd', 'assets/img/noticias/noticia_68fad5a2923111.00968383.png', '2025-10-23 20:25:54', NULL),
	(7, 20, 'asdsad', 'asdasd asd sad sads adas d', 'assets/img/noticias/noticia_68fad5bf7c0e54.83656461.png', '2025-10-23 20:26:23', NULL),
	(8, 20, 'asdasdasdasd', 'asd asda sda sasdasdasd', 'assets/img/noticias/noticia_68fad63cb9e9c2.26316607.png', '2025-10-23 20:28:28', NULL);

-- Volcando estructura para tabla parroquia.pagos
CREATE TABLE IF NOT EXISTS `pagos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `certificado_id` bigint DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `estado` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `tipo_pago_id` int DEFAULT NULL,
  `tipo_concepto` enum('certificado','donacion','evento','otro') COLLATE utf8mb4_spanish2_ci DEFAULT 'certificado' COMMENT 'Concepto del pago',
  PRIMARY KEY (`id`),
  KEY `certificado_id` (`certificado_id`),
  KEY `tipo_pago_id` (`tipo_pago_id`),
  CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificados` (`id`),
  CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipos_pago` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.pagos: ~6 rows (aproximadamente)
INSERT INTO `pagos` (`id`, `certificado_id`, `valor`, `estado`, `fecha_pago`, `tipo_pago_id`, `tipo_concepto`) VALUES
	(3, 103, 1800.75, 'completo', '2025-09-03 16:34:45', 1, 'certificado'),
	(4, 104, 3200, 'cancelado', NULL, 3, 'certificado'),
	(5, 105, 2100, 'completo', NULL, 2, 'certificado'),
	(7, 103, 1800.75, 'completo', NULL, 1, 'certificado'),
	(17, 104, 255, 'PAGADO', '2025-09-25 16:37:11', 3, 'certificado'),
	(21, 106, 2500, 'PAGADO', '2025-09-25 16:48:37', 3, 'certificado');

-- Volcando estructura para tabla parroquia.parentescos
CREATE TABLE IF NOT EXISTS `parentescos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `parentesco` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.parentescos: ~16 rows (aproximadamente)
INSERT INTO `parentescos` (`id`, `parentesco`, `estado_registro`) VALUES
	(1, 'Abuela', NULL),
	(2, 'Abuelo', NULL),
	(3, 'Madre', NULL),
	(4, 'Padre', NULL),
	(5, 'Hermano', NULL),
	(6, 'Hermana', NULL),
	(7, 'Tío', NULL),
	(8, 'Tía', NULL),
	(9, 'Primo', NULL),
	(10, 'Prima', NULL),
	(11, 'Hijo', NULL),
	(12, 'Hija', NULL),
	(13, 'Esposo', NULL),
	(14, 'Esposa', NULL),
	(15, 'Madrina', NULL),
	(16, 'Padrino', NULL);

-- Volcando estructura para tabla parroquia.parientes
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

-- Volcando estructura para tabla parroquia.participantes
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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.participantes: ~15 rows (aproximadamente)
INSERT INTO `participantes` (`id`, `feligres_id`, `sacramento_id`, `rol_participante_id`, `estado_registro`) VALUES
	(9, 1, 33, 10, NULL),
	(10, 1, 37, 10, NULL),
	(11, 3, 37, 1, NULL),
	(12, 4, 37, 8, NULL),
	(13, 5, 37, 11, NULL),
	(14, 6, 37, 9, NULL),
	(15, 1, 38, 1, NULL),
	(16, 3, 38, 6, NULL),
	(17, 7, 38, 9, NULL),
	(18, 8, 38, 8, NULL),
	(19, 2, 39, 10, NULL),
	(20, 1, 39, 1, NULL),
	(21, 10, 39, 11, NULL),
	(22, 11, 39, 9, NULL),
	(23, 12, 39, 8, NULL);

-- Volcando estructura para tabla parroquia.participantes_rol
CREATE TABLE IF NOT EXISTS `participantes_rol` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.participantes_rol: ~15 rows (aproximadamente)
INSERT INTO `participantes_rol` (`id`, `rol`, `estado_registro`) VALUES
	(1, 'Bautizado', NULL),
	(2, 'Confirmando', NULL),
	(3, 'Difunto', NULL),
	(4, 'Esposo', NULL),
	(5, 'Esposa', NULL),
	(6, 'Padre', NULL),
	(7, 'Madre', NULL),
	(8, 'Padrino', NULL),
	(9, 'Madrina', NULL),
	(10, 'Abuelo', NULL),
	(11, 'Abuela', NULL),
	(12, 'Esposo Padrino', NULL),
	(13, 'Esposo Madrina', NULL),
	(14, 'Esposa Padrino', NULL),
	(15, 'Esposa Madrina', NULL);

-- Volcando estructura para tabla parroquia.reportes
CREATE TABLE IF NOT EXISTS `reportes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `id_pagos` bigint NOT NULL,
  `titulo` varchar(255) COLLATE utf8mb4_spanish2_ci NOT NULL,
  `descripcion` text COLLATE utf8mb4_spanish2_ci NOT NULL,
  `categoria` varchar(100) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `id_pagos` (`id_pagos`),
  CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_pagos`) REFERENCES `pagos` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.reportes: ~4 rows (aproximadamente)
INSERT INTO `reportes` (`id`, `id_pagos`, `titulo`, `descripcion`, `categoria`, `fecha`, `estado_registro`) VALUES
	(1, 3, 'Pago completo certificado 103', 'Pago recibido por valor de 1800.75', 'Finanzas', '2025-09-01 05:00:00', '0000-00-00 00:00:00'),
	(2, 4, 'Pago cancelado certificado 104', 'Se canceló el pago de 3200', 'Administración', '2025-09-02 05:00:00', '0000-00-00 00:00:00'),
	(3, 5, 'Pago completo certificado 105', 'Pago recibido por valor de 2100', 'Finanzas', '2025-09-03 05:00:00', '0000-00-00 00:00:00'),
	(4, 7, 'Pago duplicado certificado 103', 'Pago duplicado detectado por 1800.75', 'Auditoría', '2025-09-04 05:00:00', '0000-00-00 00:00:00');

-- Volcando estructura para tabla parroquia.sacramentos
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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.sacramentos: ~37 rows (aproximadamente)
INSERT INTO `sacramentos` (`id`, `libro_id`, `tipo_sacramento_id`, `acta`, `folio`, `fecha_generacion`, `estado_registro`) VALUES
	(3, 2, 1, 10, 5, '2025-08-20', NULL),
	(4, 3, 1, 10, 5, '2025-08-20', NULL),
	(5, 3, 1, 10, 5, '2025-08-20', NULL),
	(6, 3, 1, 10, 5, '2025-08-20', NULL),
	(7, 3, 1, 10, 5, '2025-08-20', NULL),
	(8, 3, 1, 10, 5, '2025-08-20', NULL),
	(9, 3, 1, 10, 5, '2025-08-20', NULL),
	(10, 2, 1, 10, 5, '2025-08-20', NULL),
	(11, 2, 1, 10, 5, '2025-08-20', NULL),
	(12, 2, 1, 10, 5, '2025-08-20', NULL),
	(13, 2, 1, 10, 5, '2025-08-20', NULL),
	(14, 2, 1, 10, 5, '2025-08-20', NULL),
	(15, 2, 1, 10, 5, '2025-08-20', NULL),
	(16, 2, 1, 10, 5, '2025-08-20', NULL),
	(17, 2, 1, 10, 5, '2025-08-20', NULL),
	(18, 2, 1, 10, 5, '2025-08-20', NULL),
	(19, 2, 1, 10, 5, '2025-08-20', NULL),
	(20, 2, 1, 10, 5, '2025-08-20', NULL),
	(21, 12, 4, 10, 5, '2025-08-20', NULL),
	(22, 12, 4, 10, 5, '2025-08-20', NULL),
	(23, 12, 4, 10, 5, '2025-08-20', NULL),
	(24, 12, 4, 10, 5, '2025-08-20', NULL),
	(25, 2, 1, 10, 5, '2025-08-20', NULL),
	(26, 2, 1, 10, 5, '2025-08-20', NULL),
	(27, 2, 1, 10, 5, '2025-08-20', NULL),
	(28, 2, 1, 10, 5, '2025-08-20', NULL),
	(29, 2, 1, 10, 5, '2025-08-20', NULL),
	(30, 2, 1, 10, 5, '2025-08-20', NULL),
	(31, 2, 1, 10, 5, '2025-08-20', NULL),
	(32, 2, 1, 10, 5, '2025-08-20', NULL),
	(33, 2, 1, 10, 5, '2025-08-20', NULL),
	(34, 2, 1, 10, 5, '2025-08-20', NULL),
	(35, 2, 1, 10, 5, '2025-08-20', NULL),
	(36, 2, 1, 10, 5, '2025-08-20', NULL),
	(37, 2, 1, 0, 0, '2025-11-03', NULL),
	(38, 2, 1, 0, 0, '2025-11-03', NULL),
	(39, 2, 1, 0, 0, '2025-11-03', NULL);

-- Volcando estructura para tabla parroquia.sacramento_tipo
CREATE TABLE IF NOT EXISTS `sacramento_tipo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.sacramento_tipo: ~4 rows (aproximadamente)
INSERT INTO `sacramento_tipo` (`id`, `tipo`, `estado_registro`) VALUES
	(1, 'Bautizos', NULL),
	(2, 'Confirmaciones', NULL),
	(3, 'Defunciones', NULL),
	(4, 'Matrimonios', NULL);

-- Volcando estructura para tabla parroquia.tipos_pago
CREATE TABLE IF NOT EXISTS `tipos_pago` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.tipos_pago: ~5 rows (aproximadamente)
INSERT INTO `tipos_pago` (`id`, `descripcion`) VALUES
	(1, 'Tarjeta Crédito'),
	(2, 'Tarjeta Débito'),
	(3, 'Efectivo'),
	(4, 'Transferencia Bancaria'),
	(5, 'Paypal');

-- Volcando estructura para tabla parroquia.usuarios
CREATE TABLE IF NOT EXISTS `usuarios` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `usuario_rol_id` bigint DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `email_confirmed` tinyint(1) DEFAULT '0',
  `contraseña` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `datos_completos` tinyint(1) DEFAULT '0',
  `estado_registro` datetime DEFAULT NULL,
  `reset_token` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `usuario_rol_id` (`usuario_rol_id`),
  CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`usuario_rol_id`) REFERENCES `usuario_roles` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.usuarios: ~12 rows (aproximadamente)
INSERT INTO `usuarios` (`id`, `usuario_rol_id`, `email`, `email_confirmed`, `contraseña`, `datos_completos`, `estado_registro`, `reset_token`, `reset_token_expires`) VALUES
	(10, 1, 'SANTIAGOBENAVIDES132@GMAIL.COM', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL, NULL, NULL),
	(11, 2, 'jrobgal@gmail.com', NULL, '$2y$10$0hELomG/FpvYcq9NkXFdBuDzdtiKUgR3vqmvb6zRqdbKH412LQfpy', 0, NULL, NULL, NULL),
	(12, 1, 'williammayorga@gmail.com', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL, NULL, NULL),
	(13, 1, 'admin@beehive.com', 0, '$2y$10$wljgdto1Yb7pXJ21IphKyuOKzmVX5oJC8SQbAHwQKv2Q/S02yAI/K', 0, NULL, NULL, NULL),
	(14, 2, 'gestorbar11@gmail.com', 0, '202cb962ac59075b964b07152d234b70', 0, NULL, NULL, NULL),
	(15, 1, 'nuevo.usuario1@email.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL, NULL, NULL),
	(16, 2, 'otro.usuario2@email.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL, NULL, NULL),
	(17, 1, 'tercer.usuario3@email.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL, NULL, NULL),
	(18, 2, 'cuarto.usuario4@email.com', 0, '202cb962ac59075b964b07152d234b70', 0, NULL, NULL, NULL),
	(19, 1, 'quinto.usuario5@email.com', 0, '202cb962ac59075b964b07152d234b70', 0, NULL, NULL, NULL),
	(20, 2, 'jose@example.com', 0, 'ed2b1f468c5f915f3f1cf75d7068baae', 0, NULL, NULL, NULL),
	(21, 1, 'aquilesbedoya37@gmail.com', 0, '$2y$10$DYuEGC.Xvj1g/9B9ejZ2Oe5pSZWzGvULNtJ9JfKoZB75vzLeRRw9C', 0, NULL, 'ee33cc42317b671cb18a56ddd00d814e702ef1e2361bff3229b5ae6d69e9efd7', '2025-10-28 03:45:43');

-- Volcando estructura para tabla parroquia.usuario_grupos
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.usuario_grupos: ~0 rows (aproximadamente)

-- Volcando estructura para tabla parroquia.usuario_roles
CREATE TABLE IF NOT EXISTS `usuario_roles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- Volcando datos para la tabla parroquia.usuario_roles: ~3 rows (aproximadamente)
INSERT INTO `usuario_roles` (`id`, `rol`, `estado_registro`) VALUES
	(1, 'Feligres', NULL),
	(2, 'Administrador', NULL),
	(3, 'Secretario', NULL);

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
