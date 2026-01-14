<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <title>إدارة الزبائن</title>
    <link rel="stylesheet" href="stylecustomers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="background"></div>
<nav class="navbar">
    <a href="home.php" class="nav-brand">ATC</a>
    <h2>إدارة الزبائن</h2>
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
<div class="main-content">
    <div class="header-section">
        <h2>بيانات الزبائن</h2>
        <a href="addcustomers.php">
            <button class="butoo" type="submit">
                <i class="fas fa-user-plus"></i> إضافة زبون جديد
            </button>
        </a>
    </div>
    <div class="search-filter-section">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث برقم الجواز أو الاسم">
        </div>
        <button class="filter-btn" id="applySearch">
            <i class="fas fa-search"></i> بحث
        </button>
        <button class="reset-btn" id="resetSearch">
            <i class="fas fa-sync-alt"></i> إعادة الضبط
        </button>
    </div>
    <div class="no-results">
        <div class="no-results-icon">
            <i class="fas fa-search"></i>
        </div>
        <p>لم يتم العثور على زبائن يطابقون معايير البحث</p>
    </div>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>رقم جواز السفر</th>
                <th>الاسم</th>
                <th>الأب</th>
                <th>الكنية</th>
                <th>الايميل</th>
                <th>الهاتف</th>
                <th>تاريخ الميلاد</th>
                <th colspan="2">الإجراءات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            require_once 'connecting.php';
            $stmt = $pdo->query("SELECT * FROM customers");
            $customers = $stmt->fetchAll();
            foreach ($customers as $customer):?>
                <tr data-passid="<?= $customer['passport_number'] ?>">
                    <td dir="auto"><?= $customer['passport_number'] ?></td>
                    <td dir="auto"><?= $customer['first_name'] ?></td>
                    <td dir="auto"><?= $customer['father_name']?></td>
                    <td dir="auto"><?= $customer['last_name'] ?></td>
                    <td dir="auto"><?= $customer['email'] ?></td>
                    <td dir="auto"><?= $customer['phone'] ?></td>
                    <td dir="auto"><?= $customer['DOB'] ?></td>
                    <td dir="auto">
                        <a href="editcustomers.php?passid=<?= $customer['passport_number'] ?>" class="edit-btn">
                            <button class='edit-button'>
                                <svg class='edit-svgIcon' viewBox='0 0 512 512'>
                                    <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                </svg>
                            </button>
                        </a>
                    </td>
                    <td>
                        <button onclick="confirmDelete('<?= $customer['passport_number'] ?>')" class='delete-button'>
                            <svg class='delete-svgIcon' viewBox='0 0 448 512'>
                                <path d='M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z'></path>
                            </svg>
                        </button>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function confirmDelete(passid) {
        if (confirm('هل أنت متأكد من حذف هذا الزبون؟')) {
            fetch(`deletecustomers.php?passid=${passid}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`tr[data-passid="${passid}"]`);
                        if (row) row.remove();
                        alert('تم حذف الزبون وحجوزاته بنجاح!');
                        checkNoResults();
                    } else {
                        alert('حدث خطأ أثناء الحذف: ' + (data.error || 'خطأ غير معروف'));
                    }
                })
                .catch(error => {
                });
        }
    }
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const applyBtn = document.getElementById('applySearch');
        const resetBtn = document.getElementById('resetSearch');
        const customerRows = document.querySelectorAll('tbody tr');
        const noResultsMsg = document.querySelector('.no-results');
        const tableContainer = document.querySelector('.table-container');
        function applySearch() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            let visibleRows = 0;
            customerRows.forEach(row => {
                const passport = row.cells[0].textContent.toLowerCase();
                const firstName = row.cells[1].textContent.toLowerCase();
                const lastName = row.cells[3].textContent.toLowerCase();
                if (searchTerm === '' ||
                    passport.includes(searchTerm) ||
                    firstName.includes(searchTerm) ||
                    lastName.includes(searchTerm)) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });
            if (visibleRows === 0 && searchTerm !== '') {
                noResultsMsg.style.display = 'block';
                tableContainer.style.display = 'none';
            } else {
                noResultsMsg.style.display = 'none';
                tableContainer.style.display = 'block';
            }
        }
        function resetSearch() {
            searchInput.value = '';
            customerRows.forEach(row => {
                row.style.display = '';
            });
            noResultsMsg.style.display = 'none';
            tableContainer.style.display = 'block';
        }
        function checkNoResults() {
            const visibleRows = document.querySelectorAll('tbody tr:not([style*="display: none"])').length;
            if (visibleRows === 0) {
                noResultsMsg.style.display = 'block';
                tableContainer.style.display = 'none';
            } else {
                noResultsMsg.style.display = 'none';
                tableContainer.style.display = 'block';
            }
        }
        searchInput.addEventListener('input', applySearch);
        applyBtn.addEventListener('click', applySearch);
        resetBtn.addEventListener('click', resetSearch);
        searchInput.focus();
    });
</script>
</body>
</html>