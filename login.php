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
            //$auth = md5(time().$username.rand());
            //$db->set("auth:".$auth,$id);

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