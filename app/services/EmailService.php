<?php
require_once __DIR__ . '/../../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class EmailService {
    private $lastError;

    public function __construct() {
        // No initialization needed for PHPMailer
    }

    public function sendPasswordResetEmail($toEmail, $toName, $resetLink) {
        try {
            $mail = new PHPMailer(true);

            // Server settings
            $mail->isSMTP();
            $mail->Host = SMTP_HOST;
            $mail->SMTPAuth = true;
            $mail->Username = SMTP_USERNAME;
            $mail->Password = SMTP_PASSWORD;
            $mail->SMTPSecure = SMTP_ENCRYPTION; // Use TLS encryption
            $mail->Port = SMTP_PORT;

            // Recipients
            $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addReplyTo(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
            $mail->addAddress($toEmail, $toName);

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset - eQueue System';
            $mail->Body = $this->getPasswordResetEmailTemplate($toName, $resetLink);
            $mail->AltBody = strip_tags($this->getPasswordResetEmailTemplate($toName, $resetLink));

            // Send email
            $mail->send();
            return true;
        } catch (Exception $e) {
            $this->lastError = $mail->ErrorInfo;
            error_log("Email sending failed: " . $this->lastError);
            return false;
        }
    }

    private function getPasswordResetEmailTemplate($name, $resetLink) {
        return "
        <!DOCTYPE html>
        <html lang='en'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Password Reset</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background-color: #007bff; color: white; padding: 20px; text-align: center; }
                .content { padding: 20px; background-color: #f9f9f9; }
                .button { display: inline-block; padding: 10px 20px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
                .footer { padding: 20px; text-align: center; font-size: 12px; color: #666; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>eQueue System - Password Reset</h1>
                </div>
                <div class='content'>
                    <h2>Hello " . htmlspecialchars($name) . ",</h2>
                    <p>You have requested to reset your password for the eQueue system.</p>
                    <p>Please click the button below to reset your password:</p>
                    <p style='text-align: center; margin: 30px 0;'>
                        <a href='" . htmlspecialchars($resetLink) . "' class='button'>Reset Password</a>
                    </p>
                    <p><strong>Important:</strong> This link will expire in 24 hours for security reasons.</p>
                    <p>If you did not request this password reset, please ignore this email. Your password will remain unchanged.</p>
                    <p>If the button above doesn't work, you can copy and paste this link into your browser:</p>
                    <p><a href='" . htmlspecialchars($resetLink) . "'>" . htmlspecialchars($resetLink) . "</a></p>
                </div>
                <div class='footer'>
                    <p>This is an automated message from the eQueue System. Please do not reply to this email.</p>
                    <p>&copy; " . date('Y') . " eQueue System. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>
        ";
    }

    public function getLastError() {
        return $this->lastError;
    }
}
?>
