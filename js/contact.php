<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = htmlspecialchars(trim($_POST["name"]));
    $email = htmlspecialchars(trim($_POST["email"]));
    $message = htmlspecialchars(trim($_POST["message"]));

    $to = "timnicometo@sandpiper-productions.com";
    $subject = "New Sandpiper Productions Contact Form Message";

    $body = "You received a new message from your website.\n\n";
    $body .= "Name: " . $name . "\n";
    $body .= "Email: " . $email . "\n\n";
    $body .= "Message:\n" . $message . "\n";

    $headers = "From: Sandpiper Productions <timnicometo@sandpiper-productions.com>\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";

    mail($to, $subject, $body, $headers);

    $replySubject = "Thank you for contacting Sandpiper Productions";
    $replyBody = "Hi " . $name . ",\n\n";
    $replyBody .= "Thank you for reaching out to Sandpiper Productions.\n\n";
    $replyBody .= "I’ve received your message and will get back to you as soon as I can.\n\n";
    $replyBody .= "Best,\n";
    $replyBody .= "Sandpiper Productions";

    $replyHeaders = "From: Sandpiper Productions <timnicometo@sandpiper-productions.com>\r\n";
    $replyHeaders .= "Reply-To: timnicometo@sandpiper-productions.com\r\n";

    mail($email, $replySubject, $replyBody, $replyHeaders);

    header("Location: thank-you.html");
    exit();
}
?>
