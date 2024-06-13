<?php
require_once '../include/conn.php';
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
    <link rel="stylesheet" href="css/hospital.css">
    <title>مستشفي الحياة</title>
    <style>
        .mode {
            width: 35px;
            height: 35px;
            font-size: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            background-color: transparent;
            border: none;
            color: var(--black-color);
            transition: .3s;
        }
        .mode i {
            transition: .3s;
        }
        .mode:hover {
            color: var(--gray-color);
        }
        footer .f-content .feedback form button {
            background-color: var(--white-color);
        }
        .dark {
            --main-color: #5749ff;
            --main-dark: #3c37fd;
            --sec-color: #211ddb;
            --thd-color: #4c3da3;
            --white-color: #000;
            --black-color: #fff;
            --gray-color: #979797;
            --gray2-color: #9a9a9a;
            --gray-week: #ababab50;
        }
        .dark {
            --white-color: #272e3b !important;
            --darkwhite-color: #323946;
            /* --sec-color: #f2f2f2;
            --big-color: #fff; */
        }
    </style>
</head>

<body>

    <header>
        <div class="container">
            <div class="desktop center">
                <a href="appointment.php" class="btn center" style="color: white">احجز الان</a>
                <ul>
                    <li><a href="index.php">الرئيسية</a></li>
                    <li><a href="about.php">معلومات عنا</a></li>
                    <li><a href="doctors.php">الاطباء</a></li>
                    <li><a href="services.php" class="active">الخدمات</a></li>
                    <li>
                        <button class="mode">
                            <i class="fa-solid fa-cloud-sun"></i>
                        </button>
                    </li>
                </ul>
                <div class="logo center">
                    <img src="imgs/logo.png" alt="">
                    <a href="index.php">
                        <h1>الـ<span>حـ</span>يـاة</h1>
                    </a>
                </div>
            </div>
            <div class="mobile center">
                <!-- list -->
                <div class="list">
                    <button><i class="fa-solid fa-bars-staggered"></i></button>
                    <ul class="center">
                        <li class="center"><a href="index.php">الرئيسية</a></li>
                        <li class="center"><a href="about.php">معلومات عنا</a></li>
                        <li class="center"><a href="doctors.php">الاطباء</a></li>
                        <li class="center"><a href="services.php" class="active">الخدمات</a></li>
                    </ul>
                </div>
                <!-- appoinment link -->
                <a href="appointment.php" class="btn center">احجز الان</a>
                <!-- logo -->
                <div class="logo center">
                    <img src="imgs/logo.png" alt="">
                    <a href="index.php">
                        <h1>الـ<span>حـ</span>يـاة</h1>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- home start -->
    <!-- <section class="home section center" id="home">
        <div class="container">
            <div class="home-content">
                <div class="imgs center">
                    <img src="imgs/doctors-home.png" alt="">
                </div>
                <div class="text center">
                    <p>دعنا نجعل حياتك اكثر سعادة</p>
                    <h1>حـيـاة صـحـيـة</h1>
                    <a href="doctors.php" class="btn center">تعرف علي اطبائنا</a>
                </div>
            </div>
        </div>
    </section> -->
    <!-- home end -->

    <!-- services start -->
    <section class="services center section" id="services">
        <h1 class="head-section">خدمات المستشفي</h1>
        <p>العميل مهم جدا بالنسية لنا وهذه بعض الخدمات التي نستطيع تقديمها</p>
        <div class="container">
            <div class="services-content grid">
                <!-- service -->
                <div class="service">
                    <i class="fa-solid fa-virus-covid"></i>
                    <h1>اختبار الفيروسات</h1>
                    <p>يتم إجراء تحليل الفيروسات للعثور على الفيروسات المسببة للعدوى.</p>
                </div>
                <!-- service -->
                <div class="service">
                    <i class="fa-solid fa-lungs"></i>
                    <h1>اختبار وظائف الرئة </h1>
                    <p>تقيس اختباراتُ وظائف الرئة قدرةَ الرئتين على حمل الهواء.</p>
                </div>
                <!-- service -->
                <div class="service">
                    <i class="fa-solid fa-heart-pulse"></i>
                    <h1>فحوصات القلب</h1>
                    <p>هو فحصٌ طبيٌّ للاطمئنان على صحة قلبك وسلامته.</p>
                </div>
                <!-- service -->
                <div class="service">
                    <i class="fa-solid fa-house-medical"></i>
                    <h1>تحليل الدم</h1>
                    <p>يُستخدم هذا الاختبار لفحص الحالة الصحية العامة واكتشاف الحالات المرَضية.</p>
                </div>
                <!-- service -->
                <div class="service">
                    <i class="fa-solid fa-bed-pulse"></i>
                    <h1>اختبار الحمي</h1>
                    <p>أخذ عينات من الأنف أو الحلق لاختبارها بحثًا عن عدوى في الجهاز التنفسي.</p>
                </div>
                <!-- service -->
                <div class="service">
                    <i class="fa-solid fa-flask-vial"></i>
                    <h1>اختبار ضغط الدم</h1>
                    <p>يقيس اختبار ضغط الدم الضغط في الشرايين عند ضخ القلب للدم.</p>
                </div>
            </div>
        </div>
    </section>
    <!-- services end -->

    <!-- footer start -->
    <footer class="section center">
        <div class="container">
            <div class="f-content grid">
                <div class="contact clm">
                    <h1 class="f-head" style="color: white">تواصل معنا</h1>
                    <div class="details center">
                        <i class="fa-solid fa-envelope gray-color"></i>
                        <p class="gray-color">ELHAYA@gmail.com</p>
                    </div>
                    <div class="details center">
                        <i class="fa-brands fa-whatsapp gray-color"></i>
                        <p class="gray-color">0150006000</p>
                    </div>
                    <div class="details center">
                        <i class="fa-solid fa-location-dot gray-color"></i>
                        <p class="gray-color">17-شارع الشريف المتفرع من شارع 15 خلف ماء الذهب</p>
                    </div>
                    <div class="logo center">
                        <a href="index.php">
                            <h1 style="color: white">الـ<span>حـ</span>يـاة</h1>
                        </a>
                        <img src="imgs/logo.png" alt="">
                    </div>
                </div>
                <div class="links clm">
                    <h1 class="f-head" style="color: white">روابط سريعة</h1>
                    <a href="about.php" class="gray-color">معلومات عنا</a>
                    <a href="services.php" class="gray-color">خدماتنا</a>
                    <a href="doctors.php" class="gray-color">الاطباء</a>
                    <a href="appointment.php" class="gray-color">احجز الان</a>
                </div>
                <div class="features clm">
                    <h1 class="f-head" style="color: white">خدماتنا</h1>
                    <p class="gray-color">اختبار الفيروسات</p>
                    <p class="gray-color">اختبار وظائف الرئة</p>
                    <p class="gray-color">فحوصات القلب</p>
                    <p class="gray-color">تحليل الدم</p>
                    <p class="gray-color">اختبار الحمي</p>
                    <p class="gray-color">اختبار ضغط الدم</p>
                </div>
                <div class="feedback clm">
                    <h1 class="f-head" style="color: white">تعليقك يهمنا</h1>
                    <form action="" method="post" class="clm">
                        <input type="text" name="name" placeholder="الاسم" required style="color: white">
                        <textarea name="msg" required style="resize: none;color:white" placeholder="رسالة"></textarea>
                        <button type="submit" name="feedback">ارسال</button>
                    </form>
                </div>
            </div>
        </div>
    </footer>
    <!-- footer end -->
    <?php
    if(isset($_POST['feedback'])){
        $sql=$database->prepare("INSERT INTO comments(name,message) VALUES(:name,:message)");
        $sql->bindParam("name",$_POST['name']);
        $sql->bindParam("message",$_POST['msg']);
        if($sql->execute()){
            echo "<script>alert('تم حفظ البيانات  بنجاح')</script>";
        }
    }
    ?>
    <div class="top center">
        <i class="fa-solid fa-angles-up"></i>
    </div>

    <script src="js/main.js"></script>
    <script src="js/dark.js"></script>
</body>

</html>