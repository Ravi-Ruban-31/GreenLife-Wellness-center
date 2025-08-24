<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}
require_once 'connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Book Appointment | GreenLife Wellness</title>
  <style>
 body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: transparent;
      background: linear-gradient(to top,rgba(0,0,0,0.5)50%,rgba(0,0,0,0.5)50%),url(bgn.png);
    }
    .booking-page {
  max-width: 500px;
  margin: 60px auto;
  background: #ffffff;
  padding: 30px;
  border-radius: 10px;
  box-shadow: 0 2px 12px rgba(0,0,0,0.1);
}



.booking-page h1 {
  text-align: center;
  color: #2c5f2d;
  margin-bottom: 20px;
}

.booking-form {
  display: flex;
  flex-direction: column;
  gap: 15px;
}

.booking-form input,
.booking-form select {
  padding: 10px;
  border: 1px solid #ccc;
  border-radius: 5px;
}

.booking-form button {
  background: #2c5f2d;
  color: white;
  border: none;
  padding: 12px;
  border-radius: 5px;
  cursor: pointer;
}

.booking-form button:hover {
  background: #1e3e1e;
}

    </style>
</head>
<body>

  <div class="booking-page">
    <h2>Welcome, <?= $_SESSION['name']; ?> 👋</h2>
    <h1>Book Your Wellness Appointment</h1>

    <form action="submit_booking.php" method="POST" class="booking-form">
      <label for="service">Select Service:</label>
      <select name="service" id="service" required>
        <option value="">-- Choose a service --</option>
        <option value="Ayurvedic Therapy">Ayurvedic Therapy</option>
        <option value="Yoga & Meditation">Yoga & Meditation</option>
        <option value="Nutrition & Diet">Nutrition & Diet Consultation</option>
        <option value="Physiotherapy">Physiotherapy</option>
        <option value="Massage Therapy">Massage Therapy</option>
      </select>

      <label for="date">Preferred Date:</label>
      <input type="date" name="date" id="date" required>

      <label for="time">Preferred Time:</label>
      <input type="time" name="time" id="time" required>

      <button type="submit">Book Appointment</button>
    </form>

    <p><a href="home.html">Logout</a></p>
  </div>

</body>
</html>
