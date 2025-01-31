<?php 
require_once("config.php");

if(isset($_SESSION["user_login"])){
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","/");
}elseif(isset($_SESSION["admin_login"])){
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","admin/");
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
    <a href="/" class='position-absolute text-white text-decoration-none d-flex flex-row gap-2 align-items-center' style='top:10px;left:10px'>
        <img src="<?= imagePath("web_images/icons","chevron-back.png") ?>" alt="" width='35px' height='35px' style='filter:invert(1)'>
        กลับ
    </a>

    <div class="vh-100 d-flex flex-column flex-lg-row align-items-lg-center justify-content-between">
        <div class="h-100 bg-teal w-100 flex-1 d-flex align-items-center justify-content-center" style="border-radius:0rem 10rem 10rem 0rem">
            <div class='d-flex justify-contnt-center align-items-center flex-column text-white'>
                <h1 style='font-size:50px' class='fw-bold'>ยินดีต้อนรับ</h1>
                <p style='font-size:20px'>คุณยังไม่มีบัญชีใช่หรือไม่</p>
                <a href="register.php" style='font-size:30px' class='btn btn-outline-light'>สมัครสมาชิก</a>
            </div>
        </div>
        <div class='flex-1 d-flex justify-content-center p-4'>
            <form action="api/auth.php" method='post' class='m-0' style='width:400px;'>
                <h1 class='text-center mb-2'>เข้าสู่ระบบ</h1>
                <div class='d-flex flex-column gap-2 mb-2'>
                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder='อีเมล' name='email'>
                        <label for="อีเมล">อีเมล</label>
                    </div>
                    <div class="form-floating position-relative">
                        <input type="password" class="form-control" placeholder='รหัสผ่าน' name='password' id='password'>
                        <label for="รหัสผ่าน">รหัสผ่าน</label>
                        <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons","eye.png"); ?>" style='width:40px;height:40px;top:10px;right:10px' class='object-fit-cover position-absolute cursor-pointer'>
                    </div>
                </div>
                <div class="d-flex align-items-center justify-content-end mb-2">
                    <a href="forgotpassword.php" class='text-teal' style='font-size:20px'>ลืมรหัสผ่าน</a>
                </div>
                <button type='submit' name='login' class="btn btn-teal w-100" style='font-size:30px'>
                    เข้าสู่ระบบ
                </button>
            </form>
        </div>
    </div>
    <script>
        const showPassword = $("#showPassword");
        showPassword.on("click",()=>{
            const inputPassword = $("#password");
            if(inputPassword.attr("type") == "password"){
                showPassword.attr("src","<?= imagePath("web_images/icons","eye-slash.png"); ?>");
                inputPassword.attr("type","text")
            }else{
                showPassword.attr("src","<?= imagePath("web_images/icons","eye.png"); ?>");
                inputPassword.attr("type","password")
            }
        })
    </script>
</body>
</html>