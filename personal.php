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
    
    //??????????????????
    $db->save();
    
    
    
    echo 'success';ajaxfooter();
    
    
} else  if($action == "Uploadsave"){
    
    security::Getglobals(array('oldpassword','newpassword','confirmpassword'),'POST','true');
    
    $oldpassword = security::stripTags($oldpassword);
    $newpassword = security::stripTags($newpassword);
    $confirmpassword= security::stripTags($confirmpassword);
    
    if(empty($oldpassword) || empty($newpassword) || empty($confirmpassword)){
            echo '??????????????????';ajaxfooter();
    }
    
    
    if (!preg_match("/^(?=.*?[A-Z])([\d|a-zA-Z0-9]{6,10})+$/",$newpassword)) {
        echo '????????????????????????????????????????????????????????? 6 ????????? 10 ?????????????????????????????????';
        ajaxfooter();
    }
    
    $password = $db->hget("user:".$userid,"password");
    
    if($password && $password != md5($oldpassword)){
        echo '????????????????????????';ajaxfooter();
    }
    
    if(md5($confirmpassword) != md5($newpassword)){
        echo '???????????????????????????????????????????????????';ajaxfooter();
    }
    
    $db->hmset("user:".$userid,array(
        "password"  =>  md5($newpassword),
    ));
    
    //??????????????????
    $db->bgsave();
    
    
    echo 'success';ajaxfooter();
    
} else  if($action == "LoginLog"){
    
    $title = "Login Log";
    
    require_once printHTML('header');
    
    $activeLoginLog = 'active';
    
    $styleLoginLog = 'style="color: white;"';
    
    
    
    $count =$db->lsize("LoginLog:$userid");//?????????????????????
    //??????????????????
    $page_size = 20;
    //????????????
    $page_num=(!empty($page)) ? $page : 1;
    //?????????
    $page_count = ceil($count/$page_size);

    
    
    $links = 1;
    $start      = ( ( $page_num - $links ) > 0 ) ? $page_num - $links : 1;
    $end        = ( ( $page_num + $links ) <$page_count ) ? $page_num + $links : $page_count;
    
    $LoginLog =$db->lrange("LoginLog:$userid", ($page_num-1)*$page_size,($page_num)*$page_size);
    
    
    $page = showpage($count, $page_num, $page_count,'personal.php?action=LoginLog');
    
    require_once printHTML('personalLoginLog');
    
    require_once printHTML('footer');footer();
    
}
?>