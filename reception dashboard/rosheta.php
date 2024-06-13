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
    <link rel="stylesheet" href="css/style2.css">
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
                    <a class="link center" href="addpatient.php">
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
                <h1>صباح الخير د. '.$data->doc_name.' </h1>

                <img src="'.$img.'" alt="">
            </header>';
if(isset($_GET['id'])){
    require_once '../include/conn.php';
    $chtime = $database->prepare("UPDATE patient SET p_time = CURRENT_TIME , booking_method = 'offline',confirm = 1 WHERE id = :id");
    $chtime->bindParam("id",$_GET['id']);
    $chtime->execute();
    $dept_id = $database->prepare("SELECT depart_id FROM patient WHERE id =:id");
    $dept_id->bindParam("id",$_GET['id']);
    $dept_id->execute();
    $num = $dept_id->fetchColumn();
    $count = $database->prepare("SELECT COUNT(id) FROM patient WHERE  depart_id = $num AND p_date = CURRENT_DATE  AND id < :id ORDER BY id");
    // $com = 0;
    // $sql2->bindParam("complete",$com);
    $count->bindParam("id",$_GET['id']);
    $count->execute();                             
     //SELECT COUNT(id) FROM patient WHERE depart_id = 1 AND p_date = CURRENT_DATE AND id<60 ORDER BY id;

    $sql = $database->prepare("SELECT patient.* ,departments.price FROM patient INNER JOIN departments ON patient.depart_id = departments.id WHERE patient.id = :id;");
    $sql->bindParam("id",$_GET['id']);
    $sql->execute();
    $data =$sql->fetchObject();

    echo ' <div class="rosheta">
                <div class="title">
                    <img src="imgs/logo.png" alt="">
                    <a href=" ">
                        <h1>الـ<span>حـ</span>يـاة</h1>
                    </a>
                </div>
                <div class="time">
                    <p> التاريخ:';echo $data->p_date;echo '</p>
                </div>
                <div class="time">
                    <p> الوقت:';echo date("g:i:s A", strtotime($data->p_time));echo '</p>
                </div>
                
                <div class="details-pat">
                    <p>الاسم: '; echo $data->p_name; echo '</p>
                    <p>عدد المنتظرين:';print_r($count->fetchColumn()); echo'</p>
                    <p>المبلغ المدفوع:';echo $data->price; echo '</p>
                
                </div>
                <div class="address">
                <br>
                    <p>العنوان: شارع 15 خلف ماء الذهب</p>
                </div>
            </div>
            <form action="" method="POST"><button class="prints" type = "button" onclick ="window.print()"><h3>طباعة</h3></button></form>
            </div>
    </section>
    <script src="js/rosheta.js"></script>
    <script src="js/main.js"></script>

</body>

</html>';}}
?>
