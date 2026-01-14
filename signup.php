<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <title>إنشاء حساب جديد</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .error {
            background: #fee;
            color: #e74c3c;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #e74c3c;
            display: flex;
            align-items: center;
            gap: 10px;
            animation: shake 0.5s;
        }

        .success {
            background: #dfe;
            color: #27ae60;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            border-left: 4px solid #27ae60;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
            20%, 40%, 60%, 80% { transform: translateX(5px); }
        }

        .login-container {
            position: relative;
            z-index: 1;
        }

    </style>
</head>
<body>
<div class="background"></div>
<?php
require_once 'connecting.php';
$error = '';
$success = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $pass = trim(isset($_POST['password']) ? $_POST['password'] : '');

    if (empty($user) || empty($pass) ) {
        $error = '<i class="fas fa-exclamation-circle"></i> الرجاء إدخال جميع الحقول المطلوبة!';
    }
    else {
        try {
            $checkStmt = $pdo->prepare("SELECT * FROM users WHERE username = :user");
            $checkStmt->bindParam(':user', $user);
            $checkStmt->execute();

            if ($checkStmt->rowCount() > 0) {
                $error = '<i class="fas fa-exclamation-circle"></i> اسم المستخدم موجود مسبقاً!';
            } else {

                $hashed_password = password_hash($pass, PASSWORD_DEFAULT);


                $insertStmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (:user, :hashed_pass, 'employee')");
                $insertStmt->bindParam(':user', $user);
                $insertStmt->bindParam(':hashed_pass', $hashed_password);
                $insertStmt->execute();

                $success = '<i class="fas fa-check-circle"></i> تم إنشاء الحساب بنجاح! يمكنك <a href="login.php">تسجيل الدخول الآن</a>';
            }
        } catch (PDOException $e) {
            $error = '<i class="fas fa-exclamation-circle"></i> حدث خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage();
        }
    }
}
?>
<div class="login-container">
    <div class="login-card">
        <h1 class="login-title">إنشاء حساب </h1>
        <?php if (!empty($error)): ?>
            <div class="error">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <?php if (!empty($success)): ?>
            <div class="success">
                <?= $success ?>
            </div>
        <?php endif; ?>
        <form action="signup.php" method="post" class="login-form" id="signup-form">
            <div class="form-group">
                <label for="username">اسم المستخدم</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">كلمة المرور</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">إنشاء </button>
        </form>
    </div>
</div>
</body>
</html>