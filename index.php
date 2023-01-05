<?php
require("global.php");
security::Getglobals(array('page'),'GET','true');
security::GlobalsALL('POST');
$title = "Home";


require_once printHTML('header');


$search = security::stripTags($search);
if(!empty($search)){

    //模糊查詢
    $searchuid = $db->keys("*$search*");

    //總數
    $count = count($searchuid);
    //每次分頁幾筆
    $page_size = 20;
    //當前分頁
    $page_num=(!empty($page)) ? $page : 1;
    //分頁數
    $page_count = ceil($count/$page_size);
    
    $links = 1;
    $start      = ( ( $page_num - $links ) > 0 ) ? $page_num - $links : 1;
    $end        = ( ( $page_num + $links ) <$page_count ) ? $page_num + $links : $page_count;
    $data=[];
    

    $output = array_slice($searchuid, ($page_num-1)*$page_size,($page_num)*$page_size);
    
    foreach($output as $v){

        $uid = $db->get("username:".explode(':',$v)[1]);
        
        $data[]=$db->hgetall("user:".$uid);
    }
    
    $page = showpage($count, $page_num, $page_count,'index.php?');
    
} else {
    
    
    
    //總數
    $count =$db->lsize("uid");//获取链表的长度
    //每次分頁幾筆
    $page_size = 20;
    //當前分頁
    $page_num=(!empty($page)) ? $page : 1;
    //分頁數
    $page_count = ceil($count/$page_size);
    
    
    //$ids = $db->lrange("uid",($page_num-1)*$page_size,(($page_num-1)*$page_size+$page_size-1));
    
    $ids = $db->sort('uid', array(
        'LIMIT' => array(($page_num-1)*$page_size,$page_size),
        'SORT'=>'desc'
    ));
    
    
    $links = 1;
    $start      = ( ( $page_num - $links ) > 0 ) ? $page_num - $links : 1;
    $end        = ( ( $page_num + $links ) <$page_count ) ? $page_num + $links : $page_count;
    
    //var_dump($ids);
    
    foreach($ids as $v){
        $data[]=$db->hgetall("user:".$v);
        
        //$db->hmset("user:".$v,array(
            //"regtime"=>$timestamp,
           // "logintime"=>$timestamp,
        // ));
    
    }
    
    
    /*
    foreach($data as $value){
        
        $db->set("email:".$value['email'],$value['uid']);
   
    }*/
    

    
    
    
    
    
    $page = showpage($count, $page_num, $page_count,'index.php?');
    
    
    
}





if(!empty($_COOKIE['auth'])){
    $id=$db->get("auth:".$_COOKIE['auth']);
    $name=$db->hget("user:".$id,"username");
}

require_once printHTML('index');

require_once printHTML('footer');footer();

?>









