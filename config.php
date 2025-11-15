<?php
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
define('PAYMENT_DEFAULT_CURRENCY', $_ENV['PAYMENT_DEFAULT_CURRENCY'] ?? 'USD');
define('PAYMENT_CERTIFICATE_PRICE', (float)($_ENV['PAYMENT_CERTIFICATE_PRICE'] ?? 10.00));

// Produccion - Byethost

// define('DB_HOST', 'sql302.byethost7.com');
// define('DB_USER', 'b7_40249021');
// define('DB_PASS', 'Aquiles117.');
// define('DB_NAME', 'b7_40249021_b7_40249021_parroquia');