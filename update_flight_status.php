<?php
header('Content-Type: application/json');

require_once 'connecting.php';

$data = json_decode(file_get_contents('php://input'), true);
$flightId = isset($data['flightId']) ? $data['flightId'] : null;
$status = isset($data['status']) ? $data['status'] : null;

if (!$flightId || !$status) {
    echo json_encode(['success' => false, 'error' => 'Missing flight ID or status']);
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE flight SET status = ? WHERE flight_ID = ?");
    $stmt->execute([$status, $flightId]);

    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}
?>