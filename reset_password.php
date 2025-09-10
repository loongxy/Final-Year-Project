<?php
session_start();
include 'config.php';

if (!isset($_GET['token'])) {
    die('Invalid token.');
}

$token = mysqli_real_escape_string($conn, $_GET['token']);
$query = "SELECT * FROM users WHERE reset_token = '$token' AND token_expiry > NOW()";
$result = mysqli_query($conn, $query);

if (mysqli_num_rows($result) === 0) {
    die('Token expired or invalid.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['confirm_password']);

    if ($password !== $confirm_password) {
        echo '<script>alert("Passwords do not match."); window.location.href = "reset_password.php?token=' . htmlspecialchars($_GET['token']) . '";</script>';
        exit();
    } else {
        $update_password_query = "UPDATE users SET password = '$password', reset_token = NULL, token_expiry = NULL WHERE reset_token = '$token'";
        mysqli_query($conn, $update_password_query);

        echo '<script>alert("Your password has been reset successfully."); window.location.href = "login.php";</script>';
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
    <!-- font awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- CSS file link -->
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<!-- header section -->
<section class="header">
    <a href="home.php" class="logo">Perfection.</a>

    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="login.php">Login</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</section>

<div class="form-container">
    <form action="" method="post">
        <h3>Reset Password</h3>
        <div class="flex">
            <div class="inputBox">
                <span>New Password: </span>
                <input type="password" name="password" placeholder="Enter new password" required>
            </div>
            <div class="inputBox">
                <span>Confirm Password: </span>
                <input type="password" name="confirm_password" placeholder="Confirm new password" required>
            </div>
        </div>
        <input type="submit" value="Reset Password" class="btn">
    </form>
</div>
</body>
</html>