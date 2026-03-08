-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Mar 08, 2026 at 10:56 PM
-- Server version: 11.7.2-MariaDB
-- PHP Version: 8.3.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `parroquia`
--

-- --------------------------------------------------------

--
-- Table structure for table `certificados`
--

CREATE TABLE `certificados` (
  `id` int(10) NOT NULL,
  `usuario_generador_id` int(10) DEFAULT NULL,
  `solicitante_id` int(10) NOT NULL,
  `parentesco_id` int(10) DEFAULT NULL,
  `feligres_certificado_id` int(10) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL COMMENT 'Fecha y hora del pago completado',
  `fecha_generacion` datetime DEFAULT NULL COMMENT 'Fecha y hora de generaci├│n del PDF',
  `fecha_expiracion` date DEFAULT NULL COMMENT 'Fecha de expiraci├│n (30 d├¡as desde generaci├│n)',
  `tipo_certificado` varchar(30) DEFAULT NULL,
  `motivo_solicitud` text DEFAULT NULL COMMENT 'Motivo por el cual solicita el certificado',
  `fecha_solicitud` datetime NOT NULL COMMENT 'Fecha y hora de la solicitud',
  `sacramento_id` int(10) DEFAULT NULL,
  `ruta_archivo` varchar(130) DEFAULT NULL,
  `estado` enum('pendiente_pago','generado','descargado','expirado','vigente') DEFAULT 'pendiente_pago' COMMENT 'Estado del certificado: pendiente_pago, generado, descargado, expirado',
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `certificados`
--

INSERT INTO `certificados` (`id`, `usuario_generador_id`, `solicitante_id`, `parentesco_id`, `feligres_certificado_id`, `fecha_emision`, `fecha_pago`, `fecha_generacion`, `fecha_expiracion`, `tipo_certificado`, `motivo_solicitud`, `fecha_solicitud`, `sacramento_id`, `ruta_archivo`, `estado`, `estado_registro`) VALUES
(1, NULL, 1, NULL, 12, '2025-12-06', NULL, '2025-12-07 14:58:17', '2026-01-06', 'Bautismo', NULL, '2025-12-06 20:44:44', 1, 'certificados_generados/cert_1_bautismo_20251207_195817.pdf', 'descargado', NULL),
(2, 15, 2, NULL, 12, NULL, '2026-02-22 19:49:46', '2026-02-22 19:49:46', '2026-03-24', 'Confirmación', 'Solicitud desde Sacramentos', '2025-12-07 14:58:00', 2, 'certificados_generados/cert_2_bautismo_20260223_004946.pdf', 'descargado', NULL),
(3, 15, 12, NULL, 12, NULL, '2026-02-22 18:54:22', '2026-02-22 18:54:27', '2026-03-24', 'Bautismo', 'Solicitud desde Sacramentos', '2026-02-20 20:04:54', 8, 'certificados_generados/cert_3_bautismo_20260222_235427.pdf', 'descargado', NULL),
(4, 15, 1, NULL, 1, NULL, '2026-02-22 19:52:36', '2026-02-22 19:52:37', '2026-03-24', 'Bautismo', 'Solicitud desde Sacramentos', '2026-02-22 19:32:04', 9, 'certificados_generados/cert_4_bautismo_20260223_005237.pdf', 'descargado', NULL),
(5, 15, 12, NULL, 12, NULL, '2026-03-08 16:46:05', '2026-03-08 16:46:08', '2026-04-07', 'Bautizo', 'Generación directa', '2026-03-08 16:46:03', 9, 'certificados_generados/cert_5_bautismo_20260308_214608.pdf', 'descargado', NULL),
(6, NULL, 20, NULL, 20, NULL, NULL, '2025-10-02 10:00:00', '2025-11-02', 'Matrimonio', NULL, '2025-10-01 10:00:00', 10, NULL, 'expirado', NULL),
(7, NULL, 21, NULL, 22, NULL, '2026-03-08 17:41:25', '2026-03-08 17:41:26', '2026-04-07', 'Bautismo', NULL, '2026-03-08 17:04:27', 11, 'certificados_generados/cert_7_bautismo_20260308_224126.pdf', 'generado', NULL),
(8, NULL, 20, NULL, 22, NULL, NULL, '2026-03-08 17:41:29', '2026-04-07', 'Bautismo', NULL, '2026-03-08 17:04:27', 11, 'certificados_generados/cert_8_bautismo_20260308_224129.pdf', 'generado', NULL),
(9, 15, 22, NULL, 22, NULL, '2026-03-08 17:40:50', '2026-03-08 17:40:50', '2026-04-07', 'Bautismo', 'Solicitud desde Sacramentos', '2026-03-08 17:40:45', 11, 'certificados_generados/cert_9_bautismo_20260308_224050.pdf', 'descargado', NULL),
(10, 15, 3, NULL, 3, NULL, NULL, NULL, NULL, 'Matrimonio', 'Solicitud desde Sacramentos', '2026-03-08 17:42:45', 4, NULL, 'pendiente_pago', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `configuraciones`
--

CREATE TABLE `configuraciones` (
  `id` int(10) NOT NULL,
  `clave` varchar(50) NOT NULL COMMENT 'Clave única de configuración',
  `valor` text DEFAULT NULL COMMENT 'Valor de la configuración',
  `tipo` enum('texto','numero','booleano','json','email','url') DEFAULT 'texto' COMMENT 'Tipo de dato',
  `categoria` varchar(50) DEFAULT 'general' COMMENT 'Categoría de la configuración',
  `descripcion` text DEFAULT NULL COMMENT 'Descripción de la configuración',
  `editable` tinyint(1) DEFAULT 1 COMMENT 'Si es editable por los administradores',
  `fecha_creacion` datetime DEFAULT current_timestamp(),
  `fecha_actualizacion` datetime DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `usuario_actualizacion` int(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci COMMENT='Configuraciones generales del sistema';

--
-- Dumping data for table `configuraciones`
--

INSERT INTO `configuraciones` (`id`, `clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`, `fecha_creacion`, `fecha_actualizacion`, `usuario_actualizacion`) VALUES
(1, 'parroquia_nombre', 'Parroquia San Francisco de Asís', 'texto', 'parroquia', 'Nombre de la parroquia', 1, '2025-12-06 20:44:37', '2025-12-11 20:09:40', 15),
(2, 'parroquia_telefono', '601 402 3526', 'texto', 'parroquia', 'Teléfono principal', 1, '2025-12-06 20:44:37', '2025-12-11 20:09:40', 15),
(3, 'cert_precio_bautismo', '25000', 'numero', 'certificados', 'Precio Certificado de Bautismo', 1, '2025-12-06 20:44:37', '2025-12-11 20:09:40', 15),
(4, 'cert_precio_matrimonio', '35000', 'numero', 'certificados', 'Precio Certificado de Matrimonio', 1, '2025-12-06 20:44:37', '2025-12-11 20:09:40', 15),
(5, 'pago_moneda', 'COP', 'texto', 'pagos', 'Moneda', 1, '2025-12-06 20:44:38', '2025-12-11 20:09:40', 15),
(6, 'pago_modo', 'production', 'texto', 'pagos', 'Modo pasarela', 1, '2025-12-06 20:44:38', '2025-12-11 20:09:40', 15),
(7, 'sistema_registro_abierto', '1', 'booleano', 'sistema', 'Registro abierto', 1, '2025-12-06 20:44:38', '2025-12-11 20:09:40', 15),
(9, 'cert_precio_confirmacion', '50000', 'numero', 'certificados', 'Precio Certificado de Confirmación', 1, '2025-12-06 21:00:22', '2025-12-11 20:09:40', 15),
(10, 'cert_precio_defuncion', '15000', 'numero', 'certificados', 'Precio Certificado de Defunción', 1, '2025-12-06 21:00:22', '2025-12-11 20:09:40', 15),
(11, 'cert_precio_general', '10000', 'numero', 'certificados', 'Precio Certificado General (Por Defecto)', 1, '2026-03-08 17:30:59', '2026-03-08 17:30:59', 15);

-- --------------------------------------------------------

--
-- Table structure for table `documento_tipos`
--

CREATE TABLE `documento_tipos` (
  `id` int(10) NOT NULL,
  `tipo` varchar(30) NOT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `documento_tipos`
--

INSERT INTO `documento_tipos` (`id`, `tipo`, `estado_registro`) VALUES
(1, 'Cédula de Ciudadanía', NULL),
(2, 'Tarjeta de Identidad', NULL),
(3, 'Cédula de Extranjería', NULL),
(4, 'Registro Civil', NULL),
(5, 'Permiso Especial', NULL),
(6, 'NIT', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `feligreses`
--

CREATE TABLE `feligreses` (
  `id` int(10) NOT NULL,
  `usuario_id` int(10) DEFAULT NULL,
  `tipo_documento_id` int(10) DEFAULT NULL,
  `numero_documento` varchar(20) DEFAULT NULL,
  `telefono` varchar(20) DEFAULT NULL,
  `direccion` varchar(50) DEFAULT NULL,
  `primer_nombre` varchar(30) NOT NULL,
  `segundo_nombre` varchar(30) DEFAULT NULL,
  `primer_apellido` varchar(30) NOT NULL,
  `segundo_apellido` varchar(30) DEFAULT NULL,
  `fecha_nacimiento` date DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `feligreses`
--

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
(11, NULL, 1, '2006', '0000000', 'N/A', 'Sofia', '', 'Vargas', '', NULL, '2026-02-20 19:42:32'),
(12, 15, 1, '1031422232', '3166347898', 'Diagonal 61 sur N 20 a 30', 'Samuel', 'David', 'Bedoya', 'Garcia', '2006-06-23', NULL),
(17, 17, 1, '111111111', '111111111', 'Diagonal 61 sur N 20 a 30', 'Samuel', 'Samuel David', 'Bedoya', 'Garcia', '2026-02-04', '2026-02-27 20:30:24'),
(20, NULL, 1, '10203040', '3110000000', NULL, 'Carlos', NULL, 'Gómez', NULL, NULL, NULL),
(21, NULL, 1, '50607080', '3120000000', NULL, 'Laura', NULL, 'Ríos', NULL, NULL, NULL),
(22, NULL, 2, '11223344', 'N/A', NULL, 'Mateo', NULL, 'Gómez', NULL, NULL, NULL),
(23, NULL, 1, '99887766', '3150000000', NULL, 'Esteban', NULL, 'Quito', NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `grupos`
--

CREATE TABLE `grupos` (
  `id` int(10) NOT NULL,
  `nombre` varchar(80) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `grupos`
--

INSERT INTO `grupos` (`id`, `nombre`, `estado_registro`) VALUES
(1, 'Legión de María', NULL),
(2, 'Grupo Juvenil 1', NULL),
(3, 'hijos', '2025-12-08 12:44:03'),
(4, 'Grupo Juvenil 2', '2025-12-08 12:53:15'),
(5, 'Coro Juvenil', '2025-12-08 14:32:02');

-- --------------------------------------------------------

--
-- Table structure for table `grupo_roles`
--

CREATE TABLE `grupo_roles` (
  `id` int(10) NOT NULL,
  `rol` varchar(20) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `grupo_roles`
--

INSERT INTO `grupo_roles` (`id`, `rol`, `estado_registro`) VALUES
(1, 'Miembro', NULL),
(2, 'Coordinador', NULL),
(3, 'Tesorero', NULL),
(4, 'Coordinado', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `libros`
--

CREATE TABLE `libros` (
  `id` int(10) NOT NULL,
  `libro_tipo_id` int(10) DEFAULT NULL,
  `numero` int(10) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `libros`
--

INSERT INTO `libros` (`id`, `libro_tipo_id`, `numero`, `estado_registro`) VALUES
(1, 1, 1, NULL),
(2, 2, 1, NULL),
(3, 3, 1, NULL),
(4, 4, 1, NULL),
(5, 1, 3, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `libro_tipo`
--

CREATE TABLE `libro_tipo` (
  `id` int(10) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `libro_tipo`
--

INSERT INTO `libro_tipo` (`id`, `tipo`, `estado_registro`) VALUES
(1, 'Bautizos', NULL),
(2, 'Confirmaciones', NULL),
(3, 'Defunciones', NULL),
(4, 'Matrimonios', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `noticias`
--

CREATE TABLE `noticias` (
  `id` int(10) NOT NULL,
  `id_usuario` int(10) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` longtext NOT NULL,
  `imagen` longtext NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `noticias`
--

INSERT INTO `noticias` (`id`, `id_usuario`, `titulo`, `descripcion`, `imagen`, `fecha_publicacion`, `estado_registro`) VALUES
(1, 1, 'Horarios de Semana Santa', 'Conoce nuestra programación oficial para la Semana Mayor. Tendremos confesiones todos los días de 4:00 PM a 6:00 PM.', 'assets/img/noticias/noticia_6935d5b539f339.00351054.png', '2025-12-06 20:44:44', '2025-12-08 18:03:57'),
(2, 15, 'Gran Campaña de Donación Manos Solidarias', 'Querida comunidad, este fin de semana estaremos recolectando alimentos no perecederos y ropa en buen estado para las familias más vulnerables del sector. Pueden dejar sus aportes en el salón parroquial antes y después de la Eucaristía de las 10:00 AM y 6:00 PM. ¡Su generosidad es esperanza para muchos! Dios los bendiga.', 'assets/img/noticias/noticia_69371ff230a4a7.88656607.jpg', '2025-12-08 13:58:58', '2025-12-08 19:12:57'),
(3, 15, '¡Gran Kermés Familiar este Sábado!', 'No se pierdan nuestra gran Kermés Familiar este sábado a partir de las 12:00 pm en los jardines de la parroquia. Habrá deliciosos puestos de comida, juegos para los niños y nuestro famoso concurso de postres. ¡Ven a disfrutar en comunidad y apoya a nuestra parroquia! ¡Los esperamos!', 'assets/img/noticias/noticia_69aded126b1a99.38380663.png', '2025-12-08 14:08:13', NULL),
(4, 15, 'Charla Juvenil: El Papel de los Jóvenes en la Iglesia', 'Invitamos a todos los jóvenes de la comunidad a una charla especial este viernes a las 7:00 pm en el salón parroquial. El Padre Roberto nos hablará sobre &amp;quot;El Papel de los Jóvenes en la Iglesia de Hoy&amp;quot;. ¡Ven a compartir, aprender y fortalecer tu fe junto a otros jóvenes! Habrá un refrigerio al finalizar.', 'assets/img/noticias/noticia_69aded39b4a326.83834255.png', '2025-12-08 14:08:39', NULL),
(5, 15, 'Misa de Sanación para Enfermos y Adultos Mayores', 'Este próximo jueves a las 6:00 pm celebraremos una Misa de Sanación especialmente dedicada a nuestros enfermos y adultos mayores. Únanse a nosotros en oración para pedir por su salud y bienestar. Durante la misa se administrará el sacramento de la Unción de los Enfermos a quienes lo deseen.', 'assets/img/noticias/noticia_69aded53142601.31930919.png', '2025-12-08 14:09:03', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `pagos`
--

CREATE TABLE `pagos` (
  `id` int(10) NOT NULL,
  `certificado_id` int(10) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `estado` varchar(20) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `tipo_pago_id` int(10) DEFAULT NULL,
  `transaction_id` varchar(30) DEFAULT NULL COMMENT 'ID de transacción del gateway de pago (Stripe, PayPal, etc.)',
  `tipo_concepto` enum('certificado','donacion','evento','otro') DEFAULT 'certificado' COMMENT 'Concepto del pago'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `pagos`
--

INSERT INTO `pagos` (`id`, `certificado_id`, `valor`, `estado`, `fecha_pago`, `tipo_pago_id`, `transaction_id`, `tipo_concepto`) VALUES
(1, 1, 25000.00, 'pagado', '2025-12-06 20:44:45', 1, NULL, 'certificado'),
(2, 3, 10000.00, 'pagado', '2026-02-22 18:54:22', 3, NULL, 'certificado'),
(3, 2, 50000.00, 'pagado', '2026-02-22 19:49:46', 3, NULL, 'certificado'),
(4, 4, 10000.00, 'pagado', '2026-02-22 19:52:36', 3, NULL, 'certificado'),
(5, 5, 10000.00, 'pagado', '2026-03-08 16:46:05', 3, NULL, 'certificado'),
(6, 9, 10000.00, 'pagado', '2026-03-08 17:40:50', 3, NULL, 'certificado'),
(7, 7, 10000.00, 'pagado', '2026-03-08 17:41:25', 3, NULL, 'certificado');

-- --------------------------------------------------------

--
-- Table structure for table `pago_ordenes`
--

CREATE TABLE `pago_ordenes` (
  `id` int(10) NOT NULL,
  `certificado_id` int(10) NOT NULL,
  `order_number` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `estado` enum('pendiente','aprobado','rechazado','cancelado') DEFAULT 'pendiente',
  `transaction_id` varchar(30) DEFAULT NULL,
  `metadata` text DEFAULT NULL COMMENT 'JSON con datos adicionales del pago',
  `fecha_creacion` timestamp NULL DEFAULT current_timestamp(),
  `fecha_actualizacion` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci COMMENT='??rdenes de pago procesadas a trav??s de PaymentsWay (VePay)';

--
-- Dumping data for table `pago_ordenes`
--

INSERT INTO `pago_ordenes` (`id`, `certificado_id`, `order_number`, `amount`, `estado`, `transaction_id`, `metadata`, `fecha_creacion`, `fecha_actualizacion`) VALUES
(1, 1, 'ORD-1765071885', 25000.00, 'pendiente', NULL, NULL, '2025-12-07 01:44:45', '2025-12-07 01:44:45');

-- --------------------------------------------------------

--
-- Table structure for table `parentescos`
--

CREATE TABLE `parentescos` (
  `id` int(10) NOT NULL,
  `parentesco` varchar(40) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `parentescos`
--

INSERT INTO `parentescos` (`id`, `parentesco`, `estado_registro`) VALUES
(1, 'Padre', NULL),
(2, 'Madre', NULL),
(3, 'Hermano/a', NULL),
(4, 'Abuelo/a', NULL),
(5, 'Tío/a', NULL),
(6, 'Esposo/a', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `parientes`
--

CREATE TABLE `parientes` (
  `id` int(10) NOT NULL,
  `parentesco_id` int(10) DEFAULT NULL,
  `feligres_sujeto_id` int(10) DEFAULT NULL,
  `feligres_pariente_id` int(10) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `participantes`
--

CREATE TABLE `participantes` (
  `id` int(10) NOT NULL,
  `feligres_id` int(10) DEFAULT NULL,
  `sacramento_id` int(10) DEFAULT NULL,
  `rol_participante_id` int(10) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `participantes`
--

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
(12, 11, 4, 9, NULL),
(13, 12, 8, 1, NULL),
(19, 1, 9, 1, NULL),
(23, 20, 10, 7, NULL),
(24, 21, 10, 8, NULL),
(25, 23, 10, 9, NULL),
(26, 22, 11, 1, NULL),
(27, 20, 11, 4, NULL),
(28, 21, 11, 5, NULL),
(29, 23, 11, 2, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `participantes_rol`
--

CREATE TABLE `participantes_rol` (
  `id` int(10) NOT NULL,
  `rol` varchar(30) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `participantes_rol`
--

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

-- --------------------------------------------------------

--
-- Table structure for table `reportes`
--

CREATE TABLE `reportes` (
  `id` int(10) NOT NULL,
  `id_pagos` int(10) NOT NULL,
  `titulo` varchar(100) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` varchar(60) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sacramentos`
--

CREATE TABLE `sacramentos` (
  `id` int(10) NOT NULL,
  `libro_id` int(10) DEFAULT NULL,
  `tipo_sacramento_id` int(10) DEFAULT NULL,
  `acta` int(10) NOT NULL,
  `folio` int(10) NOT NULL,
  `fecha_generacion` date NOT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `sacramentos`
--

INSERT INTO `sacramentos` (`id`, `libro_id`, `tipo_sacramento_id`, `acta`, `folio`, `fecha_generacion`, `estado_registro`) VALUES
(1, 1, 1, 101, 10, '2000-01-15', NULL),
(2, 2, 2, 201, 20, '2015-06-20', NULL),
(3, 3, 3, 301, 30, '2023-11-01', NULL),
(4, 4, 4, 401, 40, '2024-02-14', NULL),
(8, 1, 1, 102, 51, '2026-02-20', NULL),
(9, 1, 1, 103, 52, '2026-02-22', NULL),
(10, 4, 4, 405, 45, '2020-05-15', NULL),
(11, 1, 1, 106, 54, '2026-03-08', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `sacramento_tipo`
--

CREATE TABLE `sacramento_tipo` (
  `id` int(10) NOT NULL,
  `tipo` varchar(30) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `sacramento_tipo`
--

INSERT INTO `sacramento_tipo` (`id`, `tipo`, `estado_registro`) VALUES
(1, 'Bautizo', NULL),
(2, 'Confirmación', NULL),
(3, 'Defunción', NULL),
(4, 'Matrimonio', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tipos_pago`
--

CREATE TABLE `tipos_pago` (
  `id` int(10) NOT NULL,
  `descripcion` varchar(40) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `tipos_pago`
--

INSERT INTO `tipos_pago` (`id`, `descripcion`) VALUES
(1, 'Efectivo'),
(2, 'Tarjeta Crédito'),
(3, 'Transferencia'),
(4, 'Pasarela Web');

-- --------------------------------------------------------

--
-- Table structure for table `usuarios`
--

CREATE TABLE `usuarios` (
  `id` int(10) NOT NULL,
  `usuario_rol_id` int(10) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `email_confirmed` tinyint(1) DEFAULT 0,
  `contraseña` varchar(80) DEFAULT NULL,
  `datos_completos` tinyint(1) DEFAULT 0,
  `estado_registro` datetime DEFAULT NULL,
  `reset_token` varchar(70) DEFAULT NULL,
  `reset_token_expires` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario_rol_id`, `email`, `email_confirmed`, `contraseña`, `datos_completos`, `estado_registro`, `reset_token`, `reset_token_expires`) VALUES
(1, 2, 'admin@parroquia.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(2, 3, 'secretaria@parroquia.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(10, 1, 'juan@mail.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(11, 1, 'maria@mail.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(12, 1, 'pedro@mail.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(13, 1, 'ana@mail.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(14, 1, 'roberto@mail.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(15, 2, 'aquilesbedoya37@gmail.com', 0, '$2y$10$TcdegRP5eWhBZx3seyFxOO6DZUBIkQjKuXuTEXzE5r78sVgW8Rm4e', 1, NULL, '69fa3b5650de81682c77fcaee4297dc357136a663dfbc75138053300694b5440', '2026-02-28 02:20:44'),
(16, 1, 'admin@parroquia.com', 1, '$2y$12$PeRtPu6jK73knoz5xvLuoeLskiuITeiJfp0ANt../UELTFLcaE71y', 1, NULL, NULL, NULL),
(17, 1, 'aquilesbedoya378@gmail.com', 0, '$2y$10$dUioPpXTbotZLW/TFP.LdOQE1UymFSz0JZEJHbgEmlIaWzFxLejjS', 1, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `usuario_grupos`
--

CREATE TABLE `usuario_grupos` (
  `id` int(10) NOT NULL,
  `usuario_id` int(10) DEFAULT NULL,
  `grupo_parroquial_id` int(10) DEFAULT NULL,
  `grupo_rol_id` int(10) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `usuario_grupos`
--

INSERT INTO `usuario_grupos` (`id`, `usuario_id`, `grupo_parroquial_id`, `grupo_rol_id`, `estado_registro`) VALUES
(1, 11, 2, 1, '2025-12-07 15:00:20'),
(2, 11, 2, 2, '2025-12-08 12:49:35'),
(3, 2, 2, 2, '2025-12-08 12:11:08'),
(4, 2, 2, 2, '2025-12-08 12:48:50'),
(5, 2, 2, 2, '2025-12-08 12:48:54'),
(6, 2, 2, 3, '2025-12-08 12:48:58'),
(7, 2, 2, 1, '2025-12-08 12:49:08'),
(8, 11, 2, 4, NULL),
(9, 10, 5, 2, '2025-12-08 14:27:46'),
(10, 10, 5, 4, '2025-12-08 14:29:27');

-- --------------------------------------------------------

--
-- Table structure for table `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `id` int(10) NOT NULL,
  `rol` varchar(30) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Dumping data for table `usuario_roles`
--

INSERT INTO `usuario_roles` (`id`, `rol`, `estado_registro`) VALUES
(1, 'Feligres', NULL),
(2, 'Administrador', NULL),
(3, 'Secretario', NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_generador_id` (`usuario_generador_id`),
  ADD KEY `feligres_certificado_id` (`feligres_certificado_id`),
  ADD KEY `sacramento_id` (`sacramento_id`),
  ADD KEY `certificados_parentesco_fk` (`parentesco_id`),
  ADD KEY `idx_certificados_solicitante` (`solicitante_id`),
  ADD KEY `idx_certificados_estado` (`estado`),
  ADD KEY `idx_certificados_expiracion` (`fecha_expiracion`),
  ADD KEY `idx_certificados_feligres` (`feligres_certificado_id`);

--
-- Indexes for table `configuraciones`
--
ALTER TABLE `configuraciones`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `clave` (`clave`),
  ADD KEY `categoria` (`categoria`);

--
-- Indexes for table `documento_tipos`
--
ALTER TABLE `documento_tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `feligreses`
--
ALTER TABLE `feligreses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_documento_id` (`tipo_documento_id`);

--
-- Indexes for table `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `grupo_roles`
--
ALTER TABLE `grupo_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_tipo_id` (`libro_tipo_id`);

--
-- Indexes for table `libro_tipo`
--
ALTER TABLE `libro_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indexes for table `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificado_id` (`certificado_id`),
  ADD KEY `tipo_pago_id` (`tipo_pago_id`),
  ADD KEY `idx_transaction_id` (`transaction_id`);

--
-- Indexes for table `pago_ordenes`
--
ALTER TABLE `pago_ordenes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `order_number` (`order_number`),
  ADD KEY `idx_order_number` (`order_number`),
  ADD KEY `idx_certificado` (`certificado_id`),
  ADD KEY `idx_estado` (`estado`),
  ADD KEY `idx_transaction` (`transaction_id`);

--
-- Indexes for table `parentescos`
--
ALTER TABLE `parentescos`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `parientes`
--
ALTER TABLE `parientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parentesco_id` (`parentesco_id`),
  ADD KEY `feligres_sujeto_id` (`feligres_sujeto_id`),
  ADD KEY `feligres_pariente_id` (`feligres_pariente_id`);

--
-- Indexes for table `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feligres_id` (`feligres_id`),
  ADD KEY `sacramento_id` (`sacramento_id`),
  ADD KEY `rol_participante_id` (`rol_participante_id`);

--
-- Indexes for table `participantes_rol`
--
ALTER TABLE `participantes_rol`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pagos` (`id_pagos`);

--
-- Indexes for table `sacramentos`
--
ALTER TABLE `sacramentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `tipo_sacramento_id` (`tipo_sacramento_id`);

--
-- Indexes for table `sacramento_tipo`
--
ALTER TABLE `sacramento_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tipos_pago`
--
ALTER TABLE `tipos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_rol_id` (`usuario_rol_id`);

--
-- Indexes for table `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `grupo_parroquial_id` (`grupo_parroquial_id`),
  ADD KEY `grupo_rol_id` (`grupo_rol_id`);

--
-- Indexes for table `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `configuraciones`
--
ALTER TABLE `configuraciones`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `documento_tipos`
--
ALTER TABLE `documento_tipos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `feligreses`
--
ALTER TABLE `feligreses`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `grupo_roles`
--
ALTER TABLE `grupo_roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `libros`
--
ALTER TABLE `libros`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `libro_tipo`
--
ALTER TABLE `libro_tipo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `pago_ordenes`
--
ALTER TABLE `pago_ordenes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `parentescos`
--
ALTER TABLE `parentescos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `parientes`
--
ALTER TABLE `parientes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `participantes_rol`
--
ALTER TABLE `participantes_rol`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `sacramentos`
--
ALTER TABLE `sacramentos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `sacramento_tipo`
--
ALTER TABLE `sacramento_tipo`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `tipos_pago`
--
ALTER TABLE `tipos_pago`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `usuario_roles`
--
ALTER TABLE `usuario_roles`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`usuario_generador_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `certificados_ibfk_2` FOREIGN KEY (`feligres_certificado_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `certificados_ibfk_3` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramentos` (`id`),
  ADD CONSTRAINT `certificados_parentesco_fk` FOREIGN KEY (`parentesco_id`) REFERENCES `parentescos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE,
  ADD CONSTRAINT `certificados_solicitante_fk` FOREIGN KEY (`solicitante_id`) REFERENCES `feligreses` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `feligreses`
--
ALTER TABLE `feligreses`
  ADD CONSTRAINT `feligreses_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `fk_feligres_tipo_doc` FOREIGN KEY (`tipo_documento_id`) REFERENCES `documento_tipos` (`id`) ON DELETE SET NULL ON UPDATE CASCADE;

--
-- Constraints for table `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`libro_tipo_id`) REFERENCES `libro_tipo` (`id`);

--
-- Constraints for table `noticias`
--
ALTER TABLE `noticias`
  ADD CONSTRAINT `fk_noticias_usuario` FOREIGN KEY (`id_usuario`) REFERENCES `usuarios` (`id`) ON UPDATE CASCADE;

--
-- Constraints for table `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificados` (`id`),
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipos_pago` (`id`);

--
-- Constraints for table `pago_ordenes`
--
ALTER TABLE `pago_ordenes`
  ADD CONSTRAINT `pago_ordenes_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificados` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `parientes`
--
ALTER TABLE `parientes`
  ADD CONSTRAINT `parientes_ibfk_1` FOREIGN KEY (`parentesco_id`) REFERENCES `parentescos` (`id`),
  ADD CONSTRAINT `parientes_ibfk_2` FOREIGN KEY (`feligres_sujeto_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `parientes_ibfk_3` FOREIGN KEY (`feligres_pariente_id`) REFERENCES `feligreses` (`id`);

--
-- Constraints for table `participantes`
--
ALTER TABLE `participantes`
  ADD CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`feligres_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `participantes_ibfk_2` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramentos` (`id`),
  ADD CONSTRAINT `participantes_ibfk_3` FOREIGN KEY (`rol_participante_id`) REFERENCES `participantes_rol` (`id`);

--
-- Constraints for table `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_pagos`) REFERENCES `pagos` (`id`);

--
-- Constraints for table `sacramentos`
--
ALTER TABLE `sacramentos`
  ADD CONSTRAINT `sacramentos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`),
  ADD CONSTRAINT `sacramentos_ibfk_2` FOREIGN KEY (`tipo_sacramento_id`) REFERENCES `sacramento_tipo` (`id`);

--
-- Constraints for table `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`usuario_rol_id`) REFERENCES `usuario_roles` (`id`);

--
-- Constraints for table `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  ADD CONSTRAINT `usuario_grupos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuario_grupos_ibfk_2` FOREIGN KEY (`grupo_parroquial_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `usuario_grupos_ibfk_3` FOREIGN KEY (`grupo_rol_id`) REFERENCES `grupo_roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
