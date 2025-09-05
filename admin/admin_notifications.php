<?php
include 'db.php';
session_start();

// Admin check
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: login.php");
    exit();
}

// Get upcoming appointments
$query = "SELECT a.id as appointment_id, u.id as user_id, u.full_name, u.email, 
                 a.appointment_date, a.appointment_time, d.name as doctor_name
          FROM appointments a
          JOIN users u ON a.user_id = u.id
          JOIN doctors d ON a.doctor_id = d.id
          WHERE a.appointment_date >= CURDATE()
          ORDER BY a.appointment_date, a.appointment_time";
$result = mysqli_query($conn, $query);

// Handle notification sending
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_notification'])) {
    $appointment_id = (int) $_POST['appointment_id'];
    $user_id = (int) $_POST['user_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    // Insert notification
    $insert_query = "INSERT INTO notifications (user_id, appointment_id, message)
                     VALUES ('$user_id', '$appointment_id', '$message')";
    
    if (mysqli_query($conn, $insert_query)) {
        // Get user email
        $email_query = "SELECT email FROM users WHERE id = '$user_id'";
        $email_result = mysqli_query($conn, $email_query);
        $user = mysqli_fetch_assoc($email_result);
        
        // Send email
        $subject = "NHS Appointment Reminder";
        $headers = "From: no-reply@nhs.com";
        mail($user['email'], $subject, $message, $headers);
        
        $success = "Notification sent successfully!";
    } else {
        $error = "Error sending notification: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Admin Notifications - NHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .notification-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 94, 184, 0.1);
        }
        h1 {
            color: #005eb8;
            margin-bottom: 30px;
        }
        .appointment-card {
            border: 1px solid #d8dde0;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .btn-nhs {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: bold;
        }
        .btn-nhs:hover {
            background-color: #003d73;
        }
        .form-group {
            margin-bottom: 20px;
        }
        textarea {
            min-height: 100px;
        }
    </style>
</head>
<body>
    <div class="notification-container">
        <h1>Send Appointment Reminders</h1>
        
        <?php if (isset($success)): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= $error ?></div>
        <?php endif; ?>

        <div class="row">
            <?php while ($appointment = mysqli_fetch_assoc($result)): ?>
                <div class="col-md-6">
                    <div class="appointment-card">
                        <h4>Appointment with Dr. <?= htmlspecialchars($appointment['doctor_name']) ?></h4>
                        <p><strong>Patient:</strong> <?= htmlspecialchars($appointment['full_name']) ?></p>
                        <p><strong>Date:</strong> <?= date('d M Y', strtotime($appointment['appointment_date'])) ?></p>
                        <p><strong>Time:</strong> <?= date('H:i', strtotime($appointment['appointment_time'])) ?></p>
                        
                        <form method="POST">
                            <input type="hidden" name="appointment_id" value="<?= $appointment['appointment_id'] ?>">
                            <input type="hidden" name="user_id" value="<?= $appointment['user_id'] ?>">
                            
                            <div class="form-group">
                                <label>Reminder Message</label>
                                <textarea name="message" class="form-control" required>
Dear <?= htmlspecialchars($appointment['full_name']) ?>,

This is a reminder for your appointment with Dr. <?= htmlspecialchars($appointment['doctor_name']) ?> 
on <?= date('d M Y', strtotime($appointment['appointment_date'])) ?> at <?= date('H:i', strtotime($appointment['appointment_time'])) ?>.

Please arrive 10 minutes before your scheduled time.

NHS Appointment System
                                </textarea>
                            </div>
                            
                            <button type="submit" name="send_notification" class="btn-nhs">
                                Send Reminder
                            </button>
                        </form>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
</body>
</html>