<?php 
require_once("../config.php");

$output = "";

$row=null; 
if(isset($_SESSION["user_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["user_login"]])->fetch();
}elseif(isset($_SESSION["admin_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["admin_login"]])->fetch();
}elseif(isset($_SESSION["doctor_login"])){
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$_SESSION["doctor_login"]])->fetch();
}else{
    echo "ไม่สามารถเข้าถึงหน้านี้ได้";
    return;
}

$sender_id = $row["user_id"];

if(isset($_GET["getMessage"]) && isset($_GET["receiver_id"])){
    $receiver_id = $_GET["receiver_id"];

    $chatMessage = sql("SELECT chats.*,users.image as user_image FROM chats LEFT JOIN users ON users.user_id = chats.sender_id WHERE (sender_id = '$sender_id' AND receiver_id = '$receiver_id') OR (sender_id = '$receiver_id' AND receiver_id = '$sender_id')");
    if($chatMessage->rowCount() > 0){
        while($chatUser = $chatMessage->fetch()){
            $chatImage = $chatUser["image"] ? '<img src="'. imagePath("chat_images",$chatUser["image"]) .'" width="300px" height="200px" class="object-fit-cover border rounded-xl mt-2">' : null;
            if ($chatUser['sender_id'] == $sender_id) {
                $output .= '<div class="d-flex align-items-start justify-content-end gap-2">
                                <div class="border p-2 rounded-xl shadow">
                                    <div>' . $chatUser['message'] . '</div>
                                    '.$chatImage.'
                                </div>
                                <img src="'. imagePath("user_images",$chatUser["user_image"]) .'" width="40px" height="40px" class="object-fit-cover border rounded-circle">
                        </div>';
            } else {
                $output .= '<div class="d-flex align-items-start justify-content-start gap-2">
                            <img src="'. imagePath("user_images",$chatUser["user_image"]) .'" width="40px" height="40px" class="object-fit-cover border rounded-circle">
                            <div class="border p-2 rounded-xl shadow">
                                    <div>' . $chatUser['message'] . '</div>
                                    '.$chatImage.'
                                </div>
                        </div>';
            }
        }
    }else{
        $output.='<div class="text-center w-100 h-100 d-flex justify-content-center align-items-center">คุณยังไม่มีข้อความกับบุคคลนี้ เริ่มสนทนา?</div>';
    }
}

if(isset($_GET["sendMessage"]) && isset($_GET["receiver_id"])){
    $receiver_id = $_GET["receiver_id"];
    $message = $_POST["message"]; 

    $imageFile = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../images/chat_images/";

    $image = null;
    
    if(!empty($imageFile)){
        $extension = pathinfo($imageFile)["extension"];
        $image = uniqid() . "." . $extension;
        move_uploaded_file($tmp_name,$path.$image);
    }

    sql("INSERT INTO chats(sender_id,receiver_id,message,image) VALUES(?,?,?,?)",[
        $sender_id,$receiver_id,$message,$image
    ]);
}

echo $output;

?>