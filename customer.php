<?php
session_start();
include 'config.php';

require_once('tcpdf/tcpdf.php');

if (isset($_GET['export_pdf'])) {
    $selected_month = $_GET['month'] ?? date('Y-m');
    $year = substr($selected_month, 0, 4);
    $month = substr($selected_month, 5, 2);

    $query = "SELECT * FROM users 
              WHERE user_type = 'user' 
              AND DATE_FORMAT(created_at, '%Y-%m') = '$selected_month' 
              ORDER BY created_at ASC";
    $customers = $conn->query($query);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Perfection Beauty');
    $pdf->SetTitle('Monthly Customer Report - ' . $selected_month);
    $pdf->SetSubject('Customer Report');
    $pdf->SetKeywords('Customers, Report, PDF');

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', '', 10);

    $html = '<h1 style="text-align:center;">Monthly Customer Report - ' . $selected_month . '</h1>';
    $html .= '<p style="text-align:center; font-size:18px;">Total Customers Joined This Month: ' . $customers->num_rows . '</p>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background-color:#f2f2f2;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Join Date</th>
                    </tr>
                </thead>
                <tbody>';

    if ($customers->num_rows > 0) {
        while ($row = $customers->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['created_at']) . '</td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="4" style="text-align:center;">No customers joined in ' . $selected_month . '</td></tr>';
    }

    $html .= '</tbody></table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('monthly_customer_report_' . $selected_month . '.pdf', 'D'); // 'D' 表示直接下载
    exit;
}

$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($current_page - 1) * $records_per_page;

$total_records_query = "SELECT COUNT(*) as total FROM users WHERE user_type = 'user'";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];

$total_pages = ceil($total_records / $records_per_page);

$query = "SELECT * FROM users WHERE user_type = 'user' ORDER BY name ASC LIMIT $start, $records_per_page";
$customers = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customers</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/customer.css">
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

<!-- customer -->
<section class="dashboard">
    <h1 class="heading">Customers</h1>

    <div class="add-customer">
        <form method="GET" style="display:flex; align-items:center;">
            <label for="month" style="margin-right:10px;">Select Month:</label>
            <input type="month" id="month" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : date('Y-m') ?>" required>
            <button type="submit" name="export_pdf" value="1" class="btn" style="margin-left:10px;">Export to PDF</button>
        </form>
        <a href="customer_form.php" class="btn">Add New Customer</a>
    </div>

    <div class="customer-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($customers->num_rows > 0): ?>
                    <?php while ($row = $customers->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row['id']); ?></td>
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['email']); ?></td>
                            <td>
                                <a href="customer_form.php?id=<?= htmlspecialchars($row['id']) ?>" class="option-btn">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <a href="delete_customer.php?id=<?= htmlspecialchars($row['id']) ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this customer?')">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="6">No customers found</td></tr>
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