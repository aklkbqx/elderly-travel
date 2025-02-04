<footer class="w-100 p-5">
    <div class="container">
        <div class="d-flex flex-lg-row flex-column justify-content-between align-items-center">

            <div class="d-flex flex-column">
                <h1 class="text-teal fw-bold">Elderly Travel</h1>
                <div class="fw-bold fs-5">สถิติผู้เข้าชมเว็บไซต์</div>
                <div class="d-flex flex-row align-items-center gap-2">
                    <img src="<?= imagePath("web_images/icons","eye.png") ?>" alt="" width="25px" height="25px" class="object-fit-cover">
                    จำนวนผู้เข้าชมทั้งหมด <span><?= sql("SELECT * FROM visitors WHERE visitor_id = 1")->fetch()["count"] ?></span> ครั้ง
                </div>
            </div>

            <div>
                <?php navlink("text-dark") ?>
            </div>

            <div class="d-flex justify-content-end flex-column mt-5 mt-lg-0">
                <h4>ติดตามช่าวสารใหม่ๆจากเรา</h4>
                <div class="d-flex align-items-center gap-2">
                    <div class="form-floating">
                        <input type="text" class="form-control rounded-xl" placeholder="อีเมล">
                        <label for="อีเมล">อีเมล</label>
                    </div>
                    <button type="button" class='btn btn-teal'>ติดตาม</button>
                </div>
            </div>
        </div>
    </div>
</footer>