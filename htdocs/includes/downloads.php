<?php
if (!defined('TRUNKSJJ')) die("Hack");
function get_curl_xx($url){
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
function picasa_google($link,$file){
    global $phpFastCache;
	$key = 'flash-'.$link;
    $id = explode('#',$link);
            $id = $id[1];
            //get picasa page by default file_get_contents function
            $datazs = get_curl_xx($link);
            $data = explode('shared_group_'.$id,$datazs);
            $data = explode('shared_group_',$data[1]);
            $data = $data[0];
            $data = explode('image/',$data);
            $data = explode('description',$data[1]);
            $data = $data[0];
                if(strpos($link , 'directlink') !== false){ 
				    $data = explode('image/',$datazs);
                    $data = explode('description',$data[1]);
                    $data = $data[0];
				    $datar= explode('"url":"', $data);
                }else{
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
                }
			$html = '';	
			$jwp = '';	
            for($i=1;$i<count($datar);$i++){
                if(strpos($datar[$i], 'video/mpeg4') !== false){
                    $datarz = explode('"', $datar[$i]);
                    $typep = explode('"height":', $datar[$i]);
                    $typep = explode(',', $typep[1]);
                    $typep = $typep[0];
                    $datarss = $datarz[0]; 
                    $htmls = $datarss;
					$save .= $htmls."|";
				$html .= '<a class="btn btn-green btn-download " id="btn-download-'.$typep.'" href="'.$htmls.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD '.$typep.'p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
				$jwp .= '<jwplayer:source file="'.$htmls.'" label="'.$typep.'" type="mp4" />';	
                }
            }
	if($jwp != '') $phpFastCache->set($key, $jwp, CACHED_TIME);
	else $html = '<div id="btnX" class="btn btn-blue"><span class="text1" id="countdown-info">$data trống rỗng</span><span class="timedown" id="countdown-time"></span></div>';
			
	return $html;
}
function plus_google($link,$file){
 global $phpFastCache;
	$key = 'flash-'.$link;
    $getId = explode("pid=",$link);
	$getId = explode("&",$getId[1]);
	$getId = trim($getId[0]);	
	$photos = get_curl_xx($link);
	$data = explode(',true,"'.$getId.'",false', $photos);
	$data = explode('"]', $data[1]);
	$data = $data[0];
	$data_photos = explode('url\u003d', $data);
	$photo_url = explode('%3Dm', $data_photos[1]);
	$decode = urldecode($photo_url[0]);
	$count = count($data_photos);
	$html = '';
	if($count == 5) {
		$v1080p = $decode.'=m37';
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$v360p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-720" href="'.$v720p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-1080" href="'.$v1080p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 1080p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		$jwp = '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" /><jwplayer:source file="'.$v1080p.'" label="1080" type="mp4" />';	
		
	} elseif($count == 4) {
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$v360p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-720" href="'.$v720p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		$jwp = '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" />';
	} elseif($count == 3) {
		$v360p = $decode.'=m18';
		$html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$v360p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		$jwp = '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" />';
	}
	if($html != '') $phpFastCache->set($key, $jwp, CACHED_TIME);
	else $html = '<div id="btnX" class="btn btn-blue"><span class="text1" id="countdown-info">$data trống rỗng</span><span class="timedown" id="countdown-time"></span></div>';
	
	return $html;
}
function qualidy_docs_drive_down($url){
    if(strpos($url , 'itag=34') !== false){
	    $txt = '720p';
	}elseif(strpos($url , 'itag=35') !== false){
	    $txt = '360p';
	}else $txt = '360p';
    return $txt;
}
function docs_google($link){
 global $phpFastCache;
	$key = $link.'?rel=download';
	$data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	if($data_cache != null){
		$html = '<!---Use Cache ---->'.$data_cache.'<!---/Use Cache ---->'; 
	}else{
    $html = '';
		    $get = get_curl_xx($link);
	        $data = explode('url\u003d', $get);
            for($i=1;$i<count($data);$i++){
                $url = explode('\u0026type\u003d', $data[$i]);
                $decode = urldecode($url[0]);
				if(strpos($decode , 'itag=34') !== false || strpos($decode , 'itag=35') !== false)
				$html .= '<a href="'.$decode.'" target="_blank"><i></i>&nbsp;'.qualidy_docs_drive_down($decode).'</a>';
				else 
				$html .= '';
				
			}	
	if($html != '') $phpFastCache->set($key, $jwp, CACHED_TIME);
	else $html = '<div id="btnX" class="btn btn-blue"><span class="text1" id="countdown-info">$data trống rỗng</span><span class="timedown" id="countdown-time"></span></div>';
	}	
	return $html;		
}
function photos_google_direct($link,$file){
 global $phpFastCache;
	$key = 'flash-'.$link;
    $photos = get_curl_xx($link);
	$data_photos = explode('url\u003d', $photos);
	$photo_url = explode('%3Dm', $data_photos[1]);
	$decode = urldecode($photo_url[0]);
	$count = count($data_photos);
	$html = '';
	if($count == 5) {
		$v1080p = $decode.'=m37';
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$v360p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-720" href="'.$v720p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-1080" href="'.$v1080p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 1080p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		$jwp = '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" /><jwplayer:source file="'.$v1080p.'" label="1080" type="mp4" />';	
		
	} elseif($count == 4) {
		$v720p = $decode.'=m22';
		$v360p = $decode.'=m18';
		$html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$v360p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-720" href="'.$v720p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		$jwp = '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" /><jwplayer:source file="'.$v720p.'" label="720" type="mp4" />';
	} elseif($count == 3) {
		$v360p = $decode.'=m18';
		$html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$v360p.'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		$jwp = '<jwplayer:source file="'.$v360p.'" label="360" type="mp4" />';
	}
	if($html != '') $phpFastCache->set($key, $jwp, CACHED_TIME);
	else $html = '<div id="btnX" class="btn btn-blue"><span class="text1" id="countdown-info">$data trống rỗng</span><span class="timedown" id="countdown-time"></span></div>';
	
	return $html;
}
function downloadLinkGk($subject,$file){
    global $phpFastCache;
	$key = 'flash-'.$subject;
	$jwp = '';
	$html = '';
	$array = match_link($subject);
	$postUrl = $array[1];
	$linkUrl = $array[2];
    $m = gkPhp(grabSiteGkPhp($postUrl),$linkUrl);
	if($m !== false){
	    $html .= '<a class="btn btn-green btn-download " id="btn-download-360" href="'.stripslashes($m[0][1]).'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
	    $jwp .= '<jwplayer:source file="'.stripslashes($m[0][1]).'" label="360" type="mp4" />';
	    if(stripslashes($m[1][1]) != ''){
	        $jwp .= '<jwplayer:source file="'.stripslashes($m[1][1]).'" label="720" type="mp4" />';
		    $html .= '<a class="btn btn-green btn-download " id="btn-download-720" href="'.stripslashes($m[1][1]).'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		}	
	    $phpFastCache->set($key, $jwp, CACHED_TIME);//Tạo cache cho link với thời gian là 120s ~ 2p
	}else{
	    $html = '<div id="btnX" class="btn btn-blue"><span class="text1" id="countdown-info">$data trống rỗng</span><span class="timedown" id="countdown-time"></span></div>';
	}
	return $html;
}
function getlink_phimvip($url,$file){
    global $phpFastCache;
    if(strpos($url , 'm.phimvipvn.net') !== false || strpos($url , 'm.phimchon.com') !== false){
        $url = $url;
    }else{
        if(strpos($url , 'phimvipvn.net') !== false)
        $url = str_replace('phimvipvn.net','m.phimvipvn.net',$url);
        elseif(strpos($url , 'phimvipvn.net') !== false)
        $url = str_replace('phimchon.com','m.phimchon.com',$url);
    }
	$key = 'flash-'.$url;
    $page = get_curl_xx($url);
	$play = explode('<source data-res="',$page);
	$html = '';
	$jwp = '';
	for($i=1;$i<count($play);$i++){
	    $content = explode('src="',$play[$i]);
	    $content = explode('"',$content[1]);
	    $quality = explode('"',$play[$i]);
		$html .= '<a class="btn btn-green btn-download " id="btn-download-'.str_replace('p','',$quality[0]).'" href="'.trim($content[0]).'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD '.$quality[0].'</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';
		$jwp .= '<jwplayer:source file="'.trim($content[0]).'" label="'.str_replace('p','',$quality[0]).'" type="mp4" />';
		
	}
	if($html != '') $phpFastCache->set($key, $jwp, CACHED_TIME);
	else $html = '<div id="btnX" class="btn btn-blue"><span class="text1" id="countdown-info">$data trống rỗng</span><span class="timedown" id="countdown-time"></span></div>';
	return $html;
}
function local_direct_down($link,$file){

		    $linkId = explode('|',$link);
		
			
			    if(count($linkId) == 1){
				    $html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$linkId[0].'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
				}elseif(count($linkId) == 2){
				    $html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$linkId[0].'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-720" href="'.$linkId[1].'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
				}elseif(count($linkId) == 3){
					$html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$linkId[0].'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-720" href="'.$linkId[1].'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-1080" href="'.$linkId[2].'?title='.$file.'"><span class="btn-text"><b>DOWNLOAD 1080p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
				}
			
		  
	
	return $html;
}
function DownloadPhimleTv($url){
	if(strpos($url , 'picasaweb.google.com') !== false){
	//$link = picasa_google($url);
$link = '* Tập này chưa có link download';
	}elseif(strpos($url , 'plus.google.com') !== false){
	//$link = plus_google($url);
$link = '* Tập này chưa có link download';
	}elseif(strpos($url , 'docs.google.com') !== false || strpos($url , 'drive.google.com') !== false){
	//$link = docs_google($url);
$link = '* Tập này chưa có link download';
	}elseif(strpos($url , 'photos.google.com') !== false){
$link = '* Tập này chưa có link download';
	//$link = photos_google_direct($url);
	
	}elseif(strpos($url , 'clip.vn') !== false){
	$link = '* Tập này chưa có link download';
	
	}elseif(strpos($url , 'tv.zing.vn/video') !== false){
	$link = '* Tập này chưa có link download';
	}elseif(strpos($url , 'tv.zing.vn/episode') !== false){
	$link = '* Tập này chưa có link download';
	}elseif(strpos($url , 'xvideos.com') !== false){
	$link = '* Tập này chưa có link download';
	}elseif(strpos($url , 'dailymotion') !== false){
	$link = '* Tập này chưa có link download';
	}elseif(strpos($url , 'youtube') !== false){
	$link = '* Tập này chưa có link download';
	}else{
	$link = '<a href="'.$url.'" target="_blank"><i></i>&nbsp;720p</a>';
	}
	return $link;
}?>