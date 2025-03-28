<?php
require_once("config.php");

$row = null;
if (isset($_SESSION["user_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
} elseif (isset($_SESSION["admin_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["admin_login"]])->fetch();
} elseif (isset($_SESSION["doctor_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["doctor_login"]])->fetch();
} else {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "404.php");
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

<body>
    <?php
    if (!isset($_SESSION["admin_login"]) && !isset($_SESSION["doctor_login"])) {
        require_once("components/nav.php");
    }
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class='d-flex justify-content-center container' style="margin-top: <?= isset($_SESSION["admin_login"]) || isset($_SESSION["doctor_login"]) ? "2rem" : "8rem" ?>;">
        <div class='rounded-xl shadow d-flex flex-column border w-100 mb-4'>
            <div class="p-2">
                <?php backPage("/") ?>
            </div>
            <div class='row'>
                <div class="col-lg-4">
                    <h5 class='text-center'>ส่งข้อความ</h5>
                    <div class='mt-2 w-100 overflow-auto' style="max-height: 650px;">
                        <?php function showUsersChat($role)
                        {
                            global $row;
                            $fetchUser = sql("SELECT * FROM users WHERE `role` = ?", [$role]);
                            if ($fetchUser->rowCount() > 0) {
                                $index = 0; ?>
                                <ul style='list-style:none;' class='m-0 mb-2 px-0 ps-4 pe-4 pe-lg-0'>
                                    <?php while ($user = $fetchUser->fetch()) {
                                        if ($user["user_id"] === $row["user_id"]) {
                                            continue;
                                        }
                                        $showTextRole = $role == 'admin' ? '• ผู้ดูและระบบ' : ($role == "doctor" ? "• แพทย์/หมอ" : "• ผู้ใช้งานทั่วไป");
                                        echo $index == 0 ? "<div>$showTextRole</div>" : null ?>
                                        <a href="<?= $_SERVER["REQUEST_URI"] === "/" ? "chats.php?receiver_id=" . $user['user_id'] : "../chats.php?receiver_id=" . $user['user_id'] ?>" class='btn btn-outline-teal border-0 p-1 w-100 <?= $_GET["receiver_id"] == $user['user_id'] ? "bg-teal text-white" : "" ?>'>
                                            <li class='d-flex align-items-center gap-2'>
                                                <div class='position-relative'>
                                                    <img src="<?= imagePath("user_images", $user["image"]) ?>" class='border rounded-circle object-fit-cover' style='width:50px;height:50px' />
                                                    <div style='width:15px;height:15px;bottom:0px;right:0px;' class='bg-<?= $user["active_status"] == "online" ? "success" : "danger" ?> position-absolute rounded-circle'></div>
                                                </div>
                                                <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                                            </li>
                                        </a>
                                    <?php $index++;
                                    } ?>
                                </ul>
                        <?php }
                        }
                        showUsersChat("admin");
                        showUsersChat("user");
                        showUsersChat("doctor");
                        ?>
                    </div>
                </div>
                <div class='col-lg-8 flex-1 d-flex flex-column'>
                    <?php
                    if (isset($_GET["receiver_id"])) {
                        $user = sql("SELECT * FROM users WHERE user_id = ?", [$_GET["receiver_id"]])->fetch(); ?>
                        <div class='d-flex flex-column justify-content-between'>

                            <div class='position-relative'>
                                <div class='d-flex align-items-center gap-2 shadow p-2'>
                                    <div class='position-relative'>
                                        <img src="<?= imagePath("user_images", $user["image"]) ?>" class='border rounded-circle object-fit-cover' style='width:50px;height:50px' />
                                        <div style='width:15px;height:15px;bottom:0px;right:0px;' class='bg-<?= $user["active_status"] == "online" ? "success" : "danger" ?> position-absolute rounded-circle'></div>
                                    </div>
                                    <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                                </div>
                                <div class='p-2 d-flex flex-column gap-2 overflow-auto' id='message-box' style='height:600px;'></div>
                                <div id='srollToDown' class='position-absolute bg-white cursor-pointer rounded-circle border d-flex justify-content-center align-items-center shadow' style='bottom:0;right:50%;width:50px;height:50px;'>
                                    <img src="<?= imagePath('web_images/icons', 'chevron-down.png') ?>" width='25px' height='25px' class='object-fit-cover'>
                                </div>
                            </div>

                            <form method='post' id='form-chat' class='d-flex flex-row align-items-center gap-2 p-2'>
                                <input id='chooseImage' type="file" accept="image/*" name='image' onchange="$('#imagePreview').attr('src',window.URL.createObjectURL(this.files[0]));$('#showImagePreview').removeClass('d-none').addClass('d-flex');" hidden>
                                <label for="chooseImage" class='btn btn-outline-light p-0'>
                                    <img src="<?= imagePath("web_images/icons", "image.svg") ?>" width='40px' height='40px' class='rounded-circle object-fit-cover'>
                                </label>
                                <input name="message" type="text" class='form-control h-100' placeholder='เขียนข้อความ...'>
                                <button type='submit' class='btn btn-outline-teal p-2'>
                                    <img src="<?= imagePath("web_images/icons", "send.svg") ?>" width='25px' height='25px' class='object-fit-cover svg-icon'>
                                </button>
                            </form>
                            <div id='showImagePreview' class='my-4 d-none justify-content-center align-items-center'>
                                <div style='width:300px;height:200px;' class='position-relative'>
                                    <button onclick='$("#chooseImage").val(null);$("#showImagePreview").removeClass("d-flex").addClass("d-none")' class='btn-close position-absolute rounded-circle border bg-danger p-2' type='button' style='top:10px;right:10px'></button>
                                    <img id="imagePreview" src="" width='300px' height='200px' class='object-fit-cover rounded-xl border'>
                                </div>
                            </div>
                        </div>
                    <?php } else { ?>
                        <div class='d-flex align-items-center justify-content-center h-100 w-100 bg-light'>
                            <h6>เริ่มสนทนา...</h6>
                        </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>

    <script src="libs/chats.js"></script>
</body>

</html>