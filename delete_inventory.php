<?php
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM `inventory` WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script>alert("Inventory deleted successfully!"); window.location.href = "inventory.php";</script>';
    } else {
        echo '<script>alert("Error deleting inventory!"); window.location.href = "inventory.php";</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Invalid inventory ID!"); window.location.href = "inventory.php";</script>';
}

$conn->close();
?>