<?php
session_start();
include 'config.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_POST['email'])) {
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $query = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $query);
    mysqli_stmt_bind_param($stmt, 's', $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $user_id = $row['id'];
        $user_name = $row['name'];

        $token = bin2hex(random_bytes(16));
        $expiry_time = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $update_token_query = "UPDATE users SET reset_token = ?, token_expiry = ? WHERE id = ?";
        $stmt_update = mysqli_prepare($conn, $update_token_query);
        mysqli_stmt_bind_param($stmt_update, 'ssi', $token, $expiry_time, $user_id);
        mysqli_stmt_execute($stmt_update);
        mysqli_stmt_close($stmt_update);

        try {
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'loongxingyi@gmail.com';
            $mail->Password = 'hhce pqip ulmm zmnh';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
            $mail->Port = 587;
            $mail->setFrom('noreply@perfection.com', 'Perfection Beauty');
            $mail->addAddress($email, $user_name);
            $mail->isHTML(true);
            $mail->Subject = 'Password Reset Request';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .header { color: #4CAF50; font-size: 24px; margin-bottom: 15px; }
                        .details { margin: 15px 0; padding: 15px; background: #f8f9fa; }
                    </style>
                </head>
                <body>
                    <h2 class='header'>Password Reset Request</h2>
                    <div class='details'>
                        <p>Hello $user_name,</p>
                        <p>You have requested to reset your password. Please click the link below to reset it:</p>
                        <p><a href='http://localhost/fyp/reset_password.php?token=$token'>Reset Password</a></p>
                        <p>This link will expire in 1 hour.</p>
                    </div>
                    <p>If you did not request this, please ignore this email.</p>
                </body>
                </html>
            ";
            $mail->send();

            echo '<script>alert("A password reset link has been sent to your email."); window.location.href = "login.php";</script>';
            exit();
        } catch (Exception $e) {
            echo '<script>alert("Failed to send the reset email. Please try again later."); window.location.href = "forgot_password.php";</script>';
            exit();
        }
    } else {
        echo '<script>alert("The email address does not exist in our system."); window.location.href = "forgot_password.php";</script>';
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
    <title>Forgot Password</title>
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
        <h3>Forgot Password</h3>
        <div class="flex">
            <div class="inputBox">
                <span>Email: </span>
                <input type="email" name="email" placeholder="Enter your email" required>
            </div>
        </div>
        <input type="submit" value="Send Reset Link" class="btn">
    </form>
</div>
</body>
</html>