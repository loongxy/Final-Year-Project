<?php
session_start();
include 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];
    
    $stmt = $conn->prepare("DELETE FROM feedback WHERE id = ?");
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Feedback deleted successfully.";
    } else {
        $_SESSION['error_message'] = "Error deleting feedback: " . $conn->error;
    }
    
    $stmt->close();
} else {
    $_SESSION['error_message'] = "Invalid feedback ID.";
}

header("Location: admin_feedback.php");
exit();
?>