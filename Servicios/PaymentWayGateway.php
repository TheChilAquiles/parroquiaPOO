<?php

/**
 * PaymentWayGateway
 * Implementación del gateway de PaymentsWay (VePay) - Colombia
 *
 * PaymentsWay utiliza un sistema de redirección de formularios HTML
 * donde el usuario es redirigido a su pasarela de pagos para completar la transacción.
 *
 * Documentación: https://www.vepay.com.co
 */
class PaymentWayGateway implements PaymentGatewayInterface
{
    private $merchantId;
    private $formId;
    private $terminalId;
    private $mode; // 'sandbox' o 'production'
    private $responseUrl;

    // URLs de PaymentsWay (VePay)
    const SANDBOX_URL = 'https://merchantpruebas.vepay.com.co/cartaspago/redirect';
    const PRODUCTION_URL = 'https://merchant.vepay.com.co/cartaspago/redirect';

    public function __construct(?string $merchantId = null, ?string $formId = null, ?string $terminalId = null, string $mode = 'sandbox')
    {
        // Usar constantes de config si no se pasan parámetros
        $this->merchantId = $merchantId ?? PAYMENTSWAY_MERCHANT_ID;
        $this->formId = $formId ?? PAYMENTSWAY_FORM_ID;
        $this->terminalId = $terminalId ?? PAYMENTSWAY_TERMINAL_ID;
        $this->mode = $mode;
        $this->responseUrl = PAYMENTSWAY_RESPONSE_URL;

        // Validar credenciales
        if (empty($this->merchantId) || empty($this->formId) || empty($this->terminalId)) {
            throw new Exception("PaymentsWay requiere merchant_id, form_id y terminal_id válidos");
        }
    }

    /**
     * Procesa un pago a través de PaymentsWay
     * Este método genera los datos necesarios para la redirección al formulario de pago
     *
     * @param array $paymentData Datos del pago
     * @return array
     */
    public function processPayment(array $paymentData): array
    {
        try {
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

            // Generar número de orden único
            $orderNumber = $this->generateOrderNumber($paymentData['metadata']['certificado_id'] ?? null);

            // Preparar datos para el formulario de PaymentsWay
            $formData = [
                'merchant_id' => $this->merchantId,
                'form_id' => $this->formId,
                'terminal_id' => $this->terminalId,
                'order_number' => $orderNumber,
                'amount' => $paymentData['amount'],
                'currency' => strtolower($paymentData['currency']), // PaymentsWay usa minúsculas
                'order_description' => $paymentData['description'],
                'color_base' => '#3e5569', // Color del tema de la pasarela
                'response_url' => $this->responseUrl,
            ];

            // Datos opcionales del cliente (si están disponibles)
            if (isset($paymentData['customer_email'])) {
                $formData['client_email'] = $paymentData['customer_email'];
            }
            if (isset($paymentData['customer_phone'])) {
                $formData['client_phone'] = $paymentData['customer_phone'];
            }
            if (isset($paymentData['customer_name'])) {
                // Dividir nombre completo en nombre y apellido
                $nameParts = explode(' ', $paymentData['customer_name'], 2);
                $formData['client_firstname'] = $nameParts[0];
                $formData['client_lastname'] = $nameParts[1] ?? '';
            }
            if (isset($paymentData['customer_doctype'])) {
                $formData['client_doctype'] = $paymentData['customer_doctype'];
            }
            if (isset($paymentData['customer_numdoc'])) {
                $formData['client_numdoc'] = $paymentData['customer_numdoc'];
            }

            // Determinar URL de redirección según el modo
            $paymentUrl = ($this->mode === 'production') ? self::PRODUCTION_URL : self::SANDBOX_URL;

            Logger::info("PaymentsWay: Preparando redirección de pago", [
                'order_number' => $orderNumber,
                'amount' => $paymentData['amount'],
                'mode' => $this->mode
            ]);

            return [
                'success' => true,
                'transaction_id' => $orderNumber,
                'message' => 'Redirección a pasarela de pago preparada',
                'data' => [
                    'payment_url' => $paymentUrl,
                    'form_data' => $formData,
                    'requires_redirect' => true,
                    'mode' => $this->mode
                ]
            ];

        } catch (Exception $e) {
            Logger::error("PaymentsWay: Error al preparar pago", [
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Error al preparar el pago: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Verifica el estado de una transacción
     * NOTA: PaymentsWay no proporciona API para verificación directa,
     * la confirmación se hace mediante webhook/callback
     *
     * @param string $transactionId
     * @return array
     */
    public function verifyTransaction(string $transactionId): array
    {
        Logger::warning("PaymentsWay: verifyTransaction no implementado - use callback", [
            'transaction_id' => $transactionId
        ]);

        return [
            'success' => false,
            'status' => 'unknown',
            'message' => 'PaymentsWay no soporta verificación directa. Use el callback de respuesta.'
        ];
    }

    /**
     * Reembolsa un pago
     * NOTA: Los reembolsos en PaymentsWay deben gestionarse manualmente a través del panel administrativo
     *
     * @param string $transactionId
     * @param float|null $amount
     * @return array
     */
    public function refundPayment(string $transactionId, ?float $amount = null): array
    {
        Logger::warning("PaymentsWay: refundPayment no implementado", [
            'transaction_id' => $transactionId
        ]);

        return [
            'success' => false,
            'refund_id' => null,
            'message' => 'Los reembolsos en PaymentsWay deben gestionarse a través del panel administrativo'
        ];
    }

    /**
     * Obtiene el nombre del proveedor
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return 'PaymentsWay Colombia (VePay)';
    }

    /**
     * Genera un número de orden único
     *
     * @param int|null $certificadoId
     * @return string
     */
    private function generateOrderNumber($certificadoId = null): string
    {
        $prefix = 'CERT';
        $timestamp = time();
        $random = strtoupper(substr(uniqid(), -6));

        if ($certificadoId) {
            return "{$prefix}{$certificadoId}_{$timestamp}_{$random}";
        }

        return "{$prefix}_{$timestamp}_{$random}";
    }

    /**
     * Procesa la respuesta de PaymentsWay después del pago
     * Este método debe llamarse desde el callback/webhook
     *
     * @param array $responseData Datos recibidos de PaymentsWay
     * @return array
     */
    public function processCallback(array $responseData): array
    {
        try {
            // Validar que tengamos los datos necesarios
            if (empty($responseData['order_number'])) {
                return [
                    'success' => false,
                    'message' => 'Respuesta inválida: falta order_number'
                ];
            }

            // PaymentsWay envía diferentes estados según el resultado
            // Los estados pueden ser: 'approved', 'pending', 'rejected', 'cancelled', etc.
            $status = strtolower($responseData['status'] ?? $responseData['estado'] ?? 'unknown');

            $success = in_array($status, ['approved', 'aprobada', 'exitosa', 'success']);

            Logger::info("PaymentsWay: Callback procesado", [
                'order_number' => $responseData['order_number'],
                'status' => $status,
                'success' => $success
            ]);

            return [
                'success' => $success,
                'order_number' => $responseData['order_number'],
                'status' => $status,
                'transaction_id' => $responseData['transaction_id'] ?? $responseData['order_number'],
                'amount' => $responseData['amount'] ?? null,
                'message' => $success ? 'Pago aprobado' : "Pago {$status}",
                'raw_data' => $responseData
            ];

        } catch (Exception $e) {
            Logger::error("PaymentsWay: Error al procesar callback", [
                'error' => $e->getMessage(),
                'data' => $responseData
            ]);

            return [
                'success' => false,
                'message' => 'Error al procesar respuesta: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Genera el HTML del formulario de pago para redirección automática
     *
     * @param array $formData Datos del formulario
     * @return string HTML del formulario
     */
    public function generatePaymentForm(array $formData): string
    {
        $paymentUrl = ($this->mode === 'production') ? self::PRODUCTION_URL : self::SANDBOX_URL;

        $html = '<form id="paymentForm" method="post" action="' . htmlspecialchars($paymentUrl) . '">';

        foreach ($formData as $key => $value) {
            $html .= '<input name="' . htmlspecialchars($key) . '" type="hidden" value="' . htmlspecialchars($value) . '">';
        }

        $html .= '</form>';
        $html .= '<script>document.getElementById("paymentForm").submit();</script>';

        return $html;
    }
}
