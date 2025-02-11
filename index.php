<?php 
require "config.php";
$row = null;
if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","admin/");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Elderly Travel</title>
    <?php require "link.php"; ?>
</head>
<body>
    <?php 
        require "components/nav.php";
        require_once("components/popChats.php");
        require_once("components/options.php");
    ?>

    <div class="image-header">
        <div class="d-flex flex-wrap align-items-start justify-content-xxl-between justify-content-center w-100 container px-5">
            <div class="d-flex flex-column text-white mb-5 mb-lg-0">
                <div style="font-size:20px">เว็บไซต์สำหรับผู้สูงอายุ</div>
                <div style="font-size:100px" class="text-white fw-bold">Elderly Travel</div>
                <div style="font-size:20px" class="fst-italic">"เอลเดอร์ลี่ แทรเวล" ให้คุณได้สัมผัสประสบการณ์การท่องเที่ยวเชิงสุขภาพ</div>
            </div>
            <div class="bg-white shadow p-4 d-flex flex-column align-items-center mt-5 mt-xl-0" style="width:500px;border-radius:1.5rem">
                <h3 class="">จองทริปการท่องเที่ยว</h3>
                <div class="d-flex align-items-center gap-4 w-100 mt-2">
                    <div class="form-floating w-100">
                        <input id="เช็คอิน" type="date" class="form-control rounded-xl" name=''>
                        <label for="เช็คอิน">เช็คอิน</label>
                    </div>
                    <div class="form-floating w-100">
                        <input id="เช็คเอาท์" type="date" class="form-control rounded-xl" name=''>
                        <label for="เช็คเอาท์">เช็คเอาท์</label>
                    </div>
                </div>
                <div class="my-5">
                    <div class="text-center">จำนวนคน</div>
                    <div class="d-flex align-items-center justify-content-between gap-2">
                        <button onclick="" class="btn btn-light rounded-xl fs-5 border">-</button>
                        <input id="people" type="number" value='1' min="1" max='100' class='form-control text-center' name='people'>
                        <button onclick="" class="btn btn-light rounded-xl fs-5 border">+</button>
                    </div>
                </div>
                
                <a href="<?= isset($_SESSION["user_login"]) ? 'booking.php' : 'login.php' ?>" class="btn btn-teal w-100 py-3">
                    <?= isset($_SESSION["user_login"]) ? 'ดำเนินการต่อ' : 'ดำเนินการต่อ' ?>
                </a>
            </div>
        </div>
    </div>

    <div class="bg-white">
        <div class="container position-relative">
            <div class="row bg-teal p-4 rounded-xl d-none d-lg-flex" style="transform: translateY(-80px);">
                <div class="col-lg-4 mb-2 mb-lg-0">
                    <div class="d-flex align-items-center gap-2 rounded-xl p-2 text-white" style="background-color: #37706c;">
                        <img src="<?= imagePath("web_images/icons","chats.png") ?>" width="100px" height="100px" />
                        <div>การสนทนาแบบ Realtime และแชทบอทปรึกษาตลอด 24 ชม.</div>
                    </div>
                </div>
                <div class="col-lg-4 mb-2 mb-lg-0">
                    <div class="d-flex align-items-center gap-2 rounded-xl p-2 text-white" style="background-color: #37706c;">
                        <img src="<?= imagePath("web_images/icons","plane.png") ?>" width="100px" height="100px" />
                        <div>วางแผนการท่องเที่ยวเชิงสุขภาพ เหมาะสำหรับผู้สูงอายุ</div>
                    </div>
                </div>
                <div class="col-lg-4 mb-2 mb-lg-0">
                    <div class="d-flex align-items-center gap-2 rounded-xl p-2 text-white" style="background-color: #37706c;">
                        <img src="<?= imagePath("web_images/icons","paper.png") ?>" width="100px" height="100px" />
                        <div>ตั้งกระทู้ กระดานสนทนา โต้ตอบแลกเปลี่ยนประสบการณ์ ความคิดเห็น</div>
                    </div>
                </div>
            </div>

            <div>
                <?php $fetchAllNews = sql("SELECT * FROM news");
                    if($fetchAllNews->rowCount() > 0){ ?>
                        <div class="d-flex align-items-center justify-content-between">
                            <div>
                                <div class="text-teal">News</div>
                                <h1>ข่าวสารประชาสัมพันธ์ล่าสุด</h1>
                            </div>
                            <a href="news.php" class="btn btn-outline-teal">ดูเพิ่มเติม</a>
                        </div>
                        <div class="carousel slide mt-2" id="carouselSlide" data-bs-ride="carousel">
                            <div class="carousel-inner rounded-xl overflow-hidden">
                                <?php
                                $index=1;
                                while($news = $fetchAllNews->fetch()){ ?>
                                    <a href='news.php?news_id=<?= $news["news_id"] ?>' class="carousel-item btn btn-outline-teal p-0 <?= $index==1 ? 'active' : null ?>" data-bs-intervel="1000">
                                        <img src="<?= imagePath("news_images",$news["image"]) ?>" alt="" class="rounded-xl object-fit-cover" height="500px" width="100%">
                                        <div class="position-absolute" style="bottom:2rem;left:2rem;z-index:99">
                                            <h3 class="text-white"><?= $news["title"] ?></h3>
                                        </div>
                                        <div class="position-absolute d-flex align-items-center gap-2" style="bottom:2rem;right:2rem;z-index:99">
                                            <img src="<?= imagePath("web_images/icons","eye.png") ?>" width="35px" height="35px" style='filter: invert(1);'>
                                            <div class='text-white'>จำนวนผู้เข้าชม <span><?= $news["visitors"] ?></span></div>
                                        </div>
                                        <div style='height: 500px;width: 100%;top: 0;left: 0;background-image: linear-gradient(1deg, #000000a8, transparent);' class="position-absolute"></div>
                                    </a>
                                <?php $index++; } ?>
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
            if($fetchAllAssessments->rowCount() > 0){ ?>
                <div class="text-center mt-5">
                    <h1 class="mb-2">แบบสอบถาม/ประเมิน</h1>
                    <?php while($assessment = $fetchAllAssessments->fetch()){
                        $assessment_responses = $row ? sql("SELECT * FROM assessment_responses WHERE assessment_id = ? AND user_id = ?",[$assessment["assessment_id"],$row["user_id"]])->fetch() : null;
                        ?>
                        <a href='assessments.php?assessment_id=<?= $assessment["assessment_id"] ?>' class="btn btn-light w-100 p-4 justify-content-between flex-row d-flex shadow rounded-xl border align-items-center mb-2">
                            <div class="d-flex flex-column align-items-start gap-2">
                                <?php if($assessment_responses){ ?>
                                    <div class="badge text-bg-success fw-bold fs-6">ทำแล้ว</div>
                                <?php } ?>
                                <h5><?= $assessment["title"] ?></h5>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="d-flex flex-row gap-2 align-items-center text-muted">
                                        <img src="<?= imagePath("web_images/icons","eye.png") ?>" alt="" width="25px" height="25px" class="object-fit-cover">
                                        จำนวนผู้เข้าชม <span><?= $assessment["visitors"] ?></span>
                                    </div>
                                    <div class="d-flex flex-row gap-2 align-items-center text-muted">
                                        <img src="<?= imagePath("web_images/icons","peoples.png") ?>" alt="" width="25px" height="25px" class="object-fit-cover">
                                        ผู้ทำแบบประเมิน <span><?= sql("SELECT COUNT(*) as count FROM assessment_responses")->fetch()["count"] ?></span>
                                    </div>
                                </div>
                            </div>
                            <img src="<?= imagePath("web_images/icons","chevron-forward.png") ?>" alt="" width="40px" height="40px" class="object-fit-cover">
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
    </div>

    <div class="bg-teal w-100 mt-5" style="height:500px;background-image:url('<?= imagePath('web_images','banner-footer.jpg') ?>');background-repeat: no-repeat;background-position: center;background-size: cover;"></div>

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
                    'background-image': 'linear-gradient(0deg, #1d5e5a80, #1d5e5a95)'
                });
            } else {
                navbar.css({
                    'backdrop-filter': 'blur(10px)',
                    'background-image': 'linear-gradient(0deg, transparent, transparent)'
                });
            }
        });
        $(document).ready(()=>{
            setTimeout(async () => {
                $.ajax({
                    url:"api/visitors.php",
                    type: "POST",
                    success: (response)=>{
                        console.log(response);
                    }
                })
            }, 5000);
        })
    </script>
</body>
</html>