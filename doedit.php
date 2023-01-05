<?php
require("global.php");

security::Getglobals(array('username','uid','email','step'),'POST','true');

$db->hmset("user:".$uid,array(
    "username"  =>  $username,
    "email"     =>  $email,
));

//異步儲存檔案
$db->bgsave();


header("location:index.php");

?>