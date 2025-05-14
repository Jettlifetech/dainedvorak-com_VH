<?php
$data = $_POST;

$to = "dainedvorak@gmail.com";
$subject = "Website Form Submission: " . $data['subject'];
$headers = "MIME-Version: 1.0" . "\r\n";
$headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
$headers .= "From: " . $data['email'] . "\r\n";

$message = "
<html>
<head><title>Contact Submission</title></head>
<body>
  <h2>Contact Form Submission</h2>
  <p><strong>First Name:</strong> {$data['firstName']}</p>
  <p><strong>Last Name:</strong> {$data['lastName']}</p>
  <p><strong>Email:</strong> {$data['email']}</p>
  <p><strong>Subject:</strong> {$data['subject']}</p>
  <p><strong>Message:</strong><br>{$data['body']}</p>
</body>
</html>
";

$mailSuccess = mail($to, $subject, $message, $headers);

// Save to JSON
$jsonPath = __DIR__ . '/website-contact-form-submissions.json';
$formData = [
  'timestamp' => date('Y-m-d H:i:s'),
  'firstName' => $data['firstName'],
  'lastName' => $data['lastName'],
  'email' => $data['email'],
  'subject' => $data['subject'],
  'body' => $data['body']
];

if (!file_exists($jsonPath)) {
  file_put_contents($jsonPath, json_encode([$formData], JSON_PRETTY_PRINT));
} else {
  $existing = json_decode(file_get_contents($jsonPath), true);
  $existing[] = $formData;
  file_put_contents($jsonPath, json_encode($existing, JSON_PRETTY_PRINT));
}

if ($mailSuccess) {
  http_response_code(200);
} else {
  http_response_code(500);
}
?>
