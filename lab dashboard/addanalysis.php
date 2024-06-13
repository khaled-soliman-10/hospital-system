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
        $analysis = $_POST['analysis'];
        $price = $_POST['price'];

        $check = $database->prepare("SELECT * FROM lab WHERE name = :name");
        $check->bindParam("name",$analysis);
        $check->execute();

        if ($check->rowCount() == 1) {
            echo '<script>
                    alert("هذا التحليل/الاشعة موجود مسبقا بالمختبر")
                </script>';
        }else {
            $add = $database->prepare("INSERT INTO lab(name,price) VALUES(:name,:price)");
            $add->bindParam("name",$analysis);
            $add->bindParam("price",$price);
            $add->execute();
            echo '<script>
                    alert("تم الاضافه بنجاح")
                </script>';
        }

        header("refresh: 0");

    }

    $show = $database->prepare("SELECT * FROM lab");
    $show->execute();

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
                    <a class="link center" href="add.php">
                        <i class="fa-solid fa-user-plus"></i>
                        <h1>اضافة مريض</h1>
                    </a>
                    <a class="link center active" href="addanalysis.php">
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

                <?php 
                
                if (isset($_GET['name'])) {
                    $search = "%".$_GET['name']."%";
            
                    $sLab = $database->prepare("SELECT * FROM lab WHERE name LIKE :name");
                    $sLab->bindParam("name",$search);
                    $sLab->execute();
                    }
                
                ?>

                <form method="get" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" placeholder="البحث عن التحليل">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form>
                <img src="<?php echo $img; ?>" alt="">
            </header>
            <div class="add-analysis">
                <h2>اضافة تحليل</h2>
                <div class="add-analy">
                    <div class="send-d">
                        <form method="post" class="start" id="kh">
                            <div>
                                <label for="">اسم التحليل</label>
                                <input type="text" name="analysis" placeholder="اسم التحليل" required  style="padding: 0 5px;">
                            </div>
                            
                            <div>
                                <label for=""> السعر</label>
                                <input type="text" name="price" placeholder="السعر " required  style="padding: 0 5px;">
                            </div>

                            <button type="submit" name="add"><h4>اضافة</h4></button>
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

    
    foreach ($show as $detail) {
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