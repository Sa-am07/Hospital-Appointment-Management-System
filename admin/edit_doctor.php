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
}

if (isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $specialization = $_POST['specialization'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];

    $updateQuery = "UPDATE doctors SET name='$name', specialization='$specialization', email='$email', phone='$phone' WHERE id=$id";
    if (mysqli_query($conn, $updateQuery)) {
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
    <title>Edit Doctor</title>
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
        .form-group {
            margin-bottom: 25px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #005eb8;
        }
        input[type="text"],
        input[type="email"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #d8dde0;
            border-radius: 4px;
            font-size: 16px;
            transition: border-color 0.3s;
        }
        input[type="text"]:focus,
        input[type="email"]:focus {
            border-color: #005eb8;
            outline: none;
        }
        .btn-container {
            display: flex;
            gap: 15px;
            margin-top: 30px;
        }
        .btn-nhs {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            flex: 1;
        }
        .btn-nhs:hover {
            background-color: #003d73;
        }
        .btn-secondary {
            background-color: #f0f0f0;
            color: #333;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            text-align: center;
            text-decoration: none;
            flex: 1;
        }
        .btn-secondary:hover {
            background-color: #e0e0e0;
        }
        .field-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 6px;
            margin-bottom: 20px;
            border: 1px solid #e9ecef;
        }
        .field-container label {
            color: #005eb8;
        }
    </style>
</head>
<body>
    <div class="dashboard-container">
        <h1>Edit Doctor</h1>
        
        <form method="POST">
            <input type="hidden" name="id" value="<?= $doctor['id']; ?>">
            
            <div class="field-container">
                <div class="form-group">
                    <label>Full Name</label>
                    <input type="text" name="name" value="<?= $doctor['name']; ?>" required>
                </div>
            </div>
            
            <div class="field-container">
                <div class="form-group">
                    <label>Specialization</label>
                    <input type="text" name="specialization" value="<?= $doctor['specialization']; ?>" required>
                </div>
            </div>
            
            <div class="field-container">
                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" value="<?= $doctor['email']; ?>" required>
                </div>
            </div>
            
            <div class="field-container">
                <div class="form-group">
                    <label>Phone Number</label>
                    <input type="text" name="phone" value="<?= $doctor['phone']; ?>" required>
                </div>
            </div>
            
            <div class="btn-container">
                <button type="submit" name="update" class="btn-nhs">Update Doctor</button>
                <a href="manage_doctors.php" class="btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</body>
</html>