<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <title>إدارة الرحلات</title>
    <link rel="stylesheet" href="styleflights.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
<div class="background"></div>
<nav class="navbar">
    <a href="home.php" class="nav-brand">ATC</a>
    <h2>إدارة الرحلات</h2>
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
        <h2>بيانات الرحلات</h2>
        <a href="addflights.php">
            <button class="butoo" type="submit">
                <i class="fas fa-plane-departure"></i> إضافة رحلة جديدة
            </button>
        </a>
    </div>
    <div class="search-filter-section">
        <div class="search-box">
            <input type="text" id="searchInput" placeholder="ابحث برقم الرحلة أو المدينة">
        </div>
        <div class="filter-section">
            <div class="filter-group">
                <label for="departureFilter">مدينة الانطلاق</label>
                <select id="departureFilter">
                    <option value="">جميع المدن</option>
                    <option value="Damascus (Syria)">Damascus (Syria)</option>
                    <option value="Aleppo(Syria)">Aleppo(Syria)</option>
                    <option value="Ankara(Turkey)">Ankara(Turkey)</option>
                    <option value="Baghdad(Iraq)">Baghdad(Iraq)</option>
                    <option value="Amman(Jordan)">Amman(Jordan)</option>
                    <option value="Beirut(Lebanon)">Beirut(Lebanon)</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="destinationFilter">مدينة الوجهة</label>
                <select id="destinationFilter">
                    <option value="">جميع المدن</option>
                    <option value="Algiers(Algeria)">Algiers (Algeria)</option>
                    <option value="Amman(Jordan)">Amman (Jordan)</option>
                    <option value="Amsterdam(Netherlands)">Amsterdam (Netherlands)</option>
                    <option value="Baghdad(Iraq)">Baghdad (Iraq)</option>
                    <option value="Belgrade(Serbia)">Belgrade (Serbia)</option>
                    <option value="Berlin(Germany)">Berlin (Germany)</option>
                    <option value="Brasília(Brazil)">Brasília (Brazil)</option>
                    <option value="Buenos Aires(Argentina)">Buenos Aires (Argentina)</option>
                    <option value="Cairo(Egypt)">Cairo (Egypt)</option>
                    <option value="Caracas(Venezuela)">Caracas (Venezuela)</option>
                    <option value="Khartoum(Sudan)">Khartoum (Sudan)</option>
                    <option value="Kuwait City(Kuwait)">Kuwait City (Kuwait)</option>
                    <option value="Kyiv(Ukraine)">Kyiv (Ukraine)</option>
                    <option value="Malé(Maldives)">Malé (Maldives)</option>
                    <option value="Managua(Nicaragua)">Managua (Nicaragua)</option>
                    <option value="Manama(Bahrain)">Manama (Bahrain)</option>
                    <option value="Mexico City(Mexico)">Mexico City (Mexico)</option>
                    <option value="Minsk(Belarus)">Minsk (Belarus)</option>
                    <option value="Moscow(Russia)">Moscow (Russia)</option>
                    <option value="Muscat(Oman)">Muscat (Oman)</option>
                    <option value="Nouakchott(Mauritania)">Nouakchott (Mauritania)</option>
                    <option value="Oslo(Norway)">Oslo (Norway)</option>
                    <option value="Ottawa(Canada)">Ottawa (Canada)</option>
                    <option value="Paris(France)">Paris (France)</option>
                    <option value="Quito(Ecuador)">Quito (Ecuador)</option>
                    <option value="Riyadh(Saudi Arabia)">Riyadh (Saudi Arabia)</option>
                    <option value="Sana'a(Yemen)">Sana'a (Yemen)</option>
                    <option value="Santo Domingo(Dominican Republic)">Santo Domingo (Dominican Republic)</option>
                    <option value="Sarajevo(Bosnia and Herzegovina)">Sarajevo (Bosnia and Herzegovina)</option>
                    <option value="Stockholm(Sweden)">Stockholm (Sweden)</option>
                    <option value="Sucre(Bolivia)">Sucre (Bolivia)</option>
                    <option value="Tirana(Albania)">Tirana (Albania)</option>
                    <option value="Washington D.C(United States)">Washington D.C (United States)</option>
                </select>
            </div>
            <div class="filter-group">
                <label>حالة الرحلة</label>
                <div class="status-filters">
                    <label class="status-option"><input type="checkbox" name="status" value="upcoming"> لم تنطلق بعد</label>
                    <label class="status-option"><input type="checkbox" name="status" value="departed"> انطلقت</label>
                    <label class="status-option"><input type="checkbox" name="status" value="arrived"> وصلت</label>
                    <label class="status-option"><input type="checkbox" name="status" value="cancelled"> ملغية</label>
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
        <p>لم يتم العثور على رحلات تطابق معايير البحث</p>
    </div>
    <div class="table-container">
        <table>
            <thead>
            <tr>
                <th>رقم الرحلة</th>
                <th>مدينة الانطلاق</th>
                <th>مدينة الوجهة</th>
                <th>وقت المغادرة</th>
                <th>مدة الرحلة</th>
                <th>عدد المقاعد</th>
                <th>السعر</th>
                <th>رسوم الوزن الزائد</th>
                <th>حالة الرحلة</th>
                <th colspan="3">الإجراءات</th>
            </tr>
            </thead>
            <tbody>
            <?php
            require_once 'connecting.php';

            $stmt = $pdo->query("SELECT * FROM flight WHERE status != 'cancelled'");
            $flightsToUpdate = $stmt->fetchAll();

            $now = new DateTime();

            foreach ($flightsToUpdate as $flight) {
                $departure = new DateTime($flight['departure_time']);
                $durationParts = explode('h', $flight['trip_duration']);
                $hours = (int)$durationParts[0];
                $minutes = isset($durationParts[1]) ? (int)str_replace('m', '', $durationParts[1]) : 0;
                $arrival = clone $departure;
                $arrival->add(new DateInterval("PT{$hours}H{$minutes}M"));

                $newStatus = '';
                if ($now < $departure) {
                    $newStatus = 'upcoming';
                } elseif ($now > $arrival) {
                    $newStatus = 'arrived';
                } else {
                    $newStatus = 'departed';
                }

                if ($flight['status'] != $newStatus) {
                    $updateStmt = $pdo->prepare("UPDATE flight SET status = ? WHERE flight_ID = ?");
                    $updateStmt->execute([$newStatus, $flight['flight_ID']]);
                }
            }

            $stmt = $pdo->query("SELECT * FROM flight");
            $flights = $stmt->fetchAll();

            foreach ($flights as $flight):
                $departure = new DateTime($flight['departure_time']);
                $durationParts = explode('h', $flight['trip_duration']);
                $hours = (int)$durationParts[0];
                $minutes = isset($durationParts[1]) ? (int)str_replace('m', '', $durationParts[1]) : 0;
                $arrival = clone $departure;
                $arrival->add(new DateInterval("PT{$hours}H{$minutes}M"));

                if ($flight['status'] === 'cancelled') {
                    $statusText = 'ملغية';
                    $statusClass = 'cancelled';
                }

                else {
                    switch ($flight['status']) {
                        case 'upcoming':
                            $statusText = 'لم تنطلق بعد';
                            $statusClass = 'upcoming';
                            break;
                        case 'departed':
                            $statusText = 'انطلقت';
                            $statusClass = 'departed';
                            break;
                        case 'arrived':
                            $statusText = 'وصلت';
                            $statusClass = 'arrived';
                            break;
                        default:
                            $statusText = 'لم تنطلق بعد';
                            $statusClass = 'upcoming';
                    }
                }
                ?>
                <tr data-flightid="<?= $flight['flight_ID'] ?>" data-departure="<?= $flight['departure_time'] ?>" data-duration="<?= $flight['trip_duration'] ?>" data-status="<?= $statusClass ?>">
                    <td dir="auto"><?= $flight['flight_ID'] ?></td>
                    <td dir="auto"><?= $flight['departure_city'] ?></td>
                    <td dir="auto"><?= $flight['destination_city']?></td>
                    <td dir="auto"><?= $flight['departure_time'] ?></td>
                    <td dir="auto"><?= $flight['trip_duration'] ?> ساعات </td>
                    <td dir="auto"><?= $flight['seats_count'] ?></td>
                    <td dir="auto"><?= $flight['price'] ?> ل.س</td>
                    <td dir="auto"><?= $flight['overweight_charge'] ?> ل.س من اجل 1 كيلو </td>
                    <td dir="auto">
                        <span class="status-badge <?= $statusClass ?>" data-status="<?= $statusClass ?>">
                            <?= $statusText ?>
                        </span>
                    </td>
                    <td>
                        <a href="editflights.php?flightid=<?= $flight['flight_ID'] ?>" class="edit-btn">
                            <button class='edit-button'>
                                <svg class='edit-svgIcon' viewBox='0 0 512 512'>
                                    <path d='M410.3 231l11.3-11.3-33.9-33.9-62.1-62.1L291.7 89.8l-11.3 11.3-22.6 22.6L58.6 322.9c-10.4 10.4-18 23.3-22.2 37.4L1 480.7c-2.5 8.4-.2 17.5 6.1 23.7s15.3 8.5 23.7 6.1l120.3-35.4c14.1-4.2 27-11.8 37.4-22.2L387.7 253.7 410.3 231zM160 399.4l-9.1 22.7c-4 3.1-8.5 5.4-13.3 6.9L59.4 452l23-78.1c1.4-4.9 3.8-9.4 6.9-13.3l22.7-9.1v32c0 8.8 7.2 16 16 16h32zM362.7 18.7L348.3 33.2 325.7 55.8 314.3 67.1l33.9 33.9 62.1 62.1 33.9 33.9 11.3-11.3 22.6-22.6 14.5-14.5c25-25 25-65.5 0-90.5L453.3 18.7c-25-25-65.5-25-90.5 0zm-47.4 168l-144 144c-6.2 6.2-16.4 6.2-22.6 0s-6.2-16.4 0-22.6l144-144c6.2-6.2 16.4-6.2 22.6 0s6.2 16.4 0 22.6z'></path>
                                </svg>
                            </button>
                        </a>
                    </td>
                    <td>
                        <?php if ($statusClass === 'upcoming'): ?>
                            <a href="addbooking.php?flightid=<?= $flight['flight_ID'] ?>" class="booking-btn">
                                <button class='booking-button'>
                                    <svg class='booking-svgIcon' width="20" height="24" fill="currentColor" viewBox='0 0 16 16'>
                                        <path d='M6.428 1.151C6.708.591 7.213 0 8 0s1.292.592 1.572 1.151C9.861 1.73 10 2.431 10 3v3.691l5.17 2.585a1.5 1.5 0 0 1 .83 1.342V12a.5.5 0 0 1-.582.493l-5.507-.918-.375 2.253 1.318 1.318A.5.5 0 0 1 10.5 16h-5a.5.5 0 0 1-.354-.854l1.319-1.318-.376-2.253-5.507.918A.5.5 0 0 1 0 12v-1.382a1.5 1.5 0 0 1 .83-1.342L6 6.691V3c0-.568.14-1.271.428-1.849Z'></path>
                                    </svg>
                                </button>
                            </a>
                        <?php else: ?>
                            &nbsp;
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<script>
    function updateFlightStatus(flightRow) {
        const flightId = flightRow.dataset.flightid;
        const departureTime = flightRow.dataset.departure;
        const duration = flightRow.dataset.duration;
        const currentStatus = flightRow.dataset.status;

        if (currentStatus === 'cancelled') return;

        const now = new Date();
        const departure = new Date(departureTime);

        const durationParts = duration.split('h');
        const hours = parseInt(durationParts[0]);
        const minutes = durationParts[1] ? parseInt(durationParts[1].replace('m', '')) : 0;

        const arrival = new Date(departure);
        arrival.setHours(arrival.getHours() + hours);
        arrival.setMinutes(arrival.getMinutes() + minutes);

        let newStatus = '';
        let statusText = '';
        let statusClass = '';

        if (now < departure) {
            newStatus = 'upcoming';
            statusText = 'لم تنطلق بعد';
            statusClass = 'upcoming';
        } else if (now > arrival) {
            newStatus = 'arrived';
            statusText = 'وصلت';
            statusClass = 'arrived';
        } else {
            newStatus = 'departed';
            statusText = 'انطلقت';
            statusClass = 'departed';
        }

        if (currentStatus !== statusClass) {

            const statusBadge = flightRow.querySelector('.status-badge');
            if (statusBadge) {
                statusBadge.className = 'status-badge ' + statusClass;
                statusBadge.textContent = statusText;
                statusBadge.dataset.status = statusClass;
                flightRow.dataset.status = statusClass;
            }


            fetch('update_flight_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    flightId: flightId,
                    status: newStatus
                })
            })
                .then(response => response.json())
                .then(data => {
                    if (!data.success) {
                        console.error('Failed to update flight status in database:', data.error);
                    }
                });

            if (statusClass !== 'upcoming') {
                const bookingBtn = flightRow.querySelector('.booking-button');
                if (bookingBtn) {
                    bookingBtn.parentNode.innerHTML = '&nbsp;';
                }
            }
        }
    }

    function updateAllFlightStatuses() {
        const flightRows = document.querySelectorAll('tbody tr[data-flightid]');
        flightRows.forEach(flightRow => {
            updateFlightStatus(flightRow);
        });
    }

    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        const departureFilter = document.getElementById('departureFilter');
        const destinationFilter = document.getElementById('destinationFilter');
        const applyBtn = document.getElementById('applyFilters');
        const resetBtn = document.getElementById('resetFilters');
        const flightRows = document.querySelectorAll('tbody tr');
        const noResultsMsg = document.querySelector('.no-results');
        const tableContainer = document.querySelector('.table-container');

        updateAllFlightStatuses();

        setInterval(updateAllFlightStatuses, 60000);

        function applyFilters() {
            const searchTerm = searchInput.value.trim().toLowerCase();
            const departureValue = departureFilter.value;
            const destinationValue = destinationFilter.value;
            const statusCheckboxes = document.querySelectorAll('input[name="status"]:checked');
            const selectedStatuses = Array.from(statusCheckboxes).map(cb => cb.value);

            let visibleRows = 0;
            flightRows.forEach(row => {
                const flightId = row.cells[0].textContent.toLowerCase();
                const departureCity = row.cells[1].textContent;
                const destinationCity = row.cells[2].textContent;
                const statusBadge = row.querySelector('.status-badge');
                const statusValue = statusBadge ? statusBadge.getAttribute('data-status') : '';

                const matchesSearch = searchTerm === '' ||
                    flightId.includes(searchTerm) ||
                    departureCity.toLowerCase().includes(searchTerm) ||
                    destinationCity.toLowerCase().includes(searchTerm);

                const matchesDeparture = departureValue === '' ||
                    departureCity.includes(departureValue);

                const matchesDestination = destinationValue === '' ||
                    destinationCity.includes(destinationValue);

                const matchesStatus = selectedStatuses.length === 0 ||
                    selectedStatuses.includes(statusValue);

                if (matchesSearch && matchesDeparture && matchesDestination && matchesStatus) {
                    row.style.display = '';
                    visibleRows++;
                } else {
                    row.style.display = 'none';
                }
            });

            if (visibleRows === 0 && (searchTerm !== '' || departureValue !== '' || destinationValue !== '' || selectedStatuses.length > 0)) {
                noResultsMsg.style.display = 'block';
                tableContainer.style.display = 'none';
            } else {
                noResultsMsg.style.display = 'none';
                tableContainer.style.display = 'block';
            }
        }

        function resetFilters() {
            searchInput.value = '';
            departureFilter.value = '';
            destinationFilter.value = '';

            document.querySelectorAll('input[name="status"]').forEach(checkbox => {
                checkbox.checked = true;
            });

            flightRows.forEach(row => {
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
        departureFilter.addEventListener('change', applyFilters);
        destinationFilter.addEventListener('change', applyFilters);

        document.querySelectorAll('input[name="status"]').forEach(checkbox => {
            checkbox.addEventListener('change', applyFilters);
        });

        applyBtn.addEventListener('click', applyFilters);
        resetBtn.addEventListener('click', resetFilters);
        searchInput.focus();
    });
</script>
</body>
</html>