<div class="position-fixed" style="top: 150px;right:0;z-index:99"> 
    <div class="d-flex flex-column gap-2 p-2 rounded-4">
        <div class="d-flex justify-content-center">
            <button type="button" class="p-0 d-flex justify-content-center btn btn-light fs-3" id="toggleTheme"></button>
        </div>
        <div class="d-flex flex-column gap-2 p-2 rounded-4">
            <div class="d-flex justify-content-center">
                <button onclick="setZoom('100%')" type="button" class="p-0 d-flex justify-content-center btn btn-light align-items-center flex-column">
                    <img src="<?= imagePath("web_images/icons",'small.png') ?>" width='40px' height="20px" class="rounded-4 px-0">
                    100% (ปกติ)
                </button>
            </div>
            <div class="d-flex justify-content-center">
                <button onclick="setZoom('125%')" type="button" class="p-0 d-flex justify-content-center btn btn-light align-items-center flex-column">
                    <img src="<?= imagePath("web_images/icons",'medium.png') ?>" width='40px' height="20px" class="rounded-4 px-0">
                    125% (กลาง)
                </button>
            </div>
            <div class="d-flex justify-content-center">
                <button onclick="setZoom('150%')" type="button" class="p-0 d-flex justify-content-center btn btn-light align-items-center flex-column">
                    <img src="<?= imagePath("web_images/icons",'large.png') ?>" width='40px' height="20px" class="rounded-4 px-0">
                    150% (ใหญ่)
                </button>
            </div>
    </div>
</div>
</div>