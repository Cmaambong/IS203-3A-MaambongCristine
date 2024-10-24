<?php
session_start();
include 'config.php';

// Check if the user is logged in and has the right to edit
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Admin can edit any user, while regular users can only edit their own profile
$user_id = isset($_GET['id']) ? $_GET['id'] : $_SESSION['user_id']; // Admin can use `id` from URL, users use their own session ID

// Fetch user data
$sql = "SELECT * FROM users WHERE id='$user_id'";
$result = $conn->query($sql);

if ($result->num_rows == 0) {
    echo "No user found!";
    exit();
}

$user = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Capture updated data from the form
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : $user['password'];
    
    // Handle profile picture upload (if provided)
    if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] == 0) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);
        move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file);
        $profile_picture = $target_file;
    } else {
        // If no new image is uploaded, keep the existing one
        $profile_picture = $user['profile_picture'];
    }

    // Update user data
    $sql = "UPDATE users SET 
            username='$username', 
            email='$email', 
            password='$password', 
            profile_picture='$profile_picture'
            WHERE id='$user_id'";

    if ($conn->query($sql) === TRUE) {
        echo "User updated successfully!";
    } else {
        echo "Error updating user: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container">
    <h2>Edit User</h2>
    <form method="POST" action="edit_user.php?id=<?php echo $user_id; ?>" enctype="multipart/form-data">
        <div class="mb-3">
            <label>Username</label>
            <input type="text" name="username" class="form-control" value="<?php echo $user['username']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="<?php echo $user['email']; ?>" required>
        </div>
        <div class="mb-3">
            <label>Password (Leave blank if you don't want to change it)</label>
            <input type="password" name="password" class="form-control" placeholder="New Password">
        </div>
        <div class="mb-3">
            <label>Profile Picture</label>
            <input type="file" name="profile_picture" class="form-control">
            <img src="<?php echo $user['profile_picture']; ?>" alt="Profile Picture" width="100">
        </div>
        <button type="submit" class="btn btn-primary">Update User</button>
    </form>
</div>
</body>
</html>
