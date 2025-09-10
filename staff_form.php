<?php
include 'config.php';

$id = '';
$name = '';
$email = '';
$phone = '';
$department = '';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM `staff` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $email = htmlspecialchars($row['email']);
        $phone = isset($row['phone']) && !is_null($row['phone']) ? htmlspecialchars($row['phone']) : '';
        $department = htmlspecialchars($row['department']);
    } else {
        echo '<script>alert("Record not found!"); window.location.href = "staff.php";</script>';
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : '';
    $department = isset($_POST['department']) ? htmlspecialchars($_POST['department']) : '';

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO staff (name, email, phone, department) VALUES (?, ?, ?, ?)");
        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
    
        $stmt->bind_param("ssss", $name, $email, $phone, $department);
    
        if ($stmt->execute()) {
            echo '<script>alert("Staff added successfully!"); window.location.href = "staff.php";</script>';
        } else {
            echo '<script>alert("Error adding staff: ' . htmlspecialchars($stmt->error) . '");</script>';
        }
    } else {
        $stmt = $conn->prepare("UPDATE staff SET name = ?, email = ?, phone = ?, department = ? WHERE id = ?");
        if (!$stmt) {
            die('Prepare failed: ' . htmlspecialchars($conn->error));
        }
    
        $stmt->bind_param("ssssi", $name, $email, $phone, $department, $id);
    
        if ($stmt->execute()) {
            echo '<script>alert("Staff updated successfully!"); window.location.href = "staff.php";</script>';
        } else {
            echo '<script>alert("Error updating staff: ' . htmlspecialchars($stmt->error) . '");</script>';
        }
    }
    
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Staff Form</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/staff.css">
    <!-- font awesome -->
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

<!-- staff form -->
<section class="dashboard">
    <h1 class="heading"><?= empty($id) ? 'Add New Staff' : 'Edit Staff' ?></h1>

    <div class="add-staff">
        <form action="" method="post" class="staff-form">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" name="name" id="name" placeholder="Enter staff name" value="<?= htmlspecialchars($name) ?>" required>
            </div>

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Enter staff email" value="<?= htmlspecialchars($email) ?>" required>
            </div>

            <div class="form-group">
                <label for="phone">Phone</label>
                <input type="text" name="phone" id="phone" placeholder="Enter staff phone number" value="<?= htmlspecialchars($phone) ?>">
            </div>

            <div class="form-group">
                <label for="department">Department</label>
                <select name="department" id="department" required>
                    <option value="" disabled>Select a department</option>
                    <option value="Facial Treatments" <?= $department === 'Facial Treatments' ? 'selected' : '' ?>>Facial Treatments</option>
                    <option value="Body Care" <?= $department === 'Body Care' ? 'selected' : '' ?>>Body Care</option>
                    <option value="Makeup Services" <?= $department === 'Makeup Services' ? 'selected' : '' ?>>Makeup Services</option>
                    <option value="Semi-Permanent Makeup" <?= $department === 'Semi-Permanent Makeup' ? 'selected' : '' ?>>Semi-Permanent Makeup</option>
                </select>
            </div>
            <button type="submit" class="btn"><?= empty($id) ? 'Add Staff' : 'Update Staff' ?></button>
        </form>
    </div>
</section>

<!-- scripts -->
<script src="js/admin_script.js"></script>
</body>
</html>