<?php
require 'vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$logPath = __DIR__ . '/send_mail.log';
function log_to_file($msg) {
    global $logPath;
    file_put_contents($logPath, date('Y-m-d H:i:s') . ' ' . $msg . "\n", FILE_APPEND);
}

log_to_file('send_mail.php: Script started');

$mail = new PHPMailer(true);

$data = $_POST;
log_to_file('send_mail.php: Received POST data: ' . json_encode($data));
$response_code = 500;

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username   = 'dainedvorak@gmail.com'; // Replace with your Gmail address
    $mail->Password   = 'qlyfijvscfhbohbt';    // Replace with your app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    $mail->setFrom('your_email@gmail.com', 'Contact Form');
    $mail->addAddress('dainedvorak@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Form Submission: ' . $data['subject'];
    $mail->Body    = '<h2>Contact Submission</h2>'
                      . '<p><strong>First Name:</strong> ' . $data['firstName'] . '</p>'
                      . '<p><strong>Last Name:</strong> ' . $data['lastName'] . '</p>'
                      . '<p><strong>Email:</strong> ' . $data['email'] . '</p>'
                      . '<p><strong>Message:</strong> ' . nl2br($data['body']) . '</p>';

    $mail->send();
    log_to_file('send_mail.php: Email sent successfully');
    $response_code = 200;
} catch (Exception $e) {
    log_to_file('send_mail.php: Email sending failed: ' . $mail->ErrorInfo);
    $response_code = 500;
}

// Save to JSON file
$formData = [
    'timestamp' => date('Y-m-d H:i:s'),
    'firstName' => $data['firstName'],
    'lastName' => $data['lastName'],
    'email' => $data['email'],
    'subject' => $data['subject'],
    'body' => $data['body']
];

$jsonPath = __DIR__ . '/website-contact-form-submissions.json';
$existing = [];
if (file_exists($jsonPath)) {
    $jsonContent = file_get_contents($jsonPath);
    if ($jsonContent === false) {
        log_to_file('send_mail.php: Failed to read JSON file');
    } else {
        $existing = json_decode($jsonContent, true);
        if ($existing === null && json_last_error() !== JSON_ERROR_NONE) {
            log_to_file('send_mail.php: JSON decode error: ' . json_last_error_msg());
            $existing = [];
        }
    }
}
$existing[] = $formData;
if (file_put_contents($jsonPath, json_encode($existing, JSON_PRETTY_PRINT)) === false) {
    log_to_file('send_mail.php: Failed to write to JSON file');
} else {
    log_to_file('send_mail.php: Form data saved to JSON file');
}

log_to_file('send_mail.php: Returning response code ' . $response_code);
http_response_code($response_code);
?>
