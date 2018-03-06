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
include_once('youtube/YoutbeDownloader.php');
include_once('../includes/downloads.php');
if(isset($_POST['downloadLinklist'])){	
    $captcha = ($_POST['captcha']);
    $filmId = (int)($_POST['filmId']);
	if($captcha != $_SESSION['captcha']) {
			echo 0;
	}else echo showEpisodeDownload($filmId);
	
    exit();
}elseif(isset($_POST['loadUrlDown'])){
    $episodeId = (int)$_POST['episodeId'];
    $fileName = $_POST['episodeTitle'];
	$episodeUrl = get_data("episode_url","episode","episode_id",$episodeId);
	
	if(strpos($episodeUrl , 'm.phimvipvn.net') !== false || strpos($episodeUrl , 'm.phimchon.com') !== false){
        $episodeUrl = $episodeUrl;
    }else{
        if(strpos($episodeUrl , 'phimvipvn.net') !== false)
        $episodeUrl = str_replace('phimvipvn.net','m.phimvipvn.net',$episodeUrl);
        elseif(strpos($episodeUrl , 'phimvipvn.net') !== false)
        $episodeUrl = str_replace('phimchon.com','m.phimchon.com',$episodeUrl);
    }
	$key = 'flash-'.$episodeUrl;
	$data_cache = $phpFastCache->get($key);//Kiểm tra xem link truyền vào đã cache chưa
	
	if($data_cache == null){
		if(strpos($episodeUrl, 'picasaweb.google.com') !== false){
		    $html = picasa_google($episodeUrl,$fileName);
		}elseif(strpos($episodeUrl, 'plus.google.com') !== false){
		    $html = plus_google($episodeUrl,$fileName);
		}elseif(strpos($episodeUrl, 'photos.google.com') !== false){
		    $html = photos_google_direct($episodeUrl,$fileName);
		}elseif(strpos($episodeUrl , 'phimvipvn.net') !== false || strpos($episodeUrl , 'phimchon.com') !== false){
		    $html = getlink_phimvip($episodeUrl,$fileName);
		}elseif(strpos($episodeUrl , 'youtube.com') !== false){
		    $html = YoutbeDownloader::getInstance()->getLink($episodeUrl);
		}elseif(strpos($episodeUrl , 'sv3.phimle.tv') !== false){
		    $html = downloadLinkGk($episodeUrl,$fileName);
		}elseif(strpos($episodeUrl , '|') !== false){
		    $html = local_direct_down($episodeUrl,$fileName);
		}else{
		    $html = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$episodeUrl.'?title='.$fileName.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
		} 
	}else{
	    $file = explode('<jwplayer:source file="',$data_cache);
		$html = '';
		for($i=1;$i<=count($file)-1;$i++){
		    $linkp = explode('"',$file[$i]);
		    $qualityp = explode('label="',$file[$i]);
		    $qualityp = explode('"',$qualityp[1]);
            $html .= '<a class="btn btn-green btn-download " id="btn-download-'.$qualityp[0].'" href="'.$linkp[0].'?title='.$fileName.'"><span class="btn-text"><b>DOWNLOAD '.$qualityp[0].'p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';	
  			
		}
		$html = '<!-- Use cache -->'.$html.'<!--End Use cache /-->';
	}
	echo $html;
	exit();
}

?>