const getBookings = () => {
    const bookingsBox = $("[data-booking-box]");
    const addtoBookingBtn = $("[data-addto-booking-button]");
    $.ajax({
        url: "../api/booking.php?getBookings",
        type: "GET",
        success: (response) => {
            const json = JSON.parse(response);
            let html = ``;
            if (json.length > 0) {
                json.map(({ place_id, name }, index) => {
                    addtoBookingBtn.map((eIndex, e) => {
                        if ($(e).data().addtoBookingButton == place_id) {
                            $(e).hide();
                        }
                    });
                    html += `
                    <div class="d-flex align-items-center gap-2 justify-content-between" data-place_id="${place_id}" >
                        <div class="d-flex align-items-start gap-2">
                            <div class="d-flex align-items-center justify-content-center bg-teal rounded-circle text-white fw-bold fs-5" style="width:40px;height:40px;">${index + 1}</div>
                            <div>
                                <h4>${name}</h4>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="time" value="08:00" class="form-control" name="start_time[]">
                                    <input type="time" value="09:00" class="form-control" name="end_time[]">
                                </div>
                            </div>
                        </div>
                        <button onclick="removeFromBookings(${place_id})" type="button" class="btn btn-danger">ลบ</button>
                    </div>
                    `;
                })
            } else {
                html += `<h6 class="text-center text-muted">ยังไม่มีรายการสถานที่ในขณะนี้ ทำการเพิ่มเลย...</h6>`
            }
            bookingsBox.html(html);
        }
    });
}

const addToBookings = (place_id) => {
    $.ajax({
        url: "../api/booking.php?addToBookings",
        type: "POST",
        data: { place_id },
        success: (response) => {
            getBookings();
        }
    })
}

const removeFromBookings = (place_id) => {
    const addtoBookingBtn = $("[data-addto-booking-button]");
    $.ajax({
        url: "../api/booking.php?removeFromBookings",
        type: "POST",
        data: { place_id },
        success: (response) => {
            getBookings();
            addtoBookingBtn.map((eIndex, e) => {
                if ($(e).data().addtoBookingButton == place_id) {
                    $(e).show();
                }
            })
        }
    });
}

$(document).ready(() => {
    getBookings();
})
