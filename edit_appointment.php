<?php
session_start();
require_once 'connection.php';

// Only admins can access
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'Admin') {
    header("Location: index.php");
    exit();
}

// Get appointment ID
if (!isset($_GET['id'])) {
    echo "Invalid request.";
    exit();
}

$id = intval($_GET['id']);

// Fetch appointment details
$result = $conn->query("SELECT * FROM appointments WHERE id = $id");
if ($result->num_rows === 0) {
    echo "Appointment not found.";
    exit();
}
$appointment = $result->fetch_assoc();

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $conn->query("UPDATE appointments SET service='$service', appointment_date='$date', appointment_time='$time' WHERE id=$id");
    header("Location: admin_page.php");
    exit();
}
?>

<!DOCTYPE html>
<html>
<head>
  <title>Edit Appointment</title>
  <style>
    form {
      max-width: 500px;
      margin: 40px auto;
      padding: 20px;
      background: #f4f4f4;
      border-radius: 8px;
    }
    input, select {
      width: 100%;
      padding: 10px;
      margin-bottom: 15px;
    }
    button {
      background: #2c5f2d;
      color: white;
      padding: 10px;
      border: none;
      border-radius: 6px;
      cursor: pointer;
    }
  </style>
</head>
<body>

  <h2 style="text-align:center;">Edit Appointment #<?= $appointment['id']; ?></h2>
  <form method="POST">
    <label>Service:</label>
    <select name="service" required>
      <option value="Ayurvedic Therapy" <?= $appointment['service'] === 'Ayurvedic Therapy' ? 'selected' : '' ?>>Ayurvedic Therapy</option>
      <option value="Yoga & Meditation" <?= $appointment['service'] === 'Yoga & Meditation' ? 'selected' : '' ?>>Yoga & Meditation</option>
      <option value="Nutrition & Diet" <?= $appointment['service'] === 'Nutrition & Diet' ? 'selected' : '' ?>>Nutrition & Diet</option>
      <option value="Physiotherapy" <?= $appointment['service'] === 'Physiotherapy' ? 'selected' : '' ?>>Physiotherapy</option>
      <option value="Massage Therapy" <?= $appointment['service'] === 'Massage Therapy' ? 'selected' : '' ?>>Massage Therapy</option>
    </select>

    <label>Date:</label>
    <input type="date" name="date" value="<?= $appointment['appointment_date']; ?>" required>

    <label>Time:</label>
    <input type="time" name="time" value="<?= $appointment['appointment_time']; ?>" required>

    <button type="submit">Update Appointment</button>
  </form>
</body>
</html>
