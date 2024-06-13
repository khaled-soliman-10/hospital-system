<?php
require_once '../include/conn.php';
session_start();
if(!isset($_SESSION['doctor'])){
header("location:../index.php");
}else{
echo '
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>مستشفي الحياة</title>
</head>

<body>

    <section class="main start">
        <aside class="clm width">
            <div class="main-content clm">
                <header class="center">
                    <div class="logo center">
                        <img src="imgs/logo2.png" alt="">
                        <a href="index.php">
                            <h1>الـ<span>حـ</span>يـاة</h1>
                        </a>
                    </div>
                    <button class="center"><i class="fa-solid fa-bars"></i></button>
                </header>

                <div class="links clm">

                    <a class="link center" href="index.php">
                        <i class="fa-solid fa-bed-pulse"></i>
                        <h1>المرضي</h1>
                    </a>

                    <a class="link center" href="treatment.php">
                        <i class="fa-solid fa-file-medical"></i>
                        <h1>الروشتة</h1>
                    </a>

                    <a class="link center active" href="medical.php">
                        <i class="fa-solid fa-receipt"></i>
                        <h1>التشخيص المرضي</h1>
                    </a>

                    <!-- <a class="link center" href="patient_search.php">
                        <i class="fa-solid fa-hospital-user fa-fw"></i>
                        <h1>البحث عن مريض</h1>
                    </a> -->

                </div>

            </div>

            <form action="" method="post" class="center">
                <button type="submit" name="exit" class="btn center">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <h1>
                        تسجيل خروج
                    </h1>
                </button>
            </form>
        </aside>';
        if(isset($_POST['exit'])){
            $sql = $database->prepare("UPDATE doctor_logins SET logout = :logout ,status = :status WHERE doctor_id = :doctor_id ORDER BY id DESC LIMIT 1");
            date_default_timezone_set('Africa/Cairo');
            $ldate = date('d-m-Y h:i:s A', time ());
            $sta = 0;
            $sql->bindParam("doctor_id",$_SESSION['doctor']);
            $sql->bindParam("logout",$ldate); 
            $sql->bindParam("status",$sta);
            if (!$sql->execute()) {
                echo "Error updating logout time.";
                print_r($sql->errorInfo());
            } else{           
            unset($_SESSION['doctor']);
            // session_destroy();
            header("location:../index.php");
            exit();
        }}
        echo '<div class="content clm">
            <!-- التشخيص -->
            <div class="medical-history medical clm">
                <div class="header center">
                    <h1>التاريخ المرضي للمريض</h1>
                    <img src="imgs/logo.png" alt="">
                </div>
                <!-- المحتوي -->
                <div class="details clm">
                    <form action="" method="POST" class="">
                        <div class="parent">
                            <div class="detail ">
                                <h1>المريض / </h1>
                                <input type="text" name="name" placeholder="اسم المريض">
                            </div>
                            <div class="detail ">
                                <h1>تاريخ الكشف : </h1>
                                <input type="date" name="date">
                            </div>
                        </div>
                        <div class="parent">
                            <div class="detail ">
                                <h1>التشخيص : </h1>
                                <input type="text" name="illness" placeholder="التشخيص">
                            </div>
                            <div class="detail ">
                                <h1> الدكتور : </h1>
                                <input type="text" name="doctorName" placeholder="إسم الدكتور">
                            </div>
                        </div>

                        <div class="ok center">
                            <button type="submit" class="btn" name="send">تأكيد</button>
                        </div>

                    </form>';
                    if(isset($_POST['send'])){
                        $sql = $database->prepare("INSERT INTO med_history(p_name,doctor_name,history,date) VALUES(:p_name,:doctor_name,:history,:date)");
                        $sql->bindParam("p_name",$_POST['name']);
                        $sql->bindParam("doctor_name",$_POST['doctorName']);
                        $sql->bindParam("history",$_POST['illness']);
                        $sql->bindParam("date",$_POST['date']);
                        if($sql->execute()){
                            echo "<script>alert('تم حفظ البيانات بنجاح')</script>";
                        }
                    }
                    echo '<h1>مستشفي الحياة</h1>
                </div>
            </div>
        </div>
    </section>

    <script src="js/main.js"></script>
</body>

</html>';}