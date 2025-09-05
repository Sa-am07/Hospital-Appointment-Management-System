<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db.php';

// Fetch all appointments
$query = "SELECT appointments.id, full_name AS full_name, doctors.name AS doctor_name, 
                 appointment_date, appointment_time, users.id AS user_id
          FROM appointments
          JOIN users ON appointments.user_id = users.id
          JOIN doctors ON appointments.doctor_id = doctors.id";
$result = mysqli_query($conn, $query);

if (!$result) {
    die('Error executing query: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Appointments</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 0 20px rgba(0, 94, 184, 0.1);
        }
        h2 {
            color: #005eb8;
            margin-bottom: 30px;
            text-align: center;
        }
        .table th {
            background-color: #005eb8;
            color: white;
        }
        .btn-notify {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-notify:hover {
            background-color: #003d73;
            color: white;
        }
        .btn-cancel {
            background-color: #da291c;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
        }
        .btn-cancel:hover {
            background-color: #a51e15;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
    </style>
</head>
<body>
<div class="container mt-4">
    <h2>Manage Appointments</h2>

    <?php if (isset($_GET['status']) && $_GET['status'] == 'cancelled'): ?>
        <div class="alert alert-success">Appointment has been cancelled successfully.</div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Doctor</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if (mysqli_num_rows($result) > 0): ?>
                    <?php while ($appointment = mysqli_fetch_assoc($result)): ?>
                        <tr>
                            <td><?= htmlspecialchars($appointment['id']) ?></td>
                            <td><?= htmlspecialchars($appointment['full_name']) ?></td>
                            <td><?= htmlspecialchars($appointment['doctor_name']) ?></td>
                            <td><?= date('d M Y', strtotime($appointment['appointment_date'])) ?></td>
                            <td><?= date('H:i', strtotime($appointment['appointment_time'])) ?></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="btn-notify" 
                                            onclick="openNotificationModal(
                                                '<?= $appointment['user_id'] ?>',
                                                '<?= $appointment['id'] ?>',
                                                '<?= htmlspecialchars($appointment['full_name']) ?>',
                                                '<?= htmlspecialchars($appointment['doctor_name']) ?>',
                                                '<?= $appointment['appointment_date'] ?>',
                                                '<?= $appointment['appointment_time'] ?>'
                                            )">
                                        Send Notification
                                    </button>
                                    <a href="cancel_appointment.php?id=<?= $appointment['id'] ?>" 
                                       class="btn-cancel">
                                       Cancel
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No appointments found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Notification Modal -->
<div class="modal fade" id="notificationModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Send Notification</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="send_notification.php" method="POST">
                <div class="modal-body">
                    <input type="hidden" name="user_id" id="modal_user_id">
                    <input type="hidden" name="appointment_id" id="modal_appointment_id">
                    
                    <div class="mb-3">
                        <label>Recipient</label>
                        <input type="text" class="form-control" id="modal_recipient" readonly>
                    </div>
                    
                    <div class="mb-3">
                        <label>Appointment Details</label>
                        <textarea class="form-control" id="modal_appointment_details" readonly rows="3"></textarea>
                    </div>
                    
                    <div class="mb-3">
                        <label for="notification_message">Message</label>
                        <textarea class="form-control" name="message" id="notification_message" rows="5" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn-notify">Send Notification</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    function openNotificationModal(userId, appointmentId, fullName, doctorName, date, time) {
        // Set modal values
        document.getElementById('modal_user_id').value = userId;
        document.getElementById('modal_appointment_id').value = appointmentId;
        document.getElementById('modal_recipient').value = fullName;
        
        const formattedDate = new Date(date).toLocaleDateString('en-GB', { 
            day: 'numeric', month: 'short', year: 'numeric' 
        });
        const formattedTime = new Date('1970-01-01T' + time + 'Z').toLocaleTimeString('en-GB', { 
            hour: '2-digit', minute: '2-digit' 
        });
        
        document.getElementById('modal_appointment_details').value = 
            `Appointment with Dr. ${doctorName}\n` +
            `Date: ${formattedDate}\n` +
            `Time: ${formattedTime}`;
        
        // Set default message
        document.getElementById('notification_message').value = 
            `Dear ${fullName},\n\n` +
            `This is a reminder for your upcoming appointment with Dr. ${doctorName} ` +
            `on ${formattedDate} at ${formattedTime}.\n\n` +
            `Please arrive 10 minutes before your scheduled time.\n\n` +
            `Kind regards,\nNHS Appointment Team`;
        
        // Show modal
        const modal = new bootstrap.Modal(document.getElementById('notificationModal'));
        modal.show();
    }
</script>
</body>
</html>