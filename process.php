<?php
require 'vendor/autoload.php';
require __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\SMTP;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate form inputs
    $name = htmlspecialchars($_POST['name']);
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $message = htmlspecialchars($_POST['message']);

    // Validate file upload
    if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['file']['tmp_name'];
        $fileName = $_FILES['file']['name'];
        $fileSize = $_FILES['file']['size'];
        $fileType = $_FILES['file']['type'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Allowed file types
        $allowedExtensions = ['jpg', 'png', 'pdf', 'docx'];

        if (in_array($fileExtension, $allowedExtensions) && $fileSize <= 5 * 1024 * 1024) { // 5 MB limit
            // Save file to server
            $uploadFileDir = './uploads/';
            if (!is_dir($uploadFileDir)) {
                mkdir($uploadFileDir, 0755, true);
            }

            $destPath = $uploadFileDir . $fileName;
            if (move_uploaded_file($fileTmpPath, $destPath)) {
                // Send email using PHPMailer
                $mail = new PHPMailer(true);

                try {
                    // Server settings
                    $mail->isSMTP();
                    $mail->Host = 'smtp.gmail.com'; // Replace with your SMTP server
                    $mail->SMTPAuth = true;
                    $mail->Username = $_ENV['EMAIL']; // Your Gmail address
                    $mail->Password = $_ENV['APP_PASSWORD']; // Your Gmail app password
                    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                    $mail->Port = 587;

                    // Recipients
                    $mail->setFrom('developer.udithsandaruwan@gmail.com', 'Form Submission');
                    $mail->addAddress($email); // Replace with the recipient's email
                    $mail->addReplyTo($email, $name);

                    // Attachments
                    $mail->addAttachment($destPath, $fileName);

                    // Content
                    $mail->isHTML(true);
                    $mail->Subject = 'New Media Upload Form Submission';
                    $mail->Body = "<p><strong>Name:</strong> $name</p>
                                   <p><strong>Email:</strong> $email</p>
                                   <p><strong>Message:</strong></p>
                                   <p>$message</p>";

                    $mail->send();
                    header("Location: success.php"); // Replace 'success.php' with your desired page
                } catch (Exception $e) {
                    echo "Failed to send email. Mailer Error: {$mail->ErrorInfo}";
                }
            } else {
                echo "File upload failed.";
            }
        } else {
            echo "Invalid file type or size. Allowed types: " . implode(', ', $allowedExtensions);
        }
    } else {
        echo "No file uploaded or upload error.";
    }
} else {
    echo "Invalid request.";
}
