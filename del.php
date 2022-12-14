<?php
require("global.php");
$uid = $_GET['id'];
//echo $uid;
$username = $db->hget("user:".$id,"username");
$a=$db->del("user:".$uid);
$db->del("username:".$username);
$db->lrem("uid",$uid);
//var_dump($a);
header("location:index.php");
?>