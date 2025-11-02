-- ============================================================================
-- MIGRACIÓN: Sistema de Solicitud de Certificados con Parentescos
-- Fecha: 2025-11-02
-- Descripción: Agrega funcionalidad de solicitud de certificados por
--              feligreses (propios y de familiares) con validación de
--              parentesco, pago y descarga automática.
-- ============================================================================

-- ============================================================================
-- PASO 1: MODIFICAR TABLA CERTIFICADOS
-- ============================================================================

-- Agregar nuevas columnas para sistema de solicitudes
ALTER TABLE `certificados`
ADD COLUMN `solicitante_id` BIGINT NOT NULL COMMENT 'Feligrés que solicita el certificado' AFTER `usuario_generador_id`,
ADD COLUMN `parentesco_id` BIGINT NULL COMMENT 'Tipo de parentesco si es familiar (NULL si es propio)' AFTER `solicitante_id`,
ADD COLUMN `motivo_solicitud` VARCHAR(500) NULL COMMENT 'Motivo por el cual solicita el certificado' AFTER `tipo_certificado`,
ADD COLUMN `fecha_solicitud` DATETIME NOT NULL COMMENT 'Fecha y hora de la solicitud' AFTER `motivo_solicitud`,
ADD COLUMN `fecha_pago` DATETIME NULL COMMENT 'Fecha y hora del pago completado' AFTER `fecha_emision`,
ADD COLUMN `fecha_generacion` DATETIME NULL COMMENT 'Fecha y hora de generación del PDF' AFTER `fecha_pago`;

-- Modificar columna de fecha_expiracion para que sea calculada (+30 días desde generación)
ALTER TABLE `certificados`
MODIFY COLUMN `fecha_expiracion` DATE NULL COMMENT 'Fecha de expiración (30 días desde generación)';

-- Actualizar enum de estados
ALTER TABLE `certificados`
MODIFY COLUMN `estado` ENUM('pendiente_pago', 'generado', 'descargado', 'expirado', 'vigente') DEFAULT 'pendiente_pago'
COMMENT 'Estado del certificado: pendiente_pago, generado, descargado, expirado';

-- ============================================================================
-- PASO 2: AGREGAR FOREIGN KEYS
-- ============================================================================

-- Relación: solicitante debe ser un feligrés registrado
ALTER TABLE `certificados`
ADD CONSTRAINT `certificados_solicitante_fk`
FOREIGN KEY (`solicitante_id`) REFERENCES `feligreses`(`id`)
ON DELETE RESTRICT ON UPDATE CASCADE;

-- Relación: parentesco debe existir en tabla parentescos
ALTER TABLE `certificados`
ADD CONSTRAINT `certificados_parentesco_fk`
FOREIGN KEY (`parentesco_id`) REFERENCES `parentescos`(`id`)
ON DELETE SET NULL ON UPDATE CASCADE;

-- ============================================================================
-- PASO 3: CREAR ÍNDICES PARA MEJORAR RENDIMIENTO
-- ============================================================================

-- Índice para búsquedas por solicitante
CREATE INDEX `idx_certificados_solicitante` ON `certificados`(`solicitante_id`);

-- Índice para filtrado por estado
CREATE INDEX `idx_certificados_estado` ON `certificados`(`estado`);

-- Índice para verificar expiración
CREATE INDEX `idx_certificados_expiracion` ON `certificados`(`fecha_expiracion`);

-- Índice para búsquedas por feligrés del certificado
CREATE INDEX `idx_certificados_feligres` ON `certificados`(`feligres_certificado_id`);

-- ============================================================================
-- PASO 4: ACTUALIZAR TABLA PAGOS
-- ============================================================================

-- Hacer certificado_id nullable para permitir otros tipos de pagos
ALTER TABLE `pagos`
MODIFY COLUMN `certificado_id` BIGINT NULL COMMENT 'ID del certificado si aplica (NULL para donaciones, etc.)';

-- Agregar columna para diferenciar tipo de concepto de pago
ALTER TABLE `pagos`
ADD COLUMN `tipo_concepto` ENUM('certificado', 'donacion', 'evento', 'otro') DEFAULT 'certificado'
COMMENT 'Concepto del pago' AFTER `tipo_pago_id`;

-- ============================================================================
-- PASO 5: DATOS DE EJEMPLO (OPCIONAL - COMENTADO)
-- ============================================================================

-- Ejemplo de solicitud de certificado propio
-- INSERT INTO `certificados` (
--     `usuario_generador_id`,
--     `solicitante_id`,
--     `feligres_certificado_id`,
--     `parentesco_id`,
--     `fecha_emision`,
--     `tipo_certificado`,
--     `motivo_solicitud`,
--     `fecha_solicitud`,
--     `sacramento_id`,
--     `estado`
-- ) VALUES (
--     NULL,  -- Generación automática
--     1,     -- Feligrés que solicita
--     1,     -- Mismo feligrés (certificado propio)
--     NULL,  -- Sin parentesco (es propio)
--     NOW(),
--     'Bautizo',
--     'Trámite laboral',
--     NOW(),
--     3,     -- Sacramento existente
--     'pendiente_pago'
-- );

-- Ejemplo de solicitud de certificado de familiar
-- INSERT INTO `certificados` (
--     `usuario_generador_id`,
--     `solicitante_id`,
--     `feligres_certificado_id`,
--     `parentesco_id`,
--     `fecha_emision`,
--     `tipo_certificado`,
--     `motivo_solicitud`,
--     `fecha_solicitud`,
--     `sacramento_id`,
--     `estado`
-- ) VALUES (
--     NULL,  -- Generación automática
--     2,     -- Familiar que solicita
--     1,     -- Feligrés del sacramento
--     3,     -- ID del parentesco (ej: Madre)
--     NOW(),
--     'Confirmación',
--     'Trámite académico',
--     NOW(),
--     4,     -- Sacramento existente
--     'pendiente_pago'
-- );

-- ============================================================================
-- VERIFICACIÓN DE LA MIGRACIÓN
-- ============================================================================

-- Verificar estructura de certificados
-- DESCRIBE certificados;

-- Verificar índices creados
-- SHOW INDEX FROM certificados;

-- Verificar foreign keys
-- SELECT
--     CONSTRAINT_NAME,
--     COLUMN_NAME,
--     REFERENCED_TABLE_NAME,
--     REFERENCED_COLUMN_NAME
-- FROM information_schema.KEY_COLUMN_USAGE
-- WHERE TABLE_NAME = 'certificados'
-- AND REFERENCED_TABLE_NAME IS NOT NULL;

-- ============================================================================
-- NOTAS IMPORTANTES
-- ============================================================================

-- 1. VALIDACIÓN DE PARENTESCO:
--    Antes de crear un certificado para un familiar, validar que existe
--    relación en tabla 'parientes' entre solicitante_id y feligres_certificado_id

-- 2. FLUJO DE ESTADOS:
--    pendiente_pago → (tras pago) → generado → (tras descarga) → descargado
--    → (tras 30 días) → expirado

-- 3. GENERACIÓN AUTOMÁTICA:
--    Al confirmar pago, un trigger o job debe:
--    - Generar PDF del certificado
--    - Actualizar: fecha_pago, fecha_generacion, fecha_expiracion (+30 días), ruta_archivo
--    - Cambiar estado a 'generado'

-- 4. EXPIRACIÓN:
--    Un cron job diario debe actualizar certificados con:
--    UPDATE certificados SET estado='expirado'
--    WHERE fecha_expiracion < CURDATE() AND estado='generado'

-- 5. PERMISOS:
--    - Feligrés: Puede solicitar sus certificados y de familiares registrados
--    - Secretario: Puede registrar solicitudes presenciales con pago en efectivo
--    - Administrador: Puede re-generar certificados expirados sin cobrar

-- ============================================================================
-- FIN DE LA MIGRACIÓN
-- ============================================================================
