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

    if (isset($_GET['id'])) {
        $doc_id = $_GET['id'];
        
        $doctors = $database->prepare("SELECT * FROM doctors LEFT JOIN departments ON doctors.depart_id = departments.id WHERE doc_id = :id");
        $doctors->bindParam("id",$doc_id);
        $doctors->execute();

        $doctor = $doctors->fetchObject();
    }


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
                <!-- <div class="search relative">
                    <form class="flex" action="" method="post">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الطبيب">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form>
                </div> -->
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="doc_details flex-c-c width100">
                <div class="column flex-sb-c flex-gap p-10">
                    <div class="col">
                        <h3>الإسم: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->doc_name; } ?></span></h3>
                        <h3>القسم: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->d_name; } ?></span></h3>
                        <h3>البريد الإلكتروني: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->email; } ?></span></h3>
                        <h3>الرقم: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->phone; } ?></span></h3>
                        <h3>الجنس: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->gender; } ?></span></h3>
                        <h3>العنوان: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->address; } ?></span></h3>
                        <h3>الراتب: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->salary; } ?>  جنية </span></h3>
                        <h3>تاريخ الإنضام: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->join_date; } ?></span></h3>
                        <h3>عدد ساعات العمل: <span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->hours_work; } ?> ساعات</span></h3>
                        <h3>الدور:<span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->role; } ?></span></h3>
                        <h3>كلمة المرور :<span class="m-r-10"><?php if (isset($_GET['id'])) { echo $doctor->password; } ?></span></h3>
                    </div>
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