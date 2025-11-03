-- ============================================================================
-- COMPLETAR MIGRACIÓN: Partes faltantes de la migración de certificados
-- ============================================================================

-- ============================================================================
-- PASO 1: AGREGAR FOREIGN KEYS FALTANTES
-- ============================================================================

-- Foreign key para solicitante_id (si no existe)
SET @exist := (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE CONSTRAINT_SCHEMA = 'parroquia'
               AND TABLE_NAME = 'certificados'
               AND CONSTRAINT_NAME = 'certificados_solicitante_fk');

SET @query := IF(@exist = 0,
    'ALTER TABLE `certificados` ADD CONSTRAINT `certificados_solicitante_fk` FOREIGN KEY (`solicitante_id`) REFERENCES `feligreses`(`id`) ON DELETE RESTRICT ON UPDATE CASCADE',
    'SELECT "FK certificados_solicitante_fk ya existe" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Foreign key para parentesco_id (si no existe)
SET @exist := (SELECT COUNT(*) FROM information_schema.TABLE_CONSTRAINTS
               WHERE CONSTRAINT_SCHEMA = 'parroquia'
               AND TABLE_NAME = 'certificados'
               AND CONSTRAINT_NAME = 'certificados_parentesco_fk');

SET @query := IF(@exist = 0,
    'ALTER TABLE `certificados` ADD CONSTRAINT `certificados_parentesco_fk` FOREIGN KEY (`parentesco_id`) REFERENCES `parentescos`(`id`) ON DELETE SET NULL ON UPDATE CASCADE',
    'SELECT "FK certificados_parentesco_fk ya existe" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================================================
-- PASO 2: CREAR ÍNDICES SI NO EXISTEN
-- ============================================================================

-- Índice para solicitante
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS
               WHERE TABLE_SCHEMA = 'parroquia'
               AND TABLE_NAME = 'certificados'
               AND INDEX_NAME = 'idx_certificados_solicitante');

SET @query := IF(@exist = 0,
    'CREATE INDEX `idx_certificados_solicitante` ON `certificados`(`solicitante_id`)',
    'SELECT "Índice idx_certificados_solicitante ya existe" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Índice para estado
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS
               WHERE TABLE_SCHEMA = 'parroquia'
               AND TABLE_NAME = 'certificados'
               AND INDEX_NAME = 'idx_certificados_estado');

SET @query := IF(@exist = 0,
    'CREATE INDEX `idx_certificados_estado` ON `certificados`(`estado`)',
    'SELECT "Índice idx_certificados_estado ya existe" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Índice para expiración
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS
               WHERE TABLE_SCHEMA = 'parroquia'
               AND TABLE_NAME = 'certificados'
               AND INDEX_NAME = 'idx_certificados_expiracion');

SET @query := IF(@exist = 0,
    'CREATE INDEX `idx_certificados_expiracion` ON `certificados`(`fecha_expiracion`)',
    'SELECT "Índice idx_certificados_expiracion ya existe" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Índice para feligrés del certificado
SET @exist := (SELECT COUNT(*) FROM information_schema.STATISTICS
               WHERE TABLE_SCHEMA = 'parroquia'
               AND TABLE_NAME = 'certificados'
               AND INDEX_NAME = 'idx_certificados_feligres');

SET @query := IF(@exist = 0,
    'CREATE INDEX `idx_certificados_feligres` ON `certificados`(`feligres_certificado_id`)',
    'SELECT "Índice idx_certificados_feligres ya existe" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================================================
-- PASO 3: MODIFICAR TABLA PAGOS SI ES NECESARIO
-- ============================================================================

-- Verificar si la columna tipo_concepto existe
SET @exist := (SELECT COUNT(*) FROM information_schema.COLUMNS
               WHERE TABLE_SCHEMA = 'parroquia'
               AND TABLE_NAME = 'pagos'
               AND COLUMN_NAME = 'tipo_concepto');

-- Si no existe, agregarla
SET @query := IF(@exist = 0,
    'ALTER TABLE `pagos` ADD COLUMN `tipo_concepto` ENUM(''certificado'', ''donacion'', ''evento'', ''otro'') DEFAULT ''certificado'' COMMENT ''Concepto del pago'' AFTER `tipo_pago_id`',
    'SELECT "Columna tipo_concepto ya existe en pagos" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- Verificar si certificado_id es nullable
SET @is_nullable := (SELECT IS_NULLABLE FROM information_schema.COLUMNS
                     WHERE TABLE_SCHEMA = 'parroquia'
                     AND TABLE_NAME = 'pagos'
                     AND COLUMN_NAME = 'certificado_id');

-- Si no es nullable, hacerlo nullable
SET @query := IF(@is_nullable = 'NO',
    'ALTER TABLE `pagos` MODIFY COLUMN `certificado_id` BIGINT NULL COMMENT ''ID del certificado si aplica (NULL para donaciones, etc.)''',
    'SELECT "Columna certificado_id ya es nullable" AS Info');

PREPARE stmt FROM @query;
EXECUTE stmt;
DEALLOCATE PREPARE stmt;

-- ============================================================================
-- VERIFICACIÓN FINAL
-- ============================================================================

SELECT '✅ Migración completada correctamente' AS Resultado;

-- Verificar estructura final
SELECT
    'Foreign Keys creadas:' AS Info,
    COUNT(*) AS Total
FROM information_schema.TABLE_CONSTRAINTS
WHERE CONSTRAINT_SCHEMA = 'parroquia'
AND TABLE_NAME = 'certificados'
AND CONSTRAINT_TYPE = 'FOREIGN KEY';

SELECT
    'Índices creados:' AS Info,
    COUNT(*) AS Total
FROM information_schema.STATISTICS
WHERE TABLE_SCHEMA = 'parroquia'
AND TABLE_NAME = 'certificados'
AND INDEX_NAME LIKE 'idx_certificados%';
