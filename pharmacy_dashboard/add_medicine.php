<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION['pharmacy']) {
        header("location: ../index.php");
        die();
    }
    // echo date("Y-m-d");

    $drugs = $database->prepare("SELECT * FROM pharmacy");
    $drugs->execute();

    if (isset($_POST['add'])) {
        $medName = $_POST['medname'];
        $quantity = $_POST['quantity'];

        $checkMedInStore = $database->prepare("SELECT * FROM store WHERE name = :medName");
        $checkMedInStore->bindParam("medName",$medName);
        $checkMedInStore->execute();

        if ($checkMedInStore->rowCount()==1) {
            $medDetails = $checkMedInStore->fetchObject();
            if ($quantity <= $medDetails->quantity) {
                $medId = $medDetails->id;
                $newQuantity = $medDetails->quantity - $quantity;
                $updateQuantity = $database->prepare("UPDATE store SET quantity = :new WHERE id = :id");
                $updateQuantity->bindParam("id",$medId);
                $updateQuantity->bindParam("new",$newQuantity);
                $updateQuantity->execute();
                ########################in pharmacy
                $checkInPhar = $database->prepare("SELECT * FROM pharmacy WHERE name = :medName");
                $checkInPhar->bindParam("medName",$medName);
                $checkInPhar->execute();
                if ($checkInPhar->rowCount()==1) {
                    $medicine = $checkInPhar->fetchObject();
                    $oldQuantity = $medicine->ph_quantity;
                    $new = $oldQuantity + $quantity;
                    $updatePhar = $database->prepare("UPDATE pharmacy SET ph_quantity = :new WHERE name = :medName");
                    $updatePhar->bindParam("medName",$medName);
                    $updatePhar->bindParam("new",$new);
                    $updatePhar->execute();
                    echo "<script>
                        alert('تم الاضافة بنجاح')
                        </script>";
                    header("refresh: 0; url = add_medicine.php"); 
                }else {
                    $addMed = $database->prepare("INSERT INTO pharmacy(name,price,ph_quantity,p_date,exp_date) VALUES(:name,:price,:quan,:p_date,:exp_date)");
                    $addMed->bindParam("name",$medName);
                    $addMed->bindParam("price",$medDetails->price);
                    $addMed->bindParam("quan",$quantity);
                    $addMed->bindParam("p_date",$medDetails->p_date);
                    $addMed->bindParam("exp_date",$medDetails->exp_date);
                    $addMed->execute();
                    echo "<script>
                        alert('تم الاضافة بنجاح')
                        </script>";
                    header("refresh: 0; url = add_medicine.php"); 
                }
            }else {
                echo "<script>
                    alert('الكمية غير متاحة في المخزن')
                    </script>";
                header("refresh: 0; url = add_medicine.php");
            }
        }else {
            echo "<script>
                    alert('الدواء غير متاح في المخزن')
                    </script>";
            header("refresh: 0; url = add_medicine.php");
        }
    }



?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>الصيدلية</title>
    <!-- css frame work -->
    <link rel="stylesheet" href="css_files/frame_work.css">
    <!-- css main file -->
    <link rel="stylesheet" href="css_files/master.css">
    <link rel="stylesheet" href="css_files/entermedicine.css">
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
                    <a href="add_receipt.php" class="width100">
                        <i class="fa-solid fa-receipt fa-fw"></i> 
                        <span class="m-r-10">  فاتورة مريض</span>
                    </a>
                </li>
                <li class="m-b-15 active">
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
            
                    $med = $database->prepare("SELECT * FROM pharmacy WHERE name LIKE :name");
                    $med->bindParam("name",$search);
                    $med->execute();
                        }
                
                ?>

                <div class="search relative">
                    <form class="flex" method="get">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الدواء">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form>
                </div>
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="enter-medicine width100">
                <h2 class="title m-r-20">
                    إضافة دواء من المخزن
                </h2>
                <form action="" class="width100" method="post">
                    <div class="data flex m-r-20 m-t-20">
                        <div class="medcine_name">
                            <label for="medname">إسم الدواء</label>
                            <input type="text" name="medname" id="medname" class="width100" placeholder="أدخل إسم الدواء" required>
                        </div>
                        <div class="medicine_amount">
                            <label for="amount">الكمية</label>
                            <input class="width100" type="text" name="quantity" id="amount" placeholder="الكمية" required>
                        </div>
                    </div>
                    <button type="submit" class="add b-s m-t-20 m-r-20 m-b-20" name="add">إضافة</button>
                </form>
                <div class="medicine_list width100 p">
                    <div class="searching width100  m-r-10">
                        <h2>قأمة الأدوية</h2>
                        <!-- <form action="" method="post">
                            <input type="text" name="search_medicine" id="" placeholder="البحث عن إسم الدواء">
                            <button type="submit" class="b-s">بحث</button>
                        </form> -->
                    </div>
                    <div class="table p-10">
                        <table class="width100 m-t-20">
                            <thead>
                                <tr>
                                    <td>إسم الدواء</td>
                                    <td>الكمية</td>
                                    <td> السعر</td>
                                    <td>تاريخ الصلاحية</td>
                                    <td>تاريخ الإنتهاء</td>
                                    <!-- <td>حذف</td> -->
                                </tr>
                            </thead>
                            <tbody>

                            <?php 
    if (isset($_GET['medicine_name'])) {
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
            // 
        }else{
        echo '<tr>
                <td colspan=5 style="font-size: 20px;font-weight:600">لا يوجد دواء بهذا الاسم</td>
            </tr>';
        }
    }else{
    
        foreach($drugs as $drug) {
            echo "<tr>
            <td class='p-10'>" . $drug["name"] . "</td>
            <td class='p-10'>" . $drug["ph_quantity"] . "</td>
            <td class='p-10'>" . $drug["price"] . "</td>
            <td class='p-10'>" . $drug["p_date"] . "</td>
            <td class='p-10'>" . $drug["exp_date"] . "</td>
            </tr>";
        }
    }
}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
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