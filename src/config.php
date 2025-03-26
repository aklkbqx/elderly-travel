<?php
session_start();
date_default_timezone_set('Asia/Bangkok');

$servername = "156.67.218.234";
// $servername = "mariadb";
$username = "username";
$password = "password";
$dbname = "elderly_db";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
} catch (PDOException $e) {
    echo $e->getMessage();
}

function sql($sql, $params = [])
{
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function msg($type, $title, $body, $location = null)
{
    $_SESSION[$type] = [$title, $body];
    if ($location) {
        header("location: $location");
        exit;
    }
}

foreach (["success", "warning", "danger"] as $index => $type) {
    if (isset($_SESSION[$type])) { ?>
        <div class="position-fixed opacity-0" style="top:10px;right:10px;z-index:999" id="alertMsg">
            <div class="p-3 position-relative overflow-hidden ps-4 shadow rounded-xl" style="width:350px;background-color: var(--bs-body-bg);">
                <div class="bg-<?= $type; ?> position-absolute" style="width:10px;height:100%;top:0;left:0;"></div>
                <h5><?= $_SESSION[$type][0]; ?></h5>
                <div class="position-absolute d-flex align-items-center gap-2" style="top:10px;right:10px;">
                    <div><span id="countMsg"></span>s</div>
                    <button id="btnCloseMsg" class="btn-close"></button>
                </div>
                <div><?= $_SESSION[$type][1]; ?></div>
            </div>
        </div>
    <?php unset($_SESSION[$type]);
    }
}

function linkPage($path, $target)
{
    return $_SERVER["REQUEST_URI"] == "/" ? "./$path/$target" : "../$path/$target";
}

function imagePath($path, $target)
{
    return $_SERVER["REQUEST_URI"] == "/" ? "./images/$path/$target" : "../images/$path/$target";
}

if (isset($_GET["logout"])) {
    $session = isset($_SESSION["user_login"]) ? "user_login" : (isset($_SESSION["admin_login"]) ? "admin_login" : (isset($_SESSION["doctor_login"]) ? "doctor_login" : null));
    if (isset($_SESSION[$session])) {
        sql("UPDATE users SET active_status=? WHERE user_id=?", ["offline", $_SESSION[$session]]);
        unset($_SESSION[$session]);
        msg("success", "สำเร็จ!", "ออกจากระบบแล้ว!", "/");
    }
}

function isLogin()
{
    return isset($_SESSION["user_login"]) || isset($_SESSION["admin_login"]) || isset($_SESSION["doctor_login"]);
}

function backPage($target = "/")
{ ?>
    <div class='d-flex'>
        <a href="<?= $target; ?>" class='text-dark text-decoration-none d-flex flex-row gap-2 align-items-center svg-icon'>
            <img src="<?= imagePath("web_images/icons", "chevron-back.svg") ?>" alt="back" width='15px' height='15px'>
            กลับ
        </a>
    </div>
<?php }

$thaiMonths = [
    "มกราคม",
    "กุมภาพันธ์",
    "มีนาคม",
    "เมษายน",
    "พฤษภาคม",
    "มิถุนายน",
    "กรกฎาคม",
    "สิงหาคม",
    "กันยายน",
    "ตุลาคม",
    "พฤศจิกายน",
    "ธันวาคม"
];

function formatThaiDate($date)
{
    global $thaiMonths;
    $dt = new DateTime($date);
    $year = $dt->format('Y') + 543;
    $month = $thaiMonths[$dt->format('n') - 1];
    $day = $dt->format('j');

    return "$day $month $year";
}
function timeElapsed($datetime, $full = false) {
    date_default_timezone_set('Asia/Bangkok'); // ตั้งค่าโซนเวลา
    $now = new DateTime();
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $string = [
        'y' => 'ปี',
        'm' => 'เดือน',
        'd' => 'วัน',
        'h' => 'ชั่วโมง',
        'i' => 'นาที',
        's' => 'วินาที',
    ];
    
    $result = [];
    foreach ($string as $key => $value) {
        if ($diff->$key > 0) {
            $result[] = $diff->$key . ' ' . $value;
        }
    }

    if (!$full) $result = array_slice($result, 0, 1);
    
    return $result ? implode(', ', $result) . 'ที่แล้ว' : 'ไม่กี่วินาทีที่ผ่านมา';
}


?>
