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

                    <a class="link center " href="index.php">
                        <i class="fa-solid fa-bed-pulse fa-fw"></i>
                        <h1>المرضي</h1>
                    </a>

                    <a class="link center" href="treatment.php">
                        <i class="fa-solid fa-file-medical fa-fw"></i>
                        <h1>الروشتة</h1>
                    </a>

                    <a class="link center" href="medical.php">
                        <i class="fa-solid fa-receipt fa-fw"></i>
                        <h1>التشخيص المرضي</h1>
                    </a>

                    <!-- <a class="link center active" href="patient_search.php">
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
        echo '<div class="content clm">';
        $sql = $database->prepare("SELECT * FROM doctors WHERE id = :id");
            $sql->bindParam("id",$_SESSION['doctor']);
            $sql->execute();
            $data = $sql->fetchObject();
            $img="data:".$data->image_type.";base64,".base64_encode($data->image);
           echo '<header class="center">
                <h1>صباح الخير د.   '.$data->doc_name.' </h1>
                <form action="" method="get" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" placeholder="البحث عن اسماء المرضي">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form>
                <img src=" '.$img.'" alt="">
            </header>
            <div class="patient_search">
                <h2>عرض المرضى</h2>
                <form action="" method="post">
                    <label for="s_p">البحث عن مريض</label>
                    <input type="search" name="searchForPatient" id="s_p" placeholder="إسم المريض">
                    <button type="submit" class="btn">بحث</button>
                </form>
                <div class="patient_table">
                    <table>
                        <thead>
                            <tr>
                                <td>اسم المريض</td>
                                <td>رقم المريض</td>
                                <td>تسجيل الدخول</td>
                                <td>الرقم</td>
                                <td>الجنس</td>
                                <td>البريد الالكتروني</td>
                                <td></td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>احمد صلاح</td>
                                <td>#2322</td>
                                <td>22.12.2023</td>
                                <td>0112233445</td>
                                <td>ذكر</td>
                                <td>example@gmail.com</td>
                                <td><a href="medical-history.php?name=" class="dots"><i class="fa-solid fa-ellipsis"></i></button></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="js/main.js"></script>
    <!-- <script src="js/search.js"></script> -->
</body>
</html>';}