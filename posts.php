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
    
    <?php
    require_once("components/nav.php");
    ?>

    <div class='container' style='margin-top:10rem'>

        <div class='d-flex'>
            <a href="/" class='d-flex gap-1 align-items-center text-decoration-none text-dark'>
                <img src="<?= imagePath("web_images/icons","chevron-back.png") ?>" width='15px' height='15px' class='object-fit-cover'>
                กลับ
            </a>
        </div>

        <div class='border shadow-sm mt-2 rounded-xl mb-4 p-2'>
            <div data-bs-toggle='modal' data-bs-target='#addPost' class='d-flex align-items-center gap-2 btn btn-outline-light'>
                <img src="<?= imagePath("user_images",$row["image"]); ?>" width='50px' height='50px' class='rounded-circle object-fit-cover border'>
                <div class="form-control text-start">เขียนโพสต์</div>
            </div>

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
                                <div class=''>
                                    <?= $row["firstname"] ?> <?= $row["lastname"] ?>
                                </div>
                            </div>
                            <textarea name="text" class='form-control mb-2 mt-2' style='min-height:100px' placeholder='เขียนโพสต์ของคุณ' required></textarea>
                            <input id='chooseImageFile' type="file" name='image' hidden onchange='$("#imagePreview").attr("src",window.URL.createObjectURL(this.files[0]));$("#imageBlock").removeClass("d-none")'>
                            <div class='d-flex'>
                                <label for="chooseImageFile" class='btn btn-outline-light border-0 mt-2 d-flex align-items-center gap-1'>
                                    <img src="<?= imagePath("web_images/icons","image.png") ?>" width='25px' height='25px' class='object-fit-cover'>
                                    <div class='text-dark'>เพิ่มรูปภาพ</div>
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
                            <div class=''>
                                <?= $post["firstname"] ?> <?= $post["lastname"] ?>
                            </div>
                            <div class='text-muted' style='font-size:14px'><?= $post["post_created_at"] ?></div>
                        </div>
                    </div>
                    <div class="dropdown">
                        <div data-bs-toggle='dropdown' class='cursor-pointer border d-flex align-items-center justify-content-center rounded-circle' style='width:50px;height:50px'>
                            <img src="" alt="" width='30px' height='30px' class='object-fit-cover'>
                        </div>

                        <div class="dropdown-menu">
                            <button class='dropdown-item' type='button' data-bs-toggle='modal' data-bs-target='#editPost-<?= $post["post_id"] ?>'>แก้ไข</button>
                            <div class="dropdown-divider"></div>
                            <a href="" class='dropdown-item text-danger'>ลบ</a>
                        </div>

                        <div class="modal fade" id='editPost-<?= $post["post_id"] ?>'>
                            <div class="modal-dialog modal-dialog-centered">
                                <form method='post' action='api/posts.php' class="modal-content" enctype='multipart/form-data'>
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
                                        <textarea name="text" class='form-control mb-2 mt-2' style='min-height:100px' placeholder='เขียนโพสต์ของคุณ' required></textarea>
                                        <input id='chooseImageFile-<?= $post["post_id"] ?>' type="file" name='image' hidden onchange='$("#imagePreview-<?= $post["post_id"] ?>").attr("src",window.URL.createObjectURL(this.files[0]));$("#imageBlock-<?= $post["post_id"] ?>").removeClass("d-none")'>
                                        <div class='d-flex'>
                                            <label for="chooseImageFile-<?= $post["post_id"] ?>" class='btn btn-outline-light mt-2 p-1 d-flex align-items-center gap-1'>
                                                <img src="<?= imagePath("web_images/icons","image.png") ?>" width='35px' height='35px' class='object-fit-cover'>
                                                <div class='text-dark'>เพิ่มรูปภาพ</div>
                                            </label>
                                        </div>
                                        <div class="d-none p-2" id='imageBlock-<?= $post["post_id"] ?>'>
                                            <div style='width:100%;height:300px' class="position-relative">
                                                <button type='button' class="btn-close p-2 border rounded-circle bg-danger position-absolute" style='top:5px;right:5px' onclick="$('#chooseImageFile').val(null);$('#imageBlock-<?= $post['post_id'] ?>').addClass('d-none')"></button>
                                                <img src="" id='imagePreview-<?= $post["post_id"] ?>' width='100%' height='300px' class='rounded-xl object-fit-cover border'>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type='submit' name='editPost' class='btn btn-teal w-100'>โพสต์</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class='px-4 py-2'>
                    <?= $post["text"] ?>
                </div>  
                <img src="<?= imagePath("post_images",$post["post_image"]) ?>" width='100%' height='400px' class='object-fit-cover'>
                <div class='p-2 px-4 d-flex justify-content-between'>
                    <div>ถูกใจแล้ว <span><?= sql("SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?",[$post["post_id"]])->fetch()["count"] ?></span> คน</div>
                    <div>ความคิดเห็น <span><?= sql("SELECT COUNT(*) as count FROM post_comments WHERE post_id = ?",[$post["post_id"]])->fetch()["count"] ?></span></div>
                </div>
                <div class='p-2 px-4 d-flex justify-content-between border-top gap-2'>
                    <button type='button' class='btn btn-outline-light w-100 text-dark border'>ถูกใจ</button>
                    <button type='button' class='btn btn-outline-light w-100 text-dark border'>แสดงความคิดเห็น</button>
                </div>
            </div>
            <?php }
        }else{ ?>
            <h5 class='text-center'>ยังไม่มีโพสต์ในขณะนี้...</h5>
        <?php } ?>


    </div>

</body>
</html>