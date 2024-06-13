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

                    <a class="link center active" href="treatment.php">
                        <i class="fa-solid fa-file-medical"></i>
                        <h1>الروشتة</h1>
                    </a>

                    <a class="link center" href="medical.php">
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
            <header class="center">';
            $sql = $database->prepare("SELECT doctors.* , departments.d_name FROM doctors INNER JOIN departments ON doctors.depart_id = departments.id WHERE doctors.doc_id = :id");
            $sql->bindParam("id",$_SESSION['doctor']);
            $sql->execute();
            $data = $sql->fetchObject();
            $img="data:".$data->image_type.";base64,".base64_encode($data->image);
               echo '<h1>صباح الخير د. '.$data->doc_name.' </h1>
                <!-- <form action="" method="get" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" placeholder="البحث عن اسماء المرضي">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form> -->
                <img src="'.$img.'" alt="">
            </header>
            <div class="treatment clm">
                <div class="t-details clm">
                    <header class="center">
                        <div class="dr-details center">
                            <div class="dr-name clm">
                                <h3>دكتور</h3>
                                <h1>  '.$data->doc_name.' </h1><br>
                                <p>     '.$data->d_name.' بمستشفي الحياة</p>
                            </div>
                            <img src="imgs/dr.png" alt="">
                        </div>
                        <form method="post" class="patient-details clm">
                            <div class="center">
                                <h1>الاسم :</h1>
                                <input type="text" name="name" placeholder="......................" required>
                            </div>
                            <div class="center">
                                <h1>السن :</h1>
                                <input type="text" name="age" placeholder="......................" required>
                            </div>
                            <div class="center">
                                <h1>الوزن :</h1>
                                <input type="text" name="weight" placeholder="......................" required>
                            </div>
                            <div class="center">
                                <h1>التاريخ :</h1>
                                <input type="date" name="date" required>
                            </div>
                        </form>
                    </header>
                    <div class="body clm" style="direction:ltr">
                        <h1>R/</h1>
                        <textarea  style="direction:ltr" name="treatment" rows="10" required autocomplete="off" style="resize: none;" placeholder="اكتب هنا"></textarea>
                    </div>
                    <footer class="center">
                        <div class="location clm">
                            <div class="center">
                                <i class="fa-solid fa-location-dot"></i>
                                <h1>العنوان : 17 ش الشريف المتفرع من ش 15 خلف ماء الذهب</h1>
                            </div>
                            <div class="center">
                                <h1>طوال ايام الاسبوع عدا الجمعة</h1>
                                <h1>الساعة 1 ظهرا الي 5 المغرب</h1>
                            </div>
                        </div>
                        <div class="contact center">
                            <img src="imgs/logo.png" alt="">
                            <div class="center">
                                <h1>01098797684</h1>
                                <i class="fa-solid fa-phone"></i>
                            </div>
                        </div>
                    </footer>
                </div>
                <button name="send" class="btn" type = "button" onclick="window.print()">طباعة</button>
            </div>
        </div>
    </section>

    <script src="js/main.js"></script>
    <script src="js/treat.js"></script>
</body>

</html>';}