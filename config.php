<?php


define('BASE_URL', $_ENV['APP_URL'] ?? 'http://parroquiapoo.test');
// Database configuration
// Leemos desde $_ENV en lugar de getenv()
define('DB_HOST', $_ENV['DB_HOST'] ?? 'localhost');
define('DB_NAME', $_ENV['DB_NAME'] ?? 'parroquia');
define('DB_USER', $_ENV['DB_USER'] ?? 'root');
define('DB_PASS', $_ENV['DB_PASS'] ?? '');

// SMTP Email configuration
// Leemos desde $_ENV en lugar de getenv()
define('SMTP_HOST', $_ENV['SMTP_HOST'] ?? 'smtp.gmail.com');
define('SMTP_USERNAME', $_ENV['SMTP_USERNAME'] ?? '');
define('SMTP_PASSWORD', $_ENV['SMTP_PASSWORD'] ?? '');
define('SMTP_FROM_EMAIL', $_ENV['SMTP_FROM_EMAIL'] ?? '');
define('SMTP_FROM_NAME', $_ENV['SMTP_FROM_NAME'] ?? 'Sistema Parroquial');
// Es buena práctica convertir el puerto a número
define('SMTP_PORT', (int)($_ENV['SMTP_PORT'] ?? 465));

// Payment Gateway configuration
define('PAYMENT_GATEWAY_MODE', $_ENV['PAYMENT_GATEWAY_MODE'] ?? 'sandbox'); // 'sandbox' or 'production'
define('PAYMENT_GATEWAY_PROVIDER', $_ENV['PAYMENT_GATEWAY_PROVIDER'] ?? 'mock'); // 'mock', 'stripe', 'paypal', etc.
define('PAYMENT_GATEWAY_API_KEY', $_ENV['PAYMENT_GATEWAY_API_KEY'] ?? '');
define('PAYMENT_GATEWAY_SECRET_KEY', $_ENV['PAYMENT_GATEWAY_SECRET_KEY'] ?? '');
define('PAYMENT_DEFAULT_CURRENCY', $_ENV['PAYMENT_DEFAULT_CURRENCY'] ?? 'COP');
define('PAYMENT_CERTIFICATE_PRICE', (float)($_ENV['PAYMENT_CERTIFICATE_PRICE'] ?? 10000));

// PaymentsWay (VePay) - Specific configuration for Colombia
define('PAYMENTSWAY_MERCHANT_ID', $_ENV['PAYMENTSWAY_MERCHANT_ID'] ?? '465');
define('PAYMENTSWAY_FORM_ID', $_ENV['PAYMENTSWAY_FORM_ID'] ?? '381');
define('PAYMENTSWAY_TERMINAL_ID', $_ENV['PAYMENTSWAY_TERMINAL_ID'] ?? '382');
define('PAYMENTSWAY_RESPONSE_URL', $_ENV['PAYMENTSWAY_RESPONSE_URL'] ?? '');

// Produccion - Byethost

// define('DB_HOST', 'sql302.byethost7.com');
// define('DB_USER', 'b7_40249021');
// define('DB_PASS', 'Aquiles117.');
// define('DB_NAME', 'b7_40249021_b7_40249021_parroquia');