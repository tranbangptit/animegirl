<?php
/*****
* Code by TrunksJj
* YH: imkingst - Email: duynghia95@gmail.com
*****/
ob_start();
session_start();
define('TRUNKSJJ',true);
include_once('../includes/configurations.php');
include('../includes/players.php');
include_once('../includes/Templates.php');
include_once('../includes/functions.php');
include_once('../includes/AllTemplates.php');
include_once("../includes/phpmailer.php"); 
include_once('../includes/facebook/facebook.php');
$appId = '547998618678011'; //Facebook App ID
$appSecret = 'd1b21ba7653df567b98561d9066f750c'; // Facebook App Secret
//Call Facebook API
$facebook = new Facebook(array(
  'appId'  => $appId,
  'secret' => $appSecret
));
$ZingDecode = new KZ_Crypt;

if(isset($_POST['NextEpisode'])){	
$episode_id	=	intval($_POST['EpisodeID']);	
	$film_sub = get_data('episode_urlsub','episode','episode_id',$episode_id);		
	$episode_url = get_data('episode_url','episode','episode_id',$episode_id);
	$filmID	=	intval($_POST['filmID']);	
        $mysql->update("film","film_viewed = film_viewed + 1,film_viewed_day = film_viewed_day + 1,film_viewed_w = film_viewed_w + 1,film_viewed_m = film_viewed_m + 1","film_id = '".$filmID."'");
$playTech	=	playTech_check($episode_url);	 
        
	$img = get_data('film_imgbn','film','film_id',$filmID);	 
	$server = get_data('episode_servertype','episode','episode_id',$episode_id);
	$ff = $mysql->query("SELECT episode_id,episode_url,episode_urlsub FROM ".$tb_prefix."episode WHERE episode_id > '".$episode_id."' AND episode_film = '".$filmID."' AND episode_servertype = '".$server."' ORDER BY episode_id ASC LIMIT 1");
	$film	=	$ff->fetch(PDO::FETCH_ASSOC);
	
	$epi_id = $film['episode_id'];		
	if(!($epi_id)){
	setcookie('autoNextEpisodeId', false, time() + (86400 * 30 * 12));  
	setcookie('watchedEpisodeId', false, time() + (86400 * 30 * 12));  
	}else{
	setcookie('autoNextEpisodeId', true, time() + (86400 * 30 * 12));  
	setcookie('watchedEpisodeId', $epi_id, time() + (86400 * 30 * 12));  
	}
	echo phimle_players($episode_url,$filmID,$episode_id,$server,$film_sub,$img,$playTech);	
	exit();
}elseif(isset($_POST['action'])) {

    $film_id = (int)$_POST['idBox']; 
    $rate = (int)($_POST['rate']); 
	$rate = $rate/10;
	$mysql->query("UPDATE ".$tb_prefix."film SET film_rate = film_rate + 1, film_rating_total = film_rating_total + $rate WHERE film_id = $film_id");
    $film_rate_t = get_data("film_rate","film","film_id",$film_id);
	echo $film_rate_t;
	exit();

}
elseif(isset($_POST['error'])) {

	$film_id	=	intval($_POST['film_id']);
	$episode_id	=	intval($_POST['episode_id']);
	$mysql->query("UPDATE ".$tb_prefix."episode SET episode_broken = 1 WHERE episode_id = $episode_id");
	$mysql->query("UPDATE ".$tb_prefix."film SET film_broken = 1 WHERE film_id = $film_id");
	echo 1;
	exit();
	
}elseif(isset($_POST['MoviesLoad'])){
    $page = (int)$_POST['page'];
    $rel = sql_escape($_POST['rel']);
    $name = sql_escape($_POST['name']);
	if($rel == 'update'){
	    $order_sql = "ORDER BY film_time_update DESC";
	}elseif($rel == 'new'){
	    $order_sql = "ORDER BY film_id DESC";
	}elseif($rel == 'popular'){
	    $order_sql = "ORDER BY film_viewed DESC";
	}elseif($rel == 'year'){
	    $order_sql = "ORDER BY film_year DESC";
	}elseif($rel == 'name'){
	    $order_sql = "ORDER BY film_name ASC";
	}else $order_sql = "ORDER BY film_time_update DESC";
	
	if(strpos($name , 'the-loai') !== false){
	    $ipid = explode('|',$name);
		$CatID = (int)$ipid[1];
		if(count($ipid) == 2){
		    $where_sql = "WHERE film_cat LIKE '%,".$CatID.",%'";
		}elseif(count($ipid) == 3){
		    $where_sql1 = "WHERE film_cat LIKE '%,".$CatID.",%'";
			$Key2 = $ipid[2];
			//Check $Key2
			if(is_numeric($Key2)){
			    $year = (int)$Key2;
			    $where_sql2 = " AND film_year = ".$year;
			}else{
			    $Key2 = sql_escape($Key2);
			    $CountryID = get_data('country_id','country','country_name_key',$Key2);
			    if($CountryID){
				    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
				}else $where_sql2 = "";
			}
			$where_sql = $where_sql1.$where_sql2;
		}elseif(count($ipid) == 4){
		    $where_sql1 = "WHERE film_cat LIKE '%,".$CatID.",%'";
			$Key2 = $ipid[2];
			$Key3 = $ipid[3];
			//Check $Key2
			if(is_numeric($Key2)){
			    $year = (int)$Key2;
			    $where_sql2 = " AND film_year = ".$year;
			}else{
			    $Key2 = sql_escape($Key2);
			    $CountryID = get_data('country_id','country','country_name_key',$Key2);
			    if($CountryID){
				    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
				}else $where_sql2 = "";
			}
			//Check $Key3
			if(is_numeric($Key3)){
			    $yearz = (int)$Key3;
			    $where_sql3 = " AND film_year = ".$yearz;
			}else{
			    $Key3 = sql_escape($Key3);
			    $CountryIDz = get_data('country_id','country','country_name_key',$Key3);
			    if($CountryIDz){
				    $where_sql3 = " AND film_country LIKE '%,".$CountryIDz.",%'";
				}else $where_sql3 = "";
			}
			$where_sql = $where_sql1.$where_sql2.$where_sql3;
		}
	}elseif(strpos($name , 'quoc-gia') !== false){
	    $ipid = explode('|',$name);
		$CountryID = (int)$ipid[1];
		if(count($ipid) == 2){
		    $where_sql = "WHERE film_country LIKE '%,".$CountryID.",%'";
		}elseif(count($ipid) == 3){
		    $where_sql1 = "WHERE film_country LIKE '%,".$CountryID.",%'";
			$Key2 = $ipid[2];
			//Check $Key2
			if(is_numeric($Key2)){
			    $year = (int)$Key2;
			    $where_sql2 = " AND film_year = ".$year;
			}else{
			    $Key2 = sql_escape($Key2);
			    $CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			    if($CatID){
				    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
				}else $where_sql2 = "";
			}
			$where_sql = $where_sql1.$where_sql2;
		}elseif(count($ipid) == 4){
		    $where_sql1 = "WHERE film_country LIKE '%,".$CountryID.",%'";
			$Key2 = $ipid[2];
			$Key3 = $ipid[3];
			//Check $Key2
			if(is_numeric($Key2)){
			    $year = (int)$Key2;
			    $where_sql2 = " AND film_year = ".$year;
			}else{
			    $Key2 = sql_escape($Key2);
			    $CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			    if($CatID){
				    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
				}else $where_sql2 = "";
			}
			//Check $Key3
			if(is_numeric($Key3)){
			    $year = (int)$Key3;
			    $where_sql3 = " AND film_year = ".$year;
			}else{
			    $Key3 = sql_escape($Key3);
			    $CatID = get_data('cat_id','cat','cat_name_key',$Key3);
			    if($CatID){
				    $where_sql3 = " AND film_cat LIKE '%,".$CatID.",%'";
				}else $where_sql3 = "";
			}
			$where_sql = $where_sql1.$where_sql2.$where_sql3;
		}
	}elseif(strpos($name , 'phim-') !== false){
	    $ipid = explode('|',$name);
		if(count($ipid) == 1){
		    $Key1 = sql_escape($ipid[0]);
			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
			if($Key1 == 'phim-le'){
			    $where_sql = "WHERE film_lb = 0";
			}elseif($Key1 == 'phim-bo'){
			    $where_sql = "WHERE film_lb IN (1,2)";
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql = "WHERE film_chieurap = 1";
			}elseif($Key1 == 'phim-hot'){
			    $where_sql = "WHERE film_hot = 1";
			}elseif($Key1 == 'phim-moi'){
			    $where_sql = "WHERE film_publish = 0";
			}elseif($Key1 == 'phim-18'){
			    $where_sql = "WHERE film_phim18 = 1";
			}elseif(is_numeric($Year)){
			    $where_sql = "WHERE film_year = ".$Year;
			}
		}elseif(count($ipid) == 2){
		    $Key1 = sql_escape($ipid[0]);
		    $Key2 = sql_escape($ipid[1]);
			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
			if($Key1 == 'phim-le'){
			    $where_sql1 = "WHERE film_lb = 0";
			}elseif($Key1 == 'phim-bo'){
			    $where_sql1 = "WHERE film_lb IN (1,2)";
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql1 = "WHERE film_chieurap = 1";
			}elseif($Key1 == 'phim-hot'){
			    $where_sql1 = "WHERE film_hot = 1";
			}elseif($Key1 == 'phim-moi'){
			    $where_sql1 = "WHERE film_publish = 0";
			}elseif($Key1 == 'phim-18'){
			    $where_sql1 = "WHERE film_phim18 = 1";
			}elseif(is_numeric($Year)){
			    $where_sql1 = "WHERE film_year = ".$Year;
			}
			// Check $Key2
			$CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			$CountryID = get_data('country_id','country','country_name_key',$Key2);
			if($CatID){
			    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
			}elseif($CountryID){
			    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
			}elseif(is_numeric($Key2)){
			    $where_sql2 = " AND film_year = ".$Key2;
			}
			$where_sql = $where_sql1.$where_sql2;
		}elseif(count($ipid) == 3){
		    $Key1 = sql_escape($ipid[0]);
		    $Key2 = sql_escape($ipid[1]);
		    $Key3 = sql_escape($ipid[2]);
			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
			if($Key1 == 'phim-le'){
			    $where_sql1 = "WHERE film_lb = 0";
			}elseif($Key1 == 'phim-bo'){
			    $where_sql1 = "WHERE film_lb IN (1,2)";
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql1 = "WHERE film_chieurap = 1";
			}elseif($Key1 == 'phim-hot'){
			    $where_sql1 = "WHERE film_hot = 1";
			}elseif($Key1 == 'phim-moi'){
			    $where_sql1 = "WHERE film_publish = 0";
			}elseif($Key1 == 'phim-18'){
			    $where_sql1 = "WHERE film_phim18 = 1";
			}elseif(is_numeric($Year)){
			    $where_sql1 = "WHERE film_year = ".$Year;
			}
			// Check $Key2
			$CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			$CountryID = get_data('country_id','country','country_name_key',$Key2);
			if($CatID){
			    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
			}elseif($CountryID){
			    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
			}elseif(is_numeric($Key2)){
			    $where_sql2 = " AND film_year = ".$Key2;
			}
			// Check $Key3
			$CatID = get_data('cat_id','cat','cat_name_key',$Key3);
			$CountryID = get_data('country_id','country','country_name_key',$Key3);
			if($CatID){
			    $where_sql3 = " AND film_cat LIKE '%,".$CatID.",%'";
			}elseif($CountryID){
			    $where_sql3 = " AND film_country LIKE '%,".$CountryID.",%'";
			}elseif(is_numeric($Key3)){
			    $where_sql3 = " AND film_year = ".$Key3;
			}
			$where_sql = $where_sql1.$where_sql2.$where_sql3;
		}elseif(count($ipid) == 4){
		    $Key1 = sql_escape($ipid[0]);
		    $Key2 = sql_escape($ipid[1]);
		    $Key3 = sql_escape($ipid[2]);
		    $Key4 = sql_escape($ipid[3]);
			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
			if($Key1 == 'phim-le'){
			    $where_sql1 = "WHERE film_lb = 0";
			}elseif($Key1 == 'phim-bo'){
			    $where_sql1 = "WHERE film_lb IN (1,2)";
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql1 = "WHERE film_chieurap = 1";
			}elseif($Key1 == 'phim-hot'){
			    $where_sql1 = "WHERE film_hot = 1";
			}elseif($Key1 == 'phim-moi'){
			    $where_sql1 = "WHERE film_publish = 0";
			}elseif($Key1 == 'phim-18'){
			    $where_sql1 = "WHERE film_phim18 = 1";
			}elseif(is_numeric($Year)){
			    $where_sql1 = "WHERE film_year = ".$Year;
			}
			// Check $Key2
			$CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			$CountryID = get_data('country_id','country','country_name_key',$Key2);
			if($CatID){
			    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
			}elseif($CountryID){
			    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
			}elseif(is_numeric($Key2)){
			    $where_sql2 = " AND film_year = ".$Key2;
			}
			// Check $Key3
			$CatID = get_data('cat_id','cat','cat_name_key',$Key3);
			$CountryID = get_data('country_id','country','country_name_key',$Key3);
			if($CatID){
			    $where_sql3 = " AND film_cat LIKE '%,".$CatID.",%'";
			}elseif($CountryID){
			    $where_sql3 = " AND film_country LIKE '%,".$CountryID.",%'";
			}elseif(is_numeric($Key3)){
			    $where_sql3 = " AND film_year = ".$Key3;
			}
			// Check $Key4
			$CatID = get_data('cat_id','cat','cat_name_key',$Key4);
			$CountryID = get_data('country_id','country','country_name_key',$Key4);
			if($CatID){
			    $where_sql4 = " AND film_cat LIKE '%,".$CatID.",%'";
			}elseif($CountryID){
			    $where_sql4 = " AND film_country LIKE '%,".$CountryID.",%'";
			}elseif(is_numeric($Key4)){
			    $where_sql4 = " AND film_year = ".$Key4;
			}
			$where_sql = $where_sql1.$where_sql2.$where_sql3.$where_sql4;
		}
	}else{
	
	}
	$page_size = PAGE_SIZE;
	if (!$page || $page == 0) $page = 1;
	$limit = ($page-1)*$page_size;
	$query = $mysql->query("SELECT film_id,film_name,film_name_real,film_img,film_viewed,film_tapphim,film_trangthai,film_lb,film_lang FROM ".DATABASE_FX."film $where_sql $order_sql LIMIT ".$limit.",".$page_size);
    $total = get_total("film","film_id","$where_sql $order_sql");
	$html = '';
	if($total){
	while($row = $query->fetch(PDO::FETCH_ASSOC)){
	$filmID = $row['film_id'];
$filmNAMEVN = $row['film_name'];
$filmNAMEEN = $row['film_name_real'];
$filmIMG = thumbimg($row['film_img'],200);
$filmURL = $web_link.'/phim/'.replace(strtolower($filmNAMEVN)).'-'.replace($filmID).'/';
$filmQUALITY = $row['film_tapphim'];
$filmSTATUS = str_replace('Hoàn tất','Full',$row['film_trangthai']);
	$filmVIEWED = number_format($row['film_viewed']);
	$filmLANG = film_lang($row['film_lang']);
if($row['film_lb'] == 0){
	    $Status = $filmQUALITY.'-'.$filmLANG;
	}else{
	    $Status = $filmSTATUS.'-'.$filmLANG;
	}
	    $html .= '<div class="listp"><div class="items">
<a class="movie-item m-block" href="'.$filmURL.'" title="'.$filmNAMEVN.' - '.$filmNAMEEN.'">
<div class="block-wrapper"><div class="movie-thumbnail ratio-box ratio-3_4">
<div class="public-film-item-thumb ratio-content"><span class="lable-phim"><span class="hd">'.$Status.'</span></span><img src="'.$filmIMG.'" alt="'.$filmNAMEVN.' - '.$filmNAMEEN.'"></div></div><div class="movie-meta"><div class="movie-title-1">'.$filmNAMEVN.'</div><span class="movie-title-2">'.$filmNAMEEN.'</span><span class="movie-title-chap">Lượt xem: '.$filmVIEWED.'</span></div></div></a>
</div></div>';
	}
	}else $html = 'Rất tiếc chưa có phim này. <a href="#" target="_blank"><b>BẤM VÀO ĐÂY</b></a> để yêu cầu. PhimLẻ[Tv] sẽ đáp ứng ngay!';
	echo $html;
	exit();
}elseif(isset($_POST['MoviesPage'])){
    $page = (int)$_POST['page'];
    $total = (int)$_POST['total'];
    $rel = sql_escape($_POST['rel']);
	$ext = sql_escape($_POST['ext']);
	echo view_pages('film',$total,PAGE_SIZE,$page,$ext,$rel);
	exit();
}elseif(isset($_POST['Request'])){
    $email = sql_escape($_POST['email']);
    $info = sql_escape($_POST['info']);
    $captcha = ($_POST['captcha']);
	if($captcha != $_SESSION['captcha']) {
			echo	"Mã xác nhận không đúng!";
			exit();
	}
	if(validateEmail($email) == 0){
	    echo 'Vui lòng nhập đúng định dạng email abc@domain.com!';
		exit();
	}
	$mysql->query("INSERT INTO ".DATABASE_FX."request (request_content,request_time,request_email,request_title) VALUES ('".($info)."','".NOW."','".$email."','Yêu cầu')");
	echo 1;
	exit();
}elseif(isset($_POST['Login'])){
    $username 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['username'])))));
	$password 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['password'])))));
    if(empty($username) || empty($password)) {
		echo 'Vui lòng nhập Tên tài khoản và mật khẩu đăng nhập!';
		exit();
	}else{
	// Check User in Db
	    $password = md5($password);
	    $check_user = $mysql->query("SELECT user_id,user_password,user_name,user_level FROM ".DATABASE_FX."user WHERE user_name = '".$username."' ORDER BY user_id ASC");
	    $user = $check_user->fetch(PDO::FETCH_ASSOC);
		if(!$user['user_id']){
		    echo 'Tài khoản đăng nhập không tồn tại!';
			exit();
		}elseif($user['user_id'] && $user['user_password'] != $password){
		    echo 'Mật khẩu đăng nhập không chính xác!';
			exit();
		}else{
		    $id = $user['user_id'];
			$userlevel = $user['user_level'];
		    $_SESSION["user_id"] = $id;
            $_SESSION["user_name"] = $username;
            $_SESSION["user_group"] = $userlevel;
		    setcookie('user_id', $id, time() + (86400 * 30 * 12), "/");
			$mysql->query("UPDATE ".DATABASE_FX."user SET user_time_last = ".NOW);
			echo 1;
			exit();
		}
	}
}elseif(isset($_POST['Logout'])) {
    if(isset($_SESSION["user_id"]) || $_COOKIE['user_id']){
	unset($_SESSION["user_id"]);
	unset($_SESSION["user_name"]);
	unset($_SESSION["user_group"]);
        $facebook->destroySession();
        setcookie('user_id', false, time() + (86400 * 30 * 12), "/");
	$phpFastCache->delete('phimletv-aside');
	echo 0;
	}else{ echo 1;}

	exit();

}elseif(isset($_POST['Register'])) {
		$username		=	htmlchars(stripslashes(trim(urldecode(injection($_POST['username'])))));
		$password		=	htmlchars(stripslashes(trim(urldecode(injection($_POST['password'])))));
		$repassword		=	htmlchars(stripslashes(trim(urldecode(injection($_POST['repassword'])))));
		$email			=	htmlchars(stripslashes(trim(urldecode(injection($_POST['email'])))));
		
		$check_user 			= get_data('user_id','user','user_name',$username);
        $check_email 			= get_data('user_id','user','user_email',$email);
		// kiểm tra
		if($_POST['captcha'] != $_SESSION['captcha']) {
			echo	"Mã xác nhận không đúng!";
			exit();
		}
		elseif($check_user) {
			echo	"Tên tài khoản đã có người sử dụng!";
			exit();
		}
		elseif($check_email) {
			echo	"Email đã được sử dụng!";
			exit();
		}elseif($password != $repassword){
		    echo	"Mật khẩu xác nhận không chính xác!";
			exit();
		}elseif(validateEmail($email) == 0){
		    echo	"Địa chỉ Email không chính xác!";
			exit();
		}
		else {


			$password	=	md5($password);
			$regdate	=	NOW;
			
            $mysql->query("INSERT INTO ".DATABASE_FX."user (user_name,user_password,user_email,user_time) VALUES ('".$username."','".$password."','".$email."','".NOW."')");
		
			echo 1;
			exit();
		}

	
}elseif(isset($_POST['filmLike'])) {
    $film_id	=	intval($_POST['filmID']);
    $mysql->query("UPDATE ".DATABASE_FX."film SET film_liked = film_liked + 1 WHERE film_id = '".$film_id."'");
    if(isset($_SESSION["user_id"])){
	$userid = $_SESSION['user_id'];
	
    $column = 'user_filmbox';
	$phimcheck = ','.$film_id.',';
	$check_f = $mysql->query("SELECT user_id,".$column." FROM ".DATABASE_FX."user WHERE ".$column." LIKE '%".$phimcheck."%' AND user_id = '".$userid."' ORDER BY user_id ASC");
	$fbox = $check_f->fetch(PDO::FETCH_ASSOC);
	$phimadd = $film_id.",";	
    if($fbox['user_id']){ 
	    $add_get = $mysql->query("SELECT user_id,".$column." FROM ".DATABASE_FX."user WHERE user_id = '".$userid."' ORDER BY user_id ASC");
		$rs = $add_get->fetch(PDO::FETCH_ASSOC);
	    $addphim = str_replace($phimadd,"",$rs[$column]);
		$mysql->query("UPDATE ".DATABASE_FX."user SET ".$column." = '".$addphim."' WHERE user_id = '".$userid."'");
	    echo 2;
	}else{
	    $add_get = $mysql->query("SELECT user_id,".$column." FROM ".DATABASE_FX."user WHERE user_id = '".$userid."' ORDER BY user_id ASC");
		$rs = $add_get->fetch(PDO::FETCH_ASSOC);
	    $addphim = $rs[$column].''.$phimadd;
		$mysql->query("UPDATE ".DATABASE_FX."user SET ".$column." = '".$addphim."' WHERE user_id = '".$userid."'");
	       echo 3;
	}	  
	      
	}else{
	echo 1;
	}
	
	exit();
}elseif(isset($_POST['filmBox'])) {
    $film_id	=	intval($_POST['filmID']);
    $mysql->query("UPDATE ".DATABASE_FX."film SET film_liked = film_liked + 1 WHERE film_id = '".$film_id."'");
    if(isset($_SESSION["user_id"])){
	$userid = $_SESSION['user_id'];
	
    $column = 'user_filmbox';
	$phimcheck = ','.$film_id.',';
	$check_f = $mysql->query("SELECT user_id,".$column." FROM ".DATABASE_FX."user WHERE ".$column." LIKE '%".$phimcheck."%' AND user_id = '".$userid."' ORDER BY user_id ASC");
	$fbox = $check_f->fetch(PDO::FETCH_ASSOC);
	$phimadd = $film_id.",";	
    if($fbox['user_id']){ 
	    echo 2;
	}else{
	    $add_get = $mysql->query("SELECT user_id,".$column." FROM ".DATABASE_FX."user WHERE user_id = '".$userid."' ORDER BY user_id ASC");
		$rs = $add_get->fetch(PDO::FETCH_ASSOC);
	    $addphim = $rs[$column].''.$phimadd;
		$mysql->query("UPDATE ".DATABASE_FX."user SET ".$column." = '".$addphim."' WHERE user_id = '".$userid."'");
	       echo 3;
	}	  
	      
	}else{
	echo 1;
	}
	
	exit();
}elseif(isset($_POST['filmBoxDel'])) {
    $film_id	=	intval($_POST['filmID']);
    if(isset($_SESSION["user_id"])){
	$userid = $_SESSION['user_id'];
	
    $column = 'user_filmbox';
	$phimcheck = ','.$film_id.',';
	$check_f = $mysql->query("SELECT user_id,".$column." FROM ".DATABASE_FX."user WHERE ".$column." LIKE '%".$phimcheck."%' AND user_id = '".$userid."' ORDER BY user_id ASC");
	$fbox = $check_f->fetch(PDO::FETCH_ASSOC);
	$phimadd = $film_id.",";	
    if(!$fbox['user_id']){ 
	    echo 2;
	}else{
// ,123,123,123,
	    $delphim = $fbox[$column];
		$frep = str_replace(','.$film_id,'',$delphim);
		$mysql->query("UPDATE ".DATABASE_FX."user SET ".$column." = '".$frep."' WHERE user_id = '".$userid."'");
	       echo 3;
	}	  
	      
	}else{
	echo 1;
	}
	
	exit();
}elseif(isset($_POST['setLang'])){
    $lang = strip_tags(sql_escape($_POST['Lang']));
	$phpFastCache->clean();
	setcookie('lang', $lang, time() + (86400 * 30 * 12), "/");
	exit();
}elseif(isset($_POST['reqSend']) && $_POST['reqSend'] == 1){
    if(floodpost()) {
		echo 2;
		exit();
	}else{
	    $title = sql_escape($_POST['title']);
	    if(strpos(strtolower($title) , 'admin') !== false){
		    echo 1;
			exit();
		}else{
            $txt = ($_POST['txt']);
            $Idq = (int)$_POST['id'];
            $curURL = strip_tags(sql_escape($_POST['url']));
            $mysql->query("INSERT INTO ".$tb_prefix."request (request_content,request_time,request_title,request_ip,request_type,request_url,request_slug) VALUES ('".strip_tags(sql_escape($txt))."','".NOW."','".strip_tags($title)."','".IP."','".$Idq."','".$curURL."','".replace(strtolower(strip_tags($title)))."')");
            
		    setcookie('RequestUserName', $title, time() + (86400 * 30 * 12)); 
                $content = strip_tags($title).' request: '.strip_tags(sql_escape($txt)).'. IP: '.IP.', Address: '.$curURL.', Time: '.date("l jS \of F Y h:i:s A");
                $contentHtml = strip_tags($title).' request:<br /> '.strip_tags(sql_escape($txt)).'.<br /> IP: '.IP.'<br /> Address: '.$curURL.'<br /> Time: '.date("l jS \of F Y h:i:s A");
                
$_SESSION['prev_message_post'] = NOW;
	        echo ShowRequestList("WHERE request_type = 0","ORDER BY request_time",10);
//plsendmail($content,$contentHtml); 
			exit();
		}
	}
    exit();
}
?>