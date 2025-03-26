<?php
require_once("config.php");

if (isset($_SESSION["user_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "/");
} elseif (isset($_SESSION["admin_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "admin/");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php
    require_once("./components/options.php");
    ?>

    <a href="/" class='position-absolute backpage-button' style='top:10px;left:10px'>
        <img src="<?= imagePath("web_images/icons", "chevron-back.svg") ?>" alt="" width='15px' height='15px'>
        กลับ
    </a>

    <div class="vh-100 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
        <div class='flex-1 d-flex justify-content-center p-4 align-items-center align-items-lg-start'>

            <form action="api/auth.php" method='post' class='m-0' style='width:400px;'>
                <h1 class='text-center mb-2'>สมัครสมาชิก</h1>
                <div class='d-flex flex-column gap-2 mb-2'>
                    <div class='flex-1 d-flex flex-row gap-2'>
                        <div class="form-floating flex-1">
                            <input type="text" class="form-control" placeholder='ชื่อ' name='firstname' required>
                            <label for="ชื่อ">ชื่อ</label>
                        </div>
                        <div class="form-floating flex-1">
                            <input type="text" class="form-control" placeholder='นามสกุล' name='lastname' required>
                            <label for="นามสกุล">นามสกุล</label>
                        </div>
                    </div>
                    <div class="form-floating">
                        <input type="email" class="form-control" placeholder='อีเมล' name='email' required>
                        <label for="อีเมล">อีเมล</label>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" placeholder='รหัสผ่าน' name='password' required>
                        <label for="รหัสผ่าน">รหัสผ่าน</label>
                        <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons", "eye.svg"); ?>" style='width:30px;height:30px;top:15px;right:10px' class='object-fit-cover position-absolute cursor-pointer svg-icon'>
                    </div>
                    <div class="form-floating">
                        <input type="password" class="form-control" placeholder='ยืนยันรหัสผ่าน' name='c_password' required>
                        <label for="ยืนยันรหัสผ่าน">ยืนยันรหัสผ่าน</label>
                        <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons", "eye.svg"); ?>" style='width:30px;height:30px;top:15px;right:10px' class='object-fit-cover position-absolute cursor-pointer svg-icon'>
                    </div>
                </div>
                <button type='submit' name='register' class="btn btn-teal w-100" style='font-size:30px'>
                    สมัครสมาชิก
                </button>
                <div class='mt-4 d-flex d-lg-none align-items-center gap-2 justify-content-center'>
                    <p style='font-size:20px' class="m-0 p-0">คุณมีบัญชีแล้วใช่หรือไม่</p>
                    <a href="login.php" class='btn btn-teal'>เข้าสู่ระบบ</a>
                </div>
            </form>
        </div>

        <div class="h-100 bg-teal w-100 flex-1 align-items-center justify-content-center d-none d-lg-flex" style="border-radius:5rem 0rem 0rem 5rem">
            <div class='d-flex justify-contnt-center align-items-center flex-column text-white'>
                <h1 style='font-size:50px' class='fw-bold'>ยินดีต้อนรับ</h1>
                <p style='font-size:20px'>คุณมีบัญชีแล้วใช่หรือไม่</p>
                <a href="login.php" style='font-size:30px' class='btn btn-outline-light'>เข้าสู่ระบบ</a>
            </div>
        </div>
    </div>
</body>

</html>