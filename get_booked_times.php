<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $selectedDate = $data['date'];

    if (empty($selectedDate)) {
        echo json_encode(['bookedTimes' => []]);
        exit();
    }

    $stmt = $conn->prepare("SELECT time FROM appointment WHERE date = ?");
    $stmt->bind_param("s", $selectedDate);
    $stmt->execute();
    $result = $stmt->get_result();
    $bookedTimes = [];

    while ($row = $result->fetch_assoc()) {
        $bookedTimes[] = $row['time'];
    }

    $stmt->close();

    echo json_encode(['bookedTimes' => $bookedTimes]);
} else {
    http_response_code(405);
    echo json_encode(['error' => 'Invalid request method']);
}
?>