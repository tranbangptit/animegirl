<?php 
if($value[1]=='home-forgot'){
    
    $isLogin = checkLogin();
	if(!$isLogin){
		$web_keywords = 'quên mật khẩu phimle.tv, xem phim Phim Lẻ full hd, phim Phim Lẻ online, phim Phim Lẻ vietsub, phim Phim Lẻ thuyet minh, phim  long tieng, phim Phim Lẻ tap cuoi';
	    $web_des = 'Phim Lẻ hay tuyển tập, Phim Lẻ mới nhất, tổng hợp phim Lẻ, Phim Lẻ full HD, Phim Lẻ vietsub, xem Phim Lẻ online';
	    $web_title = ''.$language['forgotz'].' | Phim Lẻ hay | Tuyển tập Phim Lẻ mới nhất 2015';
		$breadcrumbs = '';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="'.$web_link.'/account/login" title="'.$language['forgotz'].'">'.$language['forgotz'].'</a></li>';
	    $h1title = '<i class="icon-key font-purple-seance"></i> '.$language['forgotz'].'';
		 
		$userid = explode("u=",URL_LOAD);
	    $userid = explode("&",$userid[1]);
	    $userid =	(int)($userid[0]);
		$code   = explode("code=",URL_LOAD);
		$code   = sql_escape(stripslashes(trim($code[1])));
		
		
		if($code && $userid){
		    $arrz = $mysqldb->prepare("SELECT user_email,user_name FROM ".DATABASE_FX."user WHERE user_id = :id AND user_hash = :hash");
            $arrz->execute(array('id' => $userid,'hash' => $code));
	        $row = $arrz->fetch();
		    $email = $row['user_email'];
			if($email){
			    $keypass = CodeSercurity(6);
				$pass = md5(stripslashes($keypass));
				$mysql->query("UPDATE ".$tb_prefix."user SET user_hash = '', user_password = '".$pass."' WHERE user_id = '".$userid."' AND user_hash = '".$code."'");
				$to= $email; // Recipients email ID
                $mail->From = EMAIL_REPLY;
                $mail->FromName = EMAIL_NAME; // Name to indicate where the email came from when the recepient received
                $mail->AddAddress($to,EMAIL_NAME);
                $mail->AddReplyTo(EMAIL_REPLY,EMAIL_NAME);
                $mail->WordWrap = 50; // set word wrap
                $mail->IsHTML(true); // send as HTML
                $mail->Subject = $language['forgot_mail_subject'];
                $mail->Body = $language['forgot_mail_content1']." <b>".$row['user_name']."</b>,<br />".$language['forgot_pass1']."<br />".$language['forgot_pass2']." ".$keypass."<br />".$language['forgot_pass3']; //HTML Body
				
                $mail->AltBody = $language['forgot_mail_content1']." ".$row['user_name'].",".$language['forgot_pass1'].",".$language['forgot_pass2']." ".$keypass.",".$language['forgot_pass3']; //Text Body
			    if(!$mail->Send()){
				$text = $mail->ErrorInfo;
                }else{
                $text = '<form class="form-horizontal" method="post" enctype="application/x-www-form-urlencoded"><div class="text-left">'.$language['forgot_code_des'].'</div><div class="form-group"><label class="col-sm-4 control-label">Email:</label><div class="col-sm-8"><label class="col-sm-4 control-label"><b>'.$email.'</b></label></div></div><div class="form-group"><label class="col-sm-4 control-label">'.$language['password'].':</label><div class="col-sm-8"><label class="col-sm-4 control-label"><b>'.$keypass.'</b></label></div></div></form>';
                }	
			}else{
			    $text = '<form class="form-horizontal" method="post" id="forgot-form"><div class="form-group" id="join-error" style=""><div class="col-md-9" style="float: right;"><div class="join-error">* Code bảo mật gửi vào mail không đúng.</div></div></div><div class="form-body" id="loginInput"><div class="form-group"><label class="col-md-3 control-label">Email</label><div class="col-md-9"><div class="input-icon"><i class="fa fa-envelope-o"></i><input class="form-control" name="email" placeholder="Email" value="'.$email.'" type="email"></div></div></div><div class="form-group"><label class="col-md-3 control-label">'.$language['verification'].' </label><div class="col-md-9"><div class="input-group"><div class="input-icon"><i class="fa fa-lock fa-fw"></i><input class="form-control" name="captcha" placeholder="'.$language['verification_des'].'" type="text"></div><span class="input-group-btn"><img id="captchaimg" src="'.$web_link.'/captcha.php?rand='.rand(1000,9999).'"><a class="fa fa-refresh" href="javascript: refreshCaptcha();"></a></span></div></div></div><div class="form-actions"><div class="row"><div class="col-md-offset-3 col-md-9"><button type="submit" name="submit" class="btn purple">'.$language['send'].'</button></div></div></div></div></form>';
			}
			
			
		}elseif(isset($_POST['submit'])){
	    $email 		= trim(htmlchars(stripslashes(urldecode(injection($_POST['email'])))));
		$check_email = get_data('user_id','user','user_email',$email);
		if($_POST['email'] == ''){
		    $error = '* '.$language['login_error1'];
			$display = "display:block;";
			
			$text = '';
			
		}elseif($_POST['captcha'] != $_SESSION['captcha']) {
			$error = '* Mã xác nhận không đúng!';
			$display = "display:block;";

			$text = '';
		}elseif(validateEmail($email) == 0){
			$error = '* Địa chỉ Email không chính xác!';
			$display = "display:block;";

			$text = '';
		}elseif(!$check_email){
		    $error = '* Không tồn tại địa chỉ email này!';
			$display = "display:block;";

			$text = '';
		}else{
		    $key = CodeSercurity(32);
		    $mysql->query("UPDATE ".$tb_prefix."user SET user_hash = '".$key."' WHERE user_email = '".$email."'");
            $arr = $mysqldb->prepare("SELECT user_id,user_name,user_hash FROM ".DATABASE_FX."user WHERE user_email = :email");
            $arr->execute(array('email' => $email));
	        $r = $arr->fetch();
			$keyz = $r['user_hash'];
            $to= $email; // Recipients email ID
            $mail->From = EMAIL_REPLY;
            $mail->FromName = EMAIL_NAME; // Name to indicate where the email came from when the recepient received
            $mail->AddAddress($to,EMAIL_NAME);
            $mail->AddReplyTo(EMAIL_REPLY,EMAIL_NAME);
            $mail->WordWrap = 50; // set word wrap
            $mail->IsHTML(true); // send as HTML
            $mail->Subject = $language['forgot_mail_subject'];
            $mail->Body = $language['forgot_mail_content1']." ".$r['user_name'].",<br />".$language['forgot_mail_content2']."<br />".$web_link."/account/forgot?u=".$r['user_id']."&code=".$keyz."<br />".$language['forgot_mail_content3']; //HTML Body
            $mail->AltBody = $language['forgot_mail_content1']." ".$r['user_name'].",".$language['forgot_mail_content2']."".$web_link."/account/forgot?u=".$r['user_id']."&code=".$keyz.",".$language['forgot_mail_content3']; //Text Body
			if(!$mail->Send()){
				$text = $mail->ErrorInfo;
            }else{
                $text = $language['forgot_des'].'<br /><b>'.$email.'</b>.<script>$("#forgot-form").remove();</script>';
            }
			$error = false;
			
		}
	   
	}else{
	    $error = false;
	    $display = "display:none;";
		$text = false;
	}
     if($error != false || $text == false){
		    $forms = '<form class="form-horizontal" method="post" id="forgot-form"><div class="form-group" id="join-error" style=""><div class="col-md-9" style="float: right;"><div class="join-error">'.$error.'</div></div></div><div class="form-body" id="loginInput"><div class="form-group"><label class="col-md-3 control-label">Email</label><div class="col-md-9"><div class="input-icon"><i class="fa fa-envelope-o"></i><input class="form-control" name="email" placeholder="Email" value="'.$email.'" type="email"></div></div></div><div class="form-group"><label class="col-md-3 control-label">'.$language['verification'].' </label><div class="col-md-9"><div class="input-group"><div class="input-icon"><i class="fa fa-lock fa-fw"></i><input class="form-control" name="captcha" placeholder="'.$language['verification_des'].'" type="text"></div><span class="input-group-btn"><img id="captchaimg" src="'.$web_link.'/captcha.php?rand='.rand(1000,9999).'"><a class="fa fa-refresh" href="javascript: refreshCaptcha();"></a></span></div></div></div><div class="form-actions"><div class="row"><div class="col-md-offset-3 col-md-9"><button type="submit" name="submit" class="btn purple">'.$language['send'].'</button></div></div></div></div></form>';
		}else{
		    $forms = '';
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
<div class="page-container">
  <? require_once("left.php");?>
    <div class="page-content-wrapper">
        <div class="page-content" style="min-height:1269px">
            <div class="page-bar">
                <ul class="page-breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                <?=$breadcrumbs;?>
                </ul>
            </div>
            <div class="row">
<div class="col-md-9">
<div class="portlet light">
<div class="portlet-title">
<div class="caption">
<h1 class="caption-subject font-purple-seance uppercase">
<?=$h1title;?> </h1>
</div>

</div>
<div class="portlet-body" id="movies-load">
<div class="portlet-body form">
<?=$text;?>
<!-- BEGIN FORM-->
    <?=$forms;?>
	<!-- END FORM-->
</div>
</div>

		</div></div>
<div class="col-md-3">
<a class="dashboard-stat dashboard-stat-light red" href="https://docs.google.com/forms/d/1_UxlR4wiGZvdpIQWxedx69QNuqWe0U9VD1fz1n65IUw/viewform">
<div class="visual">
<i class="fa fa-comments"></i>
</div>
<div class="details">
<div class="desc">
<h3 style="left:-20px;position:relative;"><b>BẠN ĐANG TÌM PHIM?</b></h3>
</div>
<div class="desc">
Bấm vào đây để PhimLẻ[Tv] giúp bạn<br>tìm phim nhanh nhất!
</div>
</div>
</a>
<div class="portlet light hidden-sm hidden-xs">
<div class="portlet-title tabbable-line">
<div class="caption">
<span class="caption-subject font-purple-seance uppercase"><?=$language['moviesingle_hot_w'];?></span>
</div>
</div>
<div class="portlet-body">
<div class="tab-content">
<div class="scroller" style="height:477px" data-always-visible="1" data-rail-visible="0">
<?=ShowFilm("WHERE film_lb = 0","ORDER BY film_viewed_w",10,'showfilm_right_home','phimlle_hotw');?>
 </div>
</div>
</div>
</div>
<div class="portlet light hidden-sm hidden-xs">
<div class="portlet-title tabbable-line">
<div class="caption">
<span class="caption-subject font-purple-seance uppercase"><?=$language['movieserial_hot_w'];?></span>
</div>
</div>
<div class="portlet-body">
<div class="tab-content">
<div class="scroller" style="height:477px" data-always-visible="1" data-rail-visible="0">
<?=ShowFilm("WHERE film_lb IN (1,2)","ORDER BY film_viewed_w",10,'showfilm_right_home','phimbo_hotw');?></div>
</div>
</div>
</div>
</div>
</div> 
 
<? require_once("footer.php");?>
</div>
</div>
 
 
 
</div>
<div class="scroll-to-top" style="display: none;">
<i class="icon-arrow-up"></i>
</div>
<div style="margin-bottom:2px;">
</div>  
<? require_once("javascripts.php");?>
</body>
</html>
<? 
}else header('Location: '.$web_link.'/account/info');

}else header('Location: '.$web_link.'/404'); ?>