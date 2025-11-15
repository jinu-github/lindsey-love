# TODO: Switch Forgot Password to PHPMailer with Google App Password

- [x] Update config/config.php to use Gmail SMTP settings (smtp.gmail.com, port 587)
- [x] Modify app/services/EmailService.php to use PHPMailer instead of Mailtrap API
- [x] Fix StaffController.php forgot_password action to properly send emails in production and handle development mode
- [x] Test the forgot password functionality with Gmail SMTP
