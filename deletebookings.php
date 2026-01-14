<?php
require_once 'connecting.php';
$booking_id = isset($_GET['booking']) ? $_GET['booking'] : 0;
try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("SELECT flight_ID, seat_ID FROM booking WHERE booking_id = ?");
    $stmt->execute([$booking_id]);
    $booking = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$booking) {
        echo json_encode(['success' => false, 'error' => 'الحجز غير موجود']);
        exit;
    }
    $flight_ID = $booking['flight_ID'];
    $seat_ID = $booking['seat_ID'];
    $updateStmt = $pdo->prepare("UPDATE seat SET is_booked = 0 WHERE flight_ID = ? AND seat_ID = ?");
    $updateStmt->execute([$flight_ID, $seat_ID]);
    $deleteStmt = $pdo->prepare("DELETE FROM booking WHERE booking_id = ?");
    $deleteStmt->execute([$booking_id]);
    $pdo->commit();
    if ($deleteStmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'لم يتم العثور على الحجز لحذفه']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}