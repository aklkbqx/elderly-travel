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

        $startDate = new DateTime($checkInDate);
        $endDate = new DateTime($checkOutDate);
        $interval = $startDate->diff($endDate);
        $daysCount = $interval->days + 1;

        $bookingDetails = [];
        for ($i = 0; $i < $daysCount; $i++) {
            $currentDate = clone $startDate;
            $currentDate->modify("+$i day");
            $formattedDate = $currentDate->format('Y-m-d');
            $bookingDetails[$formattedDate] = [];
        }

        $insert = sql("INSERT INTO bookings(user_id, booking_details, people, booking_date, start_date, end_date) VALUES(?, ?, ?, ?, ?, ?)", [
            $row["user_id"],
            json_encode($bookingDetails),
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
    <title>จองสถานที่</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php
    require_once("components/nav.php");
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class='container p-0' style='margin-top: 10rem;'>
        <div>
            <?php backPage("/"); ?>
        </div>
        <?php if (!$isSelectedDate) { ?>
            <div class='mt-2'>
                <?php formSelectDateBooking(); ?>
            </div>
            <?php } else {
            function bookingComponent()
            {
                global $checkInDate, $checkOutDate, $people, $booking, $row;
            ?>
                <div class='row mx-2'>
                    <div class='col-lg-4 p-0 pe-sm-2'>
                        <div class='d-flex justify-content-center position-sticky' style="top:7rem">
                            <div class='shadow border mb-2 p-4 rounded-xl w-100 container'>
                                <h4 class="mb-2">เลือกวัน</h4>
                                <div class="mb-4 d-flex flex-column gap-2" id="show-all-date"></div>
                                <h4 class="mb-2">สถานที่ในรายการจองของคุณ</h4>
                                <div class='p-4 border rounded-xl d-flex flex-column gap-4'>
                                    <div id="booking-box"></div>
                                </div>
                                <div class="d-flex flex-column gap-2 mt-4">
                                    <h5>วันที่เช็คอิน: <?= formatThaiDate($checkInDate) ?><input type="hidden" name='checkInDate' disabled value="<?= $checkInDate ?>" class="border-0 bg-transparent"></h5>
                                    <h5>วันที่เช็คเอาท์: <?= formatThaiDate($checkOutDate) ?><input type="hidden" name='checkOutDate' disabled value="<?= $checkOutDate ?>" class="border-0 bg-transparent"></h5>
                                    <h5>จำนวนคน: <span id="peopleCount"><?= $people ?></span></h5>
                                    <h5 id="totalPrice">ราคารวมทั้งหมด: <span>0.00</span></h5>
                                </div>
                                <div class="d-flex align-items-center gap-2">
                                    <button type='button' data-bs-toggle="modal" data-bs-target="#cancelBooking" class='btn btn-danger flex-grow-1 mt-2 p-3 fs-5'>ยกเลิกการจอง</button>
                                    <button type="button" id="continuteBooking" data-booking-id="<?= $booking['booking_id'] ?>" class='btn btn-teal mt-2 p-3 fs-5 flex-grow-1' disabled>ดำเนินการต่อ</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class='col-lg-8 p-0'>
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
                </div>
                <?php }
            $payment = sql("SELECT * FROM payments WHERE user_id = ? AND booking_id = ?", [$row["user_id"], $booking["booking_id"]])->fetch();
            if ($payment) {
                if ($payment["status"] == "PAID" || $booking["status"] != "PENDING") { ?>
                    <h3 class="text-center">เกิดข้อผิดพลาด ได้โปรดตรวจสอบ <a href="my-booking.php">รายการจองของคุณ</a></h3>
        <?php return;
                }
                bookingComponent();
            } else {
                bookingComponent();
            }
        } ?>
    </div>

    <?php cancelBooking($booking) ?>

    <script>
        const continuteBooking = $("#continuteBooking");

        const getBookings = () => {
            const bookingsBox = $("#booking-box");
            const totalPriceElement = $("#totalPrice");
            const showAllDate = $("#show-all-date");
            const peopleCount = Number($("#peopleCount").text());

            $.ajax({
                url: "../api/booking.php?getBookings",
                type: "GET",
                success: (response) => {
                    const json = JSON.parse(response);
                    if (json.length <= 0) {
                        return;
                    }

                    let showAllDateHtml = ``;
                    let totalPrice = 0;
                    let autoincrement = 0;

                    for (const date of Object.keys(json)) {
                        showAllDateHtml += `<label for="date-index-${autoincrement}" class="btn border w-100 d-flex justify-content-start align-items-center gap-2 p-3 gap-2">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" id="date-index-${autoincrement}" value="${date}" name="select-date" onchange="toggleBookingsForDate('${date}')" />
                        <div>${date}</div>
                    </div>
                </label>`;
                        autoincrement++;
                    }

                    showAllDate.html(showAllDateHtml);

                    for (const date of Object.keys(json)) {
                        json[date].forEach(({
                            price
                        }) => {
                            totalPrice += parseFloat(price) * peopleCount;
                        });
                    }

                    const selectDate = $("[name='select-date']");
                    selectDate.on("change", function() {
                        for (const [date, bookings] of Object.entries(json)) {
                            if (json[date].length > 0) {
                                continuteBooking.prop("disabled", false);
                            } else {
                                continuteBooking.prop("disabled", true);
                            }

                            let bookingsBoxHtml = ``;
                            if ($(this).val() == date) {
                                if (bookings.length > 0) {
                                    const existingPlaceIds = bookings.map(booking => booking.place_id);

                                    bookings.forEach(({
                                        place_id,
                                        name,
                                        start_time,
                                        end_time,
                                        price
                                    }) => {
                                        bookingsBoxHtml += `
                                <div class='mb-2'>
                                    <h5 class='mb-2'>${name}</h5>
                                    <div class="d-flex align-items-end gap-2 justify-content-between flex-wrap" data-place_id="${place_id}">
                                        <div class="d-flex align-items-start gap-2">
                                            <div class="d-flex align-items-center gap-2">
                                                <input type="time" value="${start_time}" class="form-control" name="start_time[]" onchange="validateAndUpdateTime(${place_id}, this, 'start_time','${date}')">
                                                <input type="time" value="${end_time}" class="form-control" name="end_time[]" onchange="validateAndUpdateTime(${place_id}, this, 'end_time','${date}')">
                                            </div>
                                        </div>
                                        <button class="btn btn-danger" onclick="removeFromBookings('${date}', ${place_id})">ลบ</button>
                                    </div>
                                </div>
                                `;
                                    });

                                    bookingsBox.html(bookingsBoxHtml);

                                    $("[data-addPlaceId]").each(function() {
                                        const placeId = $(this).data("addplaceid");
                                        if (existingPlaceIds.includes(placeId)) {
                                            $(this).hide();
                                        } else {
                                            $(this).show();
                                        }
                                    });
                                } else {
                                    bookingsBox.html(`
                            <div class='text-center'>ยังไม่มีรายการจองของคุณ</div>
                            `);

                                    $("[data-addPlaceId]").show();
                                }
                            }
                        }
                    });

                    if (localStorage.getItem("selectDate")) {
                        selectDate.each(function() {
                            if ($(this).val() == localStorage.getItem("selectDate")) {
                                $(this).prop("checked", true).trigger("change");
                            }
                        })

                    } else {
                        selectDate.first().prop("checked", true).trigger("change");
                        localStorage.setItem("selectDate", selectDate.first().val());
                    }

                    totalPriceElement.find("span").text(totalPrice.toFixed(2));
                }
            });
        }

        continuteBooking.click(function() {
            const bookingId = $(this).data("bookingId");
            $.ajax({
                type: "POST",
                url: `../api/payment.php?continueTopayment&booking_id=${bookingId}`,
                success: (response) => {
                    const {
                        success
                    } = JSON.parse(response);
                    if (success) {
                        window.location = `./payment.php`;
                    }
                }
            })
        });

        const addtoBookingButton = $('[data-addPlaceId]');
        addtoBookingButton.click(function() {
            addToBookings($(this).data().addplaceid);
            $(this).hide()
        })

        const addToBookings = (place_id) => {
            const selectedDate = $("input[name='select-date']:checked").val();
            if (!selectedDate) {
                alert("กรุณาเลือกวันที่ที่ต้องการเพิ่มสถานที่");
                return;
            }

            $.ajax({
                url: "../api/booking.php?addToBookings",
                type: "POST",
                data: {
                    place_id,
                    date: selectedDate
                },
                success: (response) => {
                    getBookings();
                }
            });
        }

        const removeFromBookings = (date, place_id) => {
            $.ajax({
                url: "../api/booking.php?removeFromBookings",
                type: "POST",
                data: {
                    date,
                    place_id
                },
                success: (response) => {
                    getBookings();
                },
                error: (error) => {
                    console.error("Error removing booking:", error);
                }
            });
        }

        const updateBookingTime = (place_id, time, type, date) => {
            $.ajax({
                url: "../api/booking.php?updateBookingTime",
                type: "POST",
                data: {
                    place_id,
                    time,
                    type,
                    date
                },
                success: (response) => {
                    console.log(response);
                },
                error: (error) => {
                    console.error("Error updating time:", error);
                }
            });
        }

        const toggleBookingsForDate = (date) => {
            $(".bookings-list").hide();
            $(`#bookings-${date}`).show();
            localStorage.setItem("selectDate", date);
        }

        const validateAndUpdateTime = (place_id, input, type, date) => {
            const time = input.value;
            const previousEndTime = $(input).closest('div[data-place_id]').prev().find('input[name="end_time[]"]').val();

            if (type === 'start_time' && previousEndTime && time <= previousEndTime) {
                alert("เวลาเริ่มต้นต้องมากกว่าเวลาสิ้นสุดของสถานที่ก่อนหน้า");
                input.value = previousEndTime;
                return;
            }

            updateBookingTime(place_id, time, type, date);
        }

        const confirmCancelBooking = $("#confirmCancelBooking");
        confirmCancelBooking.click(() => {
            if (localStorage.getItem("selectDate")) {
                localStorage.removeItem("selectDate");
            }
        })

        $(document).ready(() => {
            getBookings();
        })
    </script>
</body>

</html>