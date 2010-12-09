<?php
include './classes/makeStatic.class.php';
$template = file_get_contents('./template/index.html');

preg_match_all("!{(.*?)}!si", $template, $matches);


foreach($matches[1] as $match){
    $match = explode(":", $match); 
    $params = explode(",", $match[1]);
        foreach($params as $param){

            if(strpos($param, '|')){ 
               $param = preg_replace("!\(|\)!si", "", $param);
                $param = explode("|", $param);
                    foreach ($param as $p){
                        $p[] = $p;
                    }
            } else {
                
            }



        }
}

?>
