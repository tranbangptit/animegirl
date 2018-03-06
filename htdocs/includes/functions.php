<?php 
if (!defined('TRUNKSJJ')) die("Hack");
function plsendmail($content,$contentHtml){
    global $mail;
	$to = "duynghia95@gmail.com";
    $mail->From = EMAIL_REPLY;
    $mail->FromName = EMAIL_NAME; // Name to indicate where the email came from when the recepient received
    $mail->AddAddress($to,EMAIL_NAME);
    $mail->AddReplyTo(EMAIL_REPLY,EMAIL_NAME);
    $mail->WordWrap = 50; // set word wrap
    $mail->IsHTML(true); // send as HTML
    $mail->Subject = "Requestbox from website localhost";
    $mail->Body = $contentHtml; //HTML Body
	$mail->AltBody = $content; //Text Body
	if(!$mail->Send()){
		$text = false;
    }else{
        $text = true;
    }	
    return $text;
}
function film_lang($id){
if($id == 1){$type = 'VietSub';}
elseif($id == 2){$type = 'Thuyết minh';}
elseif($id == 3){$type = 'Lồng tiếng';}
elseif($id == 4){$type = 'VietSub + TM';}
elseif($id == 5){$type = 'NoSub';}
elseif($id == 6){$type = 'EngSub';}
else $type='Đang cập nhật';
return $type;
}
function getCurrentPageURL() {
global $web_link;
$pageURL = 'http';
if (!empty($_SERVER['HTTPS'])) {if($_SERVER['HTTPS'] == 'on'){$pageURL .= "s";}}
$pageURL .= "://";
if ($_SERVER["SERVER_PORT"] != "80") {
$pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
} else {
$pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
}

return $pageURL;
}
function replace($string) {
	$string = get_ascii($string);
    $string = preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
        array('', '-', ''), htmlspecialchars_decode($string));
    return $string;
}

function get_ascii($str) {

	$chars = array(

		'a'	=>	array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),

		'e' 	=>	array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),

		'i'	=>	array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),

		'o'	=>	array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),

		'u'	=>	array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),

		'y'	=>	array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),

		'd'	=>	array('đ','Đ'),

	);

	foreach ($chars as $key => $arr) 

		foreach ($arr as $val)

			$str = str_replace($val,$key,$str);

	return $str;

}
function changeUrlGoogle($subject){
    $pattern = '/https:\/\/(.*).googleusercontent.com\/((.*)\/(.*)\/(.*)\/(.*)\/(.*)\/(.*))/'; 
    preg_match($pattern, $subject, $matches);
	if($matches[2]) $Url = 'https://2.bp.blogspot.com/'.$matches[2];
else $Url = $subject;
	return $Url;
}
function thumbimg($url,$size = 0){
    global $web_link,$CurrentSkin;
    if(strpos($url, 'imgur.com') !== false){
        if(strpos($url, '.jpg') !== false){
            $u = str_replace('.jpg','m.jpg',$url);
        }elseif(strpos($url, '.png') !== false){
            $u = str_replace('.png','m.png',$url);
        }
    }elseif(strpos($url, 'googleusercontent.com') !== false){
        $u = str_replace('/s0/','/s'.$size.'/',$url);
        $u = changeUrlGoogle($u);
    }elseif($url == ''){
	    $u = $web_link.'/templates/'.$CurrentSkin.'/images/noimg.jpg';
	}else $u = $url;
    return $u;
}

function cut_string($string,$len)
{
    if($len > strlen($string)){
	$len=strlen($string);
	};
    $pos = strpos($string, ' ', $len);
    if($pos){$string = substr($string,0,$pos);
	$string = $string.'...';
	}else{
	$string = substr($string,0,$len);
	}    
    return $string;
}
function htmltxt($document){
	$search = array('@<script[^>]*?>.*?</script>@si',  // Strip out javascript
				   '@<[\/\!]*?[^<>]*?>@si',            // Strip out HTML tags
				   '@<style[^>]*?>.*?</style>@siU',    // Strip style tags properly
				   '@<![\s\S]*?--[ \t\n\r]*>@'         // Strip multi-line comments including CDATA
	);
	$text = preg_replace($search, '', $document);
	return $text;
} 

function text_tidy1($string) {

	$string = str_replace ('&amp;','&',$string );

	$string = str_replace ("'","'",$string );

	$string = str_replace ( '&quot;', '"', $string );

	$string = str_replace ( '&lt;', '<', $string );

	$string = str_replace ( '&gt;', '>', $string );

		$string = str_replace ( '"', '"', $string );

		$string = str_replace ( 'alt=""', 'alt="Xem Phim Online"', $string );
		$string = str_replace ( '\r\n', '', $string);
		$string = str_replace ( '\\"', '', $string );
		$string = str_replace ( 'rn', '', $string );
		

	return $string;

}
function TAGS_LINK2($text) {
	global $web_link;
	$text	=	str_replace(",  ",",",$text);
	$text	=	str_replace(", ",",",$text);
	$text   = 	explode(",",$text);
	$html   = '';
	for ($i = 0; $i < count($text); $i++) {
		$data	=	str_replace(" ","-",$text[$i]);
		$html	.= 	"<a href=\"".$web_link."/tag/".strtolower(get_ascii($data))."/\" rel=\"follow, index\" title=\"Xem Phim ".$text[$i]."\">".$text[$i]."</a>, ";
	}
	$html = substr($html,0,-2);
	return $html;
}
function TAGS_ACTOR($text) {
	global $web_link;
	$text	=	str_replace(",  ",",",$text);
	$text	=	str_replace(", ",",",$text);
	$text   = 	explode(",",$text);
	$html = '';
	for ($i = 0; $i < count($text); $i++) {
		$data	=	str_replace(" ","-",$text[$i]);
		$html	.= 	"<a href=\"".$web_link."/dien-vien/".strtolower(get_ascii($data)).".html\" rel=\"follow, index\" title=\"Xem Phim của ".$text[$i]."\">".$text[$i]."</a>, ";
	}
	$html = substr($html,0,-2);
	return $html;
}
function TAGS_LINK($text) {
	global $web_link;
	$text	=	str_replace(",  ",",",$text);
	$text	=	str_replace(", ",",",$text);
	$text   = 	explode(",",$text);
	$html ='';
	for ($i = 0; $i < count($text); $i++) {
		$data	=	str_replace(" ","-",$text[$i]);
		$html	.= 	"<h4><a href=\"".$web_link."/tag/".strtolower(get_ascii($data))."/\" rel=\"follow, index\" title=\"Xem Phim ".$text[$i]."\">".$text[$i]."</a></h4>, ";
	}
	$html = substr($html,0,-2);
	return $html;
}

function un_htmlchars($str) {

	return str_replace(array('&lt;', '&gt;', '&quot;', '&amp;', '&#92;', '&#39'), array('<', '>', '"', '&', chr(92), chr(39)), $str );

}



function htmlchars($str) {

	return str_replace(

		array('&', '<', '>', '"', chr(92), chr(39)),

		array('&amp;', '&lt;', '&gt;', '&quot;', '&#92;', '&#39'),

		$str

	);

}
function injection($str) {

	$chars = array('chr(', 'chr=', 'chr%20', '%20chr', 'wget%20', '%20wget', 'wget(','cmd=', '%20cmd', 'cmd%20', 'rush=', '%20rush', 'rush%20', 'union%20', '%20union', 'union(', 'union=', 'echr(', '%20echr', 'echr%20', 'echr=', 'esystem(', 'esystem%20', 'cp%20', '%20cp', 'cp(', 'mdir%20', '%20mdir', 'mdir(', 'mcd%20', 'mrd%20', 'rm%20', '%20mcd', '%20mrd', '%20rm', 'mcd(', 'mrd(', 'rm(', 'mcd=', 'mrd=', 'mv%20', 'rmdir%20', 'mv(', 'rmdir(', 'chmod(', 'chmod%20', '%20chmod', 'chmod(', 'chmod=', 'chown%20', 'chgrp%20', 'chown(', 'chgrp(', 'locate%20', 'grep%20', 'locate(', 'grep(', 'diff%20', 'kill%20', 'kill(', 'killall', 'passwd%20', '%20passwd', 'passwd(', 'telnet%20', 'vi(', 'vi%20', 'insert%20into', 'select%20', 'nigga(', '%20nigga', 'nigga%20', 'fopen', 'fwrite', '%20like', 'like%20', '$_request', '$_get', '$request', '$get', '.system', 'HTTP_PHP', '&aim', '%20getenv', 'getenv%20', 'new_password', '&icq','/etc/password','/etc/shadow', '/etc/groups', '/etc/gshadow', 'HTTP_USER_AGENT', 'HTTP_HOST', '/bin/ps', 'wget%20', 'uname\x20-a', '/usr/bin/id', '/bin/echo', '/bin/kill', '/bin/', '/chgrp', '/chown', '/usr/bin', 'g\+\+', 'bin/python', 'bin/tclsh', 'bin/nasm', 'perl%20', 'traceroute%20', 'ping%20', '.pl', '/usr/X11R6/bin/xterm', 'lsof%20', '/bin/mail', '.conf', 'motd%20', 'HTTP/1.', '.inc.php', 'config.php', 'cgi-', '.eml', 'file\://', 'window.open', '<SCRIPT>', 'javascript\://','img src', 'img%20src','.jsp','ftp.exe', 'xp_enumdsn', 'xp_availablemedia', 'xp_filelist', 'xp_cmdshell', 'nc.exe', '.htpasswd', 'servlet', '/etc/passwd', 'wwwacl', '~root', '~ftp', '.js', '.jsp', 'admin_', '.history', 'bash_history', '.bash_history', '~nobody', 'server-info', 'server-status', 'reboot%20', 'halt%20', 'powerdown%20', '/home/ftp', '/home/www', 'secure_site, ok', 'chunked', 'org.apache', '/servlet/con', '<script', '/robot.txt' ,'/perl' ,'mod_gzip_status', 'db_mysql.inc', '.inc', 'select%20from', 'select from', 'drop%20', '.system', 'getenv', 'http_', '_php', 'php_', 'phpinfo()', '<?php', '?>', 'sql=','\'');

	foreach ($chars as $key => $arr)

		$str = str_replace($arr, '*', $str); 

	return $str;



}
function Upcase_First($string)

{

	$string = replace($string);

	$string = str_replace(array('-', '_'), ' ', $string);

  	$string = str_replace(' ', '-', $string); 

  return $string;

}

function replacesearch($str) {
	$str = str_replace('%20', '+', $str);
	$str = str_replace(' ', '+', $str);
	return $str;
}
function replacetag($str) {
	$str = str_replace('%20', '-', $str);
	$str = str_replace(' ', '-', $str);
	return $str;
}
function sql_escape($value)
{      
    $value = trim(htmlchars(stripslashes(urldecode(injection($value)))));
    return $value;       
}
 function validateEmail($email) {
  if(filter_var($email,FILTER_VALIDATE_EMAIL))
    return 1;
else
    return 0;
} 
function floodpost(){
	$_SESSION['current_message_post'] = NOW;
        if(isset($_SESSION['prev_message_post']))
	$timeDiff_post = $_SESSION['current_message_post'] - $_SESSION['prev_message_post'];
        else 
        $timeDiff_post = $_SESSION['current_message_post'] - 0;

	$floodInterval_post	= 10;
	$wait_post = $floodInterval_post - $timeDiff_post;	
	if($timeDiff_post <= $floodInterval_post)
	return true;
	else 
	return false;
} 
function replace_tag_a($txt){
	if(strpos(strtolower($txt) , 'http') !== false){
    //-- Bắt đầu tách link và thay thế rel=nofollow
	    $txt_filter = explode('http',strtolower($txt));
		$txt_link = $txt;
		for($i=1;$i<count($txt_filter);$i++){
		$txt_filterz = explode(' ',$txt_filter[$i]);
		$txt_filterz = 'http'.$txt_filterz[0];
        $txt_link = str_replace($txt_filterz,'<a rel="nofollow" href="'.$txt_filterz.'">'.$txt_filterz.'</a>',$txt_link);
	    
		}
        return $txt_link;	
    }else return $txt;
}
function textlink_site($info){
       	$info = str_replace("hành động",'<a title="Phim hành động" href="http://www.localhost/the-loai/hanh-dong/">hành động</a>',$info);
	       $info = str_replace("tình cảm",'<a title="Phim tình cảm" href="http://www.localhost/the-loai/tinh-cam/">tình cảm</a>',$info);
	       $info = str_replace("cấp 3",'<a title="Phim cấp 3" href="http://www.localhost/tag/phim-cap-3/">cấp 3</a>',$info);
	$info = str_replace("18 +",'<a title="Phim 18 +" href="http://www.localhost/tag/phim-18/">18 +</a>',$info);
	$info = str_replace("18+",'<a title="Phim 18+" href="http://www.localhost/tag/phim-18/">18+</a>',$info);
	$info = str_replace("kinh di",'<a title="Phim kinh di" href="http://www.localhost/the-loai/kinh-di/">kinh di</a>',$info);
	       $info = str_replace("võ thuật",'<a title="Phim võ thuật" href="http://www.localhost/the-loai/vo-thuat/">võ thuật</a>',$info);
	$info = str_replace("tâm lý",'<a title="Phim tâm lý" href="http://www.localhost/the-loai/tam-ly/">tâm lý</a>',$info);
	       $info = str_replace("hài hước",'<a title="Phim hài hước" href="http://www.localhost/the-loai/hai-huoc/">hài hước</a>',$info);
	       $info = str_replace("hoạt hình",'<a title="Phim hoạt hình" href="http://www.localhost/the-loai/hoat-hinh/">hoạt hình</a>',$info);
	       $info = str_replace("viễn tưởng",'<a title="Phim viễn tưởng" href="http://www.localhost/the-loai/vien-tuong/">viễn tưởng</a>',$info);
	       $info = str_replace("hình sự",'<a title="Phim hình sự" href="http://www.localhost/the-loai/hinh-su/">hình sự</a>',$info);
	
	$info = str_replace("xem phim",'<a title="Xem Phim" href="http://www.localhost/">xem phim</a>',$info);
       // $info = str_replace($text1,'<a title="'.$text1.'" href="'.$link.'">'.$text1.'</a>',$info);
	return $info;
}
function RemainTime($timestamp, $detailLevel = 1) {

	$periods = array("giây", "phút", "giờ", "ngày", "tuần", "tháng", "năm", "thập kỷ");
	$lengths = array("60", "60", "24", "7", "4.35", "12", "10");

	$now = time();

	// check validity of date
	if(empty($timestamp)) {
		return "Unknown time";
	}

	// is it future date or past date
	if($now > $timestamp) {
		$difference = $now - $timestamp;
		$tense = "trước";

	} else {
		$difference = $timestamp - $now;
		$tense = "from now";
	}

	if ($difference == 0) {
		return "vài giây trước";
	}

	$remainders = array();

	for($j = 0; $j < count($lengths); $j++) {
		$remainders[$j] = floor(fmod($difference, $lengths[$j]));
		$difference = floor($difference / $lengths[$j]);
	}

	$difference = round($difference);

	$remainders[] = $difference;

	$string = "";

	for ($i = count($remainders) - 1; $i >= 0; $i--) {
		if ($remainders[$i]) {
			$string .= $remainders[$i] . " " . $periods[$i];

			if($remainders[$i] != 1) {
				$string .= "";
			}

			$string .= " ";

			$detailLevel--;

			if ($detailLevel <= 0) {
				break;
			}
		}
	}

	return $string . $tense;

} 
function get_idyoutube($urls){
    $url = explode('v=',$urls);
    $url = explode('&',$url[1]);
    $id = $url[0];
    return $id;
}

function CodeSercurity($num){
    $x = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
    $i = 0;
    $code = '';
    while ($i < $num) { 
        $code .= substr($x, mt_rand(0, strlen($x)-1), 1);
        $i++;
    }
    return $code;
}
function showStar($score){
    
	$label = explode('|',STARLABEL);
	$star = '';
	$score = round($score/2,1);
	for($i=0;$i<=count($label)-1;$i++){
	$z = $i+1;
	if($z <= $score){
	    $rate = 'fa fa-star';
	}elseif($z - 0.5 > $score){
	    $rate = 'fa fa-star-o';
	}elseif($z - 1.5 >= $score){
	    $rate = 'fa fa-star-o';
	}elseif($z - 0.5 <= $score){
	    $rate = 'fa fa-star-half-full';
	}elseif($z > $score){
	    $rate = 'fa fa-star-o';
	}else $rate = 'fa fa-star-o';
	$star .= '<i title="'.$label[$i].'" class="'.$rate.'" data="'.$z.'" rel="'.$rate.'"></i>';
	}
	return $star;
}
function bad_words($str) {

	$chars = array('địt','Địt','ĐỊT','đéo','Đéo','ĐÉO','lồn','Lồn','LỒN','cặc','Cặc','CẶC','dái','Dái','DÁI','chó','Chó','CHÓ','Cứt','cứt','CỨT','ỉa','Ỉa','đái','Đái','ỈA','lon','nhu lon','dit','djt','dis','địt me','dit me','djt me','dis me','l0n','loz me','loz','lozz','lonn','loon','lôz','l0z');

	foreach ($chars as $key => $arr)

		$str = preg_replace( "/(^|\b)".$arr."(\b|!|\?|\.|,|$)/i", "8-x", $str ); 

		$str = wordwrap($str, 23, " ", true);

		$str = str_replace('<','&lt;',$str);

		$str = check_str($str);

	return $str;

}
function queryspecail($str){
	global $typeqs;
	    if ($typeqs ==1) $str = str_replace("'","\'",$str);	//Linux
    return $str;
}
function check_str($str) {
    $str2 = explode(' ',$str);
	$count = count($str2);
	for ($i= 0; $i < $count ; $i++){
	   if(strlen($str2[$i]) < 10){
	       $result .= $str2[$i].' ';
	       continue;
	   }
	   $str3 = substr($str2[$i],0,10);
	   $result .= $str3.' ';
	}
	return $result;
}
function resetTop(){
    global $mysql,$tb_prefix;
#######################################
# SET TOP OF DAY
#######################################
    $day = date('d',NOW);
    $current_day = get_data('cf_current_day','config','cf_id',1);
    if ($day != $current_day) {
	    $mysql->query("UPDATE ".$tb_prefix."film SET film_viewed_day = 0");
            $mysql->query("UPDATE ".$tb_prefix."video SET video_viewed_day = 0");
	    $mysql->query("UPDATE ".$tb_prefix."config SET cf_current_day = ".$day." WHERE cf_id = 1");
    }
    $week = date('W',NOW);
    $current_week = get_data('cf_current_w','config','cf_id',1);
    if ($week != $current_week) {
	    $mysql->query("UPDATE ".$tb_prefix."film SET film_viewed_w = 0");
            $mysql->query("UPDATE ".$tb_prefix."video SET video_viewed_week = 0");
	    $mysql->query("UPDATE ".$tb_prefix."config SET cf_current_w = ".$week." WHERE cf_id = 1");
    }
    $month = date('m',NOW);
    $current_month = get_data('cf_current_m','config','cf_id',1);
    if ($month != $current_month) {
	    $mysql->query("UPDATE ".$tb_prefix."film SET film_viewed_m = 0");
            $mysql->query("UPDATE ".$tb_prefix."video SET video_viewed_month = 0");
	    $mysql->query("UPDATE ".$tb_prefix."config SET cf_current_m = ".$month." WHERE cf_id = 1");
    }
    return false;
}
function text_preg_replace($text){
     $tx = htmlchars(stripslashes(urldecode(injection(trim($text)))));
		$tx = str_replace('   ',' ',$tx);
		$tx = str_replace('  ',' ',$tx);
return $tx;
}
function is_mobile(){	
    if(isset($_SERVER['HTTP_X_WAP_PROFILE']))
        return true;
	if(preg_match('/wap.|.wap/i',$_SERVER['HTTP_ACCEPT']))
        return true;
        
    if(isset($_SERVER['HTTP_USER_AGENT']))
    {
        $user_agents = array(
            'midp', 'j2me', 'avantg', 'docomo', 'novarra', 'palmos', 
            'palmsource', '240x320', 'opwv', 'chtml', 'pda', 
            'mmp\/', 'blackberry', 'mib\/', 'symbian', 'wireless', 'nokia', 
            'cdm', 'up.b', 'audio', 'SIE-', 'SEC-', 
            'samsung', 'mot-', 'mitsu', 'sagem', 'sony', 'alcatel', 
            'lg', 'erics', 'vx', 'NEC', 'philips', 'mmm', 'xx', 'panasonic', 
            'sharp', 'wap', 'sch', 'rover', 'pocket', 'benq', 'java', 'pt', 
            'pg', 'vox', 'amoi', 'bird', 'compal', 'kg', 'voda', 'sany', 
            'kdd', 'dbt', 'sendo', 'sgh', 'gradi', 'jb', 'dddi', 'moto', 'ipad', 'iphone', 'Opera Mobi', 'android'
        );
        $user_agents = implode('|', $user_agents);
        if (preg_match("/$user_agents/i", $_SERVER['HTTP_USER_AGENT']))
            return true;
    }
    
    return false;
}
function playTech_check($url){
	if(strpos($url , 'kisscartoon.me') !== false){
	    $playTech = 'flash';
    }elseif(strpos($url , 'picasaweb.google.com') !== false){
	    $playTech = 'flash';
	}elseif(strpos($url , 'plus.google.com') !== false){
	    $playTech = 'flash';
	}elseif(strpos($url , '://drive.google.com/') !== false || strpos($url , '://docs.google.com/') !== false){
	    $playTech = 'gkphp';
	}elseif(strpos($url , 'photos.google.com') !== false){
	    $playTech = 'flash';
	}elseif(strpos($url , 'clip.vn') !== false){
	    $playTech = 'html5';
	}elseif(strpos($url , 'tv.zing.vn/video') !== false){
	    $playTech = 'gkphp';
	}elseif(strpos($url , 'tv.zing.vn/episode') !== false){
	    $playTech = 'gkphp';
	}elseif(strpos($url , 'xvideos.com') !== false){
	    $playTech = 'gkphp';
	}elseif(strpos($url , 'phimvipvn.net') !== false || strpos($url , 'phimchon.com') !== false || strpos($url , '123movies.to') !== false){
	    $playTech = 'flash';
	}elseif(strpos($url , 'dailymotion.com') !== false){
	    $playTech = 'iframe';
	}elseif(strpos($url , 'userscloud.com') !== false){
	    $playTech = 'flash';
	}elseif(strpos($url , 'youtube.com') !== false){
	    $playTech = 'flash';
	}elseif(strpos($url , 'megabox.vn') !== false){
	    $playTech = 'flashv1';
	}elseif(strpos($url , 'sv3.localhost') !== false){
	    $playTech = 'flash';
	}elseif(strpos($url , '|') !== false){
	    $playTech = 'flash';
	}else{
	    $playTech = 'flash';
	}
	return $playTech;
}
function dvd_is_stop_query($q){ //hàm kiểm tra trong từ khóa tìm kiếm có stop word hay không?
    $stop_words = array("a's","able","about","above","according","accordingly", "across", "actually", "after", "afterwards","again", "against", "ain't", "all", "allow","allows", "almost", "alone", "along", "already","also", "although", "always", "am", "among","amongst", "an", "and", "another", "any","anybody", "anyhow", "anyone", "anything", "anyway","anyways", "anywhere", "apart", "appear", "appreciate","appropriate", "are", "aren't", "around", "as","aside", "ask", "asking", "associated", "at","available", "away", "awfully", "be", "became","because", "become", "becomes", "becoming", "been","before", "beforehand", "behind", "being", "believe","below", "beside", "besides", "best", "better","between", "beyond", "both", "brief", "but","by", "c'mon", "c's", "came", "can","can't", "cannot", "cant", "cause", "causes","certain", "certainly", "changes", "clearly", "co","com", "come", "comes", "concerning", "consequently","consider", "considering", "contain", "containing", "contains","corresponding", "could", "couldn't", "course", "currently","definitely", "described", "despite", "did", "didn't","different", "do", "does", "doesn't", "doing","don't", "done", "down", "downwards", "during","each", "edu", "eg", "eight", "either","else", "elsewhere", "enough", "entirely", "especially","et", "etc", "even", "ever", "every","everybody", "everyone", "everything", "everywhere", "ex","exactly", "example", "except", "far", "few","fifth", "first", "five", "followed", "following","follows", "for", "former", "formerly", "forth","four", "from", "further", "furthermore", "get","gets", "getting", "given", "gives", "go","goes", "going", "gone", "got", "gotten","greetings", "had", "hadn't", "happens", "hardly","has", "hasn't", "have", "haven't", "having","he", "he's", "hello", "help", "hence","her", "here", "here's", "hereafter", "hereby","herein", "hereupon", "hers", "herself", "hi","him", "himself", "his", "hither", "hopefully","how", "howbeit", "however", "i'd", "i'll","i'm", "i've", "ie", "if", "ignored","immediate", "in", "inasmuch", "inc", "indeed","indicate", "indicated", "indicates", "inner", "insofar","instead", "into", "inward", "is", "isn't","it", "it'd", "it'll", "it's", "its","itself", "just", "keep", "keeps", "kept","know", "known", "knows", "last", "lately","later", "latter", "latterly", "least", "less","lest", "let", "let's", "like", "liked","likely", "little", "look", "looking", "looks","ltd", "mainly", "many", "may", "maybe","me", "mean", "meanwhile", "merely", "might","more", "moreover", "most", "mostly", "much","must", "my", "myself", "name", "namely","nd", "near", "nearly", "necessary", "need","needs", "neither", "never", "nevertheless", "new","next", "nine", "no", "nobody", "non","none", "noone", "nor", "normally", "not","nothing", "novel", "now", "nowhere", "obviously","of", "off", "often", "oh", "ok","okay", "old", "on", "once", "one","ones", "only", "onto", "or", "other","others", "otherwise", "ought", "our", "ours","ourselves", "out", "outside", "over", "overall","own", "particular", "particularly", "per", "perhaps","placed", "please", "plus", "possible", "presumably","probably", "provides", "que", "quite", "qv","rather", "rd", "re", "really", "reasonably","regarding", "regardless", "regards", "relatively", "respectively","right", "said", "same", "saw", "say","saying", "says", "second", "secondly", "see","seeing", "seem", "seemed", "seeming", "seems","seen", "self", "selves", "sensible", "sent","serious", "seriously", "seven", "several", "shall","she", "should", "shouldn't", "since", "six","so", "some", "somebody", "somehow", "someone","something", "sometime", "sometimes", "somewhat", "somewhere","soon", "sorry", "specified", "specify", "specifying","still", "sub", "such", "sup", "sure","t's", "take", "taken", "tell", "tends","th", "than", "thank", "thanks", "thanx","that", "that's", "thats", "the", "their","theirs", "them", "themselves", "then", "thence","there", "there's", "thereafter", "thereby", "therefore","therein", "theres", "thereupon", "these", "they","they'd", "they'll", "they're", "they've", "think","third", "this", "thorough", "thoroughly", "those","though", "three", "through", "throughout", "thru","thus", "to", "together", "too", "took","toward", "towards", "tried", "tries", "truly","try", "trying", "twice", "two", "un","under", "unfortunately", "unless", "unlikely", "until","unto", "up", "upon", "us", "use","used", "useful", "uses", "using", "usually","value", "various", "very", "via", "viz","vs", "want", "wants", "was", "wasn't","way", "we", "we'd", "we'll", "we're","we've", "welcome", "well", "went", "were","weren't", "what", "what's", "whatever", "when","whence", "whenever", "where", "where's", "whereafter","whereas", "whereby", "wherein", "whereupon", "wherever","whether", "which", "while", "whither", "who","who's", "whoever", "whole", "whom", "whose","why", "will", "willing", "wish", "with","within", "without", "won't", "wonder", "would","wouldn't", "yes", "yet", "you", "you'd","you'll", "you're", "you've", "your", "yours","yourself", "yourselves", "zero");
    $ar = explode(' ', strtolower($q)); //cắt chuỗi tìm kiếm thành các từ và kiểm tra
    foreach($ar as $w){
        $w = trim($w);
		if (empty($w))
            continue;
        if (!in_array($w, $stop_words))
        return false;
    }
return true;
}
function search_stop_query($q){ 
    $ar = explode(' ', strtolower($q));
    foreach($ar as $w){
        $w = trim($w);
		if (empty($w) || strlen($w) < 4)
            return true;     
		else continue;	
    }
return false;
}
function text_preg_replace_search($text){
        $tx = htmlchars(stripslashes(urldecode(injection(trim($text)))));
		$tx = str_replace('   ',' ',$tx);
		$tx = str_replace('  ',' ',$tx);
return $tx;
}
function upperFirstChar($t)
{
    $fChar=mb_substr($t,0,1,'UTF-8');
    $fCharReplace=mb_convert_case($fChar,MB_CASE_UPPER,'UTF-8');
    $c=1;
    $t=str_replace($fChar,$fCharReplace,$t,$c);
    return $t;
}
?>