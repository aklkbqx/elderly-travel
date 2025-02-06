<?php 
require_once("config.php");

$row = null;
if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    msg("warning","คำเตือน","ไม่สามารถเข้าถึงหน้านี้ได้","admin/");
}

$score = [
    "5",
    "4",
    "3",
    "2",
    "1",
];

?>

<!DOCTYPE html>
<html lang="en" data-bs-theme='light'>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Assessments / แบบประเมิน</title>
    <?php
    require_once("link.php");
    ?>
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

        <?php
        if(isset($_GET["assessment_id"])){
            $assessment_id = $_GET["assessment_id"];
            $assessment = sql("SELECT * FROM assessments WHERE assessment_id = ?",[$assessment_id])->fetch();
            $questions = json_decode($assessment["questions"]);
            $assessment_responses = $row ? sql("SELECT * FROM assessment_responses WHERE assessment_id = ? AND user_id = ?",[$assessment_id,$row["user_id"]])->fetch() : null;
            $responses = $assessment_responses ? json_decode($assessment_responses["responses"]) : null;
        ?>
            <form action="api/assessments.php?assessment_id=<?= $assessment_id ?>" method='post'>
                <div class='mt-2 bg-white overflow-hidden rounded-xl border shadow-sm'>
                    <div class='bg-teal w-100' style='height:20px'></div>
                    <div class='px-5 py-4 position-relative'>
                        <?php if($responses){ ?>
                            <div class="badge text-bg-success fw-bold fs-6 position-absolute" style='top:10px;right:10px'>ทำแล้ว</div>
                        <?php } ?>
                        <h5><?= $assessment["title"] ?></h5>
                        <div><?= $assessment["body"] ?></div>
                    </div>
                </div>

                <div class='mt-2 bg-white overflow-hidden position-relative rounded-xl border shadow-sm'>
                    <div class='table-responsive p-4'>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th style='width:500px'></th>
                                    <?php foreach($score as $index=>$val){ ?>
                                        <th style='width:150px' class='text-center'><?= $val ?></th>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach($questions as $index=>$q){ ?>
                                    <tr>
                                        <td>
                                            <?= $q; ?>
                                        </td>
                                        <?php foreach($score as $val){ ?>
                                            <td>
                                                <label for="form-check-<?= $val ?>-<?= $index ?>" class='d-flex justify-content-center align-items-center cursor-pointer'>
                                                    <div class='form-check'>
                                                        <input <?= $responses && $responses[$index] == $val ? 'checked' : 'value="' . $val .'"' ?> <?= $responses ? "disabled" : "" ?>  id='form-check-<?= $val ?>-<?= $index ?>' type="radio" class='form-check-input cursor-pointer' name='response-<?= $index; ?>' required>
                                                    </div>
                                                </label>
                                            </td>
                                        <?php } ?>
                                    </tr>
                                <?php } ?>
                                <?php ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <?php 
                if($assessment["additional"]){ ?>
                    <div class='mt-2 bg-white overflow-hidden position-relative rounded-xl border shadow-sm'>
                        <div class='px-5 py-4'>
                            <h5><?= $assessment["additional"] ?></h5>
                            <input type="text" class="form-control mt-2" name='additional' placeholder='คำตอบ' required <?= $responses ? 'value="'. $assessment_responses["additional"] .'" disabled' : "" ?>>
                        </div>
                    </div>
                <?php } ?>

                <div class='d-flex justify-content-end mt-2'>
                    <?php 
                    if(isset($_SESSION["user_login"])){ ?>
                        <?php if($responses){ ?>
                            <a href="javascript:window.history.back()" class='btn btn-secondary d-flex align-items-center gap-1'>
                                <img src="<?= imagePath("web_images/icons","chevron-back.png") ?>" width='15px' height='15px' class='object-fit-cover'>
                                ย้อนกลับ
                            </a>
                        <?php }else{ ?>
                            <button type="submit" name='sendAssessment' class='btn btn-teal px-4'>ส่ง</button>
                        <?php } ?>
                    <?php }else{ ?>
                        <a href="login.php" class='btn btn-teal px-4'>เข้าสู่ระบบ</a>
                    <?php } ?>
                </div>
            </form>

            <script>
                setTimeout(() => {
                    $.ajax({
                        url: "api/assessments.php?visitors&assessment_id=<?= $assessment_id ?>",
                        type: "POST",
                    });
                }, 5000);
            </script>
        <?php } ?>
    </div>
</body>
</html>