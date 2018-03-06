<?php
/*****
* Code by TrunksJj
* YH: imkingst - Email: duynghia95@gmail.com
*****/
//============CONFIG DATABASE =====================\\
    $config['db_host']	= 'localhost';
    $config['db_name'] 	= 'backup';
    $config['db_user']	= 'root';
    $config['db_pass']	= 'root';
    $tb_prefix			= 'table_';
    define('SERVER_HOST',			$config['db_host']);
    define('DATABASE_NAME',			$config['db_name']);
    define('DATABASE_USER',			$config['db_user']);
    define('DATABASE_PASS',			$config['db_pass']);
    define('DATABASE_FX',			'table_');
    define('EMAIL_ACC',			        'phimletv20152@gmail.com');
    define('EMAIL_PASS',			'duynghia');
    define('EMAIL_REPLY',			'admin@phimle.tv');
    define('EMAIL_NAME',			'noreply - PhimLe.Tv');
    define('GOOGLE_API',			'AIzaSyC3rzaOnqukcovzuScuy3akVto1FPMs6PA');

	define('NOW',time());
	define('CLIENT_ID', '055dd4636d8666a');
	define('CACHED_TIME', '86400');
	define('WEB_URL',			'http://localhost');
        define('STATIC_URL',			'http://localhost/statics');
    define('UPLOAD_DIR',		        $_SERVER["DOCUMENT_ROOT"] .'/data');
    define('UPLOAD_FOLDER',		        'data');
	if(isset($_SERVER['HTTP_CF_CONNECTING_IP'])){
        $_SERVER['REMOTE_ADDR'] = $_SERVER['HTTP_CF_CONNECTING_IP'];
    }
    define('IP',$_SERVER['REMOTE_ADDR']);
    define('USER_AGENT',$_SERVER['HTTP_USER_AGENT']);
    define('URL_LOAD',$_SERVER["REQUEST_URI"]);
    define('PAGE_SIZE',24);
    define('STARLABEL','Quá dở|Dở|Bình thường|Hay|Quá hay');
	include('init.php');
?>
