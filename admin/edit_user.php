<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db.php';

if (!isset($_GET['id'])) {
    die("No user ID provided.");
}

$user_id = (int) $_GET['id'];

// Fetch current user data
$query = "SELECT * FROM users WHERE id = '$user_id'";
$result = mysqli_query($conn, $query);
if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found.");
}
$user = mysqli_fetch_assoc($result);

// Handle form submission
if (isset($_POST['update'])) {
    $full_name = mysqli_real_escape_string($conn, $_POST['full_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $phone = mysqli_real_escape_string($conn, $_POST['phone']);

    $update_query = "UPDATE users SET full_name = '$full_name', email = '$email', phone = '$phone' WHERE id = '$user_id'";
    if (mysqli_query($conn, $update_query)) {
        echo "<script>alert('User updated successfully!'); window.location.href='manage_users.php';</script>";
        exit();
    } else {
        $error = "Error updating user: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit User - NHS</title>
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
            background-color: #6c757d;
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
            background-color: #5a6268;
        }
        .button-group {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            margin-bottom: 20px;
            padding: 15px;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="edit-container">
        <h1>Edit User</h1>
        
        <?php if (isset($error)): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label>Full Name</label>
                <input type="text" name="full_name" class="form-control" 
                       value="<?= htmlspecialchars($user['full_name']) ?>" required>
            </div>
            <div class="form-group">
                <label>Email Address</label>
                <input type="email" name="email" class="form-control" 
                       value="<?= htmlspecialchars($user['email']) ?>" required>
            </div>
            <div class="form-group">
                <label>Phone Number</label>
                <input type="tel" name="phone" class="form-control" 
                       value="<?= htmlspecialchars($user['phone']) ?>" required>
            </div>
            
            <div class="button-group">
                <button type="submit" name="update" class="btn-update">
                    Update User
                </button>
                <a href="manage_users.php" class="btn-cancel">
                    Cancel
                </a>
            </div>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>