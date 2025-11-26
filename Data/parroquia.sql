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
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=32 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci COMMENT='Configuraciones generales del sistema';

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.documento_tipos
DROP TABLE IF EXISTS `documento_tipos`;
CREATE TABLE IF NOT EXISTS `documento_tipos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.grupos
DROP TABLE IF EXISTS `grupos`;
CREATE TABLE IF NOT EXISTS `grupos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `nombre` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.grupo_roles
DROP TABLE IF EXISTS `grupo_roles`;
CREATE TABLE IF NOT EXISTS `grupo_roles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.libro_tipo
DROP TABLE IF EXISTS `libro_tipo`;
CREATE TABLE IF NOT EXISTS `libro_tipo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=23 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.parentescos
DROP TABLE IF EXISTS `parentescos`;
CREATE TABLE IF NOT EXISTS `parentescos` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `parentesco` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.participantes_rol
DROP TABLE IF EXISTS `participantes_rol`;
CREATE TABLE IF NOT EXISTS `participantes_rol` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.sacramento_tipo
DROP TABLE IF EXISTS `sacramento_tipo`;
CREATE TABLE IF NOT EXISTS `sacramento_tipo` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `tipo` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.tipos_pago
DROP TABLE IF EXISTS `tipos_pago`;
CREATE TABLE IF NOT EXISTS `tipos_pago` (
  `id` int NOT NULL AUTO_INCREMENT,
  `descripcion` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

-- Volcando estructura para tabla parroquia.usuario_roles
DROP TABLE IF EXISTS `usuario_roles`;
CREATE TABLE IF NOT EXISTS `usuario_roles` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `rol` varchar(255) CHARACTER SET utf8mb4 COLLATE utf8mb4_spanish2_ci DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- La exportación de datos fue deseleccionada.

/*!40103 SET TIME_ZONE=IFNULL(@OLD_TIME_ZONE, 'system') */;
/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IFNULL(@OLD_FOREIGN_KEY_CHECKS, 1) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40111 SET SQL_NOTES=IFNULL(@OLD_SQL_NOTES, 1) */;
