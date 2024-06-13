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
    <link rel="stylesheet" href="css/search.css">
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
                <h1>صباح الخير د. مهند اسعد</h1>
                <form action="" method="get" class="center">
                    <div class="center">
                        <i class="fa-solid fa-magnifying-glass"></i>
                        <input type="search" name="name" class="search" placeholder="البحث عن اسماء المرضي">
                    </div>
                    <button class="btn center" type="submit" name="search">بحث</button>
                </form>
                <img src="imgs/ibrahem.jpg" alt="">
            </header>
            <div class="patient_search">
                <h2 class="">عرض المريض</h2>
                <form action="" class="" method="post">
                    <label for="s_p">البحث عن المريض</label>
                    <input type="search" name="searchForPatient" id="s_p" placeholder="إسم المريض">
                    <button type="submit" class="b-s"><h3>بحث</h3></button>
                </form>
                <div class="patient_table">
                    <table>
                        <thead>
                            <tr>
                                <td>اسم المريض</td>
                                <td>اسم التحليل والاشعه</td>
                                <td>السعر</td>
                                <td>تسجيل الددخول</td>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>احمد صلاح</td>
                                <td>DNA</td>
                                <td>100</td>
                                <td>22.12.2023</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </section>
</body>
</html>