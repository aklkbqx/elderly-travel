<?php
require_once("config.php");

$row = null;
if (isset($_SESSION["user_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
} elseif (isset($_SESSION["admin_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["admin_login"]])->fetch();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post | กระดานสนทนา</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php
    if(!isset($_SESSION["admin_login"])){
        require_once("components/nav.php");
    }
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>

    <div class='mx-auto' style='margin-top:<?= isset($_SESSION["admin_login"]) ? "2rem" : "10rem" ?>;max-width:1000px'>
        <?php backPage(isset($_SESSION["admin_login"]) ? "/admin/?managePosts" : "/"); ?>

        <div class='border shadow-sm mt-2 rounded-xl mb-4 p-2'>
            <?php
            if (isLogin()) { ?>
                <div data-bs-toggle='modal' data-bs-target='#addPost' class='d-flex align-items-center gap-2 btn btn-outline-light border-0'>
                    <img src="<?= imagePath("user_images", $row["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                    <div class="form-control text-start">เขียนโพสต์</div>
                </div>
            <?php } else { ?>
                <div class='d-flex align-items-center justify-content-center p-4'>
                    <div class='d-flex flex-column align-items-center gap-2'>
                        <div>กรุณาทำการเข้าสู่ระบบก่อนทำการโพสต์กระทู้</div>
                        <div class='d-flex align-items-center gap-2'>
                            <a href='register.php' class="btn btn-light">สมัครสมาชิก</a>
                            <a href='login.php' class="btn btn-teal">เข้าสู่ระบบ</a>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <div class="modal fade" id='addPost'>
                <div class="modal-dialog modal-dialog-centered">
                    <form method='post' action='api/posts.php' class="modal-content" enctype='multipart/form-data'>
                        <div class="modal-header">
                            <h4 class="modal-title">เขียนโพสต์</h4>
                            <button type='button' class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <div class='d-flex align-items-center gap-2'>
                                <img src="<?= imagePath("user_images", $row["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                                <div><?= $row["firstname"] ?> <?= $row["lastname"] ?></div>
                            </div>
                            <textarea name="text" class='form-control mb-2 mt-2' style='min-height:100px' placeholder='เขียนโพสต์ของคุณ' required></textarea>
                            <input id='chooseImageFile' type="file" accept="image/*" name='image' hidden onchange='$("#imagePreview").attr("src",window.URL.createObjectURL(this.files[0]));$("#imageBlock").removeClass("d-none")'>
                            <label for="chooseImageFile" class='btn btn-outline-light border-0' style="font-size: 30px;">🖼️</label>
                            <div class="d-none p-2" id='imageBlock'>
                                <div style='width:100%;height:300px' class="position-relative">
                                    <button type='button' class="btn-close p-2 border rounded-circle position-absolute" style='top:5px;right:5px' onclick="$('#chooseImageFile').val(null);$('#imageBlock').addClass('d-none')"></button>
                                    <img src="" id='imagePreview' width='100%' height='300px' class='rounded-xl object-fit-cover border'>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type='submit' name='addPost' class='btn btn-teal w-100'>โพสต์</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>

        <?php
        $fetchPosts = sql("SELECT *,posts.image as post_image,posts.created_at as post_created_at FROM posts LEFT JOIN users ON posts.user_id = users.user_id ORDER BY post_created_at DESC");
        if ($fetchPosts->rowCount() > 0) {
            while ($post = $fetchPosts->fetch()) { ?>
                <div class='border shadow-sm rounded-xl mb-4'>
                    <div class='pb-2 px-4 pt-4 d-flex justify-content-between align-items-center'>
                        <div class='d-flex align-items-center gap-2'>
                            <img src="<?= imagePath("user_images", $post["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                            <div class='d-flex flex-column'>
                                <div>
                                    <?= $post["firstname"] ?> <?= $post["lastname"] ?>
                                </div>
                                <div class='text-muted' style='font-size:14px'><?= timeElapsed($post["post_created_at"]) ?></div>
                            </div>
                        </div>
                        <?php
                        if (isLogin() &&  $row["user_id"] == $post["user_id"]) { ?>
                            <div class="dropdown">
                                <div class='d-flex align-items-center justify-content-center cursor-pointer' data-bs-toggle='dropdown'>
                                    <img src="<?= imagePath("web_images/icons", "thee-dot.svg") ?>" width='25px' height='25px' class='object-fit-cover rounded-circle svg-icon'>
                                </div>
                                <div class="dropdown-menu">
                                    <button class='dropdown-item' type='button' data-bs-toggle='modal' data-bs-target='#editPost-<?= $post["post_id"] ?>'>แก้ไข</button>
                                    <div class="dropdown-divider"></div>
                                    <a href="api/posts.php?deletePost&post_id=<?= $post["post_id"] ?>" class='dropdown-item text-danger'>ลบ</a>
                                </div>
                                <div class="modal fade" id='editPost-<?= $post["post_id"] ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form method='post' action='api/posts.php?post_id=<?= $post["post_id"] ?>' class="modal-content" enctype='multipart/form-data'>
                                            <div class="modal-header">
                                                <h4 class="modal-title">แก้ไขโพสต์</h4>
                                                <button type='button' class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class='d-flex align-items-center gap-2'>
                                                    <img src="<?= imagePath("user_images", $row["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                                                    <div class=''>
                                                        <?= $row["firstname"] ?> <?= $row["lastname"] ?>
                                                    </div>
                                                </div>
                                                <textarea name="text" class='form-control mb-2 mt-2' style='min-height:100px' placeholder='เขียนโพสต์ของคุณ' required><?= $post["text"] ?></textarea>
                                                <input id='chooseImageFile-<?= $post["post_id"] ?>' type="file" accept="image/*" name='image' hidden onchange='$("#imagePreview-<?= $post["post_id"] ?>").attr("src",window.URL.createObjectURL(this.files[0]));$("#imageBlock-<?= $post["post_id"] ?>").removeClass("d-none")'>
                                                <?php
                                                if ($post["post_image"]) { ?>
                                                    <label for="chooseImageFile-<?= $post["post_id"] ?>" class='btn btn-outline-light border-0' style="font-size: 30px;">🖼️</label>
                                                    <div style='width:100%;height:300px' class="position-relative">
                                                        <img src="<?= imagePath("post_images", $post["post_image"]) ?>" id='imagePreview-<?= $post["post_id"] ?>' width='100%' height='300px' class='rounded-xl object-fit-cover border'>
                                                    </div>
                                                <?php } ?>
                                            </div>
                                            <div class="modal-footer">
                                                <button type='submit' name='editPost' class='btn btn-teal w-100'>บันทึก</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                    <div class='px-4 py-2'>
                        <?= $post["text"] ?>
                    </div>
                    <?php if ($post["post_image"] !== null) { ?>
                        <style>
                            .hover-image {
                                cursor: pointer;
                                transition: transform 0.4s;

                                &:hover {
                                    transform: scale(1.01);
                                }
                            }
                        </style>
                        <img src="<?= imagePath("post_images", $post["post_image"]) ?>" width='100%' height='600px' class='object-fit-cover hover-image' data-bs-toggle='modal' data-bs-target='#imagePost-<?= $post["post_id"] ?>'>
                        <div class="modal fade" id='imagePost-<?= $post["post_id"] ?>'>
                            <div class="modal-dialog modal-dialog-centered modal-xl" data-bs-dismiss="modal">
                                <div class="modal-content bg-transparent">
                                    <div class="position-relative" style="z-index: 9999;">
                                        <button type="button" data-bs-dismiss="modal" class="btn-close position-absolute cursor-pointer bg-white rounded-circle p-2" style="z-index: 99999;top:10px;right:10px"></button>
                                        <img src="<?= imagePath("post_images", $post["post_image"]) ?>" width='100%' height='100%' class='object-fit-cover rounded-xl'>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                    <div class='p-2 px-4 d-flex justify-content-between'>
                        <div>ถูกใจแล้ว <span id="like-count-id-<?= $post["post_id"] ?>"></span> คน</div>
                        <div>ความคิดเห็น <span id="comment-count-id-<?= $post["post_id"] ?>"></span></div>
                    </div>
                    <?php
                    $post_like = sql("SELECT * FROM post_likes WHERE post_id = ?", [$post["post_id"]])->fetch();
                    ?>

                    <div class='p-2 px-4 d-flex justify-content-between border-top gap-2'>
                        <button type='button' onclick="<?= isLogin() ? "likePost($(this),'" . $post['post_id'] . "')" : "window.location = 'login.php'" ?>" class='btn <?= ($row && $post_like) && $row["user_id"] == $post_like["user_id"] ? "btn-teal" : "" ?>  w-100 border d-flex align-items-center gap-2 justify-content-center'>
                            <img src="<?= ($row && $post_like) && $row["user_id"] == $post_like["user_id"] ? imagePath("web_images/icons", "heart-red.png") : imagePath("web_images/icons", "heart-black.png") ?> " width='15px' height='15px' class='object-fit-cover svg-icon'>
                            <div><?= ($row && $post_like) && $row["user_id"] == $post_like["user_id"] ? "ถูกใจแล้ว" : "ถูกใจ" ?></div>
                        </button>
                        <button type='button' data-bs-toggle='modal' data-bs-target='#commentModel-<?= $post['post_id'] ?>' class='btn w-100 border'>แสดงความคิดเห็น</button>
                    </div>

                    <div class="modal fade" id='commentModel-<?= $post['post_id'] ?>'>
                        <div class="modal-dialog modal-dialog-centered modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">แสดงความคิดเห็น</h5>
                                    <button class="btn-close" type='button' data-bs-dismiss='modal'></button>
                                </div>
                                <div class="modal-body">
                                    <div class='d-flex flex-column align-items-center gap-2'>
                                        <div class='bg-light rounded-xl p-4 w-100 d-flex flex-column gap-2 overflow-auto' id='comment-box-<?= $post["post_id"] ?>' style='height:500px;'></div>
                                        <?php
                                        if (isLogin()) { ?>
                                            <form id="form-comment-<?= $post["post_id"] ?>" type='post' class='d-flex align-items-center gap-2 w-100'>
                                                <input type="text" class="form-control" name='comment' placeholder='เขียนความคิดเห็น'>
                                                <button class="btn btn-teal" type='submit'>ส่ง</button>
                                            </form>
                                        <?php } ?>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <div class='w-100'>
                                        <button class="w-100 btn btn-outline-light text-dark" data-bs-dismiss='modal'>ปิด</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <script>
                            setInterval(() => {
                                $.ajax({
                                    url: "api/posts.php?getComments&post_id=<?= $post["post_id"] ?>",
                                    type: "GET",
                                    success: (response) => {
                                        $("#comment-box-<?= $post["post_id"] ?>").html(response)
                                    }
                                });

                                $.ajax({
                                    url: "api/posts.php?getCommentCount&post_id=<?= $post["post_id"] ?>",
                                    type: "GET",
                                    success: function(response) {
                                        $("#comment-count-id-<?= $post["post_id"] ?>").text(response);
                                    }
                                });

                                $.ajax({
                                    url: "api/posts.php?getLikeCount&post_id=<?= $post["post_id"] ?>",
                                    type: "GET",
                                    success: function(response) {
                                        $("#like-count-id-<?= $post["post_id"] ?>").text(response);
                                    }
                                })
                            }, 500);

                            $("#form-comment-<?= $post["post_id"] ?>").on('submit', function(e) {
                                e.preventDefault();
                                const formData = new FormData($(this)[0]);
                                $.ajax({
                                    url: "api/posts.php?sendComment&post_id=<?= $post["post_id"] ?>",
                                    type: "POST",
                                    data: formData,
                                    processData: false,
                                    contentType: false,
                                    success: (response) => {
                                        $.ajax({
                                            url: "../api/posts.php?getComments&post_id=<?= $post["post_id"] ?>",
                                            type: "GET",
                                            success: (response) => {
                                                const commentBox = $("#comment-box-<?= $post["post_id"] ?>").html(response)
                                                commentBox.prop("scrollHeight");
                                            }
                                        });
                                        $(this).find("input").val(null);
                                    }
                                });
                            });
                        </script>
                    </div>
                </div>
            <?php }
        } else { ?>
            <h5 class='text-center text-muted'>ยังไม่มีกระทู้ในขณะนี้...</h5>
        <?php } ?>


    </div>
    <script src="libs/posts.js"></script>
</body>

</html>