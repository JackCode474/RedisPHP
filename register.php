<?php
require("global.php");
$userid && ObHeader("personal.php");
security::Getglobals(array('username','password','email','step'),'POST','true');

if(empty($step)){
    
    
        $title = "Register";
        
        
        require_once printHTML('header');
        
        
        
        require_once printHTML('register');
        
        require_once printHTML('footer');footer();
    
    
    
} else  {
    
        $username = security::stripTags($username);
        $password = security::stripTags($password);
        $email = security::stripTags($email);
    
        if(empty($username) || empty($password) || empty($email)){
            echo '註冊資料錯誤';ajaxfooter();
        }
    

        if (!preg_match("/^([a-zA-Z0-9]{6,30})+$/",$username)) {
            echo '您註冊帳號格式不正確只限英文、數字，最少 6 位最長 30 位。';
            ajaxfooter();
        }

        if (!preg_match("/^(?=.*?[A-Z])([\d|a-zA-Z0-9]{6,10})+$/",$password)) {
            echo '您註冊密碼格式只限英文、數字，最少 6 位最長 10 位，至少一個大寫字母。';
            ajaxfooter();
        }
        
        echo '錯誤';ajaxfooter();
        
        if (!preg_match("/^[-a-zA-Z0-9_\.]+@([0-9A-Za-z][0-9A-Za-z-]+\.)+[A-Za-z]{2,5}$/",$email)) {
            echo '您輸入 E-mail 格式錯誤。';
            ajaxfooter();
        }

    
        if($db->get("username:".$username)){
            echo '您輸入帳號已註冊過';ajaxfooter();
        }
    
        if($db->get("email:".$email)){
            echo '您輸入 E-mail 已註冊過';ajaxfooter();
        }
        
        $uid = $db->incr("userid");

        $db->hMset("user:".$uid,array(
            "uid"       =>  $uid,
            "faceupload"=>  "image/default.jpg",
            "username"  =>  $username,
            "password"  =>  $password,
            "email"     =>  $email,
            "onlineip"  =>  '',
            "ipfrom"    =>  '',
            'device'    =>  '',
            'version'   =>  '',
            "regtime"   => $GLOBALS['timestamp'],
            "logintime" => $GLOBALS['timestamp'],
        ));

        $db->rpush("uid",$uid);

        $db->set("username:".$username,$uid);
        $db->set("email:".$email,$uid);
    
        //異步儲存檔案
        $db->bgsave();
        
        
        echo 'success';ajaxfooter();
}
?>