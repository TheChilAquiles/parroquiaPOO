-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 29-09-2025 a las 02:29:22
-- Versión del servidor: 11.7.2-MariaDB
-- Versión de PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de datos: `parroquia`
--

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `certificados`
--

CREATE TABLE `certificados` (
  `id` bigint(20) NOT NULL,
  `usuario_generador_id` bigint(20) DEFAULT NULL,
  `feligres_certificado_id` bigint(20) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `tipo_certificado` varchar(255) DEFAULT NULL,
  `sacramento_id` bigint(20) DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `certificados`
--

INSERT INTO `certificados` (`id`, `usuario_generador_id`, `feligres_certificado_id`, `fecha_emision`, `fecha_expiracion`, `tipo_certificado`, `sacramento_id`, `ruta_archivo`, `estado`, `estado_registro`) VALUES
(101, 10, 1, '2024-01-15', '2025-01-15', 'bautizo', 3, '/archivos/cert101.pdf', 'vigente', '0000-00-00 00:00:00'),
(102, 11, 1, '2023-12-10', '2024-12-10', 'confirmacion', 4, '/archivos/cert102.pdf', 'vigente', '0000-00-00 00:00:00'),
(103, 12, 1, '2023-11-20', '2024-11-20', 'matrimonio', 5, '/archivos/cert103.pdf', 'vigente', '0000-00-00 00:00:00'),
(104, 13, 1, '2024-02-05', '2025-02-05', 'bautizo', 6, '/archivos/cert104.pdf', 'vigente', '0000-00-00 00:00:00'),
(105, 14, 1, '2024-03-10', '2025-03-10', 'confirmacion', 7, '/archivos/cert105.pdf', 'vigente', '0000-00-00 00:00:00'),
(106, 15, 1, '2024-04-15', '2025-04-15', 'bautizo', 8, '/archivos/cert106.pdf', 'vigente', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `documento_tipos`
--

CREATE TABLE `documento_tipos` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) NOT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `documento_tipos`
--

INSERT INTO `documento_tipos` (`id`, `tipo`, `estado_registro`) VALUES
(1, 'Cedula Ciudadania ', NULL),
(2, 'Tarjeta Identidad', NULL),
(3, 'Cedula extranjeria', NULL),
(4, 'Registro Civil', NULL),
(5, 'Permiso Especial', NULL),
(6, 'Numero Identificación Tributaria', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feligreses`
--

CREATE TABLE `feligreses` (
  `id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `tipo_documento_id` bigint(20) DEFAULT NULL,
  `numero_documento` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `primer_nombre` varchar(255) NOT NULL,
  `segundo_nombre` varchar(255) NOT NULL,
  `primer_apellido` varchar(255) NOT NULL,
  `segundo_apellido` varchar(255) NOT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `feligreses`
--

INSERT INTO `feligreses` (`id`, `usuario_id`, `tipo_documento_id`, `numero_documento`, `telefono`, `direccion`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `estado_registro`) VALUES
(1, NULL, 1, '123', NULL, NULL, 'Daniel', 'asd', 'godoy', 'duran', NULL),
(2, NULL, 2, '123', NULL, NULL, 'asd', 'asd', 'asd', 'asd', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `grupos`
--

INSERT INTO `grupos` (`id`, `nombre`, `estado_registro`) VALUES
(1, 'sasda', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_roles`
--

CREATE TABLE `grupo_roles` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` bigint(20) NOT NULL,
  `libro_tipo_id` bigint(20) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `libros`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_tipo`
--

CREATE TABLE `libro_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `libro_tipo`
--

INSERT INTO `libro_tipo` (`id`, `tipo`, `estado_registro`) VALUES
(1, 'Bautizos', NULL),
(2, 'Confirmaciones', NULL),
(3, 'Defunciones', NULL),
(4, 'Matrimonios', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `noticias`
--

CREATE TABLE `noticias` (
  `id` bigint(20) NOT NULL,
  `id_usuario` bigint(20) NOT NULL,
  `titulo` varchar(150) NOT NULL,
  `descripcion` longtext NOT NULL,
  `imagen` longtext NOT NULL,
  `fecha_publicacion` datetime NOT NULL DEFAULT current_timestamp(),
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `noticias`
--

INSERT INTO `noticias` (`id`, `id_usuario`, `titulo`, `descripcion`, `imagen`, `fecha_publicacion`, `estado_registro`) VALUES
(1, 11, 'Hola5', 'Hola Hola Hola Hola Hola Hola Hola', 'assets/img/noticias/68d9814e7604e-Diagrama_Lógico_v11.png', '2025-09-18 00:28:46', '2025-09-28 23:40:09'),
(2, 11, 'sadasd', 'asdas', 'assets/img/noticias/noticia_68d9bef0721ae5.27953340-MerParroquiav9.png', '2025-09-28 18:04:16', '2025-09-29 01:17:45'),
(3, 11, 'dsfsf', 'dfsdf', 'assets/img/noticias/noticia_68d9c1d7d61ed6.49839548-Diagrama_Lógico_v11.png', '2025-09-28 18:16:39', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pagos`
--

CREATE TABLE `pagos` (
  `id` bigint(20) NOT NULL,
  `certificado_id` bigint(20) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `fecha_pago` datetime DEFAULT NULL,
  `tipo_pago_id` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `pagos`
--

INSERT INTO `pagos` (`id`, `certificado_id`, `valor`, `estado`, `fecha_pago`, `tipo_pago_id`) VALUES
(3, 103, 1800.75, 'completo', '2025-09-03 16:34:45', 1),
(4, 104, 3200, 'cancelado', NULL, 3),
(5, 105, 2100, 'completo', NULL, 2),
(7, 103, 1800.75, 'completo', NULL, 1),
(17, 104, 255, 'PAGADO', '2025-09-25 16:37:11', 3),
(21, 106, 2500, 'PAGADO', '2025-09-25 16:48:37', 3);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parentescos`
--

CREATE TABLE `parentescos` (
  `id` bigint(20) NOT NULL,
  `parentesco` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `parentescos`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parientes`
--

CREATE TABLE `parientes` (
  `id` bigint(20) NOT NULL,
  `parentesco_id` bigint(20) DEFAULT NULL,
  `feligres_sujeto_id` bigint(20) DEFAULT NULL,
  `feligres_pariente_id` bigint(20) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `id` bigint(20) NOT NULL,
  `feligres_id` bigint(20) DEFAULT NULL,
  `sacramento_id` bigint(20) DEFAULT NULL,
  `rol_participante_id` bigint(20) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `participantes`
--

INSERT INTO `participantes` (`id`, `feligres_id`, `sacramento_id`, `rol_participante_id`, `estado_registro`) VALUES
(9, 1, 33, 10, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes_rol`
--

CREATE TABLE `participantes_rol` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `participantes_rol`
--

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

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `reportes`
--

CREATE TABLE `reportes` (
  `id` int(11) NOT NULL,
  `id_pagos` bigint(20) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descripcion` text NOT NULL,
  `categoria` varchar(100) DEFAULT NULL,
  `fecha` timestamp NOT NULL DEFAULT current_timestamp(),
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `reportes`
--

INSERT INTO `reportes` (`id`, `id_pagos`, `titulo`, `descripcion`, `categoria`, `fecha`, `estado_registro`) VALUES
(1, 3, 'Pago completo certificado 103', 'Pago recibido por valor de 1800.75', 'Finanzas', '2025-09-01 05:00:00', '0000-00-00 00:00:00'),
(2, 4, 'Pago cancelado certificado 104', 'Se canceló el pago de 3200', 'Administración', '2025-09-02 05:00:00', '0000-00-00 00:00:00'),
(3, 5, 'Pago completo certificado 105', 'Pago recibido por valor de 2100', 'Finanzas', '2025-09-03 05:00:00', '0000-00-00 00:00:00'),
(4, 7, 'Pago duplicado certificado 103', 'Pago duplicado detectado por 1800.75', 'Auditoría', '2025-09-04 05:00:00', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sacramentos`
--

CREATE TABLE `sacramentos` (
  `id` bigint(20) NOT NULL,
  `libro_id` bigint(20) DEFAULT NULL,
  `tipo_sacramento_id` bigint(20) DEFAULT NULL,
  `acta` int(11) NOT NULL,
  `folio` int(11) NOT NULL,
  `fecha_generacion` date NOT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `sacramentos`
--

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
(36, 2, 1, 10, 5, '2025-08-20', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sacramento_tipo`
--

CREATE TABLE `sacramento_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `sacramento_tipo`
--

INSERT INTO `sacramento_tipo` (`id`, `tipo`, `estado_registro`) VALUES
(1, 'Bautizos', NULL),
(2, 'Confirmaciones', NULL),
(3, 'Defunciones', NULL),
(4, 'Matrimonios', NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `tipos_pago`
--

CREATE TABLE `tipos_pago` (
  `id` int(11) NOT NULL,
  `descripcion` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `tipos_pago`
--

INSERT INTO `tipos_pago` (`id`, `descripcion`) VALUES
(1, 'Tarjeta Crédito'),
(2, 'Tarjeta Débito'),
(3, 'Efectivo'),
(4, 'Transferencia Bancaria'),
(5, 'Paypal');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `usuario_rol_id` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_confirmed` tinyint(1) DEFAULT 0,
  `contraseña` varchar(255) DEFAULT NULL,
  `datos_completos` tinyint(1) DEFAULT 0,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario_rol_id`, `email`, `email_confirmed`, `contraseña`, `datos_completos`, `estado_registro`) VALUES
(10, 1, 'SANTIAGOBENAVIDES132@GMAIL.COM', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL),
(11, 2, 'jrobgal@gmail.com', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL),
(12, 1, 'williammayorga@gmail.com', NULL, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL),
(13, 1, 'admin@beehive.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL),
(14, 2, 'gestorbar11@gmail.com', 0, '202cb962ac59075b964b07152d234b70', 0, NULL),
(15, 1, 'nuevo.usuario1@email.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL),
(16, 2, 'otro.usuario2@email.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL),
(17, 1, 'tercer.usuario3@email.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 0, NULL),
(18, 2, 'cuarto.usuario4@email.com', 0, '202cb962ac59075b964b07152d234b70', 0, NULL),
(19, 1, 'quinto.usuario5@email.com', 0, '202cb962ac59075b964b07152d234b70', 0, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_grupos`
--

CREATE TABLE `usuario_grupos` (
  `id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `grupo_parroquial_id` bigint(20) DEFAULT NULL,
  `grupo_rol_id` bigint(20) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) DEFAULT NULL,
  `estado_registro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci;

--
-- Volcado de datos para la tabla `usuario_roles`
--

INSERT INTO `usuario_roles` (`id`, `rol`, `estado_registro`) VALUES
(1, 'Feligres', NULL),
(2, 'Administrador', NULL),
(3, 'Secretario', NULL);

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_generador_id` (`usuario_generador_id`),
  ADD KEY `feligres_certificado_id` (`feligres_certificado_id`),
  ADD KEY `sacramento_id` (`sacramento_id`);

--
-- Indices de la tabla `documento_tipos`
--
ALTER TABLE `documento_tipos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `feligreses`
--
ALTER TABLE `feligreses`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `tipo_documento_id` (`tipo_documento_id`);

--
-- Indices de la tabla `grupos`
--
ALTER TABLE `grupos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `grupo_roles`
--
ALTER TABLE `grupo_roles`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `libros`
--
ALTER TABLE `libros`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_tipo_id` (`libro_tipo_id`);

--
-- Indices de la tabla `libro_tipo`
--
ALTER TABLE `libro_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `noticias`
--
ALTER TABLE `noticias`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_usuario` (`id_usuario`);

--
-- Indices de la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificado_id` (`certificado_id`),
  ADD KEY `tipo_pago_id` (`tipo_pago_id`);

--
-- Indices de la tabla `parentescos`
--
ALTER TABLE `parentescos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `parientes`
--
ALTER TABLE `parientes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `parentesco_id` (`parentesco_id`),
  ADD KEY `feligres_sujeto_id` (`feligres_sujeto_id`),
  ADD KEY `feligres_pariente_id` (`feligres_pariente_id`);

--
-- Indices de la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `feligres_id` (`feligres_id`),
  ADD KEY `sacramento_id` (`sacramento_id`),
  ADD KEY `rol_participante_id` (`rol_participante_id`);

--
-- Indices de la tabla `participantes_rol`
--
ALTER TABLE `participantes_rol`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_pagos` (`id_pagos`);

--
-- Indices de la tabla `sacramentos`
--
ALTER TABLE `sacramentos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `tipo_sacramento_id` (`tipo_sacramento_id`);

--
-- Indices de la tabla `sacramento_tipo`
--
ALTER TABLE `sacramento_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `tipos_pago`
--
ALTER TABLE `tipos_pago`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_rol_id` (`usuario_rol_id`);

--
-- Indices de la tabla `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_id` (`usuario_id`),
  ADD KEY `grupo_parroquial_id` (`grupo_parroquial_id`),
  ADD KEY `grupo_rol_id` (`grupo_rol_id`);

--
-- Indices de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `certificados`
--
ALTER TABLE `certificados`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=107;

--
-- AUTO_INCREMENT de la tabla `documento_tipos`
--
ALTER TABLE `documento_tipos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `feligreses`
--
ALTER TABLE `feligreses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT de la tabla `grupo_roles`
--
ALTER TABLE `grupo_roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT de la tabla `libro_tipo`
--
ALTER TABLE `libro_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `noticias`
--
ALTER TABLE `noticias`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de la tabla `pagos`
--
ALTER TABLE `pagos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT de la tabla `parentescos`
--
ALTER TABLE `parentescos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `parientes`
--
ALTER TABLE `parientes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de la tabla `participantes_rol`
--
ALTER TABLE `participantes_rol`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT de la tabla `reportes`
--
ALTER TABLE `reportes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT de la tabla `sacramentos`
--
ALTER TABLE `sacramentos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT de la tabla `sacramento_tipo`
--
ALTER TABLE `sacramento_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT de la tabla `tipos_pago`
--
ALTER TABLE `tipos_pago`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT de la tabla `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `certificados`
--
ALTER TABLE `certificados`
  ADD CONSTRAINT `certificados_ibfk_1` FOREIGN KEY (`usuario_generador_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `certificados_ibfk_2` FOREIGN KEY (`feligres_certificado_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `certificados_ibfk_3` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramentos` (`id`);

--
-- Filtros para la tabla `feligreses`
--
ALTER TABLE `feligreses`
  ADD CONSTRAINT `feligreses_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `libros`
--
ALTER TABLE `libros`
  ADD CONSTRAINT `libros_ibfk_1` FOREIGN KEY (`libro_tipo_id`) REFERENCES `libro_tipo` (`id`);

--
-- Filtros para la tabla `pagos`
--
ALTER TABLE `pagos`
  ADD CONSTRAINT `pagos_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificados` (`id`),
  ADD CONSTRAINT `pagos_ibfk_2` FOREIGN KEY (`tipo_pago_id`) REFERENCES `tipos_pago` (`id`);

--
-- Filtros para la tabla `parientes`
--
ALTER TABLE `parientes`
  ADD CONSTRAINT `parientes_ibfk_1` FOREIGN KEY (`parentesco_id`) REFERENCES `parentescos` (`id`),
  ADD CONSTRAINT `parientes_ibfk_2` FOREIGN KEY (`feligres_sujeto_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `parientes_ibfk_3` FOREIGN KEY (`feligres_pariente_id`) REFERENCES `feligreses` (`id`);

--
-- Filtros para la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`feligres_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `participantes_ibfk_2` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramentos` (`id`),
  ADD CONSTRAINT `participantes_ibfk_3` FOREIGN KEY (`rol_participante_id`) REFERENCES `participantes_rol` (`id`);

--
-- Filtros para la tabla `reportes`
--
ALTER TABLE `reportes`
  ADD CONSTRAINT `reportes_ibfk_1` FOREIGN KEY (`id_pagos`) REFERENCES `pagos` (`id`);

--
-- Filtros para la tabla `sacramentos`
--
ALTER TABLE `sacramentos`
  ADD CONSTRAINT `sacramentos_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libros` (`id`),
  ADD CONSTRAINT `sacramentos_ibfk_2` FOREIGN KEY (`tipo_sacramento_id`) REFERENCES `sacramento_tipo` (`id`);

--
-- Filtros para la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD CONSTRAINT `usuarios_ibfk_1` FOREIGN KEY (`usuario_rol_id`) REFERENCES `usuario_roles` (`id`);

--
-- Filtros para la tabla `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  ADD CONSTRAINT `usuario_grupos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `usuario_grupos_ibfk_2` FOREIGN KEY (`grupo_parroquial_id`) REFERENCES `grupos` (`id`),
  ADD CONSTRAINT `usuario_grupos_ibfk_3` FOREIGN KEY (`grupo_rol_id`) REFERENCES `grupo_roles` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
