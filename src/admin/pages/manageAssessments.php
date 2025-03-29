<div class='d-flex justify-content-between'>
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
    <button data-bs-toggle='modal' data-bs-target="#addAssessment" type="button" class='btn btn-teal'>เพิ่ม+</button>

    <div class="modal fade" id='addAssessment'>
        <div class="modal-dialog modal-dialog-centered">
            <form action="../api/admin/manageAssessments" class="modal-content" method='post' enctype='multipart/form-data'>
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

    <script>
        $("#addQuestion").on('click', () => {
            const lastQeustion = $("#questions div:last-child");
            console.log(lastQeustion);

            const newQuestion = lastQeustion.clone();
            const lastQuestionNumber = Number(lastQeustion.find("span").text());
            const newQuestionNumner = lastQuestionNumber + 1;

            newQuestion.find("button").removeClass("d-none");
            newQuestion.find('span').text(newQuestionNumner);
            newQuestion.find("input").val(null);

            $("#questions").append(newQuestion);
        });

        $(document).on("click", ".remove-question", function() {
            $(this).parent().remove();
            $('#questions div').each(function(index) {
                $(this).find('span').text(index + 1);
            });
        });

        $("additional-question").on("change", function() {
            $(this).parent()
        });
    </script>
</div>

<div class='overflow-auto h-100 mt-2'>
    <?php $fetchAsessment = sql("SELECT * FROM assessments");
    if ($fetchAsessment->rowCount() > 0) { ?>
        <div class='overflow-hidden rounded-xl table-responsive'>
            <table class="table table-striped text-center align-middle">
                <thead>
                    <tr>
                        <th style='width:90px'>#</th>
                        <th style='width:520px'>แบบสอบถาม/ประเมิน</th>
                        <th style='width:150px'>จำนวนผู้ตอบกลับ</th>
                        <th style='width:150px'>จำนวนผู้เข้าชม</th>
                        <th style='width:200px'>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $number = 1;
                    while ($assessment = $fetchAsessment->fetch()) { ?>
                        <tr data-search-item>
                            <td>
                                <div class='text-center fw-bold'><?= $number ?></div>
                            </td>
                            <td>
                                <h6 data-search-keyword='<?= $assessment["title"] ?>'><?= $assessment["title"] ?></h6>
                                <div><?= $assessment["body"] ?></div>
                            </td>
                            <td><?= sql("SELECT COUNT(*) as count FROM assessment_responses WHERE assessment_id = ?", [$assessment["assessment_id"]])->fetch()["count"]; ?> คน</td>
                            <td><?= $assessment["visitors"] ?> ครั้ง</td>
                            <td>
                                <div class='d-flex flex-column align-items-center gap-1 w-100'>
                                    <button type='button' data-bs-toggle='modal' class='btn btn-teal w-100' data-bs-target='#detailAssessment-<?= $assessment["assessment_id"] ?>'>รายงาน</button>
                                    <div class='d-flex gap-2 w-100'>
                                        <button type='button' data-bs-toggle='modal' class='btn btn-warning w-100' data-bs-target='#editAssessment-<?= $assessment["assessment_id"] ?>'>แก้ไข</button>
                                        <button type='button' data-bs-toggle='modal' class='btn btn-danger w-100' data-bs-target='#deleteAssessment-<?= $assessment["assessment_id"] ?>'>ลบ</button>
                                    </div>

                                    <div class="modal fade" id='detailAssessment-<?= $assessment["assessment_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered modal-xl">
                                            <div action="../api/admin/manageAssessments?assessment_id=<?= $assessment["assessment_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
                                                <div class="modal-header">
                                                    <h4 class="modal-title">รายละเอียดแบบสอบถาม/ประเมิน</h4>
                                                    <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                                </div>
                                                <div class="modal-body">
                                                    <?php
                                                    $questions = json_decode($assessment["questions"]);

                                                    $assessment_responses = sql("SELECT *,assessment_responses.created_at as ar_created_at FROM assessment_responses LEFT JOIN users ON assessment_responses.user_id = users.user_id WHERE assessment_id = ?", [$assessment["assessment_id"]]);
                                                    if ($assessment_responses->rowCount() > 0) { ?>

                                                        <div class='text-start'>
                                                            <h4><?= $assessment["title"] ?></h4>
                                                            <div><?= $assessment["body"] ?></div>
                                                        </div>

                                                        <div class="table-responsive">
                                                            <table class="table table-striped">
                                                                <thead>
                                                                    <tr>
                                                                        <th></th>
                                                                        <th style='width:250px;'></th>
                                                                        <?php foreach ($questions as $index => $q) { ?>
                                                                            <th>
                                                                                <div class='text-center'>
                                                                                    <?= $q ?>
                                                                                </div>
                                                                            </th>
                                                                        <?php } ?>
                                                                        <th>
                                                                            <div class='text-center'>
                                                                                <?= $assessment["additional"] ?>
                                                                            </div>
                                                                        </th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $number = 1;
                                                                    while ($ar = $assessment_responses->fetch()) { ?>
                                                                        <tr>
                                                                            <td class='align-middle'><?= $number; ?></td>
                                                                            <td>
                                                                                <div class='d-flex align-items-center gap-1'>
                                                                                    <img src="<?= imagePath("user_images", $ar["image"]) ?>" width='50px' height='50px' class='rounded-circle object-fit-cover'>
                                                                                    <div class='text-start'>
                                                                                        <div><?= $ar["firstname"] ?> <?= $ar["lastname"] ?></div>
                                                                                        <div><?= $ar['ar_created_at'] ?></div>
                                                                                    </div>
                                                                                </div>
                                                                            </td>
                                                                            <?php
                                                                            $reponses = json_decode($ar["responses"]);
                                                                            foreach ($reponses as $index => $res) { ?>
                                                                                <td class='align-middle'>
                                                                                    <?= $res; ?>
                                                                                </td>
                                                                            <?php } ?>
                                                                            <td>
                                                                                <?= $ar["additional"]  ?>
                                                                            </td>
                                                                        </tr>
                                                                    <?php $number++;
                                                                    } ?>
                                                                </tbody>
                                                            </table>

                                                        </div>

                                                        <div class="mt-2">
                                                            <h5 class='mb-4 fw-bold'>สรุปผลการประเมิน</h5>
                                                            <?php
                                                            $total_responses = $assessment_responses->rowCount();
                                                            $assessment_responses = sql("SELECT *,assessment_responses.created_at as ar_created_at FROM assessment_responses LEFT JOIN users ON assessment_responses.user_id = users.user_id WHERE assessment_id = ?", [$assessment["assessment_id"]]);
                                                            $question_counts = [];
                                                            foreach ($questions as $index => $q) {
                                                                $question_counts[$index] = array_fill(1, 5, 0);
                                                            }

                                                            while ($ar = $assessment_responses->fetch()) {
                                                                $responses = json_decode($ar["responses"]);
                                                                foreach ($responses as $index => $response) {
                                                                    $question_counts[$index][(int)$response]++;
                                                                }
                                                            }

                                                            foreach ($questions as $index => $question) {
                                                                $max_count = max($question_counts[$index]);
                                                            ?>
                                                                <div class="mb-4">
                                                                    <div class="fw-bold mb-2"><?= $question ?></div>
                                                                    <?php for ($rating = 1; $rating <= 5; $rating++) {
                                                                        $count = $question_counts[$index][$rating];
                                                                        $percentage = ($total_responses > 0) ? ($count / $total_responses) * 100 : 0;
                                                                    ?>
                                                                        <div class="d-flex align-items-center mb-2 gap-2">
                                                                            <div>ระดับ <?= $rating ?></div>
                                                                            <div class="flex-1 rounded-2 overflow-hidden" style='background: #e9ecef;height: 25px'>
                                                                                <div class='h-100' style="width: <?= $percentage ?>%; background: var(--teal-light); background-image: linear-gradient(45deg,rgba(255, 255, 255, 0.15) 25%,transparent 25%,transparent 50%,rgba(255, 255, 255, 0.15) 50%,rgba(255, 255, 255, 0.15) 75%,transparent 75%,transparent);background-size: 1rem 1rem;animation: progress-bar-stripes 1s linear infinite;"></div>
                                                                            </div>
                                                                            <div style='width:40px;' class="text-end text-muted"><?= number_format($percentage, 1) ?>%</div>
                                                                        </div>
                                                                    <?php } ?>
                                                                </div>
                                                            <?php } ?>
                                                        </div>
                                                    <?php } else { ?>
                                                        <h5 class="my-5 text-muted">ยังไม่มีผู้ตอบกลับ...</h5>
                                                    <?php }
                                                    ?>
                                                </div>

                                                <div class="modal-footer d-flex w-100 align-items-center">
                                                    <div class="w-100 d-flex align-items-center gap-2">
                                                        <button type="reset" data-bs-dismiss='modal' class='btn btn-light w-100'>ปิด</button>
                                                        <?php
                                                        if ($assessment_responses->rowCount() > 0) { ?>
                                                            <a target="_blank" href="report-assessment?assessment_id=<?= $assessment["assessment_id"] ?>" name='editAssessment' class='btn btn-teal w-100'>พิมพ์รายงาน</a>
                                                        <?php }
                                                        ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal fade" id='editAssessment-<?= $assessment["assessment_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action="../api/admin/manageAssessments?assessment_id=<?= $assessment["assessment_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
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
                                                    <div class='d-flex flex-column mb-2'>
                                                        <?php
                                                        foreach (json_decode($assessment["questions"]) as $index => $q) { ?>
                                                            <div class='d-flex align-items-center gap-2 mb-2'>
                                                                <h6><span><?= $index + 1 ?></span>. </h6>
                                                                <input type="text" class='form-control' name='questions[]' placeholder='คำถาม' value="<?= $q ?>">
                                                            </div>
                                                        <?php }
                                                        ?>
                                                    </div>

                                                    <?php
                                                    if ($assessment["additional"] !== null) { ?>
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
                                            <form action="../api/admin/manageAssessments?assessment_id=<?= $assessment["assessment_id"] ?>" class="modal-content" method='post' enctype='multipart/form-data'>
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
                    <?php $number++;
                    } ?>
                </tbody>
            </table>
        </div>
    <?php } else { ?>
        <h5 class='text-center text-muted'>ยังไม่มีแบบสอบถาม/แบบประเมินในขณะนี้...</h5>
    <?php } ?>
</div>