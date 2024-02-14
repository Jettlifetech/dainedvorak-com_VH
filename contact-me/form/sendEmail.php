<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

//require '/usr/share/php/libphp-phpmailer/PHPMailerAutoload.php';
require '/var/www/dainedvorak.com/contact-me/form/vendor/phpmailer/phpmailer/src/Exception.php';
require '/var/www/dainedvorak.com/contact-me/form/vendor/phpmailer/phpmailer/src/PHPMailer.php';
require '/var/www/dainedvorak.com/contact-me/form/vendor/phpmailer/phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

try {
    //Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dainedvorak@gmail.com';
    $mail->Password   = 'qeoe fadl ozzi qkll';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;

    //Recipients
    $mail->setFrom('dainedvorak@gmail.com', 'Mailer');
    $mail->addAddress('dainedvorak@gmail.com', 'Receiver Name');

    //Content
    $mail->isHTML(true);
    $mail->Subject = 'New Contact Form Submission';
    $mail->Body    = 'Name: ' . $_POST['name'] . '<br>Email: ' . $_POST['email'] . '<br>Message: ' . $_POST['message'];

    $mail->send();
    echo 'Message has been sent';
} catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}

