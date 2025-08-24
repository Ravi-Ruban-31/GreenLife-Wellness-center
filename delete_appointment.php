<?php
session_start();
require_once 'connection.php';

// Only admins can delete
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

if (!isset($_GET['id'])) {
    echo "No appointment ID specified.";
    exit();
}

$id = intval($_GET['id']);

// Delete the appointment
$conn->query("DELETE FROM appointments WHERE id = $id");

// Redirect back to admin dashboard
header("Location: admin_page.php");
exit();
?>
