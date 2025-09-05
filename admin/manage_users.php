<?php
session_start();
if (!isset($_SESSION['admin_id'])) {
    header("Location: ../admin_login.php");
    exit();
}

include '../db.php';

// Check if the delete parameter is set
if (isset($_GET['delete'])) {
    $user_id = (int) $_GET['delete'];

    // First, delete related appointments and check-ins (if any)
    $delete_appointments_query = "DELETE FROM appointments WHERE user_id = '$user_id'";
    mysqli_query($conn, $delete_appointments_query);

    // Delete the user
    $delete_user_query = "DELETE FROM users WHERE id = '$user_id'";
    if (mysqli_query($conn, $delete_user_query)) {
        echo "<script>alert('User and their appointments deleted successfully!'); window.location.href='manage_users.php';</script>";
    } else {
        echo "<script>alert('Error deleting user: " . mysqli_error($conn) . "');</script>";
    }
}

// Fetch all users
$query = "SELECT * FROM users";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Users - NHS</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            padding: 20px;
        }
        .management-container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            padding: 30px;
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
        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        .table th {
            background-color: #005eb8;
            color: white;
            padding: 12px;
            text-align: left;
        }
        .table td {
            padding: 12px;
            border-bottom: 1px solid #e0e0e0;
        }
        .table tr:nth-child(even) {
            background-color: #f5f9ff;
        }
        .table tr:hover {
            background-color: #e6f0ff;
        }
        .btn-edit {
            background-color: #ffc107;
            color: #212529;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-edit:hover {
            background-color: #e0a800;
            color: #212529;
        }
        .btn-delete {
            background-color: #da291c;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 4px;
            font-weight: bold;
            transition: background-color 0.3s;
        }
        .btn-delete:hover {
            background-color: #a51e15;
            color: white;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .alert-success {
            background-color: #d4edda;
            color: #155724;
            border-color: #c3e6cb;
            margin-bottom: 20px;
        }
        .alert-danger {
            background-color: #f8d7da;
            color: #721c24;
            border-color: #f5c6cb;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="management-container">
        <h1>Manage Users</h1>
        
        <?php if (isset($_GET['status']) && $_GET['status'] == 'deleted'): ?>
            <div class="alert alert-success">User has been deleted successfully.</div>
        <?php endif; ?>
        
        <?php if (isset($_GET['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_GET['error']) ?></div>
        <?php endif; ?>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0): ?>
                        <?php while ($user = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?= htmlspecialchars($user['id']) ?></td>
                                <td><?= htmlspecialchars($user['full_name']) ?></td>
                                <td><?= htmlspecialchars($user['email']) ?></td>
                                <td><?= htmlspecialchars($user['role']) ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit_user.php?id=<?= $user['id'] ?>" class="btn-edit">
                                            <i class="fas fa-edit"></i> Edit
                                        </a>
                                        <a href="?delete=<?= $user['id'] ?>" class="btn-delete" 
                                           onclick="return confirm('Are you sure you want to delete this user and all their appointments?')">
                                            <i class="fas fa-trash-alt"></i> Delete
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="5" class="text-center">No users found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirm before deleting
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', (e) => {
                if (!confirm('Are you sure you want to delete this user and all their appointments?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>