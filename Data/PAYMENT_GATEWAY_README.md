# Payment Gateway Integration

## Overview

This system implements a flexible payment gateway integration that supports multiple payment providers. The current implementation includes a mock gateway for development and testing, with the structure ready to add real payment providers like Stripe, PayPal, etc.

## Architecture

The payment gateway system consists of three main components:

1. **PaymentGatewayInterface**: Defines the contract that all payment gateways must implement
2. **PaymentGatewayFactory**: Creates instances of payment gateways based on configuration
3. **Payment Gateway Implementations**: Specific implementations for each provider (MockPaymentGateway, StripePaymentGateway, etc.)

## Configuration

Payment gateway settings are configured via environment variables in `.env`:

```env
# Payment Gateway Configuration
PAYMENT_GATEWAY_MODE=sandbox              # 'sandbox' or 'production'
PAYMENT_GATEWAY_PROVIDER=mock             # 'mock', 'stripe', 'paypal', etc.
PAYMENT_GATEWAY_API_KEY=                  # API key for the payment provider
PAYMENT_GATEWAY_SECRET_KEY=               # Secret key for the payment provider
PAYMENT_DEFAULT_CURRENCY=USD              # Default currency code
PAYMENT_CERTIFICATE_PRICE=10.00           # Default price for certificates
```

### Available Providers

- **mock**: Simulated payment gateway for development and testing (default)
- **stripe**: Stripe payment gateway (not yet implemented)
- **paypal**: PayPal payment gateway (not yet implemented)

## Usage

### Processing a Payment

```php
// Create gateway instance
$gateway = PaymentGatewayFactory::create();

// Prepare payment data
$paymentData = [
    'amount' => 10.00,
    'currency' => 'USD',
    'description' => 'Payment for certificate #123',
    'metadata' => [
        'certificado_id' => 123,
        'feligres_id' => 456
    ]
];

// Process payment
$result = $gateway->processPayment($paymentData);

if ($result['success']) {
    $transactionId = $result['transaction_id'];
    echo "Payment successful! Transaction ID: {$transactionId}";
} else {
    echo "Payment failed: {$result['message']}";
}
```

### Verifying a Transaction

```php
$gateway = PaymentGatewayFactory::create();
$result = $gateway->verifyTransaction($transactionId);

if ($result['success']) {
    echo "Transaction status: {$result['status']}";
}
```

### Refunding a Payment

```php
$gateway = PaymentGatewayFactory::create();
$result = $gateway->refundPayment($transactionId, $amount);

if ($result['success']) {
    echo "Refund successful! Refund ID: {$result['refund_id']}";
}
```

## Database Migration

Before using the payment gateway system with transaction tracking, run the migration to add the `transaction_id` column to the `pagos` table:

```bash
mysql -u root -p parroquia < Data/migration_add_transaction_id_to_pagos.sql
```

## Mock Payment Gateway

The mock gateway simulates payment processing for development and testing purposes.

### Features:
- Validates payment data (amount, currency, description)
- Generates realistic transaction IDs (format: `MOCK_TXN_xxxxx`)
- Logs all operations for debugging
- Supports simulated failures for testing error handling
- No real payment processing occurs

### Important Notes:
- **DO NOT USE IN PRODUCTION**: The mock gateway is for development only
- All "payments" are simulated and no money is actually transferred
- The factory will prevent using mock gateway in production mode

## Adding a New Payment Provider

To add a new payment provider (e.g., Stripe):

1. **Create the gateway class** in `Servicios/`:

```php
<?php
class StripePaymentGateway implements PaymentGatewayInterface
{
    private $apiKey;
    private $mode;

    public function __construct(string $apiKey, string $mode = 'sandbox')
    {
        $this->apiKey = $apiKey;
        $this->mode = $mode;
        // Initialize Stripe SDK
    }

    public function processPayment(array $paymentData): array
    {
        // Implement Stripe payment processing
    }

    public function verifyTransaction(string $transactionId): array
    {
        // Implement transaction verification
    }

    public function refundPayment(string $transactionId, float $amount = null): array
    {
        // Implement refund logic
    }

    public function getProviderName(): string
    {
        return 'Stripe';
    }
}
```

2. **Update PaymentGatewayFactory** to include the new provider:

```php
case 'stripe':
    return new StripePaymentGateway(PAYMENT_GATEWAY_API_KEY, $mode);
```

3. **Update configuration** to use the new provider:

```env
PAYMENT_GATEWAY_PROVIDER=stripe
PAYMENT_GATEWAY_API_KEY=sk_test_xxxxx
```

## Security Considerations

- **API Keys**: Never commit API keys to version control. Use environment variables.
- **HTTPS**: Always use HTTPS in production to protect sensitive payment data
- **Validation**: Always validate payment amounts and data before processing
- **Logging**: Payment operations are logged for audit purposes
- **Error Handling**: Failed payments are logged but sensitive data is not exposed to users

## Testing

### Development Environment
Set `PAYMENT_GATEWAY_MODE=sandbox` and `PAYMENT_GATEWAY_PROVIDER=mock` for development.

### Simulating Payment Failures
The mock gateway can simulate failures for testing error handling:

```php
$gateway = new MockPaymentGateway('sandbox', true); // true = simulate failure
```

## Troubleshooting

### Configuration Validation
To check if your payment gateway configuration is valid:

```php
$validation = PaymentGatewayFactory::validateConfiguration();
if (!$validation['valid']) {
    print_r($validation['errors']);
}
```

### Common Issues

1. **"Proveedor de pago no soportado"**: Check that PAYMENT_GATEWAY_PROVIDER is set to a valid provider
2. **"No se puede usar 'mock' gateway en modo producción"**: Change PAYMENT_GATEWAY_MODE to 'sandbox' or use a real provider
3. **"PAYMENT_GATEWAY_API_KEY es requerido"**: Set the API key in .env for real payment providers

## Future Enhancements

- [ ] Implement Stripe payment gateway
- [ ] Implement PayPal payment gateway
- [ ] Add support for recurring payments
- [ ] Implement payment webhooks for asynchronous confirmations
- [ ] Add payment retry logic for failed transactions
- [ ] Implement fraud detection
- [ ] Add support for multiple currencies with conversion rates
