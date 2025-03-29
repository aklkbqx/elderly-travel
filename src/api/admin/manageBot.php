<?php 
require_once('../../config.php');

if(isset($_REQUEST["add"])){
    $questions = json_encode(explode(".",$_POST["questions"]));
    $responses = json_encode(explode(".",$_POST["responses"]));
    sql("INSERT INTO bot_responses(questions,responses) VALUES(?,?)",[
        $questions,
        $responses
    ]);
    msg("success","สำเร็จ!","เพิ่มคำถามและคำตอบสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
}

if(isset($_REQUEST["edit"]) && isset($_GET["response_id"])){
    $questions = json_encode(explode(".",$_POST["questions"]));
    $responses = json_encode(explode(".",$_POST["responses"]));
    $response_id = $_GET["response_id"];
    sql("UPDATE bot_responses SET questions = ?,responses = ? WHERE response_id = ?",[
        $questions,
        $responses,
        $response_id
    ]);
    msg("success","สำเร็จ!","เพิ่มคำถามและคำตอบสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
}

if(isset($_GET["delete"]) && isset($_GET["response_id"])){
    $response_id = $_GET["response_id"];
    sql("DELETE FROM bot_responses WHERE response_id = ?",[$response_id]);
    msg("success","สำเร็จ!","ทำการลบคำถามและการตอบกลับสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
}
?>