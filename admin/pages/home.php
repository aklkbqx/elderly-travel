<div class='overflow-auto h-100'>
    <h4>• จำนวนของสมาชิก</h4>
    <div class='d-flex gap-2 flex-wrap'>
        <?php function showCardUserCount($role){
            $count = sql("SELECT COUNT(*) as count FROM users WHERE role = ?",[$role])->fetch()["count"] ?>
            <div class='flex-1'>
                <div class="rounded-xl p-4 shadow border text-white" 
                style='background-color: <?= $role == "user" ? "var(--bs-primary)" : ($role == "admin" ? "var(--bs-indigo)" : "var(--bs-green)") ?>'>
                    <h5>
                        จำนวนของ <?= $role == "user" ? "สมาชิกทั่วไป" : ($role == "admin" ? "ผู้ดูแลระบบ" : "แพทย์/หมอ") ?>
                    </h5>
                    <div class='d-flex justify-content-between align-items-center'>
                        <h1><?= $count; ?> คน</h1>
                        <h1><?= $role == "user" ? "👨‍👩‍👧‍👦" : ($role == "admin" ? "👤" : "👨🏻‍⚕️") ?></h1>
                    </div>
                </div>
            </div>
        <?php }

        showCardUserCount("admin");
        showCardUserCount("doctor");
        showCardUserCount("user");
        ?>
    </div>
    <!-- <h4 class='mt-4'>• จำนวนข่าวสารประชาสัมพันธ์</h4>
    <div class='d-flex gap-2 flex-wrap'>
            
    </div> -->
</div>