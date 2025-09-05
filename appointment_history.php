<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$query = "SELECT a.id, a.appointment_date, a.appointment_time, d.name AS doctor_name, 
                 d.specialization, a.status
          FROM appointments a
          JOIN doctors d ON a.doctor_id = d.id
          WHERE a.user_id = '$user_id'
          ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Appointment History - NHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .history-container {
            width: 100%;
            max-width: 1000px;
            background: white;
            padding: 40px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 94, 184, 0.1);
            margin: 40px auto;
        }
        h1 {
            color: #005eb8;
            text-align: center;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            background-color: #005eb8;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tr:nth-child(even) {
            background-color: #f5f9ff;
        }
        .table tr:hover {
            background-color: #e6f0ff;
        }
        .btn-checkin {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-checkin:hover {
            background-color: #003d73;
            color: white;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #212529;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-edit:hover {
            background-color: #e0a800;
            color: #212529;
        }
        .status-pending {
            color: #ff9800;
            font-weight: bold;
        }
        .status-confirmed {
            color: #4caf50;
            font-weight: bold;
        }
        .status-cancelled {
            color: #f44336;
            font-weight: bold;
        }
        .no-appointments {
            text-align: center;
            color: #666;
            font-size: 18px;
            padding: 30px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Your Appointment History</h2>
    <?php if (mysqli_num_rows($result) > 0): ?>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Doctor</th>
                    <th>Specialization</th>
                    <th>Appointment Date</th>
                    <th>Appointment Time</th>
                    <th>Status</th>
                    <th>Action</th> <!-- New column for the Check-in button -->
                </tr>
            </thead>
            <tbody>
                <?php while($appointment = mysqli_fetch_assoc($result)): ?>
                    <tr>
                        <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                        <td><?= htmlspecialchars($appointment['specialization']) ?></td>
                        <td><?= htmlspecialchars($appointment['appointment_date']) ?></td>
                        <td><?= htmlspecialchars($appointment['appointment_time']) ?></td>
                        <td><?= htmlspecialchars($appointment['status']) ?></td>
                        <td>
                            <a href="checkin_appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-success">
                                Check-in
                            </a>
                            <a href="edit_appointment.php?id=<?= $appointment['id'] ?>" class="btn btn-warning">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>You have no past appointments.</p>
    <?php endif; ?>
</div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>