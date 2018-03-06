<?php
/*****
* Code by TrunksJj
* YH: imkingst - Email: duynghia95@gmail.com
*****/
ob_start();
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set('memory_limit', '512M'); // increase this if you catch "Allowed memory size..."
define('TRUNKSJJ',true);
include_once('includes/configurations.php');
include('includes/players.php');
include_once('includes/LanguageHelper.php');
$object = new LanguageHelper();
$lang = $object->checkLang();
include_once($lang);
include_once('includes/Templates.php');
include_once('includes/functions.php');
include_once('includes/AllTemplates.php');
include_once("includes/phpmailer.php"); 
$ZingDecode = new KZ_Crypt;
resetTop();
if(isset($_GET['models'])){
$Gmodels	=	$_GET['models'];
$value = array();
$value	=	explode("/",$Gmodels);
$models =   $value[1];
}else $models = 'home';
$temp = new Temp();
$CurrentSkin = $temp->template();
$filename = "templates/".$CurrentSkin."/".$models.".php";
if (file_exists($filename)) {
    include_once($filename);
} else {
	header('Location: '.$web_link.'/404');
}

?>