<?php 
if($value[1]=='home-page-show'){

    $pageURL = sql_escape($value[2]);
    $page = explode("trang-",URL_LOAD);
	$page = explode(".html",$page[1]);
	$page =	(int)($page[0]);
	$rel = explode("?rel=",URL_LOAD);
	$rel = explode(".html",$rel[1]);
	$rel =	sql_escape(trim($rel[0]));
	$mysql->query("UPDATE ".DATABASE_FX."pages SET page_viewed = page_viewed + 1,
													page_viewed_d = page_viewed_d + 1,
													page_viewed_w = page_viewed_w + 1,
													page_viewed_m = page_viewed_m + 1 WHERE page_url = '".$pageURL."'");
	$arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."pages WHERE page_url = :url");
    $arr->execute(array('url' => $pageURL));
	$row = $arr->fetch();
if($row['page_id']){
	    $pageNAME = $row['page_name'];
	    $pageIMG = $row['page_img'];
	    $pageINFO = strip_tags(text_tidy1($row['page_info']),'<a><b><i><u><img><br><p><ol><ul><li><h1><h2><h3><span><strong><em>');
	    $pageTAGS = strip_tags(text_tidy1($row['page_tags']),'<a><b><i><u><img><br><p><ol><ul><li><h1><h2><h3><span><strong><em>');
	    $pageTIME = RemainTime($row['page_time_update']);
	    $pageVIEWED = number_format($row['page_viewed']);
	   
	    $web_keywords = 'phim, xem phim, phim hd, phim online, phim hd online, phim hd mien phi, xem phim mien phi, phim hay, phim hay nhất';
	    $web_des = 'Xem phim HD online chất lượng cao, tốc độ nhanh. Đến với PhimLẻ[Tv], các bạn sẽ được thưởng thức những bộ phim lôi cuốn và hấp dẫn nhất của điện ảnh thế giới';
	    $web_title = $pageNAME.' | PhimLẻ[Tv]';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/pages" title="'.$language['news'].'"><span itemprop="title">'.$language['news'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="#" title="'.$pageNAME.'">'.$pageNAME.'</a></li>';
	
		
	
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="vi" />
<title><?=$web_title;?></title>
<meta name="description" content="<?=$web_des;?>"/>
<meta name="keywords" content="<?=$web_keywords;?>"/>
<meta property="og:site_name" content="<?=$web_title;?>"/>
<? require_once("styles.php");?>
<style>.block-body.content-text img{max-width:100%;}</style>
</head>

<body>
    <? require_once("header.php");?>
    <div id="body-wrapper">
        <div class="content-wrapper">
            <div class="container">
			    <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
			    <div class="main col-lg-8 col-md-8 col-sm-7">
                
                <div class="block-body content-text">
                    <?=$pageINFO;?>
                </div>
                </div>
				 <div class="sidebar col-lg-4 col-md-4 col-sm-5">
<? if($pageURL == 'donate'){?>
						<div class="block donate_thanks">
                            <div class="widget-title">
     							<h3 class="title">Chân thành cảm ơn các mạnh thường quân:</h3> 
								</div> 
                            <div class="block-body" style="padding: 10px;background: rgb(5, 5, 5) none repeat scroll 0% 0%;">
                                <div class="announcement-list"><?=$pageTAGS;?></div>
                            </div>
                        </div>
					<? } ?>
<div class="block announcement">
                            <div class="widget-title">
     							<h3 class="title">Thông báo</h3> 
								</div> 
                            <div class="block-body">
                                <div class="announcement-list"><?=strip_tags(text_tidy1($announcement),'<a><b><i><u><br>');?></div>
                            </div>
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
							 <form role="form">	  
							    <p><input type="text" value="<?if(isset($_COOKIE['RequestUserName'])) echo $_COOKIE['RequestUserName'];?>" id="request_name" name="request_name" placeholder="Your name" <? if(isset($_COOKIE['RequestUserName'])) echo 'disabled';?>>
							    <input class="input-post" id="request_submit" name="request_submit" type="submit" value="Gửi Y/C" onclick="return reqPost();"></p>	
							    <textarea rows="4" id="request_txt" name="request_txt" style="width:100%" placeholder="Bạn muốn yêu cầu phim gì?"></textarea>    		
 <input value="0" type="hidden" name="request_id" id="request_id">
							 </form>	</div>
                        </div>
                        </div>
                        </div>
                        
 
                         <div class="block fanpage">
                            <div class="fb-page" data-href="https://www.facebook.com/phiimtv" data-width="100%" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                <div class="fb-xfbml-parse-ignore">
                                    <blockquote cite="https://www.facebook.com/phiimtv"><a href="https://www.facebook.com/phiimtv">Phim Lẻ</a></blockquote>
                                </div>
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

</html>

<? }else header('Location: '.$web_link.'/404');  }else header('Location: '.$web_link.'/404');  ?>