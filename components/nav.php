<?php 
function navlink($className){ ?>
    <ul class="navlink">
        <a href="" class="<?= $className; ?>"><li>หน้าแรก</li></a>
        <a href="" class="<?= $className; ?>"><li>กระดานสนทนา</li></a>
        <a href="" class="<?= $className; ?>"><li>สถานที่ท่องเที่ยว</li></a>
        <a href="" class="<?= $className; ?>"><li>ติดต่อเรา</li></a>
    </ul>
<?php } ?>
<nav class="w-100 position-fixed z-99" style="top:10px">
    <div class="container d-flex align-items-center p-4 position-relative">
        <h3 class="position-absolute text-white">Elderly Travel</h3>
        <div class="flex-1 d-flex justify-content-center text-white">
            <?php navlink("text-white") ?>
        </div>
        <div class="position-absolute end-0">
        <?php if(isset($_SESSION["user_login"]) || isset($_SESSION["admin_login"])){ ?>
                <div class="dropdown">
                    <img src="<?= imagePath("user_images",$row["profile_image"]) ?>" width="50px" height="50px" data-bs-toggle="dropdown" class="btn btn-outline-light rounded-circle border p-0" />
                    <ul class="dropdown-menu">
                        <a href="" class="dropdown-item">
                            <li class="d-flex align-items-center gap-2">
                                <img src="" width="35px" height="35px" />
                                <div>แก้ไขข้อมูลส่วน</div>
                            </li>
                        </a>
                        <a href="" class="dropdown-item">
                            <li class="d-flex align-items-center gap-2">
                                <img src="" width="35px" height="35px" />
                                <div>การจองของคุณ</div>
                            </li>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="" class="dropdown-item text-danger">
                            <li class="d-flex align-items-center gap-2">
                                <img src="" width="35px" height="35px" />
                                <div>ออกจากระบบ</div>
                            </li>
                        </a>
                    </ul>
                </div>

        <?php }else{ ?>
                <div class="d-flex align-items-center gap-4">
                    <a href="register.php" class="text-decoration-none text-white" style=''>สมัครสมาชิก</a>
                    <a href="login.php" class='btn btn-light'>เข้าสู่ระบบ</a>
                </div>
        <?php } ?>
        </div>
    </div>
</nav>