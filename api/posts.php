<?php 
require_once("../config.php");
$row = null;
if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["admin_login"]])->fetch();
}

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
            $image = null;
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

if(isset($_GET["sendComment"]) && isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    $comment = $_POST["comment"];
    echo $comment;
    sql("INSERT INTO post_comments(post_id,user_id,comment) VALUES(?,?,?)",[
        $post_id,
        $row["user_id"],
        $comment
    ]);
}

if(isset($_GET['getComments']) && isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    $post_comments = sql("SELECT *,post_comments.created_at as post_comment_created_at 
                        FROM post_comments LEFT JOIN users ON post_comments.user_id = users.user_id 
                        WHERE post_id = ?",[$post_id]);
    $html = "";
    if($post_comments->rowCount() > 0){
        while($comment = $post_comments->fetch()){
            if($row && $row["user_id"] == $comment["user_id"]){
                $html .= '
                <div class="d-flex align-items-center justify-content-end gap-2">
                    <div class="bg-white rounded-xl shadow p-2">'.$comment['comment'].'</div>
                    <img src="'.imagePath('user_images',$comment["image"]).'" width="40px" height="40px" class="object-fit-cover rounded-circle border">
                </div>
                ';
            }else{
                $html .= '
                <div class="d-flex align-items-center justify-content-start gap-2">
                    <img src="'.imagePath('user_images',$comment["image"]).'" width="40px" height="40px" class="object-fit-cover rounded-circle border">
                    <div class="bg-white rounded-xl shadow p-2">'.$comment['comment'].'</div>
                </div>
                ';
            }
        }
    }else{
        $html .= '
        <div class="h-100 d-flex align-items-center justify-content-center">
            ยังไม่มีความคิดเห็นในขณะนี้...
        </div>
        ';
    }
    echo $html;
}

if(isset($_GET["sendLike"]) && isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    $post_likes = sql("SELECT * FROM post_likes LEFT JOIN users ON post_likes.user_id = users.user_id WHERE post_likes.post_id = ? AND post_likes.user_id = ?",[$post_id,$row["user_id"]]);
    if($post_likes->rowCount() > 0){
        while($post_like = $post_likes->fetch()){
            if($row["user_id"] == $post_like["user_id"]){
                sql("DELETE FROM post_likes WHERE user_id = ?",[$post_like["user_id"]]);
                echo false;
            }
        }
    }else{
        sql("INSERT INTO post_likes(post_id,user_id,`like`) VALUES(?,?,?)",[
            $post_id,
            $row["user_id"],
            1
        ]);
        echo true;
    }
}

if(isset($_GET['getCommentCount']) && isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    echo sql("SELECT COUNT(*) as count FROM post_comments WHERE post_id = ?",[$post_id])->fetch()["count"];
}

if(isset($_GET['getLikeCount']) && isset($_GET["post_id"])){
    $post_id = $_GET["post_id"];
    echo sql("SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?",[$post_id])->fetch()["count"];
}
?>