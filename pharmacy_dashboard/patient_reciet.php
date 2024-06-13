<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION['pharmacy']) {
        header("location: ../index.php");
        die();
    }

    if (isset($_GET['newOne'])) {
        $id = $database->prepare("SELECT id FROM receipt ORDER BY id DESC");
        $id->execute();
        $rid = $id->fetchObject();
    }

    if (isset($_POST['enter'])) {
        // $name = $_POST['nameOfPatient'];
        $med = $_POST['nameOfMedicine'];
        $quantity = $_POST['amount'];
        $getTime = date('h:i:s a', strtotime('+1 hour'));

        $checkInPhar = $database->prepare("SELECT * FROM pharmacy WHERE name = :name");
        $checkInPhar->bindParam("name",$med);
        $checkInPhar->execute();

        if ($checkInPhar->rowCount() == 1) {
            $phMed = $checkInPhar->fetchObject();

            $quan = $phMed->ph_quantity;
            $phId = $phMed->id;
            $medName = $phMed->name;
            $medPrice = $phMed->price;
            $total = $quantity * $medPrice;
            $newPh = $quan - $quantity;

            if ($quan >= $quantity) {
                $add = $database->prepare("INSERT INTO ph_receipt(rid,med_name,med_price,rec_quantity,date,time,total) VALUES(:rid,:med,:price,:q,CURRENT_DATE,:time,:t)");
                $add->bindParam("rid",$rid->id);
                $add->bindParam("med",$medName);
                $add->bindParam("price",$medPrice);
                $add->bindParam("q",$quantity);
                $add->bindParam("time",$getTime);
                $add->bindParam("t",$total);
                $add->execute();
                //update quantity in pharmacy....
                $updatePh = $database->prepare("UPDATE pharmacy SET ph_quantity = :new WHERE id = :id");
                $updatePh->bindParam("new",$newPh);
                $updatePh->bindParam("id",$phId);
                $updatePh->execute();
                // header("refresh: 0");
            }else {
                echo "<script>
                    alert('الكمية غير متاحة')
                    </script>";
            }

        }else{
            echo "<script>
                    alert('الدواء غير موجود بالصيدلية')
                    </script>";
        }
    }
}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
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
                <li class="m-b-15 active">
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
                <li class="num m-b-15">
                    <a class="listted relative width100" href="store.php">
                        <i class="fa-solid fa-shop fa-fw"></i>
                        <span class="m-r-10"> 
                            المخزن 
                        </span>
                        <i class="fa-solid fa-angle-right tog absolute"></i>
                    </a>
                    <div class="list">
                        <a href="" class="m-r-10 width100">المخزن</a>
                        <a href="" class="m-r-10 width100">إضافة دواء</a>
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
                <!-- <div class="search relative">
                    <form class="flex" action="" method="post">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الدواء">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form>
                </div> -->
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="parent flex-sb-0 p-20 ">
                <div class="inputs">
                    <h2>الفاتورة</h2>
                    <form action="" method="post" class="flex-column">
                        <!-- <label for="p_name">إسم المريض</label>
                        <input type="text" name="nameOfPatient" id="p_name" required> -->
                        <label for="m_n">إسم الدواء</label>
                        <input type="text" name="nameOfMedicine" id="m_n" required>
                        <label for="amount">الكمية</label>
                        <input class="m-b-20 " type="text" name="amount" id="amount" placeholder="الكمية" required>
                        <button type="submit" name="enter" class="b-s">إدخال</button>
                    </form>
                </div>
                <div class="reciet " style="height: 100%;">
                    <div class="table_reciet width100 m-b-15" style="height: 70%;">
                        <table class="width100">
                            <thead>
                                <tr>
                                    <td>إسم الدواء</td>
                                    <td>الكمية</td>
                                    <td>السعر</td>
                                    <td>الإجمالي</td>
                                </tr>
                            </thead>
                            <tbody>

                            <?php
                            
                            if (isset($_POST['enter'])) {
                                $rec = $database->prepare("SELECT * FROM ph_receipt LEFT JOIN receipt ON ph_receipt.rid = receipt.id WHERE ph_receipt.rid = :rid AND date = CURRENT_DATE");
                                $rec->bindParam("rid",$rid->id);
                                $rec->execute();

                                //total
                                $tot = $database->prepare("SELECT SUM(total) AS tot FROM ph_receipt WHERE rid = :rid AND date = CURRENT_DATE");
                                $tot->bindParam("rid",$rid->id);
                                $tot->execute();
                                $totC = $tot->fetchObject();
                                $totResult = $totC->tot;

                                //date
                                $date = $database->prepare("SELECT * FROM ph_receipt WHERE rid = :rid AND date = CURRENT_DATE ORDER BY time DESC");
                                $date->bindParam("rid",$rid->id);
                                $date->execute();
                                if ($date->rowCount()>=1) {
                                    
                                    $dateC = $date->fetchObject();
                                    $dateResult = $dateC->date;
                                    $time = $dateC->time;
                                }

                                foreach($rec as $detail) {
                                    echo "<tr>
                                            <td>" . $detail['med_name'] . "</td>
                                            <td >" . $detail['rec_quantity'] . "</td>
                                            <td>" . $detail['med_price'] . "</td>
                                            <td>" . $detail['total'] . "</td>
                                        </tr>";
                                }
                            }
                            ?>
                                
                            </tbody>
                        </table>
                    </div>
                            
                    <div class="total flex-sb-0 m-b-20 p-10">
                        <div class="totalmed">
                            <p>عدد الأدوية : <span><?php if (isset($_POST['enter'])) { echo $rec->rowCount(); }?></span></p>
                        </div>
                        <div class="totalprice">
                            <p>الإجمالي : <span><?php if (isset($_POST['enter'])) { echo $totResult; }?></span></p>
                        </div>
                    </div>
                    <form  action="" method="post">
                        <button name="buy" type="button" class="b-s m-r-20 m-b-20">تأكيد الشراء</button>
                    </form>
                </div>
            </div>
            <div class="show-hide absolute width100 sh2 s-show-hide">
                <i class="fa-solid fa-xmark p-10 width100"></i>
                <div class="hidden-reciet">
                    <div class="logo">
                        <img src="images/download__15_-removebg-preview.png" alt="">
                        <h1>الحياة</h1>
                        <div class="date flex-sb-c m-b-15" style="flex-direction:column-reverse;">
                            <p>الوقت والتاريخ: <span><?php if (isset($_POST['enter']) && $date->rowCount()>=1) { echo $dateResult ." ". $time; }?></span></p>
                        </div>
                    </div>
                    <div class="table_reciet width100 m-b-15">
                        <table class="width100">
                            <thead>
                                <tr>
                                    <td>إسم الدواء</td>
                                    <td>الكمية</td>
                                    <td>السعر</td>
                                    <td>الإجمالي</td>
                                </tr>
                            </thead>
                            <tbody>
                            <?php
                            
                            if (isset($_POST['enter'])) {
                                $rec = $database->prepare("SELECT * FROM ph_receipt LEFT JOIN receipt ON ph_receipt.rid = receipt.id WHERE ph_receipt.rid = :rid AND date = CURRENT_DATE");
                                $rec->bindParam("rid",$rid->id);
                                $rec->execute();

                                //total
                                $tot = $database->prepare("SELECT SUM(total) AS tot FROM ph_receipt WHERE rid = :rid AND date = CURRENT_DATE");
                                $tot->bindParam("rid",$rid->id);
                                $tot->execute();
                                $totC = $tot->fetchObject();
                                $totResult = $totC->tot;

                                foreach($rec as $detail) {
                                    echo "<tr>
                                            <td>" . $detail['med_name'] . "</td>
                                            <td >" . $detail['rec_quantity'] . "</td>
                                            <td>" . $detail['med_price'] . "</td>
                                            <td>" . $detail['total'] . "</td>
                                        </tr>";
                                }
                            }
                            ?>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="total flex-sb-0 p-10">
                        <div class="totalmed">
                            <p>عدد الأدوية : <span><?php if (isset($_POST['enter'])) { echo $rec->rowCount(); }?></span></p>
                        </div>
                        <div class="totalprice">
                            <p>الإجمالي : <span><?php if (isset($_POST['enter'])) { echo $totResult; }?></span></p>
                            <!-- <p>تم دفع : <span>180</span></p>
                            <hr>
                            <p>باقي : <span>10</span></p> -->
                        </div>
                    </div>
                    <hr>
                    <div class="address flex" style="padding: 0 10px;">
                        <p>العنوان : 17 - شارع الشريف متفرع من شارع 15 خلف ماء الذهب</p>
                        <img style="width: 100px; margin-bottom: -20px;" src="images/download__15_-removebg-preview.png" alt="">
                    </div>
                </div>
                <form action="" class="width100" style="text-align: center;" method="post">
                    <input type="button" class="b-s m-t-20" value="طباعة" id="print" onclick="window.print()">
                </form>
            </div>
        </div>
    </div>    
    <script src="pharmacy.js"></script>
    <script src="index.js"></script>
</body>
</html>