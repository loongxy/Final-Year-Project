<?php
session_start();
include 'config.php';

require_once('tcpdf/tcpdf.php');

if (isset($_GET['export_pdf'])) {
    try {
        // Updated query to fetch data directly from the inventory table
        $query = "SELECT id, name, quantity, price FROM inventory ORDER BY name ASC";
                  
        $stmt = $conn->prepare($query);
        if (!$stmt) {
            throw new Exception("Query preparation failed: " . $conn->error);
        }
        
        $stmt->execute();
        $inventory_records = $stmt->get_result();

        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor('Perfection Beauty');
        $pdf->SetTitle('Inventory Report - ' . date('Y-m-d'));
        $pdf->SetSubject('Inventory Report');
        $pdf->SetKeywords('Inventory, Report, PDF');

        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $pdf->SetFont('helvetica', '', 10);

        $html = '<h1 style="text-align:center;">Inventory Report</h1>';
        $html .= '<h3 style="text-align:center;">Date: ' . date('Y-m-d') . '</h3>';
        $html .= '<table border="1" cellpadding="5" cellspacing="0" style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background-color:#f2f2f2;">
                            <th>ID</th>
                            <th>Product Name</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>';

        if ($inventory_records->num_rows > 0) {
            while ($row = $inventory_records->fetch_assoc()) {
                $quantity = htmlspecialchars($row['quantity']);
                $status = $quantity < 5 ? '<span style="color:red;">Stockpiles are in a desperate state</span>' : 'Normal';
                $price = 'RM ' . number_format($row['price'], 2);

                $html .= '<tr>';
                $html .= '<td>' . htmlspecialchars($row['id']) . '</td>';
                $html .= '<td>' . htmlspecialchars($row['name']) . '</td>';
                $html .= '<td>' . $quantity . '</td>';
                $html .= '<td>' . $price . '</td>';
                $html .= '<td>' . $status . '</td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr><td colspan="5" style="text-align:center;">No inventory records found</td></tr>';
        }

        $html .= '</tbody></table>';

        $html .= '<p style="text-align:right; margin-top:20px;">Report generated on: ' . date('Y-m-d H:i:s') . '</p>';

        $pdf->writeHTML($html, true, false, true, false, '');

        $pdf->Output('inventory_report_' . date('Y-m-d') . '.pdf', 'D'); // 'D' for direct download
        exit;
    } catch (Exception $e) {
        error_log("PDF Export Error: " . $e->getMessage());
        header("Location: inventory.php?error=pdf_export_failed");
        exit;
    }
}

$records_per_page = 10;
$current_page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($current_page - 1) * $records_per_page;

$total_records_query = "SELECT COUNT(*) as total FROM inventory";
$total_records_result = $conn->query($total_records_query);
$total_records = $total_records_result->fetch_assoc()['total'];

$total_pages = ceil($total_records / $records_per_page);

$query = "SELECT * FROM inventory ORDER BY name ASC LIMIT $start, $records_per_page";
$inventory = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inventory</title>
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

<!-- inventory -->
<section class="dashboard">
    <h1 class="heading">Inventory</h1>

    <div class="add-inventory">
        <form method="GET" style="display:flex; align-items:center;">
            <label for="month" style="margin-right:10px;">Select Month:</label>
            <input type="month" id="month" name="month" value="<?= isset($_GET['month']) ? $_GET['month'] : date('Y-m') ?>" required>
            <button type="submit" name="export_pdf" value="1" class="btn" style="margin-left:10px;">Export to PDF</button>
        </form>
        <a href="inventory_form.php" class="btn">Add New Product</a>
    </div>

    <div class="inventory-table">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if ($inventory->num_rows > 0): ?>
                    <?php while ($row = $inventory->fetch_assoc()): ?>
                        <?php
                        $inventory_id = htmlspecialchars($row['id']);
                        $name = htmlspecialchars($row['name']);
                        $quantity = htmlspecialchars($row['quantity']);
                        $price = 'RM ' . number_format($row['price'], 2);
                        $status = $quantity < 5 ? '<span style="color:red;">Stockpiles are in a desperate state</span>' : 'Normal';
                        ?>
                        <tr>
                            <td><?= $inventory_id ?></td>
                            <td><?= $name ?></td>
                            <td><?= $quantity ?></td>
                            <td><?= $price ?></td>
                            <td>
                            <a href="inventory_form.php?id=<?= htmlspecialchars($row['id']) ?>" class="option-btn">
                                    <i class='bx bx-edit-alt'></i>
                                </a>
                                <a href="delete_inventory.php?id=<?= $inventory_id ?>" class="delete-btn" onclick="return confirm('Are you sure you want to delete this inventory?')">
                                    <i class='bx bx-trash'></i>
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="5">No inventory found</td></tr>
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