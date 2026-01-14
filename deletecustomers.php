<?php
require_once 'connecting.php';
$passid = isset($_GET['passid']) ? $_GET['passid'] : 0;

try {
    $pdo->beginTransaction();
    $stmt = $pdo->prepare("DELETE FROM booking WHERE passport_number = ?");
    $stmt->execute([$passid]);
    $stmt = $pdo->prepare("DELETE FROM customers WHERE passport_number = ?");
    $stmt->execute([$passid]);
    $pdo->commit();
    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => 'الزبون غير موجود']);
    }
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}