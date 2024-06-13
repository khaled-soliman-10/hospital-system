<?php
require_once '../include/conn.php';
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/login.css">
    <title>مستشفي الحياة</title>
</head>
<body class="section center">
    
    <div class="login clm">
        <h1>مرحبا بك مجددا</h1>
        <p>سجل دخول الي حسابك للاستمرار</p>
        <form method="post" class="clm">
            
            <div class="clm">
                <label for="username">اسم المستخدم</label>
                <input type="text" autocomplete="off" name="username" id="username" placeholder="اسم المستخدم" required>
            </div>

            <div class="clm">
                <label for="password">كلمة المرور</label>
                <input type="password" autocomplete="off" name="password" id="password" placeholder="كلمة المرور" required>
                <i class="fa-solid fa-eye"></i>
            </div>

            <div class="clm">
                <label for="role">اختر من انت</label>
                <select name="role" id="role" required>
                    <option value="admin">ادمن (المدير)</option>
                    <option value="doctor">دكتور</option>
                    <option value="pharmacy">صيدلية</option>
                    <option value="lab">مختبر</option>
                    <option value="reception">الاستقبال</option>
                </select>
            </div>

            <button type="submit" name="login" class="btn">تسجيل دخول</button>

            <!-- نشيل الكومنت من علي الجملة لظهورها -->
            <!-- <p>حدث خطأ في تسجيل الدخول, تأكد من البيانات ثم حاول مرة اخري</p> -->

        </form>
    </div>

    <script src="js/pass.js"></script>
</body>
</html>