<?php
require_once '../include/conn.php';
session_start();
if(!isset($_SESSION['reception'])){
    header("location:../index.php");
}else{
$del = $database->prepare("DELETE FROM patient WHERE p_date < CURRENT_DATE AND booking_method = 'online'");
$del->execute();
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
                    <a class="link center" href="addpatient.php">
                        <i class="fa-solid fa-user-plus"></i>
                        <h1>اضافة مريض</h1>
                    </a>
                    <a class="link center active" href="online.php">
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
                <h1>صباح الخير د.  '.$data->doc_name.'</h1>
                <form action="" method="POST" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" class="search" placeholder="البحث عن اسماء المرضي">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form>
                <img src="'.$img.'" alt="">
            </header>
            <div class="patients clm">
                <div class="head center">
                    <h1>قائمة المرضي</h1>
                    <span class="pay">
                        <span class="pays">
                            <p>تم الدفع</p>
                            <i class="fa-solid fa-circle"></i>
                        </span>
                    <span class="not-pays">
                            <p>لم يدفع</p>
                            <i class="fa-solid fa-circle"></i>
                        </span>
                    </span>
                    <img src="imgs/logo.png" alt="">
                </div>
                <div class="tabl">
                    <table>
                        <thead>
                            <tr>
                                <td>اسم المريض</td>
                                <td>العنوان</td>
                                <td>تسجيل الدخول</td>
                                <td>الرقم</td>
                                <td>القسم</td>
                                <td> الجنس</td>
                                <td>تأكيد الحجز</td>
                                <td>الفاتورة</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>'; 
                            if(isset($_POST['search'])){
                                $search = $database->prepare("SELECT patient.* , departments.d_name FROM patient INNER JOIN departments ON patient.depart_id = departments.id WHERE patient.p_name LIKE :p_name AND patient.booking_method = 'online' AND patient.p_date = CURRENT_DATE ORDER BY patient.id");
                                $val = "%".$_POST['name'] . "%";
                                $search->bindParam("p_name", $val);
                                $search->execute();
                                foreach($search as $se){
                                    echo '<td>'.$se["p_name"].'</td>
                                <td>'.$se["address"].'</td>
                                <td>'.$se["p_date"].'</td>
                                <td>'.$se["phone"].'</td>
                                <td>'.$se["d_name"].'</td>
                                <td>'.$se["gender"].'</td>
                            
                                <td>';
                                echo '<form action="" method="POST">
                                           <input type="hidden" name="hidd" value="' . $se['confirm'] . '">
                                             <button type="submit" class="paying" style="background-color: red;" name="conf" value="' . $se["id"] . '"><h5></h5></button>
                                     </form>';
                            //     if ($se['confirm'] == 0) {
                            //         echo '<form action="" method="POST">
                            //                 <input type="hidden" name="hidd" value="' . $se['confirm'] . '">
                            //                 <button type="submit" class="paying" style="background-color: red;" name="conf" value="' . $se["id"] . '"><h5></h5></button>
                            //               </form>';
                            //     } else if ($se['confirm'] == 1) {
                            //         echo '<form action="" method="POST">
                            //                 <input type="hidden" name="hidd" value="' . $se['confirm'] . '">
                            //                 <button type="submit" class="paying" style="background-color: green;" name="conf" value="' . $se["id"] . '"><h5></h5></button>
                            //               </form>';
                            //     }
                            //     echo '</td>';
                            //    echo '<td>
                            //         <a href="rosheta.php?id='.$se['id'].'" class="btn">طباعة</a>
                            //         </td>
                                
                           echo '</tr>';};
                            
                        echo '</tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="js/main.js"></script>
</body>

</html>';
// if (isset($_POST['conf'])) {
//     if ($_POST['hidd'] == 0) {
//         $up = $database->prepare("UPDATE patient SET confirm = 1 WHERE id = :id");
//         $up->bindParam("id", $_POST['conf']);
//         $up->execute();
//         echo" <script>window.location.href='online.php';</script>";
//     } else if ($_POST['hidd'] == 1) {
//         $up = $database->prepare("UPDATE patient SET confirm = 0 WHERE id = :id");
//         $up->bindParam("id", $_POST['conf']);
//         $up->execute();
//         echo" <script>window.location.href='online.php';</script>";
//     }
//                                 }
                            }else{
                                $sql = $database->prepare("SELECT patient.*, departments.d_name FROM patient INNER JOIN departments ON patient.depart_id = departments.id WHERE patient.booking_method = 'online' AND patient.p_date = CURRENT_DATE ORDER BY patient.id");
                                $sql->execute();
                                 foreach($sql as $data){
                                echo '<td>'.$data["p_name"].'</td>
                                <td>'.$data["address"].'</td>
                                <td>'.$data["p_date"].'</td>
                                <td>'.$data["phone"].'</td>
                                <td>'.$data["d_name"].'</td>
                                <td>'.$data["gender"].'</td>
                            
                                <td>';
                                echo '<form action="" method="POST">
                                             <input type="hidden" name="hidd" value="' . $data['confirm'] . '">
                                             <button type="submit" class="paying" style="background-color: red;" name="conf" value="' . $data["id"] . '"><h5></h5></button>
                                          </form>';
                                // if ($data['confirm'] == 0) {
                                //     echo '<form action="" method="POST">
                                //             <input type="hidden" name="hidd" value="' . $data['confirm'] . '">
                                //             <button type="submit" class="paying" style="background-color: red;" name="conf" value="' . $data["id"] . '"><h5></h5></button>
                                //           </form>';
                                // } else if ($data['confirm'] == 1) {
                                //     echo '<form action="" method="POST">
                                //             <input type="hidden" name="hidd" value="' . $data['confirm'] . '">
                                //             <button type="submit" class="paying" style="background-color: green;" name="conf" value="' . $data["id"] . '"><h5></h5></button>
                                //           </form>';
                                // }
                                // echo '</td>';
                               echo '<td>
                                    <a href="rosheta.php?id='.$data['id'].'" class="btn">طباعة</a>
                                    </td>
                                
                            </tr>';};
                            
                       echo '</tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
    <script src="js/main.js"></script>
</body>

</html>';
// if (isset($_POST['conf'])) {
//     if ($_POST['hidd'] == 0) {
//         $up = $database->prepare("UPDATE patient SET confirm = 1 WHERE id = :id");
//         $up->bindParam("id", $_POST['conf']);
//         $up->execute();
//         echo" <script>window.location.href='online.php';</script>";
//     } else if ($_POST['hidd'] == 1) {
//         $up = $database->prepare("UPDATE patient SET confirm = 0 WHERE id = :id");
//         $up->bindParam("id", $_POST['conf']);
//         $up->execute();
//         echo" <script>window.location.href='online.php';</script>";
//     }
// }
}
}
?>