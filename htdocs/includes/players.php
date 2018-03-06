<?php
if (!defined('TRUNKSJJ')) die("Hack");
require_once("phpfastcache.php");

$phpFastCache = phpFastCache();//Gọi hàm	
function Decode_ZTV($text){
global $ZingDecode;
$ZingDecode->_text = $text;
 if($ZingDecode->_decrypt() != false){
    $link = trim($ZingDecode->_result);
    }else $link = '';
	return $link;
}
class YoutbeDirect
{
    private static $endpoint = "http://www.youtube.com/get_video_info";

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function getLink($id)
    {
		preg_match('#https://www.youtube.com/watch\?v=(.*)#', $id, $Match);
        $API_URL    = self::$endpoint . "?&video_id=" . $Match[1];
        $video_info = $this->curlGet($API_URL);

        $url_encoded_fmt_stream_map = '';
        parse_str($video_info);
        if(isset($reason))
        {
            return $reason;
        }
        if (isset($url_encoded_fmt_stream_map)) {
            $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
        } else {
            return 'No encoded format stream found.';
        }
        if (count($my_formats_array) == 0) {
            return 'No format stream map found - was the video id correct?';
        }
        $avail_formats[] = '';
        $i = 0;
        $ipbits = $ip = $itag = $sig = $quality = $type = $url = '';
        $expire = time();
        foreach ($my_formats_array as $format) {
            parse_str($format);
            $avail_formats[$i]['itag'] = $itag;
            $avail_formats[$i]['quality'] = $quality;
            $type = explode(';', $type);
            $avail_formats[$i]['type'] = $type[0];
            $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
            parse_str(urldecode($url));
            $avail_formats[$i]['expires'] = date("G:i:s T", $expire);
            $avail_formats[$i]['ipbits'] = $ipbits;
            $avail_formats[$i]['ip'] = $ip;
            $i++;
        }
        if (is_string($avail_formats)) {
            echo $avail_formats;
        } else {
            foreach ($avail_formats as $video) {
                if (strpos($video['url'], 'itag=22')) {
                    $m22 = $video['url'];
                } elseif (strpos($video['url'], 'itag=18')) {
                    $m18 = $video['url'];
                }
            }
            if (isset($m22, $m18)) {
                $js = '<jwplayer:source file="'.$m18.'" label="360" type="mp4"/><jwplayer:source file="'.$m22.'" label="720" type="mp4"/>';
            } elseif (isset($m18)) {
                $js = '<jwplayer:source file="'.$m18.'" label="360" type="mp4"/>';
            } else {
                $js = 'Not support';
            }
            return $js;
        }
    }

    function curlGet($URL)
    {
        $ch = curl_init();
        $timeout = 3;
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }
} 
function get_curl_x($url){
	$ch = @curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	$head[] = "Connection: keep-alive";
	$head[] = "Keep-Alive: 300";
	$head[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7";
	$head[] = "Accept-Language: en-us,en;q=0.5";
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.124 Safari/537.36');
	curl_setopt($ch, CURLOPT_HTTPHEADER, $head);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
	curl_setopt($ch, CURLOPT_TIMEOUT, 60);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 60);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Expect:'));
	$page = curl_exec($ch);
	curl_close($ch);
	return $page;
}
function bocaidau($str){
    $unicode = array(
        ''  => '%5C',
        '/' => '%2F',
        ':' => '%3A',
        '?' => '%3F',
        '=' => '%3D');
    foreach ($unicode as $nonUnicode => $uni) {
        $str = preg_replace("/($uni)/i", $nonUnicode, $str);
    }
    return $str;
}

function dailymotion_direct($url){
// http://www.dailymotion.com/video/xx55ef_khi-vo-co-bo_shortfilms
		$ID = explode('video/',$url);
		$ID = explode('_',$ID[1]);
		$ID = $ID[0];
        $source = get_curl_x('http://www.dailymotion.com/embed/video/'.$ID.'/');
        $xml = explode('stream_h264_', $source);
		$mp4 = '';
            for($i=5;$i>=1;$i--){
	           $video = explode('":"',$xml[$i]);
	           $video = explode('"',$video[1]);
               $mp4_src =  bocaidau(urlencode($video[0]));
			   if($mp4_src){
			   $type = explode('H264-',$mp4_src);
			   $type = explode('/',$type[1]);
			   $type = $type[0];
			   $type = explode('x',$type);
			   $type = $type[1];
			   $mp4 .= '<source data-res="'.$type.'p" src="'.$mp4_src.'&phimle.mp4" type="video/mp4" />';
			   }
	        } 
return $mp4;

}
function replace_ZTV($url){
    $url = explode('?format',$url);
	return $url[0];
}
function ZingTV_direct($url,$playTech='html5'){
   
	$linkSource = get_curl_x($url);
	$Source = explode("document.write('<source",$linkSource);
	$html = '';
    for($i=1;$i<=2;$i++){
	    $linkmp4 = explode('src="',$Source[$i]);
	    $linkmp4 = explode('"',$linkmp4[1]);
		$linkmp4 = trim($linkmp4[0]);
		if($playTech == 'flash'){
		if($i == 1)
		$html .= '<jwplayer:source file="'.$linkmp4.'" label="240" type="mp4" />';
		else
		$html .= '<jwplayer:source file="'.$linkmp4.'" label="360" type="mp4" default="true" />';
		}else{
		if($i == 1)
		$html .= '<source data-res="240p" src="'.$linkmp4.'" type="video/mp4" />';
		else
		$html .= '<source data-res="360p" src="'.$linkmp4.'" type="video/mp4" />';
		}
	}
	
    return $html;
}
function httpPost($url,$params)
{
  $postData = '';
   //create name value pairs seperated by &
   foreach($params as $k => $v) 
   { 
      $postData .= $k . '='.$v.'&'; 
   }
   rtrim($postData, '&');
 
    $ch = curl_init();  
 
 
    $header[0] = "Accept: text/xml,application/xml,application/xhtml+xml,"; 
    $header[0] .= "text/html;q=0.9,text/plain;q=0.8,image/png,*/*;q=0.5"; 
    $header[] = "Cache-Control: max-age=0"; 
    $header[] = "Connection: keep-alive"; 
    $header[] = "Keep-Alive: 300"; 
    $header[] = "Accept-Charset: ISO-8859-1,utf-8;q=0.7,*;q=0.7"; 
    $header[] = "Accept-Language: en-us,en;q=0.5"; 
    $header[] = "Pragma: "; // browsers keep this blank. 
	curl_setopt($ch, CURLOPT_USERAGENT, 'Googlebot/2.1 (+http://www.google.com/bot.html)'); 
    curl_setopt($ch, CURLOPT_HTTPHEADER,$header);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL,$url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
    curl_setopt($ch, CURLOPT_HEADER, false); 
    curl_setopt($ch, CURLOPT_POST, count($postData));
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postData);    
 
    $output=curl_exec($ch);
 
    curl_close($ch);
    return $output;
 
}
function GetId_ClipVN($url){
    if(strpos($url , ',') !== false){
	    $getid = explode(',',$url);
	    $getid = explode('/',$getid[1]);
	    $getid = $getid[0];
	}elseif(strpos($url , 'embed') !== false){
	    $getid = explode('embed/',$url);
	    $getid = $getid[1];
	}else{
	    $getid = explode('src=&quot;http://clip.vn/embed/',get_curl($url));
	    $getid = explode('&',$getid[1]);
	    $getid = $getid[0];
	}
	return $getid;
}
function clipvn_direct($id){
    $params = array("onsite" => "clip");
    $linkclip = httpPost("http://clip.vn/movies/nfo/".$id."",$params);
    $aArray = explode("<enclosure url='",$linkclip);
    $A=explode("'",$aArray[1]);
 //   return $A[0];
    $mp4 = '<source data-res="360p" src="'.$A[0].'" type="video/mp4" />';
    return $mp4;
}
function quality_url($id){
    if($id == 0){
        $quality = '360';
    }elseif($id == 1){
        $quality = '720';
    }elseif($id == 2){
        $quality = '1080';
    }else{
        $quality = '480';
    }
    return $quality;
}
function qualidy_docs_drive($url){
    if(strpos($url , 'itag=22') !== false){
	    $txt = '720p';
	}elseif(strpos($url , 'itag=18') !== false){
	    $txt = '360p';
	}elseif(strpos($url , 'itag=59') !== false){
	    $txt = '720p';
	}elseif(strpos($url , 'itag=43') !== false){
	    $txt = '240p';
	}else $txt = '360p';
    return $txt;
}
function phimle_savecachelink($link,$episodeid){
    global $mysql,$tb_prefix;
    $time = NOW+2160000; // lưu trong 25 ngày
	$mysql->query("UPDATE ".$tb_prefix."episode SET episode_cache_link = '".$link."', episode_cache_time = '".$time."' WHERE episode_id = '".$episodeid."'");
    return false;
}
function docs_direct($link,$playTech='html5'){
    global $phpFastCache;
	    $key = $playTech.'-'.$link;
	    $data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
		if($data_cache != null){
		    $html = $data_cache; 
		}else{
		    $html = '';
		    $get = get_curl_x("http://sub1.phim3s.net/v3/plugins_player.php?url=".$link);
	        $data = explode('url\u003d', $get);
            for($i=1;$i<count($data);$i++){
                $url = explode('\u0026type\u003d', $data[$i]);
                $decode = urldecode($url[0]);
				
				if($playTech == 'html5')
				$html .= '<source data-res="'.qualidy_docs_drive($decode).'" src="'.$decode.'" type="video/mp4" />';
				else $html .= '<jwplayer:source file="'.$decode.'" label="'.qualidy_docs_drive($decode).'" type="mp4" />';
			}	
		if($html != '') $phpFastCache->set($link, $html, CACHED_TIME);
		}
	return $html;	
}
function plus_google_direct($link,$playTech='html5'){
    global $phpFastCache;
	$key = $playTech.'-'.$link;
	$data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	if($data_cache != null){
		    $html = $data_cache; 
		}else{
	$getId = explode("pid=",$link);
	$getId = explode("&",$getId[1]);
	$getId = trim($getId[0]);	
	$photos = get_curl_x($link);
	$data = explode(',true,"'.$getId.'",false', $photos);
	$data = explode('"]', $data[1]);
	$data = $data[0];
	$data_photos = explode('url\u003d', $data);
	$photo_url = explode('%3Dm', $data_photos[1]);
	$decode = urldecode($photo_url[0]);
	$count = count($data_photos);
	$html = '';
	if($playTech == 'flash'){
	if($count == 5) {
		$v1080p = ($decode.'=m37');
		$v720p = ($decode.'=m22');
		$v360p = ($decode.'=m18');
		$html .= '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" default="true" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" /><jwplayer:source file="'.$v1080p.'" label="1080" type="mp4" />';
		
	} elseif($count == 4) {
		$v720p = ($decode.'=m22');
		$v360p = ($decode.'=m18');
		$html .= '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" default="true" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" />';	
	} elseif($count == 3) {
		$v360p = ($decode.'=m18');
		$html .= '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" default="true" />';	
	}
	}else{
	if($count == 5) {
		$v1080p = $decode.'=m37';
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html .= '<source data-res="360p" src="'.$v360p.'" type="video/mp4" />';
		$html .= '<source data-res="720p" src="'.$v720p.'" type="video/mp4" />';
		$html .= '<source data-res="1080p" src="'.$v1080p.'" type="video/mp4" />';
		
	} elseif($count == 4) {
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html .= '<source data-res="360p" src="'.$v360p.'" type="video/mp4" />';
		$html .= '<source data-res="720p" src="'.$v720p.'" type="video/mp4" />';
	} elseif($count == 3) {
		$v360p = $decode.'=m18';
		$html .= '<source data-res="360p" src="'.$v360p.'" type="video/mp4" />';
	}
	}
	if($html != '') $phpFastCache->set($key, $html, CACHED_TIME);
	}
	return $html;
}
function get_location_http($url){
    $array = get_headers($url,1);
	$html = $array["Location"];
	$html = str_replace('&','&amp;',$html);
	return $html;
}
function photos_google($link,$playTech='html5'){
    global $phpFastCache;
	$key = $playTech.'-'.$link;
	$data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	if($data_cache != null){
		    $html = $data_cache; 
		}else{
		
	$photos = get_curl_x($link);
	$data_photos = explode('url\u003d', $photos);
	$photo_url = explode('%3Dm', $data_photos[1]);
	$decode = urldecode($photo_url[0]);
	$count = count($data_photos);
	$html = '';
	if($playTech == 'flash'){
	if($count == 5) {
		$v1080p = ($decode.'=m37');
		$v720p = ($decode.'=m22');
		$v360p = ($decode.'=m18');
		$html .= '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" default="true" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" /><jwplayer:source file="'.$v1080p.'" label="1080" type="mp4" />';
		
	} elseif($count == 4) {
		$v720p = ($decode.'=m22');
		$v360p = ($decode.'=m18');
		$html .= '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" default="true" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" />';	
	} elseif($count == 3) {
		$v360p = ($decode.'=m18');
		$html .= '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" default="true" />';	
	}
	}else{
	if($count == 5) {
		$v1080p = $decode.'=m37';
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html .= '<source data-res="360p" src="'.$v360p.'" type="video/mp4" />';
		$html .= '<source data-res="720p" src="'.$v720p.'" type="video/mp4" />';
		$html .= '<source data-res="1080p" src="'.$v1080p.'" type="video/mp4" />';
		
	} elseif($count == 4) {
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html .= '<source data-res="360p" src="'.$v360p.'" type="video/mp4" />';
		$html .= '<source data-res="720p" src="'.$v720p.'" type="video/mp4" />';
	} elseif($count == 3) {
		$v360p = $decode.'=m18';
		$html .= '<source data-res="360p" src="'.$v360p.'" type="video/mp4" />';
	}
	}
	if($html != '') $phpFastCache->set($key, $html, CACHED_TIME);
	}
	return $html;
}
function picasa_direct($link,$playTech='html5') {
    global $phpFastCache;
	    $key = $playTech.'-'.$link;
		$data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	    if($data_cache == null){
		    $id = explode('#',$link);
            $id = $id[1];
            //get picasa page by default file_get_contents function
            $datazs = get_curl_x($link);
            $data = explode('shared_group_'.$id,$datazs);
            $data = explode('shared_group_',$data[1]);
            $data = $data[0];
            $data = explode('image/',$data);
            $data = explode('description',$data[1]);
            $data = $data[0];
                if(strpos($link , '#') !== false){ 
				    if($data != ''){$datar= explode('"url":"', $data);
                    }else{//gphoto$id":"$id
                        $datav = explode('gphoto$id":"'.$id,$datazs);
                        $datav = explode('gphoto$id":"',$datav[1]);
                        $datav = $datav[0];
						$data = explode('image/',$datav);
                        $data = explode('description',$data[1]);
                        $data = $data[0];
                        $datar= explode('"url":"', $datav);
                    }  
                }else{
                    $data = explode('image/',$datazs);
                    $data = explode('description',$data[1]);
                    $data = $data[0];
				    $datar= explode('"url":"', $data);
                }
			$html = '';	
            for($i=1;$i<count($datar);$i++){
                if(strpos($datar[$i], 'video/mpeg4') !== false){
                    $datarz = explode('"', $datar[$i]);
                    $typep = explode('"height":', $datar[$i]);
                    $typep = explode(',', $typep[1]);
                    $typep = $typep[0];
                    $datarss = $datarz[0]; 
                    $htmls = $datarss;
                    $htmls = str_replace('&','&amp;',$htmls);
                if($playTech == 'flash'){
				    if($typep == '360') $default = 'default="true"';	
					else $default = '';
				    $html .= '<jwplayer:source file="'.$htmls.'" label="'.$typep.'" type="mp4" '.$default.' />';	
				}else{
				    $html .= '<source data-res="'.$typep.'p" src="'.$htmls.'" type="video/mp4" />';
				}
                
                }
            }
			if($html != '') $phpFastCache->set($key, $html, CACHED_TIME);//Tạo cache cho link với thời gian là 120s ~ 2p
		}else{	        
			    $html = $data_cache;    
		}
    return $html;
}
function local_direct($link,$episodeId,$playTech='html5'){
    global $phpFastCache;
	    $key = $playTech.'-'.$episodeId;
		$data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	    if($data_cache == null){
		    $linkId = explode('|',$link);
			$html = '';
			if($playTech == 'flash'){
			    if(count($linkId) == 1){
				    $html = '<jwplayer:source file="'.$linkId[0].'" label="360" type="mp4" '.$default.' />';
				}elseif(count($linkId) == 2){
				    $html = '<jwplayer:source file="'.$linkId[0].'" label="360" type="mp4" '.$default.' /><jwplayer:source file="'.$linkId[1].'" label="720" type="mp4" />';
				}elseif(count($linkId) == 3){
				    $html = '<jwplayer:source file="'.$linkId[0].'" label="360" type="mp4" '.$default.' /><jwplayer:source file="'.$linkId[1].'" label="720" type="mp4" /><jwplayer:source file="'.$linkId[2].'" label="1080" type="mp4" />';
				}
			}else{
			    if(count($linkId) == 1){
				    $html = '<source data-res="360p" src="'.$linkId[0].'" type="video/mp4" />';
				}elseif(count($linkId) == 2){
				    $html = '<source data-res="360p" src="'.$linkId[0].'" type="video/mp4" /><source data-res="720p" src="'.$linkId[1].'" type="video/mp4" />';
				}elseif(count($linkId) == 3){
				    $html = '<source data-res="360p" src="'.$linkId[0].'" type="video/mp4" /><source data-res="720p" src="'.$linkId[1].'" type="video/mp4" /><source data-res="1080p" src="'.$linkId[2].'" type="video/mp4" />';
				}
			}
		    if($html != '') $phpFastCache->set($key, $html, CACHED_TIME);//Tạo cache cho link với thời gian là 120s ~ 2p
		}else{
		    $html = $data_cache;    
		}
	return $html;
}

function ZingTV_direct2($url){
	$linkSource = file_get_contents('compress.zlib://'.$url);
	$Source = explode('xmlURL: "',$linkSource);
	$Source = explode('"',$Source[1]);
	$Source = $Source[0];
	$sourceXML = file_get_contents('compress.zlib://'.$Source);
	$mp4 = '';
	 $f360 = explode('<source><![CDATA[',$sourceXML);
		$f360 = explode(']]>',$f360[1]);
		    if($f360[0]) $mp4 .= '<source data-res="360p" src="'.trim($f360[0]).'" type="video/mp4" />';
        $f480 = explode('<f480><![CDATA[',$sourceXML);
		$f480 = explode(']]>',$f480[1]);
		    if($f480[0]) $mp4 .= '<source data-res="480p" src="'.trim($f480[0]).'" type="video/mp4" />';
        $f720 = explode('<f720><![CDATA[',$sourceXML);
		$f720 = explode(']]></f720>',$f720[1]);
	        if($f720[0]) $mp4 .= '<source data-res="720p" src="'.trim($f720[0]).'" type="video/mp4" />';
    return $mp4;
}
function get_megabox($url){
//http://phim.megabox.vn/phim-hanh-dong/xem-phim-nguoi-nhen-2-9365.html
   $t = explode("http://phim.megabox.vn/",$url);
   $p = $t[1];
   return $p;
}
function get_megabox_stream($url){
   $page = get_curl_x($url);
   preg_match('/var iosUrl = "(http:\/\/(.*).m3u8)/U', $page, $id);
   $p = trim($id[1]);
   $t = explode("megabox.vn",$p);
   $p = 'http://sv47.vnsub.net'.$t[1];
   return $p;
}
function get_phimmoi($url){
   $page = get_curl_x($url);
   $pageUrl = explode("currentEpisode.url='",$page);
   $pageUrl = explode("'",$pageUrl[1]);
   return trim($pageUrl[0]);
}
function get_userscloud($url){
   $page = get_curl_x($url);
   $pageUrl = explode("|image|video|",$page);
   $pageUrl = explode("|",$pageUrl[1]);
   return "https://d11.usercdn.com:443/d/".$pageUrl[0]."/video.mp4";
}
function phimvip_getlink($url,$playTech='html5'){
    if(strpos($url , 'm.phimvipvn.net') !== false || strpos($url , 'm.phimchon.com') !== false){
        $url = $url;
    }else{
        if(strpos($url , 'phimvipvn.net') !== false)
        $url = str_replace('phimvipvn.net','m.phimvipvn.net',$url);
        elseif(strpos($url , 'phimvipvn.net') !== false)
        $url = str_replace('phimchon.com','m.phimchon.com',$url);
    }
    $page = get_curl_x($url);
	$play = explode('<source data-res="',$page);
	$html = '';
	for($i=1;$i<count($play);$i++){
	    $content = explode('src="',$play[$i]);
	    $content = explode('"',$content[1]);
	    $quality = explode('"',$play[$i]);
		if($playTech == 'html5')
	    $html .= '<source data-res="'.$quality[0].'" src="'.trim($content[0]).'" type="video/mp4" />';
		else
		$html .= '<jwplayer:source file="'.trim(str_replace('&','&amp;',$content[0])).'" label="'.str_replace('p','',$quality[0]).'" type="mp4" />';
		
	}
	return $html;
}
function gkPhp($post,$link){
        global $CURL;
        $content = $CURL->post($post,"link=".urlencode($link)."&f=true" ,2);
 
        if(preg_match_all("/\"link\":\"([^\"]+)\",\"label\":\"([^\"]+)\",\"type\":\"([^\"]+)\"/",$content,$m,PREG_SET_ORDER)){  
            return $m;
        }elseif(preg_match_all('/"link":\"([^\"]+)\",\"type\":\"([^\"]+)\"/',$content,$m,PREG_SET_ORDER)){
		    return $m;
        }else return false;
}

function match_link($subject){
    $pattern   = '(http:\/\/sv3.phimle.tv\/(.+)\/mp4\/(.+))';
	preg_match($pattern, $subject, $matches);
	return $matches;
}
function grabSiteGkPhp($site){
    switch($site) {
	case 'phim14': $gkUrl = 'http://player8.phim14.net/gkphp90pc/plugins/gkpluginsphp.php'; break;
	case 'biphim': $gkUrl = 'http://biphim.com/biplayer/plugins/gkpluginsphp.php'; break;
        case 'yuphim': $gkUrl = 'http://yuphim.net/plugins/gkpluginsphp.php'; break;
        case 'tvhay': $gkUrl = 'http://tvhay.org/tvhayplayer/plugins/gkpluginsphp.php'; break;
        case 'xemphimmienphi': $gkUrl = 'http://xemphimmienphi.net/gkphp/plugins/gkpluginsphp.php'; break;
        case 'phimvipvn': $gkUrl = 'http://phimvipvn.net/bacu2/plugins/gkpluginsphp.php'; break;
	}
	return $gkUrl;
}
function sourceLinkGk($subject){
    global $phpFastCache;
	$key = 'flash-'.$subject;
    $data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	if($data_cache == null){
	    $html = '';
        $array = match_link($subject);
	    $postUrl = $array[1];
	    $linkUrl = $array[2];
        $m = gkPhp(grabSiteGkPhp($postUrl),$linkUrl);
		if($m !== false){
		$html .= '<jwplayer:source file="'.stripslashes(trim(str_replace('&','&amp;',$m[0][1]))).'" label="360" type="mp4" />';
		if(stripslashes($m[0][1]) != '')
		$html .= '<jwplayer:source file="'.stripslashes(trim(str_replace('&','&amp;',$m[1][1]))).'" label="720" type="mp4" />';
		$phpFastCache->set($key, $html, CACHED_TIME);//Tạo cache cho link với thời gian là 120s ~ 2p
		}
	}else{
		$html = $data_cache;    
	}
	return $html;
}
function get_123movies($url,$playTech='html5'){
    $page = get_curl_x($url);
	$playlist = explode('url_playlist = "',$page);
	$playlist = explode('"',$playlist[1]);
	$xml = get_curl_x($playlist[0]);
	$play = explode('<jwplayer:source type="mp4"',$xml);
	$html = '';
	for($i=1;$i<count($play);$i++){
	    $content = explode('file="',$play[$i]);
	    $content = explode('"',$content[1]);
		$quality = explode('label="',$play[$i]);
		$quality = explode('"',$quality[1]);
		if($playTech == 'html5')
	    $html .= '<source data-res="'.$quality[0].'" src="'.trim(str_replace('&amp;','&',$content[0])).'" type="video/mp4" />';
		else
		$html .= '<jwplayer:source file="'.trim($content[0]).'" label="'.$quality[0].'" type="mp4" />';
	}
    return $html;
}
function playerContent($link,$film_sub,$filmID,$img){
    $player = '<video id="phimle_playertv" class="video-js vjs-default-skin" controls autoplay="autoplay" width="100%" height="100%" poster="'.$img.'" data-setup="">
 	          '.$link.'
		       <track src="'.$film_sub.'" kind="subtitles" srclang="vi" label="Tiếng Việt" default>
		      </video><script type="text/javascript">filmInfo.playTech = "html5"; ClickToLoad('.$filmID.');</script>';
    return $player;			  
}
function phimle_players($url,$filmID,$episode_id,$server,$film_sub,$img,$playTech='html5'){
    global $mysql, $web_link;
	$is_mobile = is_mobile();
	if($playTech=='iframe'){
	    if(strpos($url , '4shared.com') !== false){
            $link = explode('www.4shared.com/embed/',$url);
            $player = '<iframe src="http://static.4shared.com/flash/player/embed/embed_flash.swf?fileId='.$link[1].'&apiURL=http%3A%2F%2Fwww.4shared.com%2F" width="100%" height="100%" style="border:none;"></iframe>';
	    }elseif(strpos($url , 'dailymotion.com') !== false){
            $link = $url;
            $player = '<iframe src="http://www.phimle.tv/plajax/daily.php?url='.$link.'" width="100%" height="100%" style="border:none;"></iframe>';
	    }elseif(strpos($url , 'youtube.com') !== false){
            $link = $url;
            $player = '<iframe src="http://www.phimle.tv/players/youtube/?url='.$link.'" width="100%" height="100%" style="border:none;"></iframe>';
	    }
	}elseif($playTech=='html5'){
	
	}elseif($playTech=='flash'){
	    $player = '<script type="text/javascript">var url_playlist = "'.$web_link.'/playlist/'.$filmID.'/episode/'.$episode_id.'"; ClickToLoad('.$filmID.');</script>';
	}elseif($playTech=='flashv1'){ //-- For Megabox.vn
	    $player = '<script type="text/javascript">var url_playlist = "'.get_megabox_stream($url).'"; ClickToLoad('.$filmID.');</script>';
	}elseif($playTech=='flashv2'){ //-- For Youtube
	    $player = '<script type="text/javascript">var url_playlist = "'.$url.'"; ClickToLoad('.$filmID.');</script>';
	}elseif($playTech=='gkphp'){ //-- For tv.zing.vn + Xvideos
	    $player = '<script type="text/javascript" src="'.$web_link.'/players/gkphp/plugins/gkpluginsphp.js"></script><div id="player1" style="width:100%;height:100%;"></div><script type="text/javascript">gkpluginsphp("player1",{link:"'.$url.'"});ClickToLoad('.$filmID.');</script> ';
	}
return $player;
}
?>