<?php 
require_once("../../config.php");

if(isset($_REQUEST["addNews"])){
    $title = $_POST["title"];
    $body = $_POST["body"];

    $imageNews = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../../images/news_images/";

    try{
        if(!empty($imageNews)){
            $extension = pathinfo($imageNews)["extension"];
            $image = uniqid() .".".$extension;
            move_uploaded_file($tmp_name,$path.$image);
        }else{
            throw new PDOException("กรุณาใส่รูปภาพข่าว!");
        }
        sql("INSERT INTO news(title,body,image) VALUES(?,?,?)",[
            $title,
            $body,
            $image
        ]);
        msg("success","สำเร็จ!","เพิ่มข่าวสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["editNews"]) && isset($_GET["news_id"])){
    $news_id = $_GET["news_id"];
    $row = sql("SELECT * FROM news WHERE news_id = ?",[$news_id])->fetch();

    $title = $_POST["title"] ?? $row["title"];
    $body = $_POST["body"] ?? $row["body"];

    $imageNews = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../../images/news_images/";

    try{
        if(!empty($imageNews)){
            if(file_exists($path.$row["image"])){
                unlink($path.$row["image"]);
            }
            $extension = pathinfo($imageNews)["extension"];
            $image = uniqid() .".".$extension;
            move_uploaded_file($tmp_name,$path.$image);
        }else{
            $image = $row["image"];
        }

        sql("UPDATE news SET title=?,body=?,image=? WHERE news_id = ?",[
            $title,
            $body,
            $image,
            $news_id
        ]);
        msg("success","สำเร็จ!","แก้ไขข่าวสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["deleteNews"]) && isset($_GET["news_id"])){
    $news_id = $_GET["news_id"];
    $row = sql("SELECT * FROM news WHERE news_id = ?",[$news_id])->fetch();

    $path = "../../images/news_images/";

    try{
        if(file_exists($path.$row["image"])){
            unlink($path.$row["image"]);
        }
        sql("DELETE FROM news WHERE news_id = ?",[$news_id]);
        msg("success","สำเร็จ!","ลบข่าวสำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}
?>