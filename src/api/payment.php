<?php
require_once("../config.php");

$user = null;

if (isset($_SESSION["user_login"])) {
    $user = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
}

if (isset($_GET["continueTopayment"]) && isset($_GET["booking_id"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $booking_id = $_GET["booking_id"];

    $payments = sql("SELECT * FROM payments WHERE booking_id = ? AND user_id = ?", [
        $booking_id,
        $user["user_id"]
    ]);

    if ($payments->rowCount() <= 0) {
        sql("INSERT INTO payments(user_id,booking_id,status) VALUES(?,?,'PENDING')", [
            $user["user_id"],
            $booking_id
        ]);
    }

    echo json_encode(["success" => true]);
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
        msg("success", "สำเร็จ!", "การชำระเงินเสร็จสมบูรณ์!", "confirmation");
    } else {
        msg("error", "ข้อผิดพลาด!", "เกิดข้อผิดพลาดในการชำระเงิน", $_SERVER["HTTP_REFERER"]);
    }
}

if (isset($_GET["changePaymentMethod"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $payment_method = $_POST["payment_method"];
    $payment = sql("SELECT * FROM payments WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    $status = ["success" => false];
    $updated = sql("UPDATE payments SET payment_method = ? WHERE payment_id = ? AND user_id = ?", [
        $payment_method,
        $payment["payment_id"],
        $user["user_id"]
    ]);
    if ($updated) {
        $status = ["success" => true, "payment_method" => $payment_method];
    } else {
        $status = ["success" => false, "payment_method" => $payment_method];
    }
    echo json_encode($status);
}

if (isset($_REQUEST["confirmPayment"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $payment = sql("SELECT * FROM payments WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();

    $slipImage = $_FILES["slip-image"]["name"];
    $tmp_name = $_FILES["slip-image"]["tmp_name"];
    $path = "../images/slip_images/";

    $status = ["success" => false, "message" => ""];

    if (!empty($slipImage)) {
        $extension = pathinfo($slipImage)["extension"];
        $newFile = uniqid() . "." . $extension;
        move_uploaded_file($tmp_name, $path . $newFile);

        sql("UPDATE payments SET slip_image = ?, status = 'PAID' WHERE payment_id = ?", [
            $newFile,
            $payment["payment_id"]
        ]);
        $status = ["success" => true, "message" => "ชำระเงินสำเร็จแล้ว ต้องการไปที่หน้าการจองของฉันหรือไม่?"];
    } else {
        $status = ["success" => false, "message" => "ไม่สามารถชำระเงินได้ กรุณาอัพโหลดสลิปการโอนเงินของคุณ!"];
    }

    echo json_encode($status);
}
