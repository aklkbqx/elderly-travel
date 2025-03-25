
<div class='d-flex justify-content-between gap-2'>
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
    <button data-bs-toggle='modal' data-bs-target="#addUser" type="button" class='btn btn-teal'>เพิ่ม+</button>
    <div class="modal fade" id='addUser'>
        <div class="modal-dialog modal-dialog-centered">
            <form action="../api/admin/manageUsers.php" class="modal-content" method='post' enctype='multipart/form-data'>
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มสมาชิก</h4>
                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>
                <div class="modal-body">
                    <input type="file" accept="image/*" name="image" id="select-image" hidden onchange="$('#image-preview').attr('src',window.URL.createObjectURL(this.files[0]))">
                    <label for='select-image' class='d-flex justify-content-center btn btn-outline-light border-0 flex-column align-items-center gap-2 mb-2'>
                        <img id='image-preview' src="<?= imagePath("user_images","default-profile.png") ?>" width='200px' height='200px' class='rounded-circle border object-fit-cover'>
                        <div class='btn btn-outline-teal'>เปลี่ยนรูปภาพโปรไฟล์</div>
                    </label>
                    <div class='d-flex flex-column gap-2'>
                        <div class='flex-1 d-flex flex-row gap-2'>
                            <div class="form-floating flex-1">
                                <input type="text" class="form-control" placeholder='ชื่อ' name='firstname' required>
                                <label for="ชื่อ">ชื่อ</label>
                            </div>
                            <div class="form-floating flex-1">
                                <input type="text" class="form-control" placeholder='นามสกุล' name='lastname' required>
                                <label for="นามสกุล">นามสกุล</label>
                            </div>
                        </div>
                        <div class="form-floating">
                            <input type="email" class="form-control" placeholder='อีเมล' name='email' required>
                            <label for="อีเมล">อีเมล</label>
                        </div>
                        <div class="form-floating">
                            <input type="password" class="form-control" placeholder='รหัสผ่าน' name='password' required>
                            <label for="รหัสผ่าน">รหัสผ่าน</label>
                            <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons","eye.svg"); ?>" style='width:35px;height:35px;top:12px;right:10px' class='object-fit-cover position-absolute cursor-pointer svg-icon'>
                        </div>
                        <select name="role" class='form-select cursor-pointer'>
                            <option value="user">ผู้ใช้ทั่วไป</option>
                            <option value="admin">แอดมิน</option>
                            <option value="doctor">แพทย์/หมอ</option>
                        </select>
                    </div>

                </div>
                <div class="modal-footer d-flex w-100 align-items-center">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                        <button type="submit" name='addUser' class='btn btn-teal w-100'>เพิ่ม</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class='overflow-auto h-100'>
    <div class='mb-2 w-100 mt-3 d-flex justify-content-between mt-2 flex-wrap'>
        <h4>เลือกบทบาท</h4>
        <div class='rounded-xl d-flex flex-row align-items-center gap-1'>
            <a href='?manageUsers&user' class='btn <?= isset($_GET["user"]) ? "btn-teal" : "btn-light" ?>'>ผู้ใช้งานทั่วไป</a>
            <a href='?manageUsers&admin' class='btn <?= isset($_GET["admin"]) ? "btn-teal" : "btn-light" ?>'>ผู้ดูแลระบบ</a>
            <a href='?manageUsers&doctor' class='btn <?= isset($_GET["doctor"]) ? "btn-teal" : "btn-light" ?>'>แพทย์/หมอ</a>
        </div>
    </div>
    <?php $role = isset($_GET["user"]) ? "user" : (isset($_GET["admin"]) ? "admin" : "doctor");
    $fetchUser = sql("SELECT * FROM users WHERE role = ?",[$role]);
    if($fetchUser->rowCount() > 0){ ?>
        <div class='overflow-hidden rounded-xl table-responsive'>
            <table class='table table-striped text-center align-middle'>
                <thead>
                    <tr>
                        <td style="width: 113px;">#</td>
                        <td style="width: 442px;">ชื่อ-นามสกุล</td>
                        <td style="width: 410px;">อีเมล</td>
                        <td style="width: 349px;">จัดการ</td>
                    </tr>
                </thead>
                <tbody>
                <?php
                $index=1;
                while($user = $fetchUser->fetch()){ ?>
                    <tr data-search-item>
                        <td>
                            <div class='text-center fw-bold'><?= $index; ?></div>
                        </td>
                        <td>
                            <div class='d-flex align-items-center gap-2'>
                                <img src="<?= imagePath('user_images',$user["image"]) ?>" width='50px' height='50px' class='rounded-circle border object-fit-cover'>
                                <div class='d-flex align-items-center gap-2'>
                                    <div data-search-keyword="<?= $user["firstname"] ?> <?= $user["firstname"] ?>"><?= $user["firstname"]; ?> <?= $user["lastname"]; ?></div>
                                </div>
                            </div>
                        </td>
                        <td data-search-keyword="<?= $user["email"] ?>"><?= $user["email"]; ?></td>
                        <td>
                            <div class='d-flex align-items-center gap-2 w-100'>
                                <button type='button' data-bs-toggle='modal' class='btn btn-warning w-100' data-bs-target='#editUser-<?= $user["user_id"]; ?>'>แก้ไข</button>
                                <button type='button' data-bs-toggle='modal' class='btn btn-danger w-100' data-bs-target='#deleteUser-<?= $user["user_id"]; ?>'>ลบ</button>
                                <div class="modal fade" id='editUser-<?= $user["user_id"]; ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../api/admin/manageUsers.php?user_id=<?= $user["user_id"]; ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                            <div class="modal-header">
                                                <h4 class="modal-title">แก้ไขสมาชิก</h4>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class="modal-body">
                                                <input type="file" accept="image/*" name="image" id="select-image-<?= $user["user_id"] ?>" hidden onchange="$('#image-preview-<?= $user['user_id'] ?>').attr('src',window.URL.createObjectURL(this.files[0]))">
                                                <label for='select-image-<?= $user["user_id"] ?>' class='d-flex justify-content-center btn btn-outline-light border-0 flex-column align-items-center gap-2 mb-2'>
                                                    <img id='image-preview-<?= $user['user_id'] ?>' src="<?= imagePath("user_images",$user["image"]) ?>" width='200px' height='200px' class='rounded-circle border object-fit-cover'>
                                                    <div class='btn btn-outline-teal'>เปลี่ยนรูปภาพโปรไฟล์</div>
                                                </label>

                                                <div class='d-flex flex-column gap-2'>
                                                    <div class='flex-1 d-flex flex-row gap-2'>
                                                        <div class="form-floating flex-1">
                                                            <input type="text" class="form-control" placeholder='ชื่อ' name='firstname' value="<?= $user["firstname"] ?>" required>
                                                            <label for="ชื่อ">ชื่อ</label>
                                                        </div>
                                                        <div class="form-floating flex-1">
                                                            <input type="text" class="form-control" placeholder='นามสกุล' name='lastname' value="<?= $user["lastname"] ?>" required>
                                                            <label for="นามสกุล">นามสกุล</label>
                                                        </div>
                                                    </div>
                                                    <div class="form-floating">
                                                        <input type="email" class="form-control" placeholder='อีเมล' name='email' value="<?= $user["email"] ?>" required>
                                                        <label for="อีเมล">อีเมล</label>
                                                    </div>
                                                    <div class="form-floating">
                                                        <input type="password" class="form-control" placeholder='รหัสผ่าน' name='password'>
                                                        <label for="รหัสผ่าน">รหัสผ่าน</label>
                                                        <img onclick='openPassword($(this))' src="<?= imagePath("web_images/icons","eye.svg"); ?>" style='width:35px;height:35px;top:12px;right:10px' class='object-fit-cover position-absolute cursor-pointer svg-icon'>
                                                    </div>
                                                    <select name="role" class='form-select cursor-pointer'>
                                                        <option value="user" <?= $user["role"] === "user" ? 'selected' : NULL ?>>ผู้ใช้ทั่วไป</option>
                                                        <option value="admin" <?= $user["role"] === "admin" ? 'selected' : NULL ?>>แอดมิน</option>
                                                        <option value="doctor" <?= $user["role"] === "doctor" ? 'selected' : NULL ?>>แอดมิน</option>
                                                    </select>
                                                </div>

                                            </div>
                                            <div class="modal-footer d-flex w-100 align-items-center">
                                                <div class="w-100 d-flex align-items-center gap-2">
                                                    <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                    <button type="submit" name='editUser' class='btn btn-success w-100'>บันทึก</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal fade" id='deleteUser-<?= $user["user_id"]; ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../api/admin/manageUsers.php?user_id=<?= $user["user_id"]; ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                            <div class="modal-header">
                                                <h4 class="modal-title">ลบสมาชิก</h4>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class="modal-body">
                                                <h4 class='text-center'>แน่ใจที่จะลบสมาชิก คุณ "<?= $user["firstname"] ?> <?= $user["lastname"] ?>"</h4>
                                            </div>
                                            <div class="modal-footer d-flex w-100 align-items-center">
                                                <div class="w-100 d-flex align-items-center gap-2">
                                                    <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                    <button type="submit" name='deleteUser' class='btn btn-danger w-100'>ยืนยันการลบ</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </td>
                    </tr>
                <?php $index++; } ?>
                </tbody>
            </table>
        </div>
    <?php }else{ ?>
        <h5 class='text-center text-muted'>ยังไม่มีสมาชิก...</h5>
    <?php } ?>
</div>