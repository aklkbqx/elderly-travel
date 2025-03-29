<?php
require_once("config.php");
require_once("components/components.php");
$row = null;
if (isset($_SESSION["user_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
} elseif (isset($_SESSION["admin_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "admin/");
} elseif (isset($_SESSION["doctor_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "doctor");
}

$booking = null;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Travel</title>
    <?php require_once("link.php"); ?>
</head>

<body>

    <?php
    require_once("components/nav.php");
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class="image-header">
        <div class="d-flex flex-wrap align-items-start justify-content-xxl-between justify-content-center w-100 container gap-4">
            <div class="hero-elderly-travel" style="z-index:1">
                <div>เว็บไซต์สำหรับผู้สูงอายุ</div>
                <h1>ELDERLY TRAVEL</h1>
                <p>"เอลเดอร์ลี่ แทรเวล" ให้คุณได้สัมผัสประสบการณ์การท่องเที่ยวเชิงสุขภาพ</p>
            </div>
            <?php
            if (isLogin()) {
                $fetchBooking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$row["user_id"]]);
                if ($fetchBooking->rowCount() > 0) {
                    $label = [];
                    $booking = $fetchBooking->fetch();
                    $payment = sql("SELECT * FROM payments WHERE user_id = ? AND booking_id = ?", [$row["user_id"], $booking["booking_id"]])->fetch();
                    if ($payment) {
                        if ($payment["status"] == "PAID") {
                            $label = [
                                "header" => "ติดตามสถานะการเดินทาง",
                                "button" => [
                                    "label" => "ตรวจสอบ",
                                    "link" => "my-booking"
                                ]
                            ];
                        } elseif ($payment["status"] == "PENDING") {
                            $label = [
                                "header" => "ดำเนินการชำระเงินให้เสร็จสมบูรณ์",
                                "button" => [
                                    "label" => "ชำระเงิน",
                                    "link" => "payment"
                                ]
                            ];
                        }
                    } else {
                        $label = [
                            "header" => "ดำเนินการจองให้เสร็จสมบูรณ์",
                            "button" => [
                                "label" => "ดำเนินการต่อ",
                                "link" => "booking"
                            ]
                        ];
                    }
            ?>
                    <div class="shadow mt-2 mt-xl-0 booking-card mb-2 align-items-start" style="border-radius:1.5rem;z-index:1;">
                        <h3 class="text-center d-flex align-items-center justify-content-center w-100"><?= $label["header"] ?></h3>
                        <div class="d-flex flex-column gap-2 my-4 text-muted">
                            <h5>วันที่เช็คอิน: <?= formatThaiDate($booking["start_date"]) ?></h5>
                            <h5>วันที่เช็คเอาท์: <?= formatThaiDate($booking["end_date"]) ?></h5>
                            <h5>จำนวนคน: <?= $booking["people"] ?></h5>
                        </div>
                        <div class='w-100 gap-2 d-flex align-items-center'>
                            <?php if ($payment) {
                                if ($payment["status"] == "PENDING") { ?>
                                    <button type='button' data-bs-toggle="modal" data-bs-target="#cancelBooking" class='btn btn-danger w-100'>ยกเลิกการจอง</button>
                                <?php }
                            } else { ?>
                                <button type='button' data-bs-toggle="modal" data-bs-target="#cancelBooking" class='btn btn-danger w-100'>ยกเลิกการจอง</button>
                            <?php } ?>
                            <a href="<?= $label["button"]["link"] ?>" class='btn btn-teal w-100'><?= $label["button"]["label"] ?></a>
                        </div>
                    </div>
            <?php } else {
                    formSelectDateBooking();
                }
            } else {
                formSelectDateBooking();
            } ?>
        </div>
    </div>

    <?php cancelBooking($booking) ?>

    <div class="container position-relative suggest-web">
        <div class="row bg-teal p-4 rounded-xl d-flex mx-2" style="transform: translateY(-80px);">
            <div class="col-lg-4 mb-2 mb-lg-0">
                <div class="d-flex align-items-center gap-2 rounded-xl p-2 text-white" style="background-color:var(--teal2);">
                    <img src="<?= imagePath("web_images", "chats.png") ?>" width="100px" height="100px" />
                    <div>การสนทนาแบบ Realtime และแชทบอทปรึกษาตลอด 24 ชม.</div>
                </div>
            </div>
            <div class="col-lg-4 mb-2 mb-lg-0">
                <div class="d-flex align-items-center gap-2 rounded-xl p-2 text-white" style="background-color: var(--teal2);">
                    <img src="<?= imagePath("web_images", "plane.png") ?>" width="100px" height="100px" />
                    <div>วางแผนการท่องเที่ยวเชิงสุขภาพ เหมาะสำหรับผู้สูงอายุ</div>
                </div>
            </div>
            <div class="col-lg-4 mb-2 mb-lg-0">
                <div class="d-flex align-items-center gap-2 rounded-xl p-2 text-white" style="background-color: var(--teal2);">
                    <img src="<?= imagePath("web_images", "paper.png") ?>" width="100px" height="100px" />
                    <div>ตั้งกระทู้ กระดานสนทนา โต้ตอบแลกเปลี่ยนประสบการณ์ ความคิดเห็น</div>
                </div>
            </div>
        </div>

        <div>
            <?php $fetchAllNews = sql("SELECT * FROM news");
            if ($fetchAllNews->rowCount() > 0) { ?>
                <div class="d-flex align-items-end justify-content-between">
                    <div>
                        <div class="text-teal">News</div>
                        <h1>ข่าวสารประชาสัมพันธ์</h1>
                    </div>
                    <a href="news" class="btn btn-outline-teal">ดูเพิ่มเติม</a>
                </div>
                <div class="carousel slide mt-2" id="carouselSlide" data-bs-ride="carousel" style="z-index: 1;">
                    <div class="carousel-inner rounded-xl overflow-hidden">
                        <?php
                        $index = 1;
                        while ($news = $fetchAllNews->fetch()) { ?>
                            <a href='news?news_id=<?= $news["news_id"] ?>' class="carousel-item btn btn-outline-teal p-0 <?= $index == 1 ? 'active' : null ?>" data-bs-intervel="1000">
                                <img src="<?= imagePath("news_images", $news["image"]) ?>" alt="" class="rounded-xl object-fit-cover" height="500px" width="100%">
                                <div class="position-absolute" style="bottom:3rem;left:5%;z-index:99">
                                    <h3 class="text-white"><?= $news["title"] ?></h3>
                                </div>
                                <div class="position-absolute d-flex align-items-center gap-2" style="bottom:0rem;right:1rem;z-index:99">
                                    <img src="<?= imagePath("web_images/icons", "eye.svg") ?>" width="35px" height="35px" style='filter: invert(1);'>
                                    <div class='text-white'>จำนวนผู้เข้าชม <span><?= $news["visitors"] ?></span></div>
                                </div>
                                <div style='height: 500px;width: 100%;top: 0;left: 0;background-image: linear-gradient(1deg, #000000a8, transparent);' class="position-absolute"></div>
                            </a>
                        <?php $index++;
                        } ?>
                    </div>
                    <button type="button" data-bs-target="#carouselSlide" class="carousel-control-prev" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon"></span>
                        <span class="visually-hidden"></span>
                    </button>
                    <button type="button" data-bs-target="#carouselSlide" class="carousel-control-next" data-bs-slide="next">
                        <span class="carousel-control-next-icon"></span>
                        <span class="visually-hidden"></span>
                    </button>
                </div>
            <?php } ?>
        </div>

        <?php $fetchAllAssessments = sql("SELECT * FROM assessments");
        if ($fetchAllAssessments->rowCount() > 0) { ?>
            <div class="text-center mt-5">
                <h1 class="mb-2">แบบสอบถาม/ประเมิน</h1>
                <?php while ($assessment = $fetchAllAssessments->fetch()) {
                    $assessment_responses = $row ? sql("SELECT * FROM assessment_responses WHERE assessment_id = ? AND user_id = ?", [$assessment["assessment_id"], $row["user_id"]])->fetch() : null;
                ?>
                    <a href='assessments?assessment_id=<?= $assessment["assessment_id"] ?>' class="btn w-100 p-4 justify-content-between flex-row d-flex shadow rounded-xl border align-items-center mb-2">
                        <div class="d-flex flex-column align-items-start gap-2">
                            <?php if ($assessment_responses) { ?>
                                <div class="badge text-bg-success fw-bold fs-6">ทำแล้ว</div>
                            <?php } ?>
                            <h5><?= $assessment["title"] ?></h5>
                            <div class="d-flex align-items-center gap-2">
                                <div class="d-flex flex-row gap-2 align-items-center text-muted">
                                    <img src="<?= imagePath("web_images/icons", "eye.svg") ?>" alt="" width="25px" height="25px" class="object-fit-cover svg-icon">
                                    จำนวนผู้เข้าชม <span><?= $assessment["visitors"] ?></span>
                                </div>
                                <div class="d-flex flex-row gap-2 align-items-center text-muted">
                                    <img src="<?= imagePath("web_images/icons", "peoples.svg") ?>" alt="" width="25px" height="25px" class="object-fit-cover svg-icon">
                                    ผู้ทำแบบประเมิน <span><?= sql("SELECT COUNT(*) as count FROM assessment_responses")->fetch()["count"] ?></span>
                                </div>
                            </div>
                        </div>
                        <img src="<?= imagePath("web_images/icons", "chevron-forward.svg") ?>" alt="" width="40px" height="40px" class="object-fit-cover svg-icon">
                    </a>
                <?php } ?>
            </div>
        <?php } ?>
    </div>

    <div class="bg-teal w-100 mt-5 banner-footer position-relative"></div>

    <?php require_once("components/footer.php"); ?>

    <script>
        const navbar = $("#navbar #navbar-bg");
        navbar.css({
            'backdrop-filter': 'blur(10px)',
            'background-image': 'linear-gradient(0deg, transparent, transparent)'
        });
        $(window).scroll(function() {
            if ($(this).scrollTop() > 100) {
                navbar.css({
                    'backdrop-filter': 'blur(10px)',
                    'background-image': 'linear-gradient(0deg,rgba(44, 128, 122, 0.55), rgba(44, 128, 122, 0.5))'
                });
            } else {
                navbar.css({
                    'backdrop-filter': 'blur(10px)',
                    'background-image': 'linear-gradient(0deg, transparent, transparent)'
                });
            }
        });
        $(document).ready(() => {
            setTimeout(async () => {
                $.ajax({
                    url: "api/visitors",
                    type: "POST",
                    success: (response) => {
                        console.log(response);
                    }
                })
            }, 5000);
        })
    </script>
</body>

</html>