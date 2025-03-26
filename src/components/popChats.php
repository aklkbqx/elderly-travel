<?php 
if(isset($_SESSION["user_login"]) || isset($_SESSION["admin_login"])){ ?>
    <div class='position-fixed' style='bottom:2rem;right:2rem;z-index:99'>
    <div class='dropup'>
        <img src="<?= imagePath("web_images/icons","chat.svg") ?>" data-bs-toggle='dropdown' style='width:60px;height:60px;filter:drop-shadow(0 0 5px white);object-fit:contain' class='cursor-pointer'>
        <div class='dropdown-menu p-2 overflow-hidden' style='width:300px;height:500px;'>
            <h5 class='p-3'>ส่งข้อความ</h5>
            <div class='p-2 overflow-auto' style='height:340px'>
                <?php function showUsers($role){
                    global $row;
                    $fetchUser = sql("SELECT * FROM users WHERE role = ?",[$role]);
                    if($fetchUser->rowCount() > 0){ $index=0; ?>
                        <ul style='list-style:none;' class='m-0 p-0 w-100 d-flex flex-column gap-1 mb-2'>
                        <?php while($user = $fetchUser->fetch()){
                            if($user["user_id"] === $row["user_id"]){
                                continue;
                            } 
                                $showTextRole = $role=='admin'?'• ผู้ดูและระบบ':($role=="user" ? '• ผู้ใช้งานทั่วไป' : '• แพทย์/หมอ');
                                echo $index == 0 ? "<div>$showTextRole</div>" : null ?>
                                <a href="<?= $_SERVER["REQUEST_URI"] === "/" ? "chats.php?receiver_id=".$user['user_id'] : "../chats.php?receiver_id=".$user['user_id'] ?>" class='btn btn-outline-teal border-0 p-1 w-100'>
                                    <li class='d-flex align-items-center gap-2'>
                                        <div class='position-relative'>
                                            <img src="<?= imagePath("user_images",$user["image"]) ?>" class='border rounded-circle object-fit-cover' style='width:50px;height:50px'/>
                                            <div style='width:15px;height:15px;bottom:0px;right:0px;' class='bg-<?= $user["active_status"]=="online"?"success":"danger" ?> position-absolute rounded-circle'></div>
                                        </div>
                                        <div><?= $user["firstname"] ?> <?= $user["lastname"] ?></div>
                                    </li>
                                </a>
                            <?php $index++; } ?>
                        </ul>
                    <?php }
                }
                showUsers("admin");
                showUsers("doctor");
                showUsers("user");
                ?>
            </div>
            <div class='d-flex align-items-center gap-2 pt-2'>
                <a href="<?= $_SERVER["REQUEST_URI"] == "/" ? "chat-bot.php" : "../chat-bot.php" ?>"  class='btn btn-outline-teal border-0 p-1 w-100'>
                    <img src="<?= imagePath("user_images","bot.png") ?>" class='border rounded-circle object-fit-cover' style='width:50px;height:50px'/>
                    <div>แชทบอทช่วยเหลือ 24. ชม</div>
                </a>
            </div>
        </div>
    </div>
</div>
<?php } ?>