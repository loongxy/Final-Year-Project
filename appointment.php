<?php
session_start();
include 'config.php';

if (!isset($_SESSION['appointment'])) {
    $_SESSION['appointment'] = [];
}

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $service = trim($_POST['service']);
    $date = trim($_POST['date']);
    $time = trim($_POST['time']);

    $errors = [];

    if (empty($name)) $errors[] = 'Name is required!';
    if (empty($email)) $errors[] = 'Email is required!';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format!';
    if (empty($phone)) $errors[] = 'Phone is required!';
    if (empty($service)) $errors[] = 'Service is required!';
    if (empty($date)) $errors[] = 'Date is required!';
    if (empty($time)) $errors[] = 'Time is required!';

    $time_obj = DateTime::createFromFormat('H:i', $time);
    if ($time_obj < new DateTime('09:00') || $time_obj > new DateTime('18:00')) {
        $errors[] = 'Appointment time must be between 9:00 AM and 6:00 PM';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("SELECT id FROM `appointment` 
                                WHERE `date` = ? 
                                AND `time` = ?
                                AND `service` = ?");
        $stmt->bind_param("sss", $date, $time, $service);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            echo '<script>alert("This time slot for ' . $service . ' is already booked!"); window.location.href = "home.php";</script>';
        } else {
            $stmt = $conn->prepare("INSERT INTO appointment (name, email, phone, service, date, time, created_at) VALUES(?, ?, ?, ?, ?, ?, NOW())");
            $stmt->bind_param("ssssss", $name, $email, $phone, $service, $date, $time);

            if ($stmt->execute()) {
                echo '<script>alert("Appointment submitted successfully!"); window.location.href = "appointment.php";</script>';
            } else {
                echo '<script>alert("Error submitting appointment!");</script>';
            }
            $stmt->close();

            $mail = new PHPMailer(true);
            try {
                $mail->isSMTP();
                $mail->Host = 'smtp.gmail.com';
                $mail->SMTPAuth = true;
                $mail->Username = 'loongxingyi@gmail.com'; 
                $mail->Password = 'hhce pqip ulmm zmnh'; 
                $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
                $mail->Port = 587;

                $mail->setFrom('bookings@perfection.com', 'Perfection Beauty');
                $mail->addAddress($email, $name);
                $mail->isHTML(true);
                $mail->Subject = 'Appointment Confirmation';
                $mail->Body = "
                    <html>
                    <head>
                        <style>
                            body { font-family: Arial, sans-serif; }
                            .header { color: rgb(66, 88, 176); font-size: 24px; margin-bottom: 15px; }
                            .details { margin: 15px 0; padding: 15px; background:rgb(198, 198, 198); }
                        </style>
                    </head>
                    <body>
                        <h2 class='header'>Your Beauty Appointment is Confirmed!</h2>
                        <div class='details'>
                            <p><strong>Customer:</strong> $name</p>
                            <p><strong>Service:</strong> $service</p>
                            <p><strong>Date:</strong> $date</p>
                            <p><strong>Time:</strong> $time</p>
                            <p><strong>Contact:</strong> $phone</p>
                        </div>
                        <p>We look forward to serving you. Please arrive 10 minutes before your appointment!</p>
                    </body>
                    </html>
                ";
                $mail->send();
            } catch (Exception $e) {
                echo '<script>alert("Appointment submitted but email could not be sent.");</script>';
            }
        }
    } else {
        foreach ($errors as $error) {
            echo '<script>alert("' . $error . '");</script>';
        }
        header('Location: appointment.php');
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
    <title>Appointment</title>
    <!-- swiper CSS link -->
    <link rel="stylesheet" href="https://unpkg.com/swiper@7/swiper-bundle.min.css" />
    <!-- font awesome CDN link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- custom CSS file link -->
    <link rel="stylesheet" href="css/appointment.css">
</head>
<body>

<!-- header section -->
<section class="header">
    <a href="home.php" class="logo">Perfection.</a>
    <nav class="navbar">
        <a href="home.php">Home</a>
        <a href="service.php">Service</a>
        <a href="appointment.php">Appointment</a>
        <a href="package.php">Package</a>
        <a href="about.php">AboutUs</a>
        <a href="login.php">Login</a>
    </nav>
    <div id="menu-btn" class="fas fa-bars"></div>
</section>

<div class="heading" style="background: url(img/h3.png) no-repeat">
    <h1>Appointment</h1>
</div>

<!-- appointment form -->
<section class="appointment">
    <h1 class="heading-title">Book Your Beauty!</h1>
    <form action="appointment.php" method="post" class="appointment-form">
        <div class="flex">
            <div class="inputBox">
                <span>Name: </span>
                <input type="text" placeholder="Enter your name" name="name" required>
            </div>
            <div class="inputBox">
                <span>Email: </span>
                <input type="email" placeholder="Enter your email" name="email" required>
            </div>
            <div class="inputBox">
                <span>Phone: </span>
                <input type="tel" placeholder="Enter your number" name="phone" required>
            </div>
            <div class="inputBox">
                <span>Service: </span>
                <select name="service" required>
                    <option value="" disabled selected>Select a service</option>
                    <optgroup label="Facial Treatments">
                        <option value="Deep Cleansing">Deep Cleansing</option>
                        <option value="Hydration & Moisturizing">Hydration & Moisturizing</option>
                        <option value="Collagen Care Treatment">Collagen Care Treatment</option>
                        <option value="Whitening & Spot Removal">Whitening & Spot Removal</option>
                        <option value="Hydrating Injections">Hydrating Injections</option>
                    </optgroup>
                    <optgroup label="Body Care">
                        <option value="Full-Body Essential Oil Massage">Full-Body Essential Oil Massage</option>
                        <option value="Lymphatic Detox Massage">Lymphatic Detox Massage</option>
                        <option value="Sea Salt Scrub">Sea Salt Scrub</option>
                        <option value="Honey Exfoliation">Honey Exfoliation</option>
                        <option value="Hand Care">Hand Care</option>
                    </optgroup>
                    <optgroup label="Makeup Services">
                        <option value="Daily Makeup">Daily Makeup</option>
                        <option value="Bridal Makeup">Bridal Makeup</option>
                        <option value="Event Makeup">Event Makeup</option>
                        <option value="Stage Makeup">Stage Makeup</option>
                    </optgroup>
                    <optgroup label="Semi-Permanent Makeup">
                        <option value="Eyebrow Microblading">Eyebrow Microblading</option>
                        <option value="Eyeliner Tattoo">Eyeliner Tattoo</option>
                        <option value="Lip Blush Tattoo">Lip Blush Tattoo</option>
                    </optgroup>
                </select>
            </div>
            <div class="inputBox">
                <span>Date: </span>
                <input type="date" name="date" id="date" min="<?php echo date('Y-m-d'); ?>" required>
            </div>
            <div class="inputBox">
                <span>Time: </span>
                <input type="time" name="time" id="time" min="09:00" max="18:00" required>
                <small>Business hours: 9:00 - 18:00</small>
            </div>
        </div>
        <input type="submit" value="Book Appointment" class="btn" name="send">
    </form>
</section>

<!-- footer section -->
<section class="footer">
    <div class="box-container">
        <div class="box">
            <h3>Quick Links</h3>
            <a href="home.php"> <i class="fas fa-angle-right"></i> Home</a>
            <a href="service.php"> <i class="fas fa-angle-right"></i> Service</a>
            <a href="appointment.php"> <i class="fas fa-angle-right"></i> Appointment</a>
            <a href="package.php"> <i class="fas fa-angle-right"></i> Package</a>
            <a href="about.php"> <i class="fas fa-angle-right"></i> AboutUs</a>
        </div>
        <div class="box">
            <h3>Contact Info</h3>
            <a href="#"> <i class="fas fa-phone"></i> +60 12 772 8939 </a>
            <a href="#"> <i class="fas fa-envelope"></i> perfection@gmail.com </a>
            <a href="https://www.google.com/maps/place/12,+Jalan+Maju+Timur,+Taman+Maju,+Batu+Pahat,+Malaysia" target="_blank">
                <i class="fas fa-map"></i> 
                12, Jalan Maju Timur, Taman Maju, Batu Pahat, Malaysia
            </a>
        </div>
    </div>
</section>

<!-- swiper JS link -->
<script src="https://unpkg.com/swiper@7/swiper-bundle.min.js"></script>
<!-- custom JS file link -->
<script src="js/script.js"></script>
</body>
</html>