<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION['pharmacy']) {
        header("location: ../index.php");
        die();
    }
    // echo date("Y-m-d");

        $med = $database->prepare("SELECT * FROM Pharmacy WHERE ph_quantity = 0");
        $med->execute();


?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصيدلية</title>
    <!-- css main file -->
    <link rel="stylesheet" href="css_files/med_search.css">
    <link rel="stylesheet" href="css_files/master.css">
    <!-- css frame work -->
    <link rel="stylesheet" href="css_files/frame_work.css">
    <!-- css font awesome -->
    <link rel="stylesheet" href="css_files/all.min.css">
    <!-- fonts from google fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../admin_dashboard/css_files/khaled.css">
    <style>
        table tr:hover {
            background-color: #cfcfcf;
        }
        table tr {
            transition: .3s;
            height: 50px;
        }
        table tr:not(:last-child) td {
            border-bottom: 1px solid black;
        }
        table tr td {
            font-size: 17px;
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
                <li class="m-b-15">
                    <a href="add_receipt.php" class="width100">
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
                <li class="num m-b-15">
                    <a class="listted relative width100" href="store.php">
                        <i class="fa-solid fa-shop fa-fw"></i>
                        <span class="m-r-10"> 
                            المخزن 
                        </span>
                        <i class="fa-solid fa-angle-right tog absolute"></i>
                    </a>
                    <div class="list">
                        <a href="store.php" class="m-r-10 width100">المخزن</a>
                        <a href="add_med_to_store.php" class="m-r-10 width100">إضافة دواء</a>
                    </div>
                </li>
                <li class="m-b-15 active">
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
                <div class="search relative">
                    <!-- <form class="flex" action="" method="post">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الدواء">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form> -->
                </div>
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="patient_search m-20 p-20" style="height: 85%;">
                <h2 class="" style="margin:0px auto 20px;width:fit-content">الدواء الناقص</h2>
                <div class="patient_table" style="height: 85%;overflow-y:auto">
                    <table>
                        <thead>
                            <tr>
                                <td>اسم الدواء</td>
                                <td> السعر</td>
                                <td>الكمية </td>
                                <td>تاريخ الانتاج</td>
                                <td>تاريخ الإنتهاء</td>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 

if ($med->rowCount() >= 1) {
    foreach($med as $med){
        echo '<tr>
                <td>' . $med['name'] . '</td>
                <td>' . $med['price'] . '</td>
                <td>' . $med['ph_quantity'] . '</td>
                <td>' . $med['p_date'] . '</td>
                <td>' . $med['exp_date'] . '</td>
            </tr>';
    }
                }else{
                    echo '<tr>
                            <td colspan=5 style="font-size: 20px;font-weight:600">لا يوجد دواء ناقص بالصيدلية</td>
                        </tr>';
                }
                    }

                // }
                catch (PDOException $e) {
                    echo "failed connect" . $e->getMessage();
                }
                        
                        ?>
                            
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <script src="index.js"></script>
    <script src="search.js"></script>
</body>
</html>