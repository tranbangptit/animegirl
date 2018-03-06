<?php 
session_start();
if($value[1]=='home-login'){
    $isLogin = checkLogin();
	if(!$isLogin){
		$web_keywords = 'xem phim Phim Lẻ full hd, phim Phim Lẻ online, phim Phim Lẻ vietsub, phim Phim Lẻ thuyet minh, phim  long tieng, phim Phim Lẻ tap cuoi';
	    $web_des = 'Phim Lẻ hay tuyển tập, Phim Lẻ mới nhất, tổng hợp phim Lẻ, Phim Lẻ full HD, Phim Lẻ vietsub, xem Phim Lẻ online';
	    $web_title = ''.$language['login'].' | Phim Lẻ hay | Tuyển tập Phim Lẻ mới nhất 2015';
		$breadcrumbs = '';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="'.$web_link.'/account/login" title="'.$language['login'].'">'.$language['login'].'</a></li>';
	    $h1title = '<i class="icon-login font-purple-seance"></i> '.$language['login'].'';


    if(isset($_POST['submit'])){
	    $username 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['email'])))));
	    $password 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['password'])))));
		
		if($_POST['email'] == '' || $_POST['password'] == ''){
		    $error = '* '.$language['login_error1'];
			$display = "display:block;";
			
		}else{
		    $password = md5($password);
		    $arr = $mysqldb->prepare("SELECT user_id,user_name,user_email,user_level,user_password FROM ".DATABASE_FX."user WHERE user_email = :name");
            $arr->execute(array('name' => $username));
	        $row = $arr->fetch();
		    if(!$row['user_id']){
		        $error = '* '.$language['login_error2'];
				$display = "display:block;";
		
		    }elseif($row['user_id'] && $row['user_password'] != $password){
		        $error = '* '.$language['login_error3'];
				$display = "display:block;";
			   
		    }else{
			    $id = $row['user_id'];
			    $userlevel = $row['user_level'];
		        $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $row['user_name'];
                $_SESSION["user_group"] = $userlevel;
		        setcookie('user_id', $id, time() + (86400 * 30 * 12), "/");
			    $mysql->query("UPDATE ".DATABASE_FX."user SET user_time_last = '".NOW."' WHERE user_email = '".$username."'");
				$phpFastCache->delete('phimletv-aside');
			    header("Location: ".$web_link."/account/info");
			    
			}
		
		}
	
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

                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="account">Email</label>
                                        <div class="col-sm-8">
                                            <input class="form-control " type="text" name="email" id="account" placeholder="Địa chỉ email" value=""> </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <label class="control-label col-sm-3" for="password">Mật khẩu</label>
                                        <div class="col-sm-8">
                                            <input class="form-control" type="password" name="password" id="password" placeholder="Mật khẩu"> </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <div class="control-label col-sm-3"></div>
                                        <div class="col-sm-8">
                                            <div class="checkbox">
                                                <input id="remember" type="checkbox" value="1" name="remember" checked>
                                                <label for="remember"> Ghi nhớ đăng nhập ? </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="form-group form-group-lg row">
                                        <div class="col-sm-3"></div>
                                        <div class="col-sm-7">
                                            <button type="submit" name="submit" class="btn mbtn btn-default"><span>Đăng nhập</span></button>
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
<? }else header('Location: '.$web_link.'/account/info'); }else header('Location: '.$web_link.'/404'); ?>