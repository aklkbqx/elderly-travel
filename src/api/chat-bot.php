<?php
require_once("../config.php");

$html = "";

if (isset($_GET["sendMessage"])) {
    $message = $_POST["message"];
    $fetchAll = sql("SELECT * FROM bot_responses");
    $found = false;

    $html .= '<div class="d-flex mb-2 justify-content-end">
        <div class="d-flex rounded-xl shadow p-2 mb-2 justify-content-start border">' . $message . '</div>
    </div>';

    while ($res = $fetchAll->fetch()) {
        $questions = explode(",", json_decode($res["questions"], true)[0]);
        $responses = json_decode($res["responses"], true);

        if (in_array($message, $questions)) {
            $found = true;
            $messageArray = explode(",", $responses[0]);
            $randomMessage = trim($messageArray[array_rand($messageArray)]);
            $html .= '<div class="d-flex mb-2 justify-content-start align-items-start gap-2">
                <img src="'.imagePath("user_images", "bot.png").'" width="35px" height="35px" class="rounded-circle border object-fit-cover" />
                <div class="d-flex rounded-xl shadow p-2 mb-2 justify-content-start border">' . $randomMessage . '</div>
            </div>';
            break;
        }
    }

    if (!$found) {
        $html .= '<div class="d-flex mb-2 justify-content-start align-items-start gap-2">
            <img src="'.imagePath("user_images", "bot.png").'" width="35px" height="35px" class="rounded-circle border object-fit-cover" />
            <div class="d-flex rounded-xl shadow p-2 mb-2 justify-content-start border">ขออภัยครับ ผมไม่เข้าใจคำถาม...</div>
        </div>';
    }
}

echo $html;
