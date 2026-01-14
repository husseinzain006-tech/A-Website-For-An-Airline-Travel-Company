<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الرحلة</title>
    <link rel="stylesheet" href="styleeditflights.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-plane"></i> تعديل بيانات الرحلة</h1>
        <div class="header-buttons">
            <a href="customers.php" class="btn">
                <i class="fas fa-users"></i> الزبائن
            </a>
            <a href="flights.php" class="btn">
                <i class="fas fa-plane"></i> الرحلات
            </a>
        </div>
    </div>
    <div class="content">
        <div class="form-section">
            <h2 class="section-title"><i class="fas fa-edit"></i> تفاصيل الرحلة</h2>
            <?php
            require_once 'connecting.php';
            $flightid = isset($_GET['flightid']) ? $_GET['flightid'] : 0;
            $stmt = $pdo->prepare("SELECT * FROM flight WHERE flight_ID = ?");
            $stmt->execute([$flightid]);
            $flight = $stmt->fetch();
            if (!$flight) {
                die("الرحلة غير موجود!");
            }
            $error = '';
            $successMsg = '';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $departure = $_POST['departure'];
                $destination = $_POST['destination'];
                $deptime = $_POST['deptime'];
                $tripduration = $_POST['tripduration'];
                $seatscount = $_POST['seatscount'];
                $price = $_POST['price'];
                $overweight = $_POST['overweight'];
                $status = $_POST['status'];

                if (empty($departure) || empty($destination) || empty($deptime) || empty($tripduration) || empty($seatscount) || empty($price) || empty($overweight) || empty($status)) {
                    $error = 'الرجاء ملء الحقول المطلوبة!';
                } else {
                    $transactionStarted = false;
                    try {
                        $pdo->beginTransaction();
                        $transactionStarted = true;

                        $stmt = $pdo->prepare("
                            UPDATE flight 
                            SET 
                                departure_city = :departure, 
                                destination_city = :destination, 
                                departure_time = :deptime, 
                                trip_duration = :tripduration, 
                                seats_count = :seatscount, 
                                status = :status,
                                price = :price,
                                overweight_charge = :overweight
                            WHERE flight_ID = :flightid ");
                        $stmt->execute([
                            ':departure' => $departure,
                            ':destination' => $destination,
                            ':deptime' => $deptime,
                            ':tripduration' => $tripduration,
                            ':seatscount' => $seatscount,
                            ':price' => $price,
                            ':overweight' => $overweight,
                            ':status' => $status,
                            ':flightid' => $flightid
                        ]);

                        $current_seats_stmt = $pdo->prepare("SELECT COUNT(*) FROM seat WHERE flight_ID = ?");
                        $current_seats_stmt->execute([$flightid]);
                        $current_seats_count = $current_seats_stmt->fetchColumn();

                        if ($seatscount > $current_seats_count) {
                            $start_seat_num = $current_seats_count + 1;
                            for ($i = $start_seat_num; $i <= $seatscount; $i++) {
                                $insertSeat = $pdo->prepare("INSERT INTO seat (flight_ID, seat_ID, is_booked) VALUES (?, ?, 0)");
                                $insertSeat->execute([$flightid, $i]);
                            }
                        }
                        $pdo->commit();
                        $transactionStarted = false;

                        $successMsg = "تم تحديث بيانات الرحلة بنجاح!";
                        $stmt = $pdo->prepare("SELECT * FROM flight WHERE flight_ID = ?");
                        $stmt->execute([$flightid]);
                        $flight = $stmt->fetch();
                    } catch (Exception $e) {
                        if ($transactionStarted) {
                            $pdo->rollBack();
                        }
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
            <div class="flight-id-display">
                <i class="fas fa-hashtag"></i> رقم الرحلة: <?= $flightid ?>
            </div>
            <form method="post" id="flightForm">
                <div class="form-group">
                    <label for="departure"><i class="fas fa-plane-departure"></i> مدينة الانطلاق</label>
                    <select name="departure" id="departure" class="form-control" required>
                        <?php
                        $departureCities = [
                            "Damascus (Syria)",
                            "Aleppo(Syria)",
                            "Ankara(Turkey)",
                            "Baghdad(Iraq)",
                            "Amman(Jordan)",
                            "Beirut(Lebanon)"
                        ];
                        foreach ($departureCities as $city) {
                            $selected = ($flight['departure_city'] === $city) ? 'selected' : '';
                            echo "<option value='$city' $selected>$city</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="destination"><i class="fas fa-plane-arrival"></i> مدينة الوجهة</label>
                    <select name="destination" id="destination" class="form-control" required>
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
                        foreach ($destinationCities as $city) {
                            $selected = ($flight['destination_city'] === $city) ? 'selected' : '';
                            echo "<option value='$city' $selected>$city</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="deptime"><i class="fas fa-clock"></i> وقت المغادرة</label>
                    <input type='datetime-local' name='deptime' id="deptime" class="form-control" value="<?= date('Y-m-d\TH:i', strtotime($flight['departure_time'])) ?>" required>
                </div>
                <div class="form-group">
                    <label for="tripduration"><i class="fas fa-hourglass-half"></i> مدة الرحلة</label>
                    <input type="text" name="tripduration" id="tripduration" class="form-control" value="<?= htmlspecialchars($flight['trip_duration']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="seatscount"><i class="fas fa-chair"></i> عدد المقاعد</label>
                    <input class="form-control small-input" type="number" name="seatscount" id="seatscount" value="<?= htmlspecialchars($flight['seats_count']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="price"><i class="fas fa-money-bill-wave"></i> السعر</label>
                    <input class="form-control small-input" type="number" name="price" id="price" value="<?= htmlspecialchars($flight['price']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="overweight"><i class="fas fa-weight"></i> رسوم الوزن الزائد</label>
                    <input class="form-control small-input" type="number" name="overweight" id="overweight" value="<?= htmlspecialchars($flight['overweight_charge']) ?>" required>
                </div>
                <div class="form-group">
                    <label><i class="fas fa-info-circle"></i> حالة الرحلة</label>
                    <div class="status-options">
                        <div class="status-option">
                            <input type="radio" id="status-upcoming" name="status" value="upcoming" <?= $flight['status'] == 'upcoming' ? 'checked' : '' ?> required>
                            <label for="status-upcoming" class="status-upcoming">لم تنطلق بعد</label>
                        </div>
                        <div class="status-option">
                            <input type="radio" id="status-cancelled" name="status" value="cancelled" <?= $flight['status'] == 'cancelled' ? 'checked' : '' ?>>
                            <label for="status-cancelled" class="status-cancelled">ملغية</label>
                        </div>
                    </div>
                </div>
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> حفظ التعديلات
                    </button>
                    <a href="flights.php" class="btn-cancel">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
        <div class="summary-section">
            <h2 class="section-title"><i class="fas fa-receipt"></i> ملخص الرحلة</h2>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">رقم الرحلة:</span>
                    <span class="summary-value" id="summary-flightid"><?= $flightid ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">مسار الرحلة:</span>
                    <span class="summary-value" id="summary-route">
                        <?= htmlspecialchars($flight['departure_city']) ?> → <?= htmlspecialchars($flight['destination_city']) ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">وقت المغادرة:</span>
                    <span class="summary-value" id="summary-deptime">
                        <?php
                        $departureTime = new DateTime($flight['departure_time']);
                        echo $departureTime->format('d/m/Y H:i');
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">مدة الرحلة:</span>
                    <span class="summary-value" id="summary-tripduration"><?= htmlspecialchars($flight['trip_duration']) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">عدد المقاعد:</span>
                    <span class="summary-value" id="summary-seatscount"><?= htmlspecialchars($flight['seats_count']) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">السعر:</span>
                    <span class="summary-value" id="summary-price"><?= htmlspecialchars($flight['price']) ?> ل.س </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رسوم الوزن الزائد:</span>
                    <span class="summary-value" id="summary-overweight"><?= htmlspecialchars($flight['overweight_charge']) ?> ل.س </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">حالة الرحلة:</span>
                    <span class="summary-value" id="summary-status">
                        <?php
                        if ($flight['status'] == 'upcoming') {
                            echo '<span class="status-upcoming">لم تنطلق بعد</span>';
                        } elseif ($flight['status'] == 'cancelled') {
                            echo '<span class="status-cancelled">ملغية</span>';
                        }
                        ?>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function formatDateTime(datetime) {
            if (!datetime) return '--';
            const dateObj = new Date(datetime);
            return dateObj.toLocaleString('ar-EG', {
                year: 'numeric',
                month: 'long',
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }

        function getStatusText(status) {
            if (status === 'upcoming') {
                return '<span class="status-upcoming">لم تنطلق بعد</span>';
            } else if (status === 'cancelled') {
                return '<span class="status-cancelled">ملغية</span>';
            }
            return '--';
        }

        function updateSummary() {
            const departure = $('#departure option:selected').text();
            const destination = $('#destination option:selected').text();
            $('#summary-route').text(departure + ' → ' + destination);
            const deptime = $('#deptime').val();
            $('#summary-deptime').text(formatDateTime(deptime));
            $('#summary-tripduration').text($('#tripduration').val() || '--');
            $('#summary-seatscount').text($('#seatscount').val() || '--');
            $('#summary-price').text(($('#price').val() || '--') + ' ل.س');
            $('#summary-overweight').text(($('#overweight').val() || '--') + ' ل.س');

            const status = $('input[name="status"]:checked').val();
            $('#summary-status').html(getStatusText(status));
        }

        updateSummary();
        $('select, input').on('change input', updateSummary);
    });
</script>
</body>
</html>