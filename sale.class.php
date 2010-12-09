<?php
class Sale {
    public $inputConfFile;
    //public $dbuser;
    //public $dbpass;
    //public $numr;
    //public $prc;
    //import from file all data
    private $cnt;
    private $title;

    function importDS ($pathFile) {
        $file = file_get_contents($pathFile);
        $file = explode('[SECT]', $file);
        $file = array_slice($file, 0, count($file) - 1);
        foreach ($file as $pos => $string) : //import districts and streets
            $string = explode("\n", $string);
            $check = 0;
            foreach ($string as $line) :
                
                if ((strlen($line) > 1) && ($check == 0)) :
                    $check = 1;
                    $dName = explode(',', $line);
                    $fName = $line;
                    $slug = $this->Translit($dName[0]);
                    mysql_query("INSERT INTO districts (
								    							`id`,
								    							`district_name`,
                                                                                                                        `district_slug`,
								    							`district_synonim`)
								     VALUES ('', '$dName[0]', '$slug', '$fName')");
                    $lastID = mysql_insert_id(); 
                elseif (strlen($line) > 1) :
                    $strslug = $this->Translit($line);
                    mysql_query("INSERT INTO streets (
								    							`id`,
								    							`street_name`,
                                                                                                                        `street_slug`,
								    							`district_id`)
								     VALUES ('', '$line', '$strslug', '$lastID')");
                endif;
            endforeach
        ;
        endforeach
        ;
        //getting our man names
        preg_match("!\[MANNAMES\](.*?)\[MANNAMES\]!si", file_get_contents($pathFile), $out);
        $names = explode("\n", $out[1]);
        foreach ($names as $name) :
            if (strlen($name) > 1) :
                mysql_query("INSERT INTO `names` (`id` ,`name` ,`order` ,`gender`) VALUES (NULL , '$name', '1', '1'
);");


            endif;
        endforeach
        ;
        //getting our man second names
        preg_match("!\[MANSECONDNAMES\](.*?)\[MANSECONDNAMES\]!si", file_get_contents($pathFile), $out);
        $names = explode("\n", $out[1]);
        foreach ($names as $name) :
            if (strlen($name) > 1) :
                mysql_query("INSERT INTO `names` (`id` ,`name` ,`order` ,`gender`) VALUES (NULL , '$name', '2', '1'
);");


            endif;
        endforeach
        ;
        //getting our woman names
        preg_match("!\[WOMANNAMES\](.*?)\[WOMANNAMES\]!si", file_get_contents($pathFile), $out);
        echo "insert woman names\r\n";
        $names = explode("\n", $out[1]);
        foreach ($names as $name) :
            if (strlen($name) > 1) :
                mysql_query("INSERT INTO names (`id`,`name`,`order`,`gender`)  VALUES ('', '$name', '1',  '2')");



            endif;
        endforeach
        ;
        //getting our woman  second names
        preg_match("!\[WOMANSECONDNAMES\](.*?)\[WOMANSECONDNAMES\]!si", file_get_contents($pathFile), $out);
        $names = explode("\n", $out[1]);
        foreach ($names as $name) :
            if (strlen($name) > 1) :
                mysql_query("INSERT INTO names (`id`,`name`,`order`,`gender`)  VALUES ('', '$name', '2',  '2')");



            endif;
        endforeach
        ;
        //import phonecodes
        preg_match("!\[PHONECODES\](.*?)\[PHONECODES\]!si", file_get_contents($pathFile), $out);
        $names = explode("\n", $out[1]);
        foreach ($names as $name) :
            if (strlen($name) > 0) :
                mysql_query("INSERT INTO phones (`id`, `phone`)
								     VALUES ('', '$name')");



            endif;
        endforeach
        ;
        //import configs and menu sections
        preg_match("!\[SECTIONS\](.*?)\[SECTIONS\]!si", file_get_contents($pathFile), $out);
        $names = explode("\n", $out[1]);
        foreach ($names as $name) :
            if (strlen($name) > 0) :
                
                $name = explode("|", $name);
            $secslug = $this->Translit($name[0]); 
                mysql_query("INSERT INTO sections (`id`, `quantity`, `section_name`,`section_slug`, `config`, `group`)
								     VALUES ('', '$name[2]', '$name[0]','$secslug', '$name[1]', '$name[3]')");
            endif;
        endforeach
        ;
    }
        function Translit($text) {
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
                "ъ"=>"'","ы"=>"y","ь"=>"","э"=>"eh","ю"=>"ju","я"=>"ja"," " => "_");
        $str = strtr($text, $gost);
        return strtolower($str);
    }
    //generate rooms
    function ROOMS ($numr, $prc) {
        preg_match_all("!\%ROOMS\%(.*?)\%ROOMS\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        $this->title .= $result;
        if ($rmRn < $prc) {
            return $result;
        }
    }
    //generate district + 1 street for sale ads
    function DISTRICT ($numr, $prc) {
        $districtCount = mysql_query("SELECT id, district_synonim FROM districts");
        while ($row = mysql_fetch_array($districtCount)) {
            $disID['id'][] = $row['id'];
            $disID['syn'][] = $row['district_synonim'];
        }
        switch ($numr) :
            case ($numr == '1'): //if we choose this, we get one district and one street
                $chosenD = array_rand(range(1, count($disID['id'])), $numr);
                if ($chosenD == 0) {
                    $chosenD = 1;
                }
                $query = mysql_query('SELECT id, street_name
		FROM streets WHERE district_id=' . $disID['id'][$chosenD] . ';');
                while ($row = mysql_fetch_assoc($query)) {
                    $street[] = $row['street_name'] . '[' . $row['id'] . ']';
                }
                
                $streets = array_rand($street, 1);
                $street = $street[$streets];
                
                $query1 = mysql_query("SELECT `district_synonim` FROM `districts` WHERE `id` = " . $disID['id'][$chosenD] . " LIMIT 1;");
                $result = mysql_result($query1, NULL);
                $result = explode(',', $result);
                $result = trim($result[rand(0, count($result) - 1)]) . '{' . $disID['id'][$chosenD] . '}' . ", " . $street;
                break;
            case ($numr == '2'): //if we choose this, we get several districts or one district and several streets
                unset($result);
                if (rand(1, 2) > 1) :
                    if(count($disID['id']) < 5): $max = count($disID['id']); else: $max = 5;
                    endif;
                    $chosenD = array_rand($disID['id'], rand(2, $max));
                    foreach ($chosenD as $key) :
                        $dis = explode(',', $disID['syn'][$key]);
                        @$result .= trim($dis[rand(0, count($dis) - 1)]) . '{' . $disID['id'][$key] . '}, ';
                    endforeach
                ;
                else :
                    $chosenD = array_rand(range(1, count($disID['id'])), 1);
                    $query = mysql_query('SELECT id, street_name
		FROM streets WHERE district_id=' . $disID['id'][$chosenD] . ';');
                    while ($row = mysql_fetch_assoc($query)) {
                        $street[] = $row['street_name'] . '[' . $row['id'] . ']';
                    }
                    if (count($street) > 5) :
                        $max = 5;
                    else :
                        $max = count($street);
                    endif;
                    $streets = array_rand($street, $max);
                    foreach ($streets as $str) :
                        @$result .= ", " . $street[$str];
                    endforeach
                    ;
                    $dis = explode(',', $disID['syn'][$chosenD]);
                    @$result = trim($dis[rand(0, count($dis) - 1)]) . '{' . $disID['id'][$chosenD] . '}' . $result;
                endif;
                break;
            case ($numr == '3'):
                $streetPref = array('на улице',
                        '',
                        'рядом с улицей',
                        '',
                        'недалеко от улицы',
                        '',
                        'поблизости от улицы',
                        'желательно на улице',
                        '',
                        'желательна улица');
                $chosenD = array_rand(range(1, count($disID['id'])), 1);
                if ($chosenD == 0) {
                    $chosenD = 1;
                }
                $query = mysql_query('SELECT id, street_name
		FROM streets WHERE district_id=' . $disID['id'][$chosenD] . ';');
                while ($row = mysql_fetch_assoc($query)) {
                    $street[] = $row['street_name'] . '[' . $row['id'] . ']';
                }
                $streets = array_rand($street, 1);
                $street = $street[$streets];
                $query1 = mysql_query("SELECT `district_synonim` FROM `districts` WHERE `id` = " . $disID['id'][$chosenD] . " LIMIT 1;");
                $result = mysql_result($query1, NULL);
                $result = explode(',', $result);
                $result = trim($result[rand(0, count($result) - 1)])
                        . '{' . $disID['id'][$chosenD] . '}' . ", "
                        . $streetPref[rand(0, count($streetPref) - 1)] . " " . $street;
                break;
            endswitch
        ;
        $rmRn = rand(0, 100);
        if (@$rmRn < $prc) {
            return $result;
        }
    }
    //generate floor
    function FLOOR ($numr, $prc) {
        preg_match_all("!\%FLOOR\%(.*?)\%FLOOR\%!si", file_get_contents($this->inputConfFile), $rot);
        $flArray = explode(',', $rot[1][0]);
        $chOne = array_rand($flArray);
        if (strlen($flArray[1]) > 1) :
            @$result = trim($flArray[$chOne]);
        else :
            @$result = rand(1, trim($flArray[$chOne])) . "/" . trim($flArray[$chOne]);
        endif;
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return trim($result);
        }
    }
    //generate type of building - panel, brick etc.
    function BRICK ($numr, $prc) {
        preg_match_all("!\%BRICK\%(.*?)\%BRICK\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    //generate square
    function SQUARE ($numr, $prc) {
        preg_match_all("!\%SQUARE\%(.*?)\%SQUARE\%!si", file_get_contents($this->inputConfFile), $rot);
        @$values = explode("|", trim($rot[1][0]));
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                $numr = explode("-", $value);
                $min = $numr[0];
                $max = $numr[1];
                $result1 = round(($min + lcg_value() * (abs($max - $min))), 1);
                @$result .= $result1 . "|";
            }
        }
        @$result = substr($result, 0, - 1);
        return $result;
    }
    /*====================================================
	 * here begin function for generating some options, not main
    */
    function TOILET ($numr, $prc) { //generate toilet
        preg_match_all("!\%TOILET\%(.*?)\%TOILET\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function MORE ($numr, $prc) { //generate other shit like refrigerator and bus stop near appartments
        $numr = explode("-", $numr);
        @$cnt = rand($numr[0], $numr[1]);

        preg_match_all("!\%MORE\%(.*?)\%MORE\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        if ($cnt > count($wArr))
            $cnt = count($wArr) - 1;
        $rand_keys = array_rand($wArr, $cnt);
        if (count($rand_keys) > 1) {
            foreach ($rand_keys as $key) {
                @$result .= ", " . $wArr[$key];
            }
        } else {
            $result = $wArr[$rand_keys];
        }
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function WATER ($numr, $prc) {
        preg_match_all("!\%WATER\%(.*?)\%WATER\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function NEIGH ($numr, $prc) {
        preg_match_all("!\%NEIGH\%(.*?)\%NEIGH\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function PERFAMILY ($numr, $prc) {
        preg_match_all("!\%PERFAMILY\%(.*?)\%PERFAMILY\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function RCOND ($numr, $prc) {
        preg_match_all("!\%RCOND\%(.*?)\%RCOND\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function WHOLIVE ($numr, $prc) {
        preg_match_all("!\%WHOLIVE\%(.*?)\%WHOLIVE\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function CONDITION ($numr, $prc) {
        preg_match_all("!\%CONDITION\%(.*?)\%CONDITION\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function DEALCOND ($numr, $prc) {
        $numr = explode("-", $numr);
        @$cnt = rand($numr[0], $numr[1]);
        preg_match_all("!\%DEALCOND\%(.*?)\%DEALCOND\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        if ($cnt > count($wArr))
            $cnt = count($wArr) - 1;
        $rand_keys = array_rand($wArr, $cnt);
        if (count($rand_keys) > 1) {
            foreach ($rand_keys as $key) {
                @$result .= ", " . $wArr[$key];
            }
        } else {
            $result = $wArr[$rand_keys];
        }
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function BUYCOND ($numr, $prc) {
        preg_match_all("!\%BUYCOND\%(.*?)\%BUYCOND\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function OWNER ($numr, $prc) {
        preg_match_all("!\%OWNER\%(.*?)\%OWNER\%!si", file_get_contents($this->inputConfFile), $rot);
        $values = explode("\n", $rot[1][0]);
        foreach ($values as $value) {
            if (strlen($value) > 1) {
                @$wArr[] .= $value;
            }
        }
        $rand_keys = array_rand($wArr, $numr);
        $result = $wArr[$rand_keys];
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function PRICE ($numr, $prc) {
        $numr = explode("-", $numr);
        $cnt = rand($numr[0], $numr[1]);
        $cnt = ceil($cnt);
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            $this->cnt = $cnt;
            return $cnt . " т.р. ";
        }
    }
    function PHONE ($numr, $prc) {
        $query = mysql_query("SELECT * FROM phones;");
        while ($row = mysql_fetch_assoc($query)) :
            $name[] = $row['phone'];
        endwhile
        ;
        $name = $name[array_rand($name, 1)];
        $name = explode('|', $name);
        if (strlen($name[0]) > 0) :
            $phoneNum = '';
            switch (strlen($name[0])) :
                case 1: //city phone
                    foreach ($name as $val => $part) :
                        $piece = pow(10, $part);
                        $phoneNum .= rand(10, $piece - 1);
                        if ($val != count($name) - 1)
                            $phoneNum .= '-';
                    endforeach
                    ;
                    break;
                case (strlen($name[0]) > 1): //mobile phone
                    foreach ($name as $val => $part) :
                        if ($val == 0) :
                            $phoneNum .= $part . '-';
                            continue;
                        endif;
                        $piece = pow(10, $part);
                        $phoneNum .= rand($piece / 10, $piece - 1);
                        if ($val != count($name) - 1)
                            $phoneNum .= '-';
                    endforeach
                    ;
                    break;
                endswitch
        ;



        endif;
        return $phoneNum;
    }
    function NAME ($numr, $prc) {
        $term = rand(1, 2);
        $query = mysql_query("SELECT name FROM `names` WHERE `order` = '1' AND `gender` = '$term' ORDER BY RAND() LIMIT 1");
        $result = mysql_result($query, NULL);
        $term1 = rand(1, 2);
        if ($term1 > 1) :
            $query = mysql_query("SELECT name FROM `names` WHERE `order` = '2' AND `gender` = '$term' ORDER BY RAND() LIMIT 1");
            $result .= " " . mysql_result($query, NULL);



        endif;
        $rmRn = rand(0, 100);
        if ($rmRn < $prc) {
            return $result;
        }
    }
    function ReadFromDB ($query) {
        $qdb = mysql_query($query);
        while ($row = mysql_fetch_array($gdb)) :
            $res[] = $row;
        endwhile
        ;
        return $res;
    }
    function MOVEDOUT ($numr, $prc) {
        $movedoutRooms = array('на комнату в коммун. кв.',
                'на комнату в общ.',
                'на малосемейку',
                'на комнату в коммун. кв. c вашей доплатой',
                'на комнату в общ. c вашей доплатой',
                'на малосемейку c вашей доплатой',
                'на гостинку',
                'на гостинку c вашей доплатой',
                'на 2 комнаты',
                'на квартиру меньшей площади',
                'на 1 комн. кв.',
                'на 2 комн. кв.',
                'на 2 комн.',
                'на 1 комн. c вашей доплатой');
        @$strout .= $movedoutRooms[rand(0, 13)] . ", ";
        return $strout;
    }
    function GenerateAD ($count, $cid, $configPath) {
        for ($i = 0; $i < $count; $i ++) :
            $inputConfFile = $configPath;
            $inputConf = file($inputConfFile);
            if (! is_array($inputConf)) {
                die("cannot read config");
            }
            $this->inputConfFile = $configPath;
            $formula = $inputConf[0];
            array_shift($inputConf);
            $formula = explode("|", $formula);
            $ad = array();
            foreach ($formula as $num => $val) {
                preg_match_all("!{(.*?)}!si", $val, $out);
                if (count($out[1]) < 2) {
                    $comm = explode(',', ($out[1][0]));
                    $ad[$comm[0]] = $this->$comm[0]($comm[1], $comm[2]);
                } else {
                    @$string[] .= "MARK";
                    foreach ($out[1] as $tval) {
                        $comm = explode(',', ($tval));
                        $ad['mix'][$comm[0]] = $this->$comm[0]($comm[1], $comm[2]);
                    }
                }
            }
            $insertion = '';
            foreach ($ad as $param => $str) :
                if ($param == 'ROOMS') :
                    $str = explode('|', $str);
                    if (strlen(@$str[1]) > 0) :
                        $room_type = $str[1];
                    else :
                        $room_type = 0;
                    endif;
                    $insertion .= $str[0] . ', ';



                endif;
                if ($param == 'DISTRICT') :
                    preg_match_all("!\{([0-9]+)\}!si", $str, $out);
                    foreach ($out[1] as $ou) :

                        @$rids .= $ou . ",";
                    endforeach
                    ;
                    preg_match_all("!\[([0-9]+)\]!si", $str, $out);
                    foreach ($out[1] as $ou) :

                        @$sids .= $ou . ",";
                    endforeach
                    ;
                    $rids = substr($rids, 0, - 1);
                    $this->title .= $rids;
                    @$sids = substr($sids, 0, - 1);
                    @$this->title .= @$sids;



                endif;
                if ($param == 'mix') :
                    shuffle($str);
                    foreach ($str as $st) :
                        if (strlen($st) > 0)
                            $insertion .= $st . ', ';
                    endforeach
                ;



                endif;
                if (! is_array($str))
                    if (strlen($str) > 0)
                        $insertion .= $str . ', ';
                $insertion = preg_replace("!\[([0-9]+)\]!si", "", $insertion);
                $insertion = preg_replace("!\{([0-9]+)\}!si", "", $insertion);
                
            endforeach
            ;
            $this->title = preg_replace("/\|[0-9]+\|/", " ", $this->title . '|');
            
            $query = mysql_query("INSERT INTO `arenda`.`ads` (
                                                                    `id` ,
                                                                    `room_type` ,
                                                                    `districtID` ,
                                                                    `streetID` ,
                                                                    `group_ID` ,
                                                                    `price` ,
                                                                    `fullad` ,
                                                                    `title`
							)
							VALUES (
                                                                    '' ,
                                                                    '$room_type' ,
                                                                    '$rids',
                                                                    '$sids',
                                                                    '$cid',
                                                                    '$this->cnt' ,
                                                                    '$insertion' ,
                                                                    '$this->title'
							);");

            unset($insertion, $rids, $sids, $this->title);
        endfor
        ;
    }
}
?>