
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

            // Calculate total price across all dates
            for (const date of Object.keys(json)) {
                json[date].forEach(({ price }) => {
                    totalPrice += parseFloat(price) * peopleCount;
                });
            }

            const selectDate = $("[name='select-date']");
            selectDate.on("change", function () {
                const continuteBooking = $("#continuteBooking");
                for (const [date, bookings] of Object.entries(json)) {
                    if(json[date].length > 0){
                        continuteBooking.prop("disabled",false);
                    }else{
                        continuteBooking.prop("disabled",true);
                    }

                    let bookingsBoxHtml = ``;
                    if ($(this).val() == date) {
                        if (bookings.length > 0) {
                            const existingPlaceIds = bookings.map(booking => booking.place_id);

                            bookings.forEach(({ place_id, name, start_time, end_time, price }) => {
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
                selectDate.each(function () {
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

const addtoBookingButton = $('[data-addPlaceId]');
addtoBookingButton.click(function () {
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
        data: { place_id, date: selectedDate },
        success: (response) => {
            getBookings();
        }
    });
}

const removeFromBookings = (date, place_id) => {
    $.ajax({
        url: "../api/booking.php?removeFromBookings",
        type: "POST",
        data: { date, place_id },
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
        data: { place_id, time, type, date },
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

// const showBookingsForDate = (date) => {
//     const bookingsList = $(`#bookings-${date}`);
//     bookingsList.toggle();

//     if (bookingsList.is(':visible')) {
//         bookingsList.find('div[data-place_id]').each(function () {
//             const placeId = $(this).data('place_id');
//             $.ajax({
//                 url: `../api/booking.php?getPlaceDetails&place_id=${placeId}`,
//                 type: "GET",
//                 success: (response) => {
//                     const placeDetails = JSON.parse(response);
//                     $(this).append(`
//                         <div>
//                             <p>รายละเอียด: ${placeDetails.description}</p>
//                             <p>ราคา: ${placeDetails.price}</p>
//                         </div>
//                     `);
//                 }
//             });
//         });
//     }
// }

// const checkPlacesAdded = (bookings) => {
//     const selectedDate = $("input[name='selectDate']:checked").val();
//     if (selectedDate) {
//         const places = bookings[selectedDate] || [];
//         places.forEach(({ place_id }) => {
//             $(`[data-addto-booking-button='${place_id}']`).hide();
//         });
//     }
// }

$(document).ready(() => {
    getBookings();
})
