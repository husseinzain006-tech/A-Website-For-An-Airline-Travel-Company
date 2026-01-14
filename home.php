<?php
session_start();
?>
<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <title>الصفحة الرئيسية</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        :root {
            --primary-color: #3498db;
            --secondary-color: #2c3e50;
            --accent-color: #e74c3c;
            --light-color: #ecf0f1;
            --dark-color: #34455e;
            --success-color: #2ecc71;
            --warning-color: #f39c12;
        }
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        body {
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            color: #333;
            min-height: 100vh;
            position: relative;
            padding-bottom: 80px;
        }
        .background {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, #1a2980, #26d0ce);
            z-index: -1;
        }
        .navbar {
            background: linear-gradient(135deg, #2c3e50, #1a2980);
            color: white;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        .nav-brand {
            font-size: 1.8rem;
            font-weight: bold;
            letter-spacing: 1px;
            color: white;
            text-decoration: none;
        }
        .nav-links {
            display: flex;
            gap: 1.5rem;
        }
        .nav-links a {
            color: white;
            text-decoration: none;
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 4px;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        .nav-links a:hover {
            background-color: rgba(255, 255, 255, 0.1);
            transform: translateY(-2px);
        }
        .nav-links a.active {
            background-color: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .nav-links a.active i {
            color: var(--primary-color);
        }
        .dashboard-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 30px;
            padding: 30px;
            max-width: 1200px;
            margin: 0 auto;
        }
        .dashboard-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            height: 100%;
            text-decoration: none;
        }
        .dashboard-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
        }
        .dashboard-icon {
            font-size: 3.5rem;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        .dashboard-card h3 {
            font-size: 1.5rem;
            color: #2c3e50;
            margin-bottom: 10px;
        }
        .dashboard-card p {
            color: #7f8c8d;
            font-size: 0.9rem;
        }
        .card-statistics {
            border-top: 5px solid #3498db;
        }
        .card-customers {
            border-top: 5px solid #e74c3c;
        }
        .card-flights {
            border-top: 5px solid #2ecc71;
        }
        .card-bookings {
            border-top: 5px solid #f39c12;
        }

        .admin-footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px 0;
            text-align: right;
            z-index: 100;
        }

        .admin-btn {
            background:  #2980b9;
            color: white;
            padding: 12px 30px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: all 0.3s ease;
            font-size: 1rem;
        }

        .admin-btn i {
            font-size: 1.2rem;
        }
    </style>
</head>
<body>
<div class="background"></div>
<nav class="navbar">
    <a href="home.php" class="nav-brand">ATC</a>
    <h2>الصفحة الرئيسية</h2>
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
<div class="dashboard-container">
    <a href="statistics.php" class="dashboard-card card-statistics">
        <div class="dashboard-icon">
            <i class="fas fa-chart-bar"></i>
        </div>
        <h3>الإحصائيات</h3>
        <p>عرض جميع الإحصائيات والبيانات التحليلية</p>
    </a>

    <a href="customers.php" class="dashboard-card card-customers">
        <div class="dashboard-icon">
            <i class="fas fa-users"></i>
        </div>
        <h3>الزبائن</h3>
        <p>إدارة بيانات الزبائن وعمليات الحجز</p>
    </a>

    <a href="flights.php" class="dashboard-card card-flights">
        <div class="dashboard-icon">
            <i class="fas fa-plane"></i>
        </div>
        <h3>الرحلات</h3>
        <p>عرض وإدارة الرحلات الجوية المتاحة</p>
    </a>

    <a href="bookings.php" class="dashboard-card card-bookings">
        <div class="dashboard-icon">
            <i class="fas fa-ticket-alt"></i>
        </div>
        <h3>الحجوزات</h3>
        <p>إدارة جميع عمليات الحجز والتذاكر</p>
    </a>
</div>

<?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
    <div class="admin-footer">
        <a href="signup.php" class="admin-btn">
            <i class="fas fa-user-plus"></i> إنشاء حساب موظف جديد
        </a>
    </div>
<?php endif; ?>

</body>
</html>