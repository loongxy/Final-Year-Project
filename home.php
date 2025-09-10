<?php
session_start();

if (!isset($_SESSION['user_name'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

if (!isset($_SESSION['feedback'])) {
    $_SESSION['feedback'] = [];
}

require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/Exception.php';
require 'phpmailer/src/SMTP.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['send'])) {
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $feedback = trim($_POST['feedback']);

    $errors = [];
    if (empty($name)) $errors[] = 'Name is required!';
    if (empty($email)) $errors[] = 'Email is required!';
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Invalid email format!';
    if (empty($feedback)) $errors[] = 'Feedback is required!';

    if (empty($errors)) {
        $stmt = $conn->prepare("INSERT INTO feedback (name, email, feedback, created_at) VALUES (?, ?, ?, NOW())");
        $stmt->bind_param("sss", $name, $email, $feedback);

        if ($stmt->execute()) {
            echo '<script>alert("Your feedback has been submitted successfully!"); window.location.href = "home.php";</script>';
        } else {
            echo '<script>alert("Error submitting feedback!");</script>';
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
            $mail->Subject = 'Thank You for Your Feedback!';
            $mail->Body = "
                <html>
                <head>
                    <style>
                        body { font-family: Arial, sans-serif; }
                        .header { color:rgb(66, 88, 176); font-size: 24px; margin-bottom: 15px; }
                        .details { margin: 15px 0; padding: 15px; background: rgb(198, 198, 198); }
                    </style>
                </head>
                <body>
                    <h2 class='header'>Your Feedback!</h2>
                    <div class='details'>
                        <p><strong>Name:</strong> $name</p>
                        <p><strong>Feedback:</strong> $feedback</p>
                    </div>
                    <p>Thanks for the feedback!</p>
                </body>
                </html>
            ";
            $mail->send();
        } catch (Exception $e) {
            echo '<script>alert("Feedback submitted but email could not be sent.");</script>';
        }

        header('Location: home.php');
        exit();
    } else {
        foreach ($errors as $error) {
            echo '<script>alert("' . $error . '");</script>';
        }
        header('Location: home.php');
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
    <title>Home</title>
    
    <!-- swiper css link -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <!-- font aweson cdn link -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <!-- css file link -->
    <link rel="stylesheet" href="css/style.css">
</head>

<body>
<!-- header -->
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

<!-- home -->
 <section class="home">
    <div class="swiper home-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide slide" style="background: url(img/p1.png) no-repeat">
                <div class="content">
                    <span>Glow, Enhance, Radiate</span>
                    <h3>Beauty Without Borders</h3>
                    <a href="package.php" class="btn">Discover More</a>
                </div>
            </div>

            <div class="swiper-slide slide" style="background: url(img/p2.png) no-repeat">
                <div class="content">
                    <span>Glow, Enhance, Radiate</span>
                    <h3>Glow Everywhere You Go</h3>
                    <a href="package.php" class="btn">Discover More</a>
                </div>
            </div>

            <div class="swiper-slide slide" style="background: url(img/p3.png) no-repeat">
                <div class="content">
                    <span>Glow, Enhance, Radiate</span>
                    <h3>Bringing Beauty to Every Destination</h3>
                    <a href="package.php" class="btn">Discover More</a>
                </div>
            </div>
        </div>
        <div class="swiper-button-next"></div>
        <div class="swiper-button-prev"></div>
    </div>
 </section>

<!-- home service -->
<section class="home-service">
    <h1 class="heading-title">Our Services</h1>
    <div class="box-container">
        <div class="box">
            <img src="img/s1.png" alt="" width="90px" height="110px">
            <h3>Facial Treatments</h3>
        </div>

        <div class="box">
            <img src="img/s2.png" alt="" width="90px" height="110px">
            <h3>Body Care</h3>
        </div>

        <div class="box">
            <img src="img/s3.png" alt="" width="110px" height="110px">
            <h3>Makeup Services</h3>
        </div>

        <div class="box">
            <img src="img/s4.png" alt="" width="100px" height="140px">
            <h3>Semi-Permanent Makeup</h3>
        </div>
    </div>
    <div class="load-more"><a href="service.php" class="btn">Load More</a></div>
</section>

<!-- home package -->
<section class="home-package">
    <h1 class="heading-title">Our Packages</h1>
    <div class="box-container">
        <div class="box">
            <div class="image">
                <img src="img/b4.png" alt="">
            </div>
            <div class="content">
                <h3>Beauty & Glow</h3>
                <p>Glow and radiance meet beauty and elegance. Confidence, grace!</p>
                <a href="appointment.php" class="btn">Book Now</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="img/m2.png" alt="">
            </div>
            <div class="content">
                <h3>Beauty & Glow</h3>
                <p>Glow and radiance meet beauty and elegance. Confidence, grace!</p>
                <a href="appointment.php" class="btn">Book Now</a>
            </div>
        </div>

        <div class="box">
            <div class="image">
                <img src="img/m6.png" alt="">
            </div>
            <div class="content">
                <h3>Beauty & Glow</h3>
                <p>Glow and radiance meet beauty and elegance. Confidence, grace!</p>
                <a href="appointment.php" class="btn">Book Now</a>
            </div>
        </div>
    </div>
    <div class="load-more"><a href="service.php" class="btn">Load More</a></div>
</section>

<!-- home offer -->
 <section class="home-offer">
    <div class="content">
        <h3>upto 30% off</h3>
        <p>Glow and radiance embrace beauty and elegance. Confidence shines, and grace lasts forever!</p>
        <a href="package.php" class="btn">Book Now</a>
    </div>
 </section>

<!-- home about -->
 <section class="home-about">
    <div class="image">
        <img src="img/a1.png" alt="">
    </div>

    <div class="content">
        <h3>About Us</h3>
        <p>At Perfection Salon, we believe that beauty is an art. Our expert team provides top-quality skincare, and wellness treatments to help you look and feel your best. With premium products and personalized care, we create a luxurious and relaxing experience just for you.</p>
        <a href="about.php" class="btn">Read More</a>
    </div>
 </section>

<!-- feedback form -->
<section class="feedback">
    <h1 class="heading-title">Feedback Form</h1>

    <form action="home.php" method="post" class="feedback-form">
        <div class="flex">
            <div class="inputBox">
                <span>Name: </span>
                <input type="text" placeholder="Enter your name" name="name" required
                       value="<?php echo isset($_SESSION['old_input']['name']) ? htmlspecialchars($_SESSION['old_input']['name']) : ''; ?>">
                <?php unset($_SESSION['old_input']['name']); ?>
            </div>

            <div class="inputBox">
                <span>Email: </span>
                <input type="email" placeholder="Enter your email" name="email" required
                       value="<?php echo isset($_SESSION['old_input']['email']) ? htmlspecialchars($_SESSION['old_input']['email']) : ''; ?>">
                <?php unset($_SESSION['old_input']['email']); ?>
            </div>

            <div class="inputBox">
                <span>Feedback: </span>
                <textarea placeholder="Fill in your feedback" name="feedback" required><?php 
                    echo isset($_SESSION['old_input']['feedback']) ? htmlspecialchars($_SESSION['old_input']['feedback']) : ''; 
                    unset($_SESSION['old_input']['feedback']);
                ?></textarea>
            </div>
        </div>
        <input type="submit" value="Submit" class="btn" name="send">
    </form>
</section>

<!-- review -->
<section class="review">
    <h1 class="heading-title">Reviews</h1>
    <div class="swiper review-slider">
        <div class="swiper-wrapper">
            <?php
            $stmt = $conn->prepare("SELECT name, feedback FROM feedback ORDER BY created_at DESC");
            $stmt->execute();
            $result = $stmt->get_result();
            $reviews = $result->fetch_all(MYSQLI_ASSOC);
            $stmt->close();

            if (!empty($reviews)):
                foreach ($reviews as $feedback): ?>
                    <div class="swiper-slide slide">
                        <h3><?php echo htmlspecialchars($feedback['name']); ?></h3>
                        <p><?php echo htmlspecialchars($feedback['feedback']); ?></p>
                    </div>
                <?php endforeach;
            else:
                ?>
                <div class="swiper-slide slide">
                    <h3>No Reviews Yet</h3>
                    <p>Be the first to leave a review!</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- footer -->
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

<!-- swiper js link -->
<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<!-- js file link -->
 <script src="js/script.js"></script>

</body>
</html>