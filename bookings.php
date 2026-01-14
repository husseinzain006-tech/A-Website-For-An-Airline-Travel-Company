<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <title>إدارة الحجوزات</title>
    <link rel="stylesheet" href="stylebookings.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="background"></div>
<nav class="navbar">
    <a href="home.php" class="nav-brand">ATC</a>
    <h2>إدارة الحجوزات</h2>
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
        <h2>بيانات الحجوزات</h2>
    </div>
    <div class="search-filter-section">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث برقم الحجز أو رقم الجواز">
        </div>
        <div class="filter-section">
            <div class="filter-group">
                <label>حالة الدفع</label>
                <div class="payment-filters">
                    <label class="payment-option"><input type="checkbox" name="payment" value="1" > تم الدفع</label>
                    <label class="payment-option"><input type="checkbox" name="payment" value="0" > لم يتم الدفع</label>
                </div>
            </div>
            <button class="filter-btn" id="applyFilters">
                <i class="fas fa-search"></i> بحث
            </button>
            <button class="reset-btn" id="resetFilters">
                <i class="fas fa-sync-alt"></i> إعادة الضبط
            </button>
        </div>
    </div>
    <div class="no-results">
        <div class="no-results-icon">
            <i class="fas fa-search"></i>
        </div>
        <p>لم يتم العثور على حجوزات تطابق معايير البحث</p>
    </div>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>رقم الحجز</th>
                <th>رقم جواز السفر</th>
                <th>رقم الرحلة</th>
                <th>رقم المقعد</th>
                <th>الكلفة الإجمالية</th>
                <th>الوزن الزائد</th>
                <th>تاريخ الحجز</th>
                <th>هل تم الدفع</th>
                <th colspan="3">الإجراءات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            require_once 'connecting.php';
            $stmt = $pdo->query("SELECT * FROM booking");
            $bookings = $stmt->fetchAll();
            foreach ($bookings as $booking):
                ?>
                <tr data-bookingid="<?= $booking['booking_id'] ?>" data-paid="<?= $booking['is_paid'] ?>">
                    <td dir="auto"><?= $booking['booking_id'] ?></td>
                    <td dir="auto"><?= $booking['passport_number'] ?></td>
                    <td dir="auto"><?= $booking['flight_ID']?></td>
                    <td dir="auto"><?= $booking['seat_ID'] ?></td>
                    <td dir="auto"><?= $booking['total_cost'] ?> SP</td>
                    <td dir="auto"><?= $booking['overweight'] ?> KG</td>
                    <td dir="auto"><?= $booking['booking_date'] ?></td>
                    <td dir="auto">
                        <?php if ($booking['is_paid'] == 1): ?>
                            <span class="payment-badge paid">نعم</span>
                        <?php else: ?>
                            <span class="payment-badge not-paid">لا</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="booking_details.php?bookingid=<?= $booking['booking_id'] ?>">
                            <button class='details-button' title="عرض التفاصيل">
                                <svg class='details-svgIcon' viewBox='0 0 576 512'>
                                    <path d='M288 32c-80.8 0-145.5 36.8-192.6 80.6C48.6 156 17.3 208 2.5 243.7c-3.3 7.9-3.3 16.7 0 24.6C17.3 304 48.6 356 95.4 399.4C142.5 443.2 207.2 480 288 480s145.5-36.8 192.6-80.6c46.8-43.5 78.1-95.4 93-131.1c3.3-7.9 3.3-16.7 0-24.6c-14.9-35.7-46.2-87.7-93-131.1C433.5 68.8 368.8 32 288 32zM144 256a144 144 0 1 1 288 0 144 144 0 1 1 -288 0zm144-64c0 35.3-28.7 64-64 64c-7.1 0-13.9-1.2-20.3-3.3c-5.5-1.8-11.9 1.6-11.7 7.4c.3 6.9 1.3 13.8 3.2 20.7c13.7 51.2 66.4 81.6 117.6 67.9s81.6-66.4 67.9-117.6c-11.1-41.5-47.8-69.4-88.6-71.1c-5.8-.2-9.2 6.1-7.4 11.7c2.1 6.4 3.3 13.2 3.3 20.3z'></path>
                                </svg>
                            </button>
                        </a>
                    </td>
                    <td>
                        <a href="editbookings.php?bookingid=<?= $booking['booking_id'] ?>">
                            <button class='edit-button' title="تعديل">
                                <svg class='edit-svgIcon' viewBox='0 0 512 512'>
                                    <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                </svg>
                            </button>
                        </a>
                    </td>
                    <td>
                        <button onclick="confirmDelete('<?= $booking['booking_id'] ?>')" class='delete-button' title="حذف">
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
    function confirmDelete(bookingid) {
        if (confirm('هل أنت متأكد من حذف الحجز ؟')) {
            fetch(`deletebookings.php?booking=${bookingid}`)
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        const row = document.querySelector(`tr[data-bookingid="${bookingid}"]`);
                        if (row) row.remove();
                        alert('تم الحذف بنجاح!');
                        checkNoResults();
                    } else {
                        alert('حدث خطأ أثناء الحذف: ' + (data.error || 'خطأ غير معروف'));
                    }
                })
        }
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const applyBtn = document.getElementById('applyFilters');
        const resetBtn = document.getElementById('resetFilters');
        const bookingRows = document.querySelectorAll('tbody tr');
        const noResultsMsg = document.querySelector('.no-results');
        const tableContainer = document.querySelector('.table-container');

        function applyFilters() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const paymentCheckboxes = document.querySelectorAll('input[name="payment"]:checked');
            const selectedPayments = Array.from(paymentCheckboxes).map(cb => cb.value);

            let visibleRows = 0;
            bookingRows.forEach(row => {
                const bookingId = row.cells[0].textContent.toLowerCase();
                const passport = row.cells[1].textContent.toLowerCase();
                const isPaid = row.getAttribute('data-paid');

                const matchesSearch = searchTerm === '' ||
                    bookingId.includes(searchTerm) ||
                    passport.includes(searchTerm);

                const matchesPayment = selectedPayments.length === 0 ||
                    selectedPayments.includes(isPaid);

                if (matchesSearch && matchesPayment) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleRows === 0 && (searchTerm !== '' || selectedPayments.length > 0)) {
                noResultsMsg.style.display = 'block';
                tableContainer.style.display = 'none';
            } else {
                noResultsMsg.style.display = 'none';
                tableContainer.style.display = 'block';
            }
        }

        function resetFilters() {
            searchInput.value = '';

            document.querySelectorAll('input[name="payment"]').forEach(checkbox => {
                checkbox.checked = true;
            });

            bookingRows.forEach(row => {
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

        searchInput.addEventListener('input', applyFilters);

        document.querySelectorAll('input[name="payment"]').forEach(checkbox => {
            checkbox.addEventListener('change', applyFilters);
        });

        applyBtn.addEventListener('click', applyFilters);
        resetBtn.addEventListener('click', resetFilters);
        searchInput.focus();
    });
</script>
</body>
</html>