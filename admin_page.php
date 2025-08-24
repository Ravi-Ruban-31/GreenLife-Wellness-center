
<?php
session_start();
require_once 'connection.php';

// Only allow access if user is admin
if (!isset($_SESSION['email']) || $_SESSION['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}


$filterService = $_GET['service'] ?? '';
$filterQuery = "";

if (!empty($filterService)) {
    $filterQuery = "WHERE appointments.service = '" . $conn->real_escape_string($filterService) . "'";
}

// Fetch all users
$users = $conn->query("SELECT * FROM users");

// Fetch all appointments with user details
$appointments = $conn->query("SELECT appointments.*, users.name, users.email FROM appointments JOIN users ON appointments.user_id = users.id $filterQuery ORDER BY date, time");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - GreenLife Wellness</title>
    <style>
        body {
      margin: 0;
      font-family: 'Segoe UI', sans-serif;
      background: transparent;
      background: linear-gradient(to top,rgba(0,0,0,0.5)50%,rgba(0,0,0,0.5)50%),url(bgn.png);
    }
        h1 {
            text-align: center;
            color:rgb(251, 253, 251);
        }

          h2 {
            text-align: center;
            color:rgb(251, 253, 251);
        }
        table {
            background-color: #ccc;
            width: 100%;
            border-collapse: collapse;
            margin-top: 30px;
        }
        th, td {
            
            border: 1px solid #ccc;
            padding: 10px;
            text-align: left;
        }
        th {
            background-color: #d0e8d0;
            color: #2c5f2d;
        }
        a {
            color:rgb(236, 107, 107);
            
            text-decoration: wavy;
            margin-right: 10px;
        }
        a:hover {
            text-decoration: underline;
            background-color: #ccc;
        }
        form.filter-form {
            margin: 20px auto;
            text-align: center;
        }
        select {
            padding: 8px;
            font-size: 14px;
        }
        button.filter-button {
            padding: 8px 12px;
            background-color:rgb(160, 249, 160);
            color: black;
            border: none;
            border-radius: 4px;
            cursor: pointer;
        }
        form {
            color:rgb(251, 253, 251);
        }
       
    </style>
</head>
<body>
    <h1>Admin Dashboard</h1>

    <h2>Registered Users</h2>
    <table>
        <tr><th>ID</th><th>Name</th><th>Email</th><th>Role</th></tr>
        <?php while ($user = $users->fetch_assoc()): ?>
        <tr>
            <td><?= $user['id'] ?></td>
            <td><?= $user['name'] ?></td>
            <td><?= $user['email'] ?></td>
            <td><?= $user['role'] ?></td>
        </tr>
        <?php endwhile; ?>
    </table>

    <h2>Appointments</h2>
    <form method="GET" class="filter-form">
        <label for="service">Filter by Service:</label>
        <select name="service" id="service">
            <option value="">All</option>
            <option value="Ayurvedic Therapy" <?= $filterService == 'Ayurvedic Therapy' ? 'selected' : '' ?>>Ayurvedic Therapy</option>
            <option value="Yoga & Meditation" <?= $filterService == 'Yoga & Meditation' ? 'selected' : '' ?>>Yoga & Meditation</option>
            <option value="Nutrition & Diet" <?= $filterService == 'Nutrition & Diet' ? 'selected' : '' ?>>Nutrition & Diet Consultation</option>
            <option value="Physiotherapy" <?= $filterService == 'Physiotherapy' ? 'selected' : '' ?>>Physiotherapy</option>
            <option value="Massage Therapy" <?= $filterService == 'Massage Therapy' ? 'selected' : '' ?>>Massage Therapy</option>
        </select>
        <button class="filter-button" type="submit">Apply</button>
    </form>

    <table>
        <tr>
            <th>ID</th><th>User</th><th>Email</th><th>Service</th><th>Date</th><th>Time</th><th>Actions</th>
        </tr>
        <?php while ($row = $appointments->fetch_assoc()): ?>
        <tr>
            <td><?= $row['id'] ?></td>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['service'] ?></td>
            <td><?= $row['date'] ?></td>
            <td><?= $row['time'] ?></td>
            <td>
                <a href="edit_appointment.php?id=<?= $row['id'] ?>">Edit</a>
                <a href="delete_appointment.php?id=<?= $row['id'] ?>" onclick="return confirm('Are you sure to delete this appointment?')">Delete</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

    <p style="text-align:center; margin-top: 20px;"><a href="home.html">Logout</a></p>
</body>
</html>
