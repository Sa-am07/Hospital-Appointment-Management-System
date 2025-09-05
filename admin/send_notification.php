<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

// Use correct relative path from admin folder to your db.php location
require_once __DIR__ . '/../db.php';  // Changed to direct relative path
require_once __DIR__ . '/../send_email.php';  // Changed to direct relative path

// Initialize $conn if not already done in db.php
if (!isset($conn)) {
    die("Database connection not established");
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Validate inputs
    if (!isset($_POST['user_id']) || !isset($_POST['appointment_id']) || !isset($_POST['message'])) {
        die("Invalid request parameters");
    }

    $user_id = (int) $_POST['user_id'];
    $appointment_id = (int) $_POST['appointment_id'];
    $message = mysqli_real_escape_string($conn, $_POST['message']);

    try {
        // 1. Store notification in database
        $query = "INSERT INTO notifications (user_id, appointment_id, message)
                  VALUES (?, ?, ?)";
        $stmt = mysqli_prepare($conn, $query);
        mysqli_stmt_bind_param($stmt, "iis", $user_id, $appointment_id, $message);
        
        if (!mysqli_stmt_execute($stmt)) {
            throw new Exception("Failed to save notification: " . mysqli_error($conn));
        }

        // 2. Send email notification
        $user_query = "SELECT email FROM users WHERE id = ?";
        $stmt = mysqli_prepare($conn, $user_query);
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
        $user_result = mysqli_stmt_get_result($stmt);
        $user = mysqli_fetch_assoc($user_result);

        if ($user && function_exists('sendCustomNotification')) {
            $email_sent = sendCustomNotification(
                $user['email'], 
                "Appointment Reminder", 
                $message
            );
            
            if (!$email_sent) {
                error_log("Failed to send email to " . $user['email']);
            }
        }

        // 3. Redirect back with success message
        header("Location: manage_appointments.php?status=notified");
        exit();

    } catch (Exception $e) {
        error_log($e->getMessage());
        die("An error occurred while processing your request. Please try again later.");
    }
} else {
    header("Location: manage_appointments.php");
    exit();
}
?>