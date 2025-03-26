<div class="d-flex">
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
</div>

<div class='overflow-auto h-100'>
    <div class='mb-2 w-100 mt-3 d-flex justify-content-between mt-2 flex-wrap'>
        <h4>เลือกสถานะ</h4>
        <div class='rounded-xl d-flex flex-row align-items-center gap-1 flex-wrap'>
            <a href='?manageBookings&status=PENDING' class='btn <?= isset($_GET["status"]) && $_GET["status"] == "PENDING" ? "btn-teal" : "btn-light flex-grow-1" ?>'>รอดำเนินการ</a>
            <a href='?manageBookings&status=CONFIRMED' class='btn <?= isset($_GET["status"]) && $_GET["status"] == "CONFIRMED" ? "btn-teal" : "btn-light flex-grow-1" ?>'>ยืนยันแล้ว</a>
            <a href='?manageBookings&status=COMPLETED' class='btn <?= isset($_GET["status"]) && $_GET["status"] == "COMPLETED" ? "btn-teal" : "btn-light flex-grow-1" ?>'>สำเร็จแล้ว</a>
            <a href='?manageBookings&status=CANCELED' class='btn <?= isset($_GET["status"]) && $_GET["status"] == "CANCELED" ? "btn-teal" : "btn-light flex-grow-1" ?>'>ยกเลิกแล้ว</a>
        </div>
    </div>

    <?php $bookings = sql("SELECT * FROM bookings LEFT JOIN users ON bookings.user_id = users.user_id WHERE status = ?", [
        $_GET["status"]
    ]);
    if ($bookings->rowCount() > 0) {
        $numberList = 1;
        $totalAmount = 0;

        while ($booking = $bookings->fetch()) {
            $totalAmount *= $booking["people"];
            foreach (json_decode($booking["booking_details"], true) as $index => $detail) {
                foreach ($detail as $placeIndex => $place) {
                    $places = sql("SELECT price FROM places WHERE place_id = ?", [$place["place_id"]])->fetch();
                    $totalAmount += $places["price"];
                }
            }
            $payment = sql("SELECT * FROM payments WHERE booking_id = ? AND user_id = ?", [$booking["booking_id"], $booking["user_id"]])->fetch();
            $status = "";
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
            <div data-search-item class="border rounded-xl mb-2 p-3">
                <div class="row">
                    <div class="col-lg-8">
                        <h5>รายการ #<?= $numberList ?></h5>
                        <div class="d-flex align-items-center mt-2 gap-2">
                            <img src="<?= imagePath("user_images", $booking["image"]) ?>" width="50px" height="50px" class="object-fit-cover rounded-circle">
                            <div class="d-flex flex-column">
                                <h5 data-search-keyword="<?= $booking["firstname"] ?> <?= $booking["lastname"] ?>"><?= $booking["firstname"] ?> <?= $booking["lastname"] ?></h5>
                                <div class="text-muted"><?= $booking["email"] ?></div>
                            </div>
                        </div>

                        <div class="mt-2">
                            <div data-search-keyword="<?= $booking["people"] ?>">จำนวนคน: <?= $booking["people"] ?> คน</div>
                            <div data-search-keyword="<?= number_format($totalAmount, 2) ?>">ราคารวมทั้งหมด: ฿<?= number_format($totalAmount, 2) ?></div>
                            <div data-search-keyword="<?= formatThaiDate($booking["booking_date"]) ?>">จองวันที่: <?= formatThaiDate($booking["booking_date"]) ?></div>
                            <div>
                                เที่ยววันที่:
                                <?= formatThaiDate($booking["start_date"]) ?>
                                <?php if ($booking["start_date"] !== $booking["end_date"]) : ?>
                                    ถึง <?= formatThaiDate($booking["end_date"]) ?>
                                <?php endif; ?>
                            </div>
                            <div class="d-flex gap-2 align-items-center" data-search-keyword="<?= $status["label"] ?>">
                                สถานะการจอง:
                                <?= $status["label"]; ?>
                                <?php if ($status["loading"]) {
                                    spinner();
                                } elseif ($status["check"]) {
                                    svg("check", '25px', '25px', "teal");
                                } ?>
                            </div>
                            <?php if ($payment && $payment["payment_method"]) { ?>
                                <div class="d-flex gap-2 align-items-center">
                                    <div>ช่องทางการชำระเงิน:</div>
                                    <?= $payment["payment_method"] == "QRCODE_PROMPTPAY" ? "QR Code Prompt Pay" : ($payment["payment_method"] == "BANK_ACCOUNT_NUMBER" ? "เลขบัญชีธนาคาร(กรุงไทย)" : "null"); ?>
                                </div>
                            <?php }
                            ?>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <?php if ($payment) {
                            if ($payment["slip_image"]) { ?>
                                <div class="d-flex flex-column">
                                    <h5>สลิปการโอนเงิน:</h5>
                                    <img src="<?= imagePath("slip_images", $payment["slip_image"]) ?>" width="200px" height="200px" class="rounded-xl border object-fit-cover">
                                </div>
                        <?php }
                        } ?>
                    </div>
                </div>

                <div class="w-100 d-flex gap-2 mt-2 flex-wrap">
                    <button type='button' data-bs-toggle="modal" data-bs-target="#bookingTracking-<?= $booking["booking_id"] ?>" class='btn btn-teal flex-grow-1'>แสดงข้อมูลการเดินทาง</button>
                    <?php if (($payment && $payment["status"] == "PAID") && ($booking["status"] === "PENDING")) { ?>
                        <a href="../api/admin/manageBookings.php?confirmBooking&booking_id=<?= $booking["booking_id"] ?>" class="btn btn-warning flex-grow-1">ยืนยันการจอง</a>
                    <?php }
                    if ($booking["status"] == "PENDING") { ?>
                        <button type="button" data-bs-toggle="modal" data-bs-target="#cancelBooking" class="btn btn-danger flex-grow-1">ปฏิเสษการจอง</button>
                        <div class="modal fade" id='cancelBooking'>
                            <div class="modal-dialog modal-dialog-centered">
                                <form action="../api/admin/manageBookings.php?cancelBooking&booking_id=<?= $booking["booking_id"] ?>" class="modal-content rounded-xl" method='post'>
                                    <div class="modal-header">
                                        <h4 class="modal-title">ยกเลิกการจอง</h4>
                                        <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                    </div>
                                    <div class="modal-body">
                                        <h4 class='text-center'>แน่ใจที่จะยกเลิกการจองนี้?</h4>
                                    </div>
                                    <div class="modal-footer d-flex w-100 align-items-center">
                                        <div class="w-100 d-flex align-items-center gap-2">
                                            <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                            <button type="submit" id="confirmCancelBooking" class='btn btn-danger w-100'>ยืนยันการยกเลิก</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    <?php } ?>
                </div>

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
                        </div>
                    </div>
                </div>
            </div>
        <?php $index++;
        }
    } else { ?>
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