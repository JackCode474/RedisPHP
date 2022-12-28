<?php
require("global.php");

security::Getglobals(array('id'),'GET','true');

$username = $db->hget("user:".$id,"username");

$a=$db->del("user:".$id);
$db->del("username:".$username);
$db->del("LoginLog:".$id);

$db->lrem("uid",$id);

//var_dump($a);
header("location:index.php");
?>