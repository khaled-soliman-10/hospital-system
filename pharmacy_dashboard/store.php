<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION['pharmacy']) {
        header("location: ../index.php");
        die();
    }
    // echo date("Y-m-d");

    $store = $database->prepare("SELECT * FROM store");
    $store->execute();

    //الدواء الفاسد
    if (isset($_POST['del'])) {
        $id = $_POST['del'];
        $deleteOne = $database->prepare("DELETE FROM store WHERE id = :id");
        $deleteOne->bindParam("id",$id);
        $deleteOne->execute();
        header("refresh: 0");
    }


    if (isset($_POST['deleteAll'])) {
        $deleteALL = $database->prepare("DELETE FROM store WHERE CURRENT_DATE >= exp_date");
        $deleteALL->execute();
        header("refresh: 0");
    }




?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصيدلية</title>
    <!-- css main file -->
    <link rel="stylesheet" href="css_files/master.css">
    <link rel="stylesheet" href="css_files/store.css">
    <!-- css frame work -->
    <link rel="stylesheet" href="css_files/frame_work.css">
    <!-- css font awesome -->
    <link rel="stylesheet" href="css_files/all.min.css">
    <!-- fonts from google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin_dashboard/css_files/khaled.css">
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
                <li class="m-b-15">
                    <a href="patient_reciet.php" class="width100">
                        <i class="fa-solid fa-receipt fa-fw"></i> 
                        <span class="m-r-10">  فاتورة مريض</span>
                    </a>
                </li>
                <li class="m-b-15">
                    <a href="add_medicine.php" class="width100">
                        <i class="fa-solid fa-pills fa-fw"></i>
                        <span class="m-r-10"> إضافة دواء</span>
                    </a>
                </li>
                <li class="num m-b-15 action">
                    <a class="listted relative width100" href="store.php">
                        <i class="fa-solid fa-shop fa-fw"></i>
                        <span class="m-r-10"> 
                            المخزن 
                        </span>
                        <i class="fa-solid fa-angle-right tog absolute"></i>
                    </a>
                    <div class="list">
                        <a href="store.php" class="m-r-10 width100 active">المخزن</a>
                        <a href="add_med_to_store.php" class="m-r-10 width100">إضافة دواء</a>
                    </div>
                </li>
                <li class="m-b-15">
                    <a href="missing_med.php" class="width100">
                        <i class="fa-solid fa-file-circle-minus"></i>
                        <span class="m-r-10">الدواء الناقص</span>
                    </a>
                </li>
            </ul>
            <?php 
            
            if(isset($_POST['exit'])){
                $sql = $database->prepare("UPDATE doctor_logins SET logout = :logout ,status = :status WHERE doctor_id = :doctor_id ORDER BY id DESC LIMIT 1");
                date_default_timezone_set('Africa/Cairo');
                $ldate = date('d-m-Y h:i:s A', time ());
                $sta = 0;
                $sql->bindParam("doctor_id",$_SESSION['pharmacy']);
                $sql->bindParam("logout",$ldate); 
                $sql->bindParam("status",$sta);
                if (!$sql->execute()) {
                    echo "Error updating logout time.";
                    print_r($sql->errorInfo());
                } else{           
                unset($_SESSION['pharmacy']);
                // session_destroy();
                header("location:../index.php");
                exit();
            }}
            
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
                    
                    $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
                    $sql->bindParam("id",$_SESSION['pharmacy']);
                    $sql->execute();
                    $data = $sql->fetchObject();
                    $img="data:".$data->image_type.";base64,".base64_encode($data->image);
                    
                    ?>
                    <p class="bold">صباح الخير د.<?php echo $data->doc_name; ?></p>
                </div>

                <?php 
                
                if (isset($_GET['medicine_name'])) {
                    $search = "%".$_GET['medicine_name']."%";
            
                    $med = $database->prepare("SELECT * FROM store WHERE name LIKE :name");
                    $med->bindParam("name",$search);
                    $med->execute();
                        }
                
                ?>

                <div class="search relative">
                    <form class="flex" method="get">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الدواء">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_st" type="submit" value="بحث">
                    </form>
                </div>
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="cont width100">
                <div class="head flex-sb-c p-rl-20 width100 m-b-20">
                    <h2>قائمة الأدوية في المخزن</h2>
                    <!-- <form action="" method="post">
                        <input class="" type="text" name="search" id="" placeholder="البحث عن إسم الدواء">
                        <button type="submit" class="b-s">بحث</button>
                    </form> -->
                    <form action="" method="post">
                        <input name="ex_btn" type="button" class="b-s show" value="الدواء الفاسد">
                    </form>
                    <img src="images/download__15_-removebg-preview.png" alt="">
                </div>
                <div class="store_table p-rl-20">
                    <table class="width100">
                        <thead>
                            <tr>
                                <td>إسم الدواء</td>
                                <td>الكمية</td>
                                <td>السعر</td>
                                <td>تاريخ الانتاج</td>
                                <td>تاريخ الإنتهاء</td>
                            </tr>
                        </thead>
                        <tbody>
                            

<?php 

if (isset($_GET['medicine_name'])) {
    if ($med->rowCount() >= 1) {
    foreach($med as $med){
        echo '<tr>
                <td>' . $med['name'] . '</td>
                <td>' . $med['quantity'] . '</td>
                <td>' . $med['price'] . '</td>
                <td>' . $med['p_date'] . '</td>
                <td>' . $med['exp_date'] . '</td>
            </tr>';
    }
        // 
    }else{
    echo '<tr>
            <td colspan=5 style="font-size: 20px;font-weight:600">لا يوجد دواء بهذا الاسم</td>
        </tr>';
    }
}else{

    foreach($store as $med) {
        echo'
        <tr>
            <td class="p-10">' . $med['name'] . '</td>
            <td class="p-10">' . $med['quantity'] . '</td>
            <td class="p-10">' . $med['price'] . '</td>
            <td class="p-10">' . $med['p_date'] . '</td>
            <td class="p-10">' . $med['exp_date'] . '</td>
        </tr>
        ';
    }
}


?>

                        </tbody>
                    </table>
                </div>
            </div>
            <div class="show-hide sh1 width100 absolute f-show-hide">
                <div class="flex-sb-c p-rl-20">
                    <i class="fa-solid fa-xmark p-10 "></i>
                    <form method="post">
                        <button type="submit" class="b-s" name="deleteAll">حذف الكل</button>
                    </form>
                </div>
                <div class="hidden_table">
                    <div class="search_medicine flex-sb-c">
                        <h2>قائمة الأدوية الفاسدة</h2>
                        <img src="images/download__15_-removebg-preview.png" alt="">
                    </div>
                    <div class="table width100">
                    <table class="width100">
                        <thead>
                            <tr>
                                <td class="p-10">إسم الدواء</td>
                                <td class="p-10">الكمية</td>
                                <td class="p-10">السعر</td>
                                <td class="p-10">تاريخ الانتاج</td>
                                <td class="p-10">تاريخ الإنتهاء</td>
                                <td class="p-10">حذف</td>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            
                            //الدواء الفاسد
                            $bad = $database->prepare("SELECT * FROM store WHERE CURRENT_DATE >= exp_date");
                            $bad->execute();

                            foreach ($bad as $med) {
                                echo'<tr>
                                <td class="p-10">' . $med['name'] . '</td>
                                <td class="p-10">' . $med['quantity'] . '</td>
                                <td class="p-10">' . $med['price'] . '</td>
                                <td class="p-10">' . $med['p_date'] . '</td>
                                <td class="p-10">' . $med['exp_date'] . '</td>
                                <td class="p-10">
                                    <form method="post">
                                        <button type="submit" name="del" value="' . $med['id'] . '"><i class="fa-solid fa-xmark"></i></button>
                                    </form>
                                </td>
                            </tr>';
                            }
                            
                            ?>
                            
                        </tbody>
                    </table>
                </div>
                </div>
            </div>
        </div>
    </div>    
    <script src="pharmacy.js"></script>
    <script src="index.js"></script>
</body>
</html>

<?php 

}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
}

?>