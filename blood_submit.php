<?php
header('Content-Type: application/json');

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'email_config.php';
require 'PHPMailer/PHPMailer.php';
require 'PHPMailer/SMTP.php';
require 'PHPMailer/Exception.php';


// Database connection
$conn = new mysqli("localhost", "root", "", "blood_donation");

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit;
}

// Sanitize and collect POST data
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$blood_type = $_POST['blood_type'] ?? '';
$last_donation = $_POST['last_donation'] ?? '';
$location = $_POST['location'] ?? '';
$medications = $_POST['medications'] ?? '';
$allergies = $_POST['allergies'] ?? '';

// Save to database
$stmt = $conn->prepare("INSERT INTO donors (name, email, phone, blood_type, last_donation, location, medications, allergies) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssssss", $name, $email, $phone, $blood_type, $last_donation, $location, $medications, $allergies);

if ($stmt->execute()) {
    // Send Email
    $mail = new PHPMailer(true);

    try {
        $mail->isSMTP();
        $mail->Host       = SMTP_HOST;
        $mail->SMTPAuth   = true;
        $mail->Username   = SMTP_USERNAME;
        $mail->Password   = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port       = SMTP_PORT;

        $mail->setFrom(SMTP_FROM_EMAIL, 'LifeSaver Connect');
        $mail->addAddress($email, $name);
        $mail->addAddress('admin@example.com'); // Replace with your admin email

        $mail->isHTML(true);
        $mail->Subject = "Blood Donation Registration Confirmation";
        $mail->Body    = "
            <h2>Thank You for Registering, $name!</h2>
            <p>Your blood donation details:</p>
            <ul>
                <li><strong>Phone:</strong> $phone</li>
                <li><strong>Blood Type:</strong> $blood_type</li>
                <li><strong>Last Donation:</strong> $last_donation</li>
                <li><strong>Preferred Location:</strong> $location</li>
                <li><strong>Medications:</strong> $medications</li>
                <li><strong>Allergies:</strong> $allergies</li>
            </ul>
            <p>We’ll contact you soon to schedule your appointment.</p>
        ";

        $mail->send();
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Email could not be sent.']);
        exit;
    }

    echo json_encode(['success' => true, 'message' => 'Thank you! Your registration has been received.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save data.']);
}

$stmt->close();
$conn->close();
?>
