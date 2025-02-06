<?php
require_once("../config.php");

$row = null;
if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}else{
    msg("warning","เกิดข้อผิดพลาด","ไม่สามารถเข้าถึงหน้านี้ได้",$_SERVER["HTTP_REFERER"]);
}

if(isset($_REQUEST["sendAssessment"]) && isset($_GET["assessment_id"])){
    $assessment_id = $_GET["assessment_id"];
    $additional = $_POST["additional"] ?? null;
    $response = [];
    
    foreach($_POST as $index=>$r){
        if($index != "additional" && $index != "sendAssessment"){
            $response[] = $r;
        }
    }

    sql("INSERT INTO assessment_responses(assessment_id,user_id,responses,additional) VALUES(?,?,?,?)",[
        $assessment_id,
        $row["user_id"],
        json_encode($response),
        $additional
    ]);
    msg("success","สำเร็จ!","ทำแบบประเมินสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
}

if(isset($_GET["visitors"]) && isset($_GET["assessment_id"])){
    $assessment_id = $_GET["assessment_id"];
    $assessment = sql("SELECT * FROM assessments WHERE assessment_id = ?",[$assessment_id])->fetch();

    sql("UPDATE assessments SET visitors = ? WHERE assessment_id = ?",[$assessment["visitors"] + 1,$assessment_id]);
}

?>