<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إضافة زبون جديد</title>
    <link rel="stylesheet" href="styleaddcustomers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-user-plus"></i> إضافة زبون جديد</h1>
        <div class="header-buttons">
            <a href="customers.php" class="btn">
                <i class="fas fa-users"></i> الزبائن </a>
            <a href="flights.php" class="btn">
                <i class="fas fa-plane"></i> الرحلات </a>
        </div>
    </div>
    <div class="content">
        <div class="form-section">
            <h2 class="section-title"><i class="fas fa-edit"></i> تفاصيل الزبون</h2>
            <?php
            require_once 'connecting.php';
            $error = $passid = $fname = $faname = $lname = $emaill = $phonenumber = $bod = "";
            $successMsg = '';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $passid = (isset($_POST['passid'])) ? $_POST['passid'] : '';
                $fname = (isset($_POST['fname'])) ? $_POST['fname'] : '';
                $faname = (isset($_POST['faname'])) ? $_POST['faname'] : '';
                $lname = (isset($_POST['lname'])) ? $_POST['lname'] : '';
                $emaill = (isset($_POST['emaill'])) ? $_POST['emaill'] : '';
                $phonenumber = (isset($_POST['phonenumber'])) ? $_POST['phonenumber'] : '';
                $bod = (isset($_POST['bod'])) ? $_POST['bod'] : '';
                if (empty($passid) || empty($fname) || empty($faname) || empty($lname) || empty($emaill)) {
                    $error = 'الرجاء ملء جميع الحقول المطلوبة!';
                } else {
                    try {
                        $checkStmt = $pdo->prepare("SELECT passport_number FROM customers WHERE passport_number = ?");
                        $checkStmt->execute([$passid]);

                        if ($checkStmt->rowCount() > 0) {
                            $error = "رقم جواز السفر ($passid) موجود مسبقاً!";
                        } else {
                            $q = "INSERT INTO customers VALUES (?, ?, ?, ?, ?, ?, ?)";
                            $ps = $pdo->prepare($q);
                            $ps->bindParam(1, $passid, PDO::PARAM_INT);
                            $ps->bindParam(2, $fname, PDO::PARAM_STR);
                            $ps->bindParam(3, $faname, PDO::PARAM_STR);
                            $ps->bindParam(4, $lname, PDO::PARAM_STR);
                            $ps->bindParam(5, $emaill, PDO::PARAM_STR);
                            $ps->bindParam(6, $phonenumber, PDO::PARAM_STR);
                            $ps->bindParam(7, $bod, PDO::PARAM_STR);
                            $ps->execute();
                            $successMsg = "تم إضافة الزبون بنجاح!";
                            $passid = $fname = $faname = $lname = $emaill = $phonenumber = $bod = "";
                        }
                    } catch (PDOException $e) {
                        $error = "خطأ في الإدخال: " . $e->getMessage();
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
            <form method='post' action='addcustomers.php' id="customerForm">
                <div class="form-group">
                    <label for="passport"><i class="fas fa-passport"></i> رقم جواز السفر</label>
                    <input class="form-control small-input" dir="auto" type='tel' maxlength="9" name='passid' id="passid" value='<?= $passid ?>' required>
                </div>
                <div class="form-group">
                    <label for="fname"><i class="fas fa-user"></i> الاسم</label>
                    <input type='text' dir="auto" name='fname' id="fname" class="form-control" value='<?= $fname ?>' required>
                </div>
                <div class="form-group">
                    <label for="faname"><i class="fas fa-user-friends"></i> اسم الأب</label>
                    <input type='text' dir="auto" name='faname' id="faname" class="form-control" value='<?= $faname ?>' required>
                </div>
                <div class="form-group">
                    <label for="lname"><i class="fas fa-user-tag"></i> الكنية</label>
                    <input type='text' dir="auto" name='lname' id="lname" class="form-control" value='<?= $lname ?>' required>
                </div>
                <div class="form-group">
                    <label for="emaill"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                    <input type="email" dir="auto" name="emaill" id="emaill" class="form-control" value='<?= $emaill ?>' required>
                </div>
                <div class="form-group">
                    <label for="phonenumber"><i class="fas fa-phone"></i> رقم الهاتف</label>
                    <input class="form-control small-input" dir="auto" type="tel" pattern="^0\d{9}" name="phonenumber" id="phonenumber" title="يبدأ بصفر يليه 9 أرقام" value='<?= $phonenumber ?>'>
                </div>
                <div class="form-group">
                    <label for="bod"><i class="fas fa-birthday-cake"></i> تاريخ الميلاد</label>
                    <input class="form-control small-input" dir="auto" type="date" name="bod" id="bod" value='<?= $bod ?>'>
                </div>
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check"></i> إضافة الزبون
                </button>
            </form>
        </div>
        <div class="summary-section">
            <h2 class="section-title"><i class="fas fa-receipt"></i> ملخص الزبون</h2>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">رقم جواز السفر:</span>
                    <span class="summary-value" id="summary-passid">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الاسم الكامل:</span>
                    <span class="summary-value" id="summary-fullname">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">البريد الإلكتروني:</span>
                    <span class="summary-value" id="summary-email">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رقم الهاتف:</span>
                    <span class="summary-value" id="summary-phone">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">تاريخ الميلاد:</span>
                    <span class="summary-value" id="summary-bod">--</span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">العمر:</span>
                    <span class="summary-value" id="summary-age">--</span>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function updateSummary() {
            $('#summary-passid').text($('#passid').val() || '--');
            const fullName = ($('#fname').val() || '') + ' ' +
                ($('#faname').val() || '') + ' ' +
                ($('#lname').val() || '');
            $('#summary-fullname').text(fullName.trim() || '--');
            $('#summary-email').text($('#emaill').val() || '--');
            $('#summary-phone').text($('#phonenumber').val() || '--');
            const bod = $('#bod').val();
            if (bod) {
                const date = new Date(bod);
                const formattedDate = date.toLocaleDateString('ar-EG', {
                    year: 'numeric',
                    month: 'long',
                    day: 'numeric'
                });
                $('#summary-bod').text(formattedDate);
                const today = new Date();
                const birthDate = new Date(bod);
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                $('#summary-age').text(age + ' سنة');
            } else {
                $('#summary-bod').text('--');
                $('#summary-age').text('--');
            }
        }
        updateSummary();
        $('input').on('input change', updateSummary);
    });
</script>
</body>
</html>