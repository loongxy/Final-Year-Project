<?php
include 'config.php';

$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($current_page - 1) * $records_per_page;

$total_records_query = "SELECT COUNT(*) as total FROM feedback";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];

$total_pages = ceil($total_records / $records_per_page);

$query = "SELECT * FROM feedback ORDER BY created_at DESC LIMIT $start, $records_per_page";
$feedbacks = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Feedback</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/admin_feedback.css">
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

<!-- feedback -->
<section class="dashboard">
    <h1 class="heading">Feedback</h1>

    <div class="feedback-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Feedback</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($feedbacks->num_rows > 0): ?>
                    <?php while ($row = $feedbacks->fetch_assoc()): ?>
                        <?php
                        $name = isset($row['name']) ? htmlspecialchars($row['name']) : 'N/A';
                        $email = isset($row['email']) ? htmlspecialchars($row['email']) : 'N/A';
                        $message = isset($row['feedback']) && !is_null($row['feedback']) ? htmlspecialchars($row['feedback']) : 'No message provided';
                        $created_at = isset($row['created_at']) ? htmlspecialchars($row['created_at']) : 'N/A';
                        $feedback_id = htmlspecialchars($row['id']);
                        ?>
                        <tr>
                            <td><?= $feedback_id ?></td>
                            <td><?= $name ?></td>
                            <td><?= $email ?></td>
                            <td><?= $message ?></td>
                            <td><?= $created_at ?></td>
                            <td>
                                <a href="delete_feedback.php?id=<?= $feedback_id ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this feedback?')">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No feedback found</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <div class="pagination">
        <?php if ($current_page > 1): ?>
            <a href="?page=<?= $current_page - 1 ?>">Previous</a>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <a href="?page=<?= $i ?>" <?= ($i == $current_page) ? 'class="active"' : '' ?>>
                <?= $i ?>
            </a>
        <?php endfor; ?>

        <?php if ($current_page < $total_pages): ?>
            <a href="?page=<?= $current_page + 1 ?>">Next</a>
        <?php endif; ?>
    </div>
</section>

<!-- scripts -->
<script src="js/admin_script.js"></script>
</body>
</html>