<?php
require("global.php");
security::Getglobals(array('username','password','step'),'POST','true');

if(empty($step)){
    
    
        $title = "Login";
    
    
    require_once printHTML('header');
    
    
    
    require_once printHTML('login');
    
    require_once printHTML('footer');footer();
    
    
    
} else  {
    
    if(empty($username) || empty($password)){
        echo '無此帳號';ajaxfooter();
    }
    
    
    $id = $db->get("username:".$username);
    if(!empty($id)){
        $dbpassword = $db->hget("user:".$id,"password");
        if(md5($password) == $dbpassword){
            $db->hmset("user:".$id,array(
                "logintime" =>  $timestamp,
            ));
            $db->lPush('LoginLog:'.$id, $GLOBALS['onlineip'].":本機區網");
            $db->save();
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