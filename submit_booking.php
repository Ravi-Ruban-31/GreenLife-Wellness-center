<?php
session_start();
require_once 'connection.php';

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

// Get form data
$service = $_POST['service'];
$date = $_POST['date'];
$time = $_POST['time'];

// Get user ID from session (you need to store user_id in session on login)
$email = $_SESSION['email'];
$result = $conn->query("SELECT id FROM users WHERE email = '$email'");
$user = $result->fetch_assoc();
$user_id = $user['id'];

// Insert booking into database
$stmt = $conn->prepare("INSERT INTO appointments (user_id, service, date, time) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $service, $date, $time);

if ($stmt->execute()) {
    echo "<script>alert('Appointment booked successfully!'); window.location.href='user_page.php';</script>";
} else {
    echo "Error: " . $stmt->error;
}
?>
