<div class='position-fixed' style='bottom:2rem;right:2rem'>
        <div class='dropup'>
            <img src="" data-bs-toggle='dropdown' style='width:50px;height:50px' class='border p-0 rounded-circle object-fit-cover cursor-pointer'>
            <div class='dropdown-menu p-2' style='width:300px;height:400px;'>
                <h5 class='p-3'>ส่งข้อความ</h5>
                <div class='p-2'>
                    <?php 
                    function showUsers($role){
                        global $row;
                        $fetchUser = sql("SELECT * FROM users WHERE role = ?",[$role]);
                        if($fetchUser->rowCount() > 0){ $index=0; ?>
                            <ul style='list-style:none;' class='m-0 p-0 w-100 d-flex flex-column gap-1 mb-2'>
                            <?php while($user = $fetchUser->fetch()){
                                if($user["user_id"] === $row["user_id"]){
                                    return;
                                } 
                                $showTextRole = $role=='admin'?'• ผู้ดูและระบบ':'• ผู้ใช้งานทั่วไป';
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
                    showUsers("user");
                    ?>
                </div>
            </div>
        </div>
    </div>