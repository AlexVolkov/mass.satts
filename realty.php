<?php
$pathDB = './db/realty.sqlite';
/*
 * 
 CREATE TABLE `names` (
`id` INT( 2 ) NULL AUTO_INCREMENT PRIMARY KEY ,
`name` TEXT CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL ,
`order` INT( 2 ) NULL,
`gender` INT( 2 ) NULL
) ENGINE = MYISAM ;# MySQL вернула пустой результат (т.е. ноль строк).

CREATE TABLE `phones` (
`id` INT( 2 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
`phone` VARCHAR( 255 ) NOT NULL
) ENGINE = MYISAM ;# MySQL вернула пустой результат (т.е. ноль строк).

CREATE TABLE districts (
    			id INTEGER(2) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    			district_name TEXT, 
    			district_synonim TEXT) ENGINE=MyISAM DEFAULT CHARSET=utf8;# MySQL вернула пустой результат (т.е. ноль строк).

CREATE TABLE streets (
    			id INTEGER(2) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    			street_name TEXT, 
    			district_id TEXT) ENGINE=MyISAM DEFAULT CHARSET=utf8;# MySQL вернула пустой результат (т.е. ноль строк).

CREATE TABLE ads (
    			id INTEGER(2) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    			operation_type TEXT, 
    			street_id INTEGER(2),
    			price VARCHAR(255),
    			fullad TEXT,
    			title TEXT,
    			rooms INTEGER(2),
    			planning TEXT) ENGINE=MyISAM DEFAULT CHARSET=utf8;# MySQL вернула пустой результат (т.е. ноль строк).
*
*/
function createDB($path) {
	if ($db = new SQLite3 ( $path )) {
		$db->querySingle ( "CREATE TABLE districts (
    			id INTEGER(2) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    			district_name TEXT, 
    			district_synonim TEXT) ENGINE=MyISAM DEFAULT CHARSET=utf8" );
		$db->querySingle ( "CREATE TABLE streets (
    			id INTEGER(2) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    			street_name TEXT, 
    			district_id TEXT) ENGINE=MyISAM DEFAULT CHARSET=utf8" );
		$db->querySingle ( "CREATE TABLE ads (
    			id INTEGER(2) PRIMARY KEY NOT NULL AUTO_INCREMENT, 
    			operation_type TEXT, 
    			street_id INTEGER(2),
    			price VARCHAR(255),
    			fullad TEXT,
    			title TEXT,
    			rooms INTEGER(2),
    			planning TEXT) ENGINE=MyISAM DEFAULT CHARSET=utf8" );
		$db->querySingle ( "CREATE TABLE lemms (
    			text TEXT, 
    			taxonomy TEXT)" );
		$db->querySingle ( "CREATE TABLE lemms_taxonomy (
    			id INTEGER(2) PRIMARY KEY NOT NULL UNIQUE, 
    			taxonomy_name TEXT, 
    			operation_type TEXT)" );
		$db->close ();
	} else {
		die ( $db->lastErrorMsg () );
	}
}

function importDS($pathFile, $pathDB) {
	if ($db = new SQlite3 ( $pathDB )) {
		$file = file_get_contents ( $pathFile );
		$file = explode ( '[SECT]', $file );
		
		foreach ( $file as $pos => $string ) :
			$string = explode ( "\n", $string );
			$check = 0;
			foreach ( $string as $line ) :
				if ((strlen ( $line ) > 1) && ($check == 0)) :
					$check = 1;
					$id = rand ( 10000, 100000 );
					$dName = explode ( ',', $line );
					$fName = $line;
					$db->querySingle ( "INSERT INTO districts (
								    							id, 
								    							district_name, 
								    							district_synonim) 
								     VALUES ($id, '$dName[0]',  '$fName')" );
				 elseif (strlen ( $line ) > 1) :
					$sid = rand ( 10000, 100000 );
					$db->querySingle ( "INSERT INTO streets (
								    							id, 
								    							street_name, 
								    							district_id) 
								     VALUES ($sid, '$line',  '$id')" );
				endif;
			endforeach
			;
		endforeach
		;
	} else {
		die ( $db->lastErrorMsg () );
	}
}


//if file not exist
//createDB ( $pathDB );
//importDS ( './import/rzn_streets.txt', $pathDB );


?>

