<?php
// Enable error reporting for debugging (remove or adjust for production)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Ensure the vendor autoload exists
if (!file_exists(__DIR__ . '/vendor/autoload.php')) { // Changed to use __DIR__ for reliable path
    http_response_code(500);
    // Log this critical error before exiting
    error_log("send_mail.php: PHPMailer autoload not found at " . __DIR__ . "/vendor/autoload.php", 3, __DIR__ . '/send_mail.log');
    echo json_encode(['status' => 'error', 'message' => 'Server configuration error: PHPMailer library not found. Please check server logs.']);
    exit;
}
require __DIR__ . '/vendor/autoload.php'; // Changed to use __DIR__

// --- Configuration ---
$logFile = __DIR__ . '/send_mail.log'; // Centralized log file
$submissionsFile = __DIR__ . '/form_submissions.json'; // Submissions JSON file

// --- Error and Exception Handling ---
function log_error_custom($level, $message, $file, $line) { // Renamed to avoid conflict if a global one exists
    global $logFile;
    // Ensure log directory is writable, though __DIR__ should be.
    $logMessage = sprintf("[%s] %s: %s in %s on line %d\n", date('Y-m-d H:i:s'), $level, $message, $file, $line);
    error_log($logMessage, 3, $logFile);
}

set_error_handler(function ($errno, $errstr, $errfile, $errline) {
    // Ignore E_DEPRECATED and E_STRICT if they are too noisy for logs, or handle as needed
    if (!(error_reporting() & $errno)) {
        // This error code is not included in error_reporting
        return false;
    }
    log_error_custom("ERROR", "$errstr (Code: $errno)", $errfile, $errline);
    // Don't execute PHP internal error handler for handled errors if it causes duplicate logging or unwanted output
    return true; 
});

set_exception_handler(function ($exception) {
    global $logFile; // Ensure $logFile is accessible
    log_error_custom("EXCEPTION", $exception->getMessage(), $exception->getFile(), $exception->getLine());
    if (!headers_sent()) {
        http_response_code(500);
        echo json_encode(['status' => 'error', 'message' => 'An unexpected error occurred. Please try again later. Details logged.']);
    } else {
        // Headers already sent, perhaps log that this happened too.
        error_log("send_mail.php: Exception occurred after headers sent.", 3, $logFile);
    }
});

// --- Request Processing ---
if (!headers_sent()) {
    header('Content-Type: application/json');
}

// Load and decode input data
$inputJSON = file_get_contents("php://input");
$data = json_decode($inputJSON, true);

// Check if JSON decoding was successful
if (json_last_error() !== JSON_ERROR_NONE && !empty($inputJSON)) {
    log_error_custom("USER_ERROR", "Invalid JSON received: " . json_last_error_msg() . ". Raw input: " . substr($inputJSON, 0, 500), __FILE__, __LINE__);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => 'Invalid data format. Please ensure you are sending valid JSON.']);
    exit;
}

// If $data is null (e.g. empty input or decoding failed for empty string), initialize to empty array
$data = $data ?? [];

// --- Data Extraction and Validation ---
// Handle potential variations in key names (e.g., firstName vs first_name) and ensure they exist.
$firstName = trim($data['firstName'] ?? $data['first_name'] ?? '');
$lastName = trim($data['lastName'] ?? $data['last_name'] ?? '');
$email = filter_var(trim($data['email'] ?? ''), FILTER_SANITIZE_EMAIL);
$subject = trim($data['subject'] ?? 'No Subject Provided');
// JS sends 'body', PHP previously used 'message'. This handles both, preferring 'body'.
$bodyContent = trim($data['body'] ?? $data['message'] ?? '');


// Basic validation
$errors = [];
if (empty($firstName)) {
    $errors[] = "First name is required.";
}
if (empty($lastName)) {
    $errors[] = "Last name is required.";
}
if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "A valid email address is required.";
}
// Subject has a default, so it won't be empty unless the default is cleared.
// if (empty($subject)) { $errors[] = "Subject is required."; } 
if (empty($bodyContent)) {
    $errors[] = "Message body is required.";
}

if (!empty($errors)) {
    log_error_custom("VALIDATION_ERROR", "Form validation failed: " . implode(", ", $errors) . " Data: " . json_encode($data), __FILE__, __LINE__);
    http_response_code(400);
    echo json_encode(['status' => 'error', 'message' => implode(" ", $errors), 'errors' => $errors]);
    exit;
}

$fullName = trim($firstName . ' ' . $lastName);

// --- PHPMailer Setup and Sending ---
$mail = new PHPMailer(true);
try {
    // Server settings
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'dainedvorak@gmail.com'; // Your Gmail address
    $mail->Password   = 'xszspbwsjtxpcvja';    // Your Gmail app password (ensure this is an App Password)
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // Explicitly use constant
    $mail->Port       = 587;
    $mail->CharSet    = PHPMailer::CHARSET_UTF8; // Set CharSet to UTF-8

    // Recipients
    $mail->setFrom('dainedvorak@gmail.com', 'Portfolio Contact Form'); // Sender email and name (shown in "from" field)
    $mail->addAddress('dainedvorak@gmail.com', 'Daine Dvorak');     // Recipient email and name
    $mail->addReplyTo($email, $fullName); // Set Reply-To to the form submitter's email and name

    // Content
    $mail->isHTML(true);
    $mail->Subject = "Contact Form: " . htmlspecialchars($subject); // Sanitize subject
    
    $emailBodyHTML = "
        <p>You have received a new message from your portfolio contact form:</p>
        <table border='1' cellpadding='5' cellspacing='0' style='border-collapse: collapse; font-family: Arial, sans-serif;'>
            <tr style='background-color: #f2f2f2;'><th align='left' style='padding: 8px;'>Full Name:</th><td style='padding: 8px;'>" . htmlspecialchars($fullName) . "</td></tr>
            <tr><th align='left' style='padding: 8px;'>Email:</th><td style='padding: 8px;'><a href='mailto:" . htmlspecialchars($email) . "'>" . htmlspecialchars($email) . "</a></td></tr>
            <tr style='background-color: #f2f2f2;'><th align='left' style='padding: 8px;'>Subject:</th><td style='padding: 8px;'>" . htmlspecialchars($subject) . "</td></tr>
            <tr><th align='left' style='padding: 8px; vertical-align: top;'>Message:</th><td style='padding: 8px;'>" . nl2br(htmlspecialchars($bodyContent)) . "</td></tr>
        </table>
        <hr>
        <p style='font-size: 0.9em; color: #555;'><em>This email was sent on " . date('Y-m-d H:i:s') . " from the contact form on dainedvorak.com</em></p>
    ";
    $mail->Body = $emailBodyHTML;
    
    $emailBodyText = "You have received a new message from your portfolio contact form:

" .
                     "Full Name: " . htmlspecialchars($fullName) . "
" .
                     "Email: " . htmlspecialchars($email) . "
" .
                     "Subject: " . htmlspecialchars($subject) . "

" .
                     "Message:
" . htmlspecialchars($bodyContent) . "

" .
                     "------------------------------------------------------
" .
                     "This email was sent on " . date('Y-m-d H:i:s') . " from the contact form on dainedvorak.com";
    $mail->AltBody = $emailBodyText;

    $mail->send();

    // --- Save to JSON file ---
    $submissionData = [
        'timestamp' => date('Y-m-d H:i:s'),
        'firstName' => $firstName,
        'lastName' => $lastName,
        'email' => $email,
        'subject' => $subject,
        'body' => $bodyContent, // Use the processed body content
        'status' => 'success',
        'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? 'Unknown' // Optionally log IP
    ];

    $entries = [];
    if (file_exists($submissionsFile)) {
        $fileContent = file_get_contents($submissionsFile);
        if ($fileContent !== false && !empty($fileContent)) { // Check if content is not empty
            $entries = json_decode($fileContent, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                log_error_custom("FILE_ERROR", "Could not decode JSON from $submissionsFile. Error: " . json_last_error_msg() . ". File content (first 500 chars): " . substr($fileContent, 0, 500), __FILE__, __LINE__);
                $entries = []; // Reset to prevent further issues with corrupt file
            }
        } elseif ($fileContent === false) {
            log_error_custom("FILE_ERROR", "Could not read $submissionsFile.", __FILE__, __LINE__);
        }
        // If fileContent is empty, $entries remains [] which is fine.
    }
    $entries[] = $submissionData; // Add new entry
    
    // Attempt to write to file
    if (file_put_contents($submissionsFile, json_encode($entries, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES), LOCK_EX) === false) {
        log_error_custom("FILE_ERROR", "Could not write to $submissionsFile. Check permissions and disk space.", __FILE__, __LINE__);
        // Don't fail the whole request if only JSON saving fails, email was sent.
        // But maybe notify admin or return a slightly different success. For now, just log.
        echo json_encode(['status' => 'success', 'message' => 'Message sent successfully, but could not log submission.']);
        exit;
    }

    echo json_encode(['status' => 'success', 'message' => 'Message sent successfully and logged!']);

} catch (Exception $e) {
    $detailedError = "Mailer Error: " . ($mail->ErrorInfo ?? 'N/A') . ". Exception: " . $e->getMessage() . " in " . $e->getFile() . " on line " . $e->getLine();
    log_error_custom("MAIL_ERROR", $detailedError, __FILE__, __LINE__); // Log to custom log
    
    // Provide a generic error to the client but log details
    http_response_code(500); // Internal Server Error
    echo json_encode(['status' => 'error', 'message' => 'Message could not be sent due to a server error. Please try again later. Administrators have been notified.']);
}

?>
