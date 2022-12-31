<?php
require("global.php");
!$userid && ObHeader("login.php");
security::Getglobals(array('action'),'GET','true');

if(empty($action)){
    
    
    $title = "Personal";
    
    
    require_once printHTML('header');
    
    
    $activehome = 'active';
    $stylehome = 'style="color: white;"';
    
    require_once printHTML('personalhome');
    
    require_once printHTML('footer');footer();
    
    
    
} else  if($action == "Face"){
    
    $title = "Face Upload";
    
    require_once printHTML('header');
    
    $activeFace = 'active';
    $styleFace = 'style="color: white;"';
    
    $oldimages = $db->hget("user:".$userid,"faceupload");
    
    if(!$oldimages){
        $oldimages = 'image/default.jpg';
    }
    
    
    
    require_once printHTML('personalface');
    
    require_once printHTML('footer');footer();
    
} else  if($action == "Change"){
    
    $title = "Change Password";
    
    require_once printHTML('header');
    
    $activeChange = 'active';
    $styleChange = 'style="color: white;"';
    
    require_once printHTML('personalPassword');
    
    require_once printHTML('footer');footer();
    
    
} else  if($action == "ChangeUploadsave"){
    
    security::Getglobals(array('oldimages'),'POST','true');
    
    if(is_uploaded_file($_FILES['formFile']['tmp_name'])){
        $upimages_name = $_FILES['formFile']['name'];
        $upimages_type = $_FILES['formFile']['type'];
        $upimages_temp = $_FILES['formFile']['tmp_name'];
        $upimagestype = strtolower(substr(strrchr($upimages_name,'.'),1));
        
        $prename = $timestamp."_face_.".$upimagestype;
        
        $unintimgpatch =   "attachment/".date('Y-m')."/";
    
        createFolderd($unintimgpatch);
        
        
        @move_uploaded_file($upimages_temp,WEBROOT.$unintimgpatch.$prename);
        
        Punlink($oldimages);
        
    } else {
        
        
        $unintimgpatch = $oldimages;
    }
    
    
    
    $db->hmset("user:".$userid,array(
        "faceupload"  =>  $unintimgpatch.$prename,
    ));
    
    //同步存儲檔案
    $db->save();
    
    
    
    echo 'success';ajaxfooter();
    
    
} else  if($action == "Uploadsave"){
    
    security::Getglobals(array('oldpassword','newpassword','confirmpassword'),'POST','true');
    
    $oldpassword = security::stripTags($oldpassword);
    $newpassword = security::stripTags($newpassword);
    $confirmpassword= security::stripTags($confirmpassword);
    
    if(empty($oldpassword) || empty($newpassword) || empty($confirmpassword)){
            echo '未輸入密碼。';ajaxfooter();
    }
    
    
    if (!preg_match("/^(?=.*?[A-Z])([\d|a-zA-Z0-9]{6,10})+$/",$newpassword)) {
        echo '您輸入變更密碼格式只限英文、數字，最少 6 位最長 10 位，至少一個大寫字母。';
        ajaxfooter();
    }
    
    $password = $db->hget("user:".$userid,"password");
    
    if($password && $password != md5($oldpassword)){
        echo '您現在密碼錯誤。';ajaxfooter();
    }
    
    if(md5($confirmpassword) != md5($newpassword)){
        echo '您現在變更密碼及確認密碼不一致性。';ajaxfooter();
    }
    
    $db->hmset("user:".$userid,array(
        "password"  =>  md5($newpassword),
    ));
    
    //同步存儲檔案
    $db->save();
    
    
    echo 'success';ajaxfooter();
}
?>