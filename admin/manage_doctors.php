<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db.php';

// Fetch all doctors
$query = "SELECT id, name, specialization, email, phone FROM doctors";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Error executing query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Doctors</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h2 class="text-center">Manage Doctors</h2>
    <!-- Add Doctor Button -->
    <div class="mb-3 text-right">
        <a href="add_doctor.php" class="btn btn-primary">Add Doctor</a>
    </div>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Specialization</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($doctor = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?= $doctor['id']; ?></td>
                    <td><?= $doctor['name']; ?></td>
                    <td><?= $doctor['specialization']; ?></td>
                    <td><?= $doctor['email']; ?></td>
                    <td><?= $doctor['phone']; ?></td>
                    <td>
                        <a href="edit_doctor.php?id=<?= $doctor['id']; ?>" class="btn btn-warning btn-sm">Edit</a>
                        <a href="delete_doctor.php?id=<?= $doctor['id']; ?>" class="btn btn-danger btn-sm">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
</body>
</html>
