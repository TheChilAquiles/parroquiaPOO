-- Migration: Add transaction_id column to pagos table
-- This column stores the transaction ID from the payment gateway
-- Date: 2025-11-15
-- Purpose: Support payment gateway integration

-- Add transaction_id column
ALTER TABLE `pagos`
ADD COLUMN `transaction_id` VARCHAR(255) NULL AFTER `tipo_pago_id`,
ADD INDEX `idx_transaction_id` (`transaction_id`);

-- Add comment to the column
ALTER TABLE `pagos`
MODIFY COLUMN `transaction_id` VARCHAR(255) NULL COMMENT 'ID de transacción del gateway de pago (Stripe, PayPal, etc.)';
