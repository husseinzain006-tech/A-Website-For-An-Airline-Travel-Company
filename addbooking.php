<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة حجز جديد</title>
    <link rel="stylesheet" href="styleaddbooking.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-ticket-alt"></i> إضافة حجز جديد</h1>
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
            $error = $success = $passport_number = $flight_id = $seat_id = $overweight = $is_paid = "";
            $price = $overweight_charge = $total_cost = 0;
            $available_seats = [];
            $customer_info = [];
            $flight_info = [];
            $flight_id = isset($_GET['flightid']) ? $_GET['flightid'] : 0;

            if ($flight_id) {
                $stmt = $pdo->prepare("SELECT * FROM flight WHERE flight_ID = ?");
                $stmt->execute([$flight_id]);
                $flight_info = $stmt->fetch();
                $price = $flight_info['price'];
                $overweight_charge = $flight_info['overweight_charge'];
            } else {
                $error = "الرحلة غير موجودة!";
            }
            $stmt = $pdo->query("SELECT MAX(booking_id) AS max_id FROM booking");
            $result = $stmt->fetch();
            $booking_id = $result['max_id'] ? $result['max_id'] + 1 : 1;

            $customers = [];
            $stmt = $pdo->query("SELECT passport_number, first_name, last_name FROM customers");
            $customers = $stmt->fetchAll(PDO::FETCH_ASSOC);

            if ($flight_id) {
                $stmt = $pdo->prepare("SELECT seat_ID FROM seat WHERE flight_ID = ? AND is_booked = 0");
                $stmt->execute([$flight_id]);
                $available_seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
            }

            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $passport_number = isset($_POST['passport_number']) ? $_POST['passport_number'] : '';
                $seat_id =  isset($_POST['seat_id']) ? $_POST['seat_id'] : '';
                $overweight = isset($_POST['overweight']) ? (int)$_POST['overweight'] : 0;
                $is_paid = isset($_POST['is_paid']) ? (int)$_POST['is_paid'] : 0;
                $total_cost = $price + ($overweight * $overweight_charge);

                if (!empty($passport_number)) {
                    $stmt = $pdo->prepare("SELECT * FROM customers WHERE passport_number = ?");
                    $stmt->execute([$passport_number]);
                    $customer_info = $stmt->fetch(PDO::FETCH_ASSOC);
                }

                if (empty($passport_number) || empty($seat_id)) {
                    $error = 'الرجاء ملء جميع الحقول المطلوبة!';
                } else {
                    try {
                        $checkSeatBooked = $pdo->prepare("SELECT is_booked FROM seat WHERE flight_ID = ? AND seat_ID = ?");
                        $checkSeatBooked->execute([$flight_id, $seat_id]);
                        $seatStatus = $checkSeatBooked->fetchColumn();

                        if ($seatStatus == 1) {
                            $error = "المقعد ($seat_id) محجوز بالفعل في الرحلة ($flight_id)!";
                        } else {
                            $q = "INSERT INTO booking (booking_id, passport_number, flight_ID, seat_ID, total_cost, overweight, booking_date, is_paid) 
                                  VALUES (?, ?, ?, ?, ?, ?, NOW(), ?)";
                            $ps = $pdo->prepare($q);
                            $ps->execute([$booking_id, $passport_number, $flight_id, $seat_id, $total_cost, $overweight, $is_paid]);

                            $updateSeat = $pdo->prepare("UPDATE seat SET is_booked = 1 WHERE flight_ID = ? AND seat_ID = ?");
                            $updateSeat->execute([$flight_id, $seat_id]);

                            // توليد رقم حجز جديد للاستخدام التالي
                            $stmt = $pdo->query("SELECT MAX(booking_id) AS max_id FROM booking");
                            $result = $stmt->fetch();
                            $booking_id = $result['max_id'] + 1;

                            $success = "تم إضافة الحجز بنجاح! رقم الحجز: $booking_id";
                            $passport_number = $seat_id = "";
                            $overweight = 0;
                            $is_paid = 0;
                            $stmt = $pdo->prepare("SELECT seat_ID FROM seat WHERE flight_ID = ? AND is_booked = 0");
                            $stmt->execute([$flight_id]);
                            $available_seats = $stmt->fetchAll(PDO::FETCH_ASSOC);
                        }
                    } catch (PDOException $e) {
                        $error = "حدث خطأ: " . $e->getMessage();
                    }
                }
            }
            ?>
            <?php if (!empty($error)): ?>
                <div class="error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $error; ?>
                </div>
            <?php endif; ?>
            <?php if (!empty($success)): ?>
                <div class="success">
                    <i class="fas fa-check-circle"></i> <?php echo $success; ?>
                </div>
            <?php endif; ?>
            <div class="flight-id">
                <i class="fas fa-plane-departure"></i> رقم الرحلة: <?php echo $flight_id; ?>
                <br>
                <i class="fas fa-ticket-alt"></i> رقم الحجز: <?php echo $booking_id; ?>
            </div>
            <form method="post" action="addbooking.php?flightid=<?php echo $flight_id; ?>">
                <input type="hidden" name="flight_id" value="<?php echo $flight_id; ?>">

                <div class="form-group">
                    <label for="passport_number"><i class="fas fa-passport"></i> اختر الزبون</label>
                    <select id="passport_number" name="passport_number" class="form-control" required>
                        <option value="">اختر الزبون</option>
                        <?php foreach ($customers as $customer): ?>
                            <?php $selected = ($passport_number == $customer['passport_number']) ? 'selected' : ''; ?>
                            <option value="<?php echo $customer['passport_number']; ?>" <?php echo $selected; ?>>
                                <?php echo "{$customer['passport_number']} - {$customer['first_name']} {$customer['last_name']}"; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="seat_id"><i class="fas fa-chair"></i> اختر مقعد</label>
                    <select id="seat_id" name="seat_id" class="form-control" required>
                        <option value="">اختر مقعد</option>
                        <?php foreach ($available_seats as $seat): ?>
                            <?php $selected = ($seat_id == $seat['seat_ID']) ? 'selected' : ''; ?>
                            <option value="<?php echo $seat['seat_ID']; ?>" <?php echo $selected; ?>>
                                <?php echo $seat['seat_ID']; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="overweight"><i class="fas fa-weight-hanging"></i> الوزن الزائد (كجم)</label>
                    <input type="number" id="overweight" name="overweight" class="form-control"
                           value="<?php echo isset($overweight) ? $overweight : ''; ?>" min="0" max="50" step="1">
                </div>

                <div class="form-group">
                    <label><i class="fas fa-credit-card"></i> حالة الدفع</label>
                    <div class="radio-group">
                        <div class="radio-option">
                            <input type="radio" id="paid_true" name="is_paid" value="1"
                                <?php echo ($is_paid == 1) ? 'checked' : ''; ?>>
                            <label for="paid_true">تم الدفع</label>
                        </div>
                        <div class="radio-option">
                            <input type="radio" id="paid_false" name="is_paid" value="0"
                                <?php echo ($is_paid == 0) ? 'checked' : ''; ?>>
                            <label for="paid_false">لم يتم الدفع</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> إضافة الحجز
                </button>
            </form>
        </div>
        <div class="summary-section">
            <h2 class="section-title"><i class="fas fa-receipt"></i> ملخص الحجز</h2>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">رقم الحجز:</span>
                    <span class="summary-value"><?php echo $booking_id; ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">تاريخ الحجز:</span>
                    <span class="summary-value"><?php echo date('Y-m-d H:i'); ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الرحلة:</span>
                    <span class="summary-value"><?php echo $flight_id; ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">المقعد:</span>
                    <span class="summary-value" id="summary-seat"><?php echo $seat_id ?: '--'; ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">حالة الدفع:</span>
                    <span class="summary-value" id="summary-payment">
                        <?php
                        if ($is_paid == 1) {
                            echo '<span style="color: #27ae60;">تم الدفع</span>';
                        } elseif ($is_paid == 0) {
                            echo '<span style="color: #e74c3c;">لم يتم الدفع</span>';
                        } else {
                            echo '--';
                        }
                        ?>
                    </span>
                </div>
            </div>

            <div id="customer-info-section">
                <?php if (!empty($customer_info)): ?>
                    <div class="customer-info">
                        <h3><i class="fas fa-user"></i> معلومات الزبون</h3>
                        <div class="customer-info-item">
                            <span class="customer-label">الاسم الكامل:</span>
                            <span class="customer-value"><?php echo $customer_info['first_name'] . ' ' . $customer_info['last_name']; ?></span>
                        </div>
                        <div class="customer-info-item">
                            <span class="customer-label">رقم الجواز:</span>
                            <span class="customer-value"><?php echo $customer_info['passport_number']; ?></span>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">سعر الرحلة:</span>
                    <span class="summary-value" id="summary-flight-price">
                        <?php echo $price ? number_format($price) . ' ل.س ' : '--'; ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الوزن الزائد:</span>
                    <span class="summary-value" id="summary-overweight">
                        <?php
                        if (isset($overweight) && $overweight > 0) {
                            echo $overweight . ' كجم × ' . number_format($overweight_charge) . ' ل.س ';
                        } else {
                            echo '--';
                        }
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رسوم الوزن الزائد:</span>
                    <span class="summary-value" id="summary-overweight-fee">
                        <?php
                        if (isset($overweight) && $overweight > 0) {
                            echo number_format($overweight * $overweight_charge) . ' ل.س ';
                        } else {
                            echo '--';
                        }
                        ?>
                    </span>
                </div>
            </div>

            <div class="total-cost">
                <div class="label">التكلفة الإجمالية</div>
                <div class="value" id="total-cost-value">
                    <?php
                    $final_cost = $price;
                    if (isset($overweight) && $overweight > 0) {
                        $final_cost += ($overweight * $overweight_charge);
                    }
                    echo number_format($final_cost) . ' ل.س ';
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        $('#seat_id').change(function() {
            $('#summary-seat').text($(this).val() || '--');
        });

        $('#overweight').on('input', function() {
            var overweight = $(this).val();
            var overweightFee = <?php echo $overweight_charge; ?>;
            var flightPrice = <?php echo $price; ?>;

            if (overweight) {
                $('#summary-overweight').text(overweight + ' كجم × ' + overweightFee.toLocaleString() + ' ل.س ');
                $('#summary-overweight-fee').text((overweight * overweightFee).toLocaleString() + ' ل.س ');
                var totalCost = flightPrice + (overweight * overweightFee);
                $('#total-cost-value').text(totalCost.toLocaleString() + ' ل.س ');
            } else {
                $('#summary-overweight').text('--');
                $('#summary-overweight-fee').text('--');
                $('#total-cost-value').text(flightPrice.toLocaleString() + ' ل.س ');
            }
        });

        $('input[name="is_paid"]').change(function() {
            if ($(this).val() === '1') {
                $('#summary-payment').html('<span style="color: #27ae60;">تم الدفع</span>');
            } else {
                $('#summary-payment').html('<span style="color: #e74c3c;">لم يتم الدفع</span>');
            }
        });

        $('#passport_number').change(function() {
            var passport = $(this).val();
            if (passport) {
                $.ajax({
                    url: 'get_customer_info.php',
                    method: 'POST',
                    data: { passport_number: passport },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            var customer = response.customer;
                            var html = `
                                <div class="customer-info">
                                    <h3><i class="fas fa-user"></i> معلومات الزبون</h3>
                                    <div class="customer-info-item">
                                        <span class="customer-label">الاسم الكامل:</span>
                                        <span class="customer-value">${customer.first_name} ${customer.last_name}</span>
                                    </div>
                                    <div class="customer-info-item">
                                        <span class="customer-label">رقم الجواز:</span>
                                        <span class="customer-value">${customer.passport_number}</span>
                                    </div>
                                </div>
                            `;
                            $('#customer-info-section').html(html);
                        } else {
                            $('#customer-info-section').html('<p class="error">' + response.message + '</p>');
                        }
                    },
                    error: function() {
                        $('#customer-info-section').html('<p class="error">حدث خطأ أثناء جلب بيانات الزبون</p>');
                    }
                });
            } else {
                $('#customer-info-section').html('');
            }
        });
    });
</script>
</body>
</html>