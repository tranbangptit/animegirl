<?php
define('TRUNKSJJ', true);
ob_start();
session_start();
@include("../includes/configurations.php");
include("../includes/functions.php");
if (isset($_POST["submit"])) {
	setcookie("cech_cache", "1", "0");
	$name = trim(htmlchars(stripslashes(urldecode(injection($_POST['email'])))));
	$name = str_replace( '|', '&#124;', $name);
	//$password = md5(trim(htmlchars(stripslashes(urldecode(injection($_POST['password']))))));
	$password = "123123";		
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_email = '".$name."' AND user_password = '".$password."' AND (user_level = 2 OR user_level = 3)");
	$r = $q->fetch(PDO::FETCH_ASSOC);
	if ($r['user_id']) {
		$_SESSION['admin_id'] = $r['user_id'];
		$_SESSION['user_id'] = $r['user_id'];
		setcookie('user_id', $r['user_id'], time() + (86400 * 30 * 12), "/");
		$_SESSION['admin_level'] = $r['user_level'];
		header("Location: ./");
		}else {
		header("Location: ./index.php?error=u");
		}
}
?>
