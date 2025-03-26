<?php 
require_once("../config.php");

if(isset($_REQUEST["save"])){
    $user_id = isset($_SESSION["user_login"]) ? $_SESSION["user_login"] : (isset($_SESSION["admin_login"]) ? $_SESSION["admin_login"] : (isset($_SESSION["doctor_login"]) ? $_SESSION["doctor_login"] : null)) ;
    $row = sql("SELECT * FROM users WHERE user_id = ?",[$user_id])->fetch();
    $firstname = $_POST["firstname"] ?? $row["firstname"];
    $lastname = $_POST["lastname"] ?? $row["lastname"];
    
    $con_password = $_POST["con_password"];
    $password = $_POST["password"];
    $c_password = $_POST["c_password"];

    $imageUser = $_FILES["image"]["name"];
    $tmp_name = $_FILES["image"]["tmp_name"];
    $path = "../images/user_images/";

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

        if(!empty($password)){
            if(!password_verify($con_password,$row["password"])){
                throw new PDOException("รหัสผ่านเดิมไม่ถูกต้อง");
            }
            if($password !== $c_password){
                throw new PDOException("รหัสผ่านไม่ตรงกัน");
            }
        }

        $passwordHash = !empty($password) ? password_hash($password,PASSWORD_DEFAULT) : $row["password"];
        $insert = sql("UPDATE users SET firstname=?,lastname=?,password=?,image=? WHERE user_id = ?",[
            $firstname,
            $lastname,
            $passwordHash,
            $image,
            $user_id
        ]);
        msg("success","สำเร็จ!","แก้ไขสมาชิกคุณ $firstname เรียบร้อยแล้ว!",$_SERVER["HTTP_REFERER"]);
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}
?>