<?php 
require_once("../config.php");

$user = null;

if (isset($_SESSION["user_login"])) {
    $user = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
}

if (isset($_GET["processPayment"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_POST["booking_id"];
    $amount = $_POST["amount"];

    $insertPayment = sql("INSERT INTO payments (user_id, booking_id, amount, status) VALUES (?, ?, ?, 'PENDING')", [
        $user["user_id"],
        $booking_id,
        $amount
    ]);

    if ($insertPayment) {
        sql("UPDATE payments SET status = 'COMPLETED' WHERE booking_id = ?", [$booking_id]);
        msg("success", "สำเร็จ!", "การชำระเงินเสร็จสมบูรณ์!", "confirmation.php");
    } else {
        msg("error", "ข้อผิดพลาด!", "เกิดข้อผิดพลาดในการชำระเงิน", $_SERVER["HTTP_REFERER"]);
    }
}
?>