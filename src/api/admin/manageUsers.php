<?php 
require_once("../../config.php");

if(isset($_REQUEST["addUser"])){
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $role = $_POST["role"];

    $imageUser = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../../images/user_images/";
    
    try{
        if(!empty($imageUser)){
            $extension = pathinfo($imageUser)["extension"];
            $image = uniqid() .".".$extension;
            move_uploaded_file($tmp_name,$path.$image);
        }else{
            $image = "default-profile.png";
        }

        $passwordHash = password_hash($password,PASSWORD_DEFAULT);
        $insert = sql("INSERT INTO users(firstname,lastname,email,password,image,role) VALUES(?,?,?,?,?,?)",[
            $firstname,
            $lastname,
            $email,
            $passwordHash,
            $image,
            $role
        ]);
        msg("success","สำเร็จ!","เพิ่มสามาชิกเรียบร้อย คุณ $firstname เรียบร้อยแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["editUser"]) && isset($_GET["user_id"])){
    $user_id = $_GET["user_id"];
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$user_id])->fetch();

    $firstname = $_POST["firstname"] ?? $row["firstname"];
    $lastname = $_POST["lastname"] ?? $row["lastname"];
    $email = $_POST["email"] ?? $row["email"];
    $password = $_POST["password"];
    $role = $_POST["role"] ?? $row["role"];

    $imageUser = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../../images/user_images/";
    
    try{
        if(!empty($imageUser)){
            if($row["image"] !== "default-profile.png"){
                if(file_exists($path.$row["image"])){
                    unlink($path.$row["image"]);
                }
            }
            $extension = pathinfo($imageUser)["extension"];
            $image = uniqid() .".".$extension;
            move_uploaded_file($tmp_name,$path.$image);
        }else{
            $image = $row["image"];
        }

        $passwordHash = !empty($password) ? password_hash($password,PASSWORD_DEFAULT) : $row["password"];
        $insert = sql("UPDATE users SET firstname=?,lastname=?,email=?,password=?,image=?,role=? WHERE user_id = ?",[
            $firstname,
            $lastname,
            $email,
            $passwordHash,
            $image,
            $role,
            $user_id
        ]);
        msg("success","สำเร็จ!","แก้ไขสมาชิกคุณ $firstname เรียบร้อยแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["deleteUser"]) && isset($_GET["user_id"])){
    $user_id = $_GET["user_id"];
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$user_id])->fetch();

    $path = "../../images/user_images/";
    
    try{
        $firstname = $row["firstname"];
        if($row["image"] !== "default-profile.png"){
            if(file_exists($path.$row["image"])){
                unlink($path.$row["image"]);
            }
        }
        sql("DELETE FROM users WHERE user_id = ?",[$user_id]);
        msg("success","สำเร็จ!","ลบสมาชิกคุณ $firstname เรียบร้้อยแล้ว",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

?>