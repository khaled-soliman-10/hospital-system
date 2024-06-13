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

    //delete doctor
    if (isset($_GET['doctor_id'])) {
        $doc_id = $_GET['doctor_id'];

        $doc = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
        $doc->bindParam("id",$doc_id);
        $doc->execute();
        $doctor = $doc->fetchObject();

        if (isset($_POST['yes'])) {

            $delete = $database->prepare("DELETE FROM doctors WHERE doc_id = :id");
            $delete->bindParam("id",$doc_id);
            $delete->execute();
            
            header("location: doctors.php");

        }elseif (isset($_POST['no'])) {
            header("location: doctors.php");
        }

    }

    //delete department
    if (isset($_GET['depart_id'])) {
        $depart_id = $_GET['depart_id'];

        $depart = $database->prepare("SELECT * FROM departments WHERE id = :id");
        $depart->bindParam("id",$depart_id);
        $depart->execute();
        $department = $depart->fetchObject();

        if (isset($_POST['yes'])) {

            $delete = $database->prepare("DELETE FROM departments WHERE id = :id");
            $delete->bindParam("id",$depart_id);
            $delete->execute();
            
            header("location: departments.php");

        }elseif (isset($_POST['no'])) {
            header("location: departments.php");
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

    <style> 
    
    #kh {
        height: 87%;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    #kh form {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: space-around;
        /* background-color: #ff000035; */
        padding: 10px;
        border-radius: inherit;
        height: 350px;
        font-size: 12px;
        border: 1px solid red;
    }
    #kh .btns {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 170px;
    }
    #kh .btns button {
        width: 60px;
        height: 35px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        background-color: transparent;
        border: 1px solid transparent;
        border-radius: 5px;
        font-size: 15px;
        font-weight: 500;
        color: black;
        transition: .3s;
    }
    #kh .btns button:first-child{
        border: 1px solid red;
    }
    #kh .btns button:first-child:hover{
        background-color: #ff000035;
    }
    #kh .btns button:last-child{
        border: 1px solid green;
    }
    #kh .btns button:last-child:hover{
        background-color: #00800042;
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
                <!--  -->
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="patient_search p-20 m-20" id="kh">
                <form method="post">
                    <h1>هل انت متاكد من حذف <span style="color: #3b28ff;"><?php if (isset($_GET['doctor_id'])) { echo $doctor->doc_name; }
                    elseif (isset($_GET['depart_id'])) { echo $department->d_name; } ?></span> ؟</h1>
                    <div class="btns">
                        <button type="submit" name="yes">نعم</button>
                        <button type="submit" name="no">لا</button>
                    </div>
                </form>
            </div>
        </div>
        <script src="index.js"></script>
        <script src="search.js"></script>
    </div>
</body>
</html>

<?php

}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
}
    
?>