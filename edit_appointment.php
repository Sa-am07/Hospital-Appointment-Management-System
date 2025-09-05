<?php
include 'db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];
$appointment_id = (int) $_GET['id'];

// Fetch appointment details
$query = "SELECT a.id, a.appointment_date, a.appointment_time, a.status, d.name AS doctor_name, d.specialization
          FROM appointments a
          JOIN doctors d ON a.doctor_id = d.id
          WHERE a.id = '$appointment_id' AND a.user_id = '$user_id'";
$result = mysqli_query($conn, $query);

if (!$result) {
    die("Query failed: " . mysqli_error($conn));
}

$appointment = mysqli_fetch_assoc($result);

if (!$appointment) {
    echo "Appointment not found or you're not authorized to edit this appointment.";
    exit();
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update'])) {
        $new_date = mysqli_real_escape_string($conn, $_POST['appointment_date']);
        $new_time = mysqli_real_escape_string($conn, $_POST['appointment_time']);
        $new_status = mysqli_real_escape_string($conn, $_POST['status']);

        $update_query = "UPDATE appointments 
                         SET appointment_date = '$new_date', appointment_time = '$new_time', status = '$new_status'
                         WHERE id = '$appointment_id' AND user_id = '$user_id'";

        if (mysqli_query($conn, $update_query)) {
            header("Location: appointment_history.php");
            exit();
        } else {
            echo "Error updating appointment: " . mysqli_error($conn);
        }
    }

    if (isset($_POST['cancel'])) {
        $cancel_query = "DELETE FROM appointments WHERE id = '$appointment_id' AND user_id = '$user_id'";

        if (mysqli_query($conn, $cancel_query)) {
            header("Location: appointment_history.php");
            exit();
        } else {
            echo "Error canceling appointment: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edit Appointment - NHS</title>
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
        .edit-container {
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
        .form-group {
            margin-bottom: 25px;
        }
        label {
            font-weight: bold;
            color: #005eb8;
            margin-bottom: 8px;
            display: block;
        }
        .form-control {
            height: 50px;
            border: 2px solid #d8dde0;
            border-radius: 4px;
            padding: 10px 15px;
            font-size: 16px;
        }
        .form-control:focus {
            border-color: #005eb8;
            box-shadow: 0 0 0 0.2rem rgba(0, 94, 184, 0.25);
        }
        .btn-update {
            background-color: #005eb8;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            width: 48%;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn-update:hover {
            background-color: #003d73;
        }
        .btn-cancel {
            background-color: #da291c;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            font-weight: bold;
            width: 48%;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .btn-cancel:hover {
            background-color: #a51e15;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        select.form-control {
            appearance: none;
            background-image: url("data:image/svg+xml;charset=UTF-8,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24' fill='%23005eb8'%3e%3cpath d='M7 10l5 5 5-5z'/%3e%3c/svg%3e");
            background-repeat: no-repeat;
            background-position: right 15px center;
            background-size: 15px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Edit Appointment</h1>
        <form method="POST">
            <div class="form-group">
                <label>Doctor</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($appointment['doctor_name']) ?>" disabled>
            </div>
            <div class="form-group">
                <label>Specialization</label>
                <input type="text" class="form-control" value="<?= htmlspecialchars($appointment['specialization']) ?>" disabled>
            </div>
            <div class="form-group">
                <label>Appointment Date</label>
                <input type="date" name="appointment_date" class="form-control" value="<?= htmlspecialchars($appointment['appointment_date']) ?>" required min="<?= date('Y-m-d'); ?>">
            </div>
            <div class="form-group">
                <label>Appointment Time</label>
                <input type="time" name="appointment_time" class="form-control" value="<?= htmlspecialchars($appointment['appointment_time']) ?>" required>
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status" class="form-control" required>
                    <option value="Booked" <?= $appointment['status'] == 'Booked' ? 'selected' : '' ?>>Booked</option>
                    <option value="Confirmed" <?= $appointment['status'] == 'Confirmed' ? 'selected' : '' ?>>Confirmed</option>
                    <option value="Completed" <?= $appointment['status'] == 'Completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="Rescheduled" <?= $appointment['status'] == 'Rescheduled' ? 'selected' : '' ?>>Rescheduled</option>
                    <option value="Canceled" <?= $appointment['status'] == 'Canceled' ? 'selected' : '' ?>>Canceled</option>
                </select>
            </div>
            <div class="button-group">
                <button type="submit" name="update" class="btn-update">Update Appointment</button>
                <button type="submit" name="cancel" class="btn-cancel">Cancel Appointment</button>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Set minimum date to today
        document.querySelector('input[name="appointment_date"]').min = new Date().toISOString().split('T')[0];
    </script>
</body>
</html>