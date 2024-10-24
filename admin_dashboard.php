<?php
session_start();
include 'config.php';

if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
}

$sql = "SELECT * FROM users";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Admin Dashboard</h2>
    <table class="table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Email</th>
                <th>Role</th>
                <th>Profile Picture</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while($user = $result->fetch_assoc()): ?>
            <tr>
                <td><?php echo $user['id']; ?></td>
                <td><?php echo $user['username']; ?></td>
                <td><?php echo $user['email']; ?></td>
                <td><?php echo $user['role']; ?></td>
                <td><img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="50"></td>
                <td>
                    <a href="edit_user.php?id=<?php echo $user['id']; ?>" class="btn btn-warning">Edit</a>
                    <a  class="btn btn-info btn-sm" href="profile.php">View Profile</a>
                    <a href="delete_user.php?id=<?php echo $user['id']; ?>" class="btn btn-danger">Delete</a>

                    <button onclick="window.print()">Print User Records</button>
                </td>
                
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
    <a class="nav-link" href="logout.php">Logout</a> <!-- Logout link -->
</div>
</body>
</html>
