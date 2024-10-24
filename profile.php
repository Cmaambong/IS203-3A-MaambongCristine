<?php
session_start();
include 'config.php';

// Redirect to login if not logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Fetch user data from the database
$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "No user found!";
    exit();
}

$user = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">User Profile</a>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div class="container mt-4">
    <h2>Your Profile</h2>
    <div class="card">
        <div class="card-body">
            <h5 class="card-title">Username: <?php echo $user['username']; ?></h5>
            <p class="card-text">Email: <?php echo $user['email']; ?></p>
            <p class="card-text">Role: <?php echo ucfirst($user['role']); ?></p>
            <p class="card-text">
                <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="150" class="img-thumbnail">
            </p>
            <a href="edit_user.php?id=<?php echo $user_id; ?>" class="btn btn-primary">Edit Profile</a>
            <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a> <!-- Back to Dashboard Button -->
        </div>
    </div>
</div>
</body>
</html>
