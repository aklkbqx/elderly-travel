<?php 
require_once("../../config.php");

if(isset($_REQUEST["addAssessment"])){
    $title = $_POST["title"];
    $body = $_POST["body"];
    $questions = $_POST["questions"];
    $additional = $_POST["additional"] ?? null;

    try{
        sql("INSERT INTO assessments(title,body,questions,additional) VALUES(?,?,?,?)",[
            $title,
            $body,
            json_encode($questions),
            $additional,
        ]);
        msg("success","สำเร็จ!","เพิ่มแบบประเมิน/สอบถาม สำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["editAssessment"])){
    $title = $_POST["title"];
    $body = $_POST["body"];
    $questions = $_POST["questions"];
    $additional = $_POST["additional"] ?? null;

    try{
       
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}
?>