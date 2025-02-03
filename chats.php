<?php 
require_once("config.php");

$row=null;
if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["admin_login"]])->fetch();
}else{
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","/");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chats</title>
    <?php require_once("link.php"); ?>
</head>
<body class='bg-light'>
    <div class='vh-100 d-flex flex-column justify-content-center align-items-center position-relative'>
        <div class='d-flex flex-column gap-2' style='width:80%;height:80%'>
            <div class='d-flex align-items-center justify-content-start gap-4'>
                <a href="javascript:window.history.back()" class='d-flex flex-row gap-2 align-items-center text-decoration-none text-teal'>
                    <img src="<?= imagePath("web_images/icons","chevron-back.png"); ?>" alt="" class='object-fit-cover' style='width:15px;height:15px;'>
                    <div>กลับ</div>
                </a>
            </div>
            <div class='bg-white rounded-xl border shadow w-100 h-100'>
                <div class='row h-100'>
                    <div class='col-lg-4 p-4 h-100'>
                        <h5 class='text-center'>ส่งข้อความ</h5>
                        <div class='mt-2'>
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
                                        <a href="<?= $_SERVER["REQUEST_URI"] === "/" ? "chats.php?receiver_id=".$user['user_id'] : "../chats.php?receiver_id=".$user['user_id'] ?>" class='btn btn-outline-teal border-0 p-1 w-100 <?= $_GET["receiver_id"] == $user['user_id'] ? "bg-teal text-white" : "" ?>'>
                                            <li class='d-flex align-items-center gap-2'>
                                                <div class='position-relative'>
                                                    <img src="<?= imagePath("user_images",$user["image"]) ?>" class='border rounded-circle' style='width:50px;height:50px'/>
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
                    <div class='col-lg-8 flex-1'>
                        <?php 
                        if(isset($_GET["receiver_id"])){
                            $user = sql("SELECT * FROM users WHERE user_id = ?",[$_GET["receiver_id"]])->fetch(); ?>
                            <div class='d-flex align-items-center gap-2 shadow p-2'>
                                <div class='position-relative'>
                                    <img src="<?= imagePath("user_images",$user["image"]) ?>" class='border rounded-circle' style='width:50px;height:50px'/>
                                    <div style='width:15px;height:15px;bottom:0px;right:0px;' class='bg-<?= $user["active_status"]=="online"?"success":"danger" ?> position-absolute rounded-circle'></div>
                                </div>
                                <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                            </div>

                            <div class='bg-light flex-1'>
                                asd
                            </div>

                        <?php }else{

                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>