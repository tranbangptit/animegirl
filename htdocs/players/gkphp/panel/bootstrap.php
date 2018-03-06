<?php
/**
 * 
 * This software is distributed under the GNU GPL v3.0 license.
 * @author Gemorroj
 * @copyright 2008-2012 http://wapinet.ru
 * @license http://www.gnu.org/licenses/gpl-3.0.txt
 * @link http://wapinet.ru/gmanager/
 * @version 0.8.1 beta
 * 
 * PHP version >= 5.2.3
 * 
 */
 
//Load info file
require_once('lib/info.php');
@session_start();
////////////////////////////
//head foot
header("content-type: text/html; charset: utf-8;");
	$head = '<!DOCTYPE html PUBLIC "-//WAPFORUM//DTD XHTML Mobile 1.0//EN" "http://www.wapforum.org/DTD/xhtml-mobile10.dtd">
	<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="vi">
	<head>
	<title>LPanel 2.1 - Quản lí file trên host - Gmanager 0.8.1 beta</title>
	<meta http-equiv="Content-Type" content="charset=UTF-8" />
	<link rel="stylesheet" type="text/css" href="vtr/style.css"/>
		<script type="text/javascript" src="js.js"></script></head><body>
		<div class="header" align="center"><a href="index.php"><h1>LPanel 2.1</h1></a></div>
		';
    $end = '<div class="header" align="center">
		<h2><a href="index.php?info">V2.1 - Tmc</a></h2>
		</div></body></html>
		';
//////////////////
if(isset($_COOKIE['admin'])){$cadmin = @$_COOKIE['admin'];}else{$cadmin = @$_SESSION['admin'];}
if(isset($_COOKIE['pass'])){$cpass = @$_COOKIE['pass'];}else{$cpass = @$_SESSION['pass'];}
if(isset($_GET['info'])){
echo $head;
echo '<div class="title">Tác giả</div>
<div class="list1">
- Gmanager 0.8.1 beta by Gemorroj<br/>
- SoperDumper vh by VuThanhLai.<br/>
- Mini PhpMyAdmin by ionutvm.<br/>
-> Mod, secure, fix ... 2.1 by <a href="http://fb.com/xtmc9x">Tmc</a><br/>
</div><div class="title">Giới thiệu</div>
<div class="list1">+ Lpanel là gói tổng hợp của các code khác chuyên dụng để làm wap và fix lại.<br/>
<ul>- Saoluu.php vh thêm một số phần, lưu cookie trong vòng 1 năm, ....</ul>
<ul>- Gmanager 0.8.1 beta mod giao diện giống wapftp, công cụ sửa vb từng trang, 
đăng nhập tiện lợi.</ul>
<ul>- Mini PhpMyAdmin quản lí CSDL hiệu quả, mod đăng nhập tự động, bảo mật hệ thống,
giao diện cho điện thoại vô cùng tiện lợi và nhẹ.</ul>
<ul>-> Lpanel tổng hợp lạ các code thành 1 gói, fix các lỗi của các code, Menu login 
giúp bảo mật và tăng tính bảo mật các code trên.</ul>
+ Phiên bản ổn định nhất: 2.1<br/>
+ Phiên bản cuối cùng 2.1 , ai gặp lỗi thì đừng pm nhé.</div>
<div class="title">Trở về</div>
<div class="list1"><img src="vtr/img/quay.png" alt=""/> <a href="index.php"> Quản lí</a></div>';
echo $end;exit;
}
if($setadmin !== $cadmin || $setpass !== $cpass){
if(isset($_POST['admin']) && isset($_POST['pass'])){
		if(!isset($_COOKIE['ban'])){
		setcookie("admin",$_POST['admin'],time()+24*365*3600);
		setcookie("pass",md5(md5($_POST['pass'])),time()+24*365*3600);
		$_SESSION['admin']=$_POST['admin'];$_SESSION['pass']=md5(md5($_POST['pass']));
		}
		if($setadmin !== $cadmin || $setpass !== $cpass){ setcookie("ban","1",time()+30);}
		header("location: index.php");
		exit;
}else{
	echo $head;
		echo '
	<div class="gmenu">Vui lòng đăng nhập trước!</div>
		<div class="list1">
		<img src="vtr/img/next.gif" alt=""/> <a href="index.php?info">Thông tin code</a> |
		<img src="vtr/img/Info.png" alt=""/> <a href="data/">FAQ</a>
		</div>
	<div class="list1">
		<form method="post" action="index.php">
		<img src="vtr/img/user.png" alt=""/> Tên đăng nhập:<br/>
		<input type="text" name="admin" value="'.$cadmin.'"/><br/>
		<img src="vtr/img/pass.png" alt=""/> Mật khẩu:<br/>
		<input type="password" name="pass" value=""/><br/>
		'.(!isset($_COOKIE['ban']) ? '<input type="submit"  value="Đăng nhập"/>':'Xin chờ <img src="vtr/timer.gif" alt="Thời gian"/> để thử lại! <meta http-equiv="refresh" content="31;index.php">').'</form>
		</div>
		<div class="list1">
		<font color="red"><b>Chú ý</b>:<br/>
		+ Mật khẩu được mã hoá trước khi lưu vào trình duyệt.<br/>
		+ Nếu không lưu được cookie sẽ lưu session
		</font></div>';
	echo $end; exit;
}
}
if(isset($_GET['exit'])){
	setcookie("pass",'',time()+24*365*3600);
	$_SESSION['pass']='';
	header("location: index.php");
	exit;
}
if(isset($_GET['set'])){
			echo $head;
$nten = @$_POST['nten'];
$npass = @$_POST['npass'];
if(strlen($nten)>2 && strlen($npass)>3){
$fp = fopen('./lib/info.php','w');
fwrite($fp,'<?php
//////////////////////////////////////////////////////
//			Gmanager mod by Tmc
//			File này lưu trử thông tin đăng nhập
//			Sửa cận thận
//			Mật khẩu phải mã hoá md5 2 lần trước khi viết vào
//////////////////////////////////////////////////////
//Tên đăng nhập
$setadmin = "'.$nten.'";
//Mật khẩu được mã hoá
$setpass = "'.md5(md5($npass)).'";
//số dòng chỉnh sửa
$setdong = "'.$_POST['dong'].'";
//Công cụ sửa văn bản
$setedit = "'.$_POST['edit'].'";
//Thời gian thực thi sửa văn bản
setcookie("edit",$setedit,time()+24*365*3600);
//Tmc - fb.com/xtmc9x
');
echo '<div class="gmenu">Cài đặt được lưu! Chờ 5s để cập nhật!</div><meta http-equiv="refresh" content="5;index.php">';
			echo $end; exit;
}
echo '<div class="title">Cài đặt</div>';
if(empty($_POST['npass']) && isset($_POST['ok'])){
echo '<div class="gmenu">Cần nhập mật khẩu! Nhập vào mật khẩu cũ nếu không muốn thay đổi mật khẩu!</div>';
}
echo '<div class="list1">
<b>Thay đổi thông tin đăng nhập</b><br/>
<form method="post" action="index.php?set"/>
Tên mới:<br/>
<input type="text" name="nten" value="'.$cadmin.'"/><br/>
Mật khẩu mới:<br/>
<input type="text" name="npass" value=""/><br/>
<b>Công cụ mặc định dùng để sửa văn bản:</b><br/>';
echo '<input type="radio" name="edit" '.($setedit=='d' ? 'checked="checked"':'').' value="d"/> Công cụ Gmanager mặc định.<br/>';
echo '<input type="radio" name="edit" '.($setedit=='t' ? 'checked="checked"':'').' value="t"/> Công cụ sửa từng trang Tmc.<br/>';
echo '<b>Tuỳ chọn:</b><br/>
Số dòng mỗi trang:<br/>
<input type="text" name="dong" value="50"/><br/>
<input type="submit" name="ok" value="Thay đổi"/></form>
Tên phải hơn 2 kí tự, mật khẩu phải trên 3 kí tự.</div>';
echo $end; exit;

}



//	@unlink('data/GmanagerTrace.log');

define('GMANAGER_START', microtime(true));
define('GMANAGER_PATH', dirname(__FILE__));

Config::setConfig('config.ini');

set_include_path(
    get_include_path() . PATH_SEPARATOR .
    GMANAGER_PATH . DIRECTORY_SEPARATOR . 'lib' . PATH_SEPARATOR .
    GMANAGER_PATH . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'PEAR'
);


/**
 * Autoloader
 *
 * @param string $class
 */
function __autoload ($class)
{
    require GMANAGER_PATH . '/lib/' . str_replace('_', '/', $class) . '.php';
}

?>
