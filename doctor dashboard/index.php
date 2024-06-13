<?php
require_once '../include/conn.php';
session_start();
if(!isset($_SESSION['doctor'])){
header("location:../index.php");
}else{
    $sql = $database->prepare("SELECT * FROM doctors WHERE doc_id = :id");
            $sql->bindParam("id",$_SESSION['doctor']);
            $sql->execute();
            $data = $sql->fetchObject();
            if (isset($_POST['done'])) {
    
                $up = $database->prepare("UPDATE patient SET complete = 1 WHERE id = :id");
                $up->bindParam("id", $_POST['done']);
                $up->execute();
                header("refresh:0");
            
         }
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
    <title>مستشفي الحياة</title>
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

                    <a class="link center active" href="index.php">
                        <i class="fa-solid fa-bed-pulse fa-fw"></i>
                        <h1>المرضي</h1>
                    </a>

                    <a class="link center" href="treatment.php">
                        <i class="fa-solid fa-file-medical fa-fw"></i>
                        <h1>الروشتة</h1>
                    </a>

                    <a class="link center" href="medical.php">
                        <i class="fa-solid fa-receipt fa-fw"></i>
                        <h1>التشخيص المرضي</h1>
                    </a>
                </div>

            </div>

            <form action="" method="POST" class="center">
                <button type="submit" name="exit" class="btn center">
                    <i class="fa-solid fa-right-from-bracket fa-fw"></i>
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
            $sql->bindParam("doctor_id",$_SESSION['doctor']);
            $sql->bindParam("logout",$ldate); 
            $sql->bindParam("status",$sta);
            if (!$sql->execute()) {
                echo "Error updating logout time.";
                print_r($sql->errorInfo());
            } else{           
            unset($_SESSION['doctor']);
            // session_destroy();
            header("location:../index.php");
            exit();
        }}

       echo' <div class="content clm">
            <header class="center">';
            $img="data:".$data->image_type.";base64,".base64_encode($data->image);
                echo '<h1>صباح الخير د. '.$data->doc_name.' </h1>
                <form action="" method="POST" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" placeholder="البحث عن اسماء المرضي">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form>
                <form method = "POST" enctype="multipart/form-data">
                <img src="'.$img.'" alt="">
                </form>
            </header>';
            
           echo '<div class="patients clm">

                <div class="head center">
                    <h1>قائمة المرضي</h1>
                    <img src="imgs/logo.png" alt="">
                </div>

                <div class="table">
                    <table>
                        <thead>
                            <tr>
                                <td>اسم المريض</td>
                                <td> القسم</td>
                                <td>تسجيل الدخول</td>
                                <td>الرقم</td>
                                <td>الجنس</td>
                                <td> العنوان</td>
                                <td>الحاله</td>
                                <td>رؤيه</td>
                            </tr>
                        </thead>
                        <tbody>';
                        if(isset($_POST['search'])){
                        $search = $database->prepare("SELECT patient.*,departments.d_name FROM patient INNER JOIN departments ON patient.depart_id	= departments.id 
                        WHERE patient.p_name LIKE :p_name AND patient.depart_id =:role AND patient.confirm = 1 AND patient.p_date = CURRENT_DATE()");
                        $val = $_POST['name'] . "%";
                        $depart = $data->depart_id;
                        $search->bindParam("role",$depart);
                        $search->bindParam("p_name",$val);
                        $search->execute();
                        foreach($search as $se){
                            if($se['complete'] == 0){
                                echo '<tr>
                                    <td>';echo $se["p_name"];echo '</td>
                                     <td>';echo $se["d_name"];echo'</td>
                                     <td>';echo $se["p_date"]; echo '</td>
                                     <td>';echo $se["phone"];echo '</td>
                                     <td>';echo $se["gender"];echo '</td>
                                     <td>';echo $se["address"];echo '</td> 
                                     <td>';    
                                     echo '<form action="" method="POST">
                                    <button type="submit" style="background-color: red;color:white;" name = "done"  value = "'.$se["id"].'">تم الكشف</button>
                                    </form>';
                                    echo '</td>
                                   <td><a href="medical-history.php?val='.$se["p_name"].'  " class="dots"><i class="fa-solid fa-ellipsis"></i></a></td>';
                        
                                   echo '</tr>'; 
                                }else{
                                echo '<tr>
                                <td>';echo $se["p_name"];echo '</td>
                                 <td>';echo $se["d_name"];echo'</td>
                                 <td>';echo $se["p_date"]; echo '</td>
                                 <td>';echo $se["phone"];echo '</td>
                                 <td>';echo $se["gender"];echo '</td>
                                 <td>';echo $se["address"];echo '</td> 
                                 <td>';    
                                 echo '<form action="" method="POST">
                                <button type="submit" style="background-color: green;color:white;" name = "done"  value = "'.$se["id"].'">تم الكشف</button>
                                </form>';  
                                echo '</td>
                       <td><a href="medical-history.php?val='.$se["p_name"].'  " class="dots"><i class="fa-solid fa-ellipsis"></i></a></td>';
                        
                      echo '</tr>';
                            }
                    }
                       echo' </tbody>
                       </table>
   
                 </div>
               </div>
           </div>
       </section>
   
       <script src="js/main.js"></script>
   </body>
   
   </html>';

}else{
                        $patient = $database->prepare("SELECT patient.*,departments.d_name FROM patient INNER JOIN departments ON patient.depart_id	= departments.id 
                        WHERE patient.depart_id =:role AND patient.confirm = 1 AND patient.p_date = CURRENT_DATE");
                        $depart = $data->depart_id;
                        $patient->bindParam("role",$depart);
                        $patient->execute();
                        foreach($patient as $pa){
                            if($pa['complete'] == 0){
                                echo '<tr>
                                    <td>';echo $pa["p_name"];echo '</td>
                                     <td>';echo $pa["d_name"];echo'</td>
                                     <td>';echo $pa["p_date"]; echo '</td>
                                     <td>';echo $pa["phone"];echo '</td>
                                     <td>';echo $pa["gender"];echo '</td>
                                     <td>';echo $pa["address"];echo '</td> 
                                     <td>';    
                                     echo '<form action="" method="POST">
                                    <button type="submit" style="background-color: red;color:white;" name = "done"  value = "'.$pa["id"].'">تم الكشف</button>
                                    </form>'; 
                                    echo '</td>
                       <td><a href="medical-history.php?val='.$pa["p_name"].'  " class="dots"><i class="fa-solid fa-ellipsis"></i></a></td>';
                        
                      echo '</tr>';
                                    
                            }else{
                                echo '<tr>
                                <td>';echo $pa["p_name"];echo '</td>
                                 <td>';echo $pa["d_name"];echo'</td>
                                 <td>';echo $pa["p_date"]; echo '</td>
                                 <td>';echo $pa["phone"];echo '</td>
                                 <td>';echo $pa["gender"];echo '</td>
                                 <td>';echo $pa["address"];echo '</td> 
                                 <td>';    
                                 echo '<form action="" method="POST">
                                <button type="submit" style="background-color: green;color:white;" name = "done"  value = "'.$pa["id"].'">تم الكشف</button>
                                </form>'; 
                                echo '</td>
                       <td><a href="medical-history.php?val='.$pa["p_name"].'  " class="dots"><i class="fa-solid fa-ellipsis"></i></a></td>';
                        
                      echo '</tr>'; 
                            }
                        
                    }
                            

                       echo' </tbody>
                    </table>

              </div>
            </div>
        </div>
    </section>

    <script src="js/main.js"></script>
</body>

</html>';

}
}
?>
