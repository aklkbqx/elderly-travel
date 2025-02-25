<?php 
require_once("../config.php");

if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","admin/");
}

if(isset($_GET["visitors"]) && isset($_GET["news_id"])){
    $news_id = $_GET["news_id"];
    $news = sql("SELECT * FROM news WHERE news_id = ?",[$news_id])->fetch();
    sql("UPDATE news SET visitors = ? WHERE news_id = ?",[$news["visitors"] + 1,$news["news_id"]]);
}

if(isset($_GET["like"]) && isset($_GET["news_id"])){
    $news_id = $_GET["news_id"];
    
    $news_like = sql("SELECT * FROM news_likes WHERE news_id = ? AND user_id = ?",[$news_id,$row["user_id"]]);
    if($news_like->rowCount() > 0){
        $news = sql("DELETE FROM news_likes WHERE news_id = ? AND user_id = ?",[$news_id,$row["user_id"]]);
        msg("success","สำเร็จ!","ยกเลิกถูกใจข่าวนี้แล้ว",$_SERVER["HTTP_REFERER"]);
    }else{
        $news = sql("INSERT INTO news_likes(`news_id`,`user_id`,`like`) VALUES(?,?,?)",[
            $news_id,
            $row["user_id"],
            1
        ]);
        msg("success","สำเร็จ!","ถูกใจข่าวนี้แล้ว",$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["submit_comment"]) && isset($_GET["comment"]) && isset($_GET["news_id"])){
    $news_id = $_GET["news_id"];
    $comment = $_POST["comment"];

    sql("INSERT INTO news_comments(news_id,user_id,comment) VALUES(?,?,?)",[
        $news_id,
        $row["user_id"],
        $comment
    ]);
    msg("success","สำเร็จ!","แสดงความคิดเห็นข่าวนี้แล้ว",$_SERVER["HTTP_REFERER"]);
}

if(isset($_GET['delete_comment']) && isset($_GET['comment_id']) && isset($_GET["news_id"])){
    $comment_id = $_GET['comment_id'];
    $news_id = $_GET["news_id"];

    sql("DELETE FROM news_comments WHERE comment_id = ? AND news_id = ?",[$comment_id,$news_id]);
    msg("success","สำเร็จ!","ลบความคิดเห็นแล้ว!",$_SERVER["HTTP_REFERER"]);
}

?>