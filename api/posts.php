<?php 
require_once("../config.php");

if(isset($_REQUEST["addPost"])){
    $user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : (isset($_SESSION["admin_login"]) ? $_SESSION["admin_login"] : null);
    $text = $_POST["text"];

    $imagePost = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../images/post_images/";

    try{
        if(!empty($imagePost)){
            $extension = pathinfo($imagePost)["extension"];
            $image = uniqid() .".".$extension;
            move_uploaded_file($tmp_name,$path.$image);
        }else{
            throw new PDOException("กรุณาใส่รูปภาพกระทู้ของคุณ!");
        }
        sql("INSERT INTO posts(user_id,text,image) VALUES(?,?,?)",[
            $user_id,
            $text,
            $image
        ]);
        msg("success","สำเร็จ!","เพิ่มกระทู้สำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["editPost"]) && isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    $row = sql("SELECT * FROM posts WHERE post_id = ?",[$post_id])->fetch();

    $text = $_POST["text"] ?? $row["text"];

    $imagePost = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../images/post_images/";

    try{
        if(!empty($imagePost)){
            if(file_exists($path.$row["image"])){
                unlink($path.$row["image"]);
            }
            $extension = pathinfo($imagePost)["extension"];
            $image = uniqid() .".".$extension;
            move_uploaded_file($tmp_name,$path.$image);
        }else{
            $image = $row["image"];
        }

        sql("UPDATE posts SET text=?,image=? WHERE post_id = ?",[
            $text,
            $image,
            $post_id
        ]);
        msg("success","สำเร็จ!","แก้ไขกระทู้สำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["deletePost"]) && isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    $row = sql("SELECT * FROM posts WHERE post_id = ?",[$post_id])->fetch();

    $path = "../images/post_images/";

    try{
        if(file_exists($path.$row["image"])){
            unlink($path.$row["image"]);
        }
        sql("DELETE FROM posts WHERE post_id = ?",[$post_id]);
        msg("success","สำเร็จ!","ลบกระทู้สำเร็จแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}
?>