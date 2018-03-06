<?php 
if($value[1]=='home-asked'){
    $key = sql_escape($value[2]);
	if($key){
	$where = "AND request_slug = '".$key."'";
	}else $where = "";
    if(strpos(URL_LOAD , 'trang-') !== false){
    $page = explode("trang-",URL_LOAD);
	$page = explode(".html",$page[1]);
	$page =	(int)($page[0]);
    }
	
     $web_keywords = 'xem phim lẻ miễn phí, phim hd miễn phí, phim vietsub, phim thuyết minh lồng tiếng cực hay, phimle.tv';
	    $web_des = 'Trang yêu cầu phim ảnh, video clip hài hước, bóng đá, mv ca nhạc hd online, tin tức 24h, công nghệ mới nhất hay nhất 2016 trên phimle.tv';
	     $web_title = 'Yêu cầu | Hỏi đáp | Tán gẫu | PhimLe.Tv';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li><a class="current" href="'.$web_link.'/asked.html" title="Yêu cầu phim">Asked</a></li>';
		$pageURL = $web_link.'/asked';
		
	$page_size = PAGE_SIZE;
	if (!$page) $page = 1;
	$limit = ($page-1)*$page_size;
    $q = $mysql->query("SELECT * FROM ".DATABASE_FX."request WHERE request_type = 0 ".$where." ORDER BY request_time DESC LIMIT ".$limit.",".$page_size);
	$total = get_total("request","request_id","WHERE request_type = 0 ".$where." ORDER BY request_time");
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
<style>
#form-search-request .icon {
    background-position: -151px 3px;
    width: 46px;
    height: 46px;
    position: absolute;
    top: 6px;
    left: 15px;
    background-image: url("<?=STATIC_URL;?>/<?=$CurrentSkin;?>/images/img.png");
    background-repeat: no-repeat;
}
#form-search-request .keyword {
    line-height: 46px;
    padding-left: 46px;
    padding-right: 20px;
    font-size: 13px;
    color: #DDD;
    width: 100%;
    outline: 0px none;
    box-sizing: border-box;
}
#form-search-request .submit {
    background-position: -151px 3px;
    width: 46px;
    cursor: pointer;
    display: none;
}
#form-search-request .keyword, #form-search-request .submit {
    float: left;
    background-color: #1E1E1E;
    height: 46px;
    border: 1px solid #1D1C1C;
}
</style>
</head> <body>
    <? require_once("header.php");?>
        <div id="body-wrapper">
            <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;"> </div>
            <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;"> </div>
            <div class="content-wrapper">
                <div class="container fit">
				    <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
                    <div class="main col-lg-8 col-md-8 col-sm-7">
                        <div class="block chatting" itemscope itemtype="http://schema.org/Movie">
                           <div style="overflow: hidden;"><form style="" method="post" onsubmit="return false;" action="" class="style2" id="form-search-request">
      				<i class="icon"></i> 
					<input name="keyrequest" class="input keyword" placeholder="Nhập chính xác tên của bạn!" type="text">
					<input class="submit" value="" type="submit">
	</form></div>
 <div class="chat-form" style="margin-bottom:10px">
							 <span id="chat-error" style="display:none;"></span>	
							<?=chatForm();?></div>                           
 <div class="row" id="request_list_show" style="margin: 0px;">
							<?php 
if($total){
while($row = $q->fetch(PDO::FETCH_ASSOC)){
   $requestID = $row['request_id'];
	$requestNAME = $row['request_title'];
	$requestSLUG = $web_link.'/asked/'.$row['request_slug'].'.html';
	$requestContentz = $row['request_content'];
	$request_content = strip_tags(text_tidy1($requestContentz),'<a><b><i><u><img><br>');
	$requestContent = (textlink_site(replace_tag_a($request_content)));
 $rep = get_data("request_id","request","request_type",$requestID);
	if($rep) $reply = ShowRequestChild($requestID,10); else $reply = "";
	$requestTime = RemainTime($row['request_time']);
       $admin = get_data_multi("user_id","user","user_name = '".$requestNAME."' AND user_level = '3'");
        if($admin) $iscl = 'style="color:#ff0000;"'; else $iscl = '';
	    include("templates/".$CurrentSkin."/".$file.".php");
	
?>
                                <div class="item"><div class="details"><div class="meta"><cite><span><a href="<?=$requestSLUG;?>" id="request-<?=$requestID;?>" <?=$iscl;?>><?=$requestNAME;?></a></span> says: </cite><time><?=$requestTime;?></time><tool id="<?=$requestID;?>" title="Trả lời bình luận của <?=$requestNAME;?>" rel="<?=$requestNAME;?>">Trả lời</tool></div><div class="message"><?=$requestContent;?></div><?=$reply;?></div></div>
	<? } }else{ ?>	
   <div class="alert alert-warning" style="padding: 10px; margin: 10px;">							
    Chưa có yêu cầu nào được gửi!
	</div>
<? } ?>	
<span class="page_nav">
							<?=$ViewPage;?>
							</span>
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
					<div class="block ad_location desktop hidden-sm hidden-xs">
                              <?=showAds('right_below_fanpage');?>
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
<?if($subscribe != 1){?><script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.subscribe.js" type="text/javascript"></script><? }?>
<script>jQuery(document).ready(function(t){jQuery('#form-search-request').submit(function(){function string_to_slug(str){str = str.replace(/^\s+|\s+$/g, '');str = str.toLowerCase();var from = "ấầẩẫậẤẦẨẪẬắằẳẵặẮẰẲẴẶáàảãạâăÁÀẢÃẠÂĂếềểễệẾỀỂỄỆéèẻẽẹêÉÈẺẼẸÊíìỉĩịÍÌỈĨỊốồổỗộỐỒỔÔỘớờởỡợỚỜỞỠỢóòỏõọôơÓÒỎÕỌÔƠứừửữựỨỪỬỮỰúùủũụưÚÙỦŨỤƯýỳỷỹỵÝỲỶỸỴđĐ·/_,:;";var to = "aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaeeeeeeeeeeeeeeeeeeeeeeiiiiiiiiiioooooooooooooooooooooooooooooooooouuuuuuuuuuuuuuuuuuuuuuyyyyyyyyyydd------";for (var i=0, l=from.length ; i<l ; i++) {str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));}str = str.replace(/[^a-z0-9 -]/g, '').replace(/\s+/g, '-').replace(/-+/g, '-');return str;}
	    var keywordObj=jQuery(this).find('input[name=keyrequest]')[0];
		if(typeof keywordObj!='undefined'&&keywordObj!=null){var keyword=jQuery(keywordObj).val();keyword=string_to_slug(keyword);keyword=jQuery.trim(keyword);if(keyword==''){alert("Vui lòng điền nội dung cần tìm!");jQuery(keywordObj).focus();return false;}window.location.replace('/asked/'+keyword+'.html');}return false;});
	});
</script>
        <? require_once("footer.php");?>
</body>
</html>
<? }else header('Location: '.$web_link.'/404');  ?>