<?php 

session_start();

try {
    require_once("../connection.php");

    if (!$_SESSION["lab"]) {
        header("location: ../index.php");
        die();
    }
    // echo date("Y-m-d");

    $show = $database->prepare("SELECT * FROM lab");
    $show->execute();

    $show2 = $database->prepare("SELECT * FROM lab");
    $show2->execute();

    if (isset($_POST['update'])) {
        $id = $_POST['analysis'];
        $newPrice = $_POST['price'];

        $update = $database->prepare("UPDATE lab SET price = :price WHERE id = :id");
        $update->bindParam("price",$newPrice);
        $update->bindParam("id",$id);
        $update->execute();
        echo '<script>
                alert("تم التعديل")
            </script>';
        header("refresh: 0");
    }


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
                    <a class="link center" href="add.php">
                        <i class="fa-solid fa-user-plus"></i>
                        <h1>اضافة مريض</h1>
                    </a>
                    <a class="link center" href="addanalysis.php">
                        <i class="fa-solid fa-square-plus"></i>
                        <h1>اضافة تحليل</h1>
                    </a>
                    <a class="link center active" href="newprice.php">
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

                <?php 
                
                if (isset($_GET['name'])) {
                    $search = "%".$_GET['name']."%";
            
                    $sLab = $database->prepare("SELECT * FROM lab WHERE name LIKE :name");
                    $sLab->bindParam("name",$search);
                    $sLab->execute();
                    }
                
                ?>

                <form action="" method="get" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" placeholder="البحث عن التحليل">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form>
                <img src="<?php echo $img; ?>" alt="">
            </header>
            <div class="add-analysis">
                <h2>تعديل تحليل</h2>
                <div class="add-analy">
                    <div class="send-d">
                        <form action="" method="post" class="start" id="kh">
                            <div class="select">
                                <label for="">اسم التحليل</label>
                                <select name="analysis" id="" required  style="padding: 0 5px;">
                                    <!-- <option value=""> اختر التحليل:</option> -->

                                <?php 
                                
                                foreach ($show as $detail) {
                                    echo '<option value="' . $detail['id'] . '">' . $detail['name'] . '</option>';
                                }
                            // }
                            // catch (PDOException $e) {
                            //     echo "failed connect" . $e->getMessage();
                            // }
                                
                                ?>
                                </select>
                            </div>

                            <div>
                                <label for=""> السعر</label>
                                <input type="text" name="price" placeholder="السعر " required  style="padding: 0 5px;">
                            </div>

                            <button type="submit" name="update"><h4>تعديل</h4></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="patients clm">
                <div class="head center">
                    <h1>قائمة التحاليل والاشعه</h1>
                    <img src="imgs/logo.png" alt="">
                </div>
                <div class="tablee">
                    <table>
                        <thead>
                            <tr>
                                <td>اسم التحليل والاشعه</td>
                                <td>السعر</td>
                            </tr>
                        </thead>
                        <tbody>

                        <?php 
                                
if (isset($_GET['name'])) {
    if ($sLab->rowCount() >= 1) {
    foreach($sLab as $la){
        echo '<tr>
                <td>' . $la['name'] . '</td>
                <td>' . $la['price'] . '</td>
            </tr>';
    } 
    }else{
    echo '<tr>
            <td colspan=2 style="font-size: 20px;font-weight:600">لا يوجد تحليل او اشعة بهذا الاسم</td>
        </tr>';
    }
}else{

    foreach ($show2 as $detail) {
        echo '<tr>
        <td>' . $detail['name'] . '</td>
        <td>' . $detail['price'] . '</td>
        </tr>';
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
    </section>
    <script src="js/main.js"></script>
</body>
</html>