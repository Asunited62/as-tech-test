<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize input data
    $firstName = htmlspecialchars(trim($_POST['firstName']));
    $lastName = htmlspecialchars(trim($_POST['lastName']));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $company = htmlspecialchars(trim($_POST['company']));
    $jobTitle = htmlspecialchars(trim($_POST['jobTitle']));
    $industry = htmlspecialchars(trim($_POST['industry']));
    $businessChallenge = htmlspecialchars(trim($_POST['businessChallenge']));
    $currentSolutions = htmlspecialchars(trim($_POST['currentSolutions']));
    $budget = htmlspecialchars(trim($_POST['budget']));
    $timeline = htmlspecialchars(trim($_POST['timeline']));
    $additionalInfo = htmlspecialchars(trim($_POST['additionalInfo']));
    
    // Validate required fields
    if (empty($firstName) || empty($lastName) || empty($email) || empty($phone)) {
        die("Please fill in all required fields.");
    }
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        die("Please enter a valid email address.");
    }
    
    // Email configuration
    $to = "as.united62@gmail.com";
    $subject = "New Consultation Request from " . $firstName . " " . $lastName;
    
    // Email body
    $message = "
    <html>
    <head>
        <title>New Consultation Request</title>
    </head>
    <body>
        <h2>New Consultation Request</h2>
        <p><strong>Personal Information:</strong></p>
        <ul>
            <li><strong>Name:</strong> $firstName $lastName</li>
            <li><strong>Email:</strong> $email</li>
            <li><strong>Phone:</strong> $phone</li>
        </ul>
        
        <p><strong>Business Information:</strong></p>
        <ul>
            <li><strong>Company:</strong> $company</li>
            <li><strong>Job Title:</strong> $jobTitle</li>
            <li><strong>Industry:</strong> $industry</li>
        </ul>
        
        <p><strong>Project Details:</strong></p>
        <ul>
            <li><strong>Business Challenge:</strong> $businessChallenge</li>
            <li><strong>Current Solutions:</strong> $currentSolutions</li>
            <li><strong>Budget Range:</strong> $budget</li>
            <li><strong>Timeline:</strong> $timeline</li>
        </ul>
        
        <p><strong>Additional Information:</strong></p>
        <p>$additionalInfo</p>
        
        <hr>
        <p><em>This message was sent from the consultation form on asunited.in</em></p>
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
    $mailSent = mail($to, $subject, $message, $headerString);
    
    if ($mailSent) {
        // Log success (optional)
        error_log("Consultation form email sent successfully to: $to");
        header("Location: thank-you.html");
        exit();
    } else {
        // Log the error for debugging
        $errorMsg = "Mail send failed. Error: " . error_get_last()['message'] ?? 'Unknown error';
        error_log("Consultation form error: $errorMsg");
        die("Sorry, there was an error sending your message. Please try again or contact us directly at as.united62@gmail.com");
    }
} else {
    // If not POST request, redirect to consultation page
    header("Location: consultation.html");
    exit();
}
?>