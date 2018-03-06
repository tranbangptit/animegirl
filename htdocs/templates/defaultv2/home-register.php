<?php 
if($value[1]=='home-register'){
	$isLogin = checkLogin();
	if(!$isLogin){     
		$web_keywords = 'xem phim Phim Lẻ full hd, phim Phim Lẻ online, phim Phim Lẻ vietsub, phim Phim Lẻ thuyet minh, phim  long tieng, phim Phim Lẻ tap cuoi';
	    $web_des = 'Phim Lẻ hay tuyển tập, Phim Lẻ mới nhất, tổng hợp phim Lẻ, Phim Lẻ full HD, Phim Lẻ vietsub, xem Phim Lẻ online';
	    $web_title = ''.$language['signup'].' | Phim Lẻ hay | Tuyển tập Phim Lẻ mới nhất 2015';
		$breadcrumbs = '';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="'.$web_link.'/account/register" title="'.$language['signup'].'">'.$language['signup'].'</a></li>';
	    $h1title = '<i class="icon-note font-purple-seance"></i> '.$language['signup'].'';
		if(isset($_POST['submit'])){
	    $username		=	htmlchars(stripslashes(trim(urldecode(injection($_POST['username'])))));
		$password		=	htmlchars(stripslashes(trim(urldecode(injection($_POST['password'])))));
		$repassword		=	htmlchars(stripslashes(trim(urldecode(injection($_POST['repassword'])))));
		$email			=	htmlchars(stripslashes(trim(urldecode(injection($_POST['email'])))));
		$check_user 			= get_data('user_id','user','user_name',$username);
        $check_email 			= get_data('user_id','user','user_email',$email);
		if($_POST['captcha'] != $_SESSION['captcha']) {
			$error = '* '.$language['wrong_verification'];
			$display = "display:block;";
		}
		elseif($check_user) {
			$error = '* '.$language['wrong_user'];
			$display = "display:block;";
		}
		elseif($check_email) {
			$error = '* '.$language['wrong_email'];
			$display = "display:block;";
		}elseif($password != $repassword){
			$error = '* '.$language['wrong_pass'];
			$display = "display:block;";
		}elseif(validateEmail($email) == 0){
			$error = '* '.$language['wrong_email1'];
			$display = "display:block;";
		}elseif(strlen($username) < 6){
		    $error = '* '.$language['wrong_user1'];
			$display = "display:block;";
		}elseif(strlen($password) < 6){
		    $error = '* '.$language['wrong_pass1'];
			$display = "display:block;";
		}else {
			$password	=	md5($password);
			$regdate	=	NOW;
            $mysql->query("INSERT INTO ".DATABASE_FX."user (user_name,user_password,user_email,user_time) VALUES ('".$username."','".$password."','".$email."','".NOW."')");
		
			header("Location: ".$web_link."/account/login");
		}
	    $error = '<div> <div role="alert" class="alert alert-warning alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button> '.$error.' </div> </div> ';
	}else{
	    $error = '';
	    $display = "display:none;";
	}
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="vi" />
<title><?=$web_title;?></title>
<meta name="description" content="<?=$web_des;?>"/>
<meta name="keywords" content="<?=$web_keywords;?>"/>
<meta property="og:site_name" content="<?=$web_title;?>"/>
<? require_once("styles.php");?>
</head>

<body>
    <? require_once("header.php");?>
    <div id="body-wrapper">
        <div class="content-wrapper">
            <div class="container">
                <div class="row">
                    <div class="main col-lg-8 col-md-8 col-sm-7">
                        <div class="block">
                            <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
                            <div class="block-body">
                                <form class="form form-horizontal" method="post">
								<div class="message" style="<?=$display;?>"> 
								
								<?=$error;?> 
								
								</div>
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="account">Tài khoản</label>
                                        <div class="col-sm-9">
                                            <input class="form-control " type="text" name="username" id="account" placeholder="Tài khoản" value=""> </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="password">Mật khẩu</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="password" name="password" id="password" placeholder="Mật khẩu"> </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="repassword">Nhập lại mật khẩu</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="password" name="repassword" id="repassword" placeholder="Nhập lại mật khẩu"> </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="email">Email</label>
                                        <div class="col-sm-9">
                                            <input class="form-control" type="text" name="email" id="email" placeholder="Địa chỉ email" value=""> </div>
                                    </div>
                                   
                                   
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="captcha">Mã xác nhận</label>
                                        <div class="col-sm-3">
                                            <input class="form-control" type="text" name="captcha" id="captcha"> </div>
                                        <div class="col-sm-4"> <img id="captchaimg" src="<?=$web_link;?>/captcha/rand/<?=rand(1000,9999);?>" class="captcha" alt="Catcha" height="45" /> <a class="fa fa-refresh" href="javascript: refreshCaptcha();"></a></div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <div class="control-label col-sm-3"></div>
                                        <div class="col-sm-9">
                                            <div class="checkbox">
                                                <input id="tos" type="checkbox" value="1" name="tos" checked>
                                                <label for="tos"> Tôi đã xem và đồng ý với các quy định của website </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-7">
                                            <button type="submit" name="submit" class="btn mbtn btn-default"><span>Đăng ký</span></button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="sidebar col-lg-4 col-md-4 col-sm-5"></div>
                </div>
            </div>
        </div>
    </div>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pdnghia.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
    <? require_once("footer.php");?>
</body>

</html>
<? }else header('Location: '.$web_link.'/account/info'); }else header('Location: '.$web_link.'/404'); ?>