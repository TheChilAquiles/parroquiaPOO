-- ============================================================================
-- Migration: Create pago_ordenes table
-- Description: Tabla para almacenar órdenes de pago de PaymentsWay
-- Date: 2025-12-01
-- ============================================================================

CREATE TABLE IF NOT EXISTS pago_ordenes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    certificado_id BIGINT NOT NULL,
    order_number VARCHAR(100) UNIQUE NOT NULL,
    amount DECIMAL(10,2) NOT NULL,
    estado ENUM('pendiente', 'aprobado', 'rechazado', 'cancelado') DEFAULT 'pendiente',
    transaction_id VARCHAR(255) NULL,
    metadata TEXT NULL COMMENT 'JSON con datos adicionales del pago',
    fecha_creacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    fecha_actualizacion TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    
    FOREIGN KEY (certificado_id) REFERENCES certificados(id) ON DELETE CASCADE,
    
    INDEX idx_order_number (order_number),
    INDEX idx_certificado (certificado_id),
    INDEX idx_estado (estado),
    INDEX idx_transaction (transaction_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
COMMENT='Órdenes de pago procesadas a través de PaymentsWay (VePay)';
