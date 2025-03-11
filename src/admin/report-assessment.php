<?php
require_once("../config.php");
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>รายงานผลการประเมิน</title>
    <?php require_once("../link.php"); ?>
    <style>
        .report-header {
            text-align: center;
            margin-bottom: 3rem;
        }

        .summary-box {
            border-radius: 8px;
            padding: 1.5rem;
            margin: 1rem 0;
        }

        .graph-bar-container {
            background: #e9ecef;
            height: 25px;
            border-radius: 4px;
            overflow: hidden;
            flex: 1;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.1);
        }

        .graph-bar {
            height: 100%;
            background: var(--teal-light);
            position: relative;
            border-radius: 4px;
            background-image: linear-gradient(45deg,
                    rgba(255, 255, 255, 0.15) 25%,
                    transparent 25%,
                    transparent 50%,
                    rgba(255, 255, 255, 0.15) 50%,
                    rgba(255, 255, 255, 0.15) 75%,
                    transparent 75%,
                    transparent);
            background-size: 1rem 1rem;
            animation: progress-bar-stripes 1s linear infinite;
        }

        .rating-label {
            min-width: 80px;
            font-weight: 500;
        }

        .percentage-label {
            min-width: 100px;
            text-align: right;
            font-weight: 500;
        }

        .flex-1 {
            flex: 1;
        }

        @media print {
            .no-print {
                display: none;
            }

            .summary-box {
                border: 1px solid #000;
                padding: 1rem;
                margin: 1rem 0;
            }

            .graph-bar-container {
                border: 1px solid #000;
                box-shadow: none;
                height: 25px;
            }

            .graph-bar {
                background: #000 !important;
                animation: none;
                background-image: none !important;
                border-right: 1px solid #000;
            }

            .table {
                border-collapse: collapse !important;
            }

            .table td,
            .table th {
                border: 1px solid #000 !important;
            }

            .text-muted {
                color: #000 !important;
            }

            img {
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            @page {
                margin: 2cm;
            }

            .table {
                page-break-inside: avoid;
            }

            .graph-container {
                page-break-inside: avoid;
            }
        }
    </style>
</head>

<body class="">
    <?php
    if (isset($_GET["assessment_id"])) {
        $assessment_id = $_GET["assessment_id"];
        $assessment = sql("SELECT * FROM assessments WHERE assessment_id = ?", [$assessment_id])->fetch();
        $questions = json_decode($assessment["questions"]);
        $assessment_responses = sql("SELECT *, assessment_responses.created_at as ar_created_at 
                                   FROM assessment_responses 
                                   LEFT JOIN users ON assessment_responses.user_id = users.user_id 
                                   WHERE assessment_id = ?", [$assessment_id]);
        $total_responses = $assessment_responses->rowCount();
    ?>
        <div class="container my-5">
            <div class="text-end mb-4 no-print">
                <button onclick="window.print()" class="btn btn-primary">
                    พิมพ์รายงาน 🖨️
                </button>
            </div>

            <div class="report-header">
                <h2 class="mb-3">รายงานผลการประเมิน</h2>
                <h4 class="text-muted"><?= $assessment["title"] ?></h4>
                <p class="text-muted">จำนวนผู้ตอบแบบประเมิน: <?= $total_responses ?> คน</p>
            </div>

            <div class="summary-box">
                <h5 class="mb-3">รายละเอียดแบบประเมิน</h5>
                <div><?= $assessment["body"] ?></div>
            </div>

            <div class="mt-5">
                <h4 class="mb-4">สรุปผลการประเมิน</h4>
                <?php
                $question_counts = [];
                foreach ($questions as $index => $q) {
                    $question_counts[$index] = array_fill(1, 5, 0);
                }

                $assessment_responses->execute();
                while ($ar = $assessment_responses->fetch()) {
                    $responses = json_decode($ar["responses"]);
                    foreach ($responses as $index => $response) {
                        $question_counts[$index][(int)$response]++;
                    }
                }

                foreach ($questions as $index => $question) {
                ?>
                    <div class="graph-container">
                        <h5 class="mb-3"><?= $question ?></h5>
                        <?php
                        for ($rating = 1; $rating <= 5; $rating++) {
                            $count = $question_counts[$index][$rating];
                            $percentage = ($total_responses > 0) ? ($count / $total_responses) * 100 : 0;
                        ?>
                            <div class="d-flex align-items-center mb-2 gap-2">
                                <div class="rating-label">ระดับ <?= $rating ?></div>
                                <div class="graph-bar-container">
                                    <div class="graph-bar" style="width: <?= $percentage ?>%"></div>
                                </div>
                                <div class="percentage-label">
                                    <?= $count ?> คน (<?= number_format($percentage, 1) ?>%)
                                </div>
                            </div>
                        <?php } ?>
                    </div>
                <?php } ?>
            </div>

            <div class="mt-5">
                <h4 class="mb-4">รายละเอียดการตอบแบบประเมิน</h4>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th style="width: 50px;">#</th>
                                <th style="width: 200px;">ผู้ตอบแบบประเมิน</th>
                                <?php foreach ($questions as $q) { ?>
                                    <th><?= $q ?></th>
                                <?php } ?>
                                <th>ความคิดเห็นเพิ่มเติม</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $assessment_responses->execute();
                            $number = 1;
                            while ($ar = $assessment_responses->fetch()) {
                                $responses = json_decode($ar["responses"]);
                            ?>
                                <tr>
                                    <td><?= $number ?></td>
                                    <td>
                                        <div class="d-flex align-items-center gap-2">
                                            <img src="<?= imagePath("user_images", $ar["image"]) ?>"
                                                width="40px" height="40px"
                                                class="rounded-circle object-fit-cover">
                                            <div>
                                                <div><?= $ar["firstname"] ?> <?= $ar["lastname"] ?></div>
                                                <small class="text-muted"><?= $ar["ar_created_at"] ?></small>
                                            </div>
                                        </div>
                                    </td>
                                    <?php foreach ($responses as $response) { ?>
                                        <td class="text-center"><?= $response ?></td>
                                    <?php } ?>
                                    <td><?= $ar["additional"] ?></td>
                                </tr>
                            <?php $number++;
                            } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    <?php } ?>
</body>

</html>