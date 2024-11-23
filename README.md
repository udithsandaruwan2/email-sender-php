
# Email Sender with PHPMailer and Dotenv

A simple PHP project to send emails using PHPMailer while securely managing credentials with dotenv.

## Prerequisites

Before you begin, make sure you have the following installed:
- PHP (version 7.4 or higher)
- Composer (dependency manager for PHP)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/your-username/your-repo-name.git
   cd your-repo-name
   ```

2. Install dependencies:
   ```bash
   composer require phpmailer/phpmailer
   composer require vlucas/phpdotenv
   ```

3. Create a `.env` file in the root directory of the project:
   ```bash
   touch .env
   ```

4. Add your email credentials to the `.env` file:
   ```env
   EMAIL='your_email_here'
   APP_PASSWORD='your_email_app_password_here'
   ```

   > **Note:** Replace `your_email_here` with your email address and `your_email_app_password_here` with your email app password.

## Usage

1. Make sure the `.env` file is properly configured with your email credentials.

2. Run the PHP script:
   ```bash
   php your-script-name.php
   ```

## Example Script

Here is a basic example of how to send an email using PHPMailer and dotenv:

```php
<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

// Load environment variables from .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$email = $_ENV['EMAIL'];
$appPassword = $_ENV['APP_PASSWORD'];

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Replace with your email provider's SMTP server
    $mail->SMTPAuth = true;
    $mail->Username = $email;
    $mail->Password = $appPassword;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = 465;

    // Recipients
    $mail->setFrom($email, 'Your Name');
    $mail->addAddress('recipient@example.com', 'Recipient Name'); // Replace with the recipient's email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email';
    $mail->Body    = 'This is a test email sent using PHPMailer and dotenv.';

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
```

## License

This project is licensed under the [MIT License](LICENSE).

---

Happy coding! 🎉
