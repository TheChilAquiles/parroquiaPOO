<?php
/**
 * Test script for Payment Gateway Integration
 * Run this script to verify the payment gateway is working correctly
 */

// Load required files
require_once __DIR__ . '/../config.php';
require_once __DIR__ . '/../autoload.php';

echo "=== Payment Gateway Integration Test ===\n\n";

// Test 1: Configuration Validation
echo "Test 1: Validating Configuration...\n";
$validation = PaymentGatewayFactory::validateConfiguration();
if ($validation['valid']) {
    echo "✓ Configuration is valid\n";
} else {
    echo "✗ Configuration errors:\n";
    foreach ($validation['errors'] as $error) {
        echo "  - $error\n";
    }
}
echo "\n";

// Test 2: Available Providers
echo "Test 2: Checking Available Providers...\n";
$providers = PaymentGatewayFactory::getAvailableProviders();
foreach ($providers as $key => $provider) {
    echo "  - {$provider['name']}: {$provider['status']}\n";
}
echo "\n";

// Test 3: Create Gateway Instance
echo "Test 3: Creating Payment Gateway Instance...\n";
try {
    $gateway = PaymentGatewayFactory::create();
    echo "✓ Gateway created: {$gateway->getProviderName()}\n";
} catch (Exception $e) {
    echo "✗ Failed to create gateway: {$e->getMessage()}\n";
    exit(1);
}
echo "\n";

// Test 4: Process a Payment
echo "Test 4: Processing Test Payment...\n";
$paymentData = [
    'amount' => 10.00,
    'currency' => 'USD',
    'description' => 'Test payment for certificate #999',
    'metadata' => [
        'certificado_id' => 999,
        'feligres_id' => 123,
        'test' => true
    ]
];

$result = $gateway->processPayment($paymentData);
if ($result['success']) {
    echo "✓ Payment processed successfully\n";
    echo "  Transaction ID: {$result['transaction_id']}\n";
    echo "  Message: {$result['message']}\n";
    $transactionId = $result['transaction_id'];
} else {
    echo "✗ Payment failed: {$result['message']}\n";
    exit(1);
}
echo "\n";

// Test 5: Verify Transaction
echo "Test 5: Verifying Transaction...\n";
$verifyResult = $gateway->verifyTransaction($transactionId);
if ($verifyResult['success']) {
    echo "✓ Transaction verified\n";
    echo "  Status: {$verifyResult['status']}\n";
} else {
    echo "✗ Verification failed: {$verifyResult['message']}\n";
}
echo "\n";

// Test 6: Refund Payment
echo "Test 6: Testing Refund...\n";
$refundResult = $gateway->refundPayment($transactionId, 5.00);
if ($refundResult['success']) {
    echo "✓ Refund processed\n";
    echo "  Refund ID: {$refundResult['refund_id']}\n";
} else {
    echo "✗ Refund failed: {$refundResult['message']}\n";
}
echo "\n";

// Test 7: Invalid Payment Data
echo "Test 7: Testing Validation (should fail)...\n";
$invalidData = [
    'amount' => -10.00,  // Invalid: negative amount
    'currency' => 'USD',
    'description' => 'Invalid payment'
];
$invalidResult = $gateway->processPayment($invalidData);
if (!$invalidResult['success']) {
    echo "✓ Validation working correctly: {$invalidResult['message']}\n";
} else {
    echo "✗ Validation failed - accepted invalid data\n";
}
echo "\n";

echo "=== All Tests Completed ===\n";
echo "\nCurrent Configuration:\n";
echo "  Provider: " . PAYMENT_GATEWAY_PROVIDER . "\n";
echo "  Mode: " . PAYMENT_GATEWAY_MODE . "\n";
echo "  Currency: " . PAYMENT_DEFAULT_CURRENCY . "\n";
echo "  Certificate Price: $" . PAYMENT_CERTIFICATE_PRICE . "\n";
