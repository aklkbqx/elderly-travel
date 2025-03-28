<div class='d-flex justify-content-between'>
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
    <button data-bs-toggle='modal' data-bs-target="#addNews" type="button" class='btn btn-teal'>เพิ่ม+</button>

    <div class="modal fade" id='addNews'>
        <div class="modal-dialog modal-dialog-centered">
            <form action="../api/admin/manageNews.php" class="modal-content" method='post' enctype='multipart/form-data'>
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มข่าวสาร/ประชาสัมพันธ์</h4>
                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>
                <div class="modal-body">
                    <input type="file" accept="image/*" name="image" id="select-image" hidden onchange="$('#image-preview').attr('src',window.URL.createObjectURL(this.files[0]))">
                    <label for='select-image' class='d-flex justify-content-center btn btn-outline-light border-0 flex-column align-items-center gap-2 mb-2'>
                        <img id='image-preview' src="<?= imagePath("news_images","placeholder.png") ?>" width='100%' height='200px' class='rounded-xl border object-fit-cover'>
                        <div class='btn btn-outline-teal'>เปลี่ยนรูปข่าว</div>
                    </label>

                    <div class='d-flex flex-column gap-2'>
                        <div class="form-floating flex-1">
                            <input type="text" class="form-control" placeholder='หัวข้อข่าว' name='title' required>
                            <label for="หัวข้อข่าว">หัวข้อข่าว</label>
                        </div>
                        <div class="form-floating flex-1">
                            <textarea type="text" class="form-control" placeholder='คำอธิบาย' name='body' style='height:100px;' required></textarea>
                            <label for="คำอธิบาย">คำอธิบาย</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer d-flex w-100 align-items-center">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                        <button type="submit" name='addNews' class='btn btn-teal w-100'>เพิ่ม</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class='mt-2 h-100 overflow-auto'>
    <div class='row mb-5'>
        <?php
        $fetchNews = sql("SELECT * FROM news");
        if($fetchNews->rowCount() > 0){
            while($news = $fetchNews->fetch()){ ?>
                <div class='col-lg-4 mb-2' data-search-item>
                    <div class="rounded-xl overflow-hidden border">
                        <img src="<?= imagePath('news_images',$news["image"]) ?>" width="100%" height='250px;' class='object-fit-cover'>
                        <div class='p-2 d-flex flex-column justify-content-between' style='height:170px'>
                            <div>
                                <h5 data-search-keyword="<?= $news["title"] ?>" style="overflow: hidden;display: -webkit-box;-webkit-line-clamp: 2;-webkit-box-orient: vertical;"><?= $news["title"] ?></h5>
                                <div style='overflow: hidden;display: -webkit-box;-webkit-line-clamp: 1;-webkit-box-orient: vertical;'><?= $news["body"] ?></div>
                            </div>
                            <div class=''>
                                <button type='button' data-bs-toggle='modal' data-bs-target='#viewNewsDetail-<?= $news["news_id"] ?>' class='btn btn-teal w-100'>แสดงรายละเอียด</button>
                                <div class="modal fade" id='viewNewsDetail-<?= $news["news_id"] ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h4 class="modal-title">รายละเอียดข่าวสาร/ประชาสัมพันธ์</h4>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class="modal-body">
                                                <img src="<?= imagePath("news_images",$news["image"]); ?>" width='100%' height='300px' class='object-fit-cover rounded-xl border' />
                                                <div class='d-flex align-items-center gap-2 mt-2 justify-content-end' style='bottom:10px;right:10px'>
                                                    <img src="<?= imagePath("web_images/icons","eye.svg"); ?>" width="25px" height='25px' class='rounded-xl svg-icon' />
                                                    <div>จำนวนผู้ที่เข้าชม: <?= $news["visitors"] ?> ครั้ง</div>
                                                </div>
                                                <div class='p-2'>
                                                    <h4><?= $news["title"] ?></h4>
                                                    <div><?= $news["body"] ?></div>
                                                    <div class='d-flex justify-content-between mt-2'>
                                                        <div class='border rounded-xl text-center p-2'>
                                                            <div>จำนวนคนถูกใจ</div>
                                                            <div><?= sql("SELECT COUNT(*) as count FROM news_likes WHERE news_likes.news_id = ?",[$news["news_id"]])->fetch()["count"] ?> คน</div>
                                                        </div>
                                                        <div class='border rounded-xl text-center p-2'>
                                                            <div>จำนวนผู้แสดงความคิดเห็น</div>
                                                            <div><?= sql("SELECT COUNT(*) as count FROM news_comments WHERE news_comments.news_id = ?",[$news["news_id"]])->fetch()["count"] ?> คน</div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer d-flex w-100 align-items-center">
                                                <div class="w-100 d-flex align-items-center gap-2">
                                                    <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center gap-2 mt-2">
                                    <button type='button' data-bs-toggle='modal' data-bs-target='#editNews-<?= $news["news_id"] ?>' class='btn btn-warning border w-100'>แก้ไขข่าว</button>
                                    <button type='button' data-bs-toggle='modal' data-bs-target='#deleteNews-<?= $news["news_id"] ?>' class='btn btn-danger w-100'>ลบข่าว</button>

                                    <div class="modal fade" id='editNews-<?= $news["news_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="../api/admin/manageNews.php?news_id=<?= $news["news_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">แก้ไขข่าวสาร/ประชาสัมพันธ์</h4>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class="modal-body">
                                                    <input type="file" accept="image/*" name="image" id="select-image-<?= $news['news_id'] ?>" hidden onchange="$('#image-preview-<?= $news['news_id'] ?>').attr('src',window.URL.createObjectURL(this.files[0]))">
                                                    <label for='select-image-<?= $news['news_id'] ?>' class='d-flex justify-content-center btn btn-outline-light border-0 flex-column align-items-center gap-2 mb-2'>
                                                        <img id='image-preview-<?= $news['news_id'] ?>' src="<?= imagePath("news_images",$news["image"]) ?>" width='100%' height='200px' class='rounded-xl border object-fit-cover' />
                                                        <div class='btn btn-outline-teal'>เปลี่ยนรูปข่าว</div>
                                                    </label>

                                                    <div class='d-flex flex-column gap-2'>
                                                        <div class="form-floating flex-1">
                                                            <input type="text" class="form-control" placeholder='หัวข้อข่าว' name='title' value='<?= $news['title'] ?>' required>
                                                            <label for="หัวข้อข่าว">หัวข้อข่าว</label>
                                                        </div>
                                                        <div class="form-floating flex-1">
                                                            <textarea type="text" class="form-control" placeholder='คำอธิบาย' name='body' style='height:100px;' required><?= $news['body'] ?></textarea>
                                                            <label for="คำอธิบาย">คำอธิบาย</label>
                                                        </div>
                                                    </div>

                                                </div>
                                                <div class="modal-footer d-flex w-100 align-items-center">
                                                    <div class="w-100 d-flex align-items-center gap-2">
                                                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                        <button type="submit" name='editNews' class='btn btn-warning w-100'>บันทึก</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <div class="modal fade" id='deleteNews-<?= $news["news_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="../api/admin/manageNews.php?news_id=<?= $news["news_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">ลบข่าวสาร/ประชาสัมพันธ์</h4>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class="modal-body">
                                                    <h4 class='text-center'>คุณแน่ใจที่จะทำการลบข่าวนี้ใช่หรือไม่?</h4>
                                                </div>
                                                <div class="modal-footer d-flex w-100 align-items-center">
                                                    <div class="w-100 d-flex align-items-center gap-2">
                                                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                        <button type="submit" name='deleteNews' class='btn btn-danger w-100'>ลบ</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php }
        }else{ ?>
            <h5 class='text-center text-muted'>ยังไม่มีข่าวสารประชาสัมพันธ์ในขณะนี้...</h5>
        <?php } ?>
    </div>
</div>