<?php 
require_once 'include/conn.php';
session_start();
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
    <link rel="stylesheet" href="css/login.css">
    <title>مستشفي الحياة</title>
</head>
<body class="section center">
    
    <div class="login clm">
        <h1>مرحبا بك مجددا</h1>
        <p>سجل دخول الي حسابك للاستمرار</p>
        <form method="POST" class="clm">
            
            <div class="clm">
                <label for="username">اسم المستخدم</label>
                <input type="text" autocomplete="off" name="username" id="username" placeholder="اسم المستخدم" required>
            </div>

            <div class="clm">
                <label for="password">كلمة المرور</label>
                <input type="password" autocomplete="off" name="password" id="password" placeholder="كلمة المرور" required>
                <i class="fa-solid fa-eye"></i>
            </div>

            <div class="clm">
                <label for="role">اختر من انت</label>
                <select name="role" id="role" required>
                    <option value="admin">ادمن (المدير)</option>
                    <!-- <option value="0">دكتور</option>
                    <option value="3">صيدلية</option>
                    <option value="1">مختبر</option>
                    <option value="2">الاستقبال</option> -->
                    <option value="دكتور">دكتور</option>
                    <option value="صيدلية">صيدلية</option>
                    <option value="معمل">مختبر</option>
                    <option value="استقبال">الاستقبال</option>
                </select>
            </div>

            <button type="submit" name="login" class="btn">تسجيل دخول</button>

            <!-- نشيل الكومنت من علي الجملة لظهورها -->
            <!-- <p>حدث خطأ في تسجيل الدخول, تأكد من البيانات ثم حاول مرة اخري</p> -->

        
    <?php
    if(isset($_POST['login'])){
        if($_POST['role'] == "admin"){
            $sql = $database->prepare("SELECT * FROM admin WHERE email = :email AND password = :password");
            $sql->bindParam("email",$_POST['username']);
            $sql->bindParam("password",$_POST['password']);
            $sql->execute();
            $data = $sql->fetchObject();
            if($sql->rowCount() > 0){
                $_SESSION['admin'] = $data->id;
                header("location:admin_dashboard/index.php");
            }else{  echo '<p style = "color:black"><b>حدث خطأ في تسجيل الدخول, تأكد من البيانات ثم حاول مرة اخري</b></p>';}
        }else{
            $sql = $database->prepare("SELECT * FROM doctors WHERE email = :email AND password = :password AND role = :role");
            $sql->bindParam("email",$_POST['username']);
            $sql->bindParam("password",$_POST['password']);
            $sql->bindParam("role",$_POST['role']);
            $sql->execute();
            $data = $sql->fetchObject();
            if($sql->rowCount() > 0){
            // if($data->role == 1){
                if($data->role == "معمل"){
                $_SESSION['lab'] = $data->doc_id;
                $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
                $sql->bindParam("id",$_SESSION['lab']);
                $sql->execute();
                $data = $sql->fetchObject();
                //doctor logins
                $sql2 = $database->prepare("SELECT * FROM doctor_logins WHERE doctor_id = :doctor_id");
                $sql2->bindParam("doctor_id",$data->doc_id);
                $sql2->execute();
                if($sql2->rowCount()> 0){
                    $sql = $database->prepare("UPDATE  doctor_logins SET status=:status, login_time=CURRENT_TIMESTAMP, logout=NULL WHERE doctor_id =:doctor_id ");
                    $stat = 1;
                    $sql->bindParam("status",$stat);
                    $sql->bindParam("doctor_id",$data->doc_id);
                    $sql->execute();
                }else{
                    $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
                    VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");       
                    $stat = 1;
                    $doctor_ip=$_SERVER['REMOTE_ADDR'];
                    $uid = $data->doc_id;
                    $sql->bindParam("doctor_name",$data->doc_name);
                    $sql->bindParam("status",$stat);
                    $sql->bindParam("doctor_id",$uid);
                    $sql->bindParam("doctor_ip",$doctor_ip);
                    $sql->execute();
                }
                // $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
                // VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");       
                // $stat = 1;
                // $doctor_ip=$_SERVER['REMOTE_ADDR'];
                // $uid = $data->doc_id;
                // $sql->bindParam("doctor_name",$data->doc_name);
                // $sql->bindParam("status",$stat);
                // $sql->bindParam("doctor_id",$uid);
                // $sql->bindParam("doctor_ip",$doctor_ip);
                // $sql->execute();
                header("location:lab dashboard/index.php");
            }//elseif($data->role == 2){
                elseif($data->role == "استقبال"){
                $_SESSION['reception'] = $data->doc_id;
                $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
                $sql->bindParam("id",$_SESSION['reception']);
                $sql->execute();
                $data = $sql->fetchObject();
                //doctor logins
                $sql2 = $database->prepare("SELECT * FROM doctor_logins WHERE doctor_id = :doctor_id");
                $sql2->bindParam("doctor_id",$data->doc_id);
                $sql2->execute();
                if($sql2->rowCount()> 0){
                    $sql = $database->prepare("UPDATE  doctor_logins SET status=:status, login_time=CURRENT_TIMESTAMP, logout=NULL WHERE doctor_id =:doctor_id ");
                    $stat = 1;
                    $sql->bindParam("status",$stat);
                    $sql->bindParam("doctor_id",$data->doc_id);
                    $sql->execute();
                }else{
                    $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
                    VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");       
                    $stat = 1;
                    $doctor_ip=$_SERVER['REMOTE_ADDR'];
                    $uid = $data->doc_id;
                    $sql->bindParam("doctor_name",$data->doc_name);
                    $sql->bindParam("status",$stat);
                    $sql->bindParam("doctor_id",$uid);
                    $sql->bindParam("doctor_ip",$doctor_ip);
                    $sql->execute();
                }
                // $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
                // VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");       
                //  $stat = 1;
                // $doctor_ip=$_SERVER['REMOTE_ADDR'];
                // $uid = $data->doc_id;
                // $sql->bindParam("doctor_name",$data->doc_name);
                // $sql->bindParam("status",$stat);
                // $sql->bindParam("doctor_id",$uid);
                // $sql->bindParam("doctor_ip",$doctor_ip);
                // $sql->execute();
                header("location:reception dashboard/index.php");
            }//elseif($data->role == 3){
                elseif($data->role == "صيدلية"){
                $_SESSION['pharmacy'] = $data->doc_id;
                $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
                $sql->bindParam("id",$_SESSION['pharmacy']);
                $sql->execute();
                $data = $sql->fetchObject();
                //doctor logins
                $sql2 = $database->prepare("SELECT * FROM doctor_logins WHERE doctor_id = :doctor_id");
                $sql2->bindParam("doctor_id",$data->doc_id);
                $sql2->execute();
                if($sql2->rowCount()> 0){
                    $sql = $database->prepare("UPDATE  doctor_logins SET status=:status, login_time=CURRENT_TIMESTAMP, logout=NULL WHERE doctor_id =:doctor_id ");
                    $stat = 1;
                    $sql->bindParam("status",$stat);
                    $sql->bindParam("doctor_id",$data->doc_id);
                    $sql->execute();
                }else{
                    $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
                    VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");       
                    $stat = 1;
                    $doctor_ip=$_SERVER['REMOTE_ADDR'];
                    $uid = $data->doc_id;
                    $sql->bindParam("doctor_name",$data->doc_name);
                    $sql->bindParam("status",$stat);
                    $sql->bindParam("doctor_id",$uid);
                    $sql->bindParam("doctor_ip",$doctor_ip);
                    $sql->execute();
                }
                // $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
                // VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");       
                // $stat = 1;
                // $doctor_ip=$_SERVER['REMOTE_ADDR'];
                // $uid = $data->doc_id;
                // $sql->bindParam("doctor_name",$data->doc_name);
                // $sql->bindParam("status",$stat);
                // $sql->bindParam("doctor_id",$uid);
                // $sql->bindParam("doctor_ip",$doctor_ip);
                // $sql->execute();
                header("location:pharmacy_dashboard/index.php");
            }//elseif($data->role == 0){
                elseif($data->role == "دكتور"){
            $_SESSION['doctor'] = $data->doc_id;
            $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
            $sql->bindParam("id",$_SESSION['doctor']);
            $sql->execute();
            $data = $sql->fetchObject();
            //doctor logins
            $sql2 = $database->prepare("SELECT * FROM doctor_logins WHERE doctor_id = :doctor_id");
            $sql2->bindParam("doctor_id",$data->doc_id);
            $sql2->execute();
            if($sql2->rowCount()> 0){
                $sql = $database->prepare("UPDATE  doctor_logins SET status=:status, login_time=CURRENT_TIMESTAMP, logout=NULL WHERE doctor_id =:doctor_id ");
                $stat = 1;
                $sql->bindParam("status",$stat);
                $sql->bindParam("doctor_id",$data->doc_id);
                $sql->execute();
            }else{
                $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
                VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");       
                $stat = 1;
                $doctor_ip=$_SERVER['REMOTE_ADDR'];
                $uid = $data->doc_id;
                $sql->bindParam("doctor_name",$data->doc_name);
                $sql->bindParam("status",$stat);
                $sql->bindParam("doctor_id",$uid);
                $sql->bindParam("doctor_ip",$doctor_ip);
                $sql->execute();
            }
            // $sql = $database->prepare("INSERT INTO doctor_logins(doctor_name,status,doctor_ip,doctor_id) 
            // VALUES(:doctor_name,:status,:doctor_ip,:doctor_id)");
            // $stat = 1;
            // $doctor_ip=$_SERVER['REMOTE_ADDR'];
            // $uid = $data->doc_id;
            // $sql->bindParam("doctor_name",$data->doc_name);
            // $sql->bindParam("status",$stat);
            // $sql->bindParam("doctor_id",$uid);
            // $sql->bindParam("doctor_ip",$doctor_ip);
            // $sql->execute();
                header("location:doctor dashboard/index.php");

            }
        }else{  echo '<p style = "color:black"><b>حدث خطأ في تسجيل الدخول, تأكد من البيانات ثم حاول مرة اخري</b></p>';}

    }}
    ?>
    </form>
    </div>
    <script src="js/pass.js"></script>
</body>
</html>