<?php
include 'db.php';

$doctor_query = "SELECT * FROM doctors";
$doctor_result = mysqli_query($conn, $doctor_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Doctor List</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <div class="container">
        <h2>List of Doctors</h2>
        <ul>
            <?php while($doctor = mysqli_fetch_assoc($doctor_result)): ?>
                <li>
                    <?= htmlspecialchars($doctor['name']) ?> - <?= htmlspecialchars($doctor['specialty']) ?>
                    <a href="book_appointment.php?doctor_id=<?= $doctor['id'] ?>" class="btn btn-primary">Book Appointment</a>
                </li>
            <?php endwhile; ?>
        </ul>
    </div>
</body>
</html>
