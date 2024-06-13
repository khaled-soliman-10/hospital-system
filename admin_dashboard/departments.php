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

        
        $departments = $database->prepare("SELECT * FROM departments");
        $departments->execute();


        // if (isset($_POST['delete'])) {
        //     $id = $_POST['delete'];

        //     $delete = $database->prepare("DELETE FROM departments WHERE id = :id");
        //     $delete->bindParam("id",$id);
        //     $delete->execute();
        //         echo '<script>
        //                 alert("تم الحذف")
        //             </script>';
        //         header("refresh: 0");
        //     }
        
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
                <li class="num m-b-15 action">
                    <a class="listted relative width100" href="departments.php">
                        <i class="fa-solid fa-users fa-fw"></i>
                        <span class="m-r-10"> 
                            الأقسام 
                        </span>
                        <i class="fa-solid fa-angle-right tog absolute"></i>
                    </a>
                    <div class="list">
                        <a href="departments.php" class="m-r-10 width100 active">الأقسام</a>
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
                <?php 
                
                // if (isset($_POST['medicine_name'])) {
                //     $search = "%".$_POST['medicine_name']."%";
            
                //     $med = $database->prepare("SELECT * FROM departments WHERE d_name LIKE :name");
                //     $med->bindParam("name",$search);
                //     $med->execute();
                //     }
                
                ?>
                    <form class="flex" action="" method="post">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن القسم ">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form>
                </div> -->
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="departments-list width100">
                <div class="head flex-sb-c width100 p-rl-10">
                    <h2 class="bold ">قائمة الأقسام</h2>
                    <!-- <form action="" method="post">
                        <input type="search" name="" id="" class="p-rl-10" placeholder="البحث عن طبيب">
                        <input type="submit" class="b-s" value="بحث">
                    </form> -->
                    <img src="images/download__15_-removebg-preview.png" alt="">
                </div>
                <div class="department_table p-rl-10">
                    <table class="width100">
                        <thead>
                            <tr class="">
                                <td>#</td>
                                <td>القسم</td>
                                <td>معاد التسجيل</td>
                                <td>السعر</td>
                                <td>تعديل</td>
                                <td>تفاصيل القسم</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            $row = 0;

                            foreach ($departments as $depart) {
                                $row++;
                                if ($depart['price'] == 0) {
                                    if ($depart['d_name'] == 'المعمل') {
                                        echo '<tr>
                                        <td>' . $row . '</td>
                                        <td>' . $depart['d_name'] . '</td>
                                        <td>' . $depart['d_date'] . '</td>
                                        <td>' . $depart['price'] . '</td>
                                        <td class="modefie flex-ev-c" style="display: flex; align-items:center;justify-content:center;gap:20px">
                                            <a href="edit_department.php?id=' . $depart['id'] . '"  style="width: 35px;background-color:#2745e652;font-size:14px"><i class="fa-solid fa-pen"></i></a>
                                            <form method="get" action="delete.php">
                                                <button type="submit" value="' . $depart['id'] . '" name="depart_id"  style="width: 35px;background-color:#ff000035;font-size:14px"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </td>
                                        <td><a href="lab.php"><i class="fa-solid fa-ellipsis"></i></a></td>
                                    </tr>';
                                    }elseif ($depart['d_name'] == 'الصيدلية') {
                                        echo '<tr>
                                        <td>' . $row . '</td>
                                        <td>' . $depart['d_name'] . '</td>
                                        <td>' . $depart['d_date'] . '</td>
                                        <td>' . $depart['price'] . '</td>
                                        <td class="modefie flex-ev-c" style="display: flex; align-items:center;justify-content:center;gap:20px">
                                            <a href="edit_department.php?id=' . $depart['id'] . '"  style="width: 35px;background-color:#2745e652;font-size:14px"><i class="fa-solid fa-pen"></i></a>
                                            <form method="get" action="delete.php">
                                                <button type="submit" value="' . $depart['id'] . '" name="depart_id"  style="width: 35px;background-color:#ff000035;font-size:14px"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </td>
                                        <td><a href="pharmacy.php"><i class="fa-solid fa-ellipsis"></i></a></td>
                                    </tr>';
                                    }elseif ($depart['d_name'] == 'الاستقبال') {
                                        echo '<tr>
                                        <td>' . $row . '</td>
                                        <td>' . $depart['d_name'] . '</td>
                                        <td>' . $depart['d_date'] . '</td>
                                        <td>' . $depart['price'] . '</td>
                                        <td class="modefie flex-ev-c" style="display: flex; align-items:center;justify-content:center;gap:20px">
                                            <a href="edit_department.php?id=' . $depart['id'] . '"  style="width: 35px;background-color:#2745e652;font-size:14px"><i class="fa-solid fa-pen"></i></a>
                                            <form method="get" action="delete.php">
                                                <button type="submit" value="' . $depart['id'] . '" name="depart_id"  style="width: 35px;background-color:#ff000035;font-size:14px"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                        </td>
                                        <td><a href="receiption.php"><i class="fa-solid fa-ellipsis"></i></a></td>
                                    </tr>';
                                    }
                                }else{
                                    echo '<tr>
                                            <td>' . $row . '</td>
                                            <td>' . $depart['d_name'] . '</td>
                                            <td>' . $depart['d_date'] . '</td>
                                            <td>' . $depart['price'] . '</td>
                                            <td class="modefie flex-ev-c" style="display: flex; align-items:center;justify-content:center;gap:20px">
                                                <a href="edit_department.php?id=' . $depart['id'] . '"  style="width: 35px;background-color:#2745e652;font-size:14px"><i class="fa-solid fa-pen"></i></a>
                                                <form method="get" action="delete.php">
                                                <button type="submit" value="' . $depart['id'] . '" name="depart_id"  style="width: 35px;background-color:#ff000035;font-size:14px"><i class="fa-solid fa-xmark"></i></button>
                                            </form>
                                            </td>
                                            <td><a href="department_details.php?id=' . $depart['id'] . '"><i class="fa-solid fa-ellipsis"></i></a></td>
                                        </tr>';
                                }
                            }
                            
                            ?>
                            

                        </tbody>
                    </table>
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