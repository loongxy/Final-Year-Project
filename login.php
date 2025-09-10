<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']); 

    $select_users = mysqli_query($conn, "SELECT * FROM users WHERE email = '$email' AND password = '$password'")
        or die('Query failed: ' . mysqli_error($conn));

    if (mysqli_num_rows($select_users) > 0) {
        $row = mysqli_fetch_assoc($select_users);

        if ($row['user_type'] == 'admin') {
            $_SESSION['admin_name'] = $row['name'];
            $_SESSION['admin_email'] = $row['email'];
            $_SESSION['admin_id'] = $row['id'];
            echo '<script>alert("Login successful!"); window.location.href = "admin_home.php";</script>';
            exit();
        } elseif ($row['user_type'] == 'user') {
            $_SESSION['user_name'] = $row['name'];
            $_SESSION['user_email'] = $row['email'];
            $_SESSION['user_id'] = $row['id'];
            echo '<script>alert("Login successful!"); window.location.href = "home.php";</script>';
            exit();
        }
    } else {
        echo '<script>alert("Incorrect email or password!"); window.location.href = "login.php";</script>';
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
    <title>Login</title>
    
    <!-- font aweson cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<!-- header section -->
<section class="header">
    <a href="home.php" class="logo">Perfection.</a>

    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="register.php">Register</a>
        <a href="login.php">Login</a>
    </nav>

    <div id="menu-btn" class="fas fa-bars"></div>
</section>

<!-- login -->
<div class="form-container">
    <form action="" method="post">
        <h3>Login Now</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Email: </span>
                <input type="email" name="email" placeholder="Enter your email">
            </div>

            <div class="inputBox">
                <span>Password: </span>
                <input type="password" name="password" placeholder="Enter your password">
            </div>

        <input type="submit" name="submit" value="Login" class="btn">
        <p><a href="forgot_password.php">Forgot Password?</a></p>
        <p>Don't have an account? <a href="register.php">Register Now</a></p>
    </form>
</div>