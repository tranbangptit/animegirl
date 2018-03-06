<?php 
if (!defined('TRUNKSJJ')) die("Hack!");
@session_start();
@header("Content-Type: text/html; charset=UTF-8");
if (!ini_get('register_globals')) {
	extract($_GET);
	extract($_POST);
}
include('dbconnect.php');
include("curl.class.php");
$TrunksSQL = new TrunksSQL;
$mysqldb = $TrunksSQL->connect($config['db_host'],$config['db_user'],$config['db_pass'],$config['db_name']);
$mysql = new mysql;
$CURL = new CURL;
#######################################
# GET DATABASE
#######################################
function get_data($f1,$table,$f2,$f2_value){
	global $mysql;
	$q = $mysql->query("SELECT $f1 FROM ".DATABASE_FX.$table." WHERE $f2 = '".$f2_value."'");
	$row = $q->fetch(PDO::FETCH_ASSOC);
	$f1_value = $row[$f1];
	return $f1_value;
}
function get_data_multi($f1,$table,$f2){
	global $mysql;
	$q = $mysql->query("SELECT $f1 FROM ".DATABASE_FX.$table." WHERE $f2");
	$row = $q->fetch(PDO::FETCH_ASSOC);
	$f1_value = $row[$f1];
	return $f1_value;
}
function get_total($table,$f1,$f2 = '') {
   global $mysqldb;
   $sql = "SELECT count(*) FROM ".DATABASE_FX.$table." ".$f2.""; 
   $result = $mysqldb->prepare($sql); 
   $result->execute(); 
   $number_of_rows = $result->fetchColumn(); 
return $number_of_rows;
}
function user_online(){
    global $mysql;
	$active_sessions = 0;
    $minutes = 5; # period considered active
    if($sid = session_id()){ # if there is an active session
    $ip = IP; # Get Users IP address
    # Delete users from the table if time is greater than $minutes
	$mysql->query("DELETE FROM ".DATABASE_FX."active_sessions WHERE `date` < DATE_SUB(NOW(),INTERVAL $minutes MINUTE)");
    # Check to see if the current ip is in the table
    $row = get_data("date","active_sessions","ip",$ip);
    # If the ip isn't in the table add it.
    if(!$row){ 
	$mysql->query("INSERT INTO `active_sessions` (`ip`, `session`, `date`) VALUES ('$ip', '$sid', NOW()) ON DUPLICATE KEY UPDATE `date` = NOW()");
    }
    # Get all the session in the table
    # Add up all the rows returned
    $active_sessions = get_total('active_sessions','ip',"ORDER BY ip");
    }
    return $active_sessions;
}
#######################################
# GET CONFIG
#######################################
$q = $mysql->query("SELECT * FROM ".$tb_prefix."config WHERE cf_id = 1");
$cf = $q->fetch(PDO::FETCH_ASSOC);
$web_title 		= 	$cf['cf_web_name'];
$web_link 		= 	$cf['cf_web_link'];
$web_protect 	= 	$cf['cf_protect'];
if ($web_link[strlen($web_link)-1] == '/') $web_link = substr($web_link,0,-1);
$web_keywords 	= 	$cf['cf_web_keywords'];
$web_keyle 		= 	$cf['cf_web_keyle'];
$web_desle 		= 	$cf['cf_web_desle'];
$web_keybo 		= 	$cf['cf_web_keybo'];
$web_desbo 		= 	$cf['cf_web_desbo'];
$web_email 		= 	$cf['cf_web_email'];
$web_cache_key 		= 	$cf['cf_web_cache'];
$web_server 		= 	$cf['cf_server_post'];
$per_page 		= 	$cf['cf_per_page'];
$per_pagez 		= 	$cf['cf_sitemap_p'];
$cf_tags 		= 	$cf['cf_tags'];
$cf_fanpageid		= 	$cf['cf_fanpage_fbid'];
$cf_admin_id		= 	$cf['cf_fanpage_adid'];
$cf_textlink 		= 	$cf['cf_textlink'];
$announcement 		= 	$cf['cf_announcement'];
?>