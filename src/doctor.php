<?php
require_once("config.php");
require_once("components/components.php");
$row = null;
if (isset($_SESSION["user_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "/");
} elseif (isset($_SESSION["admin_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "admin/");
} elseif (isset($_SESSION["doctor_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["doctor_login"]])->fetch();
} else {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "/");
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Doctor</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>
    <div class="d-flex vh-100 justify-content-center align-items-center flex-column">
        <h2>ยินดีต้อนรับสู่หน้าของแพทย์/หมอ</h2>
        <div>
            <img src="<?= imagePath("user_images", $row["image"]) ?>" width="50px" height="50px" class="rounded-circle border object-fit-cover">
            <div><?= $row["firstname"] ?> <?= $row["lastname"] ?></div>
        </div>
        <a href="?logout" class="text-danger">ออกจากระบบ</a>
    </div>
</body>

</html>