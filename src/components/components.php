<?php
require_once("svg.php");
function formSelectDateBooking()
{ ?>
    <form action="booking.php" method="post" class="shadow mt-2 mt-xl-0 booking-card mb-2" style="border-radius:1.5rem;z-index:1">
        <h3>จองทริปการท่องเที่ยว</h3>
        <div class="d-flex align-items-center gap-4 w-100 mt-2 flex-warp flex-column flex-lg-row">
            <div class="form-floating w-100">
                <input id="เช็คอิน" type="date" class="form-control rounded-xl" name='checkInDate'>
                <label for="เช็คอิน">เช็คอิน</label>
            </div>
            <div class="form-floating w-100">
                <input id="เช็คเอาท์" type="date" class="form-control rounded-xl" name='checkOutDate'>
                <label for="เช็คเอาท์">เช็คเอาท์</label>
            </div>
        </div>
        <div class="my-3">
            <div class="text-center">จำนวนคน</div>
            <div class="d-flex align-items-center justify-content-between gap-2">
                <button type='button' onclick="chanagePeople('minus')" class="btn btn-light rounded-xl fs-5 border">-</button>
                <input type="number" value='1' min="1" max='100' class='form-control text-center' name='people'>
                <button type='button' onclick="chanagePeople('plus')" class="btn btn-light rounded-xl fs-5 border">+</button>
            </div>
        </div>

        <?php
        if (isLogin()) { ?>
            <button type='submit' class="btn btn-teal w-100 py-3" name='selectedDate' disabled>
                ดำเนินการต่อ
            </button>
        <?php } else { ?>
            <a href="login.php" class="btn btn-teal w-100 py-3">
                เข้าสู่ระบบ
            </a>
        <?php } ?>

        <script>
            const selectedDate = $("[name=selectedDate]");
            const checkInDate = $("[name=checkInDate]");
            const checkOutDate = $("[name=checkOutDate]");
            const people = $("[name=people]");

            const checkInput = () => {
                if (checkInDate.val() == "" || checkOutDate.val() == "" || people.val() == 0) {
                    selectedDate.attr("disabled", true);
                } else {
                    selectedDate.attr("disabled", false);
                }
            }

            const chanagePeople = (type) => {
                if (type === "minus") {
                    if (people.val() > 1) {
                        people.val(Number(people.val()) - 1)
                    }
                } else if (type === "plus") {
                    people.val(Number(people.val()) + 1)
                }
            }

            checkInDate.on("change", () => {
                checkInput();
            })
            checkOutDate.on("change", () => {
                checkInput();
            })
            people.on("input", (e) => {
                checkInput();
            })
        </script>
    </form>
<?php }

function placeCardBooking($place, $booked = false, $user_id = 0)
{
?>
    <div class='w-100 border shadow rounded-xl overflow-hidden mb-2 d-md-flex'>
        <div class="col-lg-4">
            <img src="<?= imagePath("place_images", json_decode($place["images"])[0]) ?>" alt="" width="100%" height="250px" class='object-fit-cover'>
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
            <div class="d-flex gap-2 align-items-center mt-auto">
                <?php if ($booked) { ?>
                    <button type='button' data-addPlaceId='<?= $place["place_id"]; ?>' class='btn btn-teal rounded-xl w-100'>เพิ่มลงในรายการจอง</button>
                    <?php } else {
                    $fetchBooking = sql("SELECT * FROM bookings WHERE user_id = ? AND status = 'PENDING'", [$user_id]);
                    if ($fetchBooking->rowCount() === 1) { ?>
                        <a href='booking.php' class='btn btn-teal border w-100'>ดำเนินการจองต่อ</a>
                    <?php } else { ?>
                        <a href='booking.php' class='btn btn-teal border w-100'>จอง</a>
                    <?php }
                    ?>
                <?php } ?>
                <a href="places.php?place_id=<?= $place["place_id"] ?>" class='btn btn-secondary rounded-xl w-100'>รายละเอียด</a>
            </div>
        </div>
    </div>
<?php }

function cancelBooking($booking)
{ ?>
    <div class="modal fade" id='cancelBooking'>
        <div class="modal-dialog modal-dialog-centered">
            <form action="api/booking.php?cancel_booking" class="modal-content" method='post' enctype='multipart/form-data'>
                <div class="modal-header">
                    <h4 class="modal-title">ยกเลิกการจอง</h4>
                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>
                <div class="modal-body">
                    <h4 class='text-center'>แน่ใจที่จะยกเลิกการจองของคุณ</h4>
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
<?php }

?>