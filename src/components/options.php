<div class="position-fixed" style="bottom: 40%;right:0;z-index:99">
    <div class="d-flex flex-column gap-2 rounded-xl">
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