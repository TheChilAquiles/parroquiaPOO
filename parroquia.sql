-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 12-08-2025 a las 02:34:56
-- Versión del servidor: 10.4.32-MariaDB
-- Versión de PHP: 8.2.12

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
-- Estructura de tabla para la tabla `certificado`
--

CREATE TABLE `certificado` (
  `id` bigint(20) NOT NULL,
  `usuario_generador_id` bigint(20) DEFAULT NULL,
  `feligres_certificado_id` bigint(20) DEFAULT NULL,
  `fecha_emision` date DEFAULT NULL,
  `fecha_expiracion` date DEFAULT NULL,
  `tipo_certificado` varchar(255) DEFAULT NULL,
  `sacramento_id` bigint(20) DEFAULT NULL,
  `ruta_archivo` varchar(255) DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `feligreses`
--

CREATE TABLE `feligreses` (
  `id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `primer_nombre` varchar(255) NOT NULL,
  `segundo_nombre` varchar(255) NOT NULL,
  `primer_apellido` varchar(255) NOT NULL,
  `segundo_apellido` varchar(255) NOT NULL,
  `tipo_documento_id` bigint(20) NOT NULL,
  `numero_documento` varchar(255) NOT NULL,
  `telefono` varchar(255) NOT NULL,
  `direccion` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `feligreses`
--

INSERT INTO `feligreses` (`id`, `usuario_id`, `primer_nombre`, `segundo_nombre`, `primer_apellido`, `segundo_apellido`, `tipo_documento_id`, `numero_documento`, `telefono`, `direccion`) VALUES
(16, 1, 'asd', '12', 'asd', '123', 1, '1546145', '3111111', 'sad asd asd assd '),
(17, 15, 'asd', '12', 'as', 'asd', 1, '1546145', '30155', 'sad asd asd assd '),
(18, 16, 'rusbel ', 'asdasdasd', 'aadasd', 'sadasd', 1, '2154654', '123123123', 'calle 24 norte');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_roles`
--

CREATE TABLE `grupo_roles` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libros`
--

CREATE TABLE `libros` (
  `id` bigint(20) NOT NULL,
  `libro_tipo_id` bigint(20) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `acta` int(11) DEFAULT NULL,
  `folio` int(11) DEFAULT NULL,
  `fecha_generacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libros`
--

INSERT INTO `libros` (`id`, `libro_tipo_id`, `numero`, `acta`, `folio`, `fecha_generacion`) VALUES
(1, 2, 1, NULL, NULL, NULL),
(2, 1, 1, NULL, NULL, NULL),
(3, 1, 2, NULL, NULL, NULL),
(4, 3, 1, NULL, NULL, NULL),
(5, 4, 1, NULL, NULL, NULL),
(6, 4, 2, NULL, NULL, NULL),
(7, 4, 3, NULL, NULL, NULL),
(8, 1, 3, NULL, NULL, NULL),
(9, 3, 2, NULL, NULL, NULL),
(10, 3, 3, NULL, NULL, NULL),
(11, 3, 4, NULL, NULL, NULL),
(12, 3, 5, NULL, NULL, NULL),
(13, 3, 6, NULL, NULL, NULL),
(14, 3, 7, NULL, NULL, NULL),
(15, 3, 8, NULL, NULL, NULL),
(16, 3, 9, NULL, NULL, NULL),
(17, 3, 10, NULL, NULL, NULL),
(18, 3, 11, NULL, NULL, NULL),
(19, 3, 12, NULL, NULL, NULL),
(20, 3, 13, NULL, NULL, NULL),
(21, 3, 14, NULL, NULL, NULL),
(22, 3, 15, NULL, NULL, NULL),
(23, 3, 16, NULL, NULL, NULL),
(24, 3, 17, NULL, NULL, NULL),
(25, 3, 18, NULL, NULL, NULL),
(26, 3, 19, NULL, NULL, NULL),
(27, 3, 20, NULL, NULL, NULL),
(28, 1, 4, NULL, NULL, NULL),
(29, 1, 5, NULL, NULL, NULL),
(30, 1, 6, NULL, NULL, NULL),
(31, 1, 7, NULL, NULL, NULL),
(32, 1, 8, NULL, NULL, NULL),
(33, 1, 9, NULL, NULL, NULL),
(34, 1, 10, NULL, NULL, NULL),
(35, 1, 11, NULL, NULL, NULL),
(36, 1, 12, NULL, NULL, NULL),
(37, 1, 13, NULL, NULL, NULL),
(38, 1, 14, NULL, NULL, NULL),
(39, 1, 15, NULL, NULL, NULL),
(40, 1, 16, NULL, NULL, NULL),
(41, 1, 17, NULL, NULL, NULL),
(42, 1, 18, NULL, NULL, NULL),
(43, 1, 19, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_tipo`
--

CREATE TABLE `libro_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `libro_tipo`
--

INSERT INTO `libro_tipo` (`id`, `tipo`) VALUES
(1, 'b'),
(2, 'c');

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pago`
--

CREATE TABLE `pago` (
  `id` bigint(20) NOT NULL,
  `certificado_id` bigint(20) DEFAULT NULL,
  `valor` float DEFAULT NULL,
  `estado` varchar(255) DEFAULT NULL,
  `metodo_de_pago` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `parentezcos`
--

CREATE TABLE `parentezcos` (
  `id` bigint(20) NOT NULL,
  `parentesco` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `pariente`
--

CREATE TABLE `pariente` (
  `id` bigint(20) NOT NULL,
  `parentesco_id` bigint(20) DEFAULT NULL,
  `feligres_sujeto_id` bigint(20) DEFAULT NULL,
  `feligres_pariente_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes`
--

CREATE TABLE `participantes` (
  `id` bigint(20) NOT NULL,
  `feligres_id` bigint(20) DEFAULT NULL,
  `sacramento_id` bigint(20) DEFAULT NULL,
  `rol_participante_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `participantes_rol`
--

CREATE TABLE `participantes_rol` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sacramento`
--

CREATE TABLE `sacramento` (
  `id` bigint(20) NOT NULL,
  `libro_id` bigint(20) DEFAULT NULL,
  `tipo_sacramento_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `sacramento_tipo`
--

CREATE TABLE `sacramento_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuarios`
--

CREATE TABLE `usuarios` (
  `id` bigint(20) NOT NULL,
  `usuario_rol_id` bigint(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_confirmed` tinyint(1) NOT NULL DEFAULT 0,
  `contraseña` varchar(255) NOT NULL,
  `datos_completos` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Volcado de datos para la tabla `usuarios`
--

INSERT INTO `usuarios` (`id`, `usuario_rol_id`, `email`, `email_confirmed`, `contraseña`, `datos_completos`) VALUES
(1, 1, 'ejemplo2@example.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 1),
(3, 1, 'ejemploo2@example.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(4, 1, 'ejemploo22@example.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(5, 1, 'ejemploo222@example.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(6, 1, 'ejemploo2227@example.com', 0, '2b44928ae11fb9384c4cf38708677c48', 0),
(7, 1, 'ejemploo22272@example.com', 0, '2b44928ae11fb9384c4cf38708677c48', 0),
(8, 1, 'admin@beehive.com', 0, 'c20ad4d76fe97759aa27a0c99bff6710', 0),
(9, 1, 'admin2@beehive.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(10, 1, 'admin21@beehive.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(11, 1, 'rusbel199@hotmail.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(12, 1, 'admin1@beehive.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(13, 1, 'ejemplo@example.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(14, 1, 'rusbel19955@hotmail.com', 0, '6512bd43d9caa6e02c990b0a82652dca', 0),
(15, 1, 'admin22@beehive.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 1),
(16, 1, 'jrobgal@gmail.com', 0, '81dc9bdb52d04dc20036dbd8313ed055', 1);

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_grupos`
--

CREATE TABLE `usuario_grupos` (
  `id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) NOT NULL,
  `grupo_parroquial_id` bigint(20) NOT NULL,
  `grupo_rol_id` bigint(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tablas volcadas
--

--
-- Indices de la tabla `certificado`
--
ALTER TABLE `certificado`
  ADD PRIMARY KEY (`id`),
  ADD KEY `usuario_generador_id` (`usuario_generador_id`),
  ADD KEY `feligres_certificado_id` (`feligres_certificado_id`),
  ADD KEY `sacramento_id` (`sacramento_id`);

--
-- Indices de la tabla `feligreses`
--
ALTER TABLE `feligreses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`);

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
-- Indices de la tabla `pago`
--
ALTER TABLE `pago`
  ADD PRIMARY KEY (`id`),
  ADD KEY `certificado_id` (`certificado_id`);

--
-- Indices de la tabla `parentezcos`
--
ALTER TABLE `parentezcos`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `pariente`
--
ALTER TABLE `pariente`
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
-- Indices de la tabla `sacramento`
--
ALTER TABLE `sacramento`
  ADD PRIMARY KEY (`id`),
  ADD KEY `libro_id` (`libro_id`),
  ADD KEY `tipo_sacramento_id` (`tipo_sacramento_id`);

--
-- Indices de la tabla `sacramento_tipo`
--
ALTER TABLE `sacramento_tipo`
  ADD PRIMARY KEY (`id`);

--
-- Indices de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `usuario_rol_id` (`usuario_rol_id`);

--
-- Indices de la tabla `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `grupo_parroquial_id` (`grupo_parroquial_id`),
  ADD UNIQUE KEY `usuario_id` (`usuario_id`),
  ADD UNIQUE KEY `grupo_rol_id` (`grupo_rol_id`);

--
-- Indices de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT de las tablas volcadas
--

--
-- AUTO_INCREMENT de la tabla `certificado`
--
ALTER TABLE `certificado`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `feligreses`
--
ALTER TABLE `feligreses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- AUTO_INCREMENT de la tabla `grupos`
--
ALTER TABLE `grupos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `grupo_roles`
--
ALTER TABLE `grupo_roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libros`
--
ALTER TABLE `libros`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=44;

--
-- AUTO_INCREMENT de la tabla `libro_tipo`
--
ALTER TABLE `libro_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de la tabla `pago`
--
ALTER TABLE `pago`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `parentezcos`
--
ALTER TABLE `parentezcos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `pariente`
--
ALTER TABLE `pariente`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participantes`
--
ALTER TABLE `participantes`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `participantes_rol`
--
ALTER TABLE `participantes_rol`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `sacramento`
--
ALTER TABLE `sacramento`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT de la tabla `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuario_roles`
--
ALTER TABLE `usuario_roles`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Restricciones para tablas volcadas
--

--
-- Filtros para la tabla `usuario_grupos`
--
ALTER TABLE `usuario_grupos`
  ADD CONSTRAINT `usuario_grupos_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
