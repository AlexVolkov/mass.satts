<?php
ini_set('error_reporting', E_STRICT);
ini_set('display_errors',1);


$queryUrl =  $_SERVER['REDIRECT_URL'];

///echo $queryUrl;
function FormatUrl($url) {
    $tmp = explode("/", $url);
    foreach($tmp as $val) {
        if(strlen($val) > 0) {
            $tmp1[] = $val;
        }
    }
    return $tmp1;
}

require './classes/makeStatic.class.php';
define('SMARTY_DIR','/usr/share/php/smarty/');
require(SMARTY_DIR.'Smarty.class.php');
$service = new MakeStatic();
$smarty = new Smarty ();
$smarty->template_dir='./tpl/'; 
$smarty->compile_dir='./tpl/templates_c/';
$smarty->config_dir='./tpl/configs/';
$smarty->cache_dir='./tpl/cache/';
$smarty->caching = false;
$smarty->error_reporting = E_ALL; // LEAVE E_ALL DURING DEVELOPMENT
$smarty->debugging = false;
$smarty->left_delimiter = '<!--{';
$smarty->right_delimiter = '}-->';
$db = new db();
$db->init('localhost', 'root', 'engagemenot', 'arenda');
$config = parse_ini_file('./config.ini');



if(!isset($queryUrl)) {

    $title .= 'Главная';
    $smarty->assign('tagcloud', $service->TagCloud('district',array('<span>','</span>'),true, 20));
    $smarty->assign('tagcloudstreet', $service->TagCloud('planning',array('<span>','</span>'),true, 30));
    $smarty->assign('tagclouddistree', $service->TagCloud('distreet',array('<span>','</span>'), true, 20));
    $smarty->assign('tagprice', $service->TagCloud('rooms',array('<span>','</span>'), true, 20));
    $tpl_name = 'index';

}   else {

    $url = $queryUrl;
    $content = $service->MakeContent($url);
    $tpl_name='category';
    //unset($title, $content);



    $smarty->assign('content', $content);

}

$smarty->assign('title', $title);
$smarty->display($tpl_name.".tpl");








?>
