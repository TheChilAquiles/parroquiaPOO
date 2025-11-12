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



// Produccion - Byethost

// define('DB_HOST', 'sql302.byethost7.com');
// define('DB_USER', 'b7_40249021');
// define('DB_PASS', 'Aquiles117.');
// define('DB_NAME', 'b7_40249021_b7_40249021_parroquia');