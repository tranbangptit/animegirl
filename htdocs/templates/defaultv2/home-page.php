<?php 
if($value[1]=='home-page'){

    $UserID = (int)$_SESSION["user_id"];
    $UserNAME = $_SESSION["user_name"];
    $page = explode("trang-",URL_LOAD);
	$page = explode(".html",$page[1]);
	$page =	(int)($page[0]);
	$rel = explode("?rel=",URL_LOAD);
	$rel = explode(".html",$rel[1]);
	$rel =	sql_escape(trim($rel[0]));
	if(strpos(URL_LOAD , 'rel=new') !== false || strpos(URL_LOAD , 'rel=popular') !== false || strpos(URL_LOAD , 'rel=name') !== false){
		    if(strpos(URL_LOAD , 'rel=popular') !== false){
			    $order_sql = "ORDER BY page_viewed DESC";
			}elseif(strpos(URL_LOAD , 'rel=new') !== false){
			    $order_sql = "ORDER BY page_time DESC";
			}elseif(strpos(URL_LOAD , 'rel=name') !== false){
			    $order_sql = "ORDER BY page_name ASC";
			}
			
		}else{
		    $order_sql = "ORDER BY page_time_update DESC";   
		}

	    $web_keywords = 'xem phim lẻ miễn phí, phim hd miễn phí, phim vietsub, phim thuyết minh lồng tiếng cực hay, phimle.tv';
	    $web_des = 'Bản tin phim của PhimLe.Tv. Cập nhật từng phút, từng giờ các tin tức về các bộ phim. Hãy theo dõi để tình hình về các bộ phim cũng như các đạo diễn, diễn viên, nhà sản xuất.';
	    $web_title = 'Tin tức - Bản tin phim - Tin tức điện ảnh 24/7';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li><a class="current" href="'.$web_link.'/pages" title="'.$language['news'].'">'.$language['news'].'</a></li>';
		$pageURL = $web_link.'/pages';
		
	$page_size = PAGE_SIZE;
	if (!$page) $page = 1;
	$limit = ($page-1)*$page_size;
    $q = $mysql->query("SELECT * FROM ".DATABASE_FX."pages $order_sql LIMIT ".$limit.",".$page_size);
	$total = get_total("pages","page_id"," $order_sql");
	$ViewPage = view_pages('film',$total,$page_size,$page,$pageURL,$rel);
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
</head>
<body>
<? require_once("header.php");?>
<div class="page-container">
  <? require_once("left.php");?>
    <div class="page-content-wrapper">
        <div class="page-content" style="min-height:1269px">
            <div class="page-bar">
                <ul class="page-breadcrumb" itemscope itemtype="http://data-vocabulary.org/Breadcrumb">
                <?=$breadcrumbs;?>
                </ul>
            </div>
            <div class="row">
<div class="col-md-9">
<div class="portlet light">
<div class="portlet-title">
<div class="caption">
<h1 class="caption-subject font-purple-seance uppercase">
<i class="icon-list font-purple-seance"></i> Bản tin phim - Tin tức điện ảnh 24/7 </h1>
</div>

</div>
<div class="portlet-body">
<div class="article-block">

<?php 
if($total){
while($row = $q->fetch(PDO::FETCH_ASSOC)){
    $pageURL = $web_link.'/pages/'.$row['page_url'].'.html';
	$pageNAME = $row['page_name'];
	$pageIMG = thumbimg($row['page_img']);
	$pageTIME = RemainTime($row['page_time_update']);
	$pageVIEWED = number_format($row['page_viewed']);
	$pageINFO = cut_string(strip_tags(text_tidy1($row['page_info'])),500);
	
?>
<div class="row">
<div class="col-md-4 blog-img blog-tag-data">
<a href="<?=$pageURL;?>" title="<?=$pageNAME;?>"><img src="<?=$pageIMG;?>" alt="<?=$pageNAME;?>" class="img-responsive"></a>
<ul class="list-inline">
<li>
<i class="fa fa-calendar"></i>
<?=$pageTIME;?>
</li>
<li>
<i class="fa fa-eye"></i>
<?=$pageVIEWED;?> 
</li>
</ul>
</div>
<div class="col-md-8 blog-article">
<h3><a href="<?=$pageURL;?>" title="<?=$pageNAME;?>"><?=$pageNAME;?> </a></h3>
<p><?=$pageINFO;?></p>
<a class="btn blue" href="<?=$pageURL;?>">Xem chi tiết <i class="fa fa-arrow-circle-o-right"></i>
</a>
</div>
</div>
<hr>
<? } }else{ ?>
Rất tiếc chưa có tin tức nào được đăng trong trang này!

<? } ?>

</div><br><br><div class="vaochinhgiua"><ul class="pagination pagination-lg"><?=$ViewPage;?></ul></div></div></div></div>
<div class="col-md-3">
<a class="dashboard-stat dashboard-stat-light red" href="https://docs.google.com/forms/d/1_UxlR4wiGZvdpIQWxedx69QNuqWe0U9VD1fz1n65IUw/viewform">
<div class="visual">
<i class="fa fa-comments"></i>
</div>
<div class="details">
<div class="desc">
<h3 style="left:-20px;position:relative;"><b>BẠN ĐANG TÌM PHIM?</b></h3>
</div>
<div class="desc">
Bấm vào đây để PhimLẻ[Tv] giúp bạn<br>tìm phim nhanh nhất!
</div>
</div>
</a>
<div class="portlet light hidden-sm hidden-xs">
<div class="portlet-title tabbable-line">
<div class="caption">
<span class="caption-subject font-purple-seance uppercase"><?=$language['moviesingle_hot_w'];?></span>
</div>
</div>
<div class="portlet-body">
<div class="tab-content">
<div class="scroller" style="height:477px" data-always-visible="1" data-rail-visible="0">
<?=ShowFilm("WHERE film_lb = 0","ORDER BY film_viewed_w",10,'showfilm_right_home','phimle_hotw');?>
 </div>
</div>
</div>
</div>
<div class="portlet light hidden-sm hidden-xs">
<div class="portlet-title tabbable-line">
<div class="caption">
<span class="caption-subject font-purple-seance uppercase"><?=$language['movieserial_hot_w'];?></span>
</div>
</div>
<div class="portlet-body">
<div class="tab-content">
<div class="scroller" style="height:477px" data-always-visible="1" data-rail-visible="0">
<?=ShowFilm("WHERE film_lb IN (1,2)","ORDER BY film_viewed_w",10,'showfilm_right_home','phimbo_hotw');?></div>
</div>
</div>
</div>
</div>
</div> 
 <script src="<?=$web_link;?>/templates/<?=$CurrentSkin;?>/js/pdnghia.js"></script>
<? require_once("footer.php");?>
</div>
</div>
 
 
 
</div>
<div class="scroll-to-top" style="display: none;">
<i class="icon-arrow-up"></i>
</div>
<div style="margin-bottom:2px;">
</div>  
<? require_once("javascripts.php");?>
</body>
</html>
<? }else header('Location: '.$web_link.'/404');  ?>