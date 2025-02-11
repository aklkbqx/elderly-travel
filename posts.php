<?php 
require_once("config.php");

$row = null;
if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["admin_login"]])->fetch();
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
    <?php require_once("components/nav.php");?>

    <div class='container' style='margin-top:10rem'>

        <div class='d-flex'>
            <a href="/" class='d-flex gap-1 align-items-center text-decoration-none text-dark'>
                <img src="<?= imagePath("web_images/icons","chevron-back.png") ?>" width='15px' height='15px' class='object-fit-cover'>
                กลับ
            </a>
        </div>

        <div class='border shadow-sm mt-2 rounded-xl mb-4 p-2'>
            <?php 
            if(isLogin()){ ?>
                <div data-bs-toggle='modal' data-bs-target='#addPost' class='d-flex align-items-center gap-2 btn btn-outline-light'>
                    <img src="<?= imagePath("user_images",$row["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                    <div class="form-control text-start">เขียนโพสต์</div>
                </div>
            <?php }else{ ?>
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
                                <img src="<?= imagePath("user_images",$row["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                                <div><?= $row["firstname"] ?> <?= $row["lastname"] ?></div>
                            </div>
                            <textarea name="text" class='form-control mb-2 mt-2' style='min-height:100px' placeholder='เขียนโพสต์ของคุณ' required></textarea>
                            <input id='chooseImageFile' type="file" name='image' hidden onchange='$("#imagePreview").attr("src",window.URL.createObjectURL(this.files[0]));$("#imageBlock").removeClass("d-none")'>
                            <div class='d-flex'>
                                <label for="chooseImageFile" class='btn btn-outline-light border-0 mt-2 d-flex align-items-center gap-1'>
                                    <img src="<?= imagePath("web_images/icons","image.png") ?>" width='25px' height='25px' class='object-fit-cover'>
                                </label>
                            </div>
                            <div class="d-none p-2" id='imageBlock'>
                                <div style='width:100%;height:300px' class="position-relative">
                                    <button type='button' class="btn-close p-2 border rounded-circle bg-danger position-absolute" style='top:5px;right:5px' onclick="$('#chooseImageFile').val(null);$('#imageBlock').addClass('d-none')"></button>
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
        if($fetchPosts->rowCount() > 0){
            while($post = $fetchPosts->fetch()){ ?>
            <div class='border shadow-sm rounded-xl mb-4'>
                <div class='pb-2 px-4 pt-4 d-flex justify-content-between align-items-center'>
                    <div class='d-flex align-items-center gap-2'>
                        <img src="<?= imagePath("user_images",$post["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                        <div class='d-flex flex-column'>
                            <div>
                                <?= $post["firstname"] ?> <?= $post["lastname"] ?>
                            </div>
                            <div class='text-muted' style='font-size:14px'><?= $post["post_created_at"] ?></div>
                        </div>
                    </div>
                    <?php
                    if(isLogin() &&  $row["user_id"] == $post["user_id"]){ ?>
                        <div class="dropdown">
                            <div data-bs-toggle='dropdown' class='cursor-pointer border d-flex align-items-center justify-content-center rounded-circle' style='width:50px;height:50px'>
                                <img src="<?= imagePath("web_images/icons","three-dot.png") ?>" alt="" width='30px' height='30px' class='object-fit-cover'>
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
                                                <img src="<?= imagePath("user_images",$row["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                                                <div class=''>
                                                    <?= $row["firstname"] ?> <?= $row["lastname"] ?>
                                                </div>
                                            </div>
                                            <textarea name="text" class='form-control mb-2 mt-2' style='min-height:100px' placeholder='เขียนโพสต์ของคุณ' required><?= $post["text"] ?></textarea>
                                            <input id='chooseImageFile-<?= $post["post_id"] ?>' type="file" name='image' hidden onchange='$("#imagePreview-<?= $post["post_id"] ?>").attr("src",window.URL.createObjectURL(this.files[0]));$("#imageBlock-<?= $post["post_id"] ?>").removeClass("d-none")'>
                                            <div class='d-flex'>
                                                <label for="chooseImageFile-<?= $post["post_id"] ?>" class='btn btn-outline-light mt-2 p-1 d-flex align-items-center gap-1'>
                                                    <img src="<?= imagePath("web_images/icons","image.png") ?>" width='35px' height='35px' class='object-fit-cover'>
                                                </label>
                                            </div>
                                            <div class="<?= $post["image"] ? "" : "d-none" ?> p-2" id='imageBlock-<?= $post["post_id"] ?>'>
                                                <div style='width:100%;height:300px' class="position-relative">
                                                    <button type='button' class="btn-close p-2 border rounded-circle bg-danger position-absolute" style='top:5px;right:5px' onclick="$('#chooseImageFile').val(null);$('#imageBlock-<?= $post['post_id'] ?>').addClass('d-none')"></button>
                                                    <img src="<?= imagePath("post_images",$post["post_image"]) ?>" id='imagePreview-<?= $post["post_id"] ?>' width='100%' height='300px' class='rounded-xl object-fit-cover border'>
                                                </div>
                                            </div>
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
                <?php if($post["post_image"] !== null){ ?>
                    <img src="<?= imagePath("post_images",$post["post_image"]) ?>" width='100%' height='400px' class='object-fit-cover'>
                <?php } ?>
                <div class='p-2 px-4 d-flex justify-content-between'>
                    <div>ถูกใจแล้ว <span id="like-count-id-<?= $post["post_id"] ?>"></span> คน</div>
                    <div>ความคิดเห็น <span id="comment-count-id-<?= $post["post_id"] ?>"></span></div>
                </div>
                <?php
                $post_like = sql("SELECT * FROM post_likes WHERE post_id = ?",[$post["post_id"]])->fetch();
                ?>
                <div class='p-2 px-4 d-flex justify-content-between border-top gap-2'>
                    <button type='button' onclick="likePost($(this),'<?= $post['post_id'] ?>')" class='btn <?= ($row && $post_like) && $row["user_id"] == $post_like["user_id"] ? "btn-teal" : "btn-outline-light text-dark" ?>  w-100 border d-flex align-items-center gap-2 justify-content-center'>
                        <img src="<?= ($row && $post_like) && $row["user_id"] == $post_like["user_id"] ? imagePath("web_images/icons","heart-red.png") : imagePath("web_images/icons","heart-black.png") ?> " width='15px' height='15px' class='object-fit-cover'>
                        <div><?= ($row && $post_like) && $row["user_id"] == $post_like["user_id"] ? "ถูกใจแล้ว" : "ถูกใจ" ?></div>
                    </button>
                    <button type='button' data-bs-toggle='modal' data-bs-target='#commentModel-<?= $post['post_id'] ?>' class='btn btn-outline-light w-100 text-dark border'>แสดงความคิดเห็น</button>
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
                                    if(isLogin()){ ?>
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
                                url:"api/posts.php?getComments&post_id=<?= $post["post_id"] ?>",
                                type: "GET",
                                success: (response) =>{
                                    $("#comment-box-<?= $post["post_id"] ?>").html(response)
                                }
                            });

                            $.ajax({
                                url: "api/posts.php?getCommentCount&post_id=<?= $post["post_id"] ?>",
                                type: "GET",
                                success: function(response){
                                    $("#comment-count-id-<?= $post["post_id"] ?>").text(response);
                                }
                            });

                            $.ajax({
                                url: "api/posts.php?getLikeCount&post_id=<?= $post["post_id"] ?>",
                                type: "GET",
                                success: function(response){
                                    $("#like-count-id-<?= $post["post_id"] ?>").text(response);
                                }
                            })
                        }, 500);

                        $("#form-comment-<?= $post["post_id"] ?>").on('submit',function(e){
                            e.preventDefault();
                            const formData = new FormData($(this)[0]);
                            $.ajax({
                                url:"api/posts.php?sendComment&post_id=<?= $post["post_id"] ?>",
                                type: "POST",
                                data: formData,
                                processData: false,
                                contentType:false,
                                success: (response) =>{
                                    $.ajax({
                                        url:"../api/posts.php?getComments&post_id=<?= $post["post_id"] ?>",
                                        type: "GET",
                                        success: (response) =>{
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
        }else{ ?>
            <h5 class='text-center'>ยังไม่มีโพสต์ในขณะนี้...</h5>
        <?php } ?>


    </div>
    <script src="libs/posts.js"></script>
</body>
</html>