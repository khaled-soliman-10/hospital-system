<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION['pharmacy']) {
        header("location: ../index.php");
        die();
    }
    // echo date("Y-m-d");

    if (isset($_POST["add"])) {
        $medName = $_POST['medName'];
        $quantity = $_POST['quantity'];
        $price = $_POST['price'];
        $primary = $_POST['primary'];
        $expiry = $_POST['expiry'];

        $checkStore = $database->prepare("SELECT * FROM store WHERE name = :name");
        $checkStore->bindParam("name",$medName);
        $checkStore->execute();

        if ($checkStore->rowCount()==1) {
            $med = $checkStore->fetchObject();
            $medId = $med->id;
            $newQuantity = $med->quantity + $quantity;

            $updateMed = $database->prepare("UPDATE store SET name = :name , price = :price , quantity = :quantity , p_date = :p , exp_date = :exp WHERE id = :id");
            $updateMed->bindParam("name",$medName);
            $updateMed->bindParam("price",$price);
            $updateMed->bindParam("quantity",$newQuantity);
            $updateMed->bindParam("p",$primary);
            $updateMed->bindParam("exp",$expiry);
            $updateMed->bindParam("id",$medId);

            $updateMed->execute();

            echo "<script>
                alert('تم الاضافة بنجاح')
                </script>";
            header("refresh: 0");
        }else{
            $addMed = $database->prepare("INSERT INTO store(name ,price,quantity,p_date,exp_date) VALUES(:name,:price,:quan,:p,:ex)");
            $addMed->bindparam("name",$medName);
            $addMed->bindparam("price",$price);
            $addMed->bindparam("quan",$quantity);
            $addMed->bindparam("p",$primary);
            $addMed->bindparam("ex",$expiry);

            $addMed->execute();

            echo "<script>
                alert('تم الاضافة بنجاح')
                </script>";
            header("refresh: 0");
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
    <link rel="stylesheet" href="css_files/add_to_store.css">
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
                    <a href="add_receipt.php" class="width100">
                        <i class="fa-solid fa-receipt fa-fw"></i> 
                        <span class="m-r-10">  فاتورة مريض</span>
                    </a>
                </li>
                <li class="m-b-15 ">
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
                        <a href="store.php" class="m-r-10 width100">المخزن</a>
                        <a href="add_med_to_store.php" class="m-r-10 width100 active">إضافة دواء</a>
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
                <div class="search relative">
                    <!-- <form class="flex" action="" method="post">
                        <input class="width100" type="search" name="medicine_name" id="" placeholder="البحث عن اسم الدواء">
                        <i class="fa-solid fa-magnifying-glass absolute"></i>
                        <input class="s_b b-s bold" name="search_medicine" type="submit" value="بحث">
                    </form> -->
                </div>
                <img src="<?php echo $img; ?>" alt="" class="avatar">
            </div>
            <div class="store_med width100">
                <h2 class="width100 m-r-20 m-b-20">إضافة دواء</h2>
                <form action="" class="width100 p-rl-20 " method="post">
                    <div class="div1 father flex width100">
                        <div>
                            <label for="medName">إسم الدواء</label>
                            <input type="text" placeholder="اسم الدواء" id="medName" name="medName" required>
                        </div>
                    </div>
                    <div class="div2 father flex width100">
                        <div>
                            <label for="countity" class="">الكمية</label>
                            <input type="text" placeholder="الكمية" id="countity" name="quantity" required>
                        </div>
                        <div>
                            <label for="price">السعر</label>
                            <input type="text" placeholder="السعر" id="price" name="price" required>
                        </div>
                    </div>
                    <div class="div3 father flex width100">
                        <div>
                            <label for="primary">تاريخ الانتاج</label>
                            <input type="date" placeholder="تاريخ الصلاحية" id="primary" name="primary" required>
                        </div>
                        <div>
                            <label for="expiry">تاريخ الإنتهاء</label>
                            <input type="date" placeholder="تاريخ الإنتهاء" id="expiry" name="expiry" required>
                        </div>
                    </div>
                    <button type="submit" class="b-s" name="add">إضافة</button>
                </form>
            </div>
        </div>
    </div>    
    <script src="pharmacy.js"></script>
    <script src="index.js"></script>
</body>
</html>