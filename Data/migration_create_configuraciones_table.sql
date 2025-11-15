-- Migration: Create configuraciones table
-- Tabla para almacenar configuraciones generales del sistema
-- Date: 2025-11-15
-- Purpose: Permitir configuración dinámica de precios, datos de la parroquia, y otros parámetros del sistema

CREATE TABLE IF NOT EXISTS `configuraciones` (
  `id` bigint NOT NULL AUTO_INCREMENT,
  `clave` varchar(100) COLLATE utf8mb4_spanish2_ci NOT NULL COMMENT 'Clave única de configuración',
  `valor` text COLLATE utf8mb4_spanish2_ci COMMENT 'Valor de la configuración',
  `tipo` enum('texto','numero','booleano','json','email','url') COLLATE utf8mb4_spanish2_ci DEFAULT 'texto' COMMENT 'Tipo de dato',
  `categoria` varchar(50) COLLATE utf8mb4_spanish2_ci DEFAULT 'general' COMMENT 'Categoría de la configuración',
  `descripcion` text COLLATE utf8mb4_spanish2_ci COMMENT 'Descripción de la configuración',
  `editable` tinyint(1) DEFAULT 1 COMMENT 'Si es editable por los administradores',
  `fecha_creacion` datetime DEFAULT CURRENT_TIMESTAMP,
  `fecha_actualizacion` datetime DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `usuario_actualizacion` bigint DEFAULT NULL COMMENT 'ID del usuario que actualizó',
  PRIMARY KEY (`id`),
  UNIQUE KEY `clave` (`clave`),
  KEY `categoria` (`categoria`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_spanish2_ci COMMENT='Configuraciones generales del sistema';

-- Insertar configuraciones por defecto

-- Configuraciones de la Parroquia
INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`) VALUES
('parroquia_nombre', 'Parroquia San José', 'texto', 'parroquia', 'Nombre completo de la parroquia', 1),
('parroquia_direccion', 'Calle Principal #123', 'texto', 'parroquia', 'Dirección de la parroquia', 1),
('parroquia_ciudad', 'Bogotá', 'texto', 'parroquia', 'Ciudad donde se encuentra la parroquia', 1),
('parroquia_departamento', 'Cundinamarca', 'texto', 'parroquia', 'Departamento/Estado', 1),
('parroquia_pais', 'Colombia', 'texto', 'parroquia', 'País', 1),
('parroquia_telefono', '+57 1 234 5678', 'texto', 'parroquia', 'Teléfono de contacto', 1),
('parroquia_email', 'contacto@parroquia.co', 'email', 'parroquia', 'Email de contacto', 1),
('parroquia_nit', '900.123.456-7', 'texto', 'parroquia', 'NIT o identificación fiscal', 1);

-- Configuraciones de Certificados
INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`) VALUES
('cert_precio_bautismo', '10000', 'numero', 'certificados', 'Precio del certificado de bautismo (COP)', 1),
('cert_precio_confirmacion', '10000', 'numero', 'certificados', 'Precio del certificado de confirmación (COP)', 1),
('cert_precio_matrimonio', '15000', 'numero', 'certificados', 'Precio del certificado de matrimonio (COP)', 1),
('cert_precio_defuncion', '12000', 'numero', 'certificados', 'Precio del certificado de defunción (COP)', 1),
('cert_precio_general', '10000', 'numero', 'certificados', 'Precio por defecto para otros certificados (COP)', 1),
('cert_validez_dias', '30', 'numero', 'certificados', 'Días de validez del certificado', 1),
('cert_requiere_pago', '1', 'booleano', 'certificados', 'Si los certificados requieren pago (1=Sí, 0=No)', 1);

-- Configuraciones del Sistema
INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`) VALUES
('sistema_url', 'http://localhost', 'url', 'sistema', 'URL base del sistema', 1),
('sistema_nombre', 'Sistema Parroquial', 'texto', 'sistema', 'Nombre del sistema', 1),
('sistema_mantenimiento', '0', 'booleano', 'sistema', 'Modo mantenimiento (1=Activo, 0=Inactivo)', 1),
('sistema_registro_abierto', '1', 'booleano', 'sistema', 'Permitir registro de nuevos usuarios (1=Sí, 0=No)', 1);

-- Configuraciones del Párroco y Secretario
INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`) VALUES
('parroco_nombre', 'Padre Juan Pérez', 'texto', 'firmantes', 'Nombre completo del párroco actual', 1),
('parroco_titulo', 'Párroco', 'texto', 'firmantes', 'Título del párroco', 1),
('secretario_nombre', 'María González', 'texto', 'firmantes', 'Nombre del secretario(a) actual', 1),
('secretario_titulo', 'Secretaria', 'texto', 'firmantes', 'Título del secretario(a)', 1);

-- Configuraciones de Pagos
INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`) VALUES
('pago_gateway', 'mock', 'texto', 'pagos', 'Proveedor de pagos (mock, paymentway, stripe, paypal)', 1),
('pago_modo', 'sandbox', 'texto', 'pagos', 'Modo de pago (sandbox, production)', 1),
('pago_moneda', 'COP', 'texto', 'pagos', 'Moneda por defecto (COP, USD, EUR, etc)', 1),
('pago_iva_porcentaje', '19', 'numero', 'pagos', 'Porcentaje de IVA a aplicar', 1),
('pago_aplicar_iva', '0', 'booleano', 'pagos', 'Si se debe aplicar IVA a los certificados (1=Sí, 0=No)', 1);

-- Configuraciones de Notificaciones
INSERT INTO `configuraciones` (`clave`, `valor`, `tipo`, `categoria`, `descripcion`, `editable`) VALUES
('notif_email_certificado_generado', '1', 'booleano', 'notificaciones', 'Enviar email cuando se genera un certificado', 1),
('notif_email_pago_confirmado', '1', 'booleano', 'notificaciones', 'Enviar email cuando se confirma un pago', 1),
('notif_email_nuevo_usuario', '1', 'booleano', 'notificaciones', 'Notificar al admin cuando hay nuevo usuario', 1);
