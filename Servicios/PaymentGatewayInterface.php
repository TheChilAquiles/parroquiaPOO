<?php

/**
 * PaymentGatewayInterface
 * Define el contrato para todos los procesadores de pago
 */
interface PaymentGatewayInterface
{
    /**
     * Procesa un pago
     *
     * @param array $paymentData Datos del pago [amount, currency, description, metadata]
     * @return array ['success' => bool, 'transaction_id' => string, 'message' => string, 'data' => array]
     */
    public function processPayment(array $paymentData): array;

    /**
     * Verifica el estado de una transacción
     *
     * @param string $transactionId ID de la transacción
     * @return array ['success' => bool, 'status' => string, 'message' => string]
     */
    public function verifyTransaction(string $transactionId): array;

    /**
     * Reembolsa un pago
     *
     * @param string $transactionId ID de la transacción
     * @param float $amount Monto a reembolsar (opcional, si no se especifica reembolsa todo)
     * @return array ['success' => bool, 'refund_id' => string, 'message' => string]
     */
    public function refundPayment(string $transactionId, float $amount = null): array;

    /**
     * Obtiene el nombre del proveedor
     *
     * @return string
     */
    public function getProviderName(): string;
}
