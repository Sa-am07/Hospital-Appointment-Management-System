<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

// Get notifications
$query = "SELECT n.*, a.appointment_date, a.appointment_time, d.name as doctor_name
          FROM notifications n
          JOIN appointments a ON n.appointment_id = a.id
          JOIN doctors d ON a.doctor_id = d.id
          WHERE n.user_id = '$user_id'
          ORDER BY n.created_at DESC";
$result = mysqli_query($conn, $query);

// Mark as read
if (isset($_GET['mark_read'])) {
    $notification_id = (int) $_GET['mark_read'];
    $update_query = "UPDATE notifications SET is_read = TRUE WHERE id = '$notification_id' AND user_id = '$user_id'";
    mysqli_query($conn, $update_query);
    header("Location: patient_notifications.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>My Notifications - NHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .notification-container {
            max-width: 800px;
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
        .notification {
            border-left: 4px solid #005eb8;
            padding: 15px;
            margin-bottom: 15px;
            background-color: #f5f9ff;
        }
        .notification.unread {
            border-left: 4px solid #da291c;
            background-color: #fff0f0;
        }
        .notification-time {
            color: #666;
            font-size: 0.9em;
        }
    </style>
</head>
<body>
    <div class="notification-container">
        <h1>My Notifications</h1>
        
        <?php if (mysqli_num_rows($result) > 0): ?>
            <?php while ($notification = mysqli_fetch_assoc($result)): ?>
                <div class="notification <?= $notification['is_read'] ? '' : 'unread' ?>">
                    <h5>Appointment with Dr. <?= htmlspecialchars($notification['doctor_name']) ?></h5>
                    <p><?= nl2br(htmlspecialchars($notification['message'])) ?></p>
                    <p class="notification-time">
                        <?= date('d M Y H:i', strtotime($notification['created_at'])) ?>
                        <?php if (!$notification['is_read']): ?>
                            | <a href="patient_notifications.php?mark_read=<?= $notification['id'] ?>">Mark as read</a>
                        <?php endif; ?>
                    </p>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>You have no notifications.</p>
        <?php endif; ?>
    </div>
</body>
</html>