<?php
// Email test page
$to      = getenv('MAIL_TO') ?: 'test@example.com';
$subject = 'Test Email from WordPress Docker';
$message = 'Hello! This is a test email sent from the Docker development environment.';
$headers = [
    'From: noreply@example.com',
    'Reply-To: noreply@example.com',
    'X-Mailer: PHP/' . phpversion(),
];

$result = mail($to, $subject, $message, implode("\r\n", $headers));

if ($result) {
    echo "Email sent successfully to: " . htmlspecialchars($to);
} else {
    echo "Failed to send email.";
}