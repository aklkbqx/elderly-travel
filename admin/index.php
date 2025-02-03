<?php 
if($_SERVER["REQUEST_URI"] === "/admin/"){
    header("location: /admin/?home");
    exit;
}

require "../config.php";


if($_SERVER["REQUEST_URI"] === "/admin/?manageUsers"){
    header("location: /admin/?manageUsers&user");
    exit;
}

$row=null;
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
];
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Travel</title>
    <?php require "../link.php"; ?>
</head>
<body class='bg-light'>

    <div class='position-fixed' style='bottom:2rem;right:2rem'>
        <div class='dropup'>
            <img src="" data-bs-toggle='dropdown' style='width:50px;height:50px' class='border p-0 rounded-circle object-fit-cover cursor-pointer'>
            <div class='dropdown-menu p-2' style='width:300px;height:400px;'>
                <h5 class='p-3'>ส่งข้อความ</h5>
                <div class='p-2'>
                    <?php 
                    function showUsers($role){
                        global $row;
                        $fetchUser = sql("SELECT * FROM users WHERE role = ?",[$role]);
                        if($fetchUser->rowCount() > 0){ $index=0; ?>
                            <ul style='list-style:none;' class='m-0 p-0 w-100 d-flex flex-column gap-1 mb-2'>
                            <?php while($user = $fetchUser->fetch()){
                                if($user["user_id"] === $row["user_id"]){
                                    return;
                                } 
                                $showTextRole = $role=='admin'?'• ผู้ดูและระบบ':'• ผู้ใช้งานทั่วไป';
                                echo $index == 0 ? "<div>$showTextRole</div>" : null ?>
                                <a href="<?= $_SERVER["REQUEST_URI"] === "/" ? "chats.php?receiver_id=".$user['user_id'] : "../chats.php?receiver_id=".$user['user_id'] ?>" class='btn btn-outline-teal border-0 p-1 w-100'>
                                    <li class='d-flex align-items-center gap-2'>
                                        <div class='position-relative'>
                                            <img src="<?= imagePath("user_images",$user["image"]) ?>" class='border rounded-circle object-fit-cover' style='width:50px;height:50px'/>
                                            <div style='width:15px;height:15px;bottom:0px;right:0px;' class='bg-<?= $user["active_status"]=="online"?"success":"danger" ?> position-absolute rounded-circle'></div>
                                        </div>
                                        <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                                    </li>
                                </a>
                                <?php $index++; } ?>
                            </ul>
                        <?php }
                    }
                    showUsers("admin");
                    showUsers("user");
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class='d-flex justify-content-between bg-white position-absolute w-100 shadow'>
        <h4 class='text-center p-3 text-white bg-teal d-flex align-items-center justify-content-center' style='width:300px'>ADMIN DASHBOARD</h4>
        <div class="dropdown mt-auto mx-2 p-2">
            <div class="d-flex flex-row gap-2 btn btn-outline-teal border-0 cursor-pointer dropdown-toggle align-items-center justify-content-center" data-bs-toggle="dropdown" style='border-radius: 2rem !important;'>
                <img src="<?= imagePath("user_images",$row["image"]) ?>" width="50px" height="50px" class="rounded-circle" />
                <div><?= $row["firstname"] ?> <?= $row["lastname"] ?></div>
            </div>
            <ul class="dropdown-menu">
                <a href="editprofile.php" class="dropdown-item mb-2">
                    <li class="d-flex align-items-center gap-2">
                        <img src="<?= imagePath("web_images/icons","people.png") ?>" width="35px" height="35px" class='object-fit-cover' />
                        <div>แก้ไขข้อมูลส่วน</div>
                    </li>
                </a>
                <div class="dropdown-divider"></div>
                <a href="?logout" class="dropdown-item text-danger">
                    <li class="d-flex align-items-center gap-2">
                        <img src="<?= imagePath("web_images/icons","logout.png") ?>" width="35px" height="35px" class='object-fit-cover' />
                        <div>ออกจากระบบ</div>
                    </li>
                </a>
            </ul>
        </div>
    </div>

    <div class='d-flex vh-100' style='padding-top:82px'>
        <div class="bg-white shadow d-flex flex-column pt-4" style="width:300px;">
            <ul class="m-0 p-0 d-flex flex-column gap-3 align-items-center w-100 px-2" style='list-style:none'>
                <?php foreach($pages as $index=>$page){?>
                    <a href="?<?= $index ?>" class='btn w-100 <?= isset($_GET[$index]) ? "btn-teal" : "btn-outline-light2" ?>'>
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
                <div class='bg-white shadow p-4 rounded-xl overflow-hidden' style='height: 796.56px;'>
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