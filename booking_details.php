<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تفاصيل الحجز</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .container {
            width: 100%;
            max-width: 1000px;
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
        .header-buttons .btn a {
            color: white;
            text-decoration: none;
            display: block;
        }
        .btn-print {
            background: #9b59b6;
            color: white;
            border: none;
            padding: 12px 20px;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .btn-print:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 15px rgba(0, 0, 0, 0.3);
        }
        .content {
            display: flex;
            flex-wrap: wrap;
        }
        .section {
            flex: 1;
            min-width: 300px;
            padding: 30px;
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
        .qr-section {
            text-align: center;
            margin: 30px 0;
        }
        .qr-code {
            width: 200px;
            height: 200px;
            margin: 0 auto;
            background: white;
            padding: 10px;
            border: 1px solid #eee;
        }
        .customer-link {
            margin-top: 15px;
            font-size: 14px;
            color: #666;
            word-break: break-all;
        }
        .qr-actions {
            margin-top: 15px;
        }

        @media print {
            body * {
                visibility: hidden;
            }
            .qr-section, .qr-section * {
                visibility: visible;
            }
            .qr-section {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
                height: 100%;
                display: flex;
                flex-direction: column;
                justify-content: center;
                align-items: center;
            }
            .qr-code {
                width: 300px;
                height: 300px;
            }
            .customer-link {
                font-size: 18px;
                margin-top: 30px;
            }
        }

        @media (max-width: 768px) {
            .header-buttons {
                flex-direction: column;
                gap: 10px;
            }
            .header-buttons .btn {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-ticket-alt"></i> تفاصيل الحجز </h1>
        <div class="header-buttons">
            <a href="bookings.php" class="btn">
                <i class="fas fa-list"></i>  الحجوزات
            </a>
            <a href="flights.php" class="btn">
                <i class="fas fa-plane"></i> الرحلات
            </a>
        </div>
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
            die("الحجز غير موجود! الرجاء التأكد من رقم الحجز");
        }

        $departure = new DateTime($booking['departure_time']);
        $durationParts = explode('h', $booking['trip_duration']);
        $hours = (int)$durationParts[0];
        $minutes = isset($durationParts[1]) ? (int)str_replace('m', '', $durationParts[1]) : 0;
        $arrival = clone $departure;
        $arrival->add(new DateInterval("PT{$hours}H{$minutes}M"));
        $customer_link = "http://" . $_SERVER['HTTP_HOST'] . "/A_website_for_an_airline_travel_company/booking_customer.php?bookingid=" . $booking_id;
        ?>

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
                <div class="info-item">
                    <span class="info-label">الكلفة الإجمالية:</span>
                    <span class="info-value"><?= number_format($booking['total_cost']) ?> ل.س</span>
                </div>
                <div class="info-item">
                    <span class="info-label">الوزن الزائد:</span>
                    <span class="info-value"><?= $booking['overweight'] ?> كجم</span>
                </div>
            </div>

            <h2 class="section-title"><i class="fas fa-plane"></i> معلومات الرحلة</h2>
            <div class="info-grid">
                <div class="info-item">
                    <span class="info-label">رقم الرحلة:</span>
                    <span class="info-value"><?= $booking['flight_ID'] ?></span>
                </div>  <div class="info-item">
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
                <div class="info-item">
                    <span class="info-label">البريد الإلكتروني:</span>
                    <span class="info-value"><?= $booking['email'] ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">رقم الهاتف:</span>
                    <span class="info-value"><?= $booking['phone'] ?></span>
                </div>
            </div>

            <div class="qr-section">
                <h2 class="section-title"><i class="fas fa-qrcode"></i> QR Code</h2>
                <div class="qr-code">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=<?= urlencode($customer_link) ?>" alt="QR Code">
                </div>
                <div class="customer-link">
                    <?= $customer_link ?>
                </div>
                <div class="qr-actions">
                    <button class="btn-print" onclick="window.print()">
                        <i class="fas fa-print"></i> طباعة
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const currentUrl = window.location.href;
        new QRCode(document.getElementById("qrcode"), {
            text: currentUrl,
            width: 150,
            height: 150,
            colorDark: "#000000",
            colorLight: "#ffffff",
            correctLevel: QRCode.CorrectLevel.H
        });
    });


    function downloadQR() {
        const canvas = document.querySelector("#qrcode canvas");
        const link = document.createElement('a');
        link.download = 'qr_code_booking_<?= $booking_id ?>.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
    }
</script>
</body>
</html>