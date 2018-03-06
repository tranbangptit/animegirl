<?php
/*****
* Code by TrunksJj
* YH: imkingst - Email: duynghia95@gmail.com
*****/
ob_start();
session_start();
define('TRUNKSJJ',true);
include_once('../includes/configurations.php');
if(isset($_POST['nextVideo']) && $_POST['nextVideo'] == 1){	
    $vId	=	intval($_POST['vid']);	
    $vCat	=	intval($_POST['vcat']);	
	$v = $mysql->query("SELECT video_id,video_key FROM ".$tb_prefix."video WHERE video_id <> '".$vId."' AND video_cat LIKE '%,".$vCat.",%' ORDER BY RAND() ASC LIMIT 1");
	$video	=	$v->fetch(PDO::FETCH_ASSOC);
    $videoURL = $web_link.'/xem-video/'.$video['video_key'].'-'.$video['video_id'].'.html';
	echo $videoURL;	
	exit();
}
?>