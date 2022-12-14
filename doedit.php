<?php
require("global.php");
$uid = $_POST['uid'];
$username = $_POST['username'];
$email = $_POST['email'];
$a=$db->hmset("user:".$uid,array(
    "username"=>$username,
    "email"=>$email,
));

if($a){
    header("location:index.php");
}else{
    header("location:mod.php?id=".$uid);
}
?>