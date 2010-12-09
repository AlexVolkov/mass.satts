<?
//need to load full site url
//need to generate translite urls for each category, street, district etc
require_once './classes/db.class.php';

class MakeStatic {

    protected $db;
    private $dCloud;
    private $prefix;
    private $config;

    function  __construct() {
        $this->db = new db();
        $this->db->init('localhost', 'root', 'engagemenot', 'arenda');
        $this->config = parse_ini_file('./config.ini');
    }
    public function Translit($text) {
        $gost = array(
                "Г"=>"G","Ё"=>"JO","Є"=>"EH","Ы"=>"Y","І"=>"I","і"=>"i","г"=>"g",
                "ё"=>"jo","№"=>"#","є"=>"eh","ы"=>"y","А"=>"A","Б"=>"B","В"=>"V",
                "Г"=>"G","Д"=>"D","Е"=>"E","Ж"=>"ZH","З"=>"Z","И"=>"I","Й"=>"JJ",
                "К"=>"K","Л"=>"L","М"=>"M","Н"=>"N","О"=>"O","П"=>"P","Р"=>"R","С"=>"S",
                "Т"=>"T","У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"C","Ч"=>"CH","Ш"=>"SH","Щ"=>"SHH",
                "Ъ"=>"'","Ы"=>"Y","Ь"=>"", "Э"=>"EH","Ю"=>"JU","Я"=>"JA","а"=>"a","б"=>"b",
                "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"zh","з"=>"z","и"=>"i","й"=>"jj",
                "к"=>"k","л"=>"l","м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r","с"=>"s",
                "т"=>"t","у"=>"u","ф"=>"f","х"=>"h","ц"=>"c","ч"=>"ch","ш"=>"sh","щ"=>"shh",
                "ъ"=>"'","ы"=>"y","ь"=>"","э"=>"eh","ю"=>"ju","я"=>"ja", " " => "_");
        $str = strtr($text, $gost);
        return strtolower($str);
    }

    function TagCloud($type = string, $tags = array(), $linkAct = false, $limit) {
        unset($this->dCloud);
        switch($type) {
            case 'district':
                $query = "SELECT r.`id`, r.`district_name`, count(a.id) `count`
                    FROM districts r INNER JOIN ads `a` ON a.districtID = r.id group by r.id ORDER BY `count` ASC";
                $cQ = $this->db->query( $query );//all values district - ads count, without streets.
                while($row = mysql_fetch_array($cQ)) {
                    $tmpQ['id'][] = $row['id'];
                    $tmpQ['title'][] = $row['district_name'];
                    $tmpQ['count'][] = $row['count'];
                }
                $this->prefix = '/district/';
                break;
            case 'distreet':
                $query = "SELECT r.`id` , r.`street_name` , count( a.id ) `count`
                    FROM streets r INNER JOIN ads `a` ON a.streetID = r.id GROUP BY r.id ORDER BY `count` DESC LIMIT 0, ".$limit.""; 
                $cQ = $this->db->query( $query );//all values district - ads count, with streets.
                while($row = mysql_fetch_array($cQ)) {
                    $tmpQ['id'][] = $row['id'];
                    $tmpQ['title'][] = $row['street_name'];
                    $tmpQ['count'][] = $row['count'];
                }
                $this->prefix = '/street/';
                break;
            case 'rooms':
                $query = "SELECT `room_type`, count(`id`) AS count FROM ads GROUP BY `room_type` ";
                $cQ = $this->db->query( $query );//all values district - ads count, with streets.
                while($row = mysql_fetch_array($cQ)) {
                    //$tmpQ['title'][] = $row['room_type'];
                    if($row['room_type'] == '0')
                        $tmpQ['title'][] = "Разные";
                    $tmpQ['id'][] = 0;
                    if($row['room_type'] == '1')
                        $tmpQ['title'][] = "Однокомнатные";
                    $tmpQ['id'][] = 1;
                    if($row['room_type'] == '2')
                        $tmpQ['title'][] = "Двухкомнатные";
                    $tmpQ['id'][] = 2;
                    if($row['room_type'] == 3)
                        $tmpQ['title'][] = "Трехкомнатные";
                    $tmpQ['id'][] = 3;
                    if($row['room_type'] == 4)
                        $tmpQ['title'][] = "Четырехкомнатные";
                    $tmpQ['id'][] = 4;
                    $tmpQ['count'][] = $row['count'];

                }
                $this->prefix = '/room/';
                break;
            case 'planning':
                $query = "SELECT r.`id` , r.`section_name` , count( a.id ) `count`
                    FROM sections r INNER JOIN ads `a` ON a.group_ID = r.id GROUP BY r.id ORDER BY `count` ASC";
                $cQ = $this->db->query( $query );//view planning + count.
                while($row = mysql_fetch_array($cQ)) {
                    $tmpQ['id'][] = $row['id'];
                    $tmpQ['title'][] = $row['section_name'];
                    $tmpQ['count'][] = $row['count'];
                }
                $this->prefix = '/planning/';
                break;
        }

        $maxSize = count($tmpQ['title']);

        //cut elements which more than $limit
        $tmpQ['title'] = array_slice($tmpQ['title'], -$limit);
        $tmpQ['count'] = array_slice($tmpQ['count'], -$limit);

        foreach($tmpQ['title'] as $num=>$val) {
            $open = @preg_replace("!>!si", " style=\"font-size:".((($num / 2) * 10) + 100)."%\" >", $tags[0]);
            if($linkAct) {
                $val = "<a href=\"".$this->config['url'] . $this->prefix . $tmpQ['id'][$num].  "/\" title=\"" . $val . "\"> " . $val ."</a>";
            }


            @$this->dCloud .= $open . $val . '(' .$tmpQ['count'][$num]. ')' . $tags[1];
        }
        return $this->dCloud;
    }
    public function MakeContent($url) {
        unset($addval, $ret);
        $url = $this->FormatUrl($url);
        foreach($url['name'] as $num => $val) {
            if($val == "room") $addval .= " AND `room_type` = '" . $url['param'][$num]."'";
            if($val == "district") $addval .= " AND `districtID` = '" . $url['param'][$num]."'";
            if($val == "street") $addval .= " AND `streetID` = '" . $url['param'][$num]."'";
            if($val == "planning") $addval .= " AND `group_ID` = '" . $url['param'][$num]."'";
            if($val == "ads") $addval .= " AND `id` = '" . $url['param'][$num]."'";
        }


        $str = $this->db->query("SELECT * FROM `ads` WHERE 1" . $addval);
        while($row = mysql_fetch_array($str)){
            $ret .= "<a href=\"" . $this->config['url'] ."/ads/". $row['id'] . "." . $this->config['fileext']
                    . "\" title=\"". $row['title'] ."\">" . $row['title'] . "</a><br/>" ;
        }
        return $ret;
        //print_r($url);
    }

    protected function FormatUrl($url) {
        $tmp = explode("/", $url);
        $t = 1;
        foreach($tmp as $val) {
            if(strlen($val) > 0) {
                if($this->is_odd($t)) {
                    $tmp1['name'][] = $val;
                } else {
                    $tmp1['param'][] = $val;
                }
                $t++;
            }
        }
        return $tmp1;
    }
    protected function is_odd($num) {
        return (is_numeric($num)&($num&1));
    }

    protected function is_even($num) {
        return (is_numeric($num)&(!($num&1)));
    }
    function  __destruct() {
        $this->db->done();
    }

}

/*$test = new MakeStatic();
$tags['open'] = '<span>';
$tags['close'] = '</span>';
$tmp = $test->TagCloud('rooms',$tags, $linkAct = false, $limit=0);
print_r($tmp);
//$test->__destruct();*/
?>

