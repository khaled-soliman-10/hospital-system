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

        $docto = $database->prepare("SELECT * FROM doctors INNER JOIN departments ON doctors.depart_id = departments.id WHERE doc_id = :id");
        $docto->bindParam("id",$doc_id);
        $docto->execute();

        $doct = $docto->fetchObject();

        if (isset($_POST['save'])) {
            if ($_POST['doc_password'] == $_POST['doc_re_password']) {
                $update = $database->prepare("UPDATE doctors SET doc_name = :name , gender = :gender , email = :email , password = :pass , join_date = :date , address = :add , phone = :phone , hours_work = :hours , salary = :salary , role = :role , depart_id = :depart WHERE doc_id = :id");
                $update->bindParam("id",$doct->doc_id);
                $update->bindParam("name",$_POST['doc_name']);
                $update->bindParam("gender",$_POST['sex']);
                $update->bindParam("email",$_POST['doc_mail']);
                $update->bindParam("pass",$_POST['doc_password']);
                $update->bindParam("date",$_POST['join_date']);
                $update->bindParam("add",$_POST['doc_label']);
                $update->bindParam("phone",$_POST['doc_number']);
                $update->bindParam("hours",$_POST['work_hours']);
                $update->bindParam("salary",$_POST['salary']);
                $update->bindParam("role",$_POST['stairs']);
                $update->bindParam("depart",$_POST['depart']);
    
                $update->execute();
                echo '<script>
                        alert("تم التعديل")
                    </script>';
                header("refresh: 0");
            }else {
                echo '<script>
                        alert("تأكد من تطابق كلمة المرور")
                    </script>';
            }
        }
    }


// }
// catch (PDOException $e) {
//     echo "failed connect" . $e->getMessage();
// }
    
?>



<!DOCTYPE html>
<html dir="rtl" lang="en">
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
        <div class="content width100">
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
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الدواء">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form>
                </div> -->
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="add-doctor width100">
                <div class="title flex-sb-c">
                    <h2>تعديل بيانات طبيب</h2>
                    <img src="images/download__15_-removebg-preview.png" alt="">
                </div>
                <div class="forms">
                    <form method="post">
                        <div class="inputs flex-sb-0">
                            <div class="name">
                                <label for="doc_name" class="m-r-10">إسم الطبب</label>
                                <input class="width100 p-rl-10" type="text" name="doc_name" id="doc_name" placeholder="الإسم" value="<?php if (isset($_GET['id'])) { echo $doctor->doc_name; } ?>">
                            </div>
                            <div class="sex">
                                <label for="sex" class="m-r-10">الجنس</label>
                                <select name="sex" id="sex" class="width100 p-rl-10">
                                    <?php 
                                    
                                    $options = array(
                                        "ذكر" => "ذكر",
                                        "انثي" => "انثي",
                                    );

                                    foreach ($options as $value => $label) {
                                        if ($value == $doctor->gender) {
                                            echo '<option value="' . $value . '" selected>' . $label . '</option>';
                                        }else {
                                            echo '<option value="' . $value . '">' . $label . '</option>';
                                        }
                                    }
                                    
                                    ?>
                                    <!-- <option value="ذكر">ذكر</option>
                                    <option value="انثي">أنثي</option> -->
                                </select>
                            </div>
                        </div>
                        <div class="inputs flex-sb-0">
                            <div class="number">
                                <label for="doc_number" class="m-r-10">الرقم</label>
                                <input type="text" class="width100 p-rl-10" name="doc_number" id="doc_number" placeholder="الرقم" value="<?php if (isset($_GET['id'])) { echo $doctor->phone; } ?>">
                            </div>
                            <div class="salary">
                                <label for="salary" class="m-r-10">الراتب</label>
                                <input type="text" name="salary" id="salary" class="width100 p-rl-10" placeholder="أدخل الراتب" value="<?php if (isset($_GET['id'])) { echo $doctor->salary; } ?>">
                            </div>
                        </div>
                        <div class="inputs flex-sb-0">
                            <div class="adress">
                                <label for="doc_label" class="m-r-10">العنوان</label>
                                <input type="text" class="width100 p-rl-10" name="doc_label" id="doc_label" placeholder="العنوان" value="<?php if (isset($_GET['id'])) { echo $doctor->address; } ?>">
                            </div>
                            <div class="stairs">
                                <label for="stairs" class="m-r-10">الدور</label>
                                <input type="text" name="stairs" id="stairs" class="width100 p-rl-10" placeholder="الدور" value="<?php if (isset($_GET['id'])) { echo $doctor->role; } ?>">
                            </div>
                        </div>
                        <div class="inputs flex-sb-0">
                            <div class="join">
                                <label for="join_date" class="m-r-10">تاريخ الإنضمام</label>
                                <input type="date" name="join_date" id="join_date" class="width100 p-rl-10" value="<?php if (isset($_GET['id'])) { echo $doctor->join_date; } ?>">
                            </div>
                            <div class="hours">
                                <label for="work_hours" class="m-r-10">عدد ساعات العمل</label>
                                <input type="text" name="work_hours" id="work_hours" class="width100 p-rl-10" value="<?php if (isset($_GET['id'])) { echo $doctor->hours_work; } ?>">
                            </div>
                        </div>
                        <div class="inputs flex-sb-0">
                            <div class="department">
                                <label for="doc_department" class="m-r-10">القسم</label>
                                <select name="depart" class="width100 p-rl-10" id="doc_department">

                                <?php 
                                
                                $departments = $database->prepare("SELECT * FROM departments");
                                $departments->execute();
                                foreach ($departments as $depart) {
                                    if ($depart['d_name'] == $doctor->d_name) {
                                        echo '<option value="' . $depart['id'] . '" selected>' . $depart['d_name'] . '</option>';
                                    }else {
                                        echo '<option value="' . $depart['id'] . '">' . $depart['d_name'] . '</option>';
                                    }
                                }
                                
                                ?>
                                </select>
                            </div>
                            <div class="email">
                                <label for="doc_mail" class="m-r-10">البريد الإلكتروني</label>
                                <input type="text" class="width100 p-rl-10" name="doc_mail" id="doc_mail" placeholder="البريد الإلكتروني" value="<?php if (isset($_GET['id'])) { echo $doctor->email; } ?>">
                            </div>
                        </div>
                        <div class="inputs flex-sb-0">
                            <div class="pass">
                                <label for="doc_password" class="m-r-10">كلمة المرور</label>
                                <input type="text" class="width100 p-rl-10" name="doc_password" id="doc_password" placeholder="كلمة المرور" value="<?php if (isset($_GET['id'])) { echo $doctor->password; } ?>">
                            </div>
                            <div class="re_pass">
                                <label for="doc_re_password" class="m-r-10">تأكيد كلمة المرور</label>
                                <input type="text" class="width100 p-rl-10" name="doc_re_password" id="doc_re_password" placeholder="تأكيد كلمة المرور" value="<?php if (isset($_GET['id'])) { echo $doctor->password; } ?>">
                            </div>
                        </div>
                        <div class="save-button">
                            <button class="btn" type="submit" name="save">حفظ</button>
                        </div>
                    </form> 
                </div>
            </div>
        </div>
        <script src="admin.js"></script>
    </div>
</body>
</html>

<?php

}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
}
    
?>