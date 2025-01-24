<?php 
session_start();

try{
    $pdo = new PDO("mysql:host=mariadb_elderly_travel;dbname=elderly_db","username","password");
}catch(PDOException $e){
    echo $e->getMessage();
}

function sql($sql,$params=[]){
    global $pdo;
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt;
}

function msg($type,$title,$body,$location){
    $_SESSION[$type] = [$title,$body];
    if($location){
        header("location: $location");
        exit;
    }
}

foreach(["success","warning","danger"] as $index=>$type){
    if(isset($_SESSION[$type])){ ?>
        <div class="position-fixed opacity-0" style="top:10px;right:10px;" id="alertMsg">
            <div class="border p-2 position-relative overflow-hidden ps-3 shadow-sm rounded-xl" style="width:350px;">
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

function linkPage($path, $target) {
    return $_SERVER["REQUEST_URI"] == "/" ? "./$path/$target" : "../$path/$target";
}

function imagePath($path, $target) {
    return $_SERVER["REQUEST_URI"] == "/" ? "./images/$path/$target" : "../images/$path/$target";
}
?>