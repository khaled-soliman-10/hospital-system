<?php
require_once 'conn.php';
if(isset($_POST['save'])){
    $sql=$database->prepare("INSERT INTO admin(username,password,image,image_type) VALUES(:username,:password,:image,:image_type)");
    $user = 'admin';
    $pass = '123@admin';
    $img=file_get_contents($_FILES['im']['tmp_name']);
    $ftype=$_FILES['im']['type'];
    $sql->bindParam("username",$user);
    $sql->bindParam("password", $pass);
    $sql->bindParam("image",$img);
    $sql->bindParam("image_type",$ftype);
    $sql->execute();
    }
?>
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Document</title>
    </head>
    <body>
        <form method="POST" enctype="multipart/form-data">
            <input type="file" name='im'>
            <button type="submit" name="save">ok</button>
        </form>
    </body>
    </html>









