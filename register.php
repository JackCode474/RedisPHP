<?php
require("global.php");

security::Getglobals(array('username','password','email','step'),'POST','true');

if(empty($step)){
    
    
        $title = "Register";
        
        
        require_once printHTML('header');
        
        
        
        require_once printHTML('register');
        
        require_once printHTML('footer');footer();
    
    
    
} else  {
    
        if(empty($username) || empty($password) || empty($email)){
            echo '註冊資料錯誤';ajaxfooter();
        }

        
        
        $uid = $db->incr("userid");
        $db->hMset("user:".$uid,array(
            "uid"=>$uid,
            "username"=>$username,
            "password"=>$password,
            "email"=>$email,
            "regtime"=>$timestamp,
            "logintime"=>$timestamp,
        ));

        $db->rpush("uid",$uid);

        $db->set("username:".$username,$uid);
        
        echo 'success';ajaxfooter();
}
?>