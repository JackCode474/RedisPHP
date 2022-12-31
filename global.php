<?php
define('WEBROOT',CheckRoot(__FILE__));
include(WEBROOT.'Model/security.php');
security::Getglobal();
security::globalcheck();
include(WEBROOT.'Model/MobileDetect.php');
include(WEBROOT.'Model/command.php');
include(WEBROOT.'Model/cookie.php');
include(WEBROOT.'Model/gzip.php');
include(WEBROOT.'data/config.php');

$GLOBALS['onlineip'] = GetcheckIp();

security::AntiCC();


$GLOBALS['mobiledevice'] = mobiledevice();

$protocol = 'http://';
if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] == "on") {
    $protocol = 'https://';
}

$USERAGENT = $GServer['HTTP_USER_AGENT'];
$SELF = $GServer['PHP_SELF'];
$HTTPHOST =  $protocol."$GServer[HTTP_HOST]".substr($SELF,0,strrpos($SELF,'/'));
$HTTPREFERER = $GServer['HTTP_REFERER'];
$REQUEST_URI = $GServer['PHP_SELF'].($GServer['QUERY_STRING'] ? '?'.$GServer['QUERY_STRING'] : '');
$ALLHTTP = $protocol.$GServer['HTTP_HOST'].$REQUEST_URI;
$timestamp = time();

$VersionNumber= md5(showdate($timestamp,'YmdHi'));

GzipModel::StartGzip();


$ThisYearsTime = linuxTime(showdate($timestamp,'Y')); //今年
$todayTime= linuxTime(showdate($timestamp,'Y-m-d')); //今日
$monthTime = linuxTime(showdate($timestamp,'Y-m').'-1');//當月
$YearsTime = showdate($timestamp,'Y');


Startsql();


if(CookieModel::GetCookie('UserLogin')){
   list($userid,$username) = UserDB();
} else {
    $userid = 0;
    $username=null;
}


function UserDB(){
    
    list($id,$username) = explode("|",CookieModel::ValueEncryption(CookieModel::GetCookie('UserLogin'),'DECODE'));

    if($id){
    
        $id = $GLOBALS['db']->get("username:".$username);
        if(!empty($id)){
            return array($id,$username);
        } else {
            return array(0,null);
        }
    
    } else {
        return array(0,null);
    }
    
}

function mobiledevice(){
    
    $detect = new MobileDetect();
    if( $detect->isMobile() && !$detect->isTablet()){
        
        if($detect->isiOS()){
            return array('iOSMobile',$detect->version('iOS'));
        } else if($detect->isAndroidOS()){
            return  array('AndroidMobile',$detect->version('Android'));
        }
        
    } else if( !$detect->isMobile() && $detect->isTablet()){
        
        if($detect->isiOS()){
            return array('iOSTablet',$detect->version('iOS'));
        } else if($detect->isAndroidOS()){
            return  array('AndroidTablet',$detect->version('Android'));
        }
    } else {
        
        if($detect->version('Windows NT')){
            return array('Desktop','Windows NT '.$detect->version('Windows NT'));
        } else if($detect->version('Mac')){
            return array('Desktop','Mac '.$detect->version('Mac'));
        } else if($detect->version('Linux')){
            return array('Desktop','Linux '.$detect->version('Linux'));
        }
    }
}


function meassage($msg='',$jumpurl='',$Second=2){
    
    
    if(empty($jumpurl)){
        $Second='0';
    }
    

    require_once printHTML('meassage');
    
    $output = ob_get_contents();
    
    $output = preg_replace("/<!--(.*?)-->/is","",$output);
    
    if($GLOBALS['default_rewrite']){
        $output =  HtmlRewrite($output);
    }
    
    
    ob_end_clean();
    
    $output = ObFirstr($output);
    $output = compress_html($output);
    $output = removeBOM($output);
    echo GzipModel::GzipExport($output);
    unset($output);
    GzipModel::claseGZIP();
    exit;
}

function printHTML($template='',$EXT="html"){
    if(file_exists(WEBROOT."template/$template.$EXT")){
        return WEBROOT."template/$template.$EXT";
    } else {
        exit("No template/$template.$EXT Files..");
    }
}

function ajaxfooter(){

    $output = ob_get_contents();
    $output = ObFirstr($output);
    ob_end_clean();
    $output = removeBOM($output);
    if($GLOBALS['default_redundancy']){
        $output = compress_html($output);
    }
    $GLOBALS['db']->close();
    echo GzipModel::GzipExport($output);
    unset($output);
    GzipModel::claseGZIP();
    
    exit;
}




function footer(){

    $output = ob_get_contents();
    $output = versioncache($output);
    if($GLOBALS['default_rewrite']){
        $output =  HtmlRewrite($output);
    }
    
    ob_end_clean();
    $output = ObFirstr($output);
    if($GLOBALS['default_redundancy']){
        $output= str_replace("\n",'', $output);
    }
    
    $output = removeBOM($output);
    $output = preg_replace("/<!--(.*?)-->/is","",$output);
    $GLOBALS['db']->close();
    echo GzipModel::GzipExport($output);
    unset($output);
    GzipModel::claseGZIP();
    exit;
}


function versioncache($output=''){
    if($GLOBALS['VersionNumber']){
        $output = preg_replace("/href=('|\")(.+\.(css)\"?)('|\")/i","href=\\1\\2?v={$GLOBALS['VersionNumber']}\\4",$output);
        $output = preg_replace("/src=('|\")(.+\.(js)\"?)('|\")/i","src=\\1\\2?v={$GLOBALS['VersionNumber']}\\4",$output);
        $output = preg_replace("/src=('|\")(.+\.(png|jpg|gif|jpeg|bmp|svg)\"?)('|\")/i","src=\\1\\2?v={$GLOBALS['VersionNumber']}\\4",$output);
    }
    return $output;
    
}


function ObFirstr($str='') {
    $str = str_replace(array("\r",'<!--<!---->-->', '<!---->-->', '<!--<!---->', "<!---->\n", '<!-- -->', "<!--\n-->","<!---->"),'', $str);
    return $str;
}

function HtmlRewrite($output=''){
    return $output;
}

function Startsql(){
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

function CheckRoot($path=null){
    if (empty($path)) {
        return './';
    } else {
        if (strpos($path,'\\')!==false) {
            return substr($path,0,strrpos($path,'\\')).'/';
        } elseif (strpos($path,'/')!==false) {
            return substr($path,0,strrpos($path,'/')).'/';
        } else {
            return './';
        }
    }
}



?>