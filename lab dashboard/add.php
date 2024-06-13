<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION["lab"]) {
        header("location: ../index.php");
        die();
    }
    // echo date("Y-m-d");

    if (isset($_POST['add'])) {
        $patient = $_POST['patient'];
        $analysisId = $_POST['analysis'];
        $getTime = date('h:i:s a', strtotime('+1 hour'));

        //get info from lab 
        $getInfo = $database->prepare("SELECT * FROM lab WHERE id = :id");
        $getInfo->bindParam("id",$analysisId);
        $getInfo->execute();

        $info = $getInfo->fetchObject();
        $labName = $info->name;
        $labPrice = $info->price;

        //insert data into receipt
        $insert = $database->prepare("INSERT INTO lab_receipt(p_name , date , time , lab_name , lab_price) VALUES(:p , CURRENT_DATE , :time , :l , :price)");
        $insert->bindParam("p",$patient);
        $insert->bindParam("time",$getTime);
        $insert->bindParam("l",$labName);
        $insert->bindParam("price",$labPrice);
        $insert->execute();

        // //get patient`s data from lab_receipt
        // $patientData = $database->prepare("SELECT * FROM lab_receipt WHERE p_name = :p_name");
        // $patientData->bindParam("p_name",$pName);
        // $patientData->execute();

        // //get total price and date from this patient
        // $totalPrice = $database->prepare("SELECT SUM(lab_price) AS total , date FROM lab_receipt WHERE p_name = :p_name");
        // $totalPrice->bindParam("p_name",$pName);
        // $totalPrice->execute();

        // $getTotal = $totalPrice->fetchObject();
        // $total = $getTotal->total;
        // $date = $getTotal->date;

        // header("refresh: 0");

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
                    <a class="link center" href="index.php">
                        <i class="fa-solid fa-house"></i>
                        <h1>الصفحه الرئيسيه</h1>
                    </a>
                    <a class="link center active" href="add.php">
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
                <img src="<?php echo $img; ?>" alt="">
            </header>
            <div class="add-patient">
                <div class="add-pat">
                    <h2>الفاتورة</h2>
                    <div class="send-d">
                        <form method="post" class="clm" id="kh">
                            <div class="kh">
                                <label for="">اسم المريض</label>
                                <input type="text" name="patient" placeholder="اسم المريض" required>
                            </div>

                            <div class="select">
                                <label for="analysis">اسم التحليل</label>
                                <select name="analysis" id="analysis" required>
                                    <?php 

$show = $database->prepare("SELECT * FROM lab");
$show->execute();
                                    
                                    foreach($show as $analysis) {
                                        echo '<option value="' . $analysis['id'] . '">' . $analysis['name'] . '</option>';
                                    }
                                    
                                    ?>
                                </select>
                            </div>

                            <button type="submit" name="add"><h4>اضافة</h4></button>
                        </form>
                    </div>
                </div>
                <!--  الفاتوره النهائية المخفيه -->
                <div class="rosheta-k">
                    <i class="fa-solid fa-xmark"></i>
                    <div class="tables" id="rosheta" style="height:85%;">
                    <div class="logo center">
                        <a href="index.php"><h1>الـ<span>حـ</span>يـاة</h1></a>
                    </div>
                    <?php
                    
                    //get total price and date from this patient
                    $totalPrice = $database->prepare("SELECT SUM(lab_price) AS total , date , time FROM lab_receipt WHERE p_name = :p_name AND date = CURRENT_DATE");
                    $totalPrice->bindParam("p_name",$patient);
                    $totalPrice->execute();

                    $getTotal = $totalPrice->fetchObject();
                    $total = $getTotal->total;
                    $date = $getTotal->date;
                    $time = $getTotal->time;

                    ?>
                    <p class="name">الاسم:<?php if (isset($_POST['add'])) { echo $patient; } ?></p>
                    <div class="time">
                        <p>الوقت والتاريخ : <?php if (isset($_POST['add'])) { echo $date ." ". $time; } ?></p>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th>اسم التحليل والاشعه</th>
                                <th>السعر</th>
                                <th>الاجمالي</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php 

//get patient`s data from lab_receipt
$patientData = $database->prepare("SELECT * FROM lab_receipt WHERE p_name = :p_name AND date = CURRENT_DATE");
$patientData->bindParam("p_name",$patient);
$patientData->execute();


if (isset($_POST['add'])) {

    foreach($patientData as $data){
        echo '<tr>
                <td>' . $data['lab_name'] . '</td>
                <td>' . $data['lab_price'] . '</td>
                <td>' . $data['lab_price'] . '</td>
            </tr>';
    }
    }


?>
                            
                        </tbody>
                    </table>
                    <div class="price">
                        <div>
                            <p>عدد التحاليل والاشعه:<?php if (isset($_POST['add'])) { echo $patientData->rowCount(); } ?></p>
                        </div>
                        <div>
                            <p>الاجمالي:<?php if (isset($_POST['add'])) { echo $total; } ?></p>
                            <!-- <p>تم الدفع:1000</p>
                            <p>الباقي:20</p> -->
                        </div>
                    </div>
                    <div class="place">
                        <div class="exactly">
                            <p>العنوان:17-شارع الشريف متفرع من شارع 15 خلف ماء الذهب</p>
                        </div>
                        <img src="imgs/logo.png" alt="">
                    </div>
                </div>
                <!-- الفاتورة الظاهره -->
                    <form action="" method="post"><button type="submit" class="printed" onclick="window.print()"><h4>طباعة</h4></button></form>
                </div>
                <div class="tabl" style="height: 80%;">
                    <table>
                        <thead>
                            <tr>
                                <th>اسم التحليل والاشعه</th>
                                <th>السعر</th>
                                <th>الاجمالي</th>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 

                        //get patient`s data from lab_receipt
                        $patientData = $database->prepare("SELECT * FROM lab_receipt WHERE p_name = :p_name AND date = CURRENT_DATE");
                        $patientData->bindParam("p_name",$patient);
                        $patientData->execute();

                        //get total price and date from this patient
                        $totalPrice = $database->prepare("SELECT SUM(lab_price) AS total , date FROM lab_receipt WHERE p_name = :p_name AND date = CURRENT_DATE");
                        $totalPrice->bindParam("p_name",$patient);
                        $totalPrice->execute();

                        $getTotal = $totalPrice->fetchObject();
                        $total = $getTotal->total;
                        $date = $getTotal->date;
                        
                        if (isset($_POST['add'])) {

                            foreach($patientData as $data){
                                echo '<tr>
                                        <td>' . $data['lab_name'] . '</td>
                                        <td>' . $data['lab_price'] . '</td>
                                        <td>' . $data['lab_price'] . '</td>
                                    </tr>';
                            }
                            }
                        
                    
                        ?>
                            
                        </tbody>
                    </table>
                    <div class="price">
                        <div>
                            <p>عدد التحاليل والاشعه:<?php if (isset($_POST['add'])) { echo $patientData->rowCount(); } ?></p>
                        </div>
                        <div>
                            <p>الاجمالي:<?php if (isset($_POST['add'])) { echo $total; } ?></p>
                            <!-- <p>تم الدفع:1000</p>
                            <p>الباقي:20</p> -->
                        </div>
                    </div>
                    <form method="post"><button type="button" id="ok"><h4>تأكيد</h4></button></form>
                </div>
            </div>
        </div>
    </section>
    <script src="js/main.js"></script>
    <script src="js/rosh.js"></script>
</body>
<?php 

}
catch (PDOException $e) {
    echo "failed connect" . $e->getMessage();
}

?>