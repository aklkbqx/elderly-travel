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

if(isset($_REQUEST["editAssessment"]) && isset($_GET["assessment_id"])){
    $assessment_id = $_GET["assessment_id"];
    $assessment = sql("SELECT * FROM assessments WHERE assessment_id = ?",[$assessment_id])->fetch();
    $title = $_POST["title"] ?? $assessment["title"];
    $body = $_POST["body"] ?? $assessment["body"];
    $questions = $_POST["questions"] ?? $assessment["questions"];
    $additional = $_POST["additional"] ?? $assessment["additional"];

    try{
        sql("UPDATE assessments SET title=?,body=?,questions=?,additional=? WHERE assessment_id = ?",[
            $title,
            $body,
            json_encode($questions),
            $additional,
            $assessment_id
        ]);
        msg("success","สำเร็จ!","แก้ไขแบบประเมิน/สอบถาม สำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}
if(isset($_REQUEST["deleteAssessment"]) && isset($_GET["assessment_id"])){
    $assessment_id = $_GET["assessment_id"];
    $assessment = sql("SELECT * FROM assessments WHERE assessment_id = ?",[$assessment_id])->fetch();

    try{
        sql("DELETE FROM assessments WHERE assessment_id = ?",[$assessment_id]);
        msg("success","สำเร็จ!","ลบแบบประเมิน/สอบถาม สำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}
?>