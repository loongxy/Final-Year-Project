<?php
include 'config.php';

$id = '';
$name = '';
$quantity = 0;
$price = 0;

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM `inventory` WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $name = htmlspecialchars($row['name']);
        $quantity = intval($row['quantity']);
        $price = floatval($row['price']);
    } else {
        echo '<script>alert("Record not found!"); window.location.href = "inventory.php";</script>';
        exit();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name']) : '';
    $quantity = isset($_POST['quantity']) ? intval($_POST['quantity']) : 0;
    $price = isset($_POST['price']) ? floatval($_POST['price']) : 0;

    if (empty($id)) {
        $stmt = $conn->prepare("INSERT INTO `inventory` (name, quantity, price) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $name, $quantity, $price);

        if ($stmt->execute()) {
            echo '<script>alert("Inventory added successfully!"); window.location.href = "inventory.php";</script>';
        } else {
            echo '<script>alert("Error adding inventory!");</script>';
        }
    } else {
        $stmt = $conn->prepare("UPDATE `inventory` SET name = ?, quantity = ?, price = ? WHERE id = ?");
        $stmt->bind_param("sssi", $name, $quantity, $price, $id);

        if ($stmt->execute()) {
            echo '<script>alert("Inventory updated successfully!"); window.location.href = "inventory.php";</script>';
        } else {
            echo '<script>alert("Error updating inventory!");</script>';
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
    <title>Inventory Form</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/inventory.css">
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

<!-- inventory form -->
<section class="dashboard">
    <h1 class="heading"><?= empty($id) ? 'Add New Product' : 'Edit Product' ?></h1>

    <div class="add-inventory">
        <form action="" method="post" class="inventory-form">
            <input type="hidden" name="id" value="<?= htmlspecialchars($id) ?>">

            <div class="form-group">
                <label for="name">Product Name</label>
                <input type="text" name="name" id="name" placeholder="Enter product name" value="<?= htmlspecialchars($name) ?>" required>
            </div>

            <div class="form-group">
                <label for="quantity">Quantity</label>
                <input type="number" name="quantity" id="quantity" placeholder="Enter quantity" value="<?= htmlspecialchars($quantity) ?>" min="0" required>
            </div>

            <div class="form-group">
                <label for="price">Price (RM)</label>
                <input type="number" step="0.01" name="price" id="price" placeholder="Enter price" value="<?= htmlspecialchars($price) ?>" min="0" required>
            </div>
            <button type="submit" class="btn"><?= empty($id) ? 'Add Product' : 'Update Product' ?></button>
        </form>
    </div>
</section>

<!-- scripts -->
<script src="js/admin_script.js"></script>
</body>
</html>