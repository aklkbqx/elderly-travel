<?php
require_once("../config.php");

$user = null;

if (isset($_SESSION["user_login"])) {
    $user = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
}

if (isset($_GET["cancel_booking"])) {
    $fetchBooking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user['user_id']]);
    $fetchPayment = sql("SELECT * FROM payments WHERE user_id = ?", [$user['user_id']]);
    if ($fetchBooking->rowCount() > 0) {
        $booking = $fetchBooking->fetch();

        if ($fetchPayment->rowCount() > 0) {
            $payment = $fetchPayment->fetch();
            sql("DELETE FROM payments WHERE user_id = ? AND status = 'PENDING' AND booking_id = ?", [$user['user_id'], $booking["booking_id"]]);
        }

        sql("DELETE FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user['user_id']]);
        msg("success", "สำเร็จ!", "ยกเลิกการจองเรียบร้อยแล้ว!", $_SERVER["HTTP_REFERER"]);
    }
}

if (isset($_GET["getBookings"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    if ($booking) {
        $booking_details = json_decode($booking["booking_details"], true);
        echo json_encode($booking_details);
    } else {
        echo json_encode([]);
    }
}

if (isset($_GET["addToBookings"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $place_id = $_POST["place_id"];
    $date = $_POST["date"];
    $place = sql("SELECT * FROM places WHERE place_id = ?", [$place_id])->fetch();
    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    $booking_details = json_decode($booking["booking_details"], true);

    if (!isset($booking_details[$date])) {
        $booking_details[$date] = [];
    }

    $lastEndTime = "07:00";
    if (!empty($booking_details[$date])) {
        $lastBooking = end($booking_details[$date]);
        $lastEndTime = $lastBooking['end_time'];
    }

    $startTime = date('H:i', strtotime($lastEndTime . ' + 1 hour'));
    $endTime = date('H:i', strtotime($startTime . ' + 1 hour'));

    $place_data = [
        "place_id" => $place["place_id"],
        "name" => $place["name"],
        "start_time" => $startTime,
        "end_time" => $endTime,
        "price" => $place["price"]
    ];

    $booking_details[$date][] = $place_data;

    sql("UPDATE bookings SET booking_details = ? WHERE booking_id = ?", [
        json_encode($booking_details),
        $booking["booking_id"]
    ]);

    echo json_encode($booking_details);
}

if (isset($_GET["removeFromBookings"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $date = $_POST["date"];
    $place_id = $_POST["place_id"];
    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    $booking_details = json_decode($booking["booking_details"], true);

    if (isset($booking_details[$date])) {
        $indexToRemove = null;
        foreach ($booking_details[$date] as $index => $detail) {
            if ($detail["place_id"] == $place_id) {
                $indexToRemove = $index;
                break;
            }
        }

        if ($indexToRemove !== null) {
            array_splice($booking_details[$date], $indexToRemove, 1);
        }
    }

    sql("UPDATE bookings SET booking_details = ? WHERE booking_id = ?", [
        json_encode($booking_details),
        $booking["booking_id"]
    ]);

    echo json_encode(["status" => "success"]);
}

if (isset($_GET["updateBookingTime"]) && $_SERVER["REQUEST_METHOD"] == "POST") {
    $place_id = $_POST["place_id"];
    $time = $_POST["time"];
    $type = $_POST["type"];
    $date = $_POST["date"];

    $booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user["user_id"]])->fetch();
    $booking_details = json_decode($booking["booking_details"], true);

    if (isset($booking_details[$date])) {
        foreach ($booking_details[$date] as &$detail) {
            if ($detail["place_id"] == $place_id) {
                $detail[$type] = $time;
                break;
            }
        }
    }

    sql("UPDATE bookings SET booking_details = ? WHERE booking_id = ?", [
        json_encode($booking_details),
        $booking["booking_id"]
    ]);

    echo json_encode(["status" => "success"]);
}

if (isset($_GET["getPlaceDetails"]) && $_SERVER["REQUEST_METHOD"] == "GET") {
    $place_id = $_GET["place_id"];
    $place = sql("SELECT * FROM places WHERE place_id = ?", [$place_id])->fetch();

    if ($place) {
        echo json_encode([
            "description" => $place["description"],
            "price" => $place["price"]
        ]);
    } else {
        echo json_encode([]);
    }
}

if(isset($_GET["bookingCompleted"])){
    sql("UPDATE bookings SET status = 'COMPLETED' WHERE user_id = ?",[
        $user["user_id"]
    ]);
    msg("success","สำเร็จ!","เที่ยวเสร็จสิ้นแล้ว!",$_SERVER["HTTP_REFERER"]);
}