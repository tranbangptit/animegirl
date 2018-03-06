<?php 
session_start();
if($value[1]=='home-password'){
    $isLogin = checkLogin();
	if($isLogin){
		$web_keywords = 'xem phim Phim Lẻ full hd, phim Phim Lẻ online, phim Phim Lẻ vietsub, phim Phim Lẻ thuyet minh, phim  long tieng, phim Phim Lẻ tap cuoi';
	    $web_des = 'Phim Lẻ hay tuyển tập, Phim Lẻ mới nhất, tổng hợp phim Lẻ, Phim Lẻ full HD, Phim Lẻ vietsub, xem Phim Lẻ online';
	    $web_title = 'Đổi mật khẩu | Phim Lẻ hay | Tuyển tập Phim Lẻ mới nhất 2015';
		$breadcrumbs = '';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="'.$web_link.'/account/info" title="Tài khoản">Tài khoản</a></li>';
	    $breadcrumbs .= '<li><a class="current" href="#" title="Đổi mật khẩu">Đổi mật khẩu</a></li>';
	    $h1title = '<i class="icon-login font-purple-seance"></i> Đổi mật khẩu';


    if(isset($_POST['submit'])){
	    $passwordc 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['password-current'])))));
	    $password 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['password'])))));
	    $repassword 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['re-password'])))));
		
		if($passwordc == '' || $password == '' || $repassword == ''){
		    $error = '* Mật khẩu không được để trống!';
			$display = "display:block;";
			
		}elseif($password != $repassword){
		    $error = '* Mật khẩu xác nhận không chính xác!';
			$display = "display:block;";
		}else{
		    $passwordcc = md5($passwordc);
			$user_id = $_SESSION["user_id"];
		    $arr = $mysqldb->prepare("SELECT user_id,user_name,user_email,user_level,user_password FROM ".DATABASE_FX."user WHERE user_id = :userId");
            $arr->execute(array('userId' => $user_id));
	        $row = $arr->fetch();
		    if(!$row['user_id']){
		        $error = '* Bạn vui lòng đăng nhập để tiếp tục!';
				$display = "display:block;";
		
		    }elseif($row['user_id'] && $row['user_password'] != $passwordcc){
		        $error = '* Mật khẩu hiện tại không chính xác!';
				$display = "display:block;";
			   
		    }else{
			    $password = md5($password);
			    $mysql->query("UPDATE ".DATABASE_FX."user SET user_password = '".$password."' WHERE user_id = '".$user_id."'");
				$error1 = 'Chúc mừng: Thay đổi mật khẩu thành công! (Tự động chuyển về trang chủ sau 3s) <meta http-equiv="refresh" content="3;URL='.WEB_URL.'" /> ';
				$display = "display:block;";

			}
		
		}
		if(isset($error))
	    $error = '<div> <div role="alert" class="alert alert-warning alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button> '.$error.' </div> </div> ';
		elseif(isset($error1)){
		$error = '<div> <div role="alert" class="alert alert-success alert-dismissible fade in"> <button aria-label="Close" data-dismiss="alert" class="close" type="button"><span aria-hidden="true">×</span></button> '.$error1.' </div> </div> ';
		}else 
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
                                    <div class="message" style="<?=$display;?>"><?=$error;?></div>
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="password-current">Mật khẩu hiện tại</label>
                                        <div class="col-sm-8">
                                            <input class="form-control " type="password" name="password-current" id="password-current" placeholder="Mật khẩu hiện tại" value=""> </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="password">Mật khẩu mới</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="password" name="password" id="password" placeholder="Mật khẩu"> </div>
                                    </div>
									<div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="re-password">Xác nhận mật khẩu</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="password" name="re-password" id="re-password" placeholder="Xác nhận mật khẩu"> </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-7">
                                            <button type="submit" name="submit" class="btn mbtn btn-default"><span>Thay đổi</span></button>
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
    </div><script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pdnghia.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
     <? require_once("footer.php");?>
</body>

</html>
<? }else header('Location: '.$web_link.'/account/login'); }else header('Location: '.$web_link.'/404'); ?>