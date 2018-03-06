<?php
if($value[1]=='home-info' && is_numeric($value[2])){
    $filmID = (int)$value[2];
	$mysql->update("film","film_viewed = film_viewed + 1,film_viewed_day = film_viewed_day + 1,film_viewed_w = film_viewed_w + 1,film_viewed_m = film_viewed_m + 1","film_id = '".$filmID."'");
	$arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."film WHERE film_id = :id");
    $arr->execute(array('id' => $filmID));
	$row = $arr->fetch();
	if($row['film_id']){
	$filmPublish = $row['film_publish'];
	$filmThongbao = $row['film_thongbao'];
	$filmNAMEVN = $row['film_name'];
	$filmNAMEEN = $row['film_name_real'];
	$filmYEAR = $row['film_year'];
	$filmIMG = changeUrlGoogle($row['film_img']);
	$filmIMGBN = changeUrlGoogle($row['film_imgbn']);
	$film18 = $row['film_phim18'];
	$filmRAP = $row['film_chieurap'];
	$filmRATE = $row['film_rate'];
	$filmTRAILER = $row['film_trailer'];
        $filmLIKED = $row['film_liked'];
        $filmSLUG = $row['film_slug'];
	$filmRATETOTAL = $row['film_rating_total'];
	if($filmRATE != 0)
	$filmRATESCORE = round($filmRATETOTAL/$filmRATE,1);
        else $filmRATESCORE = 0;
	$filmSTATUS = $row['film_trangthai'];
	$filmTIME = $row['film_time'];
	$filmIMDb = ($row['film_imdb']?''.$row['film_imdb'].'':"N/A");
	$filmVIEWED = number_format($row['film_viewed']);
	$filmLB = $row['film_lb'];
	$filmPRODUCERS = TAGS_LINK2($row['film_area']);
	$filmDIRECTOR = TAGS_LINK2($row['film_director']);
	$filmACTOR = TAGS_ACTOR($row['film_actor']);
	$filmLANG = film_lang($row['film_lang']);
	$filmQUALITY = ($row['film_tapphim']);
	$filmTAGS = $row['film_tag'];
	$filmURL = $web_link.'/phim/'.$filmSLUG.'-'.replace($filmID).'/';
	$filmINFO = strip_tags(text_tidy1($row['film_info']),'<b><i><u><img><br><p>');
	$filmINFOcut = (strip_tags(text_tidy1($filmINFO)));
	
	if($filmLB == 0){
	    $Status = $filmQUALITY.' '.$filmLANG;
	}else{
	    $Status = $filmSTATUS.' '.$filmLANG;
	}
	$CheckCat = $row['film_cat'];
	$CheckCat = str_replace(",,",",",$CheckCat);
	$CheckCat = explode(',',$CheckCat);
	$CheckCountry = $row['film_country'];
	$CheckCountry = str_replace(',,',',',$CheckCountry);
	$CheckCountry		=	explode(',',$CheckCountry);
	$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
	if($filmLB == 0){
	    $filmcat = '<a href="'.$web_link.'/phim-le/" title="Phim lẻ vietsub hd, phim lẻ mới">Phim lẻ</a>, ';
	    $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/phim-le/" title="'.$language['moviesingle'].'"><span itemprop="title">'.$language['moviesingle'].' <i class="fa fa-angle-right"></i></span></a></li>';
	}elseif($filmLB == 1 || $filmLB == 2){
	    $filmcat = '<a href="'.$web_link.'/phim-bo/" title="Phim bộ vietsub hd, phim bộ mới">Phim bộ</a>, ';
	    $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/phim-bo/" title="'.$language['movieserial'].'"><span itemprop="title">'.$language['movieserial'].' <i class="fa fa-angle-right"></i></span></a></li>';
	}else{
	    $filmcat = '<a href="'.$web_link.'/phim-moi/" title="Phim sắp chiếu vietsub hd, phim sắp chiếu mới">Phim sắp chiếu</a>, ';
	    $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/phim-moi/" title="Phim Sắp chiếu"><span itemprop="title">Phim sắp chiếu <i class="fa fa-angle-right"></i></span></a></li>';
	}
	if($filmRAP == 1){
	     $filmchieurap = '<a href="'.$web_link.'/phim-chieu-rap/" title="Phim chiếu rạp vietsub hd, phim chiếu rạp mới">Phim chiếu rạp</a>, ';
	}else $filmchieurap = '';
	$film_cat = '';
	for ($i=1; $i<count($CheckCat)-1;$i++) {
	    $cat_namez	  =	get_data('cat_name','cat','cat_id',$CheckCat[$i]);
            $cat_namez_title	  =	get_data('cat_name_title','cat','cat_id',$CheckCat[$i]);
	    $cat_namez_key	  =	get_data('cat_name_key','cat','cat_id',$CheckCat[$i]);
		$film_cat 	.= '<a href="'.$web_link.'/the-loai/'.replace(strtolower(get_ascii($cat_namez_key))).'/" title="'.$cat_namez.'">'.$cat_namez.'</a>,  ';
	    $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/the-loai/'.replace(strtolower(get_ascii($cat_namez_key))).'/" title="'.$cat_namez.'"><span itemprop="title">'.$cat_namez.' <i class="fa fa-angle-right"></i></span></a></li>';
	}
	$breadcrumbs .= '<li><a class="current" href="'.$filmURL.'" title="'.$web_title.'">'.$filmNAMEVN.'</a></li>';
	$film_cat_info		=	$filmcat.$filmchieurap.$film_cat;
	$link_country="";
	for ($i=1; $i<count($CheckCountry)-1;$i++) {
	$film_country = get_data('country_name','country','country_id',$CheckCountry[$i]);
	$film_country_key = get_data('country_name_key','country','country_id',$CheckCountry[$i]);
	$link_country .= '<a href="'.$web_link.'/quoc-gia/'.replace(strtolower(get_ascii($film_country_key))).'/'.'" title="'.$film_country.'">'.$film_country.'</a>,  ';
	}
	$link_country_list = $link_country;
	$isEpisode = get_data_multi("episode_id","episode","episode_film = '".$filmID."' AND episode_servertype NOT IN (13,14)"); // có hoặc ko có episode play
    $isDown = get_data_multi("episode_id","episode","episode_film = '".$filmID."' AND episode_servertype IN (1,2,3,4,5,6,7,8,9,10,11,12,13,14)"); // có hoặc ko có episode download
        
	if($filmPublish == 1){
	    $filmWATCH = '<div class="copyright"><p class="copyright_del">DELETED</p><p class="copyright_desc">Phim bị xóa vì vi phạm bản quyền</p></div>';
		$filmDownload = '';
	}else{
	    if($isEpisode && $isDown){
		    $filmWATCH = '<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6"><div class="watch"> <a href="'.$filmURL.'xem-phim.html" title="'.$filmNAMEVN.'"><i class="micon play"></i>Xem phim</a> </div></div>';
			$filmDownload = ' <li class="item"><a id="btn-film-download" class="btn btn-green btn" title="Phim '.$filmNAMEVN.' VietSub HD | '.$filmNAMEEN.' '.$filmYEAR.'" href="'.$filmURL.'download.html"><i class="fa fa-download"></i>  Download</a></li>';
		}elseif(!$isEpisode && $isDown){
		    $filmWATCH = '';
			$filmDownload = ' <li class="item"><a id="btn-film-download" class="btn btn-green btn" title="Phim '.$filmNAMEVN.' VietSub HD | '.$filmNAMEEN.' '.$filmYEAR.'" href="'.$filmURL.'download.html"><i class="fa fa-download"></i>  Download</a></li>';
		}elseif($isEpisode && !$isDown){
            $filmWATCH = '<div class="col-lg-5 col-md-5 col-sm-6 col-xs-6"><div class="watch"> <a href="'.$filmURL.'xem-phim.html" title="'.$filmNAMEVN.'"><i class="micon play"></i>Xem phim</a> </div></div>';
			$filmDownload = '';
        }else{
            $filmWATCH = '';
			$filmDownload = '';
        }
	}	
		
	if($filmThongbao != '' && $filmPublish == 0){
	$filmNote = '<div class="block info-film-note"><div class="film-note"><h4 class="hidden">Lịch chiếu/ghi chú</h4>'.un_htmlchars($filmThongbao).'</div></div>';
	}else $filmNote = '';
	
	if($filmQUALITY == 'CAM' || $filmQUALITY == 'TS' || $filmQUALITY == 'SD'){
	
       $filmSub = 0;
	}else{ $filmSub = 1;}
	
	$web_title = 'Phim '.$filmNAMEVN.' ('.$filmNAMEEN.') '.$filmYEAR.' '.$filmQUALITY.'-'.$filmLANG;
	$web_keywords = $filmTAGS;
	if($row['film_des'] == '')
	$web_des = $web_title.', thể loại '.strip_tags($film_cat_info);
	else 
	$web_des = $row['film_des'];
	if($film18 == 1) $filmCanhbao18 = '<span class="canhbao18"></span> '; else $filmCanhbao18 = '';
	if(isset($_SESSION["user_id"])){$filmBox = get_data("user_filmbox","user","user_id",$_SESSION["user_id"]);if(strpos($filmBox, ','.$filmID.',') !== false){$filmLike_class = 'added';}else $filmLike_class = 'normal';}else{$filmLike_class = 'normal';}
if(($filmSub == 0) && ($filmLB == 0)){$subscribe = 0;}elseif($filmLB == 2){$subscribe = 2;}elseif($filmLB == 3){$subscribe = 3;}else $subscribe = 1;
?><!DOCTYPE html>
<html xmlns:og="http://ogp.me/ns#">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="en" />
<title><?=$web_title;?></title>
<meta name="description" content="<?=$web_des;?>"/>
<meta name="keywords" content="<?=$web_keywords;?>"/>
<link rel="canonical" href="<?=$filmURL;?>" />
<meta itemprop="url" content="<?=$filmURL;?>" />
<meta itemprop="image" content="<?=$filmIMGBN;?>" />
<meta itemprop="image" content="<?=$filmIMG;?>" />
<meta property="og:title" content="<?=$web_title;?>" />
<meta property="og:type" content="video.movie" />
<meta property="og:description" content="<?=$web_des;?>" />
<meta property="og:url" content="<?=$filmURL;?>" />
<meta property="og:image" content="<?=$filmIMGBN;?>" />
<meta property="og:image" content="<?=$filmIMG;?>" />
<? require_once("styles.php");?>
 <script type="text/javascript">
            var filmInfo = {};
            filmInfo.filmID = parseInt('<?=$filmID;?>');
            filmInfo.isAdult = parseInt('<?=$film18;?>');
        </script>
<? if($film18 == 1){ ?><script data-cfasync="false" type="text/javascript" src="http://www.pureadexchange.com/a/display.php?r=1016509"></script><? } ?>
</head> <body>
    <? require_once("header.php");?>
        <div id="body-wrapper">
            <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;"> </div>
            <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;"> </div>
            <div class="content-wrapper">
                <div class="container fit">
				    <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
                    <div class="main col-lg-8 col-md-8 col-sm-7">
                        <div class="block info-film" itemscope itemtype="http://schema.org/Movie">
                            
                            <div class="row">
                                <div class="col-sm-3 visible-sm-block col-xs-1 visible-xs-block"></div>
                                <div class="col1 col-md-5 col-sm-8 col-xs-10">
                                    <div class="poster"> <span class="status"><?=$filmQUALITY;?></span> <?=$filmCanhbao18;?><img src="<?=$filmIMG?>" alt="<?=$filmNAMEVN;?>">
									<div class="tools-box" style="display:block;"><div class="tools-box-bookmark <?=$filmLike_class;?>" style="display: block;"><span class="bookmark-status"><i class="fa fa-gittip"></i></span><span class="bookmark-action"></span></div></div> 
									<ul class="btn-block">
										<?=$filmDownload;?>
										<? if($filmTRAILER != ''){?>
										<li class="item trailer" id="trailer"><a id="btn-film-trailer" class="btn btn-primary btn-film-trailer" title="Phim <?=$filmNAMEVN;?> VietSub HD | <?=$filmNAMEEN;?> 2015" target="_blank" href="<?=$filmTRAILER;?>"><i class="fa fa-info"></i>  Trailer</a></li>
										<? } ?>
										</ul>
									</div>
									
									
                                </div>
                                <div class="clearfix visible-sm-block visible-xs-block"></div>
                                <div class="col2 col-md-7">
								
                                    <div class="name block-title style2">
                                        <h2 itemprop="name"><?=$filmNAMEVN;?></h2> </div>
                                    <div class="name2"> <dfn><?=$filmNAMEEN;?> (<?=$filmYEAR;?>)</dfn> </div>
                                    <dl>
                                        <dt>Status:</dt><dd class="status"><?=$Status;?></dd> <br>
										<dt>Thể loại:</dt><dd> <?=$film_cat_info;?> </dd> <br>
										<dt>Quốc gia:</dt><dd><?=$link_country_list;?></dd><br>
										<dt>Đạo diễn:</dt><dd><?=$filmDIRECTOR;?></dd><br>
										<dt>Diễn viên:</dt><dd><?=$filmACTOR;?></dd><br>
										<dt>Thời lượng:</dt><dd><?=$filmTIME;?></dd> <br>
										<dt>Năm phát hành:</dt><dd><a href="<?=$web_link;?>/phim-<?=$filmYEAR;?>/" title="Phim năm <?=$filmYEAR;?> vietsub hd, phim năm <?=$filmYEAR;?> mới"><?=$filmYEAR;?></a></dd> <br>
<dt>Đăng bởi:</dt><dd><?=get_data("user_name","user","user_id",$row["film_upload"]);?></dd>
                                    </dl>
                                    <div class="extra-info">
                                        <div class="views"> <i class="micon views"></i> <span><?=$filmVIEWED;?></span> </div>
                                        <div class="like"> <i class="micon heart"></i> <span><?=$filmLIKED;?> lượt</span> </div>
										<div class="imdbs"> <i class="micon imdb"></i> <span><?=$filmIMDb;?></span> </div>
										
                                    </div>
                                    <div class="buttons row">
                                        <?=$filmWATCH;?>
                                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6" itemscope itemtype="http://data-vocabulary.org/Review-aggregate">
										    
                                            <div class="widget rating" data-scores="<?=$filmRATESCORE;?>" data-count="<?=$filmRATE;?>">
                                                <div class="stars-wrap">
                                                    <div class="stars">
													    <?=showStar($filmRATESCORE);?>
													</div>
                                                </div>
                                                <div class="text" data-text="%s luợt"> <?=$filmRATESCORE;?> / <?=$filmRATE;?> lượt </div>
                                            </div> <span class="hidden" itemprop="votes"><?=$filmRATE;?></span> <span class="hidden" itemtype="http://data-vocabulary.org/Rating" itemscope itemprop="rating"> <span itemprop="average"><?=$filmRATESCORE;?></span>
                                            <meta itemprop="best" content="10">
                                            <meta itemprop="worst" content="1"> </span>
                                        </div>
                                    </div>
									
                                </div>
                            </div>
                        </div>
                        <!--/.block-->
						
<?if($subscribe != 1){?>
<div id="subscribe-wrapper" class="block message-block" style="display:block;">
<? if(checkNotif($filmID)){ $showNotif = "none"; echo subscribeOff($filmURL,hashNotif($filmID),$filmID); }else{$showNotif = "block";}?>
<? echo subscribeSuggest($filmLB,$filmURL,$filmNAMEVN,$showNotif); echo subscribeForm($filmURL);?>
</div><? }?>
                        <div class="block info-film-text">
                            <div class="widget-title clear-top"> <div class="tabs"> <div class="tab active"><span>Thông tin</span></div> </div> <div class="socials" style="display:inline-block;float:right;"><div class="fb-like" data-href="<?=$filmURL;?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div><div style="position: relative;top: 5px;left: 5px;display: inline-block;"><g:plusone></g:plusone></div></div></div>
                            <div class="block-body">
                                <div><strong><?=$filmNAMEVN;?></strong></div>
                                <div>
                                    <?=textlink_site($filmINFO,$filmNAMEEN,$filmURL);?>
                                </div>
								 <div style="width:100%;overflow:hidden;"><?=ShowAds("info_above_cmt");?></div>
                            </div>
		<div class="block block-tags">
                            <div class="widget-title">
     							<h3 class="title">Từ khóa</h3> 
								</div> 
                            <div class="block-body slider">
                                 <?=TAGS_LINK2($filmTAGS);?>
                            </div>

                        </div>
                        </div>
				

						<?=$filmNote;?>
						<div class="block comment">
                           
                            <div class="block-body">
                                <div class="fb-comments fb_iframe_widget" data-href="<?=$filmURL;?>" data-num-posts="10" data-width="100%" data-colorscheme="light"></div>
                            </div>
                        </div>
                        <!--.block-->
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
					<div class="block ad_location">
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
  <div class="block ad_location">
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
<?if($subscribe != 1){?><script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.subscribe.js" type="text/javascript"></script><? }?>
        <? require_once("footer.php");?>
</body>
</html><?}else header('Location: '.$web_link.'/404');  }else header('Location: '.$web_link.'/404'); ?>