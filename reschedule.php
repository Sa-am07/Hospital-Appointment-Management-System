<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$appointment_id = $_GET['appointment_id'];  // Assuming the appointment ID is passed via GET
$query = "SELECT * FROM appointments WHERE id = '$appointment_id' AND user_id = '".$_SESSION['user_id']."'";
$result = mysqli_query($conn, $query);
$appointment = mysqli_fetch_assoc($result);

if (!$appointment) {
    echo "Appointment not found!";
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $new_date = $_POST['appointment_date'];
    $new_time = $_POST['appointment_time'];
    
    // Update the appointment
    $update_query = "UPDATE appointments SET appointment_date = '$new_date', appointment_time = '$new_time' WHERE id = '$appointment_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('Appointment rescheduled successfully!'); window.location.href='appointment_history.php';</script>";
    } else {
        echo "<script>alert('Error rescheduling appointment.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reschedule Appointment</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>Reschedule Your Appointment</h2>
        <form method="POST">
            <div class="form-group">
                <label>Date</label>
                <input type="date" name="appointment_date" class="form-control" value="<?= $appointment['appointment_date'] ?>" required>
            </div>
            <div class="form-group">
                <label>Time</label>
                <input type="time" name="appointment_time" class="form-control" value="<?= $appointment['appointment_time'] ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Reschedule</button>
        </form>
    </div>
</body>
</html>
