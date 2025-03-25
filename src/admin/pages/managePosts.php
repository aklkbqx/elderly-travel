<div class='row'>
    <div class='col-lg-4'>
        <form action="../api/posts.php" method='post' enctype='multipart/form-data' class="mb-5">
            <input name='image' accept="image/*" id='imagePost' hidden type="file" onchange="$('#previewImage').attr('src',window.URL.createObjectURL(this.files[0]));$('#imagePreview').removeClass('d-none')">
            <div class='d-flex align-items-center gap-2'>
                <img src="<?= imagePath("user_images", $row["image"]) ?>" alt="" class='border rounded-circle object-fit-cover' width='50px' height='50px'>
                <div><?= $row["firstname"] ?> <?= $row["lastname"] ?></div>
            </div>
            <textarea name="text" placeholder='เขียนโพสต์...' class='form-control mt-2 border-0'></textarea>

            <label for="imagePost" class='btn btn-outline-light mb-2 border-0' style="font-size: 30px;">🖼️</label>

            <div class='position-relative my-2 d-none' id='imagePreview'>
                <img src="" width='100%' height='300px' id='previewImage' class='object-fit-cover rounded-xl'>
                <button type='button' onclick="$('#imagePost').val(null);$('#imagePreview').addClass('d-none')" class='btn-close position-absolute border rounded-circle p-2' style='top:20px;right:10px'></button>
            </div>

            <button type="submit" name='addPost' class='btn btn-teal w-100'>โพสต์</button>
        </form>
    </div>

    <div class='col-lg-8'>
        <div class='overflow-auto h-100'>
            <?php
            $fetchPost = sql("SELECT * FROM posts ORDER BY created_at DESC");
            if ($fetchPost->rowCount() > 0) {
                while ($post = $fetchPost->fetch()) {
                    $user = sql("SELECT * FROM users WHERE user_id = ?", [$post["user_id"]])->fetch();
                    $post_comnents = sql("SELECT *,post_comments.created_at as comment_created_at FROM post_comments LEFT JOIN users ON post_comments.user_id = users.user_id WHERE post_id = ?", [$post["post_id"]]);
                    $heart = "heart-black.png";
                    if (isLogin()) {
                        $post_likes = sql("SELECT * FROM post_likes WHERE post_id = ? AND user_id = ?", [$post["post_id"], $row["user_id"]]);
                        while ($post_like = $post_likes->fetch()) {
                            if ($post_like["user_id"] == $row["user_id"]) {
                                $heart = "heart-red.png";
                            } else {
                                $heart = "heart-black.png";
                            }
                        }
                    }
            ?>
                    <div class='shadow border rounded-xl mb-2'>
                        <div class='d-flex align-items-center justify-content-between p-4'>
                            <div class='d-flex align-items-center gap-2'>
                                <img src="<?= imagePath("user_images", $user["image"]) ?>" alt="" class='border rounded-circle object-fit-cover' width='50px' height='50px'>
                                <div class='d-flex flex-column'>
                                    <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                                    <div><?= $post["created_at"] ?></div>
                                </div>
                            </div>
                            <div class='dropdown'>
                                <div class='d-flex align-items-center justify-content-center cursor-pointer' data-bs-toggle='dropdown'>
                                    <img src="<?= imagePath("web_images/icons", "thee-dot.svg") ?>" width='25px' height='25px' class='object-fit-cover rounded-circle svg-icon'>
                                </div>
                                <ul class='dropdown-menu'>
                                    <li class='dropdown-item' data-bs-toggle='modal' data-bs-target='#editPost-<?= $post["post_id"] ?>'>แก้ไข</li>
                                    <div class="dropdown-divider"></div>
                                    <a href="../api/posts.php?deletePost&post_id=<?= $post["post_id"] ?>" class='text-danger dropdown-item'>
                                        <li>ลบ</li>
                                    </a>
                                </ul>

                                <div class="modal fade" id='editPost-<?= $post["post_id"] ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../api/posts.php?post_id=<?= $post["post_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                            <div class="modal-header">
                                                <h4 class="modal-title">แก้ไขกระทู้ของฉัน</h4>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class="modal-body">
                                                <input name='image' accept="image/*" id='imagePost<?= $post["post_id"] ?>' hidden type="file" onchange="$('#previewImage<?= $post['post_id'] ?>').attr('src',window.URL.createObjectURL(this.files[0]));$('#imagePreview<?= $post['post_id'] ?>').removeClass('d-none')">
                                                <div class='d-flex align-items-center gap-2'>
                                                    <img src="<?= imagePath("user_images", $user["image"]) ?>" alt="" class='border rounded-circle object-fit-cover' width='50px' height='50px'>
                                                    <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                                                </div>
                                                <textarea name="text" placeholder='แก้ไขโพสต์...' class='form-control mt-2 border-0'><?= $post["text"] ?></textarea>

                                                <label for="imagePost<?= $post["post_id"] ?>" class='btn btn-outline-light mt-2'>
                                                    <img src="<?= imagePath("web_images/icons", "image.svg") ?>" width='35px' height='35px' class='svg-icon'>
                                                </label>

                                                <?php
                                                if (isset($post["image"])) { ?>
                                                    <div class='position-relative mt-2' id='imagePreview<?= $post["post_id"] ?>'>
                                                        <img src="<?= imagePath("post_images", $post["image"]) ?>" width='100%' height='300px' style='' id='previewImage<?= $post["post_id"] ?>' class='object-fit-cover'>
                                                    </div>
                                                <?php }
                                                ?>

                                            </div>
                                            <div class="modal-footer d-flex w-100 align-items-center">
                                                <div class="w-100 d-flex align-items-center gap-2">
                                                    <button type="submit" name='editPost' class='btn btn-teal w-100'>บันทึก</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class='px-4 mb-2'>
                            <?= $post["text"] ?>
                        </div>
                        <?php if ($post['image']) { ?>
                            <img src="<?= imagePath("post_images", $post['image']) ?>" width='100%' height='500px' class='object-fit-cover'>
                        <?php } ?>
                        <div class='d-flex justify-content-end p-2'>
                            <div>
                                จำนวนความคิดเห็น <?= sql("SELECT COUNT(*) as count FROM post_comments WHERE post_id = ?", [$post["post_id"]])->fetch()["count"]; ?>
                            </div>
                        </div>
                        <div class='d-flex align-items-center gap-2 px-4'>
                            <a href="../api/posts.php?like&post_id=<?= $post["post_id"] ?>" class='text-decoration-none d-flex align-items-center gap-1 btn btn-outline-light  border'>
                                <img src="<?= imagePath("web_images/icons", $heart) ?>" width='30px' height='30px' class='object-fit-cover svg-icon'>
                                <div class='text-dark'><?= sql("SELECT COUNT(*) as count FROM post_likes WHERE post_id = ?", [$post["post_id"]])->fetch()["count"] ?></div>
                            </a>
                            <form method='post' action='../api/posts.php?comment&post_id=<?= $post["post_id"] ?>' class='d-flex w-100 align-items-center gap-2 m-0'>
                                <input type="text" name='comment' class='form-control' placeholder='แสดงความคิดเห็น'>
                                <button type='submit' name='submit_comment' class='btn btn-teal'>ส่ง</button>
                            </form>
                        </div>
                        <div class='mt-4 mx-4'>
                            <?php
                            while ($post_comnent = $post_comnents->fetch()) { ?>
                                <div class='d-flex align-items-center justify-content-between'>
                                    <div class='d-flex align-items-start gap-2 mb-4'>
                                        <img src="<?= imagePath("user_images", $post_comnent["image"]); ?>" class='rounded-circle border object-fit-cover' width='50px' height='50px'>
                                        <div>
                                            <div class='d-flex align-items-center gap-2'>
                                                <h6 class='fw-bold'><?= $post_comnent["firstname"] ?> <?= $post_comnent["lastname"] ?></h6>
                                                <div><?= $post_comnent["comment_created_at"] ?></div>
                                            </div>
                                            <div class='text-muted'><?= $post_comnent["comment"] ?></div>
                                        </div>
                                    </div>
                                    <?php if (isLogin() && ($post_comnent["user_id"] == $row["user_id"])) { ?>
                                        <div class='d-flex align-items-center gap-2'>
                                            <a href="../api/posts.php?delete_comment&comment_id=<?= $post_comnent["comment_id"] ?>&post_id=<?= $post["post_id"] ?>" class='btn btn-danger'>ลบ</a>
                                        </div>
                                    <?php } ?>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                <?php }
            } else { ?>
                <h5 class='text-center text-muted mt-5'>ยังไม่มีกระทู้ในขณะนี้...</h5>
            <?php }
            ?>
        </div>
    </div>
</div>