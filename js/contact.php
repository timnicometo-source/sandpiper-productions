<?php
declare(strict_types=1);

// CHANGE THIS PATH to the real location of your secret config file
require_once dirname(__DIR__) . '/mail-config.php';

require_once __DIR__ . '/phpmailer/src/Exception.php';
require_once __DIR__ . '/phpmailer/src/PHPMailer.php';
require_once __DIR__ . '/phpmailer/src/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: contact.html');
    exit;
}

function clean_input(string $value): string {
    return trim(strip_tags($value));
}

function redirect_error(string $message = 'Unable to send message.'): void {
    echo '<!DOCTYPE html>
    <html lang="en">
    <head>
      <meta charset="UTF-8">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <title>Contact Error</title>
      <link rel="stylesheet" href="css/style.css?v=4">
    </head>
    <body>
      <main class="inner-page">
        <section class="page-section">
          <div class="container narrow">
            <div class="contact-form">
              <h1>Something went wrong</h1>
              <p>' . htmlspecialchars($message, ENT_QUOTES, 'UTF-8') . '</p>
              <p><a class="btn" href="contact.html">Back to Contact Page</a></p>
            </div>
          </div>
        </section>
      </main>
    </body>
    </html>';
    exit;
}

// Honeypot check
if (!empty($_POST['website'] ?? '')) {
    header('Location: thank-you.html');
    exit;
}

$name = clean_input($_POST['name'] ?? '');
$email = clean_input($_POST['email'] ?? '');
$phone = clean_input($_POST['phone'] ?? '');
$company = clean_input($_POST['company'] ?? '');
$projectType = clean_input($_POST['project_type'] ?? '');
$contactMethod = clean_input($_POST['contact_method'] ?? '');
$message = trim($_POST['message'] ?? '');

$allowedProjectTypes = [
    'Website Design',
    'Website Management',
    'Drone Media',
    'Video Production',
    'Photography',
    'Creative Consulting',
    'Other'
];

$allowedContactMethods = [
    'Email',
    'Phone',
    'Either'
];

if (
    $name === '' ||
    $email === '' ||
    $projectType === '' ||
    $contactMethod === '' ||
    $message === ''
) {
    redirect_error('Please complete all required fields.');
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    redirect_error('Please enter a valid email address.');
}

if (!in_array($projectType, $allowedProjectTypes, true)) {
    redirect_error('Invalid project type selected.');
}

if (!in_array($contactMethod, $allowedContactMethods, true)) {
    redirect_error('Invalid contact method selected.');
}

if (mb_strlen($message) > 5000) {
    redirect_error('Message is too long.');
}

$phoneDisplay = $phone !== '' ? $phone : 'Not provided';
$companyDisplay = $company !== '' ? $company : 'Not provided';

$adminBody = "
New contact form submission from SandpiperProductions.com

Name: {$name}
Email: {$email}
Phone: {$phoneDisplay}
Company / Organization: {$companyDisplay}
Project Type: {$projectType}
Preferred Contact Method: {$contactMethod}

Message:
{$message}
";

$userBody = "
Hi {$name},

Thanks for reaching out to Sandpiper Productions.

Your message has been received, and I’ll get back to you soon.

Here’s a copy of what you submitted:

Project Type: {$projectType}
Preferred Contact Method: {$contactMethod}
Phone: {$phoneDisplay}
Company / Organization: {$companyDisplay}

Message:
{$message}

Best,
Sandpiper Productions
";

try {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->Host = SMTP_HOST;
    $mail->SMTPAuth = true;
    $mail->Username = SMTP_USER;
    $mail->Password = SMTP_PASS;
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port = SMTP_PORT;

    $mail->CharSet = 'UTF-8';

    // Notification to you
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress(CONTACT_TO_EMAIL, CONTACT_TO_NAME);
    $mail->addReplyTo($email, $name);
    $mail->Subject = 'New Sandpiper Contact Form Submission';
    $mail->Body = $adminBody;
    $mail->send();

    // Auto-reply to visitor
    $mail->clearAddresses();
    $mail->clearReplyTos();
    $mail->setFrom(SMTP_FROM_EMAIL, SMTP_FROM_NAME);
    $mail->addAddress($email, $name);
    $mail->addReplyTo(CONTACT_TO_EMAIL, CONTACT_TO_NAME);
    $mail->Subject = 'Thanks for contacting Sandpiper Productions';
    $mail->Body = $userBody;
    $mail->send();

    header('Location: thank-you.html');
    exit;

} catch (Exception $e) {
    error_log('Contact form mailer error: ' . $e->getMessage());
    redirect_error('Your message could not be sent right now. Please try again later.');
}