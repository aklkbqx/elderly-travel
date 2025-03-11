<?php
require_once("../config.php");

$user = null;

if (isset($_SESSION["user_login"])) {
    $user = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
}

if (isset($_GET["cancel_booking"])) {
    $fetchBooking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user['user_id']]);
    if ($fetchBooking->rowCount() > 0) {
        sql("DELETE FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user['user_id']]);
        msg("success", "สำเร็จ!", "ยกเลิกการจองเรียบร้อยแล้ว!", $_SERVER["HTTP_REFERER"]);
    }
}

if (isset($_GET["getBookings"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
    $html = '';
    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    $booking_details = json_decode($booking["booking_details"]);
    echo $booking["booking_details"];
}

if (isset($_GET["addToBookings"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $place_id = $_POST["place_id"];
    $place = sql("SELECT * FROM places WHERE place_id = ?", [$place_id])->fetch();
    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    $booking_details = json_decode($booking["booking_details"]);
    $detail = $booking_details;
    $detail[] = [
        "place_id" => $place["place_id"],
        "name" => $place["name"]
    ];
    $updated = sql("UPDATE bookings SET booking_details = ? WHERE booking_id = ?", [
        json_encode($detail),
        $booking["booking_id"]
    ]);
    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    echo $booking["booking_details"];
}

if (isset($_GET["removeFromBookings"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $place_id = $_POST["place_id"];
    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    $booking_details = json_decode($booking["booking_details"]);

    $booking_details = array_values(array_filter($booking_details, function ($detail) use ($place_id) {
        return $detail->place_id != $place_id;
    }));

    sql("UPDATE bookings SET booking_details = ? WHERE booking_id = ?", [
        json_encode($booking_details),
        $booking["booking_id"]
    ]);
}
