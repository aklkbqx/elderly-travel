<?php
require_once("config.php");
require_once("components/components.php");

if (!isset($_SESSION["user_login"])) {
    msg("warning", "คำเตือน", "กรุณาเข้าสู่ระบบเพื่อทำการชำระเงิน", "login");
}

$row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
$booking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$row["user_id"]])->fetch();
$booking_details = json_decode($booking["booking_details"], true);

$totalAmount = 0;
foreach ($booking_details as $index => $detail) {
    foreach ($detail as $placeIndex => $place) {
        $places = sql("SELECT price FROM places WHERE place_id = ?", [$place["place_id"]])->fetch();
        $totalAmount += $places["price"];
    }
}

$totalAmount *= $booking["people"];

$paymentMethod = [
    [
        "image" => "thaiqr.png",
        "label" => "QR Code Prompt Pay",
        "id" => "QRCODE_PROMPTPAY"
    ],
    [
        "image" => "krungthai-bank.png",
        "label" => "เลขบัญชีธนาคาร(กรุงไทย)",
        "id" => "BANK_ACCOUNT_NUMBER"
    ],
];
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
    <?php
    require_once("components/nav.php");
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class="container mb-5" style='margin-top:10rem'>
        <div class="mb-2"><?php backPage("booking"); ?></div>
        <?php
        $payments = sql("SELECT * FROM payments WHERE user_id = ? AND booking_id = ? AND status = 'PENDING'", [
            $row["user_id"],
            $booking["booking_id"]
        ]);
        if ($payments->rowCount() > 0) {
            $payment = $payments->fetch(); ?>
            <div class="row">
                <div class="col-lg-8">
                    <div class="shadow p-4 rounded-xl">
                        <h3 class="mb-3">รายละเอียดการจอง</h3>
                        <?php foreach (json_decode($booking["booking_details"]) as $date => $detail) { ?>
                            <div class="border rounded-xl p-2 mb-3">
                                <li class="p-2 fw-bold fs-5"><?= formatThaiDate($date) ?></li>
                                <div class="d-flex flex-column">
                                    <?php foreach ($detail as $index => $place) {
                                        $place = sql("SELECT * FROM places WHERE place_id = ?", [$place->place_id])->fetch(); ?>
                                        <div class='w-100 mb-2 d-md-flex'>
                                            <div class="col-lg-4">
                                                <img src="<?= imagePath("place_images", json_decode($place["images"])[0]) ?>" alt="" width="100%" height="200px" class='object-fit-cover'>
                                            </div>
                                            <div class='col-lg-8 p-4 d-flex flex-column position-relative'>
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
                                                    <h5>
                                                        ราคา: <?= $place["price"] ?>
                                                    </h5>
                                                </div>
                                                <div class='position-absolute d-flex flex-column gap-2' style="top:10px; right:10px">
                                                    <div class='badge text-bg-success'><?= sql("SELECT * FROM place_categories WHERE category_id = ?", [$place["category_id"]])->fetch()["name"]; ?></div>
                                                    <div class='d-flex align-items-center gap-2'>
                                                        <svg width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z" fill="#ffc107" />
                                                        </svg>
                                                        <?php
                                                        $fetchPlaceReviews = sql("SELECT * FROM place_reviews WHERE place_id = ?", [$place["place_id"]])->fetchAll();
                                                        $total = count($fetchPlaceReviews);
                                                        $ratingAverage = 0;
                                                        $rating = 0;
                                                        if ($total > 0) {
                                                            foreach ($fetchPlaceReviews as $index => $review) {
                                                                $rating += $review["rating"];
                                                                $ratingAverage = $rating / $total;
                                                            }
                                                        }
                                                        ?>
                                                        <div><?= round($ratingAverage, 1) ?></div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    <?php } ?>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                </div>
                <div class="col-lg-4 mt-4 mt-sm-0">
                    <div class="shadow p-4 rounded-xl d-flex flex-column">
                        <div>
                            <h3 class="mb-2">สรุปการสั่งซื้อ</h3>
                            <div>จำนวนคน: <?= $booking["people"] ?> คน</div>
                            <div>จองวันที่: <?= formatThaiDate($booking["booking_date"]) ?></div>
                            <div>
                                เที่ยววันที่:
                                <?= formatThaiDate($booking["start_date"]) ?>
                                <?php if ($booking["start_date"] !== $booking["end_date"]) : ?>
                                    ถึง <?= formatThaiDate($booking["end_date"]) ?>
                                <?php endif; ?>
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
                        </div>

                        <div class="my-3">
                            <h4 class="mb-2">ช่องทางการชำระเงิน</h4>
                            <div class="d-flex flex-column gap-2">
                                <?php foreach ($paymentMethod as $index => $method) { ?>
                                    <div class="d-flex gap-2 align-items-center border w-100 rounded-xl p-2 cursor-pointer btn text-start">
                                        <input class="form-check-input m-0 ms-2 me-1 cursor-pointer" type="radio" name="payment_method" id="<?= $method['id'] ?>" value="<?= $method['id'] ?>" <?= $payment["payment_method"] == $method['id'] ? "checked" : "" ?>>
                                        <label class="form-check-label w-100 cursor-pointer" for="<?= $method['id'] ?>">
                                            <img src="<?= imagePath("web_images", $method["image"]) ?>" width="35px" height="35px" class="object-fit-cover overflow-hidden rounded-2">
                                            <span><?= $method["label"] ?></span>
                                        </label>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>

                        <div>
                            <h4>ราคารวมทั้งหมด: ฿<?= number_format($totalAmount, 2) ?></h4>
                            <button type='button' data-bs-toggle="modal" data-bs-target="#modalPaymentMethod" id="confirmPayment" class="btn btn-teal w-100 mt-2" disabled>ดำเนินการชำระเงิน</button>
                        </div>

                        <div class="modal fade" id="modalPaymentMethod">
                            <div class="modal-dialog modal-dialog-centered modal-md">
                                <div class="modal-content rounded-xl">
                                    <div class="modal-header">
                                        <h4 class="modal-title">ชำระเงิน</h4>
                                        <button class="btn-close" data-bs-dismiss="modal" type="button"></button>
                                    </div>
                                    <div class="modal-body">
                                        <div id="paymentMethodContainer"></div>
                                        <div class="my-3">
                                            <label for="slip-image" class="form-label">หลังโอนเงินเสร็จแล้ว #โปรดอัพโหลดสลิปเพื่อทำการตรวจสอบ</label>
                                            <input class="form-control" type="file" id="slip-image" name="slip-image">
                                        </div>
                                        <div class="d-none justify-content-center p-2" id="showSlipImage">
                                            <img src="" width="150px" height="200px" class="object-fit-cover rounded-xl overflow-hidden border">
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <div class="w-100 d-flex flex-row align-items-center gap-2">
                                            <button type="button" data-bs-dismiss="modal" class="btn btn-secondary w-100">ปิด</button>
                                            <button type="submit" name="confirmPayment" disabled class="btn btn-teal w-100">ชำระเงิน</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } else { ?>
            <h3 class="text-center">เกิดข้อผิดพลาด ได้โปรดตรวจสอบ <a href="my-booking">รายการจองของคุณ</a> </h3>
        <?php } ?>

    </div>

    <?php loadingComponent() ?>

    <script>
        $(document).ready(function() {
            function updateModalContent() {
                let selectedMethod = $('input[name="payment_method"]:checked').attr('id');
                let modalBody = $('#paymentMethodContainer');
                let totalAmount = <?= json_encode(number_format($totalAmount, 2)) ?>;

                if (selectedMethod === 'QRCODE_PROMPTPAY') {
                    modalBody.html(`
                <div class="text-center d-flex flex-column align-items-center">
                    <div class="d-flex align-items-center gap-2">
                        <h5>ชำระเงินด้วย QR Code PromptPay</h5>
                        <img src="<?= imagePath("web_images", "thaiqr.png") ?>" width="50px" height="50px" class="object-fit-cover rounded-xl overflow-hidden">
                    </div>
                    <img src="https://promptpay.io/0902856188/${totalAmount}" width="200px" height="200px" class="my-3">
                    <h4>ยอดเงิน: ฿${totalAmount}</h4>
                </div>
            `);
                } else if (selectedMethod === 'BANK_ACCOUNT_NUMBER') {
                    let bankInfo = {
                        nameTH: "ธนาคารกรุงไทย",
                        nameEN: "Krungthai Bank",
                        accountNumber: "6080282712",
                        accountHolder: "นาย เอกลักษณ์ เครือบูรณ์"
                    };

                    modalBody.html(`
                <div class="text-center">
                    <h5>ชำระเงินผ่านบัญชีธนาคาร</h5>
                    <img src="<?= imagePath("web_images", "krungthai-bank.png") ?>" width="100px" height="100px" class="object-fit-cover rounded-xl overflow-hidden">
                    <h4 class="mt-2">${bankInfo.nameTH} (${bankInfo.nameEN})</h4>
                    <h5 class="fw-bold">${bankInfo.accountHolder}</h5>
                    <div class="d-flex align-items-center justify-content-center gap-2">
                        <span class="fs-4" id="accountNumber">${bankInfo.accountNumber}</span>
                        <button class="btn btn-sm btn-teal" id="copyAccount">คัดลอก</button>
                    </div>
                    <h4 class="mt-3">ยอดเงิน: ฿${totalAmount}</h4>
                </div>
            `);
                }
            }

            $('input[name="payment_method"]').change(updateModalContent);
            $('button[data-bs-target="#modalPaymentMethod"]').click(updateModalContent);

            $(document).on("click", "#copyAccount", function() {
                let accountNumber = $("#accountNumber").text();
                navigator.clipboard.writeText(accountNumber).then(() => {
                    alert("คัดลอกเลขบัญชีแล้ว: " + accountNumber);
                });
            });

            const slipImage = $("#slip-image")
            slipImage.on("change", function() {
                const showSlipImage = $("#showSlipImage");
                const confirmPayment = $("[name='confirmPayment']");
                if ($(this)[0].files.length > 0) {
                    showSlipImage.removeClass("d-none").addClass("d-flex");
                    showSlipImage.find("img").attr("src", window.URL.createObjectURL($(this)[0].files[0]));
                    confirmPayment.prop("disabled", false)
                    const formData = new FormData();
                    formData.append("slip-image", $(this)[0].files[0], $(this)[0].files[0].name)
                    confirmPayment.on("click", function() {
                        $.ajax({
                            type: "POST",
                            url: "./api/payment?confirmPayment",
                            data: formData,
                            processData: false,
                            contentType: false,
                            beforeSend: () => {
                                $("#modalPaymentMethod").modal("hide");
                                const loadingComponent = $("#loadingComponent");
                                loadingComponent.removeClass("d-none").addClass("d-flex");
                                $("html, body").animate({
                                    scrollTop: 0
                                }, "slow").addClass("overflow-hidden");
                            },
                            success: (response) => {
                                const res = JSON.parse(response);
                                if (res.success) {
                                    if (confirm(res.message)) {
                                        window.location = "./my-booking";
                                    } else {
                                        window.location = "/";
                                    }
                                } else {
                                    alert(res.message);
                                }
                            }
                        })
                    })
                } else {
                    $(this).val(null)
                    showSlipImage.removeClass("d-flex").addClass("d-none");
                    showSlipImage.find("img").attr("src", "");
                    continuePayment.prop("disabled", true)
                }
            })

            const confirmPayment = $("#confirmPayment");
            const checkPaymentMethod = function() {
                if ($(this).prop("checked") == true) {
                    $.ajax({
                        type: "POST",
                        url: "./api/payment?changePaymentMethod",
                        data: {
                            payment_method: $(this).val()
                        },
                        success: (success) => {
                            if (success) {
                                console.log(success);
                            }
                        }
                    })
                    confirmPayment.prop("disabled", false);
                }
            }

            $("[name='payment_method']").on("change", checkPaymentMethod);
            $("[name='payment_method']").each(checkPaymentMethod)


        });
    </script>

</body>

</html>