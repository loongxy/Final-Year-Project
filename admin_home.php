<?php 
session_start();

if (!isset($_SESSION['admin_name'])) {
    header("Location: login.php");
    exit();
}

include 'config.php';

$count_appointment = $conn->prepare("SELECT COUNT(*) as total FROM `appointment`");
$count_appointment->execute();
$result = $count_appointment->get_result();
$total_appointments = $result->fetch_assoc()['total'];
$count_appointment->close();

$count_customer = $conn->prepare("SELECT COUNT(*) as total FROM `users`");
$count_customer->execute();
$result = $count_customer->get_result();
$total_customers = $result->fetch_assoc()['total'];
$count_customer->close();

$count_staff = $conn->prepare("SELECT COUNT(*) as total FROM `staff`");
$count_staff->execute();
$result = $count_staff->get_result();
$total_staffs = $result->fetch_assoc()['total'];
$count_staff->close();

$count_feedback = $conn->prepare("SELECT COUNT(*) as total FROM `feedback`");
$count_feedback->execute();
$result = $count_feedback->get_result();
$total_feedbacks = $result->fetch_assoc()['total'];
$count_feedback->close();

$count_inventory = $conn->prepare("SELECT COUNT(*) as total FROM `inventory`");
$count_inventory->execute();
$result = $count_inventory->get_result();
$total_inventorys = $result->fetch_assoc()['total'];
$count_inventory->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/admin_style.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css">
</head>
<body>
<!-- header -->
<header class="header">
    <div class="flex">
        <a href="admin_home.php" class="logo">Admin.</a>

        <div class="icons">
            <div id="menu-btn" class="bx bx-menu"></div>
            <div id="user-btn" class="bx bxs-user-circle"></div>
        </div>

        <div class="profile">
            <a href="home.php" onclick="return confirm('Logout from this website?');"><span>Logout</span></a>
        </div>
    </div>
</header>

<!-- sidebar -->
<div class="side-bar">
    <div id="close-bar">
        <i class='bx bx-x'></i>
    </div>

    <nav class="navbar">
        <a href="admin_home.php"><i class='bx bxs-home'></i><span>Home</span></a>
        <a href="admin_appointment.php"><i class='bx bxs-calendar'></i><span>Appointment</span></a>
        <a href="customer.php"><i class='bx bxs-user-account'></i><span>Customer</span></a>
        <a href="staff.php"><i class='bx bxs-user'></i><span>Staff</span></a>
        <a href="admin_feedback.php"><i class='bx bxs-message-square-dots'></i><span>Feedback</span></a>
        <a href="inventory.php"><i class='bx bx-package'></i><span>Inventory</span></a>
        <a href="home.php" onclick="return confirm('Logout from this website?');"><i class='bx bx-log-out'></i><span>Logout</span></a>
    </nav>
</div>

<!-- dashboard -->
<section class="dashboard">
    <h1 class="heading">Dashboard</h1>
    <div class="box-container">
        <div class="box">
            <h3><?= $total_appointments; ?></h3>
            <p>Total Appointments</p>
            <a href="admin_appointment.php" class="btn">Add New Appointment</a>
        </div>

        <div class="box">
            <h3><?= $total_customers; ?></h3>
            <p>Total Customers</p>
            <a href="customer.php" class="btn">Add New Customer</a>
        </div>

        <div class="box">
            <h3><?= $total_staffs; ?></h3>
            <p>Total Staff</p>
            <a href="staff.php" class="btn">Add New Staff</a>
        </div>

        <div class="box">
            <h3><?= $total_feedbacks; ?></h3>
            <p>Total Feedbacks</p>
            <a href="admin_feedback.php" class="btn">View Feedback</a>
        </div>

        <div class="box">
            <h3><?= $total_inventorys; ?></h3>
            <p>Total Inventory Items</p>
            <a href="inventory.php" class="btn">Manage Inventory</a>
        </div>
    </div>
</section>

<!-- scripts -->
<script src="js/admin_script.js"></script>
</body>
</html>