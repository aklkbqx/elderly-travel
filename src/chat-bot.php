<?php
require_once("config.php");
$row = null;
if (isset($_SESSION["user_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
} elseif (isset($_SESSION["admin_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["admin_login"]])->fetch();
} elseif (isset($_SESSION["doctor_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["doctor_login"]])->fetch();
} 
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
    <div class='d-flex justify-content-center container' style="margin-top: <?= isset($_SESSION["admin_login"]) || isset($_SESSION["doctor_login"]) ? "2rem" : "8rem" ?>;">
        <div class='rounded-xl shadow d-flex flex-column border w-100 mb-4'>
            <div class='p-2'>
                <div class='d-flex mb-2'>
                    <?php backPage("javascript:window.history.back()"); ?>
                </div>
                <div class='d-flex align-items-center gap-2'>
                    <img src="<?= imagePath("user_images", "bot.png") ?>" width='50px' height='50px' class='rounded-circle border object-fit-cover'>
                    <div>แชทบอทช่วยเหลือ 24 ชม.</div>
                </div>
            </div>
            <div class='p-2 overflow-auto' id='chat-box' style="height: 650px;">
                <div class="w-100 h-100 d-flex align-items-center justify-content-center" id="getStarted">เริ่มสนทนากับแชทบอทเลย...</div>
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
                    url: "api/chat-bot?sendMessage",
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if($("#getStarted")){
                            $("#getStarted").remove();
                        }
                        $("[name='message']").val(null);
                        chatBox.append(response);
                        chatBox.scrollTop(chatBox.prop("scrollHeight"));
                    }
                });
            });
        });
    </script>
</body>

</html>