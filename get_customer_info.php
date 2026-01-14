<?php
require_once 'connecting.php';
if (isset($_POST['passport_number'])) {
    $passport = $_POST['passport_number'];
    try {
        $stmt = $pdo->prepare("SELECT * FROM customers WHERE passport_number = ?");
        $stmt->execute([$passport]);
        $customer = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($customer) {
            echo json_encode(['status' => 'success', 'customer' => $customer]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'الزبون غير موجود']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'حدث خطأ: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'رقم الجواز غير مقدم']);
}
?>