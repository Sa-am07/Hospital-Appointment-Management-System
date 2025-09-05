<?php
function sendEmailReminder($email, $appointment_date, $appointment_time, $doctor_name) {
    $subject = "NHS Appointment Confirmation";
    $message = "Dear patient,";
    $message .= "Your appointment with Dr. $doctor_name has been confirmed.";
    $message .= "Date: " . date('d M Y', strtotime($appointment_date));
    $message .= "Time: " . date('H:i', strtotime($appointment_time));
    $message .= "Please arrive 10 minutes before your scheduled time.";
    $message .= "Thank you,\nNHS Appointment System";
    
    $headers = "From: no-reply@nhs.com";
    
    return mail($email, $subject, $message, $headers);
}

function sendCustomNotification($email, $subject, $message) {
    $headers = "From: no-reply@nhs.com";
    return mail($email, $subject, $message, $headers);
}
?>
