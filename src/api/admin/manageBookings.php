<?php
require_once("../../config.php");

if (isset($_GET["confirmBooking"]) && isset($_GET["booking_id"])) {
    $booking_id = $_GET["booking_id"];
    $bookings = sql("UPDATE bookings SET status = 'CONFIRMED' WHERE booking_id = ?", [
        $booking_id
    ]);
    msg("success","สำเร็จ!","ยืนยันการจองสำเร็จแล้ว!","../../admin/?manageBookings&status=CONFIRMED");
}

if (isset($_GET["cancelBooking"]) && isset($_GET["booking_id"])) {
    $booking_id = $_GET["booking_id"];
    $bookings = sql("UPDATE bookings SET status = 'CANCELED' WHERE booking_id = ?", [
        $booking_id
    ]);
    msg("success","สำเร็จ!","ปฏิเสธการจองแล้ว!","../../admin/?manageBookings&status=CANCELED");
}