<?php function navlink($className){ ?>
    <ul class="navlink">
        <a href="/" class="<?= $className; ?>"><li>หน้าแรก</li></a>
        <a href="posts.php" class="<?= $className; ?>"><li>กระดานสนทนา</li></a>
        <a href="places.php" class="<?= $className; ?>"><li>สถานที่ท่องเที่ยว</li></a>
        <a href="contactUs.php" class="<?= $className; ?>"><li>ติดต่อเรา</li></a>
    </ul>
<?php } ?>

<nav class="w-100 position-fixed z-99" style="top:10px;z-index:999" id="navbar">
    <div id="navbar-bg" class="container d-flex align-items-center p-4 position-relative justify-content-between rounded-xl" style="backdrop-filter:blur(10px)background-image: linear-gradient(0deg, #1d5e5a80, #1d5e5a95)">

        <a href="/" class="text-decoration-none">
            <h3 class="text-white">Elderly Travel</h3>
        </a>

        <?php navlink("text-white") ?>

        <div class="z-99">
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

            <?php } else { ?>
                    <div class="d-flex align-items-center gap-4">
                        <a href="register.php" class="text-decoration-none text-white" style=''>สมัครสมาชิก</a>
                        <a href="login.php" class='btn btn-light'>เข้าสู่ระบบ</a>
                    </div>
            <?php } ?>
        </div>
    </div>
</nav>