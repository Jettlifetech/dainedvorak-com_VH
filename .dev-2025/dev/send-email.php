

<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dainedvorak@gmail.com'; // Replace with your Gmail address
    $mail->Password   = 'xszspbwsjtxpcvja';    // Replace with your app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    // Recipients
    $mail->setFrom('dainedvorak@gmail.com', 'JETTLIFE');
    $mail->addAddress('daine@jettlifetech.com'); // Add recipient email

    // Content
    $mail->isHTML(true);
    $mail->Subject = 'Test Email from DVMS-v4 Ubuntu Server';
    $mail->Body    = 'This is a <b>test email</b> sent from Ubuntu server with PHP and Gmail.';
    $mail->AltBody = 'This is a test email sent from Ubuntu server with PHP and Gmail.';

    // Send email
    $mail->send();
    echo 'Message has been sent successfully';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}
?>
