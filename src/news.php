<?php
require_once("config.php");

$row = null;
if (isset($_SESSION["user_login"])) {
    $row = sql("SELECT * FROM users WHERE user_id = ?", [$_SESSION["user_login"]])->fetch();
} elseif (isset($_SESSION["admin_login"])) {
    msg("warning", "คำเตือน", "ไม่สามารถเข้าถึงหน้านี้ได้", "admin/");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News</title>
    <?php require_once("link.php"); ?>
</head>

<body>
    <?php
    require_once("components/nav.php");
    require_once("components/popChats.php");
    require_once("components/options.php");
    ?>
    <div class='container mb-5' style='margin-top:10rem'>

        <?php backPage('/'); ?>

        <?php if (isset($_GET["news_id"])) {
            $news_id = $_GET["news_id"];
            $news = sql("SELECT * FROM news WHERE news_id = ?", [$news_id])->fetch();
            $news_comnents = sql("SELECT *,news_comments.created_at as comment_created_at FROM news_comments LEFT JOIN users ON news_comments.user_id = users.user_id WHERE news_id = ?", [$news["news_id"]]);

            $heart = "heart-black.png";
            if (isLogin()) {
                $news_likes = sql("SELECT * FROM news_likes WHERE news_id = ? AND user_id = ?", [$news_id, $row["user_id"]]);
                while ($news_like = $news_likes->fetch()) {
                    if ($news_like["user_id"] == $row["user_id"]) {
                        $heart = "heart-red.png";
                    } else {
                        $heart = "heart-black.png";
                    }
                }
            }
        ?>
            <div class='p-2 rounded-xl shadow border mt-2'>
                <img src="<?= imagePath("news_images", $news["image"]) ?>" width='100%' height='500px' class='rounded-4 border object-fit-cover'>
                <div class='p-2'>
                    <h3 class='mt-2'><?= $news["title"] ?></h3>
                    <div><?= $news["body"] ?></div>

                    <div class='d-flex justify-content-between mt-4 mb-2'>
                        <?php
                        if (isLogin()) { ?>
                            <a href="./api/news.php?like&news_id=<?= $news_id ?>" class='text-decoration-none d-flex align-items-center gap-1 btn btn-light'>
                                <img src="<?= imagePath("web_images/icons", $heart) ?>" width='30px' height='30px' class='object-fit-cover'>
                                <div class=''><?= sql("SELECT COUNT(*) as count FROM news_likes WHERE news_id = ?", [$news_id])->fetch()["count"] ?></div>
                            </a>
                        <?php } ?>
                        <div class='d-flex align-items-center gap-2'>
                            <img src="<?= imagePath("web_images/icons", "eye.svg") ?>" width='30px' height='30px' class='object-fit-cover svg-icon'>
                            <div>จำนวนผู้เข้าชม <span><?= $news["visitors"] ?></span></div>
                        </div>
                    </div>

                    <?php
                    if (isLogin()) { ?>
                        <form method='post' action='api/news.php?comment&news_id=<?= $news_id ?>' class='d-flex align-items-center gap-2'>
                            <input type="text" name='comment' class='form-control' placeholder='แสดงความคิดเห็น'>
                            <button type='submit' name='submit_comment' class='btn btn-teal'>ส่ง</button>
                        </form>
                    <?php }
                    ?>

                    <div class='mt-4'>
                        <?php while ($news_comnent = $news_comnents->fetch()) { ?>
                            <div class='d-flex align-items-center justify-content-between'>
                                <div class='d-flex align-items-start gap-2 mb-4'>
                                    <img src="<?= imagePath("user_images", $news_comnent["image"]); ?>" class='rounded-circle border object-fit-cover' width='50px' height='50px'>
                                    <div>
                                        <div class='d-flex align-items-center gap-2'>
                                            <h6 class='fw-bold'><?= $news_comnent["firstname"] ?> <?= $news_comnent["lastname"] ?></h6>
                                            <div><?= $news_comnent["comment_created_at"] ?></div>
                                        </div>
                                        <div class='text-muted'><?= $news_comnent["comment"] ?></div>
                                    </div>
                                </div>
                                <?php if (isLogin() && ($news_comnent["user_id"] == $row["user_id"])) { ?>
                                    <div class='d-flex align-items-center gap-2'>
                                        <a href="api/news.php?delete_comment&comment_id=<?= $news_comnent["comment_id"] ?>&news_id=<?= $news_id ?>" class='btn btn-danger'>ลบ</a>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php } ?>
                    </div>
                </div>
            </div>

            <?php
            $fetchAllNews = sql("SELECT * FROM news WHERE NOT news_id = ?", [$_GET["news_id"]]);
            if ($fetchAllNews->rowCount() > 0) { ?>
                <h5 class='mt-5 mb-2 fw-bold'>ข่าวสารประชาสัมพันธ์อื่นๆ</h5>
                <div class='list-group'>
                    <?php
                    while ($news = $fetchAllNews->fetch()) { ?>
                        <a href="news.php?news_id=<?= $news["news_id"] ?>" class='list-group-item list-group-item-action d-flex gap-2'>
                            <img src="<?= imagePath("news_images", $news["image"]) ?>" width="330px" height="125px" class='object-fit-cover rounded-xl'>
                            <div>
                                <div class='mb-2 fw-semibold fs-5'><?= $news["title"] ?></div>
                                <div class='d-flex align-items-center gap-1'>
                                    <img src="<?= imagePath("web_images/icons", "eye.svg") ?>" width='20px' height='20px' class='object-fit-cover svg-icon'>
                                    <div>จำนวนผู้เข้าชม <span><?= $news["visitors"] ?></span></div>
                                </div>
                                <div class='d-flex align-items-center gap-1'>
                                    <img src="<?= imagePath("web_images/icons", "heart-red.png") ?>" width='20px' height='20px' class='object-fit-cover'>
                                    <div>จำนวนผู้ถูกใจ <span><?= sql("SELECT count(*) as count FROM news_likes WHERE news_id = ?", [$news['news_id']])->fetch()["count"]; ?></span></div>
                                </div>
                            </div>
                        </a>
                    <?php } ?>
                </div>
            <?php } ?>


            <script>
                setTimeout(() => {
                    $.ajax({
                        url: "./api/news.php?visitors&news_id=<?= $news_id ?>",
                        type: "post"
                    })
                }, 2000);
            </script>
        <?php } else { ?>
            <div class='row mt-2'>
                <?php
                $fetchAllNews = sql("SELECT * FROM news");
                if ($fetchAllNews->rowCount() > 0) {
                    while ($news = $fetchAllNews->fetch()) { ?>
                        <div class='col-lg-4 mb-2'>
                            <div class='rounded-xl border overflow-hidden d-flex flex-column justify-content-between' style="height: 320px;">
                                <div>
                                    <img src="<?= imagePath("news_images", $news["image"]) ?>" width="100%" height='200px' class='object-fit-cover '>
                                    <div class="p-2">
                                        <h5 class='fw-bold'><?= $news["title"] ?></h5>
                                    </div>
                                </div>
                                <div class="px-2 pb-2">
                                    <a href="news.php?news_id=<?= $news["news_id"] ?>" class='btn btn-teal mt-2 w-100'>อ่านรายละเอียดเพิ่มเติม</a>
                                </div>
                            </div>
                        </div>
                    <?php }
                } else { ?>
                    <h4 clas='mt-5 text-muted text-center'>
                        ยังไม่มีข่าวสารอื่นๆ...
                    </h4>
                <?php } ?>
            </div>
        <?php } ?>
    </div>
</body>

</html>