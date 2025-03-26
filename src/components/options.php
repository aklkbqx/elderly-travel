<style>
.slide-out {
    animation: slideOut 0.5s forwards;
}

.slide-in {
    animation: slideIn 0.5s forwards;
}

@keyframes slideOut {
    from {
        transform: translateX(0);
    }
    to {
        transform: translateX(100%);
    }
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
    }
    to {
        transform: translateX(0);
    }
}

.button-option {
    transition: transform 0.2s;
}
.button-option:hover {
    transform: scale(1.1);
}

</style>

<div class="position-fixed slide-in" style="bottom: 40%;right:0;z-index:99">
    <div class="d-flex flex-column gap-2 align-items-end rounded-xl">

        <button id="toggle-options" type='button' class="button-option btn rounded-xl p-0 py-2 me-2 shadow border-0" style='width: 40px;'>
            <svg xmlns="http://www.w3.org/2000/svg" width="20px" height="20px" viewBox="0 0 512 512" class="svg-icon" style='transition: transform 0.5s'><title>ionicons-v5-a</title><polyline points="184 112 328 256 184 400" style="fill:none;stroke:#000000;stroke-linecap:round;stroke-linejoin:round;stroke-width:48px"/></svg>
        </button>

        <div class="options-menu">
            <div class="d-flex justify-content-center">
                <button type="button" class="button-option btn fs-3 shadow border-0" id="toggleTheme"></button>
            </div>
            <div class="d-flex flex-column gap-2 p-2 rounded-4">
                <div class="d-flex justify-content-center">
                    <button data-zoom="100%" onclick="setZoom('100%')" type="button" class="button-option btn align-items-center flex-column shadow border-0">
                        <img src="<?= imagePath("web_images", 'normal.png') ?>" width='40px' height="20px" class="rounded-4 px-0 object-fit-cover">
                        (ปกติ)
                    </button>
                </div>
                <div class="d-flex justify-content-center">
                    <button data-zoom="125%" onclick="setZoom('125%')" type="button" class="button-option btn align-items-center flex-column shadow border-0">
                        <img src="<?= imagePath("web_images", 'medium.png') ?>" width='40px' height="20px" class="rounded-4 px-0 object-fit-cover">
                        (กลาง)
                    </button>
                </div>
                <div class="d-flex justify-content-center">
                    <button data-zoom="150%" onclick="setZoom('150%')" type="button" class="button-option btn align-items-center flex-column shadow border-0">
                        <img src="<?= imagePath("web_images", 'large.png') ?>" width='40px' height="20px" class="rounded-4 px-0 object-fit-cover">
                        (ใหญ่)
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>


<script>
    $(document).ready(function() {
        const $optionsDiv = $('.options-menu');
        const $toggleButton = $('#toggle-options');

        $toggleButton.on('click', function() {
            if ($optionsDiv.hasClass('slide-out')) {
                $optionsDiv.removeClass('slide-out').addClass('slide-in');
                $toggleButton.find("svg").css('transform', 'rotate(0deg)');
                localStorage.setItem('toggleOption', true);
                setTimeout(() => {
                    $(".options-menu").show();
                    $toggleButton.css({
                        "margin-bottom": "0"
                    });
                }, 400);
            } else {
                $optionsDiv.removeClass('slide-in').addClass('slide-out');
                $toggleButton.find("svg").css('transform', 'rotate(-180deg)');
                localStorage.setItem('toggleOption', false);
                setTimeout(() => {
                    $(".options-menu").hide();
                    $toggleButton.css({
                        "margin-bottom": "262px"
                    });
                }, 400);
            }
        });
    });

    if(!localStorage.getItem("toggleOption")){
        localStorage.setItem('toggleOption', true);
    }

    if(localStorage.getItem("toggleOption") != "true"){
        $('.options-menu').removeClass('slide-in').addClass('slide-out');
        $('#toggle-options').find("svg").css('transform', 'rotate(-180deg)')
        setTimeout(() => {
            $(".options-menu").hide();
            $('#toggle-options').css({
                "margin-bottom": "262px"
            });
        }, 400);
    }
</script>