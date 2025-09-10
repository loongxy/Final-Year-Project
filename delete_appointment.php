<?php
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    $stmt = $conn->prepare("DELETE FROM `appointment` WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        echo '<script>alert("Appointment deleted successfully!"); window.location.href = "admin_appointment.php";</script>';
    } else {
        echo '<script>alert("Error deleting appointment!"); window.location.href = "admin_appointment.php";</script>';
    }

    $stmt->close();
} else {
    echo '<script>alert("Invalid appointment ID!"); window.location.href = "admin_appointment.php";</script>';
}

$conn->close();
?>