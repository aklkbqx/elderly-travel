<?php 
require_once("../config.php");

$html = "";

if(isset($_GET["sendMessage"])) {
    $message = $_POST["message"];
    $fetchAll = sql("SELECT * FROM bot_responses");
    $found = false;

    $html.='
    <div class="d-flex mb-2 justify-content-end">
        <div class="bg-white d-flex rounded-xl shadow p-2 mb-2 justify-content-start">'. $message .'</div>
    </div>
    ';

    while($res = $fetchAll->fetch()){
        $questions = json_decode($res["questions"]);
        $responses = json_decode($res["responses"]);

        if(in_array($message, $questions)) {
            $found = true;
            $messageArray = explode(",", $responses[0]);
            $randomMessage = trim($messageArray[array_rand($messageArray)]);
            $html.='
            <div class="d-flex mb-2 justify-content-start">
                <div class="bg-white d-flex rounded-xl shadow p-2 mb-2 justify-content-start">'. $randomMessage .'</div>
            </div>';
            break;
        }
    }
    
    if(!$found) {
        $html.='
        <div class="d-flex mb-2 justify-content-start">
            <div class="bg-white d-flex rounded-xl shadow p-2 mb-2 justify-content-start">ขออภัยครับ ผมไม่เข้าใจคำถาม...</div>
        </div>';
    }
}

echo $html;
?>