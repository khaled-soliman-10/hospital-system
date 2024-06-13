<?php
require_once '../include/conn.php';
session_start();
if(!isset($_SESSION['reception'])){
    header("location:../index.php");
}else{ 
echo '<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <title> الاستقبال</title>
</head>

<body>

    <section class="main start">
        <aside class="clm width">
            <div class="main-content clm">
                <header class="center">
                    <div class="logo center">
                        <img src="imgs/logo2.png" alt="">
                        <a href="index.php">
                            <h1>الـ<span>حـ</span>يـاة</h1>
                        </a>
                    </div>
                    <button class="center"><i class="fa-solid fa-bars"></i></button>
                </header>
                <div class="links clm">
                    <a class="link center" href="index.php">
                        <i class="fa-solid fa-house"></i>
                        <h1>الصفحه الرئيسيه</h1>
                    </a>
                    <a class="link center active" href="addpatient.php">
                        <i class="fa-solid fa-user-plus"></i>
                        <h1>اضافة مريض</h1>
                    </a>
                    <a class="link center" href="online.php">
                        <i class="fa-solid fa-earth-americas"></i>
                        <h1>اون لاين</h1>
                    </a>
                    <a class="link center" href="search.php">
                        <i class="fa-solid fa-earth-americas"></i>
                        <h1>بحث</h1>
                    </a>
                </div>
            </div>
            <form action="" method="post" class="center">
                <button type="submit" name="exit" class="btn center">
                    <i class="fa-solid fa-right-from-bracket"></i>
                    <h1>
                        تسجيل خروج
                    </h1>
                </button>
            </form>
        </aside>';
        if(isset($_POST['exit'])){
            $sql = $database->prepare("UPDATE doctor_logins SET logout = :logout ,status = :status WHERE doctor_id = :doctor_id ORDER BY id DESC LIMIT 1");
            date_default_timezone_set('Africa/Cairo');
            $ldate = date('d-m-Y h:i:s A', time ());
            $sta = 0;
            $sql->bindParam("doctor_id",$_SESSION['reception']);
            $sql->bindParam("logout",$ldate); 
            $sql->bindParam("status",$sta);
            if (!$sql->execute()) {
                echo "Error updating logout time.";
                print_r($sql->errorInfo());
            } else{           
            unset($_SESSION['reception']);
            // session_destroy();
            header("location:../index.php");
            exit();
        }}
        echo '<div class="content clm">';
        $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
            $sql->bindParam("id",$_SESSION['reception']);
            $sql->execute();
            $data = $sql->fetchObject();
            $img="data:".$data->image_type.";base64,".base64_encode($data->image);
           echo '<header class="center">
                <h1>صباح الخير د.  '.$data->doc_name.' </h1>
                <img src="'.$img.'" alt="">
            </header>
            <!-- <div class="add-patient">
                <h3>اضافة مريض</h3>
                <div class="type-patient">
                    <div class="type-one">
                        <a href="addpatient.php"><h4>مريض مباشر </h4></a>
                    </div>
                </div>
                <img src="imgs/logo.png" alt="">
            </div> -->
            <form action="" method="post" class="add-details">
                <h3>مريض مباشر</h3>
                <div class="send">
                    <div>
                        <label for="">اسم المريض</label>
                        <input type="text" name="patient" placeholder="اسم المريض" required>
                    </div>
                    <div>
                        <label for="">الرقم</label>
                        <input type="text" name="number" placeholder="الرقم " required>
                    </div>
                </div>
                <div class="send">
                    <div>
                        <label for=""> القسم</label>
                        <select name="sections" id="" required>';
                       
                                $sql=$database->prepare("SELECT * FROM departments WHERE price != 0");
                                $sql->execute();
                                if($sql->rowCount()>0){
                                    foreach($sql as $depart){
                                         echo'<option value='.$depart['id'].'>'.$depart['d_name'].'</option>';
                                        }
                                    }
                                
                        echo '</select>
                    </div>
                    <div>
                        <label for=""> الجنس</label>
                        <select name="sex" id="" required>
                            <option value="ذكر">ذكر</option>
                            <option value="انثي">انثي</option>
                        </select>
                    </div>
                </div>
                <div class="send">
                    <div>
                        <label for=""> العنوان</label>
                        <input type="text" name="adrress" placeholder="العنوان" required>
                    </div>
                </div>
                <!-- هنضغط علي الزرار ونروح لصفحة الروشته -->
                <button class="submit" type="submit" name="send"><h4>حفظ البيانات</h4></button>
            </form>';
            
    if(isset($_POST['send'])){
        $sql=$database->prepare("INSERT INTO patient(p_name,phone,gender,address,booking_method,confirm,complete,depart_id) 
        VALUES(:p_name,:phone,:gender,:address,:booking_method,:confirm,:complete,:depart_id)");
        $method="offline";
        $conf=1;
        $complet=0;
        $sql->bindParam('p_name',$_POST['patient']);
        $sql->bindParam('phone',$_POST['number']);
        $sql->bindParam('gender',$_POST['sex']);
        $sql->bindParam('address',$_POST['adrress']);
        $sql->bindParam('booking_method',$method);
        $sql->bindParam('confirm',$conf);
        $sql->bindParam('complete',$complet);
        $sql->bindParam('depart_id',$_POST["sections"]);
        if($sql->execute()){
                echo "<script>window.location.href='rosheta.php?id=" . $database->lastInsertId() . "';</script>";
            }
                    }
        
    
       
     echo  '</div>
    </section>
    <script src="js/main.js"></script>
</body>

</html>';}
?>