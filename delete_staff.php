<?php
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM `staff` WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script>alert("Staff deleted successfully!"); window.location.href = "staff.php";</script>';
    } else {
        echo '<script>alert("Error deleting staff!"); window.location.href = "staff.php";</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Invalid staff ID!"); window.location.href = "staff.php";</script>';
}

$conn->close();
?>