<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - NHS</title>
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
        .admin-menu {
            border-radius: 8px;
            overflow: hidden;
        }
        .admin-menu a {
            background-color: #f0f7ff;
            color: #005eb8;
            border: none;
            border-bottom: 1px solid #d8dde0;
            padding: 16px 20px;
            font-size: 16px;
            font-weight: bold;
            text-decoration: none;
            transition: all 0.3s;
            display: flex;
            align-items: center;
        }
        .admin-menu a:last-child {
            border-bottom: none;
        }
        .admin-menu a:hover {
            background-color: #005eb8;
            color: white;
            padding-left: 25px;
        }
        .admin-menu a::before {
            content: "→";
            margin-right: 10px;
            opacity: 0;
            transition: all 0.3s;
        }
        .admin-menu a:hover::before {
            opacity: 1;
        }
        .logout-item {
            background-color: #fff0f0 !important;
            color: #da291c !important;
        }
        .logout-item:hover {
            background-color: #da291c !important;
            color: white !important;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>NHS Admin Dashboard</h1>
        
        <div class="admin-menu">
            <a href="manage_users.php">
                <i class="fas fa-users me-2"></i> Manage Users
            </a>
            <a href="manage_doctors.php">
                <i class="fas fa-user-md me-2"></i> Manage Doctors
            </a>
            <a href="manage_appointments.php">
                <i class="fas fa-calendar-alt me-2"></i> Manage Appointments
            </a>
           
            <a href="../logout.php" class="logout-item">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </a>
        </div>
    </div>

    <!-- Font Awesome for icons -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>