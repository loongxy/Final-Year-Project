<?php
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM `users` WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script>alert("Customer deleted successfully!"); window.location.href = "customer.php";</script>';
    } else {
        echo '<script>alert("Error deleting customer!"); window.location.href = "customer.php";</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Invalid customer ID!"); window.location.href = "customer.php";</script>';
}

$conn->close();
?>