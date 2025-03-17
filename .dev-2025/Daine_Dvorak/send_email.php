<?php
// Use PHPMailer classes
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Include PHPMailer library files (ensure these paths match your setup)
require 'PHPMailer/src/Exception.php';
require 'PHPMailer/src/PHPMailer.php';
require 'PHPMailer/src/SMTP.php';

// Retrieve and sanitize form data
$fullName = isset($_POST['fullName']) ? trim($_POST['fullName']) : '';
$email    = isset($_POST['email']) ? trim($_POST['email']) : '';
$subject  = isset($_POST['subject']) ? trim($_POST['subject']) : '';
$message  = isset($_POST['message']) ? trim($_POST['message']) : '';

if(empty($fullName) || empty($email) || empty($subject) || empty($message)) {
    echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
    exit;
}

$mail = new PHPMailer(true);

try {
    // SMTP configuration
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dainedvorak@gmail.com';
    $mail->Password   = 'xszspbwsjtxpcvja'; // Use your email app password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    
    // Set sender details
    $mail->setFrom('dainedvorak@gmail.com', 'Daine Dvorak dba JETTLIFE Tech');
    
    // Main recipient and copy to user
    $mail->addAddress('dainedvorak@gmail.com');
    $mail->addCC($email, $fullName);

    // Email content
    $mail->isHTML(true);
    $mail->Subject = $subject;
    $mail->Body    = "<p><strong>Name:</strong> {$fullName}</p>
                      <p><strong>Email:</strong> {$email}</p>
                      <p><strong>Message:</strong><br>{$message}</p>";
    $mail->AltBody = "Name: {$fullName}\nEmail: {$email}\nMessage: {$message}";

    $mail->send();
    echo json_encode(['status' => 'success']);
} catch (Exception $e) {
    echo json_encode([
      'status' => 'error',
      'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}"
    ]);
}
?>
