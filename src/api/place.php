<?php
require_once("../config.php");

if (isset($_SESSION["user_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
} elseif (isset($_SESSION["admin_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "admin/");
}

if (isset($_GET["visitors"]) && isset($_GET["place_id"])) {
    $place_id = $_GET["place_id"];
    $place = sql("SELECT * FROM places WHERE place_id = ?", [$place_id])->fetch();
    sql("UPDATE places SET visitors = ? WHERE place_id = ?", [$place["visitors"] + 1, $place["place_id"]]);
}

if (isset($_GET["like"]) && isset($_GET["place_id"])) {
    $place_id = $_GET["place_id"];

    $place_like = sql("SELECT * FROM place_likes WHERE place_id = ? AND user_id = ?", [$place_id, $row["user_id"]]);
    if ($place_like->rowCount() > 0) {
        $place = sql("DELETE FROM place_likes WHERE place_id = ? AND user_id = ?", [$place_id, $row["user_id"]]);
        msg("success", "สำเร็จ!", "ยกเลิกถูกใจข่าวนี้แล้ว", $_SERVER["HTTP_REFERER"]);
    } else {
        $place = sql("INSERT INTO place_likes(`place_id`,`user_id`,`like`) VALUES(?,?,?)", [
            $place_id,
            $row["user_id"],
            1
        ]);
        msg("success", "สำเร็จ!", "ถูกใจข่าวนี้แล้ว", $_SERVER["HTTP_REFERER"]);
    }
}

if (isset($_REQUEST["sendReview"]) && isset($_GET["place_id"])) {
    $place_id = $_GET["place_id"];
    $comment = $_POST["comment"];
    $rating = $_POST["rating"];

    sql("INSERT INTO place_reviews(place_id,user_id,comment,rating) VALUES(?, ?, ?, ?)", [
        $place_id,
        $row["user_id"],
        $comment,
        $rating
    ]);
    msg("success", "สำเร็จ!", "แสดงความคิดเห็นข่าวนี้แล้ว", $_SERVER["HTTP_REFERER"]);
}

if (isset($_GET['delete_review']) && isset($_GET['review_id']) && isset($_GET["place_id"])) {
    $review_id = $_GET['review_id'];
    $place_id = $_GET["place_id"];

    sql("DELETE FROM place_reviews WHERE review_id = ? AND place_id = ?", [$review_id, $place_id]);
    msg("success", "สำเร็จ!", "ลบความคิดเห็นแล้ว!", $_SERVER["HTTP_REFERER"]);
}
