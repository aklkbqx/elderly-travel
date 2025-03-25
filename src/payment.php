<?php
require_once("config.php");
require_once("components/components.php");

if (!isset($_SESSION["user_login"])) {
    msg("warning", "คำเตือน", "กรุณาเข้าสู่ระบบเพื่อทำการชำระเงิน", "login.php");
}

$row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
$booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$row["user_id"]])->fetch();
$booking_details = json_decode($booking["booking_details"], true);

$totalAmount = 0;
foreach ($booking_details as $detail) {
    $place = sql("SELECT price FROM places WHERE place_id = ?", [$detail["place_id"]])->fetch();
    $totalAmount += $place["price"];
}

$totalAmount *= $booking["people"];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ชำระเงิน</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php require_once("components/nav.php"); ?>

    <div class="container" style='margin-top:10rem'>
        <?php backPage("javascript:window.history.back()"); ?>
        <div class="shadow rounded-xl p-4 w-100">
            <?php if (isset($_GET["booking_id"])) { ?>
                <div class="payment-header">
                    <h2>ชำระเงิน</h2>
                </div>
                <div class="payment-details">
                    <h4>ราคารวมทั้งหมด: ฿<?= number_format($totalAmount, 2) ?></h4>
                    <p>จำนวนคน: <?= $booking["people"] ?></p>
                </div>
                <button class="btn btn-teal" onclick="processPayment(<?= $booking['booking_id'] ?>, <?= $totalAmount ?>)">ดำเนินการชำระเงิน</button>

                <script>
                    function processPayment(booking_id, amount) {
                        $.ajax({
                            url: "api/payment.php?processPayment",
                            type: "POST",
                            data: {
                                booking_id,
                                amount
                            },
                            success: (response) => {
                                window.location.href = "confirmation.php";
                            },
                            error: (error) => {
                                alert("เกิดข้อผิดพลาดในการชำระเงิน");
                            }
                        });
                    }
                </script>
            <?php } else { ?>
                <h3 class="text-center">เกิดข้อผิดพลาด ได้โปรดตรวจสอบ <a href="my-booking.php">รายการจองของคุณ</a> </h3>
            <?php }
            ?>
        </div>
    </div>

</body>

</html>