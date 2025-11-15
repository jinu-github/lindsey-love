<?php
// Load environment variables from .env file if it exists
if (file_exists(__DIR__ . '/../.env')) {
    $envLines = file(__DIR__ . '/../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($envLines as $line) {
        if (strpos(trim($line), '#') === 0) {
            continue; // Skip comments
        }
        list($name, $value) = explode('=', $line, 2);
        $name = trim($name);
        $value = trim($value);
        if (!getenv($name)) {
            putenv("$name=$value");
        }
    }
}

// Database Configuration
define('DB_HOST', getenv('DB_HOST') ?: 'localhost');
define('DB_USER', getenv('DB_USER') ?: 'root');
define('DB_PASS', getenv('DB_PASS') ?: '');
define('DB_NAME', getenv('DB_NAME') ?: 'eQueue');

// Development mode for testing (set to false in production)
define('DEVELOPMENT_MODE', false);

// Email Configuration - Gmail SMTP with PHPMailer (requires Google App Password)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USERNAME', 'justinvillanueva98@gmail.com');
define('SMTP_PASSWORD', 'qpvh mhwa glfp qrdd');
define('SMTP_ENCRYPTION', 'tls');
define('SMTP_FROM_EMAIL', 'justinvillanueva98@gmail.com');
define('SMTP_FROM_NAME', 'eQueue System');


// SMS Configuration - iprogtech API
define('IPROG_API_TOKEN', getenv('IPROG_API_TOKEN') ?: '');
define('IPROG_API_URL', getenv('IPROG_API_URL') ?: 'https://sms.iprogtech.com/api/v1/sms_messages');
define('SMS_SENDER_NAME', getenv('SMS_SENDER_NAME') ?: 'eQueue');
define('CLINIC_NAME', getenv('CLINIC_NAME') ?: 'Medicare Clinic');

// Establish database connection (only if not already defined, for testing)
if (!isset($conn) && class_exists('mysqli')) {
    $conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }
}
?>
