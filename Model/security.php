<?php


class Security {


	//檢查訪問 User 是否用代理 Porxy  IP 訪問
    public static function UserPorxy(){

            if($_SERVER['HTTP_X_FORWARDED_FOR'] || $_SERVER['HTTP_X_FORWARDED'] || $_SERVER['HTTP_FORWARDED_FOR'] || $_SERVER['HTTP_VIA'] || $_SERVER['HTTP_FORWARDED'] || $_SERVER['HTTP_CLIENT_IP'] || $_SERVER['HTTP_FORWARDED_FOR_IP'] || $_SERVER['VIA'] || $_SERVER['X_FORWARDED_FOR'] || $_SERVER['FORWARDED_FOR'] || $_SERVER['X_FORWARDED FORWARDED'] || $_SERVER['CLIENT_IP'] || $_SERVER['FORWARDED_FOR_IP'] || $_SERVER['HTTP_PROXY_CONNECTION'] || !$_SERVER['HTTP_CONNECTION']){
            
                return true;
            }  else {
                return false;
            }
	}



   /*
   *
   *   判斷訪問網站 USER 是否搜索引擎
   *  return boot
   */
    
    
    public static function IfSearchRobot(){

              $spanrobot = "google|baiduspider|bot|crawl|spider|slurp|sohu-search|lycos|robozilla|traveler|ia_archiver|heritrix|urllib|alexa|ask|yacy|legs|trivialnutch|rambler|tool|netcraft|search|larbin|yahoo|konqueror|exabot|yandex";

              if(preg_match("/($spanrobot)/i", strtolower($_SERVER['HTTP_USER_AGENT']))){
                     return true;
              } else {
                     return false;
              }

       }



	//遠程跨網資料傳送  curl_setopt  方式
    public static function http_post_data($url, $data_string='',$method='GET') {



              if(extension_loaded('curl')){

                    $ch = curl_init();
                    
                    
                    curl_setopt($ch, CURLOPT_TIMEOUT, '120');
                    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Keep-Alive: 300','Connection: keep-alive')) ;
                    curl_setopt($ch, CURLOPT_USERAGENT, 'Simple Life NET System');
                    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                    curl_setopt($ch, CURLOPT_AUTOREFERER, true);
                    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
                    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);




                    if($method == 'POST'){
        
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_CUSTOMREQUEST,  'POST');
                        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        
        
                    }



                    curl_setopt ($ch, CURLOPT_HEADER, 0);
        
                    $replaydata = curl_exec($ch);
        
                    curl_close($ch);


			        return $replaydata;

            } else {
    
                    return null;
            }



	}






      /*
       *      取得某目錄的檔案
       *      string $dirpath  路徑
       *      string $limit  限制過濾副檔名
       *
      */
    public static function Folderinfo($dirpath,$limit="") {

            if($limit && !is_array($limit)){
                $limit = explode(",",$limit);
            }


              $arealord = @opendir($dirpath);
              while($dir = @readdir($arealord)) {
                     if($dir && !in_array($dir,array('.','..','.svn','index.htm','index.html','.htaccess'))) {


                           if($limit && in_array(pathinfo($dir,PATHINFO_EXTENSION),$limit)) {
                                    $filename[] = $dir;

                           } else if(!$limit) {

                                    $filename[] = $dir;
                           }
                     }
              }

              closedir($arealord);
              return $filename;
      }





	//路徑轉換
    public static function escapePath($fileName, $ifCheck = true) {
		if (!security::_escapePath($fileName, $ifCheck)) {
			exit('Forbidden');
		}
		return $fileName;
	}


	//路徑轉換
    
    public static function _escapePath($fileName, $ifCheck = true) {
		$tmpname = strtolower($fileName);
		$tmparray = array('://',"\0");
		$ifCheck && $tmparray[] = '..';
		if (str_replace($tmparray, '', $tmpname) != $tmpname) {
			return false;
		}
		return true;
	}

	//目錄轉換
    
    public static function escapeDir($dir) {
		$dir = str_replace(array("'",'#','=','`','$','%','&',';'), '', $dir);
		return rtrim(preg_replace('/(\/){2,}|(\\\){1,}/', '/', $dir), '/');
	}



	// $_GET $_POST
    public static function globalcheck(){
		global $_GET,$_POST;

		foreach ($_POST as $Pkey => $Pvalue) {
			$_POST[$Pkey] = security::secure_input($Pvalue);
		}

		foreach ($_GET as $Gkey => $Gvalue) {
			$_GET[$Gkey] = security::secure_input($Gvalue);
		}

	}
    
    
    
    public static function GlobalsALL($methoddata){

		if ($methoddata == 'GET') {

			foreach ($_GET as $key => $value) {
				if(isset($_GET[$key])) {
					$GLOBALS[$key] = $_GET[$key];
				}
			}


		} elseif ($methoddata == 'POST') {

			foreach ($_POST as $key => $_value) {

				if(isset($_POST[$key])) {

                              	$GLOBALS[$key] = $_POST[$key];
				}
			}
		}

	}



	/*
	*$arraydata  允許通過變數
	*$methoddata 決定是否GET或POST來通過,無的話就全部通過!
	*/
    
    public static function Getglobals($arraydata,$methoddata="ALL",$Chver=flase){

		if(!is_array($arraydata)){
			$arraydata = array($arraydata);
		}

		foreach ($arraydata as $key) {
			if ($key == 'GLOBALS'){
			 	continue;
			}

			if ($methoddata == 'GET' && isset($_GET[$key])) {

				$GLOBALS[$key] = $_GET[$key];

			} elseif ($methoddata == 'POST' && isset($_POST[$key])) {

				$GLOBALS[$key] = $_POST[$key];


			} else if($methoddata == 'ALL'){

				if (isset($_POST[$key])) {
					$GLOBALS[$key] = trim($_POST[$key]);
				}

                            if (isset($_GET[$key])) {
					$GLOBALS[$key] = trim($_GET[$key]);
				}
			}

			//變數檢查轉換  防 xss  flase => 存在  true=>空
			if(isset($GLOBALS[$key]) && $Chver == 'true'){
				$GLOBALS[$key] = security::Chrconversion($GLOBALS[$key]);
			}
		}

	}

	//字符轉換
    public static function Chrconversion($string) {
		$string = str_replace(array("\0","%00","\r"), '', $string);
		$string = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $string);
		$string = str_replace(array("%3C",'<'), '&lt;', $string);
		$string = str_replace(array("%3E",'>'), '&gt;', $string);
		$string = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $string);
		return $string;
	}



	//檢查允許通過變數,否則一律都刪除
    public static function Getglobal(){

		//$Variable = array('GLOBALS' => 1,'_GET' => 1,'_POST' => 1,'_REQUEST' => 1,'_COOKIE' => 1,'_SERVER' => 1,'_ENV' => 1,'_FILES' => 1);

		$Variable = array('GLOBALS' => 1,'_GET' => 1,'_POST' => 1,'_COOKIE' => 1,'_SERVER' => 1,'_FILES' => 1,'_REQUEST' => 1);
		foreach ($GLOBALS as $key => $value) {
			if (!isset($Variable[$key])) {
				$GLOBALS[$key] = null;
				unset($GLOBALS[$key]);
			}
		}


		$data= array(
				'HTTP_REFERER',
				'HTTP_HOST',
				'HTTP_X_FORWARDED_FOR',
				'HTTP_USER_AGENT',
				'HTTP_CLIENT_IP',
				'HTTP_SCHEME',
				'HTTPS',
				'PHP_SELF',
				'REQUEST_URI',
				'REQUEST_METHOD',
				'REMOTE_ADDR',
				'SCRIPT_NAME',
				'QUERY_STRING',
				'HTTP_ACCEPT_ENCODING'
		);


		$GLOBALS['GServer'] = security::CodeServer($data);

		if(!$GLOBALS['GServer']['PHP_SELF']){
			$GLOBALS['GServer']['PHP_SELF'] = security::CodeServer('SCRIPT_NAME');
		}


	}

	//變數轉換
    public static function slashes(&$array) {
		if (is_array($array)) {
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					security::slashes($array[$key]);
				} else {
					$array[$key] = addslashes($value);
				}
			}
		}
	}

	// 加載類文件
    public static function import($file) {
		if (!is_file($file)) return false;
		require_once $file;
	}


	//html轉換輸出
    public static function htmlEscape($param) {
		return trim(htmlspecialchars($param, ENT_QUOTES));
	}


	//過濾 HTML 標簽
    public static function stripTags($param) {
		return trim(strip_tags($param));
	}

	//整型數過濾
    public static function int($param) {
		return intval($param);
	}

	//字符過濾前後空白
    public static function str($param) {
		return trim($param);
	}


	//是否數組
    public static function isArray($params) {
		return (!is_array($params) || !count($params)) ? false : true;
	}


	//變數是否在數組中存在
    
    public static function inArray($param, $params) {
		return (!in_array((string)$param, (array)$params)) ? false : true;
	}



	//是否 object
    public static function isObj($param) {
		return is_object($param) ? true : false;
	}


	//是否是布爾型
    public static function isBool($param) {
		return is_bool($param) ? true : false;
	}

	//是否是浮點數型
    public static function isfloat($param){
    
        return is_float($param) ? true : false;
    }

	//是否是數字型
    public static function isNum($param) {
		return is_numeric($param) ? true : false;
	}

	
	 //獲取服務器變量
    
    public static function CodeServer($data) {
		$server = array();
		$array = (array) $data;
		foreach ($array as $key) {
			$server[$key] = NULL;
			if (isset($_SERVER[$key])) {
				$server[$key] = str_replace(array('<','>','"',"'",'%3C','%3E','%22','%27','%3c','%3e'), '', $_SERVER[$key]);
			}
		}
		return is_array($data) ? $server : $server[$keys];
	}
	





	/*
	*	防 sql 及 php  隱碼攻擊
	*	最後變數都過濾掉產生無效
	*/
    
    public static function secure_input($val, $charset = 'UTF-8'){


				if (is_array($val)){
					$output = array();
					foreach ($val as $key => $data){
						$output[$key] = security::secure_input($data, $charset);
					}
					return $output;

				} else {



						//判斷是否為浮點數
						if(filter_var($val, FILTER_VALIDATE_FLOAT)){

								return filter_var($val, FILTER_SANITIZE_NUMBER_FLOAT);

						//判斷數字是否有在範圍內
						} else if(filter_var($val, FILTER_VALIDATE_INT)){

								return filter_var($val, FILTER_SANITIZE_NUMBER_INT);

						//URL驗證
						} else if(filter_var($val, FILTER_VALIDATE_URL)){

								return filter_var($val, FILTER_SANITIZE_URL);


						} else if(filter_var($val,  FILTER_VALIDATE_EMAIL)){

								return filter_var($val, FILTER_SANITIZE_EMAIL);

						} else {

				                    	//過濾針對 SQL injection 做過濾(例如單、雙引號)

				                    		//$val = filter_var($val, FILTER_SANITIZE_SPECIAL_CHARS);


				                    		$array = array('update','show table','insert into','select','fopen','file','copy','move_uploaded_file',
				                        'file_put_contents','fwrite','fputs','passthru','shell_exec','exec','system','mysql_query','mysql_unbuffered_query',
				                        'mysql_select_db','mysql_drop_db','mysql_db_query','mysqli_query','mysqli_unbuffered_query',
				                        'mysqli_select_db','mysqli_drop_db','mysqli_db_query','sqlite_query','sqlite_exec','sqlite_array_query',
				                        'sqlite_unbuffered_query','phpinfo','<?php','?'.'>','../','javascript', 'vbscript','marquee','iframe','function','passwd','etc',
				                        'eval','/var/www','union','load_file','outfile',"\'",'\/\*','|\*','\.\.\/','\.\/','%0b','substr','lower','lpad','unhex','0x');


				                    		return str_ireplace($array,'',$val);
						}




				}
	}

	/*
	*
	*      防止重新整理攻擊
	*      說明:未超過規定之內重新整理判斷  cc   攻擊
	*
	*/
    
    public static function AntiCC(){
		global $onlineip,$ifmodel,$default_refreshtime;

        /*
		if(define('NoAntiCC')){
			return false;
		}*/



		if($default_refreshtime){


				//屏障區網
			
				if($onlineip){
					$ipdb = explode(".",$onlineip);
					if(in_array($ipdb[0],array('192','127'))){
						return false;
					}
				}

				$REQUEST_URI = $_SERVER['PHP_SELF'].'?'.$_SERVER['QUERY_STRING'];


				session_start();

				$timestamp =time();

				if(isset($_SESSION['lastview'])){

		       		    $lastvisit = $_SESSION['lastview']['timestamp'];
		       	       $lastpath = $_SESSION['lastview']['REQUEST_URI'];


					if($lastpath && $REQUEST_URI == $lastpath && $timestamp - $lastvisit  < $default_refreshtime) {

		                            	//exit("11");
						//警告:系統偵測到使用者異常非法重新整理!
						//目前已經記錄您的IP 位於 國家,將不排除追究法律責任

						$UserIPcountrys = GeoipCheck('2');


						echo "Warning: System Monitor User Browsing Exception Rewind! <BR />";

						echo "The system will retain the IP record, will retain the law to pursue the right<BR />";

						echo "Your browsing IP '".$onlineip."'  Comes From  ".$UserIPcountrys." country ";


						//Protection



						unset($_SESSION['lastview']);
						session_unset();
						session_destroy();
						//setcookie('lastview','',$timestamp - 86400);

						exit;


					} else {

						session_unset();

						session_destroy();

		       			$_SESSION['lastview']['timestamp']   = $timestamp;

		       			$_SESSION['lastview']['REQUEST_URI'] = $REQUEST_URI;

						session_write_close();
					}


				} else {

		       		$_SESSION['lastview']['timestamp']   = $timestamp;
		       		$_SESSION['lastview']['REQUEST_URI'] = $REQUEST_URI;

					session_write_close();

				}

		}

	}

       /*
              自助加解密
       */
    
    
    public static function DisEncryption($str,$encode='encode') {

              if(strlen($str) > 0){

                     if($encode == 'encode'){

                            $ba64 = base64_encode($str);
                            $lengthNum = strlen($ba64)/2;
                            $scor = substr($ba64,0,$lengthNum);
                            $scos = substr($ba64,$lengthNum,strlen($ba64));

                            $extname = strtoupper(md5($ba64));
                            $expr = substr($extname,0,16);
                     	$exps = substr($extname,16,strlen($extname));

                            return $lengthNum.$scor.$expr.$scos.$exps;

                     } else if($encode == 'decode'){

                            if(preg_match("/^\d{0,3}/",$str,$r)) {

                                   $le = strlen($r[0]);

                                   return base64_decode(substr($str,$le,$r[0]).substr($str,($le+$r[0]+16),$r[0]));
                            }
                     }
              }
       }

}


?>
