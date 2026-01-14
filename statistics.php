<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <title>الإحصائيات</title>
    <link rel="stylesheet" href="stylestatistics.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="background"></div>
<nav class="navbar">
    <a href="home.php" class="nav-brand">ATC</a>
    <h2>الإحصائيات</h2>
    <div class="nav-links">
        <a href="statistics.php" class="<?= basename($_SERVER['PHP_SELF']) == 'statistics.php' ? 'active' : '' ?>">
            <i class="fas fa-chart-bar"></i> الإحصائيات
        </a>
        <a href="customers.php" class="<?= basename($_SERVER['PHP_SELF']) == 'customers.php' ? 'active' : '' ?>">
            <i class="fas fa-users"></i> الزبائن
        </a>
        <a href="flights.php" class="<?= basename($_SERVER['PHP_SELF']) == 'flights.php' ? 'active' : '' ?>">
            <i class="fas fa-plane"></i> الرحلات
        </a>
        <a href="bookings.php" class="<?= basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : '' ?>">
            <i class="fas fa-ticket-alt"></i> الحجوزات
        </a>
    </div>
</nav>
<?php
require_once 'connecting.php';
$topPriceFlights = $pdo->query("SELECT * FROM flight ORDER BY price DESC LIMIT 5")->fetchAll();

$lowPriceFlights = $pdo->query("SELECT * FROM flight ORDER BY price ASC LIMIT 5")->fetchAll();

$topIncomeFlights = $pdo->query("
    SELECT f.*, SUM(b.total_cost) AS total_income
    FROM flight f
    JOIN booking b ON f.flight_ID = b.flight_ID
    GROUP BY f.flight_ID
    ORDER BY total_income DESC
    LIMIT 5
")->fetchAll();

$topCustomer = $pdo->query("
    SELECT c.*, COUNT(b.booking_id) AS booking_count
    FROM customers c
    JOIN booking b ON c.passport_number = b.passport_number
    GROUP BY c.passport_number
    ORDER BY booking_count DESC
    LIMIT 1
")->fetch();
$departureStats = $pdo->query("
    SELECT LOWER(TRIM(departure_city)) as city, COUNT(*) AS count 
    FROM flight 
    GROUP BY LOWER(TRIM(departure_city))
")->fetchAll();

$destinationStats = $pdo->query("
    SELECT LOWER(TRIM(destination_city)) as city, COUNT(*) AS count 
    FROM flight 
    GROUP BY LOWER(TRIM(destination_city))
")->fetchAll();

$timeStats = $pdo->query("
    SELECT 
        CASE 
            WHEN HOUR(departure_time) BETWEEN 0 AND 5 THEN 'منتصف الليل'
            WHEN HOUR(departure_time) BETWEEN 6 AND 11 THEN 'صباحًا'
            WHEN HOUR(departure_time) BETWEEN 12 AND 17 THEN 'ظهرًا'
            WHEN HOUR(departure_time) BETWEEN 18 AND 23 THEN 'مساءً'
        END AS time_period,
        COUNT(*) AS count
    FROM flight 
    GROUP BY time_period
")->fetchAll();
?>
<div class="statistics-container">
    <div class="stat-card">
        <h3>أعلى 5 رحلات تكلفة</h3>
        <ul>
            <?php foreach ($topPriceFlights as $flight): ?>
                <li>
                    <a href="flight_details.php?flightid=<?= $flight['flight_ID'] ?>">
                        <?= $flight['departure_city'] ?> → <?= $flight['destination_city'] ?>
                        (<?= $flight['price'] ?> ل.س)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="stat-card">
        <h3>أقل 5 رحلات تكلفة</h3>
        <ul>
            <?php foreach ($lowPriceFlights as $flight): ?>
                <li>
                    <a href="flight_details.php?flightid=<?= $flight['flight_ID'] ?>">
                        <?= $flight['departure_city'] ?> → <?= $flight['destination_city'] ?>
                        (<?= $flight['price'] ?> ل.س)
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
    <div class="stat-card">
        <h3>أعلى 5 رحلات دخلاً</h3>
        <ul>
            <?php foreach ($topIncomeFlights as $flight): ?>
                <li>
                    <?= $flight['departure_city'] ?> → <?= $flight['destination_city'] ?>
                    (<?= $flight['total_income'] ?> ل.س)
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="stat-card">
        <h3>الزبون الأكثر حجزاً</h3>
        <?php if ($topCustomer): ?>
            <p>
                <?= $topCustomer['first_name'] ?> <?= $topCustomer['last_name'] ?>
                (<?= $topCustomer['passport_number'] ?>)
            </p>
            <p>عدد الحجوزات: <?= $topCustomer['booking_count'] ?></p>
        <?php else: ?>
            <p>لا يوجد بيانات</p>
        <?php endif; ?>
    </div>

    <div class="chart-card">
        <h3>توزيع الرحلات حسب مدينة الانطلاق</h3>
        <div class="chart-container">
            <canvas id="departureChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3>توزيع الرحلات حسب مدينة الوجهة</h3>
        <div class="chart-container">
            <canvas id="destinationChart"></canvas>
        </div>
    </div>

    <div class="chart-card">
        <h3>عدد الرحلات حسب وقت المغادرة</h3>
        <div class="chart-container">
            <canvas id="timeChart"></canvas>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        function prepareChartData(data) {
            return {
                labels: data.map(item => item.label),
                counts: data.map(item => item.count)
            };
        }

        const departureData = prepareChartData([
            <?php foreach($departureStats as $stat): ?>
            { label: '<?= addslashes($stat['city']) ?>', count: <?= (int)$stat['count'] ?> },
            <?php endforeach; ?>
        ]);

        const destinationData = prepareChartData([
            <?php foreach($destinationStats as $stat): ?>
            { label: '<?= addslashes($stat['city']) ?>', count: <?= (int)$stat['count'] ?> },
            <?php endforeach; ?>
        ]);

        const timeData = prepareChartData([
            <?php foreach($timeStats as $stat): ?>
            { label: '<?= addslashes($stat['time_period']) ?>', count: <?= (int)$stat['count'] ?> },
            <?php endforeach; ?>
        ]);

        const chartColors = [
            '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF',
            '#FF9F40', '#8AC926', '#1982C4', '#6A4C93', '#F15BB5'
        ];

        function createCharts() {
            new Chart(document.getElementById('departureChart'), {
                type: 'pie',
                data: {
                    labels: departureData.labels,
                    datasets: [{
                        data: departureData.counts,
                        backgroundColor: chartColors.slice(0, departureData.labels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            rtl: true
                        }
                    }
                }
            });

            new Chart(document.getElementById('destinationChart'), {
                type: 'pie',
                data: {
                    labels: destinationData.labels,
                    datasets: [{
                        data: destinationData.counts,
                        backgroundColor: chartColors.slice(0, destinationData.labels.length)
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                            rtl: true
                        }
                    }
                }
            });

            new Chart(document.getElementById('timeChart'), {
                type: 'bar',
                data: {
                    labels: timeData.labels,
                    datasets: [{
                        label: 'عدد الرحلات',
                        data: timeData.counts,
                        backgroundColor: [
                            '#3498db',
                            '#2ecc71',
                            '#e74c3c',
                            '#f39c12'
                        ],
                        borderColor: [
                            '#2980b9',
                            '#27ae60',
                            '#c0392b',
                            '#d35400'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                stepSize: 1
                            }
                        },
                        x: {
                            barPercentage: 0.6
                        }
                    },
                    plugins: {
                        legend: {
                            display: false
                        }
                    }
                }
            });
        }

        setTimeout(createCharts, 100);
    });
</script>
</body>
</html>