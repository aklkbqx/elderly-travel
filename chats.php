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
            <div class='bg-white rounded-xl border shadow w-100 h-100 '>
                <div class='d-flex flex-wrap h-100 row'>
                    <div class='p-4 h-100 col-lg-4'>
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
                                            continue;
                                        } 
                                        $showTextRole = $role=='admin'?'• ผู้ดูและระบบ':'• ผู้ใช้งานทั่วไป';
                                        echo $index == 0 ? "<div>$showTextRole</div>" : null ?>
                                        <a href="<?= $_SERVER["REQUEST_URI"] === "/" ? "chats.php?receiver_id=".$user['user_id'] : "../chats.php?receiver_id=".$user['user_id'] ?>" class='btn btn-outline-teal border-0 p-1 w-100 <?= $_GET["receiver_id"] == $user['user_id'] ? "bg-teal text-white" : "" ?>'>
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
                    <div class='col-lg-8 flex-1 d-flex flex-column'>
                        <?php 
                        if(isset($_GET["receiver_id"])){
                            $user = sql("SELECT * FROM users WHERE user_id = ?",[$_GET["receiver_id"]])->fetch(); ?>
                            <div class='d-flex flex-column justify-content-between'>

                                <div class='position-relative'>
                                    <div class='d-flex align-items-center gap-2 shadow p-2'>
                                        <div class='position-relative'>
                                            <img src="<?= imagePath("user_images",$user["image"]) ?>" class='border rounded-circle object-fit-cover' style='width:50px;height:50px'/>
                                            <div style='width:15px;height:15px;bottom:0px;right:0px;' class='bg-<?= $user["active_status"]=="online"?"success":"danger" ?> position-absolute rounded-circle'></div>
                                        </div>
                                        <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                                    </div>
                                    <div class='bg-light p-2 d-flex flex-column gap-2 overflow-auto' id='message-box' style='height:600px;'></div>
                                    <div id='srollToDown' class='position-absolute bg-white cursor-pointer rounded-circle border d-flex justify-content-center align-items-center shadow' style='bottom:0;right:50%;width:50px;height:50px;'>
                                        <img src="<?= imagePath('web_images/icons','chevron-down.png') ?>" width='25px' height='25px' class='object-fit-cover'>
                                    </div>
                                </div>

                                <form method='post' id='form-chat' class='d-flex flex-row align-items-center gap-2 p-2'>
                                    <input id='chooseImage' type="file" name='image' onchange="$('#imagePreview').attr('src',window.URL.createObjectURL(this.files[0]));$('#showImagePreview').removeClass('d-none').addClass('d-flex');" hidden>
                                    <label for="chooseImage" class='btn btn-outline-light p-0'>
                                        <img src="<?= imagePath("web_images/icons","image.png") ?>" width='40px' height='40px' class='rounded-circle object-fit-cover'>
                                    </label>
                                    <input name="message" type="text" class='form-control h-100' placeholder='เขียนข้อความ...'>
                                    <button type='submit' class='btn btn-outline-teal p-2'>
                                        <img src="<?= imagePath("web_images/icons","send.png") ?>" width='25px' height='25px' class='object-fit-cover'>
                                    </button>
                                </form>
                                <div id='showImagePreview' class='my-4 d-none justify-content-center align-items-center'>
                                    <div style='width:300px;height:200px;' class='position-relative'>
                                        <button onclick='$("#chooseImage").val(null);$("#showImagePreview").removeClass("d-flex").addClass("d-none")' class='btn-close position-absolute rounded-circle border bg-danger p-2' type='button' style='top:10px;right:10px'></button>
                                        <img id="imagePreview" src="" width='300px' height='200px' class='object-fit-cover rounded-xl border'>
                                    </div>
                                </div>
                            </div>
                        <?php }else{ ?>
                            <div class='d-flex align-items-center justify-content-center h-100 w-100 bg-light'>
                                <h6>เริ่มสนทนา...</h6>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="libs/chats.js"></script>
</body>
</html>