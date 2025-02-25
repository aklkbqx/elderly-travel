<?php
require_once("config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>แชทบอท</title>
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
    <div class='vh-100 d-flex flex-column justify-content-center align-items-center bg-light'>
        <div class='bg-white rounded-xl shadow d-flex flex-column' style='width:40%;height:80%'>
            <div class='p-2'>
                <div class='d-flex mb-2'>
                    <a href="javascript:window.history.back()" class='d-flex text-decoration-none text-dark align-items-center gap-2'>
                        <img src="<?= imagePath("web_images/icons", "chevron-back.svg") ?>" width='15px' height='15px' class='object-fit-cover'>
                        <div>กลับ</div>
                    </a>
                </div>
                <div class='d-flex align-items-center gap-2'>
                    <img src="<?= imagePath("user_images", "bot.png") ?>" width='50px' height='50px' class='rounded-circle border object-fit-cover'>
                    <div>แชทบอทช่วยเหลือ 24 ชม.</div>
                </div>
            </div>
            <div class='bg-light p-2 h-100 overflow-auto' id='chat-box'>

            </div>
            <form id='chat-form' class='d-flex align-items-center gap-2 p-2 mt-2'>
                <input type="text" class="form-control" name='message' placeholder='พิมพ์ข้อความสอบถาม...' required>
                <button class="btn btn-teal" type='submit'>ส่ง</button>
            </form>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            const chatBox = $("#chat-box");
            const chatForm = $("#chat-form");

            chatForm.on('submit', function(e) {
                e.preventDefault();
                const formData = new FormData($(this)[0]);

                $.ajax({
                    url: "api/chat-bot.php?sendMessage",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        chatBox.append(response);
                    }
                });
            });
        });
    </script>
</body>

</html>