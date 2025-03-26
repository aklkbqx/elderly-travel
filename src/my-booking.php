<?php
require_once("config.php");

require_once("components/components.php");
$row = null;
if (isset($_SESSION["user_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
} elseif (isset($_SESSION["admin_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "admin/");
} elseif (isset($_SESSION["doctor_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "doctor.php");
} else {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "/");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายการการจอง</title>
    <?php require_once("link.php") ?>
</head>

<body>

    <?php
    require "components/nav.php";
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class='container p-0' style='margin-top: 10rem;'>
        <?php backPage("/") ?>
        <h3 class="text-center">รายการจองทั้งหมด</h3>

        <?php $bookings = sql("SELECT * FROM bookings WHERE user_id = ?", [$row["user_id"]]);
        if ($bookings->rowCount() > 0) {
            function tableBooking($bookings)
            {
                global $row;
        ?>
                <div class="mt-2 table-responsive rounded-xl overflow-hidden border">
                    <table class="table table-striped mb-1 text-center align-middle">
                        <thead>
                            <tr>
                                <th style="width:100px">รายการ</th>
                                <th style="width: 300px;">จองวันที่</th>
                                <th style="width: 281px;">เที่ยววันที่</th>
                                <th style="width: 267px;">สถานะ</th>
                                <th style="width: 267px;">การจองของคุณ</th>
                                <th style="width:350px">จัดการ</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $index = 1;
                            $status = "";
                            while ($booking = $bookings->fetch()) {
                                $payment = sql("SELECT * FROM payments WHERE booking_id = ? AND user_id = ?", [$booking["booking_id"], $row["user_id"]])->fetch();
                                if ($payment) {
                                    if ($payment["status"] == "PAID") {
                                        $status = [
                                            "label" => "ชำระเงินสำเร็จแล้ว",
                                            "loading" => false,
                                            "check" => true
                                        ];
                                    } elseif ($payment["status"] == "PENDING") {
                                        $status = [
                                            "label" => "รอการชำระเงินให้สำเร็จ...",
                                            "loading" => true,
                                            "check" => false
                                        ];
                                    }
                                } else {
                                    $status = [
                                        "label" => "รอการจองให้สำเร็จ...",
                                        "loading" => true,
                                        "check" => false
                                    ];
                                }
                            ?>
                                <tr>
                                    <td>#<?= $index ?></td>
                                    <td><?= formatThaiDate($booking["booking_date"]) ?></td>
                                    <td>
                                        <?= formatThaiDate($booking["start_date"]) ?>
                                        <?php if ($booking["start_date"] !== $booking["end_date"]) : ?>
                                            ถึง <?= formatThaiDate($booking["end_date"]) ?>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 align-items-center justify-content-center">
                                            <?= $status["label"]; ?>
                                            <?php if ($status["loading"]) {
                                                spinner();
                                            } elseif ($status["check"]) {
                                                svg("check", '25px', '25px', "teal");
                                            } ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex gap-2 align-items-center justify-content-center">
                                            <?= $booking["status"] == "PENDING" ? "กำลังดำเนินการ" : ($booking["status"] == "CONFIRMED" ? "ยืนยันแล้ว" : ($booking["status"] == "COMPLETED" ? "เที่ยวสำเร็จแล้ว" : ($booking["status"] == "CANCELED" ? "ถูกปฏิเสธการจอง" : ""))); ?>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center gap-2">
                                            <?php if ($payment) {
                                                if ($payment["status"] == "PENDING" && $booking["status"] == "PENDING") { ?>
                                                    <button type='button' data-bs-toggle="modal" data-bs-target="#cancelBooking" class='btn btn-danger flex-grow-1'>ยกเลิกการจอง</button>
                                                    <a href="payment.php" class="btn btn-warning flex-grow-1">ชำระเงิน</a>
                                                <?php } elseif ($payment["status"] == "PAID" && ($booking["status"] == "CONFIRMED" || $booking["status"] == "COMPLETED")) { ?>
                                                    <button type='button' data-bs-toggle="modal" data-bs-target="#bookingTracking-<?= $booking["booking_id"] ?>" class='btn btn-teal flex-grow-1'>
                                                        <?= $booking["status"] == "CONFIRMED" ? "ติดตามสถานะการเดินทาง" : ($booking["status"] == "COMPLETED" ? "ดูการเดินทาง" : "") ?>
                                                    </button>
                                                    <div class="modal fade" id="bookingTracking-<?= $booking["booking_id"] ?>">
                                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                                            <div class="modal-content rounded-xl">
                                                                <div class="modal-header">
                                                                    <h4 class="modal-title">สถานะการเดินทาง</h4>
                                                                    <button type="button" data-bs-dismiss="modal" class="btn-close"></button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <div class="text-start">
                                                                        <div>จำนวนคน: <?= $booking["people"] ?> คน</div>
                                                                        <div>จองวันที่: <?= formatThaiDate($booking["booking_date"]) ?></div>
                                                                        <div>
                                                                            เที่ยววันที่:
                                                                            <?= formatThaiDate($booking["start_date"]) ?>
                                                                            <?php if ($booking["start_date"] !== $booking["end_date"]) : ?>
                                                                                ถึง <?= formatThaiDate($booking["end_date"]) ?>
                                                                            <?php endif; ?>
                                                                        </div>
                                                                    </div>

                                                                    <ul class="my-2 border rounded-xl mx-0">
                                                                        <?php foreach (json_decode($booking["booking_details"]) as $index => $date) { ?>
                                                                            <li class="p-2 d-flex px-0 gap-2 align-items-center">
                                                                                <div><?= formatThaiDate($index) ?>:</div>
                                                                                <div class="d-flex align-items-center gap-2">
                                                                                    <?= count($date); ?>
                                                                                    <span>สถานที่</span>
                                                                                </div>
                                                                            </li>
                                                                        <?php } ?>
                                                                    </ul>

                                                                    <?php foreach (json_decode($booking["booking_details"]) as $date => $detail) { ?>
                                                                        <div class="border rounded-xl p-2 mb-3">
                                                                            <li class="p-2 fw-bold fs-5 text-start"><?= formatThaiDate($date) ?></li>
                                                                            <div class="d-flex flex-column">
                                                                                <?php foreach ($detail as $index => $place_detail) {
                                                                                    $place = sql("SELECT * FROM places WHERE place_id = ?", [$place_detail->place_id])->fetch(); ?>
                                                                                    <div class='w-100 mb-2 d-md-flex'>
                                                                                        <div class="col-lg-4">
                                                                                            <img src="<?= imagePath("place_images", json_decode($place["images"])[0]) ?>" alt="" width="100%" height="250px" class='object-fit-cover'>
                                                                                        </div>
                                                                                        <div class='col-lg-8 p-4 d-flex position-relative text-start'>
                                                                                            <div>
                                                                                                <h4 class="mb-2"><?= $place["name"] ?></h4>
                                                                                                <div>
                                                                                                    <p class="mb-1">
                                                                                                        <?php svg("map-pin", "25px", "25px", "#212529") ?>
                                                                                                        <span><?= $place["address"] ?></span>
                                                                                                    </p>
                                                                                                </div>
                                                                                                <div>
                                                                                                    <h6>
                                                                                                        <?php svg("heart", "25px", "25px", "#dc3545") ?>
                                                                                                        ผลต่อสุขภาพ:
                                                                                                    </h6>
                                                                                                    <p><?= $place["health"] ?></p>
                                                                                                </div>
                                                                                                <h5>เริ่มเที่ยวเวลา: <?= $place_detail->start_time ?></h5>
                                                                                                <h5>สิ้นสุดเวลา: <?= $place_detail->end_time ?></h5>
                                                                                            </div>
                                                                                        </div>
                                                                                    </div>
                                                                                <?php } ?>
                                                                            </div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                                <?php
                                                                if ($booking["status"] == "CONFIRMED") { ?>
                                                                    <div class="modal-footer">
                                                                        <div class="w-100">
                                                                            <a href="./api/booking.php?bookingCompleted" class="btn btn-teal w-100">เที่ยวสำเร็จแล้ว</a>
                                                                        </div>
                                                                    </div>
                                                                <?php }
                                                                ?>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php } elseif ($payment["status"] == "PAID" && $booking["status"] == "PENDING") { ?>
                                                    <div class="d-flex align-items-center gap-2">
                                                        รอแอดมินอนุมัติ <?php spinner() ?>
                                                    </div>
                                                <?php } elseif ($booking["status"] == "CANCELED") {
                                                    echo "ถูกปฏิเสธโดยแอดมิน";
                                                }
                                            } else { ?>
                                                <button type="button" data-bs-toggle="modal" data-bs-target="#cancelBooking" class="btn btn-danger flex-grow-1">ยกเลิกการจอง</button>
                                                <a href="booking.php" class="btn btn-teal flex-grow-1">ดำเนินการจองต่อ</a>
                                            <?php } ?>
                                        </div>
                                        <?php cancelBooking($booking["booking_id"]); ?>
                                    </td>
                                </tr>
                            <?php $index++;
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            <?php }

            ?>
            <?php tableBooking($bookings) ?>

        <?php } else { ?>
            <div class="mt-5 d-flex justify-content-center align-items-center gap-2">
                <div class="text-muted ">ยังไม่มีการจองในขณะนี้</div>
                <div class="spinner-grow spinner-grow-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow spinner-grow-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="spinner-grow spinner-grow-sm" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        <?php } ?>
    </div>

</body>

</html>