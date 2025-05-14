<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload for PHPMailer

// Load form data
$data = json_decode(file_get_contents("php://input"), true);

$mail = new PHPMailer(true);
try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'dainedvorak@gmail.com'; // Replace with your Gmail address
    $mail->Password = 'xszspbwsjtxpcvja';    // Replace with your app password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    $mail->setFrom('dainedvorak@gmail.com', 'Portfolio Site');
    $mail->addAddress('dainedvorak@gmail.com'); 

    $mail->isHTML(true);
    $mail->Subject = $data['subject'];
    $mail->Body = "
        <strong>From:</strong> {$data['first_name']} {$data['last_name']}<br>
        <strong>Email:</strong> {$data['email']}<br>
        <strong>Message:</strong><br>{$data['message']}
    ";

    $mail->send();

    // Save to JSON file
    $entryFile = 'form_submissions.json';
    $entries = file_exists($entryFile) ? json_decode(file_get_contents($entryFile), true) : [];
    $entries[] = $data;
    file_put_contents($entryFile, json_encode($entries, JSON_PRETTY_PRINT));

    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $mail->ErrorInfo]);
}
