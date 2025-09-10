<?php
include 'config.php';

$id = '';
$name = '';
$email = '';
$phone = '';
$service = '';
$date = '';
$time = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM `appointment` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $email = htmlspecialchars($row['email']);
        $phone = htmlspecialchars($row['phone']);
        $service = htmlspecialchars($row['service']);
        $date = htmlspecialchars($row['date']);
        $time = htmlspecialchars($row['time']);
    } else {
        echo '<script>alert("Record not found!"); window.location.href = "admin_appointment.php";</script>';
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $service = $_POST['service'];
    $date = $_POST['date'];
    $time = $_POST['time'];

    $check_unique_query = "SELECT COUNT(*) as count FROM appointment WHERE date = ? AND time = ? AND service = ?";
    $stmt_check = $conn->prepare($check_unique_query);
    $stmt_check->bind_param("sss", $date, $time, $service);
    $stmt_check->execute();
    $result_check = $stmt_check->get_result();
    $count = $result_check->fetch_assoc()['count'];

    if ($count > 0 && (!empty($id) || empty($id))) {
        echo '<script>alert("This appointment already exists for the selected date, time, and service.");</script>';
    } else {
        if (empty($id)) {
            $stmt_insert = $conn->prepare("INSERT INTO `appointment` (name, email, phone, service, date, time) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt_insert->bind_param("sssssss", $name, $email, $phone, $service, $date, $time);

            if ($stmt_insert->execute()) {
                echo '<script>alert("Appointment added successfully!"); window.location.href = "admin_appointment.php";</script>';
            } else {
                echo '<script>alert("Error adding appointment!");</script>';
            }
        } else {
            $stmt_update = $conn->prepare("UPDATE `appointment` SET name = ?, email = ?, phone = ?, service = ?, date = ?, time = ? WHERE id = ?");
            $stmt_update->bind_param("sssssssi", $name, $email, $phone, $service, $date, $time, $id);

            if ($stmt_update->execute()) {
                echo '<script>alert("Appointment updated successfully!"); window.location.href = "admin_appointment.php";</script>';
            } else {
                echo '<script>alert("Error updating appointment!");</script>';
            }
        }
    }

    $stmt_check->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/admin_appointment.css">
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

<!-- appointment form -->
<section class="dashboard">
    <h1 class="heading"><?= empty($id) ? 'Add New Appointment' : 'Edit Appointment' ?></h1>

    <div class="add-appointment">
        <form action="" method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" placeholder="Enter your name" value="<?= $name ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" placeholder="Enter your email" value="<?= $email ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="tel" name="phone" placeholder="Enter your phone" value="<?= $phone ?>">
            </div>

            <div class="form-group">
                <label for="service">Service</label>
                <select name="service" required>
                    <option value="" disabled>Select a service</option>
                    <optgroup label="Facial Treatments">
                        <option value="Deep Cleansing" <?= $service === 'Deep Cleansing' ? 'selected' : '' ?>>Deep Cleansing</option>
                        <option value="Hydration & Moisturizing" <?= $service === 'Hydration & Moisturizing' ? 'selected' : '' ?>>Hydration & Moisturizing</option>
                        <option value="Collagen Care Treatment" <?= $service === 'Collagen Care Treatment' ? 'selected' : '' ?>>Collagen Care Treatment</option>
                        <option value="Whitening & Spot Removal" <?= $service === 'Whitening & Spot Removal' ? 'selected' : '' ?>>Whitening & Spot Removal</option>
                        <option value="Hydrating Injections" <?= $service === 'Hydrating Injections' ? 'selected' : '' ?>>Hydrating Injections</option>
                    </optgroup>
                    <optgroup label="Body Care">
                        <option value="Full-Body Essential Oil Massage" <?= $service === 'Full-Body Essential Oil Massage' ? 'selected' : '' ?>>Full-Body Essential Oil Massage</option>
                        <option value="Lymphatic Detox Massage" <?= $service === 'Lymphatic Detox Massage' ? 'selected' : '' ?>>Lymphatic Detox Massage</option>
                        <option value="Sea Salt Scrub" <?= $service === 'Sea Salt Scrub' ? 'selected' : '' ?>>Sea Salt Scrub</option>
                        <option value="Honey Exfoliation" <?= $service === 'Honey Exfoliation' ? 'selected' : '' ?>>Honey Exfoliation</option>
                        <option value="Hand Care" <?= $service === 'Hand Care' ? 'selected' : '' ?>>Hand Care</option>
                    </optgroup>
                    <optgroup label="Makeup Services">
                        <option value="Daily Makeup" <?= $service === 'Daily Makeup' ? 'selected' : '' ?>>Daily Makeup</option>
                        <option value="Bridal Makeup" <?= $service === 'Bridal Makeup' ? 'selected' : '' ?>>Bridal Makeup</option>
                        <option value="Event Makeup" <?= $service === 'Event Makeup' ? 'selected' : '' ?>>Event Makeup</option>
                        <option value="Stage Makeup" <?= $service === 'Stage Makeup' ? 'selected' : '' ?>>Stage Makeup</option>
                    </optgroup>
                    <optgroup label="Semi-Permanent Makeup">
                        <option value="Eyebrow Microblading" <?= $service === 'Eyebrow Microblading' ? 'selected' : '' ?>>Eyebrow Microblading</option>
                        <option value="Eyeliner Tattoo" <?= $service === 'Eyeliner Tattoo' ? 'selected' : '' ?>>Eyeliner Tattoo</option>
                        <option value="Lip Blush Tattoo" <?= $service === 'Lip Blush Tattoo' ? 'selected' : '' ?>>Lip Blush Tattoo</option>
                    </optgroup>
                </select>
            </div>

            <div class="form-group">
                <label for="date">Date</label>
                <input type="date" name="date" value="<?= $date ?>" required>
            </div>

            <div class="form-group">
                <label for="time">Time</label>
                <input type="time" name="time" min="09:00" max="18:00" value="<?= $time ?>" required>
            </div>

            <button type="submit" class="btn"><?= empty($id) ? 'Add Appointment' : 'Update Appointment' ?></button>
        </form>
    </div>
</section>

<!-- scripts -->
<script src="js/admin_script.js"></script>
</body>
</html>