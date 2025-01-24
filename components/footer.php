<footer class="w-100 p-5">
    <div class="container">
        <div class="d-flex flex-row justify-content-between align-items-center">

            <div class="d-flex flex-column">
                <h1 class="text-teal fw-bold">Elderly Travel</h1>
                <div class="fw-bold fs-5">สถิติผู้เข้าชมเว็บไซต์</div>
                <div class="d-flex flex-row align-items-center gap-2">
                    <img src="<?= imagePath("web_images/icons","eye.png") ?>" alt="" width="35px" height="35px" class="object-fit-cover">
                    จำนวนผู้เข้าชมทั้งหมด <span><?= sql("SELECT COUNT(*) as count FROM visitors")->fetch()["count"] ?></span> ครั้ง
                </div>
            </div>

            <div>
                <?php navlink("text-dark") ?>
            </div>

            <div class="d-flex justify-content-end flex-column">
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