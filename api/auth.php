<?php 
require_once("../config.php");

if(isset($_REQUEST["register"])){
    $firstname = $_POST["firstname"];
    $lastname = $_POST["lastname"];
    $email = $_POST["email"];
    $password = $_POST["password"];
    $c_password = $_POST["c_password"];
    
    try{
        if($password !== $c_password){
            throw new PDOException("รหัสผ่านไม่ตรงกัน");
        }

        $passwordHash = password_hash($password,PASSWORD_DEFAULT);

        $insert = sql("INSERT INTO users(firstname,lastname,email,password,active_status) VALUES(?,?,?,?,?)",[
            $firstname,
            $lastname,
            $email,
            $passwordHash,
            "online"
        ]);

        $lastInsertId = $pdo->lastInsertId();

        $_SESSION["user_login"] = $lastInsertId;
        
        msg("success","สำเร็จ!","คุณ $firstname สมัครสมาชิกสำเร็จแล้ว","../");
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}

if(isset($_REQUEST["login"])){
    $email = $_POST["email"];
    $password = $_POST["password"];
    
    try{
        $fetchUser = sql("SELECT * FROM users WHERE email = ?",[$email]);
        if($fetchUser->rowCount() > 0){
            $row = $fetchUser->fetch();
            if(password_verify($password,$row["password"])){
                $_SESSION[$row["role"]."_login"] = $row["user_id"];
                $location = $row["role"] == "admin" ? "../admin/" : "../" ;
                $firstname = $row["firstname"];
                sql("UPDATE users SET active_status=? WHERE user_id = ?",["online",$row["user_id"]]);
                msg("success","สำเร็จ!","คุณ $firstname เข้าสู่ระบบสำเร็จแล้ว",$location);
            }else{
                throw new PDOException("รหัสผ่านของคุณไม่ถูกต้อง!");
            }
        }else{
            msg("warning","คำเตือน","ไม่พบข้อมูลของคุณในระบบ กรุณา <a href='../register.php'>สมัครสมาชิก</a>",$_SERVER["HTTP_REFERER"]);
        }
    }catch(PDOException $e){
        msg("danger","เกิดข้อผิดพลาด",$e->getMessage(),$_SERVER["HTTP_REFERER"]);
    }
}
?>