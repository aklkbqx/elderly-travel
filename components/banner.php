<div class="d-flex align-items-end justify-content-center" style="width:100%;height:100vh;background-image:url('<?= imagePath('web_images','bg-banner.png') ?>');background-position: center;background-repeat: no-repeat;background-size: cover;padding-bottom: 5rem;">
    <div class="d-flex flex-wrap align-items-start justify-content-lg-between justify-content-center w-100 container px-5">
        <div>
            <div class="d-flex flex-column text-white">
                <div style="font-size:20px">เว็บไซต์สำหรับผู้สูงอายุ</div>
                <div style="font-size:100px" class="text-white fw-bold">Elderly Travel</div>
                <div style="font-size:20px" class="fst-italic">"เอลเดอร์ลี่ แทรเวล" ให้คุณได้สัมผัสประสบการณ์การท่องเที่ยวเชิงสุขภาพ</div>
            </div>
            <img src="<?= imagePath("web_images","elderly.png"); ?>" width="100%" height="400px" class="mt-5" style="object-fit:contain">
        </div>
        <div class="bg-white shadow p-4 d-flex flex-column align-items-center" style="width:500px;border-radius:1.5rem">
            <h3 class="">จองทริปการท่องเที่ยว</h3>
            <div class="d-flex align-items-center gap-4 w-100 mt-2">
                <div class="form-floating w-100">
                    <input id="เช็คอิน" type="date" class="form-control rounded-xl">
                    <label for="เช็คอิน">เช็คอิน</label>
                </div>
                <div class="form-floating w-100">
                    <input id="เช็คเอาท์" type="date" class="form-control rounded-xl">
                    <label for="เช็คเอาท์">เช็คเอาท์</label>
                </div>
            </div>
            <div class="my-5">
                <div class="text-center">จำนวนคน</div>
                <div class="d-flex align-items-center justify-content-between gap-2">
                    <button class="btn btn-light rounded-xl fs-5 border">-</button>
                    <label for="">0</label>
                    <button class="btn btn-light rounded-xl fs-5 border">+</button>
                </div>
            </div>
            
            <a disabled href="<?= isset($_SESSION["user_login"]) ? 'booking.php' : 'login.php' ?>" class="btn btn-teal w-100 py-3">
                <?= isset($_SESSION["user_login"]) ? 'ดำเนินการต่อ' : 'ดำเนินการต่อ' ?>
            </a>

        </div>
    </div>
</div>