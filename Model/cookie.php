<?php
!defined('WEBROOT') && exit('Forbidden');

//cookie 加密 key
$cookiekey = "$%^&*".$_SERVER['SERVER_NAME'].$_SERVER['HTTP_USER_AGENT']."#$%^&!*";

class CookieModel{
    
    public static function GethttpsSecure(){

              $httpsSecure = false;

		if (!$_SERVER['REQUEST_URI'] || ($https = @parse_url($_SERVER['REQUEST_URI']))===false) {
			$https = array();
		}


		if ($https['scheme']=='https' || (empty($https['scheme']) && ($_SERVER['HTTP_SCHEME']=='https' || $_SERVER['HTTPS'] && strtolower($_SERVER['HTTPS'])!='off'))) {
			$httpsSecure = true;
		}

		return $httpsSecure;
	}
    
    
    
    public static function GetAdminUser($ckVar){

		$ckHttponly = false;

		if ($ckVar=='AdminUser') {
			$httpagent = strtolower($_SERVER['HTTP_USER_AGENT']);
			if (!($httpagent && preg_match('/msie ([0-9]\.[0-9]{1,2})/i', $httpagent) && strstr($httpagent, 'mac'))) {
				$ckHttponly = true;
			}
		}

		return $ckHttponly;
	}
    
    public static function ShowCookie($ckVar,$ckValue,$ckTime='F'){
		global $timestamp,$Cookiepath,$Cookiedomain;

		//cookie 儲存時效  一年 31536000     一個月 2592000  一天 86400  1 小時 3600    即時  0


		$httpsSecure = CookieModel::GethttpsSecure();

		$ckHttponly = CookieModel::GetAdminUser($ckVar);

		$ckVar = CookieModel::GetCodeEcy($ckVar);

		if ($ckTime=='F') {

			$ckTime = $timestamp+31536000;

		//每 6 天到期
		} else if ($ckTime=='6day') {

			$ckTime = $timestamp+518400;
		//每 30 天到期
		} else if ($ckTime=='month') {

			$ckTime = $timestamp+2592000;

		//10 年到期
		} else if ($ckTime=='G') {

			$ckTime = $timestamp+315360000; // 10 年

		//現在開始 24 小時制
		} else if ($ckTime=='24H') {

			$ckTime = $timestamp+86400;

		//現在開始明天12:00:00到期

		} else if ($ckTime=='24E') {

			$ckTime = strtotime(date('Y-m-d',$timestamp+86400));

		} elseif ($ckValue=='' && $ckTime==0) {

			return setcookie($ckVar,'',$timestamp-31536000,$Cookiepath,$Cookiedomain,$httpsSecure,$ckHttponly);

		}


		return setcookie($ckVar,$ckValue,$ckTime,$Cookiepath,$Cookiedomain,$httpsSecure,$ckHttponly);


	}
    
    
    public static function GetCodeEcy($Var){

		$ckVar = substr(md5($Var.CookieModel::CookieKEY()),0,8);
		return $ckVar;
	}
    
    
    public static function GetCookie($ckVar){
		global $timestamp;

		$ckVar = CookieModel::GetCodeEcy($ckVar);

		return $_COOKIE[$ckVar];
	}
    
    public static function CookieKEY(){
		global $cookiekey;
		$varuse = $_SERVER['HTTP_HOST'].$cookiekey;
		return substr(md5($varuse),0,5);
	}
    
    public static function ValueEncryption($string,$action='ENCODE'){
		global $cookiekey;

		$action != 'ENCODE' && $string = base64_decode($string);
		$code = '';
		$key = substr(md5($_SERVER['HTTP_HOST'].$cookiekey), 8, 18);
		$keyLen = strlen($key);
		$strLen = strlen($string);
		for ($i = 0; $i < $strLen; $i++) {
			$k = $i % $keyLen;
			$code .= $string[$i] ^ $key[$k];
		}
		return ($action != 'DECODE' ? base64_encode($code) : $code);

	}

}



?>
