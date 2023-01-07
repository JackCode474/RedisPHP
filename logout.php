<?php
require("global.php");
!$userid && ObHeader("login.php");
CookieModel::ShowCookie('UserLogin','');
header("location:index.php");
?>