<div class='col-lg-4'>
    <div class='p-5 d-flex flex-column align-items-center justify-content-center'>
        <label for="select-image" class='cursor-pointer'>
            <img id='image-preview' src="<?= imagePath("user_images",$row["image"]) ?>" width='300px' height='300px' class='border rounded-circle bg-light object-fit-cover'>
        </label>
        <input id='select-image' type="file" name='image' hidden onchange="$('#image-preview').attr('src',window.URL.createObjectURL(this.files[0]))">
        <label for="select-image" class='btn btn-outline-teal mt-2'>แก้ไขรูปภาพ</label>
    </div>
</div>
<div class='col-lg-8'>
    <div class='p-2'>
        <h1 class='mb-4'>แก้ไขข้อมูลส่วนตัว</h1>
        <div class='d-flex flex-column gap-2'>
            <div class='flex-1 d-flex flex-row gap-2'>
                <div class="form-floating flex-1">
                    <input type="text" class="form-control" placeholder='ชื่อ' name='firstname' value="<?= $row["firstname"] ?>">
                    <label for="ชื่อ">ชื่อ</label>
                </div>
                <div class="form-floating flex-1">
                    <input type="text" class="form-control" placeholder='นามสกุล' name='lastname' value="<?= $row["lastname"] ?>">
                    <label for="นามสกุล">นามสกุล</label>
                </div>
            </div>
            <div class="form-floating">
                <p class='form-control mb-0'><?= $row["email"] ?></p>
                <label for="อีเมล">อีเมล</label>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" placeholder='รหัสผ่านเดิม' name='con_password'>
                <label for="รหัสผ่านเดิม">รหัสผ่านเดิม</label>
                <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons","eye.png"); ?>" style='width:40px;height:40px;top:10px;right:10px' class='object-fit-cover position-absolute cursor-pointer'>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" placeholder='รหัสผ่าน' name='password'>
                <label for="รหัสผ่าน">รหัสผ่าน</label>
                <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons","eye.png"); ?>" style='width:40px;height:40px;top:10px;right:10px' class='object-fit-cover position-absolute cursor-pointer'>
            </div>
            <div class="form-floating">
                <input type="password" class="form-control" placeholder='ยืนยันรหัสผ่าน' name='c_password'>
                <label for="ยืนยันรหัสผ่าน">ยืนยันรหัสผ่าน</label>
                <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons","eye.png"); ?>" style='width:40px;height:40px;top:10px;right:10px' class='object-fit-cover position-absolute cursor-pointer'>
            </div>
        </div>
        <div class='mt-4 d-flex flex-row justify-content-end gap-2'>
            <button type='reset' class='btn btn-light'>ยกเลิก</button>
            <button type='submit' class='btn btn-teal' name='save'>บันทึก</button>
        </div>
    </div>
</div>