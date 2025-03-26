<?php 
require_once('../../config.php');

if(isset($_REQUEST["addQue-Res"])){
    $questions = json_encode(explode(".",$_POST["questions"]));
    $responses = json_encode(explode(".",$_POST["responses"]));
    sql("INSERT INTO bot_responses(questions,responses) VALUES(?,?)",[
        $questions,
        $responses
    ]);
    msg("success","สำเร็จ!","เพิ่มคำถามและคำตอบสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
}

if(isset($_GET["deleteBotResponse"]) && isset($_GET["response_id"])){
    $response_id = $_GET["response_id"];
    sql("DELETE FROM bot_responses WHERE response_id = ?",[$response_id]);
    msg("success","สำเร็จ!","ทำการลบคำถามและการตอบกลับสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
}
?>