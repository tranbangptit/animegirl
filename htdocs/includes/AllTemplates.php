<?php 
if (!defined('TRUNKSJJ')) die("Hack");
include("downloads.php");
function ShowFilm($where,$order,$limit,$file,$keycache){
    global $mysql,$web_link,$CurrentSkin,$language,$phpFastCache;
	$keycache = 'phimletv-'.$keycache;
	$data_cache = $phpFastCache->get($keycache);//Kiểm tra xem link truyền vào đã cache chưa
	if($data_cache != null && ($keycache != 'phimletv-')){
	    $html = '<!---Use Cache '.$keycache.'---->'.$data_cache.'<!---/End Use Cache '.$keycache.'---->'; 
	}else{
	
    $arr = $mysql->query("SELECT film_id,film_name_real,film_name,film_trangthai,film_time,film_lang,film_img,film_imgbn,film_tapphim,film_viewed,film_lb,film_rate,film_rating_total,film_year,film_slug,film_imgkinhdien FROM ".DATABASE_FX."film $where $order DESC LIMIT $limit");
	$html = '';
	$z = 0;
	while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	++$z;
	$filmID = $row['film_id'];
	$filmNAMEEN = $row['film_name_real'];
	$filmNAMEVN = $row['film_name'];
	$filmSTATUS = str_replace('Hoàn tất','Full',$row['film_trangthai']);
	$filmQUALITY = $row['film_tapphim'];
	$filmIMGBN = $row['film_imgbn'];
        $filmIMGKD = $row['film_imgkinhdien'];
        if($filmIMGKD == '') $filmIMGSlide = $filmIMGBN; else $filmIMGSlide = $filmIMGKD;
	$filmYEAR = $row['film_year'];
	$filmIMG = thumbimg($row['film_img'],200);
	$filmTIME = $row['film_time'];
	if($row['film_rate'] != 0){
	if(round($row['film_rating_total']/$row['film_rate'],0) == 0) $filmRATE = 5; else $filmRATE = (5*round($row['film_rating_total']/$row['film_rate'],0))/10;
	}else $filmRATE = 5;
	$filmVIEWED = number_format($row['film_viewed']);
	$filmLANG = film_lang($row['film_lang']);
        $filmSLUG = $row['film_slug'];
	$filmURL = WEB_URL.'/phim/'.$filmSLUG.'-'.replace($filmID).'/';
	if($row['film_lb'] == 0){
	    $Status = $filmQUALITY.'-'.$filmLANG;
	}else{
	    $Status = $filmSTATUS.' '.$filmLANG;
	}
	    include("templates/".$CurrentSkin."/".$file.".php");
	}
	   if($html != '') $phpFastCache->set($keycache, $html, CACHED_TIME);
	}
	return $html;
}
function ShowRequest($where,$order,$limit,$file){
    global $mysql,$web_link,$CurrentSkin,$language;
	$arr = $mysql->query("SELECT * FROM ".DATABASE_FX."request $where $order DESC LIMIT $limit");
	$html = '';
	while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	$requestID = $row['request_id'];
	$requestNAME = $row['request_title'];
        $requestSLUG = $row['request_slug'];
	$requestContentz = $row['request_content'];
	$request_content = strip_tags(text_tidy1($requestContentz),'<a><b><i><u><img><br>');
	$requestContent = (textlink_site(replace_tag_a($request_content)));
        $rep = get_data("request_id","request","request_type",$requestID);
	if($rep) $reply = ShowRequestChild($requestID,10); else $reply = "";
	$requestTime = RemainTime($row['request_time']);
       $admin = get_data_multi("user_id","user","user_name = '".$requestNAME."' AND user_level = '3'");
        if($admin) $iscl = 'style="color:#ff0000;"'; else $iscl = '';
	    include("templates/".$CurrentSkin."/".$file.".php");
	}
        $html .= '<div style="text-align: center; font-weight: bold; padding: 5px; background:#121212;"><a title="Xem tất cả yêu cầu của thành viên" href="'.$web_link.'/asked.html">Xem tất cả</a></div>';
	return $html;
	
}
function ShowRequestChild($id,$limit){
    global $mysql,$web_link,$CurrentSkin,$language;
	$arr = $mysql->query("SELECT * FROM ".DATABASE_FX."request WHERE request_type = '".$id."' ORDER BY request_time DESC LIMIT $limit");
	
	$reply = '<span class="chat-reply" id="'.$id.'">[Xem trả lời]</span><div class="itemsq" style="display:none;" id="admin-reply-'.$id.'">';
	while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	$requestID = $row['request_id'];
	$requestNAME = $row['request_title'];
	$requestContentz = $row['request_content'];
        $requestSLUG = $row['request_slug'];
	$request_content = strip_tags(text_tidy1($requestContentz),'<a><b><i><u><img><br>');
	$requestContent = (textlink_site(replace_tag_a($request_content)));
	$requestTime = RemainTime($row['request_time']);
 $admin = get_data_multi("user_id","user","user_name = '".$requestNAME."' AND user_level = '3'");
if($admin) $iscl = 'isadmin'; else $iscl = '';
	    $reply .= '<div class="details '.$iscl.'"><div class="meta"><cite><span class="'.$iscl.'"><a href="'.$web_link.'/asked/'.$requestSLUG.'.html" id="request-'.$id.'">'.$requestNAME.'</a></span> says: </cite><time>'.$requestTime.'</time><tool id="'.$id.'" title="Trả lời bình luận của '.$requestNAME.'" rel="'.$requestNAME.'">Trả lời</tool></div><div class="message">'.$requestContent.'</div></div>';
	}
	$reply .= '</div>';
	
	return $reply;
}
function ShowRequestList($where,$order,$limit){
    global $mysql,$web_link,$CurrentSkin,$language;
	$arr = $mysql->query("SELECT * FROM ".DATABASE_FX."request $where $order DESC LIMIT $limit");
	$html = '';
	while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	$requestID = $row['request_id'];
	$requestNAME = $row['request_title'];
	$requestContentz = $row['request_content'];
        $requestSLUG = $row['request_slug'];
	$request_content = strip_tags(text_tidy1($requestContentz),'<a><b><i><u><img><br>');
	$requestContent = (textlink_site(replace_tag_a($request_content)));
        $rep = get_data("request_id","request","request_type",$requestID);
	if($rep) $reply = ShowRequestChild($requestID,10); else $reply = "";
        $admin = get_data_multi("user_id","user","user_name = '".$requestNAME."' AND user_level = '3'");
        if($admin) $iscl = 'style="color:#ff0000;"'; else $iscl = '';
	$requestTime = RemainTime($row['request_time']);
	$html .= '<div class="item"><div class="details"><div class="meta"><cite><span><a href="'.$web_link.'/asked/'.$requestSLUG.'.html" id="request-'.$requestID.'" '.$iscl.'>'.$requestNAME.'</a></span> says: </cite><time>'.$requestTime.'</time><tool id="'.$requestID.'" title="Trả lời bình luận của '.$requestNAME.'" rel="'.$requestNAME.'">Trả lời</tool></div><div class="message">'.$requestContent.'</div>'.$reply.'</div></div>';
	}
         $html .= '<div style="text-align: center; font-weight: bold; padding: 5px; background:#121212;"><a title="Xem tất cả yêu cầu của thành viên" href="'.$web_link.'/asked.html">Xem tất cả</a></div>';
	return $html;
	
}
function ShowVideo($where,$order,$limit,$file,$keycache){
    global $mysql,$web_link,$CurrentSkin,$language,$phpFastCache;
	$keycache = 'phimletv-'.$keycache;
	$data_cache = $phpFastCache->get($keycache);//Kiểm tra xem link truyền vào đã cache chưa
	if($data_cache != null && ($keycache != 'phimletv-')){
	    $html = '<!---Use Cache '.$keycache.'---->'.$data_cache.'<!---/End Use Cache '.$keycache.'---->'; 
	}else{
    $arr = $mysql->query("SELECT * FROM ".DATABASE_FX."video $where $order DESC LIMIT $limit");
	$html = '';
	while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	$videoID = $row['video_id'];
	$videoKEY = $row['video_key'];
	$videoNAME = $row['video_name'];
        $videoTIME = RemainTime($row['video_time']);
	$videoIMG = 'http://i.ytimg.com/vi/'.get_idyoutube($row['video_url']).'/mqdefault.jpg';
	$videoLINK = $videoURL = WEB_URL.'/xem-video/'.$videoKEY.'-'.$videoID.'.html';
	$videoVIEWED = number_format($row['video_viewed']);
        $videoPOSTER = $row['video_upload'];
        $videoDURATION = ($row['video_duration']);
	if(is_numeric($videoPOSTER)){ 
	$videoUploader = get_data("user_name","user","user_id",$videoPOSTER);
	     if(isset($videoUploader) && $videoUploader != '') $videoPOSTER = $videoUploader; else $videoPOSTER = $videoPOSTER;
	}
	else $videoPOSTER = $videoPOSTER;
	    include("templates/".$CurrentSkin."/".$file.".php");
	}
	 if($html != '') $phpFastCache->set($keycache, $html, CACHED_TIME);
	}
	return $html;
}
function ServerTotal(){
    global $web_server;
	$sev = str_replace("[+]","|",$web_server);
        $sev = explode('|',$sev);
	return count($sev);
}
function ServerNAME($id){
    global $web_server;
	$id = $id - 1;
	$sev = str_replace('[+]','|',$web_server);
        $sev = explode('|',$sev);
	return $sev[$id];
}
function EpisodeList($film_id,$episode_id,$episode_name,$server,$type=""){
    global $mysql,$web_link,$CurrentSkin,$language;
	$q = $mysql->query("SELECT episode_id,episode_name,episode_servertype,episode_url FROM ".DATABASE_FX."episode WHERE episode_film = ".$film_id." AND episode_servertype NOT IN (13,14) ORDER BY episode_id ASC");
	$filmSLUG = get_data('film_slug','film','film_id',$film_id);
    $sv = '';
	while ($r = $q->fetch(PDO::FETCH_ASSOC)){
        
	 	$episode_type = $r['episode_servertype'];    
		if($episode_type == 1){
		    if((strpos($r['episode_name'] , 'ull') !== false) || (strpos($r['episode_name'] , 'CAM') !== false)){
		        $link_seo = WEB_URL.'/phim/'.$filmSLUG.'-'.$film_id.'/xem-phim-'.$r['episode_id'].'.html';
		    }else{
			    if(is_numeric($r['episode_name']))
		        $link_seo = WEB_URL.'/phim/'.$filmSLUG.'-'.$film_id.'/tap-'.$r['episode_name'].'.html';
				else{
				$name = replace($r['episode_name']);
				$name = str_replace('-','',strtolower($name));
				$name = str_replace(' ','',strtolower($name));
				
				$link_seo = WEB_URL.'/phim/'.$filmSLUG.'-'.$film_id.'/tap-'.$name.'-'.$r['episode_id'].'.html';
			    }
			}
		}else{ 
		    if(is_numeric($r['episode_name']))
		        $name = $r['episode_name'];
				else{
				$name = replace($r['episode_name']);
				$name = str_replace('-','',strtolower($name));
				$name = str_replace(' ','',strtolower($name));
			    }
		    $link_seo = WEB_URL.'/phim/'.$filmSLUG.'-'.$film_id.'/tap-'.$name.'-'.$r['episode_id'].'.html';
		}
		if($r['episode_id'] == $episode_id){ 
		$claa = 'bg-purple-wisteria'; 
		$name = 'active';
		}else{ 
		$claa = 'bg-purple-soft';	
		$name = '';}
		
        if($type == "defaultv2")
		$sv[$episode_type] .= '<li><a href="'.$link_seo.'" title="Tập '.$r['episode_name'].'" id="'.$r['episode_id'].'" class="'.$name.'" data-play="'.playTech_check($r['episode_url']).'">'.$r['episode_name'].'</a></li>';
		else
        $sv[$episode_type] .= '<a class="btn btn-sm '.$claa.'" role="button" id="'.$r['episode_id'].'" href="'.$link_seo.'" title="'.$language['episode'].' '.$r['episode_name'].'" name="'.$name.'">'.$r['episode_name'].'</a>';
	}
	$TotalSV = ServerTotal();
	$html = '';
	for($i=1;$i<=$TotalSV;$i++){
	if (isset($sv[$i]))
	    if($type == "defaultv2")
		$html .= '<div class="name col-lg-3 col-md-3 col-sm-3"> <i class="fa fa-database"></i> '.ServerNAME($i).' </div><div class="episodes col-lg-9 col-md-9 col-sm-9"><ul>'.$sv[$i].'</ul></div><span style="clear: both;margin: 10px 0;display:block;"></span>';
		else
	    $html .= '<div class="epi"><div style="padding-top:8px;"><b>'.ServerNAME($i).':</b>'.$sv[$i].'<br/></div></div>';
	}
	return $html;
}
function View_pages_url($ext,$num,$rel){
    if($rel){
	    $url = $ext.'/trang-'.$num.'.html?rel='.$rel;
	}else 
	    $url = $ext.'/trang-'.$num.'.html';
    return $url;
}
function view_pages($type,$ttrow,$limit,$page,$ext,$rel,$skin=""){
    global $language;
	$total = ceil($ttrow/$limit);

	if ($total <= 1) return '';
    
$main = '';
	
    if ($page<>1){

	  if($type=='film') 
        if($skin = "defaultv2")
		$main .= '<span class="item"><a href="'.View_pages_url($ext,1,$rel).'" title="Trang 1">Đầu</a></span>';
        else 
		$main .= '<li><a title="'.$language['first'].'" href="'.View_pages_url($ext,1,$rel).'" data="1"><i class="fa fa-angle-left"></i></a></li>';
       
    }

	

	$main .= '';

	for($num = 1; $num <= $total; $num++){

		if ($num < $page - 1 || $num > $page + 4) 

		continue;

		if($num==$page) 
            if($skin = "defaultv2")
		       $main .= '<span class="current">'.$num.'</span>';
            else 
			$main .= '<li class="active"><a title="'.$language['page'].' '.$num.'" href="'.View_pages_url($ext,$num,$rel).'" data="'.$num.'">'.$num.'</a></li>'; 

        else { 

          if($type=='film') 
		   if($skin = "defaultv2")
		$main .= '<span class="item"><a href="'.View_pages_url($ext,$num,$rel).'" title="Trang '.$num.'">'.$num.'</a></span>';
        else 
		   $main .= '<li><a title="'.$language['page'].' '.$num.'" href="'.View_pages_url($ext,$num,$rel).'" data="'.$num.'">'.$num.'</a></li>'; 

          
       } 	

    }

   $main .= '';

    if ($page<>$total){

	    if($type=='film') 
		if($skin = "defaultv2")
		$main .= '<span class="item"><a href="'.View_pages_url($ext,$total,$rel).'" title="Trang cuối">Cuối</a></span>';
        else 
		$main .= '<li><a title="'.$language['last'].'" href="'.View_pages_url($ext,$total,$rel).'" data="'.$total.'"><i class="fa fa-angle-right"></i></a></li>';

        
    }

  return $main;

}
function checkLogin(){
	global $mysql;
	if (isset($_COOKIE['user_id'])) {

		$identifier = $_COOKIE['user_id'];

		$q = $mysql->query("SELECT user_identifier, user_id, user_name FROM ".DATABASE_FX."user WHERE user_id = '".$identifier."'");
        $user = $q->fetch(PDO::FETCH_ASSOC);
		if ($user['user_id']) {

			$_SESSION['user_id'] = $user['user_id'];

			$_SESSION['user_name'] = $user['user_name'];

			$return = true;

		}

		else $return = false;

	}else $return = false;

	return $return;

}
function playTech(){
    $isLogin = checkLogin();
    $isMobile = is_mobile();
    if($isMobile){
        $Tech = 'html5'; 
    }else{
        $Tech = 'auto'; 
    }
return $Tech;
}
function Logged(){
    $isLogin = checkLogin();
	if($isLogin){
	    $html = '<div class="logged">
		<div class="welcome">
                    <div class="btn-group"">
                        <button type="button" class="btn btn btn-bdown username btn-small">Xin chào, '.$_SESSION["user_name"].'</button>
                        <button type="button" class="btn btn btn-bdown dropdown-toggle btn-small" data-toggle="dropdown"><span class="caret"></span></button>
                            <ul class="dropdown-menu" role="menu">
                                 <li><a href="'.WEB_URL.'/account/info">Thông tin tài khoản</a></li>
                                 <li><a href="'.WEB_URL.'/account/password">Đổi mật khẩu</a></li>
                                 <li class="divider"></li>
                                 <li><a class="fxlink-logout" href="javascript:;" onclick="Logout();">Thoát</a></li>
                           </ul>
                   </div>
                   <a href="'.WEB_URL.'/account/film" type="button" class="btn btn-red btn-small">Tủ phim</a>
				</div>
			</div>';
	}else{
	    $html = '<div id="signing">
<div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" style="padding: 0px;">
     			<a href="/account/register" rel="nofollow" class="signup">Đăng ký</a>
	<a href="/account/login" rel="nofollow" class="signin">Đăng nhập</a> </div>
<div class="col-lg-4 col-md-4 col-sm-4 col-xs-4" style="padding: 0px;">
      <a class="btn btn-block facebookLogin" href="/account/login/facebook?tranfer='.getCurrentPageURL().'" rel="nofollow"><i class="fa fa-facebook"></i> Đăng nhập Fb</a></div>


			</div>';
	}
	return $html;
}
function isLogin(){
    global $language;
    $isLogin = checkLogin();
	if($isLogin){
	    $html = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" rel="nofollow">
                            <i class="fa fa-user" style="font-size:20px;color:#fff;"></i>
                            <span class="username username-hide-on-mobile" style="color:#FFF;">'.$_SESSION["user_name"].' </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                           <li><a href="'.WEB_URL.'/account/info" class="items" rel="nofollow"><i class="icon-user"></i> '.$language['account'].'</a></li><li><a href="'.WEB_URL.'/account/film" class="items" rel="nofollow"><i class="icon-film"></i> '.$language['filmbox'].'</a></li><li><a href="javascript:;" onclick="Logout();" rel="nofollow"><i class="icon-action-redo "></i>'.$language['logout'].'</a></li>
                        </ul>';
	}else{
	    $html = '<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true" rel="nofollow">
                            <i class="fa fa-user" style="font-size:20px;color:#fff;"></i>
                            <span class="username username-hide-on-mobile" style="color:#FFF;">'.$language['account2'].' </span>
                            <i class="fa fa-angle-down"></i>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-default">
                            <li>
                                <a href="javascript:void(0)" onclick="Login();" rel="nofollow">
                                    <i class="icon-lock"></i> '.$language['login'].' </a>
                            </li>
                                <a href="javascript:void(0)" onclick="Register();" rel="nofollow">
                            <li>
                                    <i class="icon-key"></i> '.$language['signup'].' </a>
                            </li>
                        </ul>';
	}
	return $html;
}
function showAds($pos_text){
    global $mysql;
    $isLogin = checkLogin();
    $isMobile = is_mobile();
    $pos = get_data("adspos_id","adspos","adspos_pos",$pos_text);
	$q = $mysql->query("SELECT ads_pos,ads_id,ads_embed,ads_mobile,ads_login FROM ".DATABASE_FX."ads WHERE ads_pos = '".$pos."' AND ads_close = 0");
    $ads = $q->fetch(PDO::FETCH_ASSOC);
	if($ads['ads_pos'] != ''){
	    if($ads['ads_login'] == 0){//-- Không tắt khi đăng nhập
		    if($ads['ads_mobile'] == 0){ //-- Không tắt trên mobile
			    $embed = $ads['ads_embed'];
			}else{
			    if($isMobile){
			        $embed = '';
			    }else{
			        $embed = $ads['ads_embed'];
			    }
			}        
		}else{
		    if($isLogin){
			    $embed = '';
			}else{
			    if($isMobile){
			        $embed = '';
			    }else{
			        $embed = $ads['ads_embed'];
			    }
			}
		} 		
	}else $embed = '';
    return un_htmlchars($embed);		
}

function showEpisodeDownload($filmId){
    global $mysql,$web_link,$CurrentSkin,$language,$web_server;
    $filmSLUG = get_data("film_slug","film","film_id",$filmId);
    $q = $mysql->query("SELECT episode_id,episode_name,episode_servertype,episode_url,episode_message FROM ".DATABASE_FX."episode WHERE episode_film = ".$filmId." AND episode_servertype NOT IN (15,16) ORDER BY episode_id ASC");
	while ($r = $q->fetch(PDO::FETCH_ASSOC)){    
        $episode_type = $r['episode_servertype'];    
	 	$episode_name = $r['episode_name'];    
	 	$episode_url = $r['episode_url'];    
	if(strpos($episode_url , '4share.vn') !== false || strpos($episode_url , 'fshare.vn') !== false)
        $episodeLink = '<a href="'.$episode_url.'" target="_blank"><i></i>&nbsp;Download Full</a>';
    elseif(strpos($episode_url , 'phim.megabox.vn') !== false){
        $episodeLink = "This episode does not support download!";
    }else{
        $episodeLink = '<a href="'.WEB_URL.'/cartoon/'.$filmSLUG.'-'.replace($filmId).'/download-'.$r['episode_id'].'.html?download='.NOW.$_SESSION['captcha'].'" target="_blank"><i></i>&nbsp;Download Full</a>';
    }
	 	
		$sv[$episode_type] .= '<tr><td>'.ServerNAME($episode_type).'</td><td>Episode '.$episode_name.'</td><td>'.$episodeLink.'</td> <td>'.un_htmlchars($r['episode_message']).'</td> </tr>';
	}
	$TotalSV = str_replace("[+]","|",$web_server);
        $TotalSV = explode("|",$TotalSV);
	$html = '<table class="imagetable">';
	for($i=1;$i<=count($TotalSV);$i++){
	if ($sv[$i])  $html .= ' <thead> <tr><th>Server</th> <th>Episode</th> <th>Download link</th> <th>Note</th> </tr> </thead> <tbody>'.$sv[$i].'</tbody>';
	}
	$html .= '</table>';
	return $html;
}
function chatForm(){
    $isLogin = checkLogin();
	if($isLogin){
	    $username = $_SESSION["user_name"];
	}elseif(isset($_COOKIE['RequestUserName'])){
	    $username = $_COOKIE['RequestUserName'];
	}else $username = "";
	if($username != '') $dis = "disabled"; else $dis = '';
    $html = ' <form role="form"><p><input type="text" value="'.$username.'" id="request_name" name="request_name" placeholder="Your name" '.$dis.'><input class="input-post" id="request_submit" name="request_submit" type="submit" value="Gửi Y/C" onclick="return reqPost();"></p><textarea rows="4" id="request_txt" name="request_txt" style="width:100%" placeholder="Bạn muốn yêu cầu phim gì?"></textarea><input value="0" type="hidden" name="request_id" id="request_id"></form>';
	return $html;
}
function subscribeSuggest($type,$slug,$filmNAMEVN,$showNotif="none"){

    if($type == 0){
	    $text = 'Phim này chưa có bản đẹp';
	    $text2 = 'Bạn có muốn nhận thông tin khi phim này có bản đẹp không ?';
	}elseif($type == 2){
	    $text = 'Bộ phim này vẫn còn ra tập mới';
	    $text2 = 'Bạn có muốn nhận thông tin khi có tập mới không ?';
	}elseif($type == 3){
	    $text = 'Phim này mới chỉ có trailer';
	    $text2 = 'Bạn có muốn nhận thông tin khi phim có thể xem online không ?';
	}
    $html = '<div class="subscribe-block clearfix" id="subscribe-button" style="display:'.$showNotif.'"><div class="form-subscribe" id="form-subscribe-container"><h4 class="subscribe-title">'.$text.'</h4><hr class="subscribe-title-hr"><div class="text-block" id="subscribe-message"><a style="float: right;" id="btn-subscribe" class="btn btn-red btn-subscribe" title="Đăng ký theo dõi phim '.$filmNAMEVN.'" href="'.$slug.'theo-doi.html" rel="nofollow">Có, đăng ký</a><span class="subscribe-promote">'.$text2.'</span></div></div><div class="clearfix"></div></div>';
	return $html;
}
function subscribeForm($slug){
    $isLogin = checkLogin();
	if($isLogin){
	    $username = $_SESSION["user_name"];
		$email = get_data("user_email","user","user_id",$_SESSION["user_id"]);
	}elseif(isset($_COOKIE['subscribeName']) || isset($_COOKIE['subscribeEmail'])){
	    $username = $_COOKIE['notifEmail'];
	    $email = $_COOKIE['notifName'];
	}else{ $username = "";$email = "";}
    $html = '<div class="form-subscribe" id="subscribe-form" style="display:none;"><h4 class="subscribe-title">Đăng ký theo dõi phim</h4><hr class="subscribe-title-hr"><form class="form-inline" id="form-film-subscribe" method="POST" action="'.$slug.'theo-doi.html"><div class="row"><div class="col-lg-6"><div class="form-group"><label for="subscribe-email">Nhập Email của bạn</label><input name="subscribe-email" class="form-control" id="subscribe-email" value="'.$email.'" type="email"></div></div><div class="col-lg-6"><div class="form-group"><label for="subscribe-fullname">Nhập tên của bạn</label><input name="subscribe-fullname" class="form-control" id="subscribe-fullname" value="'.$username.'" type="text"></div></div></div><div class="form-group"><label for="subscribe-verify">Mã bảo vệ</label><div class="row"><div class="col-lg-6"><input name="subscribe-verify" class="form-control" id="subscribe-verify" type="text"></div><div class="col-lg-3"><img src="'.WEB_URL.'/captcha/rand/'.rand(1000,9999).'" id="captchaimg" align="middle"><a class="fa fa-refresh" href="javascript: refreshCaptcha();" style="margin-left: 10px;"></a></div><div class="col-lg-3"><button type="submit" class="btn btn-primary btn-submitsubscribe" name="subscribe-submit">Đăng ký theo dõi</button></div></div></div></form></div>';
	return $html;
}
function subscribeOff($slug,$hash,$name){
    $html = '<div id="subscribe-unsubscribe" class="subscribe-block clearfix" style=""><div class="form-subscribe" id="form-subscribe-container"><h4 class="subscribe-title">Bạn đang theo dõi phim này</h4><hr class="subscribe-title-hr"><div class="text-block" id="subscribe-message"><a id="btn-unsubscribe" class="btn btn-red btn-subscribed" style="float:right;" title="Hủy đăng ký theo dõi phim '.$name.'" href="'.$slug.'huy-theo-doi.html" data-hash="'.$hash.'" rel="nofollow">Hủy đăng ký</a><span class="subscribe-promote">Cám ơn bạn, Phim Lẻ sẽ gửi email cho bạn khi phim được cập nhật.</span></div></div><div class="clearfix"></div></div>';
	return $html;
}
function checkNotif($filmId){
    $isLogin = checkLogin();
	if($isLogin){
	    $email = get_data("user_email","user","user_id",$_SESSION["user_id"]);
		$notif = get_data_multi("notif_id","notif","notif_email = '".$email."' AND notif_film = '".$filmId."'");
	}elseif(isset($_COOKIE['notifEmail'])){
	    $email = $_COOKIE['notifEmail'];
		$notif = get_data_multi("notif_id","notif","notif_email = '".$email."' AND notif_film = '".$filmId."'");
	}
	if($notif) return true; else return false;
}
function hashNotif($filmId){
    $isLogin = checkLogin();
	if($isLogin){
	    $email = get_data("user_email","user","user_id",$_SESSION["user_id"]);
		$notif = get_data_multi("notif_secrethash","notif","notif_email = '".$email."' AND notif_film = '".$filmId."'");
	}elseif(isset($_COOKIE['notifEmail'])){
	    $email = $_COOKIE['notifEmail'];
		$notif = get_data_multi("notif_secrethash","notif","notif_email = '".$email."' AND notif_film = '".$filmId."'");
	}else $notif = '';
	return $notif;
}
?>