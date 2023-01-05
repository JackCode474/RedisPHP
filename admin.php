<?php
error_reporting(E_ERROR | E_PARSE);
//error_reporting(E_ALL);
//ini_set('display_errors', '1');
define('WEBROOT',checkroot(__FILE__));
include(WEBROOT.'Model/security.php');
security::Getglobal();
include(WEBROOT.'Model/command.php');
include(WEBROOT.'Model/cookie.php');
include(WEBROOT.'Model/gzip.php');
include(WEBROOT.'Model/admin.php');

if(empty($adminis)){
    include security::escapePath(WEBROOT."admin/index.php");
} else if(file_exists(WEBROOT."admin/$adminis.php")){
    include security::escapePath(WEBROOT."admin/$adminis.php");
} else {
    exit("Unable to read  $adminis Files");
}


function checkroot($path=null){
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