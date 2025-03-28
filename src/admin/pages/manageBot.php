<div class='d-flex justify-content-between mb-2'>
    <div class='d-flex align-items-center gap-2 position-relative'>
        <input type="search" name='search' class='form-control ps-5' placeholder='ค้นหา' oninput="searchResult($(this))">
        <label for="" style='position: absolute;top: 0px;left: 8px;font-size: 25px;'>🔍</label>
        <button type='button' class='btn btn-teal'>ค้นหา</button>
    </div>
    <button data-bs-toggle='modal' data-bs-target="#add" type="button" class='btn btn-teal'>เพิ่มคำถามและคำตอบ+</button>

    <div class="modal fade" id='add'>
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
                        <button class="btn btn-teal w-100" type='submit' name='add'>เพิ่ม</button>
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
            <table class='table table-striped align-middle'>
                <thead>
                    <tr>
                        <th style="width: 5%;"></th>
                        <th style="width: 35%;">คำถาม</th>
                        <th style="width: 35%;">คำตอบ</th>
                        <th>จัดการ</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $index = 1;
                    while($bot = $bot_responses->fetch()){
                        $questions = json_decode($bot["questions"]);
                        $responses = json_decode($bot["responses"]);
                        ?>
                        <tr data-search-item>
                            <td class='text-center'><?= $index; ?></td>
                            <td data-search-keyword="<?= implode(", ",$questions) ?>"><?= implode(", ",$questions) ?></td>
                            <td data-search-keyword="<?= implode(", ",$responses) ?>"><?= implode(", ",$responses) ?></td>
                            <td>
                                <div class='d-flex align-items-center gap-2 w-100'>
                                    <button type="button" class="btn btn-warning w-100" data-bs-toggle='modal' data-bs-target='#editQue-Res-<?= $bot["response_id"] ?>'>แก้ไข</button>

                                    <div class="modal fade" id='editQue-Res-<?= $bot["response_id"] ?>'>
                                        <div class="modal-dialog modal-dialog-centered">
                                            <form action='../api/admin/manageBot.php?response_id=<?= $bot["response_id"] ?>' method='post' class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">แก้ไขคำถามและคำตอบ</h5>
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
                                                        <button class="btn btn-warning w-100" type='submit' name='edit'>แก้ไข</button>
                                                    </div>
                                                </div>
                                            </form>
                                        </div>
                                    </div>

                                    <a href='../api/admin/manageBot.php?delete&response_id=<?= $bot["response_id"] ?>' class="btn btn-danger w-100">ลบ</a>
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