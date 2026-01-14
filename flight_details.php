<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الرحلة</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            min-height: 100vh;
            padding: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            color: #333;
        }
        .container {
            width: 100%;
            max-width: 900px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #2c3e50, #1a2980);
            color: white;
            padding: 25px 30px;
            text-align: center;
        }
        .header h1 {
            font-size: 28px;
            margin-bottom: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
        }
        .header-buttons {
            display: flex;
            justify-content: center;
            gap: 15px;
        }
        .header-buttons .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(135deg, #2c3e50, #1a2980);
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            text-decoration: none;
        }
        .header-buttons .btn:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        .content {
            display: flex;
            min-height: 500px;
        }
        .form-section {
            flex: 1;
            padding: 30px;
            border-right: 1px solid #eee;
        }
        .summary-section {
            flex: 1;
            padding: 30px;
            background: #f9fafc;
        }
        .section-title {
            color: #2c3e50;
            padding-bottom: 12px;
            margin-bottom: 25px;
            border-bottom: 2px solid #3498db;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .flight-id-display {
            background: #f1f2f6;
            padding: 12px 15px;
            border-radius: 8px;
            font-weight: 600;
            color: #2c3e50;
            display: inline-block;
            margin-bottom: 20px;
            border: 2px dashed #3498db;
        }
        .flight-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
        }
        .flight-info p {
            margin: 10px 0;
            font-size: 16px;
        }
        .summary-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
        }
        .summary-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f0f0f0;
        }
        .summary-item:last-child {
            border-bottom: none;
        }
        .summary-label {
            color: #7b8a9a;
            font-weight: 500;
            min-width: 120px;
        }
        .summary-value {
            font-weight: 600;
            color: #2c3e50;
            text-align: left;
            max-width: 200px;
            overflow-wrap: break-word;
        }
        .btn-back {
            background: #3498db;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            margin-top: 20px;
        }
        .btn-back:hover {
            background: #2980b9;
            transform: translateY(-3px);
        }

        /* أنماط جديدة للحالة */
        .flight-status-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.05);
            margin-bottom: 25px;
            border-left: 5px solid;
        }
        .status-upcoming {
            border-left-color: #3498db;
        }
        .status-departed {
            border-left-color: #f39c12;
        }
        .status-arrived {
            border-left-color: #2ecc71;
        }
        .status-cancelled {
            border-left-color: #e74c3c;
        }
        .status-title {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .status-icon {
            font-size: 24px;
        }
        .status-description {
            color: #666;
            margin-bottom: 15px;
        }
        .status-time {
            font-size: 14px;
            color: #7b8a9a;
        }

        @media (max-width: 768px) {
            .content {
                flex-direction: column;
            }
            .form-section {
                border-right: none;
                border-bottom: 1px solid #eee;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-plane"></i> تفاصيل الرحلة</h1>
        <div class="header-buttons">
            <a href="flights.php" class="btn">
                <i class="fas fa-plane"></i>      الرحلات
            </a>
            <a href="statistics.php" class="btn">
                <i class="fas fa-home"></i> الإحصائيات
            </a>
        </div>
    </div>
    <div class="content">
        <div class="form-section">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> معلومات الرحلة</h2>
            <?php
            require_once 'connecting.php';
            $flight_id = isset($_GET['flightid']) ? $_GET['flightid'] : 0;

            $stmt = $pdo->prepare("
                SELECT * FROM flight 
                WHERE flight_ID = ?
            ");
            $stmt->execute([$flight_id]);
            $flight = $stmt->fetch();

            if (!$flight) {
                die("الرحلة غير موجودة! الرجاء التأكد من رقم الرحلة");
            }

            $now = new DateTime();
            $departure = new DateTime($flight['departure_time']);
            $durationParts = explode('h', $flight['trip_duration']);
            $hours = (int)$durationParts[0];
            $minutes = isset($durationParts[1]) ? (int)str_replace('m', '', $durationParts[1]) : 0;
            $arrival = clone $departure;
            $arrival->add(new DateInterval("PT{$hours}H{$minutes}M"));

            if ($flight['status'] === 'cancelled') {
                $statusClass = 'status-cancelled';
                $statusText = 'ملغية';
                $statusIcon = 'fa-ban';
                $statusDesc = 'تم إلغاء هذه الرحلة ولا يمكن حجز تذاكر لها';
            } else {
                if ($now < $departure) {
                    $statusClass = 'status-upcoming';
                    $statusText = 'لم تنطلق بعد';
                    $statusIcon = 'fa-clock';
                    $statusDesc = 'الرحلة لم تنطلق بعد، يمكن حجز تذاكر';
                } elseif ($now > $arrival) {
                    $statusClass = 'status-arrived';
                    $statusText = 'وصلت';
                    $statusIcon = 'fa-check-circle';
                    $statusDesc = 'وصلت الرحلة إلى وجهتها بنجاح';
                } else {
                    $statusClass = 'status-departed';
                    $statusText = 'انطلقت';
                    $statusIcon = 'fa-plane-departure';
                    $statusDesc = 'الرحلة في الجـو حالياً';
                }
            }

            $bookingsStmt = $pdo->prepare("SELECT COUNT(*) FROM booking WHERE flight_ID = ?");
            $bookingsStmt->execute([$flight_id]);
            $bookingsCount = $bookingsStmt->fetchColumn();

            $incomeStmt = $pdo->prepare("SELECT SUM(total_cost) FROM booking WHERE flight_ID = ?");
            $incomeStmt->execute([$flight_id]);
            $totalIncome = $incomeStmt->fetchColumn();
            ?>

            <div class="flight-id-display">
                <i class="fas fa-hashtag"></i> رقم الرحلة: <?= $flight_id ?>
            </div>

            <div class="flight-status-card <?= $statusClass ?>">
                <div class="status-title">
                    <i class="fas <?= $statusIcon ?>"></i>
                    <span>حالة الرحلة: <?= $statusText ?></span>
                </div>
                <div class="status-description"><?= $statusDesc ?></div>
                <div class="status-time">
                    <div><i class="fas fa-plane-departure"></i> المغادرة: <?= $departure->format('Y-m-d H:i:s') ?></div>
                    <div><i class="fas fa-plane-arrival"></i> الوصول المتوقع: <?= $arrival->format('Y-m-d H:i:s') ?></div>
                </div>
            </div>

            <div class="flight-info">
                <p><strong><i class="fas fa-city"></i> مدينة الانطلاق:</strong> <?= $flight['departure_city'] ?></p>
                <p><strong><i class="fas fa-location-dot"></i> مدينة الوجهة:</strong> <?= $flight['destination_city'] ?></p>
                <p><strong><i class="fas fa-clock"></i> وقت المغادرة:</strong> <?= $flight['departure_time'] ?></p>
                <p><strong><i class="fas fa-hourglass"></i> مدة الرحلة:</strong> <?= $flight['trip_duration'] ?></p>
                <p><strong><i class="fas fa-chair"></i> عدد المقاعد:</strong> <?= $flight['seats_count'] ?></p>
                <p><strong><i class="fas fa-tag"></i> السعر:</strong> <?= number_format($flight['price']) ?> ل.س</p>
                <p><strong><i class="fas fa-weight-scale"></i> رسوم الوزن الزائد:</strong> <?= number_format($flight['overweight_charge']) ?> ل.س/كجم</p>
            </div>
            <a href="home.php" class="btn-back">
                <i class="fas fa-arrow-left"></i> العودة إلى الصفحة الرئيسية
            </a>
        </div>

        <div class="summary-section">
            <h2 class="section-title"><i class="fas fa-chart-bar"></i> إحصائيات الرحلة</h2>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">عدد الحجوزات:</span>
                    <span class="summary-value"><?= $bookingsCount ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">المقاعد المشغولة:</span>
                    <span class="summary-value">
                        <?= $bookingsCount ?> / <?= $flight['seats_count'] ?>
                        (<?= round(($bookingsCount / $flight['seats_count']) * 100, 2) ?>%)
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">إجمالي الدخل:</span>
                    <span class="summary-value" style="color: #27ae60; font-weight: bold;">
                        <?= number_format($totalIncome) ?> ل.س
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">متوسط سعر الحجز:</span>
                    <span class="summary-value">
                        <?= $bookingsCount > 0 ? number_format($totalIncome / $bookingsCount) : 0 ?> ل.س
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">أعلى حجز سعراً:</span>
                    <span class="summary-value">
                        <?php
                        $maxStmt = $pdo->prepare("SELECT MAX(total_cost) FROM booking WHERE flight_ID = ?");
                        $maxStmt->execute([$flight_id]);
                        $maxCost = $maxStmt->fetchColumn();
                        echo number_format($maxCost) . ' ل.س';
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">أقل حجز سعراً:</span>
                    <span class="summary-value">
                        <?php
                        $minStmt = $pdo->prepare("SELECT MIN(total_cost) FROM booking WHERE flight_ID = ?");
                        $minStmt->execute([$flight_id]);
                        $minCost = $minStmt->fetchColumn();
                        echo number_format($minCost) . ' ل.س';
                        ?>
                    </span>
                </div>
            </div>

            <h3 class="section-title"><i class="fas fa-users"></i> العملاء</h3>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">عدد العملاء:</span>
                    <span class="summary-value"><?= $bookingsCount ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">العميل الأكثر حجزاً:</span>
                    <span class="summary-value">
                        <?php
                        $topCustomerStmt = $pdo->prepare("
                            SELECT c.first_name, c.last_name, COUNT(b.booking_id) as bookings
                            FROM booking b
                            JOIN customers c ON b.passport_number = c.passport_number
                            WHERE b.flight_ID = ?
                            GROUP BY b.passport_number
                            ORDER BY bookings DESC
                            LIMIT 1
                        ");
                        $topCustomerStmt->execute([$flight_id]);
                        $topCustomer = $topCustomerStmt->fetch();

                        if ($topCustomer) {
                            echo $topCustomer['first_name'] . ' ' . $topCustomer['last_name'] ."<br>". ' عدد الحجوزات '. $topCustomer['bookings'] ;
                        } else {
                            echo 'لا يوجد بيانات';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>