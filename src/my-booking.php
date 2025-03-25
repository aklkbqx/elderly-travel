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

        <?php
        $bookings = sql("SELECT * FROM bookings WHERE user_id = ? AND status ='PENDING' ", [$row["user_id"]]);
        if (!$bookings->rowCount() > 0) { ?>
            <div class="mt-2 table-responsive rounded-xl overflow-hidden border">
                <table class="table table-striped mb-1 text-center align-middle">
                    <thead>
                        <tr>
                            <th style="width:250px">รายการ</th>
                            <th style="width:250px">จองวันที่</th>
                            <th style="width:250px">เที่ยววันที่</th>
                            <th style="width:250px">สถานะ</th>
                            <th style="width:250px">จัดการ</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>#1</td>
                            <td>2025-03-20</td>
                            <td>2025-03-20 - 2025-03-20</td>
                            <td>กำลังรออนุมัติ...</td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <button type="button" class="btn btn-danger">ยกเลิกการจอง</button>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
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