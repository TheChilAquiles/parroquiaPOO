-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Servidor: 127.0.0.1
-- Tiempo de generación: 22-08-2025 a las 02:33:41
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
-- Base de datos: `basedatos2`
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
  `usuario_id` bigint(20) DEFAULT NULL,
  `tipo_documento_id` bigint(20) DEFAULT NULL,
  `numero_documento` varchar(255) DEFAULT NULL,
  `telefono` varchar(255) DEFAULT NULL,
  `direccion` varchar(255) DEFAULT NULL,
  `primer_nombre` varchar(255) NOT NULL,
  `segundo_nombre` varchar(255) NOT NULL,
  `primer_apellido` varchar(255) NOT NULL,
  `segundo_apellido` int(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupos`
--

CREATE TABLE `grupos` (
  `id` bigint(20) NOT NULL,
  `nombre` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `grupo_roles`
--

CREATE TABLE `grupo_roles` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro`
--

CREATE TABLE `libro` (
  `id` bigint(20) NOT NULL,
  `libro_tipo_id` bigint(20) DEFAULT NULL,
  `numero` int(11) DEFAULT NULL,
  `acta` int(11) DEFAULT NULL,
  `folio` int(11) DEFAULT NULL,
  `fecha_generacion` date DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `libro_tipo`
--

CREATE TABLE `libro_tipo` (
  `id` bigint(20) NOT NULL,
  `tipo` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `usuario_rol_id` bigint(20) DEFAULT NULL,
  `email` varchar(255) DEFAULT NULL,
  `email_confirmed` tinyint(1) DEFAULT NULL,
  `contraseña` varchar(255) DEFAULT NULL,
  `datos_completos` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_grupos`
--

CREATE TABLE `usuario_grupos` (
  `id` bigint(20) NOT NULL,
  `usuario_id` bigint(20) DEFAULT NULL,
  `grupo_parroquial_id` bigint(20) DEFAULT NULL,
  `grupo_rol_id` bigint(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estructura de tabla para la tabla `usuario_roles`
--

CREATE TABLE `usuario_roles` (
  `id` bigint(20) NOT NULL,
  `rol` varchar(255) DEFAULT NULL
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
  ADD KEY `usuario_id` (`usuario_id`);

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
-- Indices de la tabla `libro`
--
ALTER TABLE `libro`
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
-- AUTO_INCREMENT de la tabla `certificado`
--
ALTER TABLE `certificado`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `feligreses`
--
ALTER TABLE `feligreses`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `libro`
--
ALTER TABLE `libro`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `libro_tipo`
--
ALTER TABLE `libro_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
-- AUTO_INCREMENT de la tabla `sacramento_tipo`
--
ALTER TABLE `sacramento_tipo`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT de la tabla `usuarios`
--
ALTER TABLE `usuarios`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

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
-- Filtros para la tabla `certificado`
--
ALTER TABLE `certificado`
  ADD CONSTRAINT `certificado_ibfk_1` FOREIGN KEY (`usuario_generador_id`) REFERENCES `usuarios` (`id`),
  ADD CONSTRAINT `certificado_ibfk_2` FOREIGN KEY (`feligres_certificado_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `certificado_ibfk_3` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramento` (`id`);

--
-- Filtros para la tabla `feligreses`
--
ALTER TABLE `feligreses`
  ADD CONSTRAINT `feligreses_ibfk_1` FOREIGN KEY (`usuario_id`) REFERENCES `usuarios` (`id`);

--
-- Filtros para la tabla `libro`
--
ALTER TABLE `libro`
  ADD CONSTRAINT `libro_ibfk_1` FOREIGN KEY (`libro_tipo_id`) REFERENCES `libro_tipo` (`id`);

--
-- Filtros para la tabla `pago`
--
ALTER TABLE `pago`
  ADD CONSTRAINT `pago_ibfk_1` FOREIGN KEY (`certificado_id`) REFERENCES `certificado` (`id`);

--
-- Filtros para la tabla `pariente`
--
ALTER TABLE `pariente`
  ADD CONSTRAINT `pariente_ibfk_1` FOREIGN KEY (`parentesco_id`) REFERENCES `parentezcos` (`id`),
  ADD CONSTRAINT `pariente_ibfk_2` FOREIGN KEY (`feligres_sujeto_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `pariente_ibfk_3` FOREIGN KEY (`feligres_pariente_id`) REFERENCES `feligreses` (`id`);

--
-- Filtros para la tabla `participantes`
--
ALTER TABLE `participantes`
  ADD CONSTRAINT `participantes_ibfk_1` FOREIGN KEY (`feligres_id`) REFERENCES `feligreses` (`id`),
  ADD CONSTRAINT `participantes_ibfk_2` FOREIGN KEY (`sacramento_id`) REFERENCES `sacramento` (`id`),
  ADD CONSTRAINT `participantes_ibfk_3` FOREIGN KEY (`rol_participante_id`) REFERENCES `participantes_rol` (`id`);

--
-- Filtros para la tabla `sacramento`
--
ALTER TABLE `sacramento`
  ADD CONSTRAINT `sacramento_ibfk_1` FOREIGN KEY (`libro_id`) REFERENCES `libro` (`id`),
  ADD CONSTRAINT `sacramento_ibfk_2` FOREIGN KEY (`tipo_sacramento_id`) REFERENCES `sacramento_tipo` (`id`);

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
