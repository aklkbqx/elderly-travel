<?php
require_once("config.php");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404</title>
    <?php
    require_once("link.php");
    ?>
</head>

<body>
    <div class="vh-100 d-flex align-items-center justify-content-center">
        <div class='d-flex flex-column align-items-center gap-2'>
            <div class='text-center'>
                <h2 class=''>404 NOT FOUND</h2>
                <div>เสียใจด้วยครับ เกิดข้อผิดพลาดบางอย่าง....</div>
            </div>
            <img src="<?= imagePath("web_images", "not_found.png") ?>" width='100px' height='100px' class='object-fit-contian'>
            <?php
            backPage("/");
            ?>
        </div>
    </div>
</body>

</html>