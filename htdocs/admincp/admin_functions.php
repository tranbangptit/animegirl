<?php 
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");

function un_htmlchars($str) {

	return str_replace(array('&lt;', '&gt;', '&quot;', '&amp;', '&#92;', '&#39'), array('<', '>', '"', '&', chr(92), chr(39)), $str );

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
function covtime($youtube_time){
    $start = new DateTime('@0'); // Unix epoch
    $start->add(new DateInterval($youtube_time));
    if (strlen($youtube_time)>8)
    {
    return $start->format('g:i:s');
}   else {
	return $start->format('i:s');
}
}
function getDuration_ytb($id){
    $dur = xem_web('https://www.googleapis.com/youtube/v3/videos?id='.$id.'&key='.GOOGLE_API.'&part=contentDetails');
	$VidDuration = json_decode($dur, true); 
	foreach ($VidDuration['items'] as $vidTime) {
    $Vid = $vidTime['contentDetails']['duration'];
    }
    return covtime($Vid);
}
function htmlchars($str) {

	return str_replace(

		array('&', '<', '>', '"', chr(92), chr(39)),

		array('&amp;', '&lt;', '&gt;', '&quot;', '&#92;', '&#39'),

		$str

	);

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
function thumbimg($url,$size = 0){
    if(strpos($url, 'imgur.com') !== false){
        if(strpos($url, '.jpg') !== false){
            $u = str_replace('.jpg','m.jpg',$url);
        }elseif(strpos($url, '.png') !== false){
            $u = str_replace('.png','m.png',$url);
        }
    }elseif(strpos($url, 'googleusercontent.com') !== false){
        $u = str_replace('/s0/','/s'.$size.'/',$url);
    }else $u = $url;
    return $u;
}
function admin_viewpages($ttrow,$n,$pg){
global $link;
$div=ceil($ttrow/$n);
$link = preg_replace("#&pg=([0-9]{1,})#si","",$link);
$html = false;
$pgt = $pg-1;
if ($pg<>1) $html.='<li><a href="'.$link.'" onfocus="this.blur()"><i class="fa fa-chevron-left"></i><i class="fa fa-chevron-left"></i></a></li><li><a href="'.$link.'&pg='.$pgt.'" onfocus="this.blur()"><i class="fa fa-chevron-left"></i></a></li>';
	for($l = 0; $l < $div; $l++) {
		if ($l < $pg - 3 || $l > $pg + 2) 
		continue;
		$m = $l+1;
		if($m == $pg) $html .= '<li class="active"><a onfocus="this.blur()">'.$m.'</a></li>';
		else $html .= '<li><a onfocus="this.blur()" href="'.$link.'&pg='.$m.'">'.$m.'</a></li>';
	}
	$pgs = $pg+1;
	
	if ($pg<>$m) $html.='<li><a href="'.$link.'&pg='.$pgs.'"><i class="fa fa-chevron-right"></i></a></li><li><a href="'.$link.'&pg='.$m.'" onfocus="this.blur()"><i class="fa fa-chevron-right"></i><i class="fa fa-chevron-right"></i></a></li>';

	$html.="</td></tr></table>";
return $html;
}
function injection($str) {

	$chars = array('chr(', 'chr=', 'chr%20', '%20chr', 'wget%20', '%20wget', 'wget(','cmd=', '%20cmd', 'cmd%20', 'rush=', '%20rush', 'rush%20', 'union%20', '%20union', 'union(', 'union=', 'echr(', '%20echr', 'echr%20', 'echr=', 'esystem(', 'esystem%20', 'cp%20', '%20cp', 'cp(', 'mdir%20', '%20mdir', 'mdir(', 'mcd%20', 'mrd%20', 'rm%20', '%20mcd', '%20mrd', '%20rm', 'mcd(', 'mrd(', 'rm(', 'mcd=', 'mrd=', 'mv%20', 'rmdir%20', 'mv(', 'rmdir(', 'chmod(', 'chmod%20', '%20chmod', 'chmod(', 'chmod=', 'chown%20', 'chgrp%20', 'chown(', 'chgrp(', 'locate%20', 'grep%20', 'locate(', 'grep(', 'diff%20', 'kill%20', 'kill(', 'killall', 'passwd%20', '%20passwd', 'passwd(', 'telnet%20', 'vi(', 'vi%20', 'insert%20into', 'select%20', 'nigga(', '%20nigga', 'nigga%20', 'fopen', 'fwrite', '%20like', 'like%20', '$_request', '$_get', '$request', '$get', '.system', 'HTTP_PHP', '&aim', '%20getenv', 'getenv%20', 'new_password', '&icq','/etc/password','/etc/shadow', '/etc/groups', '/etc/gshadow', 'HTTP_USER_AGENT', 'HTTP_HOST', '/bin/ps', 'wget%20', 'uname\x20-a', '/usr/bin/id', '/bin/echo', '/bin/kill', '/bin/', '/chgrp', '/chown', '/usr/bin', 'g\+\+', 'bin/python', 'bin/tclsh', 'bin/nasm', 'perl%20', 'traceroute%20', 'ping%20', '.pl', '/usr/X11R6/bin/xterm', 'lsof%20', '/bin/mail', '.conf', 'motd%20', 'HTTP/1.', '.inc.php', 'config.php', 'cgi-', '.eml', 'file\://', 'window.open', '<SCRIPT>', 'javascript\://','img src', 'img%20src','.jsp','ftp.exe', 'xp_enumdsn', 'xp_availablemedia', 'xp_filelist', 'xp_cmdshell', 'nc.exe', '.htpasswd', 'servlet', '/etc/passwd', 'wwwacl', '~root', '~ftp', '.js', '.jsp', 'admin_', '.history', 'bash_history', '.bash_history', '~nobody', 'server-info', 'server-status', 'reboot%20', 'halt%20', 'powerdown%20', '/home/ftp', '/home/www', 'secure_site, ok', 'chunked', 'org.apache', '/servlet/con', '<script', '/robot.txt' ,'/perl' ,'mod_gzip_status', 'db_mysql.inc', '.inc', 'select%20from', 'select from', 'drop%20', '.system', 'getenv', 'http_', '_php', 'php_', 'phpinfo()', '<?php', '?>', 'sql=','\'');

	foreach ($chars as $key => $arr)

		$str = str_replace($arr, '*', $str); 

	return $str;



}
function sqlescape2($value)
  {
      
      $value = trim(htmlchars(stripslashes(urldecode(($value)))));
    return $value;  
          
}
function sqlescape($value)
  {
      
      $value = trim(htmlchars(stripslashes(urldecode(injection($value)))));
    return $value;  
          
}
function uplaidate($uplaidate) {
	$html = "<input type=\"radio\" value=\"1\" checked name=\"uplaidate\"> Ko Up
			<input type=\"radio\" value=\"2\" name=\"uplaidate\"> Up Lại";
	return $html;
}
function mailchecksend($uplaidate) {
	$html = "<input type=\"radio\" value=\"0\" checked name=\"mailchecksend\"> Ko Send
			<input type=\"radio\" value=\"1\" name=\"mailchecksend\"> Send";
	return $html;
}
function cat_show($id=0) {

	$html = '<select name="cat_type" class="form-control m-b" onchange="load_again(this.value)">';	

if($id == 0){ $slted = 'selected';$slted1 = '';} else{ $slted = '';$slted1 = 'selected';}
$html .= '<option value="0" '.$slted.'>Show</option>';	
$html .= '<option value="1" '.$slted1.'>Không Show</option>';	
	$html .= '</select>';	
	return $html;
}
function cat_child_of($id=1) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_child = 0 ORDER BY cat_order ASC");
	$html = '<select name="cat_child" class="form-control m-b" onchange="load_again(this.value)">';	
$html .= '<option value="0">0 - CẤP 1</option>';	
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
if($r['cat_id'] == $id) $slted = 'selected'; else $slted = '';
$html .= '<option value="'.$r['cat_id'].'" '.$slted.'>'.$r['cat_id'].' - '.$r['cat_name'].'</option>';	

	}

	$html .= '</select>';	
	return $html;
}
function cat_video_show() {
	global $mysql,$tb_prefix;
$cat_video_id = get_data("cat_id","cat","cat_name_key","video-clip");
	$q = $mysql->query("SELECT cat_id,cat_name FROM ".$tb_prefix."cat WHERE cat_child = ".$cat_video_id." AND cat_type = 0 ORDER BY cat_order ASC");
	$html = '';	
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {

$html .= '<option value="'.$r['cat_id'].'">'.$r['cat_name'].'</option>';	

	}

	
	return $html;
}
function cat_video_show_id($id,$z) {
	global $mysql,$tb_prefix;
$cat_video_id = get_data("cat_id","cat","cat_name_key","video-clip");
	$q = $mysql->query("SELECT cat_id,cat_name FROM ".$tb_prefix."cat WHERE cat_child = ".$cat_video_id." AND cat_type = 0 ORDER BY cat_order ASC");
	$html = '<select name="video-cat['.$z.']" class="form-control m-b" onchange="load_again(this.value)">';	
        while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
if($r['cat_id'] == $id) $slted = 'selected'; else $slted = '';
$html .= '<option value="'.$r['cat_id'].'" '.$slted.'>'.$r['cat_name'].'</option>';	

	}

	$html .= '</select>';	
	return $html;
}
function acp_cat_video($id = 0, $add = false) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_child = 121 AND cat_type = 0 ORDER BY cat_order ASC");
	$cat=explode(',',$id);
	$num = count($cat);
	$html="<table><tbody><tr><td>";
	$is = 0;
	while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
		for ($i=1; $i<$num;$i++) if ($cat[$i]==$r['cat_id']) $checked='checked="checked"';
		if ($is>3){ $html.="</td><td>";$is=0;}
		$html .= '<input type="checkbox" id="selectcat" name="selectcat[]" value="'.$r['cat_id'].'" '.$checked.'> '.$r['cat_name']."<br/>";
		$checked="";
		$is++;
		}
	$html .="</td><tr></tbody></table>";
	return $html;
}
function acp_cat($id = 0, $add = false) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_child = 0 AND cat_type = 0 ORDER BY cat_order ASC");
	$cat=explode(',',$id);
	$num = count($cat);
	$html="<table><tbody><tr><td>";
	$is = 0;
	while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
		for ($i=1; $i<$num;$i++) if ($cat[$i]==$r['cat_id']) $checked='checked="checked"';
		if ($is>3){ $html.="</td><td>";$is=0;}
		$html .= '<input type="checkbox" id="selectcat" name="selectcat[]" value="'.$r['cat_id'].'" '.$checked.'> '.$r['cat_name']."<br/>";
		$checked="";
		$is++;
		}
	$html .="</td><tr></tbody></table>";
	return $html;
}
function acp_country($id = 0, $add = false) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."country ORDER BY country_order ASC");
	$cat=explode(',',$id);
	$num=count($cat);
	$html="<table><tbody><tr><td>";
	$is = 0;
	while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
		for ($i=0; $i<$num;$i++) if ($cat[$i]==$r['country_id']) $checked='checked="checked"';
		if ($is>3){ $html.="</td><td>";$is=0;}
		$html .= '<input type="checkbox" id="selectcountry" name="selectcountry[]" value="'.$r['country_id'].'" '.$checked.'> '.$r['country_name']."<br/>";
		$checked="";
		$is++;
		}
	$html .="</td><tr></tbody></table>";
	return $html;
}
function ads_close($id=0) {
	$html = '<select name="ads_close" class="form-control m-b" onchange="load_again(this.value)">';	
	if($id == 0) $slted = 'selected'; else $slted = '';
	if($id == 1) $slted1 = 'selected'; else $slted1 = '';
	$html .= '<option value="0" '.$slted.'>Bật</option>';	
		$html .= '<option value="1" '.$slted1.'>Tắt</option>';	
	$html .= '</select>';	
	return $html;
}
function ads_mobile($id=0) {
	$html = '<select name="ads_mobile" class="form-control m-b" onchange="load_again(this.value)">';	
	if($id == 0) $slted = 'selected'; else $slted = '';
	if($id == 1) $slted1 = 'selected'; else $slted1 = '';
	$html .= '<option value="0" '.$slted.'>Bật</option>';	
		$html .= '<option value="1" '.$slted1.'>Tắt</option>';	
	$html .= '</select>';	
	return $html;
}
function ads_login($id=0) {
	$html = '<select name="ads_login" class="form-control m-b" onchange="load_again(this.value)">';	
	if($id == 0) $slted = 'selected'; else $slted = '';
	if($id == 1) $slted1 = 'selected'; else $slted1 = '';
	$html .= '<option value="0" '.$slted.'>Bật</option>';	
		$html .= '<option value="1" '.$slted1.'>Tắt</option>';	
	$html .= '</select>';	
	return $html;
}
function set_type($file_type=1) {
	global $web_server;	
        $web_server1 = str_replace("[+]","|",$web_server);
	$ServerArray = explode('|',$web_server1);
	$html = '<select name="file_type" class="form-control m-b" onchange="load_again(this.value)">';	
	for($i=0;$i<count($ServerArray);$i++){		
	$z = $i + 1;		 
	if($file_type == $z) $slted = 'selected'; else $slted = '';	
	$html .= '<option value="'.$z.'" '.$slted.'>'.$z.' - '.$ServerArray[$i].'</option>';	
	}	
	$html .= '</select>';	
	return $html;
}
function trang_thai($trang_thai = 1) {
	$html = "<select name=film_lang class='form-control m-b'>".
		"<option value=1".(($trang_thai==1)?' selected':'').">Việtsub</option>".
		        "<option value=2".(($trang_thai==2)?' selected':'').">Thuyết minh</option>".
		        "<option value=3".(($trang_thai==3)?' selected':'').">Lồng tiếng</option>".
		        "<option value=4".(($trang_thai==4)?' selected':'').">VIETSUB + TM</option>".
		        "<option value=5".(($trang_thai==5)?' selected':'').">NoSUB</option>".
		        "<option value=6".(($trang_thai==6)?' selected':'').">EngSUB</option>".
		"</select>";
	return $html;
}
function film_lb($trang_thai) {
	$html = "<select name=film_lb class='form-control m-b'>".
		"<option value=0".(($trang_thai==0)?' selected':'').">Phim lẻ</option>".
		        "<option value=1".(($trang_thai==1)?' selected':'').">Phim bộ hoàn thành</option>".
		        "<option value=2".(($trang_thai==2)?' selected':'').">Phim bộ chưa hoàn thành</option>".
		        "<option value=3".(($trang_thai==3)?' selected':'').">Phim sắp chiếu</option>".
                        "<option value=4".(($trang_thai==4)?' selected':'').">TV Show</option>".
		"</select>";
	return $html;
}
function phimrap($trang_thai) {
	$html = "<select name=film_rap class='form-control m-b'>".
		"<option value=0".(($trang_thai==0)?' selected':'').">Không chiếu rạp</option>".
		        "<option value=1".(($trang_thai==1)?' selected':'').">Chiếu rạp</option>".

		"</select>";
	return $html;
}
function phimkinhdien($trang_thai) {
	$html = "<select name=film_kinhdien class='form-control m-b'>".
		"<option value=0".(($trang_thai==0)?' selected':'').">Không Kinh điển</option>".
		        "<option value=1".(($trang_thai==1)?' selected':'').">Kinh điển</option>".

		"</select>";
	return $html;
}
function phimtvshow($trang_thai) {
	$html = "<select name=film_tvshow class='form-control m-b'>".
		"<option value=0".(($trang_thai==0)?' selected':'').">Không TV-SHOW</option>".
		        "<option value=1".(($trang_thai==1)?' selected':'').">TV-SHOW</option>".

		"</select>";
	return $html;
}
function phimhot($trang_thai) {
	$html = "<select name=film_hot class='form-control m-b'>".
		"<option value=0".(($trang_thai==0)?' selected':'').">Không đề cử</option>".
		        "<option value=1".(($trang_thai==1)?' selected':'').">Đề cử</option>".

		"</select>";
	return $html;
}
function phim18($trang_thai) {
	$html = "<select name=film_18 class='form-control m-b'>".
		"<option value=0".(($trang_thai==0)?' selected':'').">Không 18+</option>".
		        "<option value=1".(($trang_thai==1)?' selected':'').">Phim 18+</option>".

		"</select>";
	return $html;
}
function join_value($str){
	$num=count($str);
	$max=$num-1;
	$string ="";
	for ($i=0; $i<$num;$i++){
		$string .=$str[$i].',';
		
	}
return $string;
}
function acp_film($id = 0, $add = false) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."film ORDER BY film_name_ascii ASC");
	$html = "<select name=film class='form-control m-b'>";
	if ($add) $html .= "<option value=dont_edit".(($id == 0)?" selected":'').">Không sửa</option>";
	while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
		$html .= "<option value=".$r['film_id'].(($id == $r['film_id'])?" selected":'').">".$r['film_name']."</option>";
	}
	$html .= "</select>";
	return $html;
}
function acp_film_ep_slt($file_type){
	    global $web_server;		
                $Ser = str_replace("[+]","|",$web_server);
		$ServerArray = explode('|',$Ser);	
		$html = '<select name="server_ep_slt" class="form-control m-b">';	
		for($i=0;$i<count($ServerArray);$i++){		
     		$z = $i + 1;		  
			
			$html .= '<option value="'.$z.'">'.$z.' - '.$ServerArray[$i].'</option>';
		}		
		$html .= '</select>';	
		return $html;
}
function acp_film_ep($file_type,$x){
	    global $web_server;		
		$Ser = str_replace("[+]","|",$web_server);
		$ServerArray = explode('|',$Ser);	
		$html = '<select name="server_ep['.$x.']" class="form-control m-b">';	
		for($i=0;$i<count($ServerArray);$i++){		
     		$z = $i + 1;		  
			if($file_type == $z) $slted = 'selected'; else $slted = '';
			$html .= '<option value="'.$z.'" '.$slted.'>'.$z.' - '.$ServerArray[$i].'</option>';
		}		
		$html .= '</select>';	
		return $html;
}
function acp_cache_key(){
	    global $web_cache_key;		
		$ServerArray = explode('|',$web_cache_key);	
		$html = '<select name="cache_key" class="form-control m-b">';	
		for($i=0;$i<count($ServerArray);$i++){		
			$html .= '<option value="'.$ServerArray[$i].'">'.$ServerArray[$i].'</option>';
		}		
		$html .= '</select>';	
		return $html;
}
function acp_text_type($type){
    global $web_server;	
        $sev = str_replace('[+]','|',$web_server);
	$ServerArray = explode('|',$sev);
	$text = $ServerArray[$type-1];
	return $text;
}
function replace($string) {

	$string = get_ascii($string);

    $string = preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),

        array('', '-', ''), htmlspecialchars_decode($string));

    return $string;

}
function xem_web($url) {
  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch, CURLOPT_URL, $url);
  curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/4.0 (compatible; MSIE 8.0; Windows NT 6.0)");
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
  curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
  curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  return $data;
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
function acp_quick_add_film2($new_film,$name_real,$tapphim,$actor,$year,$time,$area,$director,$cat,$info,$country,$file_type,$bo_le,$key,$des,$imgbn,$tag,$trang_thai,$imdb,$newepisode) {
	global $mysql, $tb_prefix,$mysqldb;
	$tt = get_total('film','film_id',"WHERE film_name_real = '".UNIstr($name_real)."'");
	if ($tt) {
		$film = false;
	}
	else {
		$mysql->query("UPDATE ".$tb_prefix."user SET user_point = user_point + 1 WHERE user_id = '".$_SESSION['admin_id']."'");
	    $sql = "INSERT INTO ".$tb_prefix."film (film_name,film_name_real,film_tapphim,film_name_ascii,film_actor,film_actor_ascii,film_year,film_time,film_area,film_director,film_director_ascii,film_cat,film_info,film_country,film_server,film_lb,film_key,film_des,film_imgbn,film_upload,film_tag,film_tag_ascii,film_trangthai,film_imdb,film_time_update,film_newepisode) VALUES ('".($new_film)."','".($name_real)."','".$tapphim."','".strtolower(get_ascii(($new_film)))."','".($actor)."','".strtolower(get_ascii(($actor)))."','".$year."','".$time."','".$area."','".($director)."','".strtolower(get_ascii(($director)))."',',".$cat."','".queryspecail($info)."',',".$country.",','".$file_type."','".$bo_le."','".$keyw."','".$des."','".$imgbn."','".$_SESSION['admin_id']."','".strtolower($tag)."','".strtolower(get_ascii($tag))."','".$trang_thai."','".$imdb."','".NOW."','".$newepisode."')";
		$mysqldb->query($sql); 
		
        $film = $mysqldb->lastInsertId();
	}
	return $film;
}
function acp_showtimeday($id = 1, $add = false) {
	$html="<table><tbody><tr><td>";
		for ($i=1; $i<=7;$i++){
		if(strpos($id , ','.$i.',') !== false) $checked='checked="checked"';
		if ($is>3){ $html.="</td><td>";$is=0;}
		$html .= '<input type="checkbox" id="selectstime" name="selectstime[]" value="'.$i.'" '.$checked.'> '.acp_showtime($i)."<br/>";
		$checked="";
		$is++;
		}
	$html .="</td><tr></tbody></table>";
	return $html;
}
function acp_showtime($file_type=1) {
		if($file_type == 1){$html = 'Monday';}
		elseif($file_type == 2){$html = 'Tuesday';}
		elseif($file_type == 3){$html = 'Wednesday';}
		elseif($file_type == 4){$html = 'Thursday';}
		elseif($file_type == 5){$html = 'Friday';}
		elseif($file_type == 6){$html = 'Saturday';}
		elseif($file_type == 7){$html = 'Sunday';}
	return $html;
}
function get_idyoutube($urls){
$url = explode('v=',$urls);
$url = explode('&',$url[1]);
$id = $url[0];
return $id;
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
function acp_user_ban($lv) {
	$html = "<select name=ban class='form-control m-b'>".
	    "<option value=0".(($lv==0)?' selected':'').">No</option>".
		"<option value=1".(($lv==1)?' selected':'').">Yes</option>".
	"</select>";
	return $html;
}
function acp_publish($lv) {
	$html = "<select name=film_publish class='form-control m-b'>".
	    "<option value=0".(($lv==0)?' selected':'').">Không vi phạm</option>".
		"<option value=1".(($lv==1)?' selected':'').">Vi phạm</option>".
	"</select>";
	return $html;
}
function acp_user_level($lv=0) {
	global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."user_level ORDER BY user_level_type ASC");
	$html = "<select name=level class='form-control m-b'>";
	while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
		$html .= "<option value=".$r['user_level_type'].(($lv == $r['user_level_type'])?" selected":'').">- ".$r['user_level_name']."</option>";
		}
	$html .= "</select>";
	return $html;
}
function acp_ads_type($id = 0) {
	$html = "<select name=ads_type class='form-control m-b'>".
	    "<option value=0".(($id==0)?' selected':'').">Text</option>".
		"<option value=1".(($id==1)?' selected':'').">Images</option>".
		"<option value=2".(($id==2)?' selected':'').">Flash</option>".
		"<option value=3".(($id==3)?' selected':'').">HTML CODE</option>".
	"</select>";
	return $html;
}
function acp_site_off($id = 0) {
	$html = "<select name=cf_site_off class='form-control m-b'>".
	    "<option value=0".(($id==0)?' selected':'').">Không</option>".
		"<option value=1".(($id==1)?' selected':'').">Có</option>".
	"</select>";
	return $html;
}
function acp_ads_pos($id = 1) {
    global $mysql,$tb_prefix;
	$q = $mysql->query("SELECT * FROM ".$tb_prefix."adspos ORDER BY adspos_pos ASC");
	$html = "<select name=pos class='form-control m-b'>";
	while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
	if($r['adspos_id'] == $id){$sle = 'selected';}else{$sle = '';}
	    $html .=  '<option value="'.$r['adspos_id'].'" '.$sle.'>'.$r['adspos_name'].'</option>';
    }
	$html .= "</select>";
	return $html;
}
function get_by_curl($url){
    $headers = array(
		"User-Agent: googlebot",
        "Content-Type: application/x-www-form-urlencoded",
		"Referer: ".$url,
        );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    curl_setopt($ch, CURLOPT_HEADER, 1);
    curl_setopt($ch, CURLOPT_REFERER, $referer);
    curl_setopt($ch, CURLOPT_COOKIE, $cookie );
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    if($var) {
    curl_setopt($ch, CURLOPT_POST, 1);        
    curl_setopt($ch, CURLOPT_POSTFIELDS, $var);
    }
    curl_setopt($ch, CURLOPT_URL,$url);

    return curl_exec($ch);
}
function queryspecail($str){
	global $typeqs;
	if ($typeqs ==1) $str = str_replace("'","\'",$str);	//Linux
return $str;
}
function Imgur_Upload($url,$type){
    global $ImageResize;
    define('DIR', dirname(__FILE__));
	$isWatermark = 1;

	        if(!preg_match('#^https?:\/\/(.*)\.(gif|png|jpg)$#i', $url)) die('image=Invalid Url');
            $dir_image_file = DIR . '/temp/'.basename($url);
			if(copy($url, $dir_image_file)){
				$ImageResize->load($dir_image_file);
                if($type == 1){ 
				$ImageResize->resize(450,600); 
				$logos = 'logo_p.png';
				}else{ 
				$ImageResize->resizeToWidth(960);
				$logos = 'logo_b.png';
				}
                $ImageResize->save($dir_image_file);
				if($isWatermark){
						$watermark_path = DIR . '/'.$logos;
						watermark($dir_image_file, $dir_image_file, $watermark_path);
						}
				$client_id = CLIENT_ID;
				$handle = fopen($dir_image_file, "r");
				$data = fread($handle, filesize($dir_image_file));
				$pvars   = array('image' => base64_encode($data));
				$timeout = 30;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
				curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $pvars);
				curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
				$out = curl_exec($curl);
				curl_close ($curl);
				$pms = json_decode($out,true);
				$url = $pms['data']['link'];
					if($url != ""){
						$X = $url;
					}else{
                        $X = 'http://www.phimle.tv/noimg.jpg';
					} 
				unlink($dir_image_file);
			return $X;
			}	
			
}
function random_element($array)
{
    if (is_array($array) && null !== $key = array_rand($array)) {
        return $array[$key];
    }

    return null;
}
function Picasa_Upload($UrlImg,$type){
    global $uploader,$config,$ImageResize;
	define('DIR', dirname(__FILE__));
	$tempfolder = DIR . '/temp/'; // CMOD 777
	$isWatermark = 1;
	if(!preg_match('#^https?:\/\/(.*)\.(gif|png|jpg)$#i', $UrlImg)) die('image=Invalid Url');
						while(stripos($UrlImg,'%')!==false){
							$UrlImg = rawurldecode($UrlImg);
						}
						$filePath = $tempfolder . basename($UrlImg);
						$imgk = @file_get_contents($UrlImg);
						$fk = fopen($filePath,"w");
						fwrite($fk,$imgk);
						fclose($fk);
	$ImageResize->load($filePath);
    if($type == 1){ 
	    $ImageResize->resize(450,600); 
		$logos = 'logo_p.png';
	}else{ 
            $ImageResize->resizeToWidth(960);
		$logos = 'logo_b.png';
	}

   $ImageResize->save($filePath);
   if($isWatermark){
						$watermark_path = DIR . '/'.$logos;
						watermark($filePath, $filePath, $watermark_path);
						}
	$data_server = 'picasanew';
    $server = strtolower($data_server);
    $uploader->useCurl($config['use_curl']);
    $uploader->setCache($config['cache_adapter']);

    $result = array();
    try {
        $serverConfig = $config[$server];
        switch ($server) {   
            case 'picasanew':

                $account = random_element($serverConfig['accounts']);
                $albumId = random_element($account['album_ids']);

                $uploader->login($account['username'], $account['password']);
				$uploader->setApi($serverConfig['API']['ID']);
				$uploader->setSecret($serverConfig['API']['secret']);
                $uploader->setAlbumId($albumId);
                break; 
        }
        // group cache identifier is made by plugin name, username
        // so we should call this after call login();
        $uploader->getCache()->garbageCollect();
        $url = $uploader->upload($filePath);
        $result = array(
            'error' => false,
            'url'   => $url,
        );
	
    } catch (Exception $e) {
        $result = array(
            'error'   => true,
            'message' => $e->getMessage()
        );
    }
	unlink($filePath);
    return $url;
}

?>