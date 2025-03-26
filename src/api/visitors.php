<?php 
require_once("../config.php");

if($_SERVER["REQUEST_METHOD"] === "POST"){
    $fetchVisitor = sql("SELECT * FROM visitors WHERE visitor_id = 1");
    if($fetchVisitor->rowCount() > 0){
        $visitor = $fetchVisitor->fetch();
        $count = intval($visitor["count"]);
        sql("UPDATE visitors SET count = $count + 1 WHERE visitor_id = ?",[$visitor["visitor_id"]]);
        echo "update visitor success";
    }else{
        sql("INSERT INTO visitors(count) VALUES(1)");
    }
}
?>