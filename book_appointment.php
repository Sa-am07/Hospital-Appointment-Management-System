<?php
include 'db.php';
session_start();
require_once 'send_email.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user_id = $_SESSION['user_id'];
    $doctor_id = $_POST['doctor_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];

    // Fetch doctor name
    $doctor_query = "SELECT name FROM doctors WHERE id = '$doctor_id'";
    $doctor_result = mysqli_query($conn, $doctor_query);
    $doctor = mysqli_fetch_assoc($doctor_result);
    $doctor_name = $doctor['name'];

    // Fetch user email
    $user_query = "SELECT email FROM users WHERE id = '$user_id'";
    $user_result = mysqli_query($conn, $user_query);
    $user = mysqli_fetch_assoc($user_result);
    $user_email = $user['email'];

    // Insert appointment
    $query = "INSERT INTO appointments (user_id, doctor_id, appointment_date, appointment_time)
              VALUES ('$user_id', '$doctor_id', '$appointment_date', '$appointment_time')";

    if (mysqli_query($conn, $query)) {
        sendEmailReminder($user_email, $appointment_date, $appointment_time, $doctor_name);
        echo "<script>alert('Appointment booked successfully!'); window.location.href='appointment_history.php';</script>";
    } else {
        echo "<script>alert('Error booking appointment.');</script>";
    }
}

// Fetch doctors
$doctor_result = mysqli_query($conn, "SELECT id, name, specialization FROM doctors");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Book Appointment - NHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            padding: 20px;
        }
        .booking-container {
            width: 100%;
            max-width: 600px;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 94, 184, 0.1);
        }
        h1 {
            color: #005eb8;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }
        .form-group {
            margin-bottom: 25px;
        }
        .form-control {
            height: 50px;
            border: 2px solid #d8dde0;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #005eb8;
            box-shadow: 0 0 0 0.2rem rgba(0, 94, 184, 0.25);
        }
        label {
            font-weight: bold;
            color: #005eb8;
            margin-bottom: 8px;
            display: block;
        }
        .btn-book {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            border-radius: 4px;
            margin-top: 10px;
            transition: background-color 0.3s;
        }
        .btn-book:hover {
            background-color: #003d73;
            color: white;
        }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23005eb8'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <h1>Book Appointment</h1>
        <form method="POST">
            <div class="form-group">
                <label>Select Doctor</label>
                <select name="doctor_id" class="form-control" required>
                    <option value="">-- Choose Doctor --</option>
                    <?php while($doc = mysqli_fetch_assoc($doctor_result)): ?>
                        <option value="<?= $doc['id']; ?>">
                            <?= htmlspecialchars($doc['name']) . ' - ' . htmlspecialchars($doc['specialization']); ?>
                        </option>
                    <?php endwhile; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" required min="<?= date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Appointment Time</label>
                <input type="time" name="appointment_time" class="form-control" required min="09:00" max="17:00">
            </div>
            <button type="submit" class="btn-book">Book Appointment</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today
        document.querySelector('input[name="appointment_date"]').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>