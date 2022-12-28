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

        
        if($db->get("username:".$username)){
            echo '帳號已註冊過';ajaxfooter();
        }
        

        
        $uid = $db->incr("userid");

        $db->hMset("user:".$uid,array(
            "uid"       =>  $uid,
            "username"  =>  $username,
            "password"  =>  $password,
            "email"     =>  $email,
            "regtime"   =>  $timestamp,
            "logintime" =>  $timestamp,
        ));

        $db->rpush("uid",$uid);

        $db->set("username:".$username,$uid);
        
        $db->set("email:".$email,$uid);
    
        //同步存儲檔案
        $db->save();
        
        
        echo 'success';ajaxfooter();
}
?>