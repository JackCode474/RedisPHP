<?php
require("global.php");

$userid && ObHeader("personal.php");

security::Getglobals(array('email','password','step'),'POST','true');

if(empty($step)){
    
    
        $title = "Login";
    
    
    require_once printHTML('header');
    
    
    
    require_once printHTML('login');
    
    require_once printHTML('footer');footer();
    
    
    
} else  {
    $email = security::stripTags($email);
    $password = security::stripTags($password);
    if(empty($email) || empty($password)){
        echo '無此帳號';ajaxfooter();
    }
    

    $id = $db->get("email:".$email);
    if(!empty($id)){
        $dbpassword = $db->hget("user:".$id,"password");
        if(md5($password) == $dbpassword){
    
            $country = GeoipCheck();
            list($type,$ver) = mobiledevice();
            
            $db->hmset("user:".$id,array(
                "onlineip"  =>  $GLOBALS['onlineip'],
                "ipfrom"    =>  $country,
                'device'    =>  $type,
                'version'   =>  $ver,
                "logintime" =>  $GLOBALS['timestamp'],
            ));
            
            
            $username = $db->hget("user:".$id,"username");
            
            $db->lPush('LoginLog:'.$id, $GLOBALS['onlineip']."|$country|$type|$ver|".date('Y-m-d H:i:s',$GLOBALS['timestamp']));
            
            $db->bgsave();
            
            CookieModel::ShowCookie('UserLogin',CookieModel::ValueEncryption($id."|".$username));

            echo 'success';ajaxfooter();
        } else {
            echo '無此帳號';ajaxfooter();
        }
    } else {
        echo '無此帳號';ajaxfooter();
    }
    
    


}

?>