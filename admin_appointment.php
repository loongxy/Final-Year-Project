<?php
session_start();
include 'config.php';

require_once('tcpdf/tcpdf.php');

if (isset($_GET['export_pdf'])) {
    $selected_month = $_GET['month'] ?? date('Y-m');
    $year = substr($selected_month, 0, 4);
    $month = substr($selected_month, 5, 2);

    $query = "SELECT * FROM appointment 
              WHERE DATE_FORMAT(date, '%Y-%m') = '$selected_month' 
              ORDER BY date ASC, time ASC";
    $bookings = $conn->query($query);

    $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

    $pdf->SetCreator(PDF_CREATOR);
    $pdf->SetAuthor('Perfection Beauty');
    $pdf->SetTitle('Monthly Booking Report - ' . $selected_month);
    $pdf->SetSubject('Booking Report');
    $pdf->SetKeywords('Bookings, Report, PDF');

    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);

    $pdf->AddPage();

    $pdf->SetFont('helvetica', '', 10);

    $html = '<h1 style="text-align:center;">Monthly Booking Report - ' . $selected_month . '</h1>';
    $html .= '<p style="text-align:center; font-size:18px;">Total Bookings This Month: ' . $bookings->num_rows . '</p>';
    $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
                <thead>
                    <tr style="background-color:#f2f2f2;">
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Service</th>
                        <th>Date</th>
                        <th>Time</th>
                    </tr>
                </thead>
                <tbody>';

    if ($bookings->num_rows > 0) {
        while ($row = $bookings->fetch_assoc()) {
            $html .= '<tr>';
            $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['service']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['date']) . '</td>';
            $html .= '<td>' . htmlspecialchars($row['time']) . '</td>';
            $html .= '</tr>';
        }
    } else {
        $html .= '<tr><td colspan="7" style="text-align:center;">No bookings found in ' . $selected_month . '</td></tr>';
    }

    $html .= '</tbody></table>';

    $pdf->writeHTML($html, true, false, true, false, '');

    $pdf->Output('monthly_booking_report_' . $selected_month . '.pdf', 'D');
    exit;
}

$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($current_page - 1) * $records_per_page;

$total_records_query = "SELECT COUNT(*) as total FROM appointment";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];

$total_pages = ceil($total_records / $records_per_page);

$query = "SELECT * FROM appointment ORDER BY date ASC, time ASC LIMIT $start, $records_per_page";
$appointments = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointments</title>
    <!-- CSS -->
    <link rel="stylesheet" href="css/admin_appointment.css">
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

<!-- appointment -->
<section class="dashboard">
    <h1 class="heading">Appointments</h1>

    <div class="add-appointment">
        <form method="GET" style="">
            <label for="month" style="">Select Month:</label>
            <input type="month" id="month" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : date('Y-m') ?>" required>
            <button type="submit" name="export_pdf" value="1" class="btn" style="margin-left:10px;">Export to PDF</button>
        </form>
        <a href="admin_appointment_form.php" class="btn">Add New Appointment</a>
    </div>

    <div class="appointment-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Service</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($appointments->num_rows > 0): ?>
                    <?php while ($row = $appointments->fetch_assoc()): ?>
                        <?php
                        $name = htmlspecialchars($row['name']);
                        $email = htmlspecialchars($row['email']);
                        $phone = htmlspecialchars($row['phone']);
                        $service = htmlspecialchars($row['service']);
                        $date = htmlspecialchars($row['date']);
                        $time = htmlspecialchars($row['time']);
                        $status = ucfirst(htmlspecialchars($row['status']));
                        $appointment_id = htmlspecialchars($row['id']);
                        ?>
                        <tr>
                            <td><?= $appointment_id ?></td>
                            <td><?= $name ?></td>
                            <td><?= $email ?></td>
                            <td><?= $phone ?></td>
                            <td><?= $service ?></td>
                            <td><?= $date ?></td>
                            <td><?= $time ?></td>
                            <td>
                                <select class="status-dropdown" data-id="<?= $appointment_id ?>" onchange="updateStatus(this)">
                                    <option value="pending" <?= ($status === 'Pending' ? 'selected' : '') ?>>Pending</option>
                                    <option value="confirmed" <?= ($status === 'Confirmed' ? 'selected' : '') ?>>Confirmed</option>
                                    <option value="completed" <?= ($status === 'Completed' ? 'selected' : '') ?>>Completed</option>
                                    <option value="cancelled" <?= ($status === 'Cancelled' ? 'selected' : '') ?>>Cancelled</option>
                                </select>
                            </td>
                            <td>
                                <a href="admin_appointment_form.php?id=<?= $appointment_id ?>" class="option-btn">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <a href="delete_appointment.php?id=<?= $appointment_id ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this appointment?')">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9">No appointments found</td></tr>
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