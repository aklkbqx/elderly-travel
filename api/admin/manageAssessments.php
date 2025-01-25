<?php 
require_once("../../config.php");

if(isset($_REQUEST["addAssessment"])){
    foreach($_POST as $index=>$post){
        if($index==="title" && $index==="body"){
            $title = $_POST["title"];
            $body = $_POST["body"];
        }else{
            echo $index;
        }
    }

    // $imageAssessment = $_FILES["image"]["name"];
    // $tmp_name = $_FILES["image"]["tmp_name"];
    // $path = "../../assessment_images/";

    // try{
    //     sql("INSERT INTO assessments(title,body,image,questions,additional) VALUES(?,?,?,?,?)",[
    //         $title,
    //         $body,
    //         $questions,
    //         $additional,
    //     ]);
    // }catch(PDOException $e){
    //     msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    // }
}
?>