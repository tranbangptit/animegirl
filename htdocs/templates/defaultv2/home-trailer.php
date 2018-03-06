<?php
if($value[1]=='home-trailer' && is_numeric($value[2])){
    $filmID = (int)$value[2];
	$mysql->update("film","film_viewed = film_viewed + 1,film_viewed_day = film_viewed_day + 1,film_viewed_w = film_viewed_w + 1,film_viewed_m = film_viewed_m + 1","film_id = '".$filmID."'");
	$arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."film WHERE film_id = :id");
    $arr->execute(array('id' => $filmID));
	$row = $arr->fetch();
if($row['film_id']){
	$filmNAMEVN = $row['film_name'];
	$filmNAMEEN = $row['film_name_real'];
	$filmYEAR = $row['film_year'];
	$filmTRAILER = $row['film_trailer'];
	$film18 = $row['film_phim18'];
	$filmLIKED = number_format($row['film_liked']);
	$filmRATE = $row['film_rate'];
	$filmRATETOTAL = $row['film_rating_total'];
        if($filmRATE != 0)
	$filmRATESCORE = round($filmRATETOTAL/$filmRATE,1); else $filmRATESCORE = 0;
	$filmIMG = changeUrlGoogle($row['film_img']);
	$filmIMGBN = changeUrlGoogle($row['film_imgbn']);
	$filmSTATUS = $row['film_trangthai'];
        $filmLIKED = $row['film_liked'];
	$filmVIEWED = number_format($row['film_viewed']);
        $filmIMDb = ($row['film_imdb']?''.$row['film_imdb'].'':"N/A");
	$filmLB = $row['film_lb'];
	$filmLANG = film_lang($row['film_lang']);
	$filmQUALITY = ($row['film_tapphim']);
	$filmTAGS = $row['film_tag'];
        $filmSLUG = $row['film_slug'];
	$filmURL = $web_link.'/phim/'.$filmSLUG.'-'.replace($filmID).'/';
	$filmINFO = strip_tags(text_tidy1($row['film_info']),'<a><b><i><u><img><br><p>');
	$filmINFOcut = cut_string(text_tidy1(strip_tags($filmINFO)),160);
	$web_title = 'Tập '.$EpisodeNAME.' '.$filmNAMEVN.' ('.$filmNAMEEN.') '.$filmYEAR.' '.$filmQUALITY.'-'.$filmLANG;
	$web_keywords = $filmTAGS;
	$web_des = $filmINFOcut;
	if($filmLB == 0){
	    $Status = $filmQUALITY.' '.$filmLANG;
	}else{
	    $Status = $filmSTATUS.' '.$filmLANG;
	}
	$CheckCat = str_replace(',,,',',',$row['film_cat']);
	$CheckCat = str_replace(',,',',',$CheckCat);
	$CheckCat		=	explode(',',$CheckCat);
	$CheckCountry = str_replace(',,,',',',$row['film_country']);
	$CheckCountry = str_replace(',,',',',$CheckCountry);
	$CheckCountry		=	explode(',',$CheckCountry);
	$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
	if($filmLB == 0)
	    $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/phim-le/" title="'.$language['moviesingle'].'"><span itemprop="title">'.$language['moviesingle'].' <i class="fa fa-angle-right"></i></span></a></li>';
	else
	    $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/phim-bo/" title="'.$language['movieserial'].'"><span itemprop="title">'.$language['movieserial'].' <i class="fa fa-angle-right"></i></span></a></li>';
    $film_cat = '';
$cat_namez_title = '';
	for ($i=1; $i<count($CheckCat)-1;$i++) {
	    $cat_namez	  =	get_data('cat_name','cat','cat_id',$CheckCat[$i]);
$cat_namez_title	  .=	get_data('cat_name_title','cat','cat_id',$CheckCat[$i]).',';
	    $cat_namez_key	  =	get_data('cat_name_key','cat','cat_id',$CheckCat[$i]);
		$film_cat 	.= '<a href="'.$web_link.'/the-loai/'.replace(strtolower(get_ascii($cat_namez_key))).'/" title="'.$cat_namez.'">'.$cat_namez.'</a> ,';
	    $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/the-loai/'.replace(strtolower(get_ascii($cat_namez_key))).'/" title="'.$cat_namez.'"><span itemprop="title">'.$cat_namez.' <i class="fa fa-angle-right"></i></span></a></li>';
	}
$cat_namez_title = substr($cat_namez_title,0,-1);
	$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" class="current" href="'.$filmURL.'" title="'.$web_title.'">'.$filmNAMEVN.'</a></li>';
	$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb">Trailer</li>';
$film_countryz_title = "";
	for ($i=1; $i<count($CheckCountry)-1;$i++) {
	$film_countryz_title .= get_data('country_name_title','country','country_id',$CheckCountry[$i]).',';
	}
	$film_countryz_title = substr($film_countryz_title,0,-1);
	$film_cat_info		=	substr($film_cat,0,-7);
	if($filmIMGBN) $img = $filmIMGBN; else $img = $filmIMG;
	$player = phimle_players($EpisodeURL,$filmID,$episode_id,$EpisodeTYPE,$EpisodeSUB,$img);
	$check_f = $mysql->query("SELECT user_id FROM ".DATABASE_FX."user WHERE user_filmbox LIKE '%,".$filmID.",%' AND user_id = '".$_SESSION["user_id"]."' ORDER BY user_id ASC");
	$fbox = $check_f->fetch(PDO::FETCH_ASSOC);
	if($fbox['user_id']){
	    $like = $language['liked'];
	}else $like = $language['like'];
	$filmPublish = $row['film_publish'];
	$filmThongbao = $row['film_thongbao'];
	if($filmThongbao != '' && $filmPublish == 0){
	$filmNote = '<div class="block info-film-note"><div class="film-note"><h4 class="hidden">Lịch chiếu/ghi chú</h4>'.un_htmlchars($filmThongbao).'</div></div>';
	}else $filmNote = '';
?><!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="vi" />
<title><?=$web_title;?></title>
<meta name="description" content="<?=$web_des;?>"/>
<meta name="keywords" content="<?=$web_keywords;?>"/>
<link rel="canonical" href="<?=$filmURL;?>" />
<meta itemprop="url" content="<?=$filmURL;?>" />
<meta itemprop="image" content="<?=$filmIMGBN;?>" />
<meta itemprop="image" content="<?=$filmIMG;?>" />
<meta property="og:title" content="<?=$web_title;?>" />
<meta property="og:type" content="video.episode" />
<meta property="og:description" content="<?=$web_des;?>" />
<meta property="og:url" content="<?=$filmURL;?>" />
<meta property="og:image" content="<?=$filmIMGBN;?>" />
<meta property="og:image" content="<?=$filmIMG;?>" />
        <? require_once("styles.php");?>
	 <script type="text/javascript">
            var filmInfo = {};
            filmInfo.episodeID = parseInt('<?=$episode_id;?>');
            filmInfo.filmID = parseInt('<?=$filmID;?>');
            filmInfo.filmVIEWED = parseInt('<?=$filmVIEWED;?>');
            filmInfo.playTech = '<?=playTech();?>';
        </script>
</head>

    <body>
        <? require_once("header.php");?>
            <div id="body-wrapper">
                <!--<div class="ad_location background-dark">
                    <div class="ad_location container desktop hidden-sm hidden-lg" style="padding-top: 0px; margin-bottom: 15px;"> </div>
                    <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;"> </div>
                </div>-->
                <div class="content-wrapper background-dark watch-page">
                    <div class="container fit">
					<div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
                        <div class="main col-lg-8 col-md-8">
<div class="block info-film watch-film" itemscope="" itemtype="http://schema.org/Movie" style="display:block;">
                            
                            <div class="row">
                                <div class="col-sm-3 visible-sm-block col-xs-1 visible-xs-block"></div>
                                <div class="col1 col-md-3 col-sm-8 col-xs-10">
                                    <div class="poster"> <span class="status"><?=$filmQUALITY;?></span> <img src="<?=thumbimg($row['film_img'],200);?>" alt="<?=$filmNAMEVN;?>">
<div class="movie-watch-link-box"><a class="movie-watch-link" href="<?=$filmURL;?>download.html" title="Trailer <?=$filmNAMEVN;?> - <?=$filmNAMEEN;?>">Download</a></div>
									<div class="tools-box" style="display:block;"><div class="tools-box-bookmark normal" style="display: block;"><span class="bookmark-status"><i class="fa fa-gittip"></i></span><span class="bookmark-action"></span></div></div> 
									
									</div>
									
									
                                </div>
                                <div class="clearfix visible-sm-block visible-xs-block"></div>
                                <div class="col2 col-md-9">
								
                                    <div class="name block-title style2">
                                        <h2 itemprop="name">Trailer <?=$filmNAMEVN;?></h2> </div>
                                    <div class="name2"> <dfn>Trailer <?=$filmNAMEEN;?> (<?=$filmYEAR;?>)</dfn> </div>
                                    <dl>
                                        <dt>Status:</dt><dd class="status"><?=$Status;?></dd> <br />
                                        <dt></dt><dd> <?=cut_string(text_tidy1(strip_tags($filmINFO)),450);?> </dd> 
                                    </dl>
                                    <div class="extra-info">
                                        <div class="views"> <i class="micon views"></i> <span><?=$filmVIEWED;?></span> </div>
                                        <div class="like"> <i class="micon heart"></i> <span><?=$filmLIKED;?> lượt</span> </div>
										<div class="imdbs"> <i class="micon imdb"></i> <span><?=$filmIMDb;?></span> </div>
										
                                    </div>
                                    
									
                                </div>
                            </div>
                        </div>
                            <div class="block media">
                                <div class="block-title" style="display:none;"> Xem phim <?=$filmNAMEVN;?> / Tập <?=$EpisodeNAME;?></div>
                                <div class="block-body" style="position: relative;">
								<? if($filmTRAILER == ""){?>
								<div class="error-not-available"><div class="alert-container"><div class="alert-inner" style="padding: 24px 30px;"><div class="alert-heading">Trailer chưa có</div><div class="alert-subheading">Hiện tại phim này chưa có trailer chính thức. Khi nào có chuyên trang sẽ cập nhật ngay!<br /> Mong bạn thông cảm!</div></div></div></div>
							<?	}else{?>
                                    <div class="ad_location desktop hidden-sm hidden-xs"> </div>
                                    <div class="ad_location mobile hidden-lg hidden-md"> </div>
                                    <div id="abd_mv">
                                        <div id="player-area" style="padding:10px;height:400px;">    
										<iframe src="https://www.youtube.com/embed/<?=get_idyoutube($filmTRAILER);?>?modestbranding=1&amp;iv_load_policy=3&amp;showinfo=1&amp;rel=0&amp;enablejsapi=1&amp;origin=http://www.phimle.tv" allowfullscreen="true" mozallowfullscreen="true" webkitallowfullscreen="true" scrolling="no" frameborder="no" height="100%" width="100%"></iframe>
                                        </div>
                                    </div>
                                    
									<? } ?>
                                </div>
                            </div>
                            <!--.block-->
                            <div class="ad_location desktop hidden-sm hidden-xs"> </div>
                            <div class="ad_location mobile hidden-lg hidden-md"> </div>
                            
                            <!--.block-->
<div class="block fblikepl" style="margin-top:10px;"> <div class="block-body">
							<div class="fb-like" data-href="<?=$filmURL;?>" data-layout="button_count" data-action="like" data-show-faces="true" data-share="true"></div>
<div style="position: relative;top: 5px;left: 5px;display: inline-block;"><g:plusone></g:plusone></div>
</div>
</div>
                            <!--<div class="row ad_location desktop hidden-sm hidden-xs">
                                <div class="col-lg-6 col-md-6"></div>
                                <div class="col-lg-6 col-md-6"></div>
                            </div>
                            <div class="ad_location mobile hidden-lg hidden-md"> </div>-->
							<?=$filmNote;?>
                            <div class="block comment">
                                  <div class="widget-title clear-top"> <div class="tabs"> <div class="tab active"><span>Bình luận</span></div> </div> </div>
                                <div class="block-body">
                                    <div class="fb-comments fb_iframe_widget" data-href="<?=$filmURL;?>" data-num-posts="10" data-width="100%" data-colorscheme="dark"></div>
                                </div>
                            </div>
                            <div class="block list-film-slide">
                                 <div class="widget-title">
     							<h3 class="title">Phim liên quan</h3> 
								</div>
                                <div class="block-body slider">
                                    <div class="control prev"></div>
                                    <div class="control next"></div>
                                    <div class="list-film row" id="pl-slidez">
                                         <?=ShowFilm("WHERE film_id <> '".$filmID."' AND (MATCH (film_name,film_name_real,film_name_ascii,film_tag,film_tag_ascii) AGAINST ('".text_preg_replace($filmNAMEVN." ".$filmNAMEEN)."' IN BOOLEAN MODE) OR film_cat LIKE '%".$row['film_cat']."%')","ORDER BY MATCH (film_name,film_name_real,film_name_ascii,film_tag,film_tag_ascii) AGAINST ('".text_preg_replace($filmNAMEVN." ".$filmNAMEEN)."' IN BOOLEAN MODE) ",8,"relate_film","");?>
                                    </div>
                                </div>
                            </div>
                            <!--/.block-->
                        </div>
                        <!--/.main-->
                         <div class="sidebar col-lg-4 col-md-4 col-sm-5">
<div class="block announcement">
                            <div class="widget-title">
     							<h3 class="title">Thông báo</h3> 
								</div> 
                            <div class="block-body">
                                <div class="announcement-list"><?=strip_tags(text_tidy1($announcement),'<a><b><i><u><br>');?></div>
                            </div>
                        </div>
                       <div class="block ad_location desktop hidden-sm hidden-xs" id="ads_location">
                              <?=showAds('right_below_fanpage');?>
                        </div>
						<div class="block chatting">
						<div class="widget-title">
						<span class="tabs"><div class="tab " data-name="request_list" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Yêu cầu/ tán gẫu</a></div></div>
							<div class="tab active" data-name="request_post" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Gửi yêu cầu</a></div></div>	
								 </span>
						</div> 
						
						<div class="block-body">
<span class="rtips">Nhấn vào nút "Trả lời" để reply bình luận đó!</span>
						<div class="content hidden" data-name="request_list" id="request_list_show">
						     <?=ShowRequest("WHERE request_type = 0","ORDER BY request_time",10,'showrequest_templates');?>
                        </div>
						<div class="content " data-name="request_post">
						     <div class="chat-form" style="margin-bottom:10px">
							 <span id="chat-error" style="display:none;"></span>	
							<?=chatForm();?></div>
                        </div>
                        </div>
                        </div>
                        <div class="block interested">
						<div class="widget-title">
     							<h3 class="title">Phim hot tuần</h3> 
								<span class="tabs"><div class="tab active" data-name="lew" data-target=".block.interested .content"><div class="name"><a title="Phim lẻ" href="phim-le/">Phim lẻ</a></div></div>
								<div class="tab" data-name="bow" data-target=".block.interested .content"><div class="name"><a title="Phim bộ" href="phim-bo/">Phim bộ</a></div></div>
								 </span></div> 
								
                          
                            <div class="block-body">
                                <div class="content" data-name="lew">
                                    <div class="list-film-simple">
                                        <?=ShowFilm("WHERE film_lb = 0","ORDER BY film_viewed_w",10,'showfilm_right_home','phimle_hotw');?>


                                    </div>
                                </div>
                                <div class="content hidden" data-name="bow">
                                    <div class="list-film-simple">

                                        <?=ShowFilm("WHERE film_lb IN (1,2)","ORDER BY film_viewed_w",10,'showfilm_right_home','phimbo_hotw');?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.block-->
  <div class="block ad_location desktop hidden-sm hidden-xs">
                              <?=showAds('right_below_tags');?>
                       
                        </div>
                        <div class="block fanpage">
                            <div class="fb-page" data-href="https://www.facebook.com/phiimtv" data-width="100%" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                <div class="fb-xfbml-parse-ignore">
                                    <blockquote cite="https://www.facebook.com/phiimtv"><a href="https://www.facebook.com/phiimtv">Phim Lẻ</a></blockquote>
                                </div>
                            </div>

                        </div>
                        <div class="block ad_location mobile hidden-lg hidden-md">

                        </div>
                        <div class="block tagcloud">
                            <div class="widget-title">
     							<h3 class="title">Từ khóa phổ biến</h3> 
								</div> 
                            <div class="block-body">
                                <ul>

                                    <? require_once("hot_tags_home.php");?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--.sidebar-->
                    </div>
                </div>
            </div>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery-2.1.0.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/owl.carousel.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.notie.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.public.js" type="text/javascript"></script>		 
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>


            <? require_once("footer.php");?>
    </body>

    </html><?}else header('Location: '.$web_link.'/404'); }else header('Location: '.$web_link.'/404'); ?>