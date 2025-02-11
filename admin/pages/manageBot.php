<div class='d-flex justify-content-between mb-2'>
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
    <button data-bs-toggle='modal' data-bs-target="#addQue-Res" type="button" class='btn btn-teal'>เพิ่มคำถามและคำตอบ+</button>

    <div class="modal fade" id='addQue-Res'>
        <div class="modal-dialog modal-dialog-centered">
            <form action='../api/admin/manageBot.php' method='post' class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">เพิ่มคำถามและคำตอบ</h5>
                    <button class="btn-close" data-bs-dismiss='modal' type='button'></button>
                </div>
                <div class="modal-body">
                    <div class="form-floating mb-2">
                        <input type="text" class="form-control" placeholder='คำถาม (,)' name='questions'>
                        <label for="คำถาม (,)">คำถาม (,)</label>
                    </div>
                    <div class="form-floating">
                        <input type="text" class="form-control" placeholder='คำตอบ (,)' name='responses'>
                        <label for="คำตอบ (,)">คำตอบ (,)</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="d-flex gap-2 flex-row w-100">
                        <button class="btn btn-light w-100" type='button' data-bs-dismiss='modal'>ปิด</button>
                        <button class="btn btn-teal w-100" type='submit' name='addQue-Res'>เพิ่ม</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>
<div class='overflow-auto h-100'>

    <?php  $bot_responses = sql("SELECT * FROM bot_responses"); 
    if($bot_responses->rowCount() > 0){ ?>
        <div class='table-responsive'>
            <table class='table table-striped table-bordered'>
                <thead>
                    <tr>
                        <th style="width: 5%;"></th>
                        <th style="width: 50%;">คำถาม</th>
                        <th style="width: 50%;">คำตอบ</th>
                        <th style="width: 150px;">จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $index = 1;
                    while($bot = $bot_responses->fetch()){
                        $questions = json_decode($bot["questions"]);
                        $responses = json_decode($bot["responses"]);

                        echo implode(", ",$responses);
                        ?>
                        <tr data-search-item>
                            <td class='text-center'><?= $index; ?></td>
                            <td data-search-keyword="<?= implode(", ",$questions) ?>"><?= implode(", ",$questions) ?></td>
                            <td data-search-keyword="<?= implode(", ",$responses) ?>"><?= implode(", ",$responses) ?></td>
                            <td>
                                <div class='d-flex align-items-center gap-2'>
                                    <button type="button" class="btn btn-warning" data-bs-toggle='modal' data-bs-target='#editQue-Res-<?= $bot["response_id"] ?>'>แก้ไข</button>

                                    <div class="modal fade" id='editQue-Res-<?= $bot["response_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action='../api/admin/manageBot.php' method='post' class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">เพิ่มคำถามและคำตอบ</h5>
                                                    <button class="btn-close" data-bs-dismiss='modal' type='button'></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-floating mb-2">
                                                        <input type="text" class="form-control" placeholder='คำถาม (,)' name='questions' value='<?= implode(", ",$questions) ?>'>
                                                        <label for="คำถาม (,)">คำถาม (,)</label>
                                                    </div>
                                                    <div class="form-floating">
                                                        <input type="text" class="form-control" placeholder='คำตอบ (,)' name='responses' value='<?= implode(", ",$responses) ?>'>
                                                        <label for="คำตอบ (,)">คำตอบ (,)</label>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <div class="d-flex gap-2 flex-row w-100">
                                                        <button class="btn btn-light w-100" type='button' data-bs-dismiss='modal'>ปิด</button>
                                                        <button class="btn btn-warning w-100" type='submit' name='editQue-Res'>แก้ไข</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <a href='../api/admin/manageBot.php?deleteBotResponse&response_id=<?= $bot["response_id"] ?>' class="btn btn-danger">ลบ</a>
                                </div>
                            </td>
                        </tr>
                    <?php $index++; } ?>
                </tbody>
            </table>
        </div>
    <?php }else{ ?>
        <h5 class='mt-5 text-muted text-center'>ยังไม่มีคำถามและคำตอบของแชทบอทในขณะนี้...</h5>
    <?php } ?>
</div>