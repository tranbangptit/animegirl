<?php
/*****
* Code by TrunksJj
* YH: imkingst - Email: duynghia95@gmail.com
*****/
ob_start();
session_start();
define('TRUNKSJJ',true);
error_reporting(E_ALL);
ini_set('display_errors', 1);
include_once('../includes/configurations.php');
include_once('../includes/players.php');
include_once('../includes/functions.php');
include_once('../includes/AllTemplates.php');
include_once("../includes/phpmailer.php"); 
if(isset($_GET['filmId']) && $_GET['filmId'] != ''){
    $filmId = (int)($_GET['filmId']);
    $send =  get_total("notif","notif_id","WHERE notif_film = '".$filmId."' AND notif_send = 1");
    $key = "filmId-".$filmId;
    if($send){
	 //--Mail
	    $arrMail = $mysqldb->prepare("SELECT notif_email,notif_username,notif_secrethash FROM ".DATABASE_FX."notif WHERE notif_film = :phim AND notif_send = 1");
        $arrMail->execute(array('phim' => $filmId));
	    $rowMail = $arrMail->fetch();
		$email = $rowMail['notif_email'];
        $name =  $rowMail['notif_username'];
        $secrethash =  $rowMail['notif_secrethash'];
	 //--Film
	    $arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."film WHERE film_id = :id");
        $arr->execute(array('id' => $filmId));
	    $row = $arr->fetch();
		$filmNAMEVN = $row['film_name'];
		$filmNAMEEN = $row['film_name_real'];
                $filmSLUG = $row['film_slug'];
		$filmID = $row['film_id'];
		$filmURL = $web_link.'/phim/'.$filmSLUG.'-'.replace($filmID).'/';
		$CheckCat = str_replace(',,,',',',$row['film_cat']);
	    $CheckCat = str_replace(',,',',',$CheckCat);
	    $CheckCat		=	explode(',',$CheckCat);
		$film_cat = '';
		$filmTIME = $row['film_time'];
		$filmLB = $row['film_lb'];
		$filmLANG = film_lang($row['film_lang']);
		$filmQUALITY = ($row['film_tapphim']);
		
		$Status = $row['film_trangthai'];
	    for ($i=1; $i<count($CheckCat)-1;$i++) {
	        $cat_namez	  =	get_data('cat_name','cat','cat_id',$CheckCat[$i]);
            $cat_namez_title	  =	get_data('cat_name_title','cat','cat_id',$CheckCat[$i]);
	        $cat_namez_key	  =	get_data('cat_name_key','cat','cat_id',$CheckCat[$i]);
		    $film_cat 	.= '<a href="'.$web_link.'/the-loai/'.replace(strtolower(get_ascii($cat_namez_key))).'/" title="'.$cat_namez.'">'.$cat_namez.'</a>,  ';
	    }
		//--SendMail
	    $to = $email;
        
        $data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
		$contentHtmlz = 'Xin chào '.$name.',';
            if($data_cache != null){
		        $contentHtml = $data_cache; 
		    }else{
		        $contentHtml = '<br><br>
			Bạn nhận được email này vì bạn đã theo dõi phim trên PhimLe.Tv.<br><br>
			Bộ phim <b>'.$filmNAMEVN.'</b> mà bạn đang theo dõi vừa có cập nhật mới:<br>
			+--------------------<br>
			<b>Tên tiếng việt:</b> '.$filmNAMEVN.'<br>
			<b>Tên tiếng anh:</b> '.$filmNAMEEN.'<br>
			<b>Thể loại:</b> '.$film_cat.'<br>
			<b>Trạng thái hiện tại:</b> <font color="red">'.$Status.'</font><br>
			<b>Chất lượng:</b> <font color="red">'.$filmQUALITY.'</font><br>
			<b>Ngôn ngữ:</b> '.$filmLANG.'<br>
			<b>Thời lượng:</b> '.$filmTIME.'<br>
			<b>Link xem phim:</b> <a href="'.$filmURL.'" target="_blank">'.$filmURL.'</a><br>
			+-----------------------------<wbr>------------------------------<wbr>----<br><br>
			<br>
			Rất cám ơn bạn đã ủng hộ website. !<br>
			<br>
			Nếu bạn không muốn theo dõi phim này nữa, bạn có thể ngừng theo dõi bất cứ lúc nào bằng cách click vào đường link dưới đây:<br>
			<a href="'.$filmURL.'huy-theo-doi.html?secrethash='.$secrethash.'" target="_blank">'.$filmURL.'huy-theo-doi.html?secrethash='.$secrethash.'</a>
			<br>
			<br>
			Thân !<br>
			<b>PhimLe.Tv</b>, Website xem phim trực tuyến miễn phí !<br>
			Website: <a href="http://www.phimle.tv/" target="_blank">http://www.phimle.tv/</a><br>
			Facebook: <a href="http://www.facebook.com/phiimtv" target="_blank">http://www.facebook.com/<wbr>phiimtv</a><br><br>
			<i>Trường hợp hệ thống liên tục gửi email thông báo cho bạn với nội dung giống nhau thì rất có thể đang bị lỗi, nếu bạn không phiền có thể bỏ chút thời gian báo lỗi khẩn cấp đến email <a href="mailto:phimletv2015@gmail.com" target="_blank">phimletv2015@gmail.com</a> để chúng tôi khắc phục. Cám ơn các bạn.</i>';
            $phpFastCache->set($key, $contentHtml, CACHED_TIME);
            }
		$contentHtml = 'Xin chào '.$name.','.$contentHtml;
        $mail->CharSet = "UTF-8";
        $mail->FromName = "PhimLe.Tv Notifier"; // Name to indicate where the email came from when the recepient received
        $mail->AddAddress($to,"PhimLe.Tv Notifier");
        $mail->AddReplyTo(EMAIL_REPLY,"PhimLe.Tv Notifier");
        $mail->WordWrap = 50; // set word wrap
        $mail->Subject = $filmQUALITY."-".$filmLANG." | ".$Status.": ".$filmNAMEVN." (".$filmNAMEEN.") [".$row['film_year']."]";
        $mail->Body = $contentHtml; //HTML Body
	    $mail->AltBody = htmltxt($contentHtml); //Text Body
	    if(!$mail->Send()){ // Nếu lỗi
		    echo 'Đã xảy ra lỗi trong quá trình gửi mail.<br>- Tên: '.$name.'<br>- Email: '.$email.'<br>- Đăng ký phim: '.$filmNAMEVN;
        }else{
			$mysql->query("UPDATE ".DATABASE_FX."notif SET notif_send = 0 WHERE notif_film = '".$filmID."' AND notif_email = '".$email."'");

            echo 'Hoàn tất send mail cho '.$name.'('.$email.') ~> Chuyển sang người kế tiếp...sau 3s<meta http-equiv="refresh" content="3;url=sendmail.php?filmId='.$filmID.'">';
        }
	}else{//$filmId 
	    $phpFastCache->delete($key);
	    echo 'Hoàn tất send mail cho người theo dõi!(Tự động chuyển về danh sách phim sau 3s)<meta http-equiv="refresh" content="3;url=index.php?act=film&mode=edit">';
	}	
}else echo "Null";

?>