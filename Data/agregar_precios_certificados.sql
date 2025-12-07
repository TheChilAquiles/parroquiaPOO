-- Script para asegurar que todos los precios de certificados estén en la base de datos
-- Ejecutar este script para verificar y agregar configuraciones faltantes

-- Verificar configuraciones existentes
SELECT 'Configuraciones actuales:' as mensaje;
SELECT id, clave, valor, descripcion FROM configuraciones WHERE clave LIKE 'cert_precio_%' ORDER BY clave;

-- Agregar o actualizar precio para Bautismo
INSERT INTO configuraciones (clave, valor, tipo, categoria, descripcion, editable, fecha_creacion, fecha_actualizacion)
VALUES ('cert_precio_bautismo', '25000', 'numero', 'certificados', 'Precio Certificado de Bautismo', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE descripcion = 'Precio Certificado de Bautismo';

-- Agregar o actualizar precio para Confirmación
INSERT INTO configuraciones (clave, valor, tipo, categoria, descripcion, editable, fecha_creacion, fecha_actualizacion)
VALUES ('cert_precio_confirmacion', '20000', 'numero', 'certificados', 'Precio Certificado de Confirmación', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE descripcion = 'Precio Certificado de Confirmación';

-- Agregar o actualizar precio para Defunción
INSERT INTO configuraciones (clave, valor, tipo, categoria, descripcion, editable, fecha_creacion, fecha_actualizacion)
VALUES ('cert_precio_defuncion', '15000', 'numero', 'certificados', 'Precio Certificado de Defunción', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE descripcion = 'Precio Certificado de Defunción';

-- Agregar o actualizar precio para Matrimonio
INSERT INTO configuraciones (clave, valor, tipo, categoria, descripcion, editable, fecha_creacion, fecha_actualizacion)
VALUES ('cert_precio_matrimonio', '35000', 'numero', 'certificados', 'Precio Certificado de Matrimonio', 1, NOW(), NOW())
ON DUPLICATE KEY UPDATE descripcion = 'Precio Certificado de Matrimonio';

-- Verificar que todos los precios estén correctamente configurados
SELECT 'Configuraciones después de la actualización:' as mensaje;
SELECT id, clave, valor, descripcion, editable FROM configuraciones WHERE clave LIKE 'cert_precio_%' ORDER BY clave;
