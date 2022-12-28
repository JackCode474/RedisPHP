<?php
require("global.php");

security::Getglobals(array('username','uid','email','step'),'POST','true');

$db->hmset("user:".$uid,array(
    "username"  =>  $username,
    "email"     =>  $email,
));

//同步存儲檔案
$db->save();


header("location:index.php");

?>