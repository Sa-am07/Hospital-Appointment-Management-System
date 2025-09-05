<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    die("No appointment ID provided.");
}

$appointment_id = (int) $_GET['id'];

// Get appointment details
$query = "SELECT a.id, a.appointment_date, a.appointment_time, d.name AS doctor_name, d.specialization
          FROM appointments a
          JOIN doctors d ON a.doctor_id = d.id
          WHERE a.id = '$appointment_id' AND a.user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Appointment not found or access denied.");
}

$appointment = mysqli_fetch_assoc($result);

// Check if already checked in
$checkQuery = "SELECT checkin_time FROM checkin WHERE appointment_id = '$appointment_id'";
$checkResult = mysqli_query($conn, $checkQuery);

$alreadyCheckedIn = false;
$checkinTime = null;

if ($checkResult && mysqli_num_rows($checkResult) > 0) {
    $checkinRow = mysqli_fetch_assoc($checkResult);
    $alreadyCheckedIn = true;
    $checkinTime = $checkinRow['checkin_time'];
}

// Handle check-in
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['check_in']) && !$alreadyCheckedIn) {
    $checkin_time = date('Y-m-d H:i:s');
    $insertQuery = "INSERT INTO checkin (appointment_id, checkin_time) VALUES ('$appointment_id', '$checkin_time')";

    if (mysqli_query($conn, $insertQuery)) {
        header("Location: appointment_history.php");
        exit();
    } else {
        die("Check-in failed: " . mysqli_error($conn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Check-in Appointment - NHS</title>
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
        .checkin-container {
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
        .appointment-details {
            margin-bottom: 30px;
        }
        .detail-row {
            display: flex;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        .detail-label {
            font-weight: bold;
            color: #005eb8;
            width: 150px;
        }
        .detail-value {
            flex: 1;
        }
        .btn-checkin {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            width: 100%;
            border-radius: 4px;
            margin-top: 20px;
            transition: background-color 0.3s;
        }
        .btn-checkin:hover {
            background-color: #003d73;
            color: white;
        }
        .alert-info {
            background-color: #e6f2ff;
            color: #005eb8;
            border-color: #b3d1ff;
            margin-top: 20px;
        }
        .checkin-time {
            font-weight: bold;
            color: #4caf50;
        }
    </style>
</head>
<body>
    <div class="checkin-container">
        <h1>Appointment Check-in</h1>
        
        <div class="appointment-details">
            <div class="detail-row">
                <div class="detail-label">Doctor:</div>
                <div class="detail-value"><?= htmlspecialchars($appointment['doctor_name']) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Specialization:</div>
                <div class="detail-value"><?= htmlspecialchars($appointment['specialization']) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Date:</div>
                <div class="detail-value"><?= date('d M Y', strtotime($appointment['appointment_date'])) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Time:</div>
                <div class="detail-value"><?= date('H:i', strtotime($appointment['appointment_time'])) ?></div>
            </div>
            <div class="detail-row">
                <div class="detail-label">Status:</div>
                <div class="detail-value">
                    <?php if ($alreadyCheckedIn): ?>
                        <span class="checkin-time">Checked in at <?= date('H:i', strtotime($checkinTime)) ?></span>
                    <?php else: ?>
                        Not checked in yet
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!$alreadyCheckedIn): ?>
            <form method="POST">
                <button type="submit" name="check_in" class="btn-checkin">Check-in Now</button>
            </form>
        <?php else: ?>
            <div class="alert alert-info">
                You have already checked in for this appointment.
            </div>
            <a href="appointment_history.php" class="btn-checkin">Back to Appointments</a>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>