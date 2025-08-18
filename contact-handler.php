<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $name = htmlspecialchars(trim($_POST['name']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject']));
    $message = htmlspecialchars(trim($_POST['message']));
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        die("Please fill in all required fields.");
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid email address.");
    }
    
    // Email configuration
    $to = "info@asunited.in";
    $emailSubject = "New Contact Form Message: " . $subject;
    
    // Email body
    $emailMessage = "
    <html>
    <head>
        <title>New Contact Form Message</title>
    </head>
    <body>
        <h2>New Contact Form Message</h2>
        <p><strong>From:</strong> $name</p>
        <p><strong>Email:</strong> $email</p>
        <p><strong>Subject:</strong> $subject</p>
        
        <p><strong>Message:</strong></p>
        <div style='background: #f5f5f5; padding: 15px; border-radius: 5px; margin: 10px 0;'>
            " . nl2br($message) . "
        </div>
        
        <hr>
        <p><em>This message was sent from the contact form on asunited.in</em></p>
    </body>
    </html>
    ";
    
    // Email headers - Use proper From address
    $headers = array(
        'MIME-Version' => '1.0',
        'Content-type' => 'text/html; charset=UTF-8',
        'From' => 'noreply@asunited.in',
        'Reply-To' => $email,
        'X-Mailer' => 'PHP/' . phpversion()
    );
    
    // Convert headers array to string
    $headerString = '';
    foreach($headers as $key => $value) {
        $headerString .= $key . ': ' . $value . "\r\n";
    }
    
    // Send email with error logging
    $mailSent = mail($to, $emailSubject, $emailMessage, $headerString);
    
    if ($mailSent) {
        // Log success (optional)
        error_log("Contact form email sent successfully to: $to");
        header("Location: thank-you.html");
        exit();
    } else {
        // Log the error for debugging
        $errorMsg = "Mail send failed. Error: " . error_get_last()['message'] ?? 'Unknown error';
        error_log("Contact form error: $errorMsg");
        die("Sorry, there was an error sending your message. Please try again or contact us directly at info@asunited.in");
    }
} else {
    // If not POST request, redirect to contact page
    header("Location: contact.html");
    exit();
}
?>