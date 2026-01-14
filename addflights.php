<?php
session_start();
require_once 'connecting.php';

try {
    $stmt = $pdo->query("DESCRIBE flight");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("خطأ في الاتصال بقاعدة البيانات: " . $e->getMessage());
}

$error = '';
$successMsg = '';
$flightData = [
    'flightid' => '',
    'departure' => '',
    'destination' => '',
    'deptime' => '',
    'tripduration' => '',
    'seatscount' => '',
    'price' => '',
    'overweight' => ''
];
$flightid = $flightData['flightid'];
$departure = $flightData['departure'];
$destination = $flightData['destination'];
$deptime = $flightData['deptime'];
$tripduration = $flightData['tripduration'];
$seatscount = $flightData['seatscount'];
$price = $flightData['price'];
$overweight = $flightData['overweight'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $flightData = [
        'flightid' => isset($_POST['flightid']) ? $_POST['flightid'] : '',
        'departure' => isset($_POST['departure']) ? $_POST['departure'] : '',
        'destination' => isset($_POST['destination']) ? $_POST['destination'] : '',
        'deptime' => isset($_POST['deptime']) ? $_POST['deptime'] : '',
        'tripduration' => isset($_POST['tripduration']) ? $_POST['tripduration'] : '',
        'seatscount' => isset($_POST['seatscount']) ? $_POST['seatscount'] : '',
        'price' => isset($_POST['price']) ? $_POST['price'] : '',
        'overweight' => isset($_POST['overweight']) ? $_POST['overweight'] : ''
    ];
    $flightid = $flightData['flightid'];
    $departure = $flightData['departure'];
    $destination = $flightData['destination'];
    $deptime = $flightData['deptime'];
    $tripduration = $flightData['tripduration'];
    $seatscount = $flightData['seatscount'];
    $price = $flightData['price'];
    $overweight = $flightData['overweight'];
    if (in_array('', $flightData)) {
        $error = 'الرجاء ملء جميع الحقول المطلوبة!';
    } else {
        try {
            $checkStmt = $pdo->prepare("SELECT flight_id FROM flight WHERE flight_id = ?");
            $checkStmt->execute([$flightData['flightid']]);

            if ($checkStmt->rowCount() > 0) {
                $error = "رقم الرحلة ({$flightData['flightid']}) موجود مسبقاً!";
            } else {
                $q = "INSERT INTO flight (
                    flight_id, 
                    departure_city, 
                    destination_city, 
                    departure_time, 
                    trip_duration, 
                    seats_count, 
                    status,
                    price, 
                    overweight_charge
                ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

                $ps = $pdo->prepare($q);
                $ps->execute([
                    $flightData['flightid'],
                    $flightData['departure'],
                    $flightData['destination'],
                    $flightData['deptime'],
                    $flightData['tripduration'],
                    $flightData['seatscount'],
                    'upcoming',
                    $flightData['price'],
                    $flightData['overweight']
                ]);
                $seatValues = [];
                for ($i = 1; $i <= $flightData['seatscount']; $i++) {
                    $seatValues[] = "({$flightData['flightid']}, $i, 0)";
                }

                if (!empty($seatValues)) {
                    $pdo->exec("INSERT INTO seat (flight_id, seat_id, is_booked) VALUES " . implode(',', $seatValues));
                }

                $successMsg = "تم إضافة الرحلة بنجاح!";
                $flightid = $departure = $destination = $deptime = $tripduration = $seatscount = $price = $overweight = "";
            }
        } catch (PDOException $e) {
            $error = "حدث خطأ في قاعدة البيانات: " . $e->getMessage();
        }
    }
}
function convertDurationToText($duration) {
    if (empty($duration)) return '';
    if (preg_match('/(ساعة|ساعات|دقيقة|دقائق)/', $duration)) {
        return $duration;
    }
    if (is_numeric($duration)) {
        $hours = floor($duration);
        $minutes = round(($duration - $hours) * 60);

        $text = '';
        if ($hours > 0) {
            $text .= $hours . ' ' . ($hours == 1 ? 'ساعة' : 'ساعات');
        }
        if ($minutes > 0) {
            if ($hours > 0) $text .= ' و ';
            $text .= $minutes . ' ' . ($minutes == 1 ? 'دقيقة' : 'دقائق');
        }
        return $text ?: '';
    }

    return $duration;
}
$tripduration_display = convertDurationToText($tripduration);
?>
<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة رحلة جديدة</title>
    <link rel="stylesheet" href="styleaddflight.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-plane"></i> إدارة الرحلات </h1>
        <div class="header-buttons">
            <a href="flights.php" class="btn">
                <i class="fas fa-list"></i> الرحلات
            </a>
            <a href="home.php" class="btn">
                <i class="fas fa-home-alt"></i> الصفحة الرئيسية
            </a>
        </div>
    </div>
    <div class="content">
        <div class="form-section">
            <h2 class="section-title"><i class="fas fa-plus-circle"></i> إضافة رحلة جديدة</h2>
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
            <form method="post" action="addflights.php" id="flightForm">
                <div class="form-group">
                    <label for="flightid"><i class="fas fa-hashtag"></i> رقم الرحلة</label>
                    <input type="number" id="flightid" name="flightid" class="form-control" value="<?= $flightid ?>" required >
                </div>
                <div class="form-group">
                    <label for="departure"><i class="fas fa-plane-departure"></i> مدينة الانطلاق</label>
                    <select id="departure" name="departure" class="form-control" required>
                        <option value="">اختر مدينة الانطلاق</option>
                        <?php
                        $departureCities = [
                            "Damascus (Syria)", "Aleppo(Syria)", "Ankara(Turkey)",
                            "Baghdad(Iraq)", "Amman(Jordan)", "Beirut(Lebanon)"
                        ];
                        foreach ($departureCities as $city) {
                            $selected = ($departure === $city) ? 'selected' : '';
                            echo "<option value='$city' $selected>$city</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination"><i class="fas fa-plane-arrival"></i> مدينة الوجهة</label>
                    <select id="destination" name="destination" class="form-control" required>
                        <option value="">اختر مدينة الوجهة</option>
                        <?php
                        $destinationCities = [
                            "Algiers(Algeria)", "Amman(Jordan)", "Amsterdam(Netherlands)",
                            "Baghdad(Iraq)", "Belgrade(Serbia)", "Berlin(Germany)",
                            "Brasília(Brazil)", "Buenos Aires(Argentina)", "Cairo(Egypt)",
                            "Caracas(Venezuela)", "Khartoum(Sudan)", "Kuwait City(Kuwait)",
                            "Kyiv(Ukraine)", "Malé(Maldives)", "Managua(Nicaragua)",
                            "Manama(Bahrain)", "Mexico City(Mexico)", "Minsk(Belarus)",
                            "Moscow(Russia)", "Muscat(Oman)", "Nouakchott(Mauritania)",
                            "Oslo(Norway)", "Ottawa(Canada)", "Paris(France)",
                            "Quito(Ecuador)", "Riyadh(Saudi Arabia)", "Sana'a(Yemen)",
                            "Santo Domingo(Dominican Republic)", "Sarajevo(Bosnia and Herzegovina)",
                            "Stockholm(Sweden)", "Sucre(Bolivia)", "Tirana(Albania)",
                            "Washington D.C(United States)"
                        ];
                        sort($destinationCities);
                        foreach ($destinationCities as $city) {
                            $selected = ($destination === $city) ? 'selected' : '';
                            echo "<option value='$city' $selected>$city</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="deptime"><i class="fas fa-calendar-alt"></i> تاريخ ووقت المغادرة</label>
                    <input type="datetime-local" id="deptime" name="deptime" class="form-control" value="<?= $deptime ?>" required>
                </div>
                <div class="form-group">
                    <label for="tripduration"><i class="fas fa-clock"></i> مدة الرحلة</label>
                    <input type="text" id="tripduration" name="tripduration" class="form-control" value="<?= $tripduration_display ?>" required>
                </div>
                <div class="form-group">
                    <label for="seatscount"><i class="fas fa-chair"></i> عدد المقاعد</label>
                    <input type="number" id="seatscount" name="seatscount" class="form-control" value="<?= $seatscount ?>" required >
                </div>
                <div class="form-group">
                    <label for="price"><i class="fas fa-money-bill-wave"></i> سعر التذكرة (ل.س)</label>
                    <input type="number" id="price" name="price" class="form-control" value="<?= $price ?>" required >
                </div>
                <div class="form-group">
                    <label for="overweight"><i class="fas fa-weight-hanging"></i> رسوم الوزن الزائد (ل.س/كجم)</label>
                    <input type="number" id="overweight" name="overweight" class="form-control" value="<?= $overweight ?>" required >
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-paper-plane"></i> إضافة الرحلة الجديدة
                </button>
            </form>
        </div>
        <div class="summary-section">
            <h2 class="section-title"><i class="fas fa-info-circle"></i> ملخص الرحلة</h2>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">رقم الرحلة:</span>
                    <span class="summary-value" id="summary-flightid">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">مسار الرحلة:</span>
                    <span class="summary-value">
                        <span id="summary-departure">--</span>
                        <i class="fas fa-long-arrow-alt-right flight-icon"></i>
                        <span id="summary-destination">--</span>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">وقت المغادرة:</span>
                    <span class="summary-value" id="summary-deptime">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">مدة الرحلة:</span>
                    <span class="summary-value" id="summary-duration">-- <span>ساعات</span></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">عدد المقاعد:</span>
                    <span class="summary-value" id="summary-seats">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">سعر التذكرة:</span>
                    <span class="summary-value" id="summary-price">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رسوم الوزن الزائد:</span>
                    <span class="summary-value" id="summary-overweight">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">حالة الرحلة:</span>
                    <span class="summary-value">
                        <span class="status-indicator status-upcoming">لم تنطلق بعد</span>
                    </span>
                </div>
            </div>
            <div class="info-box">
                <p><i class="fas fa-info-circle"></i> سيتم تعيين حالة الرحلة تلقائياً إلى "لم تنطلق بعد" عند الإضافة</p>
                <p><i class="fas fa-couch"></i> سيتم إنشاء مقاعد الرحلة تلقائياً بناءً على العدد المحدد</p>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function formatDurationText(duration) {
            if (!duration || duration.trim() === '') return '--';

            if (duration.includes('ساعة') || duration.includes('دقيقة')) {
                return duration;
            }
            if (!isNaN(duration)) {
                const hours = Math.floor(duration);
                const minutes = Math.round((duration - hours) * 60);

                let text = '';
                if (hours > 0) {
                    text += hours + ' ' + (hours === 1 ? 'ساعة' : 'ساعات');
                }
                if (minutes > 0) {
                    if (hours > 0) text += ' و ';
                    text += minutes + ' ' + (minutes === 1 ? 'دقيقة' : 'دقائق');
                }
                return text || '';
            }

            return duration;
        }

        function formatArabicDate(dateString) {
            if (!dateString) return '--';

            const date = new Date(dateString);
            const options = {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit',
                hour12: true
            };

            return date.toLocaleString('ar-EG', options);
        }

        function formatCurrency(amount) {
            if (!amount) return '--';
            return parseInt(amount).toLocaleString('ar-EG') + ' ل.س';
        }

        function updateSummary() {
            $('#summary-flightid').text($('#flightid').val() || '--');

            $('#summary-departure').text($('#departure').val().split('(')[0] || '--');
            $('#summary-destination').text($('#destination').val().split('(')[0] || '--');

            $('#summary-deptime').text(formatArabicDate($('#deptime').val()));

            const duration = $('#tripduration').val();
            $('#summary-duration').text(formatDurationText(duration));

            $('#summary-seats').text($('#seatscount').val() || '--');

            $('#summary-price').text(formatCurrency($('#price').val()));
            $('#summary-overweight').text(formatCurrency($('#overweight').val()));
        }

        updateSummary();
        $('input, select').on('input change', updateSummary);

        const now = new Date();
        const minDateTime = new Date(now.getTime() - now.getTimezoneOffset() * 60000)
            .toISOString()
            .slice(0, 16);
        $('#deptime').attr('min', minDateTime);
        }).on('blur', function() {
            this.placeholder = '';
        });
</script>
</body>
</html>