<?php
/*****
* Code by TrunksJj
* YH: imkingst - Email: duynghia95@gmail.com
*****/
ob_start();
session_start();
define('TRUNKSJJ',true);
include_once('../includes/configurations.php');
include_once('../includes/players.php');
include_once('../includes/functions.php');
include_once('../includes/AllTemplates.php');
include_once("../includes/phpmailer.php"); 
if(isset($_POST['subscribe']) && $_POST['subscribe'] == 1){	
    $captcha = ($_POST['captcha']);
    $filmId = (int)($_POST['filmId']);
    $email = $_POST['email'];
    $name = strip_tags(sql_escape($_POST['fullname']));
	$pattern = '#^[a-z][a-z0-9\._]{2,31}@[a-z0-9\-]{3,}(\.[a-z]{2,4}){1,2}$#';
	$secrethash = CodeSercurity(32);
	$checknotif = get_data_multi("notif_id","notif","notif_email = '".$email."' AND notif_film = '".$filmId."'");
    if(preg_match($pattern, $email, $match) != 1){
        echo 1; //--Địa chỉ email ko hợp lệ
    }elseif($captcha != $_SESSION['captcha']) {
		echo 2; //--Captcha không chính xác
	}elseif($checknotif) {
		echo 4; //--Captcha không chính xác
	}else{
	    $mysql->query("INSERT INTO ".DATABASE_FX."notif (notif_film,notif_username,notif_email,notif_time,notif_secrethash) VALUES ('".($filmId)."','".$name."','".$email."','".NOW."','".$secrethash."')");
	    $arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."film WHERE film_id = :id");
        $arr->execute(array('id' => $filmId));
	    $row = $arr->fetch();
		$filmNAMEVN = $row['film_name'];
		$filmNAMEEN = $row['film_name_real'];
		$filmID = $row['film_id'];
		$filmURL = $web_link.'/phim/'.replace(strtolower($filmNAMEVN)).'-'.replace($filmID).'/';
		$CheckCat = str_replace(',,,',',',$row['film_cat']);
	    $CheckCat = str_replace(',,',',',$CheckCat);
	    $CheckCat		=	explode(',',$CheckCat);
		$film_cat = '';
		$filmTIME = $row['film_time'];
		$filmLB = $row['film_lb'];
		$filmLANG = film_lang($row['film_lang']);
		$filmQUALITY = ($row['film_tapphim']);
		if($filmLB == 0){
	        $Status = $filmQUALITY.' '.$filmLANG;
	    }else{
	        $Status = $filmSTATUS.' '.$filmLANG;
	    }
	    for ($i=1; $i<count($CheckCat)-1;$i++) {
	        $cat_namez	  =	get_data('cat_name','cat','cat_id',$CheckCat[$i]);
            $cat_namez_title	  =	get_data('cat_name_title','cat','cat_id',$CheckCat[$i]);
	        $cat_namez_key	  =	get_data('cat_name_key','cat','cat_id',$CheckCat[$i]);
		    $film_cat 	.= '<a href="'.$web_link.'/the-loai/'.replace(strtolower(get_ascii($cat_namez_key))).'/" title="'.$cat_namez.'">'.$cat_namez.'</a>,  ';
	    }
	    $to = $email;
		$secrethash = get_data_multi("notif_secrethash","notif","notif_email = '".$email."' AND notif_film = '".$filmID."'");
		$contentHtml = 'Xin chào '.$name.',<br><br>
			Đội ngũ phát triển Phim Lẻ (PhimLe.Tv) rất vui khi bạn đã ủng hộ website và đăng ký theo dõi phim trên PhimLe.Tv.<br><br>
			Hệ thống vừa nhận được đăng ký theo dõi phim từ email <a href="mailto:'.$email.'" target="_blank">'.$email.'</a>. Dưới đây là thông tin phim đã theo dõi:<br>
			+-----------------------------<wbr>------------------------------<wbr>----<br>
			<b>Tên tiếng việt:</b> '.$filmNAMEVN.'<br>
			<b>Tên tiếng anh:</b> '.$filmNAMEEN.'<br>
			<b>Thể loại:</b> '.$film_cat.'<br>
			<b>Thời lượng:</b> '.$filmTIME.'<br><b>Trạng thái:</b> '.$Status.'<br>
			<b>Link xem phim:</b> <a href="'.$filmURL.'" target="_blank">'.$filmURL.'</a><br>
			+-----------------------------<wbr>------------------------------<wbr>----<br><br>
			<br>
			Kể từ bây giờ, Phim Lẻ sẽ gửi email cho bạn khi phim này được cập nhật (Có trailer mới, có thể xem online, có bản đẹp, cập nhật tập mới ...)<br>
			<br>
			Bạn có thể ngừng theo dõi phim bất cứ lúc nào bằng cách click vào đường link dưới đây:<br>
			<a href="'.$filmURL.'huy-theo-doi.html?secrethash='.$secrethash.'" target="_blank">'.$filmURL.'huy-theo-doi.html?secrethash='.$secrethash.'</a>
			<br>
			<br>
			Thân !<br>
			<b>PhimLe.Tv</b>, Website xem phim trực tuyến miễn phí !<br>
			Website: <a href="http://www.phimle.tv/" target="_blank">http://www.phimle.tv/</a><br>
			Facebook: <a href="http://www.facebook.com/phiimtv" target="_blank">http://www.facebook.com/<wbr>phiimtv</a>';
        $mail->CharSet = "UTF-8";
        $mail->From = EMAIL_REPLY;
        $mail->FromName = "PhimLe.Tv Notifier"; // Name to indicate where the email came from when the recepient received
        $mail->AddAddress($to,"PhimLe.Tv Notifier");
        $mail->AddReplyTo(EMAIL_REPLY,"PhimLe.Tv Notifier");
        $mail->WordWrap = 50; // set word wrap
        $mail->IsHTML(true); // send as HTML
        $mail->Subject = "PhimLe[Tv] Xác nhận theo dõi: Phim ".$row['film_name']." - ".$row['film_name_real']." (".$row['film_year'].")";
        $mail->Body = $contentHtml; //HTML Body
	    $mail->AltBody = htmltxt($contentHtml); //Text Body
	    if(!$mail->Send()){ // Nếu lỗi
		    echo 3; // Send mail lỗi
        }else{
 
            setcookie('notifEmail', $email, time() + (86400 * 30 * 12));
            setcookie('notifName', $name, time() + (86400 * 30 * 12));
            echo subscribeOff($filmURL,$secrethash,$filmNAMEVN);
            
        }
	}
    exit();
}elseif(isset($_POST['unsubscribe']) && $_POST['unsubscribe'] == 1){
    $filmId = (int)$_POST['filmId'];
    $hash = strip_tags(sql_escape($_POST['hash']));
	if(checkNotif($filmId)){
    	$mysql->query("DELETE FROM ".$tb_prefix."notif WHERE notif_secrethash = '".$hash."'");
	    echo 1; // Đã xóa
	}else echo 2;
	exit();
}else echo "Null";

?>