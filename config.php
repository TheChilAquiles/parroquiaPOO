<?php
// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_NAME', getenv('DB_NAME') ?: 'parroquia');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');

// SMTP Email configuration
define('SMTP_HOST', getenv('SMTP_HOST') ?: 'smtp.gmail.com');
define('SMTP_USERNAME', getenv('SMTP_USERNAME') ?: '');
define('SMTP_PASSWORD', getenv('SMTP_PASSWORD') ?: '');
define('SMTP_FROM_EMAIL', getenv('SMTP_FROM_EMAIL') ?: '');
define('SMTP_FROM_NAME', getenv('SMTP_FROM_NAME') ?: 'Sistema Parroquial');
define('SMTP_PORT', getenv('SMTP_PORT') ?: 465);



// Produccion - Byethost

// define('DB_HOST', 'sql302.byethost7.com');
// define('DB_USER', 'b7_40249021');
// define('DB_PASS', 'Aquiles117.');
// define('DB_NAME', 'b7_40249021_b7_40249021_parroquia');

