<?php
require_once("config.php");

if (isset($_SESSION["user_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "/");
} elseif (isset($_SESSION["admin_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "admin/");
} elseif (isset($_SESSION["doctor_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "doctor.php");
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php require_once("./components/options.php"); ?>

    <a href="/" class='position-absolute text-white text-decoration-none d-none d-lg-flex flex-row gap-2 align-items-center' style='top:10px;left:10px'>
        <img src="<?= imagePath("web_images/icons", "chevron-back.svg") ?>" alt="" width='15px' height='15px' style='filter:invert(1)'>
        กลับ
    </a>

    <a href="/" class='position-absolute backpage-button d-flex d-lg-none' style='top:10px;left:10px'>
        <img src="<?= imagePath("web_images/icons", "chevron-back.svg") ?>" alt="" width='15px' height='15px'>
        กลับ
    </a>

    <div class="vh-100 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
        <div class="h-100 bg-teal w-100 flex-1 d-lg-flex align-items-center justify-content-center d-none" style="border-radius:0rem 5rem 5rem 0rem">
            <div class='d-flex justify-contnt-center align-items-center flex-column text-white'>
                <h1 style='font-size:50px' class='fw-bold'>ยินดีต้อนรับ</h1>
                <p style='font-size:20px'>คุณยังไม่มีบัญชีใช่หรือไม่</p>
                <a href="register.php" style='font-size:30px' class='btn btn-outline-light'>สมัครสมาชิก</a>
            </div>
        </div>
        <div class='flex-1 d-flex justify-content-center p-4 align-items-center align-item-lg-start'>
            <form action="api/auth.php" method='post' class='m-0' style='width:400px;'>
                <h1 class='text-center mb-2'>เข้าสู่ระบบ</h1>
                <div class='d-flex flex-column gap-2 mb-2'>
                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder='อีเมล' name='email' required>
                        <label for="อีเมล">อีเมล</label>
                    </div>
                    <div class="form-floating position-relative">
                        <input type="password" class="form-control" placeholder='รหัสผ่าน' name='password' id='password' required>
                        <label for="รหัสผ่าน">รหัสผ่าน</label>
                        <img class='object-fit-cover position-absolute cursor-pointer svg-icon' onclick='openPassword($(this))' src="<?= imagePath("web_images/icons", "eye.svg"); ?>" style='width:30px;height:30px;top:15px;right:10px'>
                    </div>
                </div>
                <!-- <div class="d-flex align-items-center justify-content-end mb-2">
                    <a href="forgotpassword.php" class='text-teal' style='font-size:20px'>ลืมรหัสผ่าน</a>
                </div> -->
                <button type='submit' name='login' class="btn btn-teal w-100" style='font-size:30px'>
                    เข้าสู่ระบบ
                </button>

                <div class='mt-4 d-flex d-lg-none align-items-center gap-2 justify-content-center'>
                    <p style='font-size:20px' class="m-0 p-0">คุณยังไม่มีบัญชีใช่หรือไม่</p>
                    <a href="register.php" class='btn btn-teal'>สมัครสมาชิก</a>
                </div>
            </form>
        </div>
    </div>
</body>

</html>