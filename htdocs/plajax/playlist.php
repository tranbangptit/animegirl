<?php
define('TRUNKSJJ',true);
ob_start();
session_start();
include('../includes/configurations.php');
include('../includes/players.php');
$filmId = (int)$_GET['filmId'];
$episodeId = (int)$_GET['episodeId'];
$q = $mysqldb->prepare("SELECT episode_url,episode_name,episode_urlsub FROM ".DATABASE_FX."episode WHERE episode_id = :id AND episode_film = :film");
$q->execute(array('id' => $episodeId,'film' => $filmId));
	$row = $q->fetch();
	$episodeUrl = $row['episode_url'];
        $episodeSub = $row['episode_urlsub'];
	if(strpos($episodeUrl , 'picasaweb.google.com') !== false){
	    $link = picasa_direct($episodeUrl,'flash');
            
	}elseif(strpos($episodeUrl , 'plus.google.com') !== false){
	    $link = plus_google_direct($episodeUrl,'flash');
	}elseif(strpos($episodeUrl , 'photos.google.com') !== false){
	    $link = photos_google($episodeUrl,'flash');
	}elseif(strpos($episodeUrl , '://docs.google.com') !== false || strpos($episodeUrl , '://drive.google.com') !== false){
	    $link = docs_direct($episodeUrl,'flash');
	}elseif(strpos($episodeUrl , 'tv.zing.vn/video') !== false){
	    $link = ZingTV_direct($episodeUrl,'flash');
	}elseif(strpos($episodeUrl , 'phimvipvn.net') !== false || strpos($episodeUrl , 'phimchon.com') !== false){
	    $link = phimvip_getlink($episodeUrl,'flash');
	}elseif(strpos($episodeUrl , 'youtube.com') !== false){
            $qualitys = YoutbeDirect::getInstance()->getLink($episodeUrl);
            if(is_string($qualitys)){$link =  $qualitys;}else {$links ='';foreach ($qualitys as $video) {$links .= $js;}$link =  $links;}
	    $link = str_replace("&","&amp;",$link);
	}elseif(strpos($episodeUrl , '123movies.to') !== false){
	    $link = get_123movies($episodeUrl,'flash');
	}elseif(strpos($episodeUrl , 'userscloud.com') !== false){
	    $links = get_userscloud($episodeUrl,'flash');
            $link = '<jwplayer:source file="'.$links.'" label="720" type="mp4" />';
	}elseif(strpos($episodeUrl , 'sv3.phimle.tv') !== false){
	    $link = sourceLinkGk($episodeUrl);
	}elseif(strpos($episodeUrl , 'phimmoi.net') !== false){
	    $link = '<jwplayer:source file="http://www.phimmoi.net/player.php?url='.get_phimmoi($episodeUrl).'" label="360" type="mp4" />';
	}elseif(strpos($episodeUrl , '|') !== false){
	    $link = local_direct($episodeUrl,$episodeId,'flash');
	}else{
            $episodeUrl = str_replace("&","&amp;",$episodeUrl);
	    $link = '<jwplayer:source file="'.$episodeUrl.'" label="360" type="mp4" />';
	}
        $link = str_replace("lh3.googleusercontent.com","2.bp.blogspot.com",$link);
	$bannerImg = get_data('film_imgbn','film','film_id',$filmId);
        if($bannerImg == '') $bannerImg = WEB_URL.'/players/no-banner.jpg';
	$filmName = get_data('film_name','film','film_id',$filmId);
if($episodeSub != ''){
    $sub = '<jwplayer:track file="'.$episodeSub.'" label="VietNam" kind="captions" default="true"/>';
}else{
 $sub = '';
}
header('Content-Type: application/xml; charset=utf-8');
$xml = '<?xml version="1.0" encoding="UTF-8"?>
<rss xmlns:jwplayer="http://rss.jwpcdn.com/">
  <channel>
    <title>'.$filmName.'</title>
    <description></description>
    <link>https://www.phimle.tv</link>
    <item>
      <title>Tập '.$row['episode_name'].'</title>
      <description></description>
      <guid>PhimLe.Tv</guid>
      <jwplayer:image>'.$bannerImg.'</jwplayer:image>
      '.$link.'
      '.$sub.'
    </item>
  </channel>
</rss>';
echo $xml;
exit();
?>