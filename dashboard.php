<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$full_name = $_SESSION['full_name'];
$role = $_SESSION['role']; // patient or admin
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Dashboard - NHS Appointment</title>
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
        .dashboard-container {
            width: 100%;
            max-width: 800px;
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
        .welcome-message {
            color: #005eb8;
            text-align: center;
            margin-bottom: 40px;
            font-size: 24px;
        }
        .action-card {
            border: 2px solid #d8dde0;
            border-radius: 8px;
            padding: 25px;
            margin-bottom: 30px;
        }
        .card-title {
            color: #005eb8;
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
            text-align: center;
        }
        .btn-nhs {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            border-radius: 4px;
            margin-bottom: 15px;
            transition: background-color 0.3s;
        }
        .btn-nhs:hover {
            background-color: #003d73;
            color: white;
        }
        .btn-logout {
            background-color: #da291c;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            border-radius: 4px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-logout:hover {
            background-color: #a51e15;
            color: white;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>NHS Appointment Dashboard</h1>
        <div class="welcome-message">Welcome, <?php echo htmlspecialchars($full_name); ?>!</div>

        <?php if ($role == 'admin'): ?>
            <div class="action-card">
                <h3 class="card-title">Admin Options</h3>
                <a href="admin/manage_users.php" class="btn btn-nhs">Manage Users</a>
                <a href="admin/manage_doctors.php" class="btn btn-nhs">Manage Doctors</a>
                <a href="admin/manage_appointments.php" class="btn btn-nhs">Manage Appointments</a>
            </div>
        <?php else: ?>
            <div class="action-card">
                <h3 class="card-title">Patient Actions</h3>
                <a href="book_appointment.php" class="btn btn-nhs">Book Appointment</a>
                <a href="appointment_history.php" class="btn btn-nhs">View My Appointments</a>
                <a href="patient_notifications.php" class="btn btn-nhs"> My Notifications</a>
              
            </div>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-logout">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>