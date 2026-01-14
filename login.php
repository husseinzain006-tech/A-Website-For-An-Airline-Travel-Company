<!DOCTYPE html>
<html dir="auto">
<head>
    <meta charset="UTF-8">
    <title>تسجيل الدخول</title>
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
session_start();
require_once 'connecting.php';
$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user = trim(isset($_POST['username']) ? $_POST['username'] : '');
    $pass = trim(isset($_POST['password']) ? $_POST['password'] : '');

    if (empty($user) || empty($pass)) {
        $error = '<i class="fas fa-exclamation-circle"></i> الرجاء إدخال جميع الحقول المطلوبة!';
    } else {
        try {
            $q = "SELECT * FROM users WHERE username = :user";
            $stmt = $pdo->prepare($q);
            $stmt->bindParam(':user', $user);
            $stmt->execute();

            if ($stmt->rowCount() == 0) {
                $error = '<i class="fas fa-exclamation-circle"></i> اسم المستخدم أو كلمة المرور غير صحيحة!';
            } else {
                $userData = $stmt->fetch(PDO::FETCH_ASSOC);

                if (password_verify($pass, $userData['password'])) {
                    $_SESSION['user_id'] = $userData['userid'];
                    $_SESSION['username'] = $userData['username'];
                    $_SESSION['role'] = $userData['role'];

                    header("Location: home.php");
                    exit();
                } else {
                    $error = '<i class="fas fa-exclamation-circle"></i> اسم المستخدم أو كلمة المرور غير صحيحة!';
                }
            }
        } catch (PDOException $e) {
            $error = '<i class="fas fa-exclamation-circle"></i> حدث خطأ في الاتصال بقاعدة البيانات: ' . $e->getMessage();
        }
    }
}
?>
<div class="login-container">
    <div class="login-card">
        <h1 class="login-title">تسجيل الدخول</h1>
        <?php if (!empty($error)): ?>
            <div class="error">
                <?= $error ?>
            </div>
        <?php endif; ?>
        <form action="login.php" method="post" class="login-form">
            <div class="form-group">
                <label for="username">اسم المستخدم:</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group password-toggle">
                <label for="password">كلمة المرور:</label>
                <input type="password" id="password" name="password" required>
            </div>
            <button type="submit" class="login-button">دخول</button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('success')) {
            setTimeout(() => {
                document.querySelector('.info')?.classList.add('fade-out');
            }, 5000);
        }
    });
</script>
</body>
</html>