<?php function navlink($className){ ?>
    <ul class="navlink">
        <a href="/" class="<?= $className; ?>"><li>หน้าแรก</li></a>
        <a href="posts.php" class="<?= $className; ?>"><li>กระดานสนทนา</li></a>
        <a href="places.php" class="<?= $className; ?>"><li>สถานที่ท่องเที่ยว</li></a>
        <a href="contactUs.php" class="<?= $className; ?>"><li>ติดต่อเรา</li></a>
    </ul>
<?php } ?>

<nav class="w-100 position-fixed z-99" style="top:10px;z-index:9" id="navbar">
    <div id="navbar-bg" class="container d-flex align-items-center p-4 position-relative justify-content-between rounded-2xl" style="backdrop-filter:blur(10px);background-image: linear-gradient(0deg, rgb(13 110 253 / 85%), rgb(13 110 253 / 60%))">

        <a href="/" class="text-decoration-none">
            <h3 class="text-white">Elderly Travel</h3>
        </a>

        <div class="d-none d-lg-block">
            <?php navlink("text-white") ?>
        </div>

        <div class="z-99">
            <?php if(isset($_SESSION["user_login"]) || isset($_SESSION["admin_login"])){ ?>
                <div class="dropdown">
                    <div class="d-flex flex-row gap-2 btn btn-outline-light border-0 cursor-pointer dropdown-toggle align-items-center" data-bs-toggle="dropdown" style='border-radius: 2rem !important;'>
                        <img src="<?= imagePath("user_images",$row["image"]) ?>" width="50px" height="50px" class="rounded-circle object-fit-cover" />
                        <div><?= $row["firstname"] ?></div>
                    </div>
                    <ul class="dropdown-menu">
                        <a href="editprofile.php" class="dropdown-item mb-2">
                            <li class="d-flex align-items-center gap-2">
                                <img src="<?= imagePath("web_images/icons","people.png") ?>" width="35px" height="35px" class='object-fit-cover' />
                                <div>แก้ไขข้อมูลส่วน</div>
                            </li>
                        </a>
                        <a href="booking.php" class="dropdown-item">
                            <li class="d-flex align-items-center gap-2">
                                <img src="<?= imagePath("web_images/icons","list-paper.png") ?>" width="35px" height="35px" class='object-fit-cover' />
                                <div>การจองของคุณ</div>
                            </li>
                        </a>
                        <div class="dropdown-divider"></div>
                        <a href="?logout" class="dropdown-item text-danger">
                            <li class="d-flex align-items-center gap-2">
                                <img src="<?= imagePath("web_images/icons","logout.png") ?>" width="35px" height="35px" class='object-fit-cover' />
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