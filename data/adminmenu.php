<?php
!defined('WEBROOT') && exit('Forbidden');

$menuleft = array(
        'adminuser' => array(
                    'name'        =>	'管理員',
                    'option'=> array(
                                'adminoriginal'         =>	'<a href="javascript:;" onclick="topmenu(\'adminoriginal\',\'原創管理員\'); return false;">原創管理員</a>',
                                'AdminApplication'      =>	'<a href="javascript:;" onclick="topmenu(\'AdminApplication\',\'管理員列表\');" >管理員列表</a>',
                                'adminlevel'            =>	'<a href="javascript:;" onclick="topmenu(\'adminlevel\',\'管理員等級\'); return false;">管理員等級</a>',
                                'LoginLog'              =>	'<a href="javascript:;" onclick="topmenu(\'LoginLog\',\'登入出記錄\');return false;" >登入出記錄</a>',
                                'OperationLog'          =>	'<a href="javascript:;" onclick="topmenu(\'OperationLog\',\'操作記錄\');return false;" >操作記錄</a>',
                    ),
        ),
        'members' => array(
                    'name'        =>	'會員管理',
                    'option'=> array(
                                'members'              =>  '<a href="javascript:;" onclick="topmenu(\'members\',\'會員管理\');return false;" >會員管理</a>',
                    ),
        ),
);


$moduledb = array(
    'adminoriginal'     =>	'[原創管理員]',
    'AdminApplication'  =>  '[管理員列表]',
    'adminlevel'        =>	'[管理員等級]',
    'LoginLog'          =>  '[登入出記錄]',
    'members'          =>  '[會員管理]',
);

?>