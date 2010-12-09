<?php
error_reporting(1);
$pathDB = './db/realty.sqlite';

settype ( $prc, "int" );

//вынести в инклюд


$mnames = file ( "./lib/names/mnames.txt" );
$msnames = file ( "./lib/names/msnames.txt" );
$wnames = file ( "./lib/names/wnames.txt" );
$wsnames = file ( "./lib/names/wsnames.txt" );


		
	$phonecodes = array (
			'8-903-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-905-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-906-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-910-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-915-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-916-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-920-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-960-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-961-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-962-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			'8-953-' . rand ( 100, 999 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ), 
			rand ( 10, 99 ) . '-' . rand ( 10, 99 ) . '-' . rand ( 10, 99 ) 
	);
	
	function GetRandomValue($array, $percentage, $sep) {
		$rul = rand ( 0, 100 );
		if ($rul <= $percentage) {
			$random = array_rand ( $array );
			$value = $array [$random];
			if (isset ( $sep )) {
				$sep = ", ";
				
			}
			return $value . $sep;
		} else {
			return FALSE;
		}
	}
	
	function DISTRICTSALE($numr, $prc) {
		global $pathDB;
		$db = new SQLite3 ( $pathDB );
		$districtCount = $db->querySingle ("SELECT count(*) FROM districts");
		$chosenD = array_rand ( range ( 1, $districtCount ), $numr );
		if ($chosenD == 0) {
			$chosenD = 1;
		}
		$chosenD = $db->querySingle ("SELECT id FROM districts WHERE rowid = $chosenD");

		$query = $db->query ( 'SELECT street_name FROM streets WHERE district_id='.$chosenD.';' );

		while ( $row = $query->fetchArray()) {
			$street [] = $row ['street_name']; 
		}
		$streets = array_rand ( $street, 1 );
		$street = $street [$streets];
		$query1 = $db->querySingle ( "SELECT `district_name` FROM `districts` WHERE `id` = " . $chosenD . " LIMIT 1;" );
		$result = $query1 . ", " . $street;
		//$result = iconv ( 'cp1251', 'UTF-8', $result );
		
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}

	function ROOMS($numroom, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%ROOMS\%(.*?)\%ROOMS\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numroom );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	
	function BRICK($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%BRICK\%(.*?)\%BRICK\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function FLOORN($numr, $prc) {
		$flArray = array ("5", "9", "12" );
		$chOne = array_rand ( $flArray );
		$result = rand ( 1, $flArray [$chOne] ) . "/" . $flArray [$chOne];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function FLOORNS($numr, $prc) {
		$flArray = array ("2", "3", "5" );
		$chOne = array_rand ( $flArray );
		$result = rand ( 1, $flArray [$chOne] ) . "/" . $flArray [$chOne];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function FLOORNH($numr, $prc) {
		$flArray = array ("4", "3", "5" );
		$chOne = array_rand ( $flArray );
		$result = rand ( 1, $flArray [$chOne] ) . "/" . $flArray [$chOne];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function FLOORNB($numr, $prc) {
		$flArray = array ("4", "9", "5" );
		$chOne = array_rand ( $flArray );
		$result = rand ( 1, $flArray [$chOne] ) . "/" . $flArray [$chOne];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function FLOORNUL($numr, $prc) {
		$flArray = array ("10", "9", "5", "12" );
		$chOne = array_rand ( $flArray );
		$result = rand ( 1, $flArray [$chOne] ) . "/" . $flArray [$chOne];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	
	function SQUARE($numr, $prc) {
		$numr = explode ( "-", $numr );
		$min = $numr [0];
		$max = $numr [1];
		$result = ($min + lcg_value () * (abs ( $max - $min )));
		return round ( $result, 1 ) . " м.кв.";
	
	}
	function SQUAREST($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%SQUAREST\%(.*?)\%SQUAREST\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "|", trim ( $rot [1] [0] ) );
		
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				$numr = explode ( "-", $value );
				$min = $numr [0];
				$max = $numr [1];
				$result1 = round ( ($min + lcg_value () * (abs ( $max - $min ))), 1 );
				$result .= $result1 . "|";
			}
		}
		$result = substr ( $result, 0, - 1 );
		return $result . ", ";
	}
	function PHONE($numr, $prc) {
		global $phonecodes;
		$ph = array_rand ( $phonecodes );
		return " " . $phonecodes [$ph];
	}
	function NAME($numr, $prc) {
		global $wnames, $wsnames, $mnames, $msnames;
		$term = rand ( 1, 3 );
		if ($term >= 2) {
			$distName = array_rand ( $wnames );
			$dist = $wnames [$distName];
			$term1 = rand ( 1, 3 );
			if ($term1 >= 2) {
				$distName = array_rand ( $wsnames );
				$dist .= " " . $wsnames [$distName];
			}
		} else {
			$distName = array_rand ( $mnames );
			$dist = $mnames [$distName];
			$term1 = rand ( 1, 3 );
			if ($term1 >= 2) {
				$distName = array_rand ( $msnames );
				$dist .= " " . $msnames [$distName];
			}
		}
		
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return " " . $dist . ", ";
		}
	}
	
	function TOILET($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%TOILET\%(.*?)\%TOILET\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function MORE($numr, $prc) {
		global $inputConfFile;
		$numr = explode ( "-", $numr );
		$cnt = rand ( $numr [0], $numr [1] );
		preg_match_all ( "!\%MORE\%(.*?)\%MORE\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $cnt );
		if (count ( $rand_keys ) > 1) {
			foreach ( $rand_keys as $key ) {
				$result .= ", " . $wArr [$key];
			}
		} else {
			$result = $wArr [$rand_keys];
		}
		$rmRn = rand ( 0, 100 );
		//var_dump ($result);
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function WATER($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%WATER\%(.*?)\%WATER\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function NEIGH($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%NEIGH\%(.*?)\%NEIGH\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function PERFAMILY($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%PERFAMILY\%(.*?)\%PERFAMILY\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function RCOND($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%RCOND\%(.*?)\%RCOND\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	
	function WHOLIVE($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%WHOLIVE\%(.*?)\%WHOLIVE\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function CONDITION($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%CONDITION\%(.*?)\%CONDITION\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function PRICE($numr, $prc) {
		
		$numr = explode ( "-", $numr );
		$cnt = rand ( $numr [0], $numr [1] );
		$cnt = ceil ( $cnt );
		
		if ($rmRn < $prc) {
			return $cnt . "0 000 т.р., ";
		}
	}
	function PRICES($numr, $prc) {
		
		$numr = explode ( "-", $numr );
		$cnt = rand ( $numr [0], $numr [1] );
		$cnt = ceil ( $cnt );
		
		if ($rmRn < $prc) {
			return "до " . $cnt . " т.р., ";
		}
	}
	function DEALCOND($numr, $prc) {
		global $inputConfFile;
		$numr = explode ( "-", $numr );
		$cnt = rand ( $numr [0], $numr [1] );
		preg_match_all ( "!\%DEALCOND\%(.*?)\%DEALCOND\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $cnt );
		if (count ( $rand_keys ) > 1) {
			foreach ( $rand_keys as $key ) {
				$result .= ", " . $wArr [$key];
			}
		} else {
			$result = $wArr [$rand_keys];
		}
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	function OWNER($numr, $prc) {
		global $inputConfFile;
		preg_match_all ( "!\%OWNER\%(.*?)\%OWNER\%!si", file_get_contents ( $inputConfFile ), $rot );
		$values = explode ( "\n", $rot [1] [0] );
		foreach ( $values as $value ) {
			if (strlen ( $value ) > 1) {
				@$wArr [] .= $value;
			}
		}
		$rand_keys = array_rand ( $wArr, $numr );
		$result = $wArr [$rand_keys];
		$rmRn = rand ( 0, 100 );
		if ($rmRn < $prc) {
			return $result . ", ";
		}
	}
	
	function MOVEDOUT($numr, $prc) {
		$movedoutRooms = array ('комната в коммун. кв.', 'Гостинка', '2 комнаты' );
		
		$forWhat = array ('на 3 комн', 'на 1 комн. кв.', 'на 2 комн. кв.', 'на 2 комн.', 'на 3 комн. кв.', 'на 1 комн.', 'на комнату меньшей площади' );
	
	}
	
	function DISTRICTSS($numr, $prc) {
		
		$streetPref = array ('на улице', 'рядом с улицей', 'недалеко от улицы', 'поблизости от улицы', 'желательно на улице', '', '', 'желательна улица', '', '' );
		
		$streetFiles = array ('../rzn_streets/butirki.txt', '../rzn_streets/centr.txt', '../rzn_streets/dp.txt', '../rzn_streets/dp.txt', '../rzn_streets/dyagilevo.txt', '../rzn_streets/kalnoe.txt', '../rzn_streets/kanishevo.txt', '../rzn_streets/mervino.txt', '../rzn_streets/moscovsky.txt', '../rzn_streets/nedostoevo.txt', '../rzn_streets/priokskiy.txt', '../rzn_streets/rosha.txt', '../rzn_streets/shlakoviy.txt', '../rzn_streets/sokolovka.txt', '../rzn_streets/sol.txt', '../rzn_streets/stroitel.txt', '../rzn_streets/teatr.txt', '../rzn_streets/yuzhniy.txt', '../rzn_streets/zhd.txt' );
		
		$dist1 = array ('и прилег. районы', 'или в любом районе города', 'или любой неотдаленный район', 'или в черте города', 'или можно спальные районы' );
		
		$rand = rand ( 0, 3 );
		if ($rand >= 1) {
			$distNum = rand ( 0, 4 );
			$streetFiles1 = array_rand ( $streetFiles, $distNum );
			if (is_array ( $streetFiles1 )) {
				foreach ( $streetFiles1 as $val ) {
					$tmp = exec ( "cat " . $streetFiles [$val] . " | head -1" );
					$tmp = explode ( ",", $tmp );
					@$distName .= $tmp [rand ( 0, count ( $tmp ) - 1 )] . ", ";
				}
			} else {
				$tmp = file ( $streetFiles [$streetFiles1] );
				$tmp = $tmp [0];
				$tmp = explode ( ",", $tmp );
				@$distName .= $tmp [rand ( 0, count ( $tmp ) - 1 )] . ", ";
			}
		
		} else {
			$streetFiles1 = array_rand ( $streetFiles, 1 );
			$file = file ( $streetFiles [$streetFiles1] );
			$tmp = $file [0];
			$tmp = explode ( ",", $tmp );
			@$distName .= $tmp [rand ( 0, count ( $tmp ) - 1 )] . ", ";
			array_shift ( $file );
			
			$max = "6";
			if (($max >= count ( $file ))) {
				$max = count ( $file );
			}
			
			$streetNum = array_rand ( $file, rand ( 0, $max ) );
			
			if ($streetNum == NULL) {
				$streetNum = "1";
			}
			@$distName .= $streetPref [rand ( 0, count ( $streetPref ) - 1 )] . " ";
			if (is_array ( $streetNum )) {
				foreach ( $streetNum as $val ) {
					@$distName .= $file [$val] . ", ";
				}
			} else {
				@$distName .= $file [$streetNum] . ",  " . GetRandomValue ( $dist1, 73, 1 );
			}
		
		}
		
		//echo @$distName;
		//die();
		return $distName;
	
	}
	
	function GetValue($str) {
		$params = explode ( ",", $str );
		//print_r($params); 
		$result = $params [0] ( $params [1], $params [2] );
		$result = preg_replace ( "/\n/", "", $result );
		$result = preg_replace ( "/\r/", "", $result );
		return $result;
	
	}
	
	
	
	
	for($i = 0; $i < $argv [2]; $i ++) {
		
		$inputConfFile = $argv [1];
		$inputConf = file ( $inputConfFile );
		
		if (! is_array ( $inputConf )) {
			die ( "cannot read config" );
		}
		//global $inputConf;
		$formula = $inputConf [0];
		array_shift ( $inputConf );
		$formula = explode ( "|", $formula );
		foreach ( $formula as $num => $val ) {
			
			preg_match_all ( "!{(.*?)}!si", $val, $out );
			
			if (count ( $out [1] ) < 2) {
				$string [] .= GetValue ( $out [1] [0] ); //if we've a single position
			

			} else {
				$string [] .= "MARK";
				foreach ( $out [1] as $tval ) {
					$mArr [] .= GetValue ( $tval );
				
				}
			}
		
		}
		
		$trArr = "";
		shuffle ( $mArr );
		foreach ( $mArr as $vl ) {
			if (strlen ( $vl ) > 1) {
				$trArr .= $vl;
			}
		}
		
		if ($string [5] == "MARK") {
			$string [5] = $trArr;
		}
		
		//print_r($string); 

		foreach ( $string as $numb => $txt ) {
			//echo $txt . "\r\n";
			if ($numb == 1) {
				$prm = explode ( ",", $txt );
				$distr = trim ( $prm [0] );
				//$query1 = mysql_query("SELECT `id` FROM `districts` WHERE `name` = \"" . iconv("UTF-8", "cp1251", $distr). "\" LIMIT 1;");
				//$rid = mysql_result($query1, 0);
				$str2 =  trim ( $prm [1] ) ;
				$insert ['distr'] .= $distr;
				$insert ['street'] .= $str2;
			}
			if (strlen ( $txt ) > 0) {
				$mainstr .=  $txt ;

			}
		
		}
		$price = iconv ( "UTF-8", "cp1251", $string [6] );
		
		$db = new SQLite3 ( $pathDB );
		$id = rand ( 10, 100000 );
		$db->querySingle ( "INSERT INTO ads (
								    							id, 
								    							operation_type,
								    							street_id,
								    							price,						    							
								    							fulltext,
								    							title,
								    							rooms, 
								    							planning) 
								     VALUES ('$id', '1',  '1', '$price', '$mainstr', '1', '1', '1')" );
		

		unset ( $trArr, $string, $mArr, $trArr, $dataM1, $insert, $mainstr, $strout );
	}



?>
