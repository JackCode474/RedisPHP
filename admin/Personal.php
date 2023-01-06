<?php
!defined('WEBROOT') && exit('Forbidden');

if(empty($action)){

    
    include template('Personal');footer();
    
} else if($action == 'EditSave'){
    
    
    security::GlobalsALL('POST');
    
    if(empty($newpass)){
        echo "未知 password";ajaxfooter();
    }
    
    if (!preg_match("/^(?=.*?[A-Z])([\d|a-zA-Z0-9]{6,10})+$/",$newpass)) {
        echo '您輸入變更密碼格式只限英文、數字，最少 6 位最長 10 位，至少一個大寫字母。';
        ajaxfooter();
    }
    $GLOBALS['db']->select(1);
    $password = $db->hget("admin:".$admincpid,"password");
    if($password != md5($oldpass)){
        echo "錯誤密碼";ajaxfooter();
    }
    
    $db->hmset("admin:".$admincpid,array(
        "password"  =>  md5($newpass),
    ));
    

    $db->bgsave();
    
    $GLOBALS['db']->select(0);
    
    CookieModel::ShowCookie('AdminisatorLogin',CookieModel::ValueEncryption($admincpid."|".$newpass."|".$adminusername."|".$admingroup));

    echo "success";ajaxfooter();

}


?>