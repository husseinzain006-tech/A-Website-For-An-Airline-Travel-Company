<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تعديل بيانات الزبون</title>
    <link rel="stylesheet" href="styleeditcustomers.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
<body>
<div class="container">
    <div class="header">
        <h1><i class="fas fa-user-edit"></i> تعديل بيانات الزبون</h1>
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
            <h2 class="section-title"><i class="fas fa-edit"></i> تفاصيل الزبون</h2>
            <?php
            require_once 'connecting.php';
            $passid = isset($_GET['passid']) ? $_GET['passid'] : 0;
            $stmt = $pdo->prepare("SELECT * FROM customers WHERE passport_number = ?");
            $stmt->execute([$passid]);
            $customer = $stmt->fetch();

            if (!$customer) {
                die("الزبون غير موجود!");
            }
            $error = '';
            $successMsg = '';
            if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                $fname = $_POST['fname'];
                $faname = $_POST['faname'];
                $lname = $_POST['lname'];
                $emaill = $_POST['emaill'];
                $phonenumber = $_POST['phonenumber'];
                $bod = $_POST['bod'];
                if (empty($fname) || empty($faname) || empty($lname) || empty($emaill)) {
                    $error = 'الرجاء ملء الحقول المطلوبة!';
                } else {
                    try {
                        $stmt = $pdo->prepare("
                            UPDATE customers 
                            SET 
                                first_name = :fname, 
                                father_name = :faname, 
                                last_name = :lname, 
                                email = :emaill, 
                                phone = :phonenumber, 
                                DOB = :bod 
                            WHERE passport_number = :passid ");
                        $stmt->execute([
                            ':fname' => $fname,
                            ':faname' => $faname,
                            ':lname' => $lname,
                            ':emaill' => $emaill,
                            ':phonenumber' => $phonenumber,
                            ':bod' => $bod,
                            ':passid' => $passid
                        ]);
                        $successMsg = "تم تحديث بيانات الزبون بنجاح!";
                        $stmt = $pdo->prepare("SELECT * FROM customers WHERE passport_number = ?");
                        $stmt->execute([$passid]);
                        $customer = $stmt->fetch();
                    } catch (PDOException $e) {
                        $error = "خطأ في التحديث: " . $e->getMessage();
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
            <div class="passport-display">
                <i class="fas fa-passport"></i> رقم جواز السفر: <?= $passid ?>
            </div>
            <form method="post" id="customerForm">
                <div class="form-group">
                    <label for="fname"><i class="fas fa-user"></i> الاسم</label>
                    <input type="text" name="fname" id="fname" class="form-control" value="<?= htmlspecialchars($customer['first_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="faname"><i class="fas fa-user-friends"></i> اسم الأب</label>
                    <input type="text" name="faname" id="faname" class="form-control" value="<?= htmlspecialchars($customer['father_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="lname"><i class="fas fa-user-tag"></i> الكنية</label>
                    <input type="text" name="lname" id="lname" class="form-control" value="<?= htmlspecialchars($customer['last_name']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="emaill"><i class="fas fa-envelope"></i> البريد الإلكتروني</label>
                    <input type="email" name="emaill" id="emaill" class="form-control" value="<?= htmlspecialchars($customer['email']) ?>" required>
                </div>
                <div class="form-group">
                    <label for="phonenumber"><i class="fas fa-phone"></i> رقم الهاتف</label>
                    <input class="form-control small-input" type="tel" name="phonenumber" id="phonenumber" value="<?= htmlspecialchars($customer['phone']) ?>">
                </div>
                <div class="form-group">
                    <label for="bod"><i class="fas fa-birthday-cake"></i> تاريخ الميلاد</label>
                    <input class="form-control small-input" type="date" name="bod" id="bod" value="<?= $customer['DOB'] ?>">
                </div>
                <div style="margin-top: 30px;">
                    <button type="submit" class="btn-submit">
                        <i class="fas fa-save"></i> حفظ التعديلات
                    </button>
                    <a href="customers.php" class="btn-cancel">
                        <i class="fas fa-times"></i> إلغاء
                    </a>
                </div>
            </form>
        </div>
        <div class="summary-section">
            <h2 class="section-title"><i class="fas fa-receipt"></i> ملخص الزبون</h2>
            <div class="summary-card">
                <div class="summary-item">
                    <span class="summary-label">رقم جواز السفر:</span>
                    <span class="summary-value" id="summary-passid"><?= $passid ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">الاسم الكامل:</span>
                    <span class="summary-value" id="summary-fullname">
                        <?= htmlspecialchars($customer['first_name']) . ' ' .
                        htmlspecialchars($customer['father_name']) . ' ' .
                        htmlspecialchars($customer['last_name']) ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">البريد الإلكتروني:</span>
                    <span class="summary-value" id="summary-email"><?= htmlspecialchars($customer['email']) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">رقم الهاتف:</span>
                    <span class="summary-value" id="summary-phone"><?= htmlspecialchars($customer['phone']) ?></span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">تاريخ الميلاد:</span>
                    <span class="summary-value" id="summary-bod">
                        <?php
                        if ($customer['DOB']) {
                            $date = new DateTime($customer['DOB']);
                            echo $date->format('d/m/Y');
                        } else {
                            echo '--';
                        }
                        ?>
                    </span>
                </div>
                <div class="summary-item">
                    <span class="summary-label">العمر:</span>
                    <span class="summary-value" id="summary-age">
                        <?php
                        if ($customer['DOB']) {
                            $birthDate = new DateTime($customer['DOB']);
                            $today = new DateTime();
                            $age = $today->diff($birthDate)->y;
                            echo $age . ' سنة';
                        } else {
                            echo '--';
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
        function updateSummary() {
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