<?php 
if($value[1]=='home-video-cat'){
    $catKey = sql_escape($value[2]);
	$catId = get_data("cat_id","cat","cat_name_key",$catKey);
	if(isset($catId) && (int)$catId){
    $catName = get_data("cat_name_title","cat","cat_id",$catId);
    $page = explode("trang-",URL_LOAD);
	$page = explode(".html",$page[1]);
	$page =	(int)($page[0]);
	$rel = explode("?rel=",URL_LOAD);
	$rel = explode(".html",$rel[1]);
	$rel =	sql_escape(trim($rel[0]));
	if(strpos(URL_LOAD , 'rel=new') !== false || strpos(URL_LOAD , 'rel=popular') !== false || strpos(URL_LOAD , 'rel=name') !== false){
		    if(strpos(URL_LOAD , 'rel=popular') !== false){
			    $order_sql = "ORDER BY video_viewed DESC";
			}elseif(strpos(URL_LOAD , 'rel=new') !== false){
			    $order_sql = "ORDER BY video_time DESC";
			}elseif(strpos(URL_LOAD , 'rel=name') !== false){
			    $order_sql = "ORDER BY video_name ASC";
			}
			
		}else{
		    $order_sql = "ORDER BY video_time DESC";   
		}

	    $web_keywords = 'video clip hài,mv ca nhạc, video clip thể thao,bóng đá, tin tức 24h, video clip độc lạ, công nghệ 247, phimle.tv';
	    $web_des = 'Trang chia sẻ video clip hài hước, bóng đá, mv ca nhạc hd online, tin tức 24h, công nghệ mới nhất hay nhất 2016';
	    $web_title = 'Video Clip '.$catName.' hay | PhimLe.Tv';
		$breadcrumbs = '';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/videos.html" title="'.$language['videos'].'"><span itemprop="title">'.$language['videos'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li><a class="current" href="'.$web_link.'/videos/'.$catKey.'.html" title="'.$catName.'">'.$catName.'</a></li>';
		$pageURL = $web_link.'/videos/'.$catKey;
		
	$page_size = PAGE_SIZE;
	if (!$page) $page = 1;
	$limit = ($page-1)*$page_size;
    $q = $mysql->query("SELECT * FROM ".DATABASE_FX."video WHERE video_cat LIKE '%,".$catId.",%' ORDER BY video_time DESC LIMIT ".$limit.",".$page_size);
	$total = get_total("video","video_id"," WHERE video_cat LIKE '%,".$catId.",%' ");
	$ViewPage = view_pages('film',$total,$page_size,$page,$pageURL,$rel);
?>
<!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="vi" />
<title><?=$web_title;?></title>
<meta name="description" content="<?=$web_des;?>"/>
<meta name="keywords" content="<?=$web_keywords;?>"/>
<link rel="canonical" href="<?=$pageURL.'.html';?>" />
<meta itemprop="url" content="<?=$pageURL.'.html';?>" />
<meta itemprop="image" content="<?=$web_link.'/players/video-banner.jpg';?>" />
<meta property="og:title" content="<?=$web_title;?>" />
<meta property="og:description" content="<?=$web_des;?>" />
<meta property="og:url" content="<?=$pageURL.'.html';?>" />
<meta property="og:image" content="<?=$web_link.'/players/video-banner.jpg';?>" />
<link rel="stylesheet" href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/foundation.min.css">
	<link rel="stylesheet" href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/videopage.css?v=2">
<? require_once("styles.php");?>
<style>
.row {
    margin-right: 0;
    margin-left: 0;
}
.tabs {
    float: none;
}
</style>
</head> <body>  <? require_once("header.php");?>
 <div id="body-wrapper"> 
 <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;">  </div> 
 <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;"> </div>
 <div class="content-wrapper"> 
     <div class="container">
	  <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
<div class="row"> 	
	
	<div class="large-8 columns left-side">		
<div class="row pattern">
<h2 class="head-line-2">
	Video clip <?=$catName;?>

</h2>
 <?php 

while($row = $q->fetch(PDO::FETCH_ASSOC)){
    $videoURL = $web_link.'/xem-video/'.$row['video_key'].'-'.$row['video_id'].'.html';
	$videoNAME = $row['video_name'];
	$videoIMG = 'http://i.ytimg.com/vi/'.get_idyoutube($row['video_url']).'/mqdefault.jpg';
	$videoTIME = RemainTime($row['video_time_update']);
	$videoVIEWED = number_format($row['video_viewed']);
	$videoPOSTER = $row['video_upload'];
        $videoDURATION = ($row['video_duration']);
        if(is_numeric($videoPOSTER)){ 
	$videoUploader = get_data("user_name","user","user_id",$videoPOSTER);
	     if(isset($videoUploader) && $videoUploader != '') $videoPOSTER = $videoUploader; else $videoPOSTER = $videoPOSTER;
	}
	else $videoPOSTER = $videoPOSTER;
	   
	
?>	
	<div class="column medium-4 margin-bottom-5px">
				
			<div class="column medium-12 small-5 ratio16_9">
				<div class="box">
					<span class="video-time"><?=$videoDURATION;?></span>
					<a href="<?=$videoURL;?>" 
						title="Video clip <?=$videoNAME;?>">
						<img alt="Video clip <?=$videoNAME;?>" src="<?=$videoIMG;?>">
						<div class="description-clip"><?=$videoNAME;?></div>
					</a>
				</div>
			</div>
			<div class="column medium-12 small-7 video-detail">
				<a href="<?=$videoURL;?>" 
					title="Video clip <?=$videoNAME;?>"><strong><?=$videoNAME;?></strong></a>
				<div class="metadata-clip">
					<span class="user-icon"><?=$videoPOSTER;?></span>
					<span class="play-icon"><?=$videoVIEWED;?></span>
				</div>
			</div>
 		
	</div>
			<? }  ?>
</div>
<span class="page_nav"><?=$ViewPage;?></span>
	</div>
	
<div class="large-4 columns right-side">
	<div class="row margin-left-5px">
	<div class="row tag-cloud margin-left-5px bottom-margin-10px margin-left-5px">
	<div class="fb-page" data-href="https://www.facebook.com/phiimtv" data-width="100%" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                <div class="fb-xfbml-parse-ignore">
                                    <blockquote cite="https://www.facebook.com/phiimtv"><a href="https://www.facebook.com/phiimtv">Phim Lẻ</a></blockquote>
                                </div>
                            </div>
          </div>
		<div class="medium-6 large-12 columns pop-tab">
			<ul class="tabs" data-tab role="tablist">
			  <li class="tab-title">
			  	<a href="javascript:void(0)" data="#panel2-1" class="active"><h3>Xem nhiều hôm nay</h3></a>
			  </li>
			  <li class="tab-title">
			  	<a href="javascript:void(0)" data="#panel2-2"><h3>Hot tuần</h3></a>
			  </li>
			</ul>
			<div class="tabs-content">
			  <section role="tabpanel" aria-hidden="false" class="content active" id="panel2-1">
			  <?php 
$qview = $mysql->query("SELECT * FROM ".DATABASE_FX."video ORDER BY video_viewed_day DESC LIMIT 5");
while($row = $qview->fetch(PDO::FETCH_ASSOC)){
    $videoURL = $web_link.'/xem-video/'.$row['video_key'].'-'.$row['video_id'].'.html';
	$videoNAME = $row['video_name'];
	$videoIMG = 'http://i.ytimg.com/vi/'.get_idyoutube($row['video_url']).'/mqdefault.jpg';
	$videoTIME = RemainTime($row['video_time_update']);
	$videoVIEWED = number_format($row['video_viewed']);
	$videoPOSTER = $row['video_upload'];
        $videoDURATION = ($row['video_duration']);
        if(is_numeric($videoPOSTER)){ 
	$videoUploader = get_data("user_name","user","user_id",$videoPOSTER);
	     if(isset($videoUploader) && $videoUploader != '') $videoPOSTER = $videoUploader; else $videoPOSTER = $videoPOSTER;
	}
	else $videoPOSTER = $videoPOSTER;
	    
	
?>   							
			<div class="row">
				<div class="columns small-5 margin-bottom-5px ratio16_9">
					<div class="box">
						<span class="video-time"><?=$videoDURATION;?></span>
						<a href="<?=$videoURL;?>" 
							title="Video clip <?=$videoNAME;?>">
							<img alt="Video clip <?=$videoNAME;?>" src="<?=$videoIMG;?>">
						</a>
					</div>
				</div>
				<div class="columns small-7 margin-bottom-5px detail-group">
					<a href="<?=$videoURL;?>" title="Video clip <?=$videoNAME;?>"><strong><?=$videoNAME;?></strong></a>
					<div class="content-item"></div>
					<span class="play-icon"><?=$videoVIEWED;?></span>
				</div>
			</div>
 		
			<? } ?>			
			
 		
						  </section>
			  <section role="tabpanel" aria-hidden="true" class="content" id="panel2-2">
			    		 <?php 
$qview = $mysql->query("SELECT * FROM ".DATABASE_FX."video ORDER BY video_viewed_week DESC LIMIT 5");
while($row = $qview->fetch(PDO::FETCH_ASSOC)){
    $videoURL = $web_link.'/xem-video/'.$row['video_key'].'-'.$row['video_id'].'.html';
	$videoNAME = $row['video_name'];
	$videoIMG = 'http://i.ytimg.com/vi/'.get_idyoutube($row['video_url']).'/mqdefault.jpg';
	$videoTIME = RemainTime($row['video_time_update']);
	$videoVIEWED = number_format($row['video_viewed']);
	$videoPOSTER = $row['video_upload'];
        $videoDURATION = ($row['video_duration']);
        if(is_numeric($videoPOSTER)){ 
	$videoUploader = get_data("user_name","user","user_id",$videoPOSTER);
	     if(isset($videoUploader) && $videoUploader != '') $videoPOSTER = $videoUploader; else $videoPOSTER = $videoPOSTER;
	}
	else $videoPOSTER = $videoPOSTER;
	   
	
?>   							
			<div class="row">
				<div class="columns small-5 margin-bottom-5px ratio16_9">
					<div class="box">
						<span class="video-time"><?=$videoDURATION;?></span>
						<a href="<?=$videoURL;?>" 
							title="Video clip <?=$videoNAME;?>">
							<img alt="Video clip <?=$videoNAME;?>" src="<?=$videoIMG;?>">
						</a>
					</div>
				</div>
				<div class="columns small-7 margin-bottom-5px detail-group">
					<a href="<?=$videoURL;?>" title="Video clip <?=$videoNAME;?>"><strong><?=$videoNAME;?></strong></a>
					<div class="content-item"></div>
					<span class="play-icon"><?=$videoVIEWED;?></span>
				</div>
			</div>
 		
			<? } ?>							
			
 		
						  </section>
			</div>
		</div>
		<div class="large-12 columns text-center show-for-large-up top-margin-10px">	
		</div>
		<div class="large-12 columns text-center show-for-large-up bottom-margin-10px">			<div class="ad-box-300">
							<?=showAds('right_below_fanpage');?>
						</div>
		</div>
		<div class="medium-6 large-12 columns cat-group">
			<h3>Danh mục</h3>
<div class="cat-detail">
<? 
$qcat = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_child = 121 AND cat_type = 0 ORDER BY cat_order ASC");
while ($row = $qcat->fetch(PDO::FETCH_ASSOC)) {
$catURL = $web_link.'/videos/'.$row['cat_name_key'].'.html';
$catNAME = $row['cat_name'];
?>
				<a href="<?=$catURL;?>" title="Đi đến trang <?=$catNAME;?>"> <?=$catNAME;?></a>
				
	<? } ?>
</div>	
		</div>
	</div>	
	<div class="row tag-cloud top-margin-10px margin-left-5px bottom-margin-10px margin-left-5px">
		<h4>Tag Cloud</h4>
<div class="tag-detail">
				<a href="<?=$web_link;?>/videos/tag/hay" title="Đi đến trang hay"><h5>hay</h5></a>
			<a href="<?=$web_link;?>/videos/tag/sexy" title="Đi đến trang sexy"><h5>sexy</h5></a>
			<a href="<?=$web_link;?>/videos/tag/teen" title="Đi đến trang Teen"><h5>Teen</h5></a>
			<a href="<?=$web_link;?>/videos/tag/music-2" title="Đi đến trang nhạc"><h5>nhạc</h5></a>
			<a href="<?=$web_link;?>/videos/tag/man" title="Đi đến trang đàn ông"><h5>đàn ông</h5></a>
			<a href="<?=$web_link;?>/videos/tag/everyone" title="Đi đến trang everyone"><h5>everyone</h5></a>
			<a href="<?=$web_link;?>/videos/tag/tech" title="Đi đến trang tech"><h5>tech</h5></a>
			<a href="<?=$web_link;?>/videos/tag/women" title="Đi đến trang phụnữ"><h5>phụnữ</h5></a>
			<a href="<?=$web_link;?>/videos/tag/hai" title="Đi đến trang hài"><h5>hài</h5></a>
			<a href="<?=$web_link;?>/videos/tag/others" title="Đi đến trang khácc"><h5>khácc</h5></a>
			<a href="<?=$web_link;?>/videos/tag/men" title="Đi đến trang men"><h5>men</h5></a>
			<a href="<?=$web_link;?>/videos/tag/newclips-2" title="Đi đến trang clipmới"><h5>clipmới</h5></a>
			<a href="<?=$web_link;?>/videos/tag/maidam" title="Đi đến trang maidam"><h5>maidam</h5></a>
			<a href="<?=$web_link;?>/videos/tag/video-clip" title="Đi đến trang video clip"><h5>video clip</h5></a>
			<a href="<?=$web_link;?>/videos/tag/sexygirls" title="Đi đến trang sexygirls"><h5>sexygirls</h5></a>
			<a href="<?=$web_link;?>/videos/tag/sepakbola" title="Đi đến trang sepakbola"><h5>sepakbola</h5></a>
			<a href="<?=$web_link;?>/videos/tag/football" title="Đi đến trang bóng đá"><h5>bóng đá</h5></a>
			<a href="<?=$web_link;?>/videos/tag/ca-nhac" title="Đi đến trang ca nhac"><h5>ca nhac</h5></a>
			<a href="<?=$web_link;?>/videos/tag/highlight" title="Đi đến trang clip hay"><h5>clip hay</h5></a>
			<a href="<?=$web_link;?>/videos/tag/valentine" title="Đi đến trang Valentine"><h5>Valentine</h5></a>
			<a href="<?=$web_link;?>/videos/tag/clip-yeu-thich" title="Đi đến trang clip yeu thich"><h5>clip yeu thich</h5></a>
			<a href="<?=$web_link;?>/videos/tag/sexy-girls" title="Đi đến trang sexy girls"><h5>sexy girls</h5></a>
			<a href="<?=$web_link;?>/videos/tag/man-woman" title="Đi đến trang đàn ông phụ nữ"><h5>đàn ông phụ nữ</h5></a>
			<a href="<?=$web_link;?>/videos/tag/keren" title="Đi đến trang keren"><h5>keren</h5></a>
			<a href="<?=$web_link;?>/videos/tag/cute" title="Đi đến trang dễ thương"><h5>dễ thương</h5></a>
			<a href="<?=$web_link;?>/videos/tag/romantis" title="Đi đến trang romantis"><h5>romantis</h5></a>
			<a href="<?=$web_link;?>/videos/tag/danong" title="Đi đến trang Danong"><h5>Danong</h5></a>
			<a href="<?=$web_link;?>/videos/tag/musik" title="Đi đến trang musik"><h5>musik</h5></a>
			<a href="<?=$web_link;?>/videos/tag/vui" title="Đi đến trang vui"><h5>vui</h5></a>
			<a href="<?=$web_link;?>/videos/tag/soc" title="Đi đến trang Soc"><h5>Soc</h5></a>
	</div>
	</div>
	<div class="row text-center show-for-large-up margin-left-5px bottom-margin-10px">			<div class="ad-box-300">
						
						</div>
		</div>
</div></div>  		 </div> </div> </div> 
<script src="http://code.jquery.com/jquery-2.1.4.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plvideo.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
<? require_once("footer.php");?>
</body>
</html>
<? }else header('Location: '.$web_link.'/404?error='.$catKey); }else header('Location: '.$web_link.'/404');   ?>