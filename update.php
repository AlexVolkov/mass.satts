<?php
//error_reporting(1);
include_once './sale.class.php';
$gen = new Sale();




$dbname = 'arenda';
$dbuser = 'root';
$dbpass = 'engagemenot';
$link = mysql_connect('localhost', $dbuser, $dbpass);
if (! $link) {
    die('Not connected : ' . mysql_error());
}
$db_selected = mysql_select_db($dbname, $link);
if (! $db_selected) {
    die('Can\'t use connection : ' . mysql_error());
}
echo "connection with $dbname established\r\n";

mysql_query("TRUNCATE `ads` ;");
mysql_query("TRUNCATE `districts` ;");
mysql_query("TRUNCATE `names` ;");
mysql_query("TRUNCATE `phones` ;");
mysql_query("TRUNCATE `sections` ;");


$gen->importDS('./import/rzn.txt'); //exception for existing tables



$ini_config = parse_ini_file('./config.ini');
$query = mysql_query("DELETE FROM ads;");
$query = mysql_query("SELECT * FROM sections ;");

while ($row = mysql_fetch_array($query)) :
    $readSect['id'][] = $row['id'];
    $readSect['quantity'][] = $row['quantity'];
    $readSect['config'][] = $row['config'];
endwhile;

foreach ($readSect['id'] as $num => $value) :
        echo $readSect['config'][$num] ." begin\r\n";
        $str =$gen->GenerateAD($readSect['quantity'][$num], $readSect['id'][$num], $readSect['config'][$num]);
        //echo $str;
endforeach
;
?>          