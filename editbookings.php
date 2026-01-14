<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الحجز</title>
    <link rel="stylesheet" href="styleeditbookings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-ticket-alt"></i> تعديل بيانات الحجز</h1>
        <div class="header-buttons">
            <a href="bookings.php" class="btn">
                <i class="fas fa-list"></i> الحجوزات
            </a>
            <a href="flights.php" class="btn">
                <i class="fas fa-plane"></i> الرحلات
            </a>
        </div>
    </div>
    <div class="content">
        <div class="form-section">
            <h2 class="section-title"><i class="fas fa-edit"></i> تفاصيل الحجز</h2>
            <?php
            require_once 'connecting.php';
            $booking_id = isset($_GET['bookingid']) ? $_GET['bookingid'] : 0;
            $stmt = $pdo->prepare("
               SELECT b.*, f.departure_city, f.destination_city, f.price, f.overweight_charge, 
                 c.first_name, c.last_name, c.passport_number
                  FROM booking b
                   LEFT JOIN flight f ON b.flight_ID = f.flight_ID
                    LEFT JOIN customers c ON b.passport_number = c.passport_number
                   WHERE b.booking_id = ?  ");
            $stmt->execute([$booking_id]);
            $booking = $stmt->fetch();
            if (!$booking) {
                die("الحجز غير موجود! الرجاء التأكد من رقم الحجز");
            }
            $error = '';
            $successMsg = '';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $new_seat_id = $_POST['seat_id'];
                $overweight = $_POST['overweight'];
                $booking_date = $_POST['booking_date'];
                $is_paid = isset($_POST['is_paid']) ? 1 : 0;
                $seatCheck = $pdo->prepare("
                    SELECT * FROM seat 
                    WHERE flight_ID = ? 
                    AND seat_ID = ? 
                    AND is_booked = 0 ");
                $seatCheck->execute([$booking['flight_ID'], $new_seat_id]);
                $availableSeat = $seatCheck->fetch();
                if (!$availableSeat && $new_seat_id != $booking['seat_ID']) {
                    $error = 'المقعد المحدد غير متاح!';
                } else {
                    try {
                        $pdo->beginTransaction();
                        $freeOldSeat = $pdo->prepare("
                            UPDATE seat 
                            SET is_booked = 0 
                            WHERE flight_ID = ? 
                            AND seat_ID = ?
                        ");
                        $freeOldSeat->execute([$booking['flight_ID'], $booking['seat_ID']]);
                        $bookNewSeat = $pdo->prepare("
                            UPDATE seat 
                            SET is_booked = 1 
                            WHERE flight_ID = ? 
                            AND seat_ID = ? ");
                        $bookNewSeat->execute([$booking['flight_ID'], $new_seat_id]);
                        $flight_price = $booking['price'];
                        $overweight_charge = $booking['overweight_charge'];
                        $total_cost = $flight_price + ($overweight * $overweight_charge);
                        $updateBooking = $pdo->prepare("
                            UPDATE booking 
                            SET 
                                seat_ID = :seat_id,
                                overweight = :overweight,
                                booking_date = :booking_date,
                                is_paid = :is_paid,
                                total_cost = :total_cost
                            WHERE booking_id = :booking_id ");
                        $updateBooking->execute([
                            ':seat_id' => $new_seat_id,
                            ':overweight' => $overweight,
                            ':booking_date' => $booking_date,
                            ':is_paid' => $is_paid,
                            ':total_cost' => $total_cost,
                            ':booking_id' => $booking_id
                        ]);
                        $pdo->commit();
                        $successMsg = "تم تحديث بيانات الحجز بنجاح!";
                        $stmt->execute([$booking_id]);
                        $booking = $stmt->fetch();
                    } catch (Exception $e) {
                        $pdo->rollBack();
                        $error = "حدث خطأ: " . $e->getMessage();
                    }
                }
            }
            ?>
            <?php if (!empty($error)): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> <?= $error ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($successMsg)): ?>
                <div class="success">
                    <i class="fas fa-check-circle"></i> <?= $successMsg ?>
                </div>
            <?php endif; ?>
            <div class="booking-id-display">
                <i class="fas fa-hashtag"></i> رقم الحجز: <?= $booking_id ?>
            </div>
            <div class="customer-info">
                <h3><i class="fas fa-user"></i> معلومات الزبون</h3>
                <p><strong>الاسم:</strong> <?= $booking['first_name'] . ' ' . $booking['last_name'] ?></p>
                <p><strong>رقم الجواز:</strong> <?= $booking['passport_number'] ?></p>
            </div>
            <div class="flight-info">
                <h3><i class="fas fa-plane"></i> معلومات الرحلة</h3>
                <p><strong>المسار:</strong> <?= $booking['departure_city'] ?> → <?= $booking['destination_city'] ?></p>
                <p><strong>رقم الرحلة:</strong> <?= $booking['flight_ID'] ?></p>
                <p><strong>سعر الرحلة:</strong> <?= number_format($booking['price']) ?> ل.س</p>
                <p><strong>رسوم الوزن الزائد للكيلو:</strong> <?= number_format($booking['overweight_charge']) ?> ل.س</p>
            </div>
            <form method="post" id="bookingForm">
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="seat_id"><i class="fas fa-chair"></i> رقم المقعد</label>
                            <select name="seat_id" id="seat_id" class="form-control" required>
                                <?php
                                $seatsStmt = $pdo->prepare("
                                    SELECT seat_ID 
                                    FROM seat 
                                    WHERE flight_ID = ? 
                                    AND (is_booked = 0 OR seat_ID = ?)
                                    ORDER BY seat_ID ");
                                $seatsStmt->execute([$booking['flight_ID'], $booking['seat_ID']]);
                                $seats = $seatsStmt->fetchAll();
                                foreach ($seats as $seat) {
                                    $selected = ($seat['seat_ID'] == $booking['seat_ID']) ? 'selected' : '';
                                    echo "<option value='{$seat['seat_ID']}' $selected> {$seat['seat_ID']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="overweight"><i class="fas fa-weight"></i> الوزن الزائد (كجم)</label>
                            <input type="number" name="overweight" id="overweight" class="form-control"
                                   value="<?= $booking['overweight'] ?>" min="0" max="50" step="0.5" required>
                        </div>
                    </div>
                </div>
                <div class="form-row">
                    <div class="form-col">
                        <div class="form-group">
                            <label for="booking_date"><i class="fas fa-calendar"></i> تاريخ الحجز</label>
                            <input type="datetime-local" name="booking_date" id="booking_date"
                                   class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($booking['booking_date'])) ?>" required>
                        </div>
                    </div>
                    <div class="form-col">
                        <div class="form-group">
                            <label for="is_paid"><i class="fas fa-money-check"></i> حالة الدفع</label>
                            <div style="margin-top: 10px;">
                                <input type="checkbox" name="is_paid" id="is_paid"
                                    <?= $booking['is_paid'] ? 'checked' : '' ?>
                                       style="transform: scale(1.5); margin-left: 10px;">
                                <label for="is_paid" style="font-weight: normal;">تم الدفع</label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="cost-calculation">
                    <h3><i class="fas fa-calculator"></i> حساب التكلفة</h3>
                    <p><strong>سعر الرحلة:</strong> <span id="flight-price"><?= number_format($booking['price']) ?></span> ل.س</p>
                    <p><strong>الوزن الزائد:</strong> <span id="overweight-value"><?= $booking['overweight'] ?></span> كجم ×
                        <span id="overweight-charge"><?= number_format($booking['overweight_charge']) ?></span> ل.س =
                        <span id="overweight-cost"><?= number_format($booking['overweight'] * $booking['overweight_charge']) ?></span> ل.س</p>
                    <p><strong>التكلفة الإجمالية:</strong> <span id="total-cost" style="font-weight: bold;"><?= number_format($booking['total_cost']) ?></span> ل.س</p>
                </div>
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> حفظ التعديلات
                    </button>
                    <a href="bookings.php" class="btn-cancel">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
        <div class="summary-section">
            <h2 class="section-title"><i class="fas fa-receipt"></i> ملخص الحجز</h2>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">رقم الحجز:</span>
                    <span class="summary-value"><?= $booking_id ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الزبون:</span>
                    <span class="summary-value"><?= $booking['first_name'] . ' ' . $booking['last_name'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رقم الجواز:</span>
                    <span class="summary-value"><?= $booking['passport_number'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الرحلة:</span>
                    <span class="summary-value">
                        <?= $booking['departure_city'] ?> → <?= $booking['destination_city'] ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رقم الرحلة:</span>
                    <span class="summary-value"><?= $booking['flight_ID'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">المقعد:</span>
                    <span class="summary-value" id="summary-seat"><?= $booking['seat_ID'] ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الوزن الزائد:</span>
                    <span class="summary-value" id="summary-overweight"><?= $booking['overweight'] ?> كجم</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">تاريخ الحجز:</span>
                    <span class="summary-value" id="summary-booking-date">
                        <?php
                        $bookingDate = new DateTime($booking['booking_date']);
                        echo $bookingDate->format('d/m/Y H:i');
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">حالة الدفع:</span>
                    <span class="summary-value" id="summary-is-paid">
                        <?= $booking['is_paid'] ? 'تم الدفع' : 'لم يتم الدفع' ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">التكلفة الإجمالية:</span>
                    <span class="summary-value" id="summary-total-cost" style="color: #e74c3c; font-weight: bold;">
                        <?= number_format($booking['total_cost']) ?> ل.س
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function updateCost() {
            const overweight = parseFloat($('#overweight').val()) || 0;
            const flightPrice = <?= $booking['price'] ?>;
            const overweightCharge = <?= $booking['overweight_charge'] ?>;
            const overweightCost = overweight * overweightCharge;
            const totalCost = flightPrice + overweightCost;
            $('#overweight-value').text(overweight);
            $('#overweight-cost').text(overweightCost.toLocaleString());
            $('#total-cost').text(totalCost.toLocaleString());
        }
        function updateSummary() {
            $('#summary-seat').text($('#seat_id').val());
            $('#summary-overweight').text($('#overweight').val() + ' كجم');
            const bookingDate = new Date($('#booking_date').val());
            $('#summary-booking-date').text(
                bookingDate.toLocaleDateString('ar-EG') + ' ' +
                bookingDate.toLocaleTimeString('ar-EG', {hour: '2-digit', minute:'2-digit'})
            );
            $('#summary-is-paid').text($('#is_paid').is(':checked') ? 'تم الدفع' : 'لم يتم الدفع');
            updateCost();
            $('#summary-total-cost').text($('#total-cost').text() + ' ل.س');
        }
        updateCost();
        $('#overweight').on('input', updateCost);
        $('select, input').on('change', updateSummary);
        $('#is_paid').change(updateSummary);
    });
</script>
</body>
</html>