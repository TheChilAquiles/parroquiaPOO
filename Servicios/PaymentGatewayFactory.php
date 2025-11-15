<?php

/**
 * PaymentGatewayFactory
 * Crea instancias de pasarelas de pago según la configuración
 */
class PaymentGatewayFactory
{
    /**
     * Crea una instancia de la pasarela de pago configurada
     *
     * @return PaymentGatewayInterface
     * @throws Exception Si el proveedor no es soportado
     */
    public static function create(): PaymentGatewayInterface
    {
        $provider = PAYMENT_GATEWAY_PROVIDER;
        $mode = PAYMENT_GATEWAY_MODE;

        Logger::info("PaymentGatewayFactory: Creando gateway", [
            'provider' => $provider,
            'mode' => $mode
        ]);

        switch (strtolower($provider)) {
            case 'mock':
                return new MockPaymentGateway($mode);

            case 'paymentway':
                return new PaymentWayGateway(
                    PAYMENT_GATEWAY_API_KEY,
                    PAYMENT_GATEWAY_SECRET_KEY,
                    $mode
                );

            case 'stripe':
                // Aquí se implementaría la integración con Stripe
                // return new StripePaymentGateway(PAYMENT_GATEWAY_API_KEY, $mode);
                throw new Exception("Stripe payment gateway no implementado aún. Use 'mock' o 'paymentway'.");

            case 'paypal':
                // Aquí se implementaría la integración con PayPal
                // return new PayPalPaymentGateway(PAYMENT_GATEWAY_API_KEY, PAYMENT_GATEWAY_SECRET_KEY, $mode);
                throw new Exception("PayPal payment gateway no implementado aún. Use 'mock' o 'paymentway'.");

            default:
                Logger::error("PaymentGatewayFactory: Proveedor no soportado", [
                    'provider' => $provider
                ]);
                throw new Exception("Proveedor de pago no soportado: {$provider}. Proveedores disponibles: mock, paymentway");
        }
    }

    /**
     * Verifica si la configuración del gateway es válida
     *
     * @return array ['valid' => bool, 'errors' => array]
     */
    public static function validateConfiguration(): array
    {
        $errors = [];

        // Validar que el modo sea válido
        if (!in_array(PAYMENT_GATEWAY_MODE, ['sandbox', 'production'])) {
            $errors[] = "PAYMENT_GATEWAY_MODE debe ser 'sandbox' o 'production'";
        }

        // Validar proveedor
        if (empty(PAYMENT_GATEWAY_PROVIDER)) {
            $errors[] = "PAYMENT_GATEWAY_PROVIDER no puede estar vacío";
        }

        // Validar que en producción no se use mock
        if (PAYMENT_GATEWAY_MODE === 'production' && PAYMENT_GATEWAY_PROVIDER === 'mock') {
            $errors[] = "No se puede usar 'mock' gateway en modo producción";
        }

        // Validar API keys para proveedores reales
        if (in_array(PAYMENT_GATEWAY_PROVIDER, ['stripe', 'paypal', 'paymentway']) && empty(PAYMENT_GATEWAY_API_KEY)) {
            $errors[] = "PAYMENT_GATEWAY_API_KEY es requerido para " . PAYMENT_GATEWAY_PROVIDER;
        }

        // Validar secret key para proveedores que lo requieren
        if (in_array(PAYMENT_GATEWAY_PROVIDER, ['paypal', 'paymentway']) && empty(PAYMENT_GATEWAY_SECRET_KEY)) {
            $errors[] = "PAYMENT_GATEWAY_SECRET_KEY es requerido para " . PAYMENT_GATEWAY_PROVIDER;
        }

        // Validar precio del certificado
        if (!is_numeric(PAYMENT_CERTIFICATE_PRICE) || PAYMENT_CERTIFICATE_PRICE <= 0) {
            $errors[] = "PAYMENT_CERTIFICATE_PRICE debe ser un número positivo";
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors
        ];
    }

    /**
     * Obtiene información sobre los proveedores disponibles
     *
     * @return array
     */
    public static function getAvailableProviders(): array
    {
        return [
            'mock' => [
                'name' => 'Mock Payment Gateway',
                'description' => 'Simulador de pagos para desarrollo y pruebas',
                'status' => 'available',
                'supports_sandbox' => true,
                'country' => 'Global'
            ],
            'paymentway' => [
                'name' => 'PaymentWay Colombia',
                'description' => 'Pasarela de pagos colombiana - Acepta PSE, tarjetas crédito/débito, billeteras electrónicas',
                'status' => 'available',
                'supports_sandbox' => true,
                'country' => 'Colombia'
            ],
            'stripe' => [
                'name' => 'Stripe',
                'description' => 'Procesador de pagos internacional',
                'status' => 'not_implemented',
                'supports_sandbox' => true,
                'country' => 'Global'
            ],
            'paypal' => [
                'name' => 'PayPal',
                'description' => 'Procesador de pagos PayPal',
                'status' => 'not_implemented',
                'supports_sandbox' => true,
                'country' => 'Global'
            ]
        ];
    }
}
