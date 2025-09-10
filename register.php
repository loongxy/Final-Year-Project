<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = md5($_POST['password']);
    $confirm_password = md5($_POST['cpassword']);
    $user_type = mysqli_real_escape_string($conn, $_POST['user_type']);

    $check_email_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($result) > 0) {
        echo '<script>alert("User already exists!"); window.location.href = "register.php";</script>';
        exit();
    } else {
        if ($password !== $confirm_password) {
            echo '<script>alert("Confirm password not matched!"); window.location.href = "register.php";</script>';
            exit();
        } else {
            $insert_query = "INSERT INTO users (name, email, password, user_type) 
                            VALUES ('$name', '$email', '$password', '$user_type')";
            if (mysqli_query($conn, $insert_query)) {
                echo '<script>alert("Registered Successfully!"); window.location.href = "home.php";</script>';
                exit();
            } else {
                echo '<script>alert("Failed to register. Please try again later."); window.location.href = "register.php";</script>';
                exit();
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    
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

<!-- register -->
<div class="form-container">
    <form action="" method="post">
        <h3>Register Now</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Name: </span>
                <input type="text" name="name" placeholder="Enter your name">
            </div>

            <div class="inputBox">
                <span>Email: </span>
                <input type="email" name="email" placeholder="Enter your email">
            </div>

            <div class="inputBox">
                <span>Password: </span>
                <input type="password" name="password" placeholder="Enter your password">
            </div>

            <div class="inputBox">
                <span>Confirm Password: </span>
                <input type="password" name="cpassword" placeholder="Confirm your password">
            </div>

            <div class="inputBox">
                <span>User: </span>
                <select name="user_type" required>
                    <option value="user" >user</option>
                </select>
            </div>
        </div>

        <input type="submit" name="submit" value="Register" class="btn">
        <p>Already have an account? <a href="login.php">Login Now</a></p>
    </form>
</div>

<!-- js file link -->
 <script src="js/script.js"></script>

</body>
</html>