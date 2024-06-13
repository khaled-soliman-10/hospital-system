<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION["lab"]) {
        header("location: ../index.php");
        die();
    }
    // echo date("Y-m-d");

    $patientInfo = $database->prepare("SELECT * FROM lab_receipt WHERE date = CURRENT_DATE");
    $patientInfo->execute();
    $numOfPatient = $patientInfo->rowCount();

    $money = $database->prepare("SELECT SUM(lab_price) AS total FROM lab_receipt WHERE date = CURRENT_DATE");
    $money->execute();
    $sum = $money->fetchObject();
    $total = $sum->total;

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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title>lab_Dashboard</title>
</head>
<body>
    
    <section class="main start">
        <aside class="clm width">
            <div class="main-content clm">
                <header class="center">
                    <div class="logo center">
                        <img src="imgs/logo2.png" alt="">
                        <a href="index.php"><h1>الـ<span>حـ</span>يـاة</h1></a>
                    </div>
                    <button class="center"><i class="fa-solid fa-bars"></i></button>
                </header>
                <div class="links clm">
                    <a class="link center active" href="index.php">
                        <i class="fa-solid fa-house"></i>
                        <h1>الصفحه الرئيسيه</h1>
                    </a>
                    <a class="link center" href="add.php">
                        <i class="fa-solid fa-user-plus"></i>
                        <h1>اضافة مريض</h1>
                    </a>
                    <a class="link center" href="addanalysis.php">
                        <i class="fa-solid fa-square-plus"></i>
                        <h1>اضافة تحليل</h1>
                    </a>
                    <a class="link center" href="newprice.php">
                        <i class="fa-solid fa-magnifying-glass-dollar"></i>
                        <h1>تعديل السعر</h1>
                    </a>
                </div>
            </div>
            <?php 
            
            if(isset($_POST['exit'])){
                $sql = $database->prepare("UPDATE doctor_logins SET logout = :logout ,status = :status WHERE doctor_id = :doctor_id ORDER BY id DESC LIMIT 1");
                date_default_timezone_set('Africa/Cairo');
                $ldate = date('d-m-Y h:i:s A', time ());
                $sta = 0;
                $sql->bindParam("doctor_id",$_SESSION['lab']);
                $sql->bindParam("logout",$ldate); 
                $sql->bindParam("status",$sta);
                if (!$sql->execute()) {
                    echo "Error updating logout time.";
                    print_r($sql->errorInfo());
                } else{           
                unset($_SESSION['lab']);
                // session_destroy();
                header("location:../index.php");
                exit();
            }}
            
            ?>
            <form action="" method="post" class="center">
                <button type="submit" name="exit" class="btn center">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <h1>
                        تسجيل خروج
                    </h1>
                </button>
            </form>
        </aside>
        <div class="content clm">
            <header class="center">
            <?php 
                
                $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
                $sql->bindParam("id",$_SESSION['lab']);
                $sql->execute();
                $data = $sql->fetchObject();
                $img="data:".$data->image_type.";base64,".base64_encode($data->image);
                    
                ?>
                <h1>صباح الخير د. <?php echo $data->doc_name; ?></h1>

                <?php 
                
                if (isset($_GET['name'])) {
                    $search = "%".$_GET['name']."%";
            
                    $sPatient = $database->prepare("SELECT * FROM lab_receipt WHERE p_name LIKE :name AND date = CURRENT_DATE");
                    $sPatient->bindParam("name",$search);
                    $sPatient->execute();
                    }
                
                ?>

                <form method="get" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" placeholder="البحث عن اسماء المرضي">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form>
                <img src="<?php echo $img; ?>" alt="">
            </header>
            <div class="details">
                <div class="boxs">
                    <div class="box">
                        <div class="h-title">
                            <i class="fa-solid fa-bed-pulse"></i>
                            <h3>عدد المرضي</h3>
                        </div>
                        <div class="main-d">
                            <h4><?php echo $numOfPatient ?></h4>
                            <p>عدد المرضي اليوم فقط</p>
                        </div>
                    </div>
                    <div class="box">
                        <div class="h-title">
                            <i class="fa-solid fa-sack-dollar"></i>
                            <h3>دخل اليوم</h3>
                        </div>
                        <div class="main-d">
                            <h4><?php if($total == NULL){ echo '0';}else{ echo $total; } ?> جنية </h4>
                            <p>بيانات دخل اليوم فقط</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="patients clm">
                <div class="head center">
                    <h1>قائمة المرضي</h1>
                    <img src="imgs/logo.png" alt="">
                </div>
                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <td>اسم المريض</td>
                                <td>اسم التحليل والاشعه</td>
                                <td>السعر</td>
                                <td>تسجيل الدخول</td>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 

if (isset($_GET['name'])) {
    if ($sPatient->rowCount() >= 1) {
    foreach($sPatient as $pa){
        echo '<tr>
                <td>' . $pa['p_name'] . '</td>
                <td>' . $pa['lab_name'] . '</td>
                <td>' . $pa['lab_price'] . '</td>
                <td>' . $pa['date'] . '</td>
            </tr>';
    } 
    }else{
    echo '<tr>
            <td colspan=4 style="font-size: 20px;font-weight:600">لا يوجد مريض بهذا الاسم</td>
        </tr>';
    }
}else{

    
    foreach ($patientInfo as $patient) {
        echo '<tr>
        <td>' . $patient['p_name'] . '</td>
        <td>' . $patient['lab_name'] . '</td>
        <td>' . $patient['lab_price'] . '</td>
        <td>' . $patient['date'] . '</td>
        </tr>';
    }
}
                        
                        ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="js/main.js"></script>
</body>
</html>

<?php 

}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
}

?>