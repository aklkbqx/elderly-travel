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
    <title>สถานที่ท่องเที่ยว</title>
    <?php
    require_once("link.php");
    ?>
</head>

<body>
    <?php
    require_once("./components/nav.php");
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class='container' style='margin-top:10rem'>
        <?php backPage('javascript:window.history.back()'); ?>
        <div class='mb-5 mt-2'>
            <?php
            if (isset($_GET["place_id"])) {
                $place_id = $_GET["place_id"];
                $place = sql("SELECT * FROM places WHERE place_id = ?", [$place_id])->fetch();
                $place_reviews = sql("SELECT *,place_reviews.created_at as review_created_at FROM place_reviews LEFT JOIN users ON place_reviews.user_id = users.user_id WHERE place_id = ?", [$place["place_id"]]);

                $heart = "heart-black.png";
                if (isLogin()) {
                    $place_likes = sql("SELECT * FROM place_likes WHERE place_id = ? AND user_id = ?", [$place_id, $row["user_id"]]);
                    while ($place_like = $place_likes->fetch()) {
                        if ($place_like["user_id"] == $row["user_id"]) {
                            $heart = "heart-red.png";
                        } else {
                            $heart = "heart-black.png";
                        }
                    }
                } ?>

                <div class='p-2 rounded-xl shadow border mt-2'>
                    <img src="<?= imagePath("place_images", json_decode($place["images"])[0]) ?>" width='100%' height='500px' class='rounded-4 border object-fit-cover'>
                    <div class='p-2'>
                        <?php if (isset($_GET["booking"])) { ?>
                            <button type='button' class='btn btn-teal rounded-xl w-100'>เพิ่มลงในรายการจอง</button>
                        <?php } ?>
                        <h3 class='mt-2 mb-2'><?= $place["name"] ?></h3>
                        <div>📍 ที่อยู่: <?= $place["address"] ?></div>
                        <div>❤️ ผลต่อสุขภาพ: <?= $place["health"] ?></div>
                        <div>💰 ราคา: <?= $place["price"] ?></div>

                        <div class='d-flex justify-content-between mt-4 mb-2'>
                            <?php
                            if (isLogin()) { ?>
                                <a href="./api/place.php?like&place_id=<?= $place_id ?>" class='text-decoration-none d-flex align-items-center gap-1 btn btn-light'>
                                    <img src="<?= imagePath("web_images/icons", $heart) ?>" width='30px' height='30px' class='object-fit-cover'>
                                    <div class=''><?= sql("SELECT COUNT(*) as count FROM place_likes WHERE place_id = ?", [$place_id])->fetch()["count"] ?></div>
                                </a>
                            <?php } ?>
                            <div class='d-flex align-items-center gap-2'>
                                <img src="<?= imagePath("web_images/icons", "eye.svg") ?>" width='30px' height='30px' class='object-fit-cover svg-icon'>
                                <div>จำนวนผู้เข้าชม <span><?= $place["visitors"] ?></span></div>
                            </div>
                        </div>

                        <?php
                        if (sql("SELECT * FROM place_reviews WHERE user_id = ?", [$row["user_id"]])->rowCount() != 1) { ?>
                            <div class="mt-4" id="reviewSection">
                                <h5>ให้คะแนนและรีวิว</h5>
                                <div class="d-flex align-items-center gap-2 mt-2" id="starContainer">
                                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                                        <div class="btn-star" data-rating="<?= $i ?>">
                                            <svg width="35" height="35" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z" fill="#212529" />
                                            </svg>
                                        </div>
                                    <?php } ?>
                                </div>
                                <div id="commentForm" class="mt-3" style="display: none;">
                                    <form method="post" action="api/place.php?review&place_id=<?= $place_id ?>" class="d-flex flex-column gap-2">
                                        <input type="hidden" name="rating" id="ratingInput" value="0">
                                        <textarea name="comment" class="form-control" rows="3" placeholder="เขียนรีวิวของคุณที่นี่..."></textarea>
                                        <div class="d-flex gap-2">
                                            <button type="submit" name="sendReview" class="btn btn-teal">ส่งรีวิว</button>
                                            <button type="button" class="btn btn-secondary" id="cancelReview">ยกเลิก</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        <?php } ?>


                        <script>
                            $(document).ready(function() {
                                const $stars = $('.btn-star');
                                const $commentForm = $('#commentForm');
                                let selectedRating = 0;

                                $stars.hover(
                                    function() {
                                        const rating = $(this).data('rating');
                                        highlightStars(rating);
                                    },
                                    function() {
                                        highlightStars(selectedRating);
                                    }
                                );

                                $stars.click(function() {
                                    selectedRating = $(this).data('rating');
                                    highlightStars(selectedRating);
                                    $commentForm.slideDown(300);
                                    $('#ratingInput').val(selectedRating);
                                });

                                function highlightStars(rating) {
                                    $stars.each(function() {
                                        const $star = $(this);
                                        const $path = $star.find('svg path');
                                        const starRating = $star.data('rating');

                                        if (starRating <= rating) {
                                            $path.attr('fill', '#ffc107');
                                        } else {
                                            $path.attr('fill', '#212529');
                                        }
                                    });
                                }

                                $('#cancelReview').click(function() {
                                    $commentForm.slideUp(300);
                                    selectedRating = 0;
                                    highlightStars(0);
                                });

                                $commentForm.find('form').submit(function(e) {
                                    if (!selectedRating) {
                                        e.preventDefault();
                                        alert('กรุณาให้คะแนนดาวก่อนส่งรีวิว');
                                    }
                                });
                            });
                        </script>

                        <div class='mt-4'>
                            <?php
                            while ($review = $place_reviews->fetch()) { ?>
                                <div class='d-flex align-items-center justify-content-between'>
                                    <div class='d-flex align-items-start gap-2 mb-4'>
                                        <img src="<?= imagePath("user_images", $review["image"]); ?>" class='rounded-circle border object-fit-cover' width='50px' height='50px'>
                                        <div>
                                            <div class='d-flex align-items-center gap-2'>
                                                <h6 class='fw-bold'><?= $review["firstname"] ?> <?= $review["lastname"] ?></h6>
                                                <div><?= $review["review_created_at"] ?></div>
                                            </div>
                                            <?php for ($i = 1; $i <= 5; $i++) {
                                                $starColor = $i <= $review["rating"] ? "#FFD700" : "#212529";
                                            ?>
                                                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M9.15316 5.40838C10.4198 3.13613 11.0531 2 12 2C12.9469 2 13.5802 3.13612 14.8468 5.40837L15.1745 5.99623C15.5345 6.64193 15.7144 6.96479 15.9951 7.17781C16.2757 7.39083 16.6251 7.4699 17.3241 7.62805L17.9605 7.77203C20.4201 8.32856 21.65 8.60682 21.9426 9.54773C22.2352 10.4886 21.3968 11.4691 19.7199 13.4299L19.2861 13.9372C18.8096 14.4944 18.5713 14.773 18.4641 15.1177C18.357 15.4624 18.393 15.8341 18.465 16.5776L18.5306 17.2544C18.7841 19.8706 18.9109 21.1787 18.1449 21.7602C17.3788 22.3417 16.2273 21.8115 13.9243 20.7512L13.3285 20.4768C12.6741 20.1755 12.3469 20.0248 12 20.0248C11.6531 20.0248 11.3259 20.1755 10.6715 20.4768L10.0757 20.7512C7.77268 21.8115 6.62118 22.3417 5.85515 21.7602C5.08912 21.1787 5.21588 19.8706 5.4694 17.2544L5.53498 16.5776C5.60703 15.8341 5.64305 15.4624 5.53586 15.1177C5.42868 14.773 5.19043 14.4944 4.71392 13.9372L4.2801 13.4299C2.60325 11.4691 1.76482 10.4886 2.05742 9.54773C2.35002 8.60682 3.57986 8.32856 6.03954 7.77203L6.67589 7.62805C7.37485 7.4699 7.72433 7.39083 8.00494 7.17781C8.28555 6.96479 8.46553 6.64194 8.82547 5.99623L9.15316 5.40838Z" fill="<?= $starColor ?>" />
                                                </svg>
                                            <?php } ?>
                                            <div class='text-muted'><?= $review["comment"] ?></div>
                                        </div>
                                    </div>
                                    <?php if (isLogin() && ($review["user_id"] == $row["user_id"])) { ?>
                                        <div class='d-flex align-items-center gap-2'>
                                            <a href="api/place.php?delete_review&review_id=<?= $review["review_id"] ?>&place_id=<?= $place_id ?>" class='btn btn-danger'>ลบ</a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>

                <?php
                $fetchAllPlaces = sql("SELECT * FROM places WHERE NOT place_id = ?", [$_GET["place_id"]]);
                if ($fetchAllPlaces->rowCount() > 0) { ?>
                    <h5 class='mt-5 mb-2 fw-bold'>สถานที่ท่องเที่ยวอื่นๆ</h5>
                    <div class='list-group'>
                        <?php
                        while ($place = $fetchAllPlaces->fetch()) { ?>
                            <a href="place.php?place_id=<?= $place["place_id"] ?>" class='list-group-item list-group-item-action d-flex gap-2'>
                                <img src="<?= imagePath("place_images", json_decode($place["images"])[0]) ?>" width="330px" height="125px" class='object-fit-cover rounded-xl'>
                                <div>
                                    <div class='mb-2 fw-semibold fs-5'><?= $place["name"] ?></div>
                                    <div class='d-flex align-items-center gap-1'>
                                        <img src="<?= imagePath("web_images/icons", "eye.png") ?>" width='20px' height='20px' class='object-fit-cover'>
                                        <div>จำนวนผู้เข้าชม <span><?= $place["visitors"] ?></span></div>
                                    </div>
                                    <div class='d-flex align-items-center gap-1'>
                                        <img src="<?= imagePath("web_images/icons", "heart-red.png") ?>" width='20px' height='20px' class='object-fit-cover'>
                                        <div>จำนวนผู้ถูกใจ <span><?= sql("SELECT count(*) as count FROM place_likes WHERE place_id = ?", [$place['place_id']])->fetch()["count"]; ?></span></div>
                                    </div>
                                </div>
                            </a>
                        <?php } ?>
                    </div>
                <?php } ?>

                <script>
                    setTimeout(() => {
                        $.ajax({
                            url: "./api/place.php?visitors&place_id=<?= $place_id ?>",
                            type: "post"
                        })
                    }, 2000);
                </script>

                <?php } else {
                $fetchPlaces = sql("SELECT * FROM places");
                if ($fetchPlaces->rowCount() > 0) {
                    while ($place = $fetchPlaces->fetch()) {
                        placeCardBooking($place,false,$row ? $row["user_id"] : 0);
                    }
                } else { ?>
                    <h5 class='text-center text-muted'>ยังไม่มีสถานที่ในขณะนี้...</h5>
            <?php }
            } ?>
        </div>
    </div>
</body>

</html>