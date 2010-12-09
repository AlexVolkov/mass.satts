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
$smarty = new Smarty ();//объект smarty
$smarty->template_dir='./tpl/';//указываем путь к шаблонам
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
    unset($title, $content);

/*    switch($url[0]) {
        case("distreet"):
            $title .= 'Поиск по улицам';
            //$query = mysql_query("SELECT ads.* FROM `ads` WHERE ads.districtID =
            //(SELECT districts.id FROM `districts` WHERE districts.district_slug = '$url[1]');");
            break;
        case("district"):

            if($url[2]) {
                $sid = $url[2];
                $mod = 'SELECT `district_name` FROM `districts`';
                $tlevel = 1;
            }

            if($url[3]) {
                $sid = $url[3];
                $mod = 'SELECT `street_name` FROM `streets`';
                $tlevel = 2;
            }

            $vch = filter_var($sid, FILTER_VALIDATE_INT);
            if(!$vch)
                $tpl_name= '404';

            $query = mysql_query($mod . " WHERE id = '$sid'");
            $tt = mysql_result($query, 0,0);

            $title .= 'Поиск по районам - ' . $tt;

            if($tlevel == 1) {
                $query = mysql_query("SELECT ads.* FROM `ads` WHERE ads.districtID =
                    (SELECT districts.id FROM `districts` WHERE districts.district_slug = '$sid');");
                while($row = mysql_fetch_array($query)) {
                    $content .= "<a href=\"" . $queryUrl . $row['id']." \" >".$row['title']."</a><br/>";
                    //print_r($row); die();
                }
            }
            if($tlevel ==2) {
                $query = mysql_query("SELECT ads.* FROM `ads` WHERE ads.id = '$sid';");
                while($row = mysql_fetch_array($query)) {
                    print_r($row); 
                }
            }

            break;
        default:
            $title .= 'Главная';
            break;
    }

*/

    $smarty->assign('content', $content);

}

$smarty->assign('title', $title);
$smarty->display($tpl_name.".tpl");








?>
