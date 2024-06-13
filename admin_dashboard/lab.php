<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION['admin']) {
        header("location:../index.php");
        die();
    }

    if(isset($_POST['exit'])){          
        unset($_SESSION['admin']);
        // session_destroy();
        header("location:../index.php");
        exit();
    }
    // echo date("Y-m-d");


            
            //patients
            $patients = $database->prepare("SELECT * FROM lab_receipt WHERE date = CURRENT_DATE");
            $patients->execute();

            $numPatients = $patients->rowCount();

            //doctors
            $doctors = $database->prepare("SELECT * FROM doctors LEFT JOIN doctor_logins ON doctor_logins.doctor_id = doctors.doc_id WHERE role = 'معمل'");
            $doctors->execute();

            $numDoctors = $doctors->rowCount();

            //day total
            $day = $database->prepare("SELECT SUM(lab_price) AS total FROM lab_receipt  WHERE date = CURRENT_DATE");
            $day->execute();

            $sum = $day->fetchObject();
            $price = $sum->total;

            //month total
            $month = $database->prepare("SELECT SUM(lab_price) AS total FROM lab_receipt  WHERE date >= CURRENT_DATE - INTERVAL 1 MONTH");
            $month->execute();

            $tMonth = $month->fetchObject();
            $total = $tMonth->total;

        
// }
// catch (PDOException $e) {
//     echo "failed connect" . $e->getMessage();
// }
    
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>المدير</title>
    <!-- css main file -->
    <link rel="stylesheet" href="css_files/master.css">
    <!-- css frame work -->
    <link rel="stylesheet" href="css_files/frame_work.css">
    <!-- css font awesome -->
    <link rel="stylesheet" href="css_files/all.min.css">
    <!-- fonts from google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css_files/khaled.css">

</head>
<body>
    <div class="page flex">
        <div class="sidebar relative p-10">
            <div class="title flex-0-c m-b-20 bold">
                <img class="logo" src="images/download__15__14-removebg-preview.png" alt="">
                <a href="index.php"><h2>الـ<span>حـ</span>يـاة</h2></a>
                <i class="fa-solid fa-bars fa-fw"></i>
            </div>
            <ul class="links flex">
                <li class=" m-b-15">
                    <a href="index.php" class="width100">
                        <i class="fa-solid fa-cubes fa-fw"></i>
                        <span class="m-r-10">الصفحة الرئيسية</span>
                    </a>
                </li>
                <li class="num m-b-15">
                    <a class="listted relative width100" href="doctors.php">
                        <i class="fa-solid fa-user-doctor fa-fw"></i>
                        <span class="m-r-10"> 
                            الأطباء 
                        </span>
                        <i class="fa-solid fa-angle-right tog absolute"></i>
                    </a>
                    <div class="list">
                        <a href="doctors.php" class="m-r-10 width100">الأطباء</a>
                        <a href="add-doctors.php" class="m-r-10 width100">إضافة طبيب</a>
                    </div>
                </li>
                <li class="num m-b-15">
                    <a class="listted relative width100" href="departments.php">
                        <i class="fa-solid fa-users fa-fw"></i>
                        <span class="m-r-10"> 
                            الأقسام 
                        </span>
                        <i class="fa-solid fa-angle-right tog absolute"></i>
                    </a>
                    <div class="list">
                        <a href="departments.php" class="m-r-10 width100">الأقسام</a>
                        <a href="add-department.php" class="m-r-10 width100">إضافة قسم</a>
                    </div>
                </li>
                <li class="m-b-15">
                    <a href="receiption.php" class="width100">
                        <i class="fa-solid fa-people-group fa-fw"></i>
                        <span class="m-r-10">الإستقبال</span>
                    </a>
                </li>
                <li class="m-b-15 active">
                    <a href="lab.php" class="width100">
                        <i class="fa-solid fa-flask-vial fa-fw"></i>
                        <span class="m-r-10">المعمل</span>
                    </a>
                </li>
                <li class="m-b-15">
                    <a href="pharmacy.php" class="width100">
                        <i class="fa-solid fa-pills fa-fw"></i>
                        <span class="m-r-10">الصيدلية</span>
                    </a>
                </li>
                <li class="m-b-15">
                    <a href="patients.php" class="width100">
                        <i class="fa-solid fa-bed fa-fw"></i>
                        <span class="m-r-10">المرضي</span>
                    </a>
                </li>
                <li class="m-b-15">
                    <a href="last_login.php" class="width100">
                        <i class="fa-solid fa-clipboard-check fa-fw"></i>
                        <span class="m-r-10">أخر تسجيل دخول</span>
                    </a>
                </li>
                <li class="m-b-15">
                    <a href="salaries.php" class="width100">
                        <i class="fa-solid fa-sack-dollar fa-fw"></i>
                        <span class="m-r-10">الرواتب</span>
                    </a>
                </li>
                <!-- <li class="m-b-15">
                    <a href="patient_search.php" class="width100">
                        <i class="fa-solid fa-pills fa-fw"></i>
                        <span class="m-r-10">البحث عن مريض</span>
                    </a>
                </li> -->
                <li class="m-b-15">
                    <a href="coments.php" class="width100">
                        <i class="fa-regular fa-comments fa-fw"></i>
                        <span class="m-r-10">التعليقات</span>
                    </a>
                </li>
            </ul>
            <?php 
            
            // if(isset($_POST['exit'])){          
            //     unset($_SESSION['admin']);
            //     // session_destroy();
            //     header("location:../index.php");
            //     exit();
            // }
            
            ?>
            <form method="post" class="log-out absolute">
                <button type="submit" name="exit" class="width100">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-fw"></i>
                    <span class="m-r-10 bold">تسجيل الخروج</span>
                </button>
            </form >
        </div>
        <div class="content main_rpl width100 relative">
            <div class="header flex-sb-c p-10 m-b-20">
                <div class="text">
                <?php 
                
                $sql = $database->prepare("SELECT * FROM admin WHERE id = :id");
                $sql->bindParam("id",$_SESSION['admin']);
                $sql->execute();
                $data = $sql->fetchObject();
                $img="data:".$data->image_type.";base64,".base64_encode($data->image);
                    
                ?>
                    <p class="bold">مرحبا بك د. <?php echo $data->username; ?></p>
                </div>
                <!-- <div class="search relative">
                    <form class="flex" action="" method="post">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الطبيب">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form>
                </div> -->
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="rlp flex-ar-c p-rl-20 m-b-20">
                <div class="statistics first center p-10">
                    <div class="one  flex-c-c flex-gap m-t-20 m-b-15">
                        <i class="fa-solid fa-money-bill-trend-up "></i>
                        <h2>دخل اليوم</h2>
                    </div>
                    <h2 class="m-b-15"><span><?php if($price == NULL) { echo '0'; }else{ echo $price; } ?></span> جنية <i class="fa-solid fa-sack-dollar m-r-10"></i></h2>
                    <button type="button"><i class="fa-solid fa-file-import"></i> دخل الشهر</button>
                </div>
                <div class="statistics center p-10">
                    <h2 class="m-b-40 m-t-20"> <i class="fa-solid fa-file-invoice-dollar m-l-10"></i> عدد المرضي </h2>
                    <h2> <span><?php echo $numPatients ?></span> مريض </h2>
                </div>
            </div>
            <div class="rpl_content p-rl-20 m-r-20 m-l-20">
                <div class="rpl_header flex-sb-c m-b-20">
                    <h2>قائمة أطباء المعمل</h2>
                    <img style="width: 120px;" src="images/download__15_-removebg-preview.png" alt="">
                </div>
                <div class="rpl_table">
                    <table class="width100 center">
                        <thead>
                            <tr>
                                <td>إسم الطبيب</td>
                                <td>رقم التليفون</td>
                                <td>عدد ساعات العمل</td>
                                <td>الراتب (بالجنيه)</td>
                                <td>الحالة(شيفت)</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            foreach ($doctors as $doc) {
                                if ($doc['status'] == 1) {
                                    echo '<tr>
                                    <td>' . $doc['doc_name'] . '</td>
                                    <td>' . $doc['phone'] . '</td>
                                    <td>' . $doc['hours_work'] . ' ساعات</td>
                                    <td>' . $doc['salary'] . ' جنية </td>
                                    <td>متواجد</td>
                                    </tr>';
                                }else {
                                    echo '<tr>
                                    <td>' . $doc['doc_name'] . '</td>
                                    <td>' . $doc['phone'] . '</td>
                                    <td>' . $doc['hours_work'] . ' ساعات</td>
                                    <td>' . $doc['salary'] . ' جنية </td>
                                    <td>غير متواجد</td>
                                    </tr>';
                                }
                            }
                            
                            ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="show-hide width100 absolute">
                <i class="fa-solid fa-xmark m-b-20 close"></i>
                <div class="cont flex-c-c width100">
                    <div class="statistics center p-10">
                        <div class="one  flex-c-c flex-gap m-t-20 m-b-40">
                            <i class="fa-solid fa-money-bill-trend-up "></i>
                            <h2>دخل الشهر</h2>
                        </div>
                        <h2 class="m-b-15"><span><?php if($total == NULL) { echo '0'; }else{ echo $total; } ?></span> جنية <i class="fa-solid fa-sack-dollar m-r-10"></i></h2>
                    </div>
                </div>
            </div>
        </div>
    </div>    
    <!-- <script src="pharmacy.js"></script> -->
    <script src="admin.js"></script>
    <script src="showHide.js"></script>
</body>
</html>

<?php 

}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
}

?>