<?php
include 'db.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Collect and sanitize input data
    $name = htmlspecialchars(strip_tags(trim($_POST['name'])));
    $email = filter_var(trim($_POST['email']), FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars(strip_tags(trim($_POST['message'])));

    // Validate data
    if (empty($name) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "Invalid input. Please go back and try again.";
        exit;
    }

    // Email configuration
    // Note: For this to work on XAMPP localhost, you need to configure sendmail in php.ini
    // or upload this to a live server.
    $to = "info@ankurropvatika.com"; // Replace with your actual email address
    $subject = "New Contact Message from $name";
    $email_content = "Name: $name\n";
    $email_content .= "Email: $email\n\n";
    $email_content .= "Message:\n$message\n";
    $headers = "From: $email";

    // Save to Database
    $stmt = $conn->prepare("INSERT INTO messages (name, email, message) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $message);
    $db_saved = $stmt->execute();
    $stmt->close();

    // Send email
    // NOTE: On localhost, mail() often fails without SMTP config. 
    // We add file logging here so you can verify it works locally.
    $log_entry = "Time: " . date("Y-m-d H:i:s") . "\n" . $email_content . "-------------------------\n";
    $saved_to_file = file_put_contents("messages.txt", $log_entry, FILE_APPEND);

    if ($db_saved || $saved_to_file || mail($to, $subject, $email_content, $headers)) {
        echo '<script>alert("Thank you! Your message has been sent."); window.location.href="index.php";</script>';
    } else {
        echo '<script>alert("Oops! Something went wrong and we couldn\'t send your message."); window.location.href="index.php";</script>';
    }
} else {
    header("Location: index.php");
    exit;
}
?>