<?php 
require_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <?php require_once("link.php"); ?>
</head>
<body>
    <a href="login.php" class='position-absolute text-teal text-decoration-none' style='top:10px;left:10px'><- กลับ</a>
    <div class='d-flex vh-100 justify-content-center align-items-center'>
        <form action="api/forgotpassword.php" method='post'>
            <h1 class='mb-2'>ลืมรหัสผ่านใช่หรือไม่</h1>
            <div class='form-floating'>
                <input type="text" class="form-control" name='email' placeholder='อีเมล'>
                <label for="อีเมล">อีเมล</label>
            </div>
            <button type='button' name='continute' class='btn btn-teal w-100 mt-2 py-3'>ดำเนินการต่อ</button>
        </form>
    </div>
</body>
</html>