<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>
        <?=$web_title;?>
    </title>
    <meta name="description" content="<?=$web_title;?>">
    <meta name="keywords" content="<?=$web_keywords;?>">
    <meta name="robots" content="index, follow">
    <meta name="revisit-after" content="1 days">
    <meta property="og:title" content="<?=$web_title;?>">
    <meta property="og:description" content="<?=$web_title;?>">
    <? require_once("styles.php");?>
    <link rel="stylesheet" type="text/css" href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/slick.css"/>		

</head>

<body>

    <? require_once("header.php");?>
        <div id="body-wrapper">
            <div class="block hotest">
                <div class="block-title">
                    <div class="stars"> <i></i> <i></i> <i></i> <span class="hidden-xs"><i></i> <i></i></span> </div>
                    <div class="title">Phim đề cử</div>
                    <div class="stars"> <i></i> <i></i> <i></i> <span class="hidden-xs"><i></i> <i></i></span> </div>
                </div>
                <div class="block-body slider">
                    <div class="container">
                        <div class="control prev"></div>
                        <div class="control next"></div>
                        <div class="list-film row owl-carousel owl-theme" id="pl-slide">
                            <?=ShowFilm('WHERE film_hot = 1','ORDER BY film_time_update',20,'showfilm_phimhot_home','cache_phimhot');?>


                        </div>
                    </div>
                </div>
                <div class="block-foot"></div>
            </div>


            <!--.hotest-->
            <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;display:none;"> </div>
            <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;display:none;"> </div>
            <div class="content-wrapper">
                <div class="container fit">
                    <div class="main col-lg-8 col-md-8 col-sm-7">
                        <div class="block movie-kinhdien">
                            
<div id="feature" class="slide fn-slide-show" data-fade="true" data-autoplay="true" data-arrows="false" data-slides-to-show="1" data-slides-to-scroll="1" data-infinite="true" data-speed="1000" data-custom-nav="#feature .dot"> 
<div class="slide-body non-opacity">
 <div class="slide-scroll"> 
<?=ShowFilm('WHERE film_kinhdien = 1','ORDER BY film_time_update',6,'showtemplate_phimkinhdien_scroll','cache_phimkinhdien_scroll');?>

 </div> <a href="#" class="zicon icon-arrow prev fn-prev"></a> <a href="#" class="zicon icon-arrow next fn-next"></a> </div>

 <div class="slide-thumb">
 <ul id="slide-ul"> 
<?=ShowFilm('WHERE film_kinhdien = 1','ORDER BY film_time_update',6,'showtemplate_phimkinhdien_thumb','cache_phimkinhdien_thumb');?>
 
 </ul> </div> <div class="clearfix"></div></div>
                        </div>
                        <div class="block movie-update">
                            <div class="col-left">
                                <div class="block-movie">
                                   <?=ShowFilm('WHERE film_lb IN (1,2) AND film_hot = 1','ORDER BY film_time_update',4,'showtemplate_phimbohot','cache_phimbohot_home');?>
                                </div>
                            </div>

                            <div class="col-right">
                                <div id="tabs-movie">
                                    <ul class="tabs-movie-block">
                                        <li class="tab-movie ui-tabs-active" id="tabs-1"><a href="#tabs-1" rel="nofollow">Phim lẻ mới</a></li>
                                        <li class="tab-movie" id="tabs-2"><a href="#tabs-2" rel="nofollow">Phim bộ mới</a></li>
                                        <li class="tab-movie" id="tabs-3"><a href="#tabs-3" rel="nofollow">Phim bộ full</a></li>
                                    </ul>
                                    <div class="clear"></div>
                                    <h2 class="hidden">Phim lẻ mới</h2>
                                    <ul class="tab-content" id="tabs-1">
                                        <?=ShowFilm('WHERE film_lb = 0','ORDER BY film_time_update',12,'showfilm_phimbo_home','cache_phimlenew_home');?>
                                    </ul>
                                    <h2 class="hidden">Phim bộ mới</h2>
                                    <ul class="tab-content" id="tabs-2" style="display: none;">
                                        <?=ShowFilm('WHERE film_lb IN (1,2)','ORDER BY film_time_update',12,'showfilm_phimbo_home','cache_phimbonew_home');?>

                                    </ul>
                                    <div class="clear"></div>
                                    <h2 class="hidden">Phim bộ mới hoàn thành</h2>
                                    <ul class="tab-content" id="tabs-3" style="display: none;">
                                        <?=ShowFilm('WHERE film_lb = 1','ORDER BY film_time_update',12,'showfilm_phimbo_home','cache_phimbodone_home');?>
                                    </ul>
                                    <div class="clear"></div>
                                </div>
                            </div>
                        </div>
						<div class="block ad" style="width:100%;overflow:hidden;">
						 <?=showAds('home_below_phimbo');?>
						 </div>
                        <div class="block update">
                          
							<div class="widget-title">
     							<h3 class="title">Phim mới cập nhật</h3> 
								<span class="tabs"> <div class="tab active" data-name="all" data-target=".block.update .content"><div class="name"><a title="Tất cả" href="javascript:void(0)">Tất cả</a></div></div>
								<div class="tab" data-name="le" data-target=".block.update .content"><div class="name"><a title="Phim lẻ" href="phim-le/">Phim lẻ</a></div></div>
								<div class="tab" data-name="bo" data-target=".block.update .content"><div class="name"><a title="Phim bộ" href="phim-bo/">Phim bộ</a></div></div>
                                <div class="tab" data-name="rap" data-target=".block.update .content"><div class="name"><a title="Phim bộ" href="phim-chieu-rap/">Phim chiếu rạp</a></div></div>								</span></div> 
							
                            <div class="block-body">
                                <div class="content" data-name="all">
                                    <div class="list-film row">
                                        <?=ShowFilm('WHERE film_lb IN (0,1,2) AND film_cat NOT LIKE "%,5,%"','ORDER BY film_time_update',20,'showfilm_template','cache_phimall');?>


                                    </div>
                                    <div class="more"> <a href="<?=$web_link;?>/phim-moi/">Phim mới</a> </div>
                                </div>
                                <div class="content hidden" data-name="le">
                                    <div class="list-film row">
                                        <?=ShowFilm('WHERE film_lb = 0 AND film_cat NOT LIKE "%,5,%"','ORDER BY film_time_update',20,'showfilm_template','cache_phimle');?>
                                    </div>
                                    <div class="more"> <a href="<?=$web_link;?>/phim-le/" title="Phim lẻ">Phim lẻ</a> </div>
                                </div>

                                <div class="content hidden" data-name="bo">
                                    <div class="list-film row">
                                        <?=ShowFilm('WHERE film_lb IN (1,2) AND film_cat NOT LIKE "%,5,%"','ORDER BY film_time_update',20,'showfilm_template','cache_phimbo');?>
                                    </div>
                                    <div class="more"> <a href="<?=$web_link;?>/phim-bo/" title="Phim bộ">Phim bộ</a> </div>
                                </div>
								<div class="content hidden" data-name="rap">
                                    <div class="list-film row">
                                        <?=ShowFilm('WHERE film_chieurap = 1 AND film_lb <> 3 AND film_cat NOT LIKE "%,5,%"','ORDER BY film_time_update',20,'showfilm_template','cache_phimrap');?>
                                    </div>
                                    <div class="more"> <a href="<?=$web_link;?>/phim-chieu-rap/" title="Phim chiếu rạp">Phim chiếu rạp</a> </div>
                                </div>
                            </div>
                        </div>
<div class="block ad" style="width:100%;overflow:hidden;">
						 <?=showAds('movie_sapchieu_below');?>
						 </div>
						 <div class="block update">
                          
							<div class="widget-title">
     							<h3 class="title">Phim hoạt hình</h3> 
								</div> 
							
                            <div class="block-body">
                                <div class="contentz" data-name="coming-soon">
                                    <div class="list-film row">
                                        <?=ShowFilm('WHERE film_cat LIKE "%,5,%"','ORDER BY film_time_update',8,'showfilm_template','cache_phimhoathinh');?>


                                    </div>
                                    <div class="more"> <a href="<?=$web_link;?>/the-loai/hoat-hinh/">Phim hoạt hình</a> </div>
                                </div>
                                
                            </div>
                        </div>
<div class="block ad" style="width:100%;overflow:hidden;"><?=showAds("home_above_comingsoon");?></div>
						<div class="block update">
                          
							<div class="widget-title">
     							<h3 class="title">Phim sắp chiếu</h3> 
								</div> 
							
                            <div class="block-body">
                                <div class="contentz" data-name="coming-soon">
                                    <div class="list-film row">
                                        <?=ShowFilm('WHERE film_lb = 3 AND film_trailer <> ""','ORDER BY RAND()',8,'showfilm_template','cache_phimsapchieu');?>


                                    </div>
                                    <div class="more"> <a href="<?=$web_link;?>/trailer/">Phim sắp chiếu</a> </div>
                                </div>
                                
                            </div>
                        </div>
                        <!--.block-->
               <div class="block ad" style="width:100%;overflow:hidden;">
						 <?=showAds('home_above_videos');?>
						 </div>

                        <!--.block-->
                        <div class="block update">
                            
							<div class="widget-title">
     							<h3 class="title">Video Clip</h3> 
								</div> 
                            <div class="block-body">
                                <div class="list-film clip row">
                                    <?=ShowVideo('','ORDER BY video_time',8,'showvideo_templates','home_video');?>

                                </div>
                                <div class="more"> <a href="videos.html">Xem tất cả</a> </div>
                            </div>
                        </div>
                        <!--.block-->
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
						<span class="tabs"><div class="tab active" data-name="request_list" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Yêu cầu/ tán gẫu</a></div></div>
							<div class="tab" data-name="request_post" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Gửi yêu cầu</a></div></div>	
								 </span>
						</div> 
						
						<div class="block-body">
<span class="rtips">Nhấn vào nút "Trả lời" để reply bình luận đó!</span>
						<div class="content" data-name="request_list" id="request_list_show">
						     <?=ShowRequest("WHERE request_type = 0","ORDER BY request_time",10,'showrequest_templates');?>
                        </div>
						<div class="content hidden" data-name="request_post">
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
                        <div class="block fanpage">


                            <div class="fb-page" data-href="https://www.facebook.com/phiimtv" data-width="339px" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                <div class="fb-xfbml-parse-ignore">
                                    <blockquote cite="https://www.facebook.com/phiimtv"><a href="https://www.facebook.com/phiimtv"></a></blockquote>
                                </div>
                            </div>

                        </div> 
                        <div class="block ad_location desktop hidden-sm hidden-xs">
                              <?=showAds('right_below_tags');?>
                       
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
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/slick.min.js" type="text/javascript"></script>
 <script> $(document).ready(function(){$('.slide-scroll').slick({slidesToShow: 1,slidesToScroll: 1,autoplay: true,arrows: true,fade: true,asNavFor: '.slide-thumb #slide-ul'});$('.slide-thumb #slide-ul').slick({slidesToShow: 6,slidesToScroll: 1,asNavFor: '.slide-scroll',dots: false,centerMode: false,focusOnSelect: true});});</script>
        <? require_once("footer.php");?>
</body>
</html>