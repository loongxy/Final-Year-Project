<?php
include 'config.php';

$data = json_decode(file_get_contents('php://input'), true);
$id = $conn->real_escape_string($data['id']);
$status = $conn->real_escape_string($data['status']);

$query = "UPDATE `appointment` SET `status` = ? WHERE `id` = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('si', $status, $id);

if ($stmt->execute()) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}
?>