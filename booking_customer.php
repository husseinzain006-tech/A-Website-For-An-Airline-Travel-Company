<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تذكرة السفر</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: #f5f7fa;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .ticket {
            max-width: 800px;
            width: 100%;
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .header h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .header p {
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px;
        }
        .section {
            margin-bottom: 30px;
        }
        .section-title {
            color: #1a2980;
            font-size: 18px;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #eee;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #eee;
        }
        .info-label {
            font-weight: bold;
            color: #555;
        }
        .info-value {
            color: #1a2980;
        }

        @media (max-width: 600px) {
            .info-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
<div class="ticket">
    <div class="header">
        <h1><i class="fas fa-ticket-alt"></i> تذكرة السفر - ATC</h1>
    </div>

    <div class="content">
        <?php
        require_once 'connecting.php';
        $booking_id = isset($_GET['bookingid']) ? $_GET['bookingid'] : 0;

        $stmt = $pdo->prepare("
            SELECT b.*, f.departure_city, f.destination_city, f.departure_time, 
                   f.trip_duration, c.first_name, c.last_name, c.email, c.phone
            FROM booking b
            JOIN flight f ON b.flight_ID = f.flight_ID
            JOIN customers c ON b.passport_number = c.passport_number
            WHERE b.booking_id = ?
        ");
        $stmt->execute([$booking_id]);
        $booking = $stmt->fetch();

        if (!$booking) {
            die("عذراً، لا يمكن العثور على تفاصيل الحجز. يرجى التأكد من الرابط.");
        }

        $departure = new DateTime($booking['departure_time']);
        $durationParts = explode('h', $booking['trip_duration']);
        $hours = (int)$durationParts[0];
        $minutes = isset($durationParts[1]) ? (int)str_replace('m', '', $durationParts[1]) : 0;
        $arrival = clone $departure;
        $arrival->add(new DateInterval("PT{$hours}H{$minutes}M"));
        ?>
        <div class="section">
            <h2 class="section-title"><i class="fas fa-user"></i> معلومات المسافر</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">الاسم الكامل:</span>
                    <span class="info-value"><?= $booking['first_name'] . ' ' . $booking['last_name'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">رقم الجواز:</span>
                    <span class="info-value"><?= $booking['passport_number'] ?></span>
                </div>
            </div>
        </div>
        <div class="section">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> معلومات الحجز</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">رقم الحجز:</span>
                    <span class="info-value"><?= $booking_id ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">حالة الدفع:</span>
                    <span class="info-value"><?= $booking['is_paid'] ? 'تم الدفع' : 'لم يتم الدفع' ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">تاريخ الحجز:</span>
                    <span class="info-value"><?= $booking['booking_date'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">رقم المقعد:</span>
                    <span class="info-value"><?= $booking['seat_ID'] ?></span>
                </div>
            </div>
        </div>

        <div class="section">
            <h2 class="section-title"><i class="fas fa-plane"></i> معلومات الرحلة</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">رقم الرحلة:</span>
                    <span class="info-value"><?= $booking['flight_ID'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">وقت المغادرة:</span>
                    <span class="info-value"><?= $departure->format('Y-m-d H:i') ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">المغادرة:</span>
                    <span class="info-value"><?= $booking['departure_city'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">الوجهة:</span>
                    <span class="info-value"><?= $booking['destination_city'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">مدة الرحلة:</span>
                    <span class="info-value"><?= $booking['trip_duration'] ?> ساعات </span>
                </div>
            </div>
        </div>


    </div>

</div>
</body>
</html>
