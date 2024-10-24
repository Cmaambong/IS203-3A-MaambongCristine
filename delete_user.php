<?php
session_start();
include 'config.php';

// Ensure only admins can delete users
if ($_SESSION['role'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Check if a user ID is provided in the URL
if (isset($_GET['id'])) {
    $user_id = $_GET['id'];

    // Prevent the admin from deleting their own account
    if ($user_id == $_SESSION['user_id']) {
        echo "Error: You cannot delete your own account!";
        exit();
    }

    // Delete the user from the database
    $sql = "DELETE FROM users WHERE id='$user_id'";
    
    if ($conn->query($sql) === TRUE) {
        echo "User deleted successfully!";
        header('Location: admin_dashboard.php'); // Redirect back to the admin dashboard after deletion
    } else {
        echo "Error deleting user: " . $conn->error;
    }
} else {
    echo "No user ID provided!";
}
?>

