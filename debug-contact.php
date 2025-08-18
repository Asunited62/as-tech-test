<?php
// Debug version of contact handler - shows all errors
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h2>Contact Form Debug</h2>";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    echo "<p><strong>Form submitted via POST ✓</strong></p>";
    
    // Show all POST data
    echo "<p><strong>POST Data:</strong></p>";
    echo "<pre>" . print_r($_POST, true) . "</pre>";
    
    // Sanitize input data
    $name = htmlspecialchars(trim($_POST['name'] ?? ''));
    $email = filter_var(trim($_POST['email'] ?? ''), FILTER_SANITIZE_EMAIL);
    $subject = htmlspecialchars(trim($_POST['subject'] ?? ''));
    $message = htmlspecialchars(trim($_POST['message'] ?? ''));
    
    echo "<p><strong>Sanitized Data:</strong></p>";
    echo "<ul>";
    echo "<li>Name: '$name'</li>";
    echo "<li>Email: '$email'</li>";
    echo "<li>Subject: '$subject'</li>";
    echo "<li>Message: '$message'</li>";
    echo "</ul>";
    
    // Validate required fields
    if (empty($name) || empty($email) || empty($subject) || empty($message)) {
        echo "<p style='color: red;'><strong>❌ Validation Error:</strong> Missing required fields.</p>";
        exit;
    }
    echo "<p style='color: green;'><strong>✓ All required fields present</strong></p>";
    
    // Validate email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "<p style='color: red;'><strong>❌ Email validation failed</strong></p>";
        exit;
    }
    echo "<p style='color: green;'><strong>✓ Email validation passed</strong></p>";
    
    // Check if mail function exists
    if (!function_exists('mail')) {
        echo "<p style='color: red;'><strong>❌ PHP mail() function not available</strong></p>";
        exit;
    }
    echo "<p style='color: green;'><strong>✓ PHP mail() function available</strong></p>";
    
    // Email configuration
    $to = "info@asunited.in";
    $emailSubject = "Debug Test: " . $subject;
    
    // Simple email body for testing
    $emailMessage = "Test email from debug script.\n\nName: $name\nEmail: $email\nSubject: $subject\nMessage: $message";
    
    // Simple headers
    $headers = "From: noreply@asunited.in\r\n";
    $headers .= "Reply-To: $email\r\n";
    $headers .= "X-Mailer: PHP/" . phpversion();
    
    echo "<p><strong>Email Details:</strong></p>";
    echo "<ul>";
    echo "<li>To: $to</li>";
    echo "<li>Subject: $emailSubject</li>";
    echo "<li>Headers: " . str_replace("\r\n", " | ", $headers) . "</li>";
    echo "</ul>";
    
    // Attempt to send email
    echo "<p><strong>Attempting to send email...</strong></p>";
    
    $mailResult = mail($to, $emailSubject, $emailMessage, $headers);
    
    if ($mailResult) {
        echo "<p style='color: green; font-size: 18px;'><strong>✅ EMAIL SENT SUCCESSFULLY!</strong></p>";
        echo "<p>Check the inbox at: $to</p>";
    } else {
        echo "<p style='color: red; font-size: 18px;'><strong>❌ EMAIL SEND FAILED!</strong></p>";
        
        // Get last error
        $lastError = error_get_last();
        if ($lastError) {
            echo "<p><strong>Last PHP Error:</strong></p>";
            echo "<pre>" . print_r($lastError, true) . "</pre>";
        }
        
        // Additional debugging info
        echo "<p><strong>Server Info:</strong></p>";
        echo "<ul>";
        echo "<li>PHP Version: " . phpversion() . "</li>";
        echo "<li>Server: " . ($_SERVER['SERVER_SOFTWARE'] ?? 'Unknown') . "</li>";
        echo "<li>Mail function: " . (function_exists('mail') ? 'Available' : 'Not available') . "</li>";
        echo "</ul>";
    }
    
} else {
    echo "<p style='color: orange;'><strong>⚠ No POST data received</strong></p>";
    echo "<p>Make sure to submit the form using POST method.</p>";
    
    echo "<h3>Quick Test Form:</h3>";
    echo '<form method="POST" action="">
        <p>Name: <input type="text" name="name" value="Test User" required></p>
        <p>Email: <input type="email" name="email" value="test@example.com" required></p>
        <p>Subject: <input type="text" name="subject" value="Debug Test" required></p>
        <p>Message: <textarea name="message" required>This is a debug test message.</textarea></p>
        <p><input type="submit" value="Send Test Email"></p>
    </form>';
}
?>