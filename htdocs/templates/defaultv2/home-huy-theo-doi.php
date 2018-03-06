<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
if($value[1]=='home-huy-theo-doi' && is_numeric($value[2])){
    $filmID = (int)$value[2];
	$filmNAMEVN = get_data("film_name","film","film_id",$filmID);
	$filmURL = WEB_URL.'/phim/'.replace(strtolower($filmNAMEVN)).'-'.replace($filmID).'/';
    if(strpos(URL_LOAD , '?secrethash=') !== false){
	    $hash = explode("?secrethash=",URL_LOAD);
	    $hash =	sql_escape(trim($hash[1]));
		if(checkNotif($filmID)){
    	    $mysql->query("DELETE FROM ".$tb_prefix."notif WHERE notif_secrethash = '".$hash."'");
	    }
	} 
	header('Location: '.$filmURL); 
}else header('Location: '.$web_link.'/404'); 
?>