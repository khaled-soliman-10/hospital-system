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

    // number of males
    $males = $database->prepare("SELECT * FROM patient WHERE gender = 'ذكر'");
    $males->execute();
    $numMales = $males->rowCount();

    // number of females
    $females = $database->prepare("SELECT * FROM patient WHERE gender = 'انثي'");
    $females->execute();
    $numFemales = $females->rowCount();
    
    // number of patints
    $numPatients = $numFemales + $numMales;
    
    // number of doctors
    $doctors = $database->prepare("SELECT * FROM doctors");
    $doctors->execute();
    $numDocs = $doctors->rowCount();

    // number of departments
    $departments = $database->prepare("SELECT * FROM departments");
    $departments->execute();
    $numDeparts = $departments->rowCount();

    //last login
    $lastLogin = $database->prepare("SELECT * FROM doctor_logins LEFT JOIN doctors ON doctor_logins.doctor_id = doctors.doc_id");
    $lastLogin->execute();
        
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
    <style>
        .avatar{
            border-radius: 50%;
            width: 40px !important;
            height: 40px;
        }
    </style>
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
                <li class="active m-b-15">
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
                <li class="m-b-15">
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
            <form method="post" class="log-out absolute">
                <button type="submit" name="exit" class="width100">
                    <i class="fa-solid fa-arrow-right-from-bracket fa-fw"></i>
                    <span class="m-r-10 bold">تسجيل الخروج</span>
                </button>
            </form >
        </div>
        <div class="content width100 relative">
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
                <div class="search relative">
                <?php 
                
                if (isset($_POST['medicine_name'])) {
                    $search = "%".$_POST['medicine_name']."%";
            
                    $med = $database->prepare("SELECT * FROM doctor_logins LEFT JOIN doctors ON doctor_logins.doctor_id = doctors.doc_id WHERE doc_name LIKE :name");
                    $med->bindParam("name",$search);
                    $med->execute();
                    }
                
                ?>
                    <form class="flex" action="" method="post">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الطبيب">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form>
                </div>
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="details width100 p-rl-20 flex-ar-c m-b-20">
                <div class="detail flex-sb-c">
                    <i class="fa-solid fa-bed-pulse fa-fw"></i>
                    <div class="text">
                        <p>المرضي</p>
                        <p><?php echo $numPatients ?></p>
                    </div>
                </div>
                <div class="detail flex-sb-c ">
                    <div class="text">
                        <p>نساء</p>
                        <p><?php echo $numFemales ?></p>
                    </div>
                    <div class="text">
                        <p>رجال</p>
                        <p><?php echo $numMales ?></p>
                    </div>
                </div>
            </div>
            <div class="details width100 p-rl-20 flex-ar-c m-b-20">
                <div class="detail flex-sb-c">
                    <i class="fa-solid fa-user-nurse fa-fw"></i>
                    <div class="text">
                        <p>الأطباء</p>
                        <p><?php echo $numDocs ?></p>
                    </div>
                </div>
                <div class="detail flex-sb-c">
                    <i class="fa-solid fa-people-group fa-fw"></i>
                    <div class="text">
                        <p>الأقسام</p>
                        <p><?php echo $numDeparts ?></p>
                    </div>
                </div>
            </div>
            <div class="list p-rl-20">
                <div class="main-head flex-sb-c m-b-15">
                    <h2>الأطباء المتاحين في كل قسم</h2>
                    <img style="width: 120px;" src="images/download__15_-removebg-preview.png" alt="">
                </div>
                <div class="mP-doc-table">
                    <table class="width100">
                        <thead>
                            <tr>
                                <td>إسم الطبيب</td>
                                <td>إسم القسم</td>
                                <td>وقت تسجيل الدخول</td>
                                <td>مدة الشغل</td>
                                <td>تفاصيل الدكتور</td>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 

if (isset($_POST['medicine_name'])) {
    if ($med->rowCount() >= 1) {
        foreach($med as $med){
            $department = $database->prepare("SELECT * FROM departments WHERE id = :id");
            $department->bindParam("id",$med['depart_id']);
            $department->execute();

            $depart = $department->fetchObject();
            if ($med['status'] == 1) {
                echo '<tr>
                <td>' . $med['doctor_name'] . '</td>
                <td>' . $depart->d_name . '</td>
                <td>' . $med['login_time'] . '</td>
                <td>' . $med['hours_work'] . '</td>
                <td>
                    <a href="doctor_details.php?id=' . $med['doc_id'] . '"><i class="fa-solid fa-ellipsis"></i></a>
                </td>
                </tr>';
            }
        }
    }else{
    echo '<tr>
            <td colspan=5 style="font-size: 20px;font-weight:600">لا يوجد طبيب بهذا الاسم</td>
        </tr>';
    }
}else{


                        
                        $row=0;
                        foreach ($lastLogin as $doc) {
                            $row++;
                            $department = $database->prepare("SELECT * FROM departments WHERE id = :id");
                            $department->bindParam("id",$doc['depart_id']);
                            $department->execute();

                            $depart = $department->fetchObject();
                            if ($doc['status'] == 1) {
                                echo '<tr>
                                <td>' . $doc['doctor_name'] . '</td>
                                <td>' . $depart->d_name . '</td>
                                <td>' . $doc['login_time'] . '</td>
                                <td>' . $doc['hours_work'] . '</td>
                                <td>
                                    <a href="doctor_details.php?id=' . $doc['doc_id'] . '"><i class="fa-solid fa-ellipsis"></i></a>
                                </td>
                                </tr>';
                            }
                        }
                    }
                        
                        ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>    
    <!-- <script src="pharmacy.js"></script> -->
    <script src="admin.js"></script>
</body>
</html>

<?php

}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
}
    
?>
