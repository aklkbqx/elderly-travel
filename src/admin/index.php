<?php 
if($_SERVER["REQUEST_URI"] === "/admin/"){
    header("location: /admin/?home");
    exit;
}

require_once("../config.php");
require_once("../components/components.php");


if($_SERVER["REQUEST_URI"] === "/admin/?manageUsers"){
    header("location: /admin/?manageUsers&user");
    exit;
}

if($_SERVER["REQUEST_URI"] === "/admin/?manageBookings"){
    header("location: /admin/?manageBookings&status=PENDING");
    exit;
}

$row = null;
if(isset($_SESSION["user_login"])){
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","/");
}elseif(isset($_SESSION["admin_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["admin_login"]])->fetch();
}else{
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","/");
}

$pages = [
    "home"=>"🏡 หน้าแรก",
    "manageUsers"=>"👤 จัดการสมาชิก",
    "manageNews"=>"📰 จัดการข่าวประชาสัมพันธ์",
    "manageAssessments"=>"📝 จัดการแบบสอบถาม/ประเมิน",
    "managePosts"=>"📃 จัดการกระทู้",
    "manageBot"=>"🤖 จัดการบอท/สอนบอท",
    "managePlaces"=>"🌲 จัดการสถานที่",
    "manageBookings"=>"📜 จัดการรายการจอง",
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ADMIN</title>
    <?php require_once("../link.php"); ?>
</head>

<body>
    <?php
    require_once("../components/options.php");
    require_once("../components/popChats.php");
    ?>

    <div class='d-flex justify-content-between position-absolute w-100 shadow'>
        <h4 class='text-center p-3 text-white bg-teal d-flex align-items-center justify-content-center' style='width:300px'>ADMIN DASHBOARD</h4>
        <div class="dropdown mt-auto mx-2 p-2">
            <div class="d-flex flex-row gap-2 btn btn-outline-teal border-0 cursor-pointer dropdown-toggle align-items-center justify-content-center" data-bs-toggle="dropdown" style='border-radius: 2rem !important;'>
                <img src="<?= imagePath("user_images",$row["image"]) ?>" width="50px" height="50px" class="rounded-circle object-fit-cover" />
                <div><?= $row["firstname"] ?> <?= $row["lastname"] ?></div>
            </div>
            <ul class="dropdown-menu">
                <a href="editprofile.php" class="dropdown-item mb-2">
                    <li class="d-flex align-items-center gap-2">
                        <img src="<?= imagePath("web_images/icons","people.svg") ?>" width="35px" height="35px" class='object-fit-cover' />
                        <div>แก้ไขข้อมูลส่วนตัว</div>
                    </li>
                </a>
                <div class="dropdown-divider"></div>
                <a href="?logout" class="dropdown-item text-danger">
                    <li class="d-flex align-items-center gap-2">
                        <img src="<?= imagePath("web_images/icons","logout.svg") ?>" width="35px" height="35px" class='object-fit-cover' />
                        <div>ออกจากระบบ</div>
                    </li>
                </a>
            </ul>
        </div>
    </div>

    <div class='d-flex vh-100' style='padding-top:82px'>
        <div class=" shadow d-flex flex-column pt-4 overflow-auto pb-2" style="width:300px;">
            <ul class="m-0 p-0 d-flex flex-column gap-3 align-items-center w-100 px-2 text-start" style='list-style:none'>
                <?php foreach($pages as $index=>$page){?>
                    <a href="?<?= $index ?>" class='btn w-100 <?= isset($_GET[$index]) ? "btn-teal text-start fw-bold" : " text-start fw-light" ?>' style='font-size:18px'>
                        <li><?= $page; ?></li>
                    </a>
                <?php } ?>
            </ul>
        </div>
        <div class='flex-1 p-4'>
            <div class='d-flex flex-column h-100'>
                <h1 class='mb-2'>
                    <?php foreach($pages as $index=>$page){
                        if(isset($_GET[$index])){ 
                            echo $page;
                        } ?>
                    <?php } ?>
                </h1>
                <div class=' shadow p-4 rounded-xl overflow-hidden' style='height: 796.56px;'>
                    <?php foreach($pages as $index=>$page){
                        if(isset($_GET[$index])){
                            require_once("pages/$index.php");
                        } ?>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</body>
</html>