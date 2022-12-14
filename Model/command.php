<?php
!defined('WEBROOT') && exit('Forbidden');
include(WEBROOT.'Model/MaxMind/src/MaxMind/Db/Reader.php');
include(WEBROOT.'Model/MaxMind/src/MaxMind/Db/Reader/Decoder.php');
include(WEBROOT.'Model/MaxMind/src/MaxMind/Db/Reader/InvalidDatabaseException.php');
include(WEBROOT.'Model/MaxMind/src/MaxMind/Db/Reader/Metadata.php');
include(WEBROOT.'Model/MaxMind/src/MaxMind/Db/Reader/Util.php');


function GeoipCheck(){
        global $onlineip;
    
        //$onlineip = '125.227.75.203';
    
        //$onlineip = '103.104.169.13';
    
        $str='';

    
        if($onlineip == '127.0.0.1'){

                $str = "區網IP";
            
        } else {
            
                $reader = new MaxMind\Db\Reader(WEBROOT.'Model/GeoLite2-Country.mmdb');
        
        
                //print_r($reader->get($onlineip));
                
                $country = $reader->get($onlineip);
                
                $str= $country['country']['names']['en'];
                $reader->close();
        }
        
       
        
        return $str;
}






/*
* @return String
* Unix 時間戳記轉正確時間
*/

function showdate($timestamp,$timeformat=null,$ty=false){

	$default_timedf='8';

	$default_datefmat='Y-m-d H:i';

	$timeformat = $timeformat ? $timeformat : $default_datefmat ;

	$d = date($timeformat,$timestamp);

	if($ty == true){

		$gh = date('H',$timestamp);

		if($gh < 6){
  			$datename="凌晨";
		}elseif($gh < 7){
  			$datename="早上";
		}elseif($gh < 12){
  			$datename="上午";
		}elseif($gh < 13){
  			$datename="中午";
		}elseif($gh < 18){
  			$datename="下午";
		}else{
  			$datename="晚上";
		}

		$d = str_replace(" "," $datename ",$d);
	}
	return $d;
}


function FilterHTML($value){

	$value = preg_replace('/\s+/','', $value);

	$value = preg_replace("/\<(.+?)\>/is","",$value);

	$value = str_replace(array('&','&nbsp;','&amp;','&quot;','&rsquo;','&gt;','&lt;',"\r\n","\n","\t"),"",$value);

	$value = str_replace(array("<",">","~","!","：","--","-","(",")","_","*","&","#","{","}","[","]","／","&#32;","「","」","！"),"",$value);

	$value = trim($value);

	return $value;
}




function strFilter($str,$air=false,$lan=false){

	//過濾多餘的空白
	if($air){
		$str = preg_replace('/\s+/','', $str);

	}


	if($lan){
		$str = str_replace(array("<",">","~","!","：","--","-","(",")","_","*","&","#","{","}","[","]","／","&#32;"),"",$str);
		$str = str_replace(array('"',"'","\t",'&nbsp;'),array('&quot;','&#39;','&nbsp;&nbsp;',''),$str);
	}


    	$farr = array(
        	"/<(\/?)(script|i?frame|style|html|body|title|link|meta|\?|\%)([^>]*?)>/isU",  //過濾 <script 等可能引入惡意內容或惡意改變顯示佈局的代碼,如果不需要插入flash等,還可以加入<object的過濾
        	"/(<[^>]*)on[a-zA-Z]+\s*=([^>]*>)/isU",  //過濾javascript的on事件
   	);
	$tarr = array(
        	"＜\\1\\2\\3＞",
		"\\1\\2",
   	);
	$str = preg_replace($farr,$tarr,$str);

	return trim($str);
}





function strCode($code,$numes='200'){

        $code = preg_replace("/\<(.+?)\>/is","",$code);

	$code= str_replace(array('&nbsp;','&amp;','&quot;','&rsquo;','&gt;','&lt;'),"",$code);

	//$code = preg_replace("/\s+/is","",$code);

	return  substrs(trim($code),$numes);
}






/**
 * 截取字串
 *
 * @param string $content 內容
 * @param int $length 截取字串長度
 * @param string $add 是否省略?，Y|N
 * @return string
 */
function substrs($content, $length, $add = 'Y') {

	if (strlen($content) > $length) {

              $content = substr(compress_html($content), 0, $length);


		$hex = '';
		$len = strlen($content) - 1;
		for ($i = $len; $i >= 0; $i -= 1) {
			$ch = ord($content[$i]);
			$hex.=" $ch";
			if (($ch & 128) == 0 || ($ch & 192) == 192) {
				return substr($content, 0, $i).($add == 'Y' ? ' ....' : '');
			}
		}
		return $content.$hex.($add == 'Y' ? ' ....' : '');
	}
	return $content;
}


function clear_utf8 ($str) {
	//return preg_replace('/([\x80-\xff]*)/i', '', $str);

	return str_replace(chr(0xC2).chr(0xA0),"",$str);
}



 /*
*   @$str       字串資料
*   @$split_len 每次截取字元長度
*   @$show      類型
*/

function utf8_str_split($str, $split_len = 1,$show = 1){

	$str = FilterHTML($str);


	$len = mb_strlen($str, 'UTF-8');
	$arr = array();
	$temp_str = $str;
	for($i = 0 ; $i<$len/$split_len ; $i++){
		$arr[] = mb_substr($temp_str, 0, $split_len, 'UTF-8');
		$temp_str = mb_substr($temp_str, $split_len, $len, 'UTF-8');
	}


	if($show == '1'){
		foreach($arr as $k => $v){
			if($v){
				$ardb.= $ardb ? "、".trim($v) : trim($v);
			}
		}
		return $ardb;

	} else if($show == '2'){
		foreach($arr as $k => $v){
			if($v){
				$ardb.= $ardb ? ' '.trim($v) : trim($v) ;
			}
		}
		return $ardb;

	} else if($show == '3'){
		foreach($arr as $k => $v){
			
			if($v){
				$ardb.= $ardb ? ',"'.trim($v).'"' : '"'.trim($v).'"' ;
			}
		}
		return $ardb;
	}
}




/**
* 壓縮 html 清除換行符,清除製表符,去掉註釋標記
* @param $string
* @return 壓縮後的 $string
*/

function compress_html($string) {
	//$string = str_replace("\r\n",'', $string);
	$string = str_replace("\n",'', $string);
	$string = str_replace("\t",'', $string);

	$pattern = array(
		"/> *([^ ]*) *</",
		"/[\s]+/",
		"/<!--[^!]*-->/",
		//"/\" /",
		"/ \"/",
		"'/\*[^*]*\*/'"
	);
	$replace = array(
		">\\1<",
		" ",
		"",
		//"\"",
		"\"",
		""
	);

	$string = preg_replace($pattern, $replace, $string);

	return $string;
}









/*
* @return String
* 取出正確 IP  包含隱藏 Proxy IP
*/

function GetcheckIp(){

	if ($_SERVER['HTTP_X_FORWARDED_FOR'] && $_SERVER['REMOTE_ADDR']) {
			if (strstr($_SERVER['HTTP_X_FORWARDED_FOR'], ',')) {
				$x = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
				$_SERVER['HTTP_X_FORWARDED_FOR'] = trim(end($x));
			}
			if (preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_X_FORWARDED_FOR'])) {
				$onlineip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			}
	} elseif ($_SERVER['HTTP_CLIENT_IP'] && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/', $_SERVER['HTTP_CLIENT_IP'])) {
		$onlineip = $_SERVER['HTTP_CLIENT_IP'];
	}

	if(!$onlineip && preg_match('/^([0-9]{1,3}\.){3}[0-9]{1,3}$/',$_SERVER['REMOTE_ADDR'])){
		$onlineip = $_SERVER['REMOTE_ADDR'];
	}

	!$onlineip && $onlineip = "No IP";

	return $onlineip;
}



//分頁
function showpage($count, $page, $numofpage, $url, $max = null, $ajaxurl = 'false') {
	$count = intval($count);
	$page = intval($page);
	$numofpage = intval($numofpage);
	$max = intval($max);
	$total = $numofpage;
	$pages=null;

	if (!empty($max)) {
		$max = (int) $max;
		$numofpage > $max && $numofpage = $max;
	}
	/*
	if ($numofpage <= 1 || !is_numeric($page)) {
		return '';
	}*/


	if (is_numeric($page)){

		list($pre, $next) = array($page - 1, $page + 1);

		list($url, $mao) = explode('#', $url);

		$mao && $mao = '#' . $mao;

		$pre == 0 && $pre=1;

		$pages = '<nav aria-label="Page navigation example"><ul class="pagination">';


		if($page > 4){


			if($ajaxurl == 'true'){

				    $pages.= "<li class=\"page-item\"><a href=\"javascript:;\" class=\"page-link\" onclick=\"return ajaxpage('{$url}page=1');\" title=\"&#31532;&#19968;&#38913;\">&lt;&lt;</a></li>";

			}  else {


				    $pages.= "<li class=\"page-item\"><a href=\"{$url}page=1$mao\"  class=\"page-link\" title=\"&#31532;&#19968;&#38913;\">&lt;&lt;</a></li>";
			}



		}

		if($numofpage > 1){
            if($ajaxurl == 'true'){
				        $pages.= "<li class=\"page-item\"><a href=\"javascript:;\" class=\"page-link\" onclick=\"return ajaxpage('{$url}page=$pre');\" title=\"&#19978;&#19968;&#38913;\">&lt;</a></li>";
            }  else {
				        $pages.= "<li class=\"page-item\"><a href=\"{$url}page=$pre$mao\" class=\"page-link\"  title=\"&#19978;&#19968;&#38913;\">&lt;</a></li>";
			}
		}

		for ($i = $page - 2; $i <= $page - 1; $i++) {
			        if ($i < 1) continue;

                     if($ajaxurl == 'true'){

				            $pages .= "<li class=\"page-item\"><a href=\"javascript:;\" class=\"page-link\" onclick=\"return ajaxpage('{$url}page=$i');\" title=\"&#31532;{$i}&#38913;\">$i</a></li>";

                     }  else {

				            $pages .= "<li class=\"page-item\"><a href=\"{$url}page=$i$mao\" class=\"page-link\" title=\"&#31532;{$i}&#38913;\">$i</a></li>";
			        }
		}


		$pages.= "<li class=\"page-item active\"><a href=\"javascript:;\" class=\"page-link\">$page</a></li>";


		if ($page < $numofpage) {
                    $flag = 0;
                    $topage = $numofpage>2 ? '1' : '0';
        
                    for ($i = $page + 1; $i <= $numofpage-$topage; $i++) {
        
                                if($ajaxurl == 'true'){
                            $pages .= "<li class=\"page-item\"><a href=\"javascript:;\" class=\"page-link\" onclick=\"return ajaxpage('{$url}page=$i');\" title=\"&#31532;{$i}&#38913;\">$i</a></li>";
                                }  else {
                            $pages .= "<li class=\"page-item\"><a href=\"{$url}page=$i$mao\" class=\"page-link\" title=\"&#31532;{$i}&#38913;\">$i</a></li>";
                        }
        
        
                        $flag++;
                        if ($flag == 2) break;
                    }
		}

		if($total > 3 && $page < $total){


                    if($ajaxurl == 'true'){
                    
                                $pages.= "<li class=\"page-item\"><a  href=\"javascript:;\" class=\"page-link\" onclick=\"return ajaxpage('{$url}page=$total');\" title=\"&#26368;&#24460;&#31532;{$total}&#38913;\">...$total</a></li>";
                    
                    }  else {
                    
                                $pages.= "<li class=\"page-item\"><a  href=\"{$url}page=$total$mao\" class=\"page-link\"  title=\"&#26368;&#24460;&#31532;{$total}&#38913;\">...$total</a></li>";
                    
                    }

		}

		if($page < $total){
                    if($ajaxurl == 'true'){
                    
                                $pages.= "<li class=\"page-item\"><a href=\"javascript:;\" class=\"page-link\" onclick=\"return ajaxpage('{$url}page=$next');\" title=\"&#19979;&#19968;&#38913;\">&gt;</a></li>";
                    
                    }  else {
                    
                                $pages.= "<li class=\"page-item\"><a href=\"{$url}page=$next$mao\" class=\"page-link\"  title=\"&#19979;&#19968;&#38913;\">&gt;</a></li>";
                    }
		}





		$pages.= "<li class=\"page-item\"><a href=\"javascript:;\" class=\"page-link\">&#20849; {$total} &#38913;/";

		//$pages.= "&#x6BCF;&#x9801; $defaultpage &#x7B46/";

		$pages.= "&#x5171;&#x6709; $count &#31558;</a></li>";

		$pages.= "</ul></nav>";

		return $pages;
	}


}



/*
*   @param  string $path  目錄路徑
*   @param  string $Spath  預設刪除全部
*   @param  bool false   只刪除 目錄子資料及檔案
*   @param  array NoFile  array('1','2')  保留目錄及檔案
*   對某一個目錄刪除包含裡面檔案
*
*/
function deldir($path,$Spath=true,$NoFile=""){

	if ($NoFile && !is_array($NoFile)) {
		$NoFile = array($NoFile);
	}

	if (file_exists($path)) {
		if (is_file($path)) {
			@unlink($path);
		} else {
			$handle = opendir($path);
			while ($file = readdir($handle)) {
				if ($file!='' && !in_array($file,array('.','..'))) {

					if($NoFile && in_array($file,$NoFile)){
						continue;
					}

					if (is_dir("$path/$file")) {

						deldir("$path/$file");

					} else {

						@unlink("$path/$file");
					}
					rmdir("$path/$file");
				}
			}
			closedir($handle);
			if($Spath){
				rmdir($path);
			}
		}
	}
}






/**
 * 讀取文件
 *
 * @param string $fileName 文件絕對路徑
 * @param string $method 讀取模式
 */
function readover($fileName, $method = 'rb') {
	$fileName = security::escapePath($fileName);
	$data = '';
	if ($handle = @fopen($fileName, $method)) {
		flock($handle, LOCK_SH);
		$data = @fread($handle, filesize($fileName));
		fclose($handle);
	}
	return $data;
}

/*
*
*
*  寫入檔案
*  @param  string  $filename  路徑/檔名
*  @param  string  $data  內容
*  @param  string $method 類型  w+ a+  rb+
*  @param  string $iflock 鎖定檔案
*  @param  string $check 撿查路徑符號
*  @param  string $chmod  設定為 Linux 讀寫 777
*/

function writeover($filename,$data,$method='rb+',$iflock=1,$check=1,$chmod=1){
	$check && strpos($filename,'..')!==false && exit('Forbidden');
	touch($filename);
	$handle = fopen($filename,$method);
	$iflock && flock($handle,LOCK_EX);
	fwrite($handle,$data);
	$method=='rb+' && ftruncate($handle,strlen($data));
	fclose($handle);
	$chmod && @chmod($filename,0777);
}

/*
* @param  string $fileName  路徑/檔名
*刪除檔案
*/
function Punlink($fileName) {
	return @unlink(security::escapePath($fileName));
}



/*
*
*  @ 建立目錄
*
*/

function createFolderd($path,$write=false){
	if (!is_dir($path)) {
		createFolderd(dirname($path));
		@mkdir($path);
		@chmod($path,0777);
		@fclose(@fopen($path.'/index.html','w'));
		@chmod($path.'/index.html',0777);
              $write && writeover(WEBROOT.'cache/del.txt',$path."\n",'a+');
	}


}

/*
*
**   SQL  過濾格式
*
*
*/

function GetFilter($var,$strip = true) {
	if (is_array($var)) {
		foreach ($var as $key => $value) {
			$var[$key] = trim(GetFilter($value,$strip));
		}
		return $var;
	} elseif (is_numeric($var)) {
		return " '".trim($var)."' ";
	} else {
		return " '".addslashes($strip ? stripslashes(trim($var)) : trim($var))."' ";
	}
}

function SqlSingle($arraydb,$strip=true) {
	$arraydb = GetFilter($arraydb,$strip);
	$strdb = '';
	foreach ($arraydb as $key => $value) {
          	$strdb .= ($strdb ? ', ' : ' ').$key.'='.$value;
	}
	return $strdb;
}


function SqlMulti($array,$strip=true) {
	$str = '';
	foreach ($array as $val) {
		if (!empty($val)) {
			$str .= ($str ? ', ' : ' ') . '(' . showImplode($val,$strip) .') ';
		}
	}
	return $str;
}
function showImplode($array,$strip=true) {
	return implode(',',GetFilter($array,$strip,true));
}


/*
*      @return   string
*     String   日期轉  unix  時間
*
*/


function linuxTime($time){
	return strtotime($time);
}


/*
*    @return   string
*    特殊符號過濾
*
*/

function Charcv($string){
	$string = str_replace(array("\0","%00","\r"), '', $string);
	$string = preg_replace(array('/[\\x00-\\x08\\x0B\\x0C\\x0E-\\x1F]/','/&(?!(#[0-9]+|[a-z]+);)/is'), array('', '&amp;'), $string);
	$string = str_replace(array("%3C",'<'), '&lt;', $string);
	$string = str_replace(array("%3E",'>'), '&gt;', $string);
	$string = str_replace(array('"',"'","\t",'  '), array('&quot;','&#39;','    ','&nbsp;&nbsp;'), $string);
	return $string;
}


/*
* 判斷是否有 BOM 自動移除
* @param $str
* @return $str
*/
function removeBOM($str = ''){
    if(substr($str, 0,3) == pack("CCC",0xef,0xbb,0xbf)){
		$str = substr($str, 3);
    }
    return $str;
}




function ObHeader($URL) {

       $URL =  HtmlRewrite($URL);


	echo '<meta http-equiv="expires" content="0">';
	echo '<meta http-equiv="Pragma" content="no-cache">';
	echo '<meta http-equiv="Cache-Control" content="no-cache">';
	echo "<meta http-equiv='refresh' content='0;url=$URL'>";

	exit;

}





/**
* 操作加鎖
*
* @param string $t 鎖定名稱
* @param int $u 附加名稱
* @return bool 是否成功
*/

function Lock($t='user') {
	global $timestamp;

       if($t){
              $u = md5($t);
       	if(!file_exists(WEBROOT."cache/{$t}_{$u}.lock")){
       		writeover(WEBROOT."cache/{$t}_{$u}.lock","LOCK");
       		return true;
       	} else {
       		return false;
       	}
       }
}


/**
* 操作解鎖
*
* @param string $t 鎖定名稱
* @param int $u 附加名稱
*/

function UnLock($t='user') {

       if($t){
              $u = md5($t);
              if(file_exists(WEBROOT."cache/{$t}_{$u}.lock")){
       		Punlink("cache/{$t}_{$u}.lock");
                     return true;
       	} else {
                     return false;
       	}
       }
}


function Filetime($file) {
	global $timestamp;

       if(!file_exists($file)){
		writeover($file,"LOCK");
	}

	return intval($timestamp-filemtime($file));
}


function valueexport($input,$t = null,$Filter=false) {
	$output = '';
	if (is_array($input)) {
		$output .= "array(\r\n";
		foreach ($input as $key => $value) {
			$output .= $t."\t".valueexport($key,$t."\t",$Filter).' => '.valueexport($value,$t."\t",$Filter);
			$output .= ",\r\n";
		}
		$output .= $t.')';
	} elseif (is_string($input)) {


		if($Filter){
			$output .= "'".FilterHTML(str_replace(array("\\","'"),array("\\\\","\'"),$input))."'";
		} else {

			$output .= "'".str_replace(array("\\","'"),array("\\\\","\'"),$input)."'";
		}

	} elseif (is_int($input) || is_double($input)) {
		$output .= "'".(string)$input."'";
	} elseif (is_bool($input)) {
		$output .= $input ? 'true' : 'false';
	} else {
		$output .= 'NULL';
	}
	return $output;
}






?>
