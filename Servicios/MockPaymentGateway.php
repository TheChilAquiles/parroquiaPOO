<?php

/**
 * MockPaymentGateway
 * Implementación simulada de pasarela de pago para desarrollo y pruebas
 * NO USAR EN PRODUCCIÓN
 */
class MockPaymentGateway implements PaymentGatewayInterface
{
    private $mode;
    private $simulateFailure;

    public function __construct(string $mode = 'sandbox', bool $simulateFailure = false)
    {
        $this->mode = $mode;
        $this->simulateFailure = $simulateFailure;
    }

    /**
     * Procesa un pago simulado
     */
    public function processPayment(array $paymentData): array
    {
        // Validar datos requeridos
        $requiredFields = ['amount', 'currency', 'description'];
        foreach ($requiredFields as $field) {
            if (!isset($paymentData[$field])) {
                return [
                    'success' => false,
                    'transaction_id' => null,
                    'message' => "Campo requerido faltante: {$field}",
                    'data' => []
                ];
            }
        }

        // Validar monto
        if (!is_numeric($paymentData['amount']) || $paymentData['amount'] <= 0) {
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'El monto debe ser un número positivo',
                'data' => []
            ];
        }

        // Simular falla si está configurado
        if ($this->simulateFailure) {
            Logger::warning("MockPaymentGateway: Simulando falla de pago", $paymentData);
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Pago rechazado por el banco (simulado)',
                'data' => ['error_code' => 'MOCK_DECLINED']
            ];
        }

        // Generar ID de transacción simulado
        $transactionId = 'MOCK_' . strtoupper(uniqid('txn_', true));

        // Log del pago procesado
        Logger::info("MockPaymentGateway: Pago procesado exitosamente", [
            'transaction_id' => $transactionId,
            'amount' => $paymentData['amount'],
            'currency' => $paymentData['currency'],
            'mode' => $this->mode
        ]);

        // Simular respuesta exitosa
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'message' => 'Pago procesado exitosamente (modo simulación)',
            'data' => [
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
                'status' => 'completed',
                'processed_at' => date('Y-m-d H:i:s'),
                'mode' => $this->mode
            ]
        ];
    }

    /**
     * Verifica el estado de una transacción simulada
     */
    public function verifyTransaction(string $transactionId): array
    {
        // Validar formato del ID
        if (!str_starts_with($transactionId, 'MOCK_')) {
            return [
                'success' => false,
                'status' => 'not_found',
                'message' => 'Transacción no encontrada en el sistema mock'
            ];
        }

        Logger::info("MockPaymentGateway: Verificando transacción", [
            'transaction_id' => $transactionId
        ]);

        // Simular verificación exitosa
        return [
            'success' => true,
            'status' => 'completed',
            'message' => 'Transacción completada (modo simulación)',
            'data' => [
                'transaction_id' => $transactionId,
                'verified_at' => date('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Simula un reembolso
     */
    public function refundPayment(string $transactionId, float $amount = null): array
    {
        // Validar formato del ID
        if (!str_starts_with($transactionId, 'MOCK_')) {
            return [
                'success' => false,
                'refund_id' => null,
                'message' => 'Transacción no encontrada para reembolso'
            ];
        }

        // Generar ID de reembolso
        $refundId = 'MOCK_REFUND_' . strtoupper(uniqid('ref_', true));

        Logger::info("MockPaymentGateway: Reembolso procesado", [
            'transaction_id' => $transactionId,
            'refund_id' => $refundId,
            'amount' => $amount
        ]);

        // Simular reembolso exitoso
        return [
            'success' => true,
            'refund_id' => $refundId,
            'message' => 'Reembolso procesado exitosamente (modo simulación)',
            'data' => [
                'original_transaction_id' => $transactionId,
                'refunded_amount' => $amount,
                'refunded_at' => date('Y-m-d H:i:s')
            ]
        ];
    }

    /**
     * Obtiene el nombre del proveedor
     */
    public function getProviderName(): string
    {
        return 'Mock Payment Gateway';
    }
}
