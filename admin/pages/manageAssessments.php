<div class='d-flex justify-content-between bg-white'>
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
    <button data-bs-toggle='modal' data-bs-target="#addAssessment" type="button" class='btn btn-teal'>เพิ่ม+</button>

    <div class="modal fade" id='addAssessment'>
        <div class="modal-dialog modal-dialog-centered">
            <form action="../api/admin/manageAssessments.php" class="modal-content" method='post' enctype='multipart/form-data'>
                <div class="modal-header">
                    <h4 class="modal-title">เพิ่มแบบสอบถาม/ประเมิน</h4>
                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                </div>
                <div class="modal-body">
                    <div class='form-floating mb-2'>
                        <input type="text" class='form-control' name='title' placeholder='หัวข้อ' required>
                        <label for="หัวข้อ">หัวข้อ</label>
                    </div>
                    <div class='form-floating'>
                        <textarea name="body" class='form-control' style='min-height:100px' placeholder='คำอธิบาย'></textarea>
                        <label for="คำอธิบาย">คำอธิบาย</label>
                    </div>
                    <div class='my-2 fw-bold'>เขียนคำถาม...</div>
                    <div class='d-flex flex-column mb-2' id='questions'>
                        <div class='d-flex align-items-center gap-2 mb-2'>
                            <h6><span>1</span>. </h6>
                            <input type="text" class='form-control' name='questions[]' placeholder='คำถาม' required>
                            <button type='button' class='d-none btn btn-outline-danger remove-question'>ลบ</button>
                        </div>                        
                    </div>

                    <button class='btn btn-outline-teal w-100' type='button' id='addQuestion'>เพิ่มข้อคำถาม+</button>

                    <div class="form-check mt-2 d-flex align-items-center gap-2">
                        <input type="checkbox" class='form-check-input cursor-pointer' id='check-additional-question' onchange="$(this).prop('checked') === true ? $('#additional-question').removeClass('d-none').find('input').val(null) : $('#additional-question').addClass('d-none').find('input').val(null)" name='additional'>
                        <label for="check-additional-question" class="form-check-label fw-bold fs-6 cursor-pointer">คำถามสำหรับให้ตอบกลับ</label>
                    </div>

                    <div class="form-floating d-none mt-2" id='additional-question'>
                        <input type="text" class='form-control' placeholder='คำถามเพิ่มเติม' name='additional'>
                        <label for="คำถามเพิ่มเติม">คำถามเพิ่มเติม</label>
                    </div>
                </div>
                <div class="modal-footer d-flex w-100 align-items-center">
                    <div class="w-100 d-flex align-items-center gap-2">
                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                        <button type="submit" name='addAssessment' class='btn btn-success w-100'>เพิ่ม</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>


<div class='overflow-auto h-100 mt-2'>
<?php 
$fetchAsessment = sql("SELECT * FROM assessments");
if($fetchAsessment->rowCount() > 0){ ?>
    <div class='overflow-hidden rounded-xl table-responsive'>
        <table class='table table-striped text-center align-middle'>
            <thead>
                <tr>
                    <td style="width: 90px;">#</td>
                    <td style="width: 520px;">แบบประเมิน/สอบถาม</td>
                    <td style="width: 150px;">ตอบกลับ</td>
                    <td style="width: 150px;">เข้าชม</td>
                    <td style="width: 200px;">จัดการ</td>
                </tr>
            </thead>
            <tbody>
                <?php 
                $index=1;
                while($assessment = $fetchAsessment->fetch()){ ?>
                    <tr data-search-item>
                        <td>
                            <div class='text-center fw-bold'><?= $index ?></div>
                        </td>
                        <td>
                            <h6 data-search-keyword='<?= $assessment["title"] ?>'><?= $assessment["title"] ?></h6>
                            <div><?= $assessment["body"] ?></div>
                        </td>
                        <td><?= sql("SELECT COUNT(*) as count FROM assessment_responses WHERE assessment_id = ?",[$assessment["assessment_id"]])->fetch()["count"]; ?> คน</td>
                        <td><?= $assessment["visitors"] ?> ครั้ง</td>
                        <td>
                            <div class='d-flex flex-column align-items-center gap-1 w-100'>
                                <button type='button' data-bs-toggle='modal' class='btn btn-teal w-100' data-bs-target='#detailAssessment-<?= $assessment["assessment_id"] ?>'>รายละเอียด</button>
                                <div class='d-flex gap-2 w-100'>
                                    <button type='button' data-bs-toggle='modal' class='btn btn-warning w-100' data-bs-target='#editAssessment-<?= $assessment["assessment_id"] ?>'>แก้ไข</button>
                                    <button type='button' data-bs-toggle='modal' class='btn btn-danger w-100' data-bs-target='#deleteAssessment-<?= $assessment["assessment_id"] ?>'>ลบ</button>
                                </div>

                                <div class="modal fade" id='detailAssessment-<?= $assessment["assessment_id"] ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div action="../api/admin/manageAssessments.php?assessment_id=<?= $assessment["assessment_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                            <div class="modal-header">
                                                <h4 class="modal-title">รายละเอียดแบบสอบถาม/ประเมิน</h4>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class="modal-body">
                                                
                                            </div>
                                            <div class="modal-footer d-flex w-100 align-items-center">
                                                <div class="w-100 d-flex align-items-center gap-2">
                                                    <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                    <a href="report-assessment.php?assessment_id=<?= $assessment["assessment_id"] ?>" name='editAssessment' class='btn btn-teal w-100'>พิมพ์รายงาน</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="modal fade" id='editAssessment-<?= $assessment["assessment_id"] ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../api/admin/manageAssessments.php?assessment_id=<?= $assessment["assessment_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                            <div class="modal-header">
                                                <h4 class="modal-title">แก้ไขแบบสอบถาม/ประเมิน</h4>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class='form-floating mb-2'>
                                                    <input type="text" class='form-control' name='title' placeholder='หัวข้อ' value='<?= $assessment["title"] ?>' required>
                                                    <label for="หัวข้อ">หัวข้อ</label>
                                                </div>
                                                <div class='form-floating'>
                                                    <textarea name="body" class='form-control' style='min-height:100px' placeholder='คำอธิบาย'><?= $assessment["body"] ?></textarea>
                                                    <label for="คำอธิบาย">คำอธิบาย</label>
                                                </div>
                                                <div class='my-2 fw-bold text-start'>คำถาม...</div>
                                                <div class='d-flex flex-column mb-2' id='questions'>
                                                    <?php 
                                                    foreach(json_decode($assessment["questions"]) as $index=>$q){ ?>
                                                        <div class='d-flex align-items-center gap-2 mb-2'>
                                                            <h6><span><?= $index + 1 ?></span>. </h6>
                                                            <input type="text" class='form-control' name='questions[]' placeholder='คำถาม' value="<?= $q ?>">
                                                        </div>
                                                    <?php } 
                                                    ?>  
                                                </div>

                                                <?php 
                                                if($assessment["additional"] !== null){ ?>
                                                    <div class="form-floating mt-2" id='additional-question'>
                                                        <input type="text" class='form-control' placeholder='คำถามเพิ่มเติม' name='additional' value='<?= $assessment["additional"]; ?>' required>
                                                        <label for="คำถามเพิ่มเติม">คำถามเพิ่มเติม</label>
                                                    </div>
                                                <?php } ?>

                                            </div>
                                            <div class="modal-footer d-flex w-100 align-items-center">
                                                <div class="w-100 d-flex align-items-center gap-2">
                                                    <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                    <button type="submit" name='editAssessment' class='btn btn-warning w-100'>บันทึก</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="modal fade" id='deleteAssessment-<?= $assessment["assessment_id"] ?>'>
                                    <div class="modal-dialog modal-dialog-centered">
                                        <form action="../api/admin/manageAssessments.php?assessment_id=<?= $assessment["assessment_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                            <div class="modal-header">
                                                <h4 class="modal-title">ลบแบบสอบถาม/ประเมิน</h4>
                                                <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                            </div>
                                            <div class="modal-body">
                                                    <h4>ID: <?= $assessment["assessment_id"] ?></h4>
                                                    <h5 class='text-center'>คุณแน่ใจที่จะทำการลบแบบสอบถาม/ประเมิน นี้หรือไม่?</h5>
                                            </div>
                                            <div class="modal-footer d-flex w-100 align-items-center">
                                                <div class="w-100 d-flex align-items-center gap-2">
                                                    <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                    <button type="submit" name='deleteAssessment' class='btn btn-danger w-100'>ลบ</button>
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
    <h5 class='text-center text-muted'>ยังไม่มีแบบสอบถาม/ประเมิน...</h5>
<?php } ?>
    
</div>