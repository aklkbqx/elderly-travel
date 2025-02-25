<?php
require_once("config.php");
require_once("components/components.php");

$row = null;

foreach (["user", "doctor"] as $index => $type) {
    if (isset($_SESSION[$type . "_login"])) {
        $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION[$type . "_login"]])->fetch();
    }
}

if (!$row) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้ กรุณาทำการเข้าสู่ระบบ", "login.php");
}

$isSelectedDate = false;
$booking_date = date('Y-m-d');

$booking = null;

$fetchBooking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$row["user_id"]]);
if ($fetchBooking->rowCount() > 0) {
    $booking = $fetchBooking->fetch();
    $checkInDate = $booking["start_date"];
    $checkOutDate = $booking["end_date"];
    $people = $booking["people"];
    $isSelectedDate = true;
} else {
    $isSelectedDate = false;
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $checkInDate = $_POST["checkInDate"];
        $checkOutDate = $_POST["checkOutDate"];
        $people = $_POST["people"];

        $insert = sql("INSERT INTO bookings(user_id,people,booking_date,start_date,end_date) VALUES(?,?,?,?,?)", [
            $row["user_id"],
            $people,
            $booking_date,
            $checkInDate,
            $checkOutDate
        ]);

        header("Refresh:0");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>สถานที่ทั้งหมด</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php
    require_once("components/nav.php");
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class='container p-0' style='margin-top: 10rem;'>
        <?php
        backPage();
        if (!$isSelectedDate) { ?>
            <div class='mt-2'>
                <?php formSelectDateBooking(); ?>
            </div>
        <?php } else { ?>
            <h4 class="mt-2">สถานที่ทั้งหมด</h4>
            <form action="" method="post" class='m-0 mt-2 row'>
                <div class='col-lg-8'>
                    <?php
                    $places = sql("SELECT * FROM places");
                    if ($places->rowCount() > 0) {
                        while ($place = $places->fetch()) {
                            placeCardBooking($place, true, $row["user_id"]);
                        }
                    } else { ?>
                        <h6 class='text-center text-muted'>
                            ยังไม่มีสถานที่ในขณะนี้...
                        </h6>
                    <?php }
                    ?>
                </div>
                <div class='col-lg-4 p-0'>
                    <div class='d-flex justify-content-center position-sticky' style="top:10px">
                        <div class='shadow border mb-2 p-4 rounded-xl w-100 container'>
                            <h4>สถานที่ในรายการจองของคุณ</h4>
                            <div class='my-4 p-4 border rounded-xl d-flex flex-column gap-4' data-booking-box></div>
                            <div class="d-flex flex-column gap-2 mt-2">
                                <h5>วันที่เช็คอิน: <?= formatThaiDate($checkInDate) ?><input type="hidden" name='checkInDate' disabled value="<?= $checkInDate ?>" class="border-0 bg-transparent"></h5>
                                <h5>วันที่เช็คเอาท์: <?= formatThaiDate($checkOutDate) ?><input type="hidden" name='checkOutDate' disabled value="<?= $checkOutDate ?>" class="border-0 bg-transparent"></h5>
                                <h5>จำนวนคน: <?= $people ?><input type="hidden" name='people' disabled value="<?= $people ?>" class="border-0 bg-transparent"></h5>
                            </div>
                            <div class="d-flex align-items-center gap-2">
                                <button type='button' data-bs-toggle="modal" data-bs-target="#cancelBooking" class='btn btn-danger w-100 mt-2 p-3 fs-5'>ยกเลิกการจอง</button>
                                <button type='submit' name='continueBooking' class='btn btn-teal w-100 mt-2 p-3 fs-5'>ดำเนินการต่อ</button>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        <?php } ?>
    </div>

    <?php cancelBooking($booking) ?>

    <script src="libs/booking.js"></script>
</body>

</html>