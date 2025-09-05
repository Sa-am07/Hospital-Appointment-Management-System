<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db.php';

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get the form data
    $doctor_name = mysqli_real_escape_string($conn, $_POST['doctor_name']);
    $specialization = mysqli_real_escape_string($conn, $_POST['specialization']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    
    // Insert the new doctor into the database
    $query = "INSERT INTO doctors (name, specialization, email, phone) 
              VALUES ('$doctor_name', '$specialization', '$email', '$phone')";
    
    if (mysqli_query($conn, $query)) {
        // Redirect to a success page or display a success message
        echo "<script>alert('Doctor added successfully!'); window.location='manage_doctors.php';</script>";
    } else {
        // If the query fails, show an error message
        echo "<script>alert('Error adding doctor: " . mysqli_error($conn) . "');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Doctor</title>
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
        .btn-nhs {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 12px 25px;
            font-size: 16px;
            font-weight: bold;
            border-radius: 4px;
            cursor: pointer;
            transition: background-color 0.3s;
            width: 100%;
            margin-top: 10px;
        }
        .btn-nhs:hover {
            background-color: #003d73;
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
        <h1>Add New Doctor</h1>
        
        <form action="add_doctor.php" method="POST">
            <div class="field-container">
                <div class="form-group">
                    <label for="doctor_name">Doctor Name</label>
                    <input type="text" id="doctor_name" name="doctor_name" required>
                </div>
            </div>
            
            <div class="field-container">
                <div class="form-group">
                    <label for="specialization">Specialization</label>
                    <input type="text" id="specialization" name="specialization" required>
                </div>
            </div>
            
            <div class="field-container">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
            </div>
            
            <div class="field-container">
                <div class="form-group">
                    <label for="phone">Contact Number</label>
                    <input type="text" id="phone" name="phone" required>
                </div>
            </div>
            
            <button type="submit" class="btn-nhs">Add Doctor</button>
        </form>
    </div>
</body>
</html>