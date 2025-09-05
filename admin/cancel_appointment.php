<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db.php';

// Check if 'id' is in the URL
if (isset($_GET['id'])) {
    $appointment_id = $_GET['id'];

    // Debug: Output the appointment ID to check if it's correctly passed
    echo "Appointment ID: " . $appointment_id . "<br>";  // Debugging line

    // Prepare the query to update the appointment status
    $query = "UPDATE appointments SET status = 'Cancelled' WHERE id = ?";
    $stmt = mysqli_prepare($conn, $query);

    if ($stmt === false) {
        die('Error preparing query: ' . mysqli_error($conn)); // Debugging line
    }

    // Bind the parameter and execute the query
    mysqli_stmt_bind_param($stmt, 'i', $appointment_id);

    if (mysqli_stmt_execute($stmt)) {
        // Successfully cancelled the appointment
        echo "Appointment cancelled successfully";  // Debugging line
        header("Location: manage_appointments.php?status=cancelled");
        exit();
    } else {
        echo "Error executing query: " . mysqli_error($conn);  // Debugging line
    }
} else {
    // If no ID is provided, show an error message
    echo "No appointment ID provided";  // Debugging line
}
?>
