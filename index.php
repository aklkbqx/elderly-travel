<?php 
require "config.php";

if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    msg("คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้",$_SERVER["HTTP_REFERER"]);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Travel</title>
    <?php require "link.php"; ?>
</head>
<body>
    <?php 
    require "components/nav.php"; 
    require_once("components/banner.php");
    ?>    

    <div class="bg-white vh-100">
        <div class="container">
            <div class="row bg-teal p-5 rounded-xl">
                asd
            </div>
        </div>
    </div>

</body>
</html>