<?php function navlink($className)
{ ?>
    <style>
        .navlink {
            display: flex;
            align-items: center;
            margin: 0;
            padding: 0;
            list-style: none;
            gap: 2rem;
        }

        .navlink li {
            list-style: none;
        }

        .navlink a {
            text-decoration: none;
            font-weight: 700;
            color: #212529;
            display: inline-block;
            transition: transform 0.2s ease-in-out;
        }

        .navlink a:hover {
            transform: translateY(5px);
        }
    </style>
    <ul class="navlink">
        <li>
            <a href="/" class="<?= $className; ?>">หน้าแรก</a>
        </li>
        <li>
            <a href="posts" class="<?= $className; ?>">กระดานสนทนา</a>
        </li>
        <li>
            <a href="places" class="<?= $className; ?>">สถานที่ท่องเที่ยว</a>
        </li>
    </ul>

<?php } ?>

<nav class="w-100 position-fixed z-99" style="top:10px;z-index:9" id="navbar">
    <div class="mx-2">
        <div id="navbar-bg" class="w-100 container d-flex align-items-center p-4 justify-content-between rounded-2xl position-relative" style="backdrop-filter:blur(10px);background-image: linear-gradient(0deg,rgb(44, 128, 122), teal);height: 85px;">

            <a href="/" class="text-decoration-none">
                <h3 class="text-white">ELDERLY TRAVEL</h3>
            </a>

            <div class="d-none d-lg-block">
                <?php navlink("text-white") ?>
            </div>

            <div>
                <?php if (isset($_SESSION["user_login"]) || isset($_SESSION["admin_login"]) || isset($_SESSION["doctor_login"])) { ?>
                    <div class="dropdown d-none d-lg-flex">
                        <div class="d-flex flex-row gap-2 btn btn-outline-light border-0 cursor-pointer dropdown-toggle align-items-center" data-bs-toggle="dropdown" style='border-radius: 2rem !important;'>
                            <img src="<?= imagePath("user_images", $row["image"]) ?>" width="50px" height="50px" class="rounded-circle object-fit-cover" />
                            <div><?= $row["firstname"] ?></div>
                        </div>
                        <ul class="dropdown-menu">
                            <a href="editprofile" class="dropdown-item mb-2">
                                <li class="d-flex align-items-center gap-2">
                                    <img src="<?= imagePath("web_images/icons", "people.svg") ?>" width="35px" height="35px" class='object-fit-cover svg-icon' />
                                    <div>แก้ไขข้อมูลส่วนตัว</div>
                                </li>
                            </a>
                            <a href="my-booking" class="dropdown-item">
                                <li class="d-flex align-items-center gap-2">
                                    <img src="<?= imagePath("web_images/icons", "list-paper.svg") ?>" width="35px" height="35px" class='object-fit-cover' />
                                    <div>การจองของคุณ</div>
                                </li>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="?logout" class="dropdown-item text-danger">
                                <li class="d-flex align-items-center gap-2">
                                    <img src="<?= imagePath("web_images/icons", "logout.svg") ?>" width="35px" height="35px" class='object-fit-cover' />
                                    <div>ออกจากระบบ</div>
                                </li>
                            </a>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="d-none d-lg-flex align-items-center gap-4">
                        <a href="register" class="text-decoration-none text-white">สมัครสมาชิก</a>
                        <a href="login" class='btn btn-light'>เข้าสู่ระบบ</a>
                    </div>
                <?php } ?>

                <button type='button' onclick="openCollapse()" class="d-block d-lg-none btn border border-light d-flex justify-content-center align-items-center" style="width: 60px;height:50px">
                    <svg id='hamburger-collapse' width="35px" height="35px" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M4 18L20 18" stroke="#fff" stroke-width="2" stroke-linecap="round" />
                        <path d="M4 12L20 12" stroke="#fff" stroke-width="2" stroke-linecap="round" />
                        <path d="M4 6L20 6" stroke="#fff" stroke-width="2" stroke-linecap="round" />
                    </svg>

                    <svg id='chevron-up-collapse' class="d-none" fill="#fff" height="20px" width="20px" version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                        viewBox="0 0 407.436 407.436" xml:space="preserve">
                        <polygon points="203.718,91.567 0,294.621 21.179,315.869 203.718,133.924 386.258,315.869 407.436,294.621 " />
                    </svg>
                </button>
            </div>
        </div>

    </div>

    <div class="position-absolute left-0 w-100 d-none " id="nav-collapse">
        <div class="p-2 bg-teal w-100 rounded-4">
            <div class="d-flex flex-column align-items-center gap-3">
                <div>
                    <?php navlink("text-white") ?>
                </div>
                <?php if (isset($_SESSION["user_login"]) || isset($_SESSION["admin_login"]) || isset($_SESSION["doctor_login"])) { ?>
                    <div class="dropdown">
                        <div class="d-flex flex-row gap-2 btn btn-outline-light border-0 cursor-pointer dropdown-toggle align-items-center" data-bs-toggle="dropdown" style='border-radius: 2rem !important;'>
                            <img src="<?= imagePath("user_images", $row["image"]) ?>" width="50px" height="50px" class="rounded-circle object-fit-cover" />
                            <div><?= $row["firstname"] ?></div>
                        </div>
                        <ul class="dropdown-menu">
                            <a href="editprofile" class="dropdown-item mb-2">
                                <li class="d-flex align-items-center gap-2">
                                    <img src="<?= imagePath("web_images/icons", "people.svg") ?>" width="35px" height="35px" class='object-fit-cover svg-icon' />
                                    <div>แก้ไขข้อมูลส่วนตัว</div>
                                </li>
                            </a>
                            <a href="my-booking" class="dropdown-item">
                                <li class="d-flex align-items-center gap-2">
                                    <img src="<?= imagePath("web_images/icons", "list-paper.svg") ?>" width="35px" height="35px" class='object-fit-cover' />
                                    <div>การจองของคุณ</div>
                                </li>
                            </a>
                            <div class="dropdown-divider"></div>
                            <a href="?logout" class="dropdown-item text-danger">
                                <li class="d-flex align-items-center gap-2">
                                    <img src="<?= imagePath("web_images/icons", "logout.svg") ?>" width="35px" height="35px" class='object-fit-cover' />
                                    <div>ออกจากระบบ</div>
                                </li>
                            </a>
                        </ul>
                    </div>
                <?php } else { ?>
                    <div class="d-flex align-items-center gap-4">
                        <a href="register" class="text-decoration-none text-white">สมัครสมาชิก</a>
                        <a href="login" class='btn btn-light'>เข้าสู่ระบบ</a>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>

</nav>

<script>
    const openCollapse = () => {
        const navCollapse = $("#nav-collapse");
        const hamburger = $("#hamburger-collapse");
        const chevronUp = $("#chevron-up-collapse");
        if (navCollapse.hasClass("d-none")) {
            navCollapse.removeClass("d-none");
            hamburger.addClass("d-none");
            chevronUp.removeClass("d-none");
        } else {
            navCollapse.addClass("d-none")
            hamburger.removeClass("d-none");
            chevronUp.addClass("d-none");
        }
    }
</script>