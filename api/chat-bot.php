<?php 
require_once("../config.php");

if(isset($_GET["sendMessage"])) {
    $message = $_POST["message"];
    $fetchAll = sql("SELECT * FROM bot_responses");
    $found = false;

    while($res = $fetchAll->fetch()){
        $questions = json_decode($res["questions"]);
        $responses = json_decode($res["responses"]);
        
        if(in_array($message, $questions)) {
            $found = true;
            $messageArray = explode(",", $responses[0]);
            // $randomMessage = trim($messageArray[array_rand($messageArray)]);
            // echo $randomMessage;
            // break;
        }
    }
    
    if(!$found) {
        echo "ไม่พบข้อความที่ตรงกัน";
    }
}
?>