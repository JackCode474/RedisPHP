<?php
!defined('WEBROOT') && exit('Forbidden');
include(WEBROOT."data/config.php");

security::Getglobals(array('adminis','quit','action','page'),'GET',true);
security::Getglobals(array('AdminUSER','AdminPW','action'),'POST',true);

GzipModel::StartGzip();

if ($adminis && strpos($adminis,'..') !== false) {
    exit('Forbidden');
}



$protocol = 'http://';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    $protocol = 'https://';
}


$timestamp = time();
$admin_file = $adminis ? 'admin.php?adminis='.$adminis : 'admin.php';
$REQUESTURI = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];
$SELF = $_SERVER['PHP_SELF'];
$HTTPHOST =  $protocol."$_SERVER[HTTP_HOST]".substr($SELF,0,strrpos($SELF,'/'));
$HTTPREFERER = $_SERVER['HTTP_REFERER'];
$VersionNumber = showdate($timestamp,'YmdHi');
$onlineip = GetcheckIp();
$ThisYearsTime = linuxTime(showdate($timestamp,'Y')); //今年
$todayTime= linuxTime(showdate($timestamp,'Y-m-d')); //今日
$monthTime = linuxTime(showdate($timestamp,'Y-m').'-1');//當月
$YearsTime = showdate($timestamp,'Y');

if(isset($quit) && $quit == 'quit'){
    
    CookieModel::ShowCookie('AdminisatorLogin','');
    echo '<meta http-equiv="refresh" content="1; url=admin.php">';
    exit;
}


StartSqlDb();

if(!empty($AdminUSER) && !empty($AdminPW)){
    
    AdminInfocheckpass($AdminUSER,$AdminPW);
    
} else {
    
    list($admincpid,$adminusername,$adminsleval,$admincountry) = AdminInfoChecks();

}

function  AdminInfocheckpass($AdminUSER,$AdminPW){
    
    if(!$AdminUSER && !$AdminPW){
        include template('login');footer();
    }
    
    if (!is_object($GLOBALS['db'])) {
        StartSqlDb();
    }
    
    $country = GeoipCheck();
    
    $GLOBALS['db']->select(1);
    $id = $GLOBALS['db']->get("username:".$AdminUSER);

    if($id){
        
        $dbpassword =$GLOBALS['db']->hget("admin:".$id,"password");

        if(md5($AdminPW) != $dbpassword){

            include template('login');footer();
        
        } else {
            
            $dbgroup =$GLOBALS['db']->hget("admin:".$id,"group");
    
            $GLOBALS['db']->hmset("admin:".$id,array(
                "onlineip"  =>  $GLOBALS['onlineip'],
                "ipfrom"    =>  $country,
                "logintime" =>  $GLOBALS['timestamp'],
            ));
    
            $GLOBALS['db']->select(0);
    
            CookieModel::ShowCookie('AdminisatorLogin',CookieModel::ValueEncryption($id."|".$AdminPW."|".$AdminUSER."|".$dbgroup));
    
            echo "success";ajaxfooter();
        }

    } else {
        
        
        
        
        echo "NoAdminUser";ajaxfooter();
        
    }
}


function AdminInfoChecks(){
    
    
    if(CookieModel::GetCookie('AdminisatorLogin')){
        list($admincpid,$adminpassword,$adminusername,$group) = explode("|",CookieModel::ValueEncryption(CookieModel::GetCookie('AdminisatorLogin'),'DECODE'));
        if($admincpid){
    
                if (!is_object($GLOBALS['db'])) {
                    StartSqlDb();
                }
                $GLOBALS['db']->select(1);
           
                $id = $GLOBALS['db']->get("username:".$adminusername);
                
                $dbpassword =$GLOBALS['db']->hget("admin:".$id,"password");
    

                
                if(!$id || $admincpid != $id || md5($adminpassword) != $dbpassword){
                   
                    include template('login');footer();
            
                } else {
                    //echo md5($adminpassword)." - $adminusername";exit;
                    $GLOBALS['db']->select(0);
                    return array($admincpid,$adminusername,$group);
                }
            
            
        } else {
         
            include template('login');footer();
        }
        
    } else {
       
        include template('login');footer();
    }
    
    
}

function adminmsg($msg,$jumpurl="",$Second=1){
    
    if(!$Second){
        ObHeader($jumpurl);
    }
    
    
    ob_end_clean();
    if(!$msg && $jumpurl){
        //header("Location: $jumpurl");
        ObHeader($jumpurl);
        exit;
    }
    
    include template('message');footer();
}





function ajaxfooter(){
    global $default_obstart;
    
    $output = ob_get_contents();
    
    $output = ObFirstr($output);
    
    ob_end_clean();
    
    $output = removeBOM($output);
    //header("Content-Type: text/xml;charset=UTF-8");
    $GLOBALS['db']->close();
    echo GzipModel::GzipExport(trim($output));
    
    unset($output);
    
    GzipModel::claseGZIP();
    exit;
}


function footer(){
    
    $output = ob_get_contents();
    $output = versioncache($output);
    ob_end_clean();
    
    $output = ObFirstr($output);
    
    if($GLOBALS['default_redundancy']){
        $output = compress_html($output);
    }
    
    $output = removeBOM($output);
    $output = preg_replace("/<!--(.*?)-->/is","",$output);
    $GLOBALS['db']->close();
    echo GzipModel::GzipExport($output);
    unset($output);
    GzipModel::claseGZIP();
    exit;
}

function versioncache($output){
    
    
    if($GLOBALS['VersionNumber']){
        
        $output = preg_replace("/href=('|\")(.+\.(css)\"?)('|\")/i","href=\\1\\2?v={$GLOBALS['VersionNumber']}\\4",$output);
        
        $output = preg_replace("/src=('|\")(.+\.(js)\"?)('|\")/i","src=\\1\\2?v={$GLOBALS['VersionNumber']}\\4",$output);
        
        $output = preg_replace("/src=('|\")(attachment.+\.(png|jpg|gif|jpeg|bmp)\"?)('|\")/i","src=\\1\\2?v={$GLOBALS['VersionNumber']}\\4",$output);
    }
    return $output;
    
}




function ObFirstr($str) {
    
    $str = str_replace(array("\r",'<!--<!---->-->', '<!---->-->', '<!--<!---->', "<!---->\n", '<!-- -->', "<!--\n-->","<!---->"),'', $str);
    
    return $str;
}




function template($template,$EXT="html"){
    
    if(file_exists(WEBROOT."admin/template/".$template.".$EXT")){
        
        return WEBROOT."admin/template/".$template.".$EXT";
    }else{
        exit("No $template.$EXT Files..");
    }
}



function StartSqlDb(){
    global $db;
    
    try{
        
        if (!is_object($GLOBALS['db'])) {
            
            $db = new Redis();
            if ($db->connect($GLOBALS['Redishost'],$GLOBALS['Redisport']) == false) {
                die($db->getLastError());
            }
            
            if($db->auth($GLOBALS['Redispwassword']) == false){
                die($db->getLastError());
            }
        }
        
    }catch (RedisException $ex) {
        
        echo $ex->getMessage();
        exit;
    }
}

?>