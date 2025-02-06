<?php 
require_once("config.php");

if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","admin/");
}else{
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","/");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <?php require_once("link.php"); ?>
</head>
<body>
    <?php require "components/nav.php"; ?>
    <div style='margin-top:10rem' class='container'>

        <div class='mb-2'>
            <a href="javascript:window.history.back()" class='text-dark text-decoration-none d-flex flex-row gap-2 align-items-center'>
                <img src="<?= imagePath("web_images/icons","chevron-back.png") ?>" alt="" width='35px' height='35px'>
                กลับ
            </a>
        </div>
        
        <div class='container bg-white shadow p-4 rounded-xl'>
            <form method='post' action='api/user.php' class='row' enctype='multipart/form-data'>
                <?php require_once("components/editprofile.php"); ?>
            </form>
        </div>
    </div>
</body>
</html>