<?php
session_start();
include 'config.php';

if ($_SESSION['role'] !== 'user') {
    header('Location: login.php');
}

$user_id = $_SESSION['user_id'];
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);
$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];

    // Handle file upload
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;

        // Update with profile picture
        $sql = "UPDATE users SET username='$username', email='$email', profile_picture='$profile_picture' WHERE id='$user_id'";
    } else {
        // Update without profile picture
        $sql = "UPDATE users SET username='$username', email='$email' WHERE id='$user_id'";
    }

    if ($conn->query($sql) === TRUE) {
        echo "Profile updated!";
    } else {
        echo "Error: " . $conn->error;
    }
}
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
<div class="container">
    <h2>User Profile</h2>
    <form method="POST" action="user_profile.php" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="100">
        </div>
        <button type="submit" class="btn btn-primary">Update Profile</button>
        <a class="nav-link" href="logout.php">Logout</a> <!-- Logout link -->
    </form>
</div>
</body>
</html>
