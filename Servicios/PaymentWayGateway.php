<?php

/**
 * PaymentWayGateway
 * Implementación del gateway de Payment Way Solutions (Colombia)
 *
 * NOTA: Esta es una implementación base que debe ser configurada con las credenciales
 * y endpoints reales de Payment Way Solutions.
 *
 * Documentación oficial: Contactar a Payments Way Solutions (paymentsway.co)
 * para obtener credenciales de API y documentación técnica.
 */
class PaymentWayGateway implements PaymentGatewayInterface
{
    private $apiKey;
    private $secretKey;
    private $mode; // 'sandbox' o 'production'
    private $apiUrl;

    // Endpoints (estos deben ser actualizados con los URLs reales de PaymentWay)
    const SANDBOX_URL = 'https://sandbox.paymentsway.co/api/v1'; // URL hipotética
    const PRODUCTION_URL = 'https://api.paymentsway.co/api/v1';  // URL hipotética

    public function __construct(string $apiKey, string $secretKey, string $mode = 'sandbox')
    {
        $this->apiKey = $apiKey;
        $this->secretKey = $secretKey;
        $this->mode = $mode;
        $this->apiUrl = ($mode === 'production') ? self::PRODUCTION_URL : self::SANDBOX_URL;

        // Validar credenciales
        if (empty($this->apiKey) || empty($this->secretKey)) {
            throw new Exception("PaymentWay requiere API Key y Secret Key válidos");
        }
    }

    /**
     * Procesa un pago a través de PaymentWay
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

            // Preparar datos para la API de PaymentWay
            // NOTA: Estos campos deben ajustarse según la documentación oficial de PaymentWay
            $requestData = [
                'api_key' => $this->apiKey,
                'amount' => $paymentData['amount'],
                'currency' => $paymentData['currency'],
                'description' => $paymentData['description'],
                'reference' => $this->generateReference(),
                'customer' => [
                    'email' => $paymentData['customer_email'] ?? '',
                    'name' => $paymentData['customer_name'] ?? '',
                    'phone' => $paymentData['customer_phone'] ?? ''
                ],
                'metadata' => $paymentData['metadata'] ?? [],
                'return_url' => $paymentData['return_url'] ?? '',
                'notification_url' => $paymentData['notification_url'] ?? '',
            ];

            // Generar firma de seguridad
            $requestData['signature'] = $this->generateSignature($requestData);

            // Hacer petición HTTP a PaymentWay
            $response = $this->makeApiRequest('/payments', 'POST', $requestData);

            // Procesar respuesta
            if ($response['success']) {
                Logger::info("PaymentWay: Pago procesado exitosamente", [
                    'transaction_id' => $response['transaction_id'] ?? 'N/A',
                    'amount' => $paymentData['amount']
                ]);

                return [
                    'success' => true,
                    'transaction_id' => $response['transaction_id'] ?? null,
                    'message' => 'Pago procesado exitosamente',
                    'data' => [
                        'payment_url' => $response['payment_url'] ?? null, // URL para redirigir al usuario
                        'status' => $response['status'] ?? 'pending',
                        'reference' => $response['reference'] ?? null,
                        'mode' => $this->mode
                    ]
                ];
            } else {
                Logger::error("PaymentWay: Pago rechazado", $response);
                return [
                    'success' => false,
                    'transaction_id' => null,
                    'message' => $response['message'] ?? 'Pago rechazado',
                    'data' => ['error_code' => $response['error_code'] ?? 'UNKNOWN']
                ];
            }

        } catch (Exception $e) {
            Logger::error("PaymentWay: Error al procesar pago", [
                'error' => $e->getMessage()
            ]);
            return [
                'success' => false,
                'transaction_id' => null,
                'message' => 'Error al procesar el pago: ' . $e->getMessage(),
                'data' => []
            ];
        }
    }

    /**
     * Verifica el estado de una transacción
     *
     * @param string $transactionId
     * @return array
     */
    public function verifyTransaction(string $transactionId): array
    {
        try {
            $requestData = [
                'api_key' => $this->apiKey,
                'transaction_id' => $transactionId
            ];

            $requestData['signature'] = $this->generateSignature($requestData);

            $response = $this->makeApiRequest('/payments/' . $transactionId, 'GET', $requestData);

            if ($response['success']) {
                return [
                    'success' => true,
                    'status' => $response['status'] ?? 'unknown',
                    'message' => 'Transacción verificada',
                    'data' => $response
                ];
            } else {
                return [
                    'success' => false,
                    'status' => 'error',
                    'message' => $response['message'] ?? 'Error al verificar transacción'
                ];
            }

        } catch (Exception $e) {
            Logger::error("PaymentWay: Error al verificar transacción", [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);
            return [
                'success' => false,
                'status' => 'error',
                'message' => 'Error al verificar transacción: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Reembolsa un pago
     *
     * @param string $transactionId
     * @param float|null $amount
     * @return array
     */
    public function refundPayment(string $transactionId, float $amount = null): array
    {
        try {
            $requestData = [
                'api_key' => $this->apiKey,
                'transaction_id' => $transactionId,
                'amount' => $amount,
                'reason' => 'Solicitud de reembolso'
            ];

            $requestData['signature'] = $this->generateSignature($requestData);

            $response = $this->makeApiRequest('/refunds', 'POST', $requestData);

            if ($response['success']) {
                Logger::info("PaymentWay: Reembolso procesado", [
                    'transaction_id' => $transactionId,
                    'refund_id' => $response['refund_id'] ?? 'N/A'
                ]);

                return [
                    'success' => true,
                    'refund_id' => $response['refund_id'] ?? null,
                    'message' => 'Reembolso procesado exitosamente',
                    'data' => $response
                ];
            } else {
                return [
                    'success' => false,
                    'refund_id' => null,
                    'message' => $response['message'] ?? 'Error al procesar reembolso'
                ];
            }

        } catch (Exception $e) {
            Logger::error("PaymentWay: Error al procesar reembolso", [
                'error' => $e->getMessage(),
                'transaction_id' => $transactionId
            ]);
            return [
                'success' => false,
                'refund_id' => null,
                'message' => 'Error al procesar reembolso: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtiene el nombre del proveedor
     *
     * @return string
     */
    public function getProviderName(): string
    {
        return 'PaymentWay Colombia';
    }

    /**
     * Genera una referencia única para la transacción
     *
     * @return string
     */
    private function generateReference(): string
    {
        return 'PW_' . strtoupper(uniqid('', true)) . '_' . time();
    }

    /**
     * Genera la firma de seguridad para la petición
     * NOTA: Debe ajustarse según el algoritmo específico de PaymentWay
     *
     * @param array $data
     * @return string
     */
    private function generateSignature(array $data): string
    {
        // Este es un ejemplo genérico. Debe reemplazarse con el algoritmo oficial de PaymentWay
        // Usualmente es un HMAC-SHA256 de ciertos campos concatenados

        // Ordenar datos alfabéticamente
        ksort($data);

        // Construir string de firma
        $signatureString = '';
        foreach ($data as $key => $value) {
            if ($key !== 'signature' && !is_array($value)) {
                $signatureString .= $key . '=' . $value . '&';
            }
        }
        $signatureString = rtrim($signatureString, '&');

        // Generar HMAC
        $signature = hash_hmac('sha256', $signatureString, $this->secretKey);

        return $signature;
    }

    /**
     * Realiza una petición HTTP a la API de PaymentWay
     *
     * @param string $endpoint
     * @param string $method
     * @param array $data
     * @return array
     */
    private function makeApiRequest(string $endpoint, string $method, array $data): array
    {
        $url = $this->apiUrl . $endpoint;

        $headers = [
            'Content-Type: application/json',
            'Accept: application/json',
            'Authorization: Bearer ' . $this->apiKey
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, $this->mode === 'production');
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        } elseif ($method === 'GET') {
            curl_setopt($ch, CURLOPT_URL, $url . '?' . http_build_query($data));
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            throw new Exception("cURL Error: " . $curlError);
        }

        $responseData = json_decode($response, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new Exception("Error al decodificar respuesta JSON");
        }

        // Determinar si fue exitoso basado en el código HTTP
        $responseData['success'] = ($httpCode >= 200 && $httpCode < 300);
        $responseData['http_code'] = $httpCode;

        return $responseData;
    }

    /**
     * Valida la notificación de webhook de PaymentWay
     *
     * @param array $webhookData
     * @return bool
     */
    public function validateWebhook(array $webhookData): bool
    {
        if (!isset($webhookData['signature'])) {
            return false;
        }

        $receivedSignature = $webhookData['signature'];
        $expectedSignature = $this->generateSignature($webhookData);

        return hash_equals($expectedSignature, $receivedSignature);
    }
}
