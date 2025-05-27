<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Composer autoload for PHPMailer

// Define log file path
$logFile = '/var/www/dainedvorak.com/send_mail.log';

// Logging function
function logActivity($message) {
    global $logFile;
    $timestamp = date('[Y-m-d H:i:s]');
    $logMessage = $timestamp . ' ' . $message . PHP_EOL;
    file_put_contents($logFile, $logMessage, FILE_APPEND);
}

// Log start of email process
logActivity("Email process started");

// Load form data
$data = json_decode(file_get_contents("php://input"), true);
logActivity("Form data received: " . json_encode($data));

$mail = new PHPMailer(true);
try {
    logActivity("Configuring SMTP connection");
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'dainedvorak@gmail.com'; // your Gmail
    $mail->Password = 'qlyfijvscfhbohbt';     // your App Password
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    logActivity("Setting email content");
    $mail->setFrom('dainedvorak+portfolio@gmail.com', 'Portfolio Site');
    $mail->addAddress('dainedvorak@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = $data['subject'];
    $mail->Body = "
        <strong>From:</strong> {$data['first_name']} {$data['last_name']}<br>
        <strong>Email:</strong> {$data['email']}<br>
        <strong>Message:</strong><br>{$data['message']}
    ";

    logActivity("Attempting to send email from {$data['email']} with subject '{$data['subject']}'");
    $mail->send();
    logActivity("Email sent successfully");

    // Save to JSON file
    $entryFile = 'form_submissions.json';
    logActivity("Saving submission to $entryFile");
    $entries = file_exists($entryFile) ? json_decode(file_get_contents($entryFile), true) : [];
    $entries[] = $data;
    file_put_contents($entryFile, json_encode($entries, JSON_PRETTY_PRINT));
    logActivity("Submission saved successfully");

    echo json_encode(['status' => 'success']);
    logActivity("Success response returned to client");
} catch (Exception $e) {
    $errorMessage = $mail->ErrorInfo;
    logActivity("ERROR: Email sending failed - $errorMessage");
    echo json_encode(['status' => 'error', 'message' => $errorMessage]);
    logActivity("Error response returned to client");
}
logActivity("Email process completed");
?>
