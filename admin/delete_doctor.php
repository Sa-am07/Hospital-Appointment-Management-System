<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $query = "SELECT * FROM doctors WHERE id = $id";
    $result = mysqli_query($conn, $query);
    $doctor = mysqli_fetch_assoc($result);

    if (!$doctor) {
        header("Location: manage_doctors.php");
        exit();
    }
}

if (isset($_POST['confirm_delete'])) {
    $id = $_POST['id'];
    $deleteQuery = "DELETE FROM doctors WHERE id = $id";
    if (mysqli_query($conn, $deleteQuery)) {
        header("Location: manage_doctors.php");
        exit();
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Delete Doctor</title>
    <link href="https://fonts.googleapis.com/css2?family=Arial:wght@400;500;700&display=swap" rel="stylesheet">
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
            text-align: center;
        }
        h1 {
            color: #005eb8;
            margin-bottom: 30px;
            font-size: 28px;
            font-weight: bold;
        }
        .confirmation-message {
            background-color: #fff9f9;
            border-left: 4px solid #da291c;
            padding: 20px;
            margin-bottom: 30px;
            text-align: left;
        }
        .doctor-info {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 30px;
            border: 1px solid #e9ecef;
            text-align: left;
        }
        .doctor-info p {
            margin: 10px 0;
        }
        .doctor-info strong {
            color: #005eb8;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            justify-content: center;
        }
        .btn-danger {
            background-color: #da291c;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .btn-danger:hover {
            background-color: #a51e15;
        }
        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-decoration: none;
        }
        .btn-secondary:hover {
            background-color: #e0e0e0;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Delete Doctor</h1>
        
        <div class="confirmation-message">
            <p>You are about to permanently delete this doctor record. This action cannot be undone.</p>
        </div>
        
        <div class="doctor-info">
            <p><strong>Name:</strong> <?= htmlspecialchars($doctor['name']); ?></p>
            <p><strong>Specialization:</strong> <?= htmlspecialchars($doctor['specialization']); ?></p>
            <p><strong>Email:</strong> <?= htmlspecialchars($doctor['email']); ?></p>
            <p><strong>Phone:</strong> <?= htmlspecialchars($doctor['phone']); ?></p>
        </div>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?= $doctor['id']; ?>">
            <div class="btn-container">
                <button type="submit" name="confirm_delete" class="btn-danger">Confirm Delete</button>
                <a href="manage_doctors.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>