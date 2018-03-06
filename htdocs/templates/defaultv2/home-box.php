<?php 
if($value[1]=='home-box'){
if(isset($_SESSION["user_id"])){
    $UserID = (int)$_SESSION["user_id"];
    $UserNAME = $_SESSION["user_name"];
    $page = explode("trang-",URL_LOAD);
	$page = explode(".html",$page[1]);
	$page =	(int)($page[0]);
	$rel = explode("?rel=",URL_LOAD);
	$rel = explode(".html",$rel[1]);
	$rel =	sql_escape(trim($rel[0]));
	if(strpos(URL_LOAD , 'rel=new') !== false || strpos(URL_LOAD , 'rel=popular') !== false || strpos(URL_LOAD , 'rel=year') !== false  || strpos(URL_LOAD , 'rel=name') !== false){
		    if(strpos(URL_LOAD , 'rel=popular') !== false){
			    $order_sql = "ORDER BY film_viewed DESC";
			}elseif(strpos(URL_LOAD , 'rel=new') !== false){
			    $order_sql = "ORDER BY film_id DESC";
			}elseif(strpos(URL_LOAD , 'rel=year') !== false){
			    $order_sql = "ORDER BY film_year DESC";
			}elseif(strpos(URL_LOAD , 'rel=name') !== false){
			    $order_sql = "ORDER BY film_name ASC";
			}
			
		}else{
		    $order_sql = "ORDER BY film_time_update DESC";   
		}

	   
	    $web_keywords = 'xem phim của '.$UserNAME.' full hd, phim của '.$UserNAME.' online, phim của '.$UserNAME.' vietsub, phim của '.$UserNAME.' thuyet minh, phim  long tieng, phim của '.$UserNAME.' tap cuoi';
	    $web_des = 'Phim của '.$UserNAME.' hay tuyển tập, phim của '.$UserNAME.' mới nhất, tổng hợp phim của '.$UserNAME.', phim của '.$UserNAME.' full HD, phim của '.$UserNAME.' vietsub, xem phim của'.$UserNAME.' online';
	    $web_title = $UserNAME.' | BST phim '.$UserNAME.'';
		$breadcrumbs .= '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/account/info" title="'.$language['account2'].'"><span itemprop="title">'.$language['account2'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="#" title="'.$UserNAME.'">'.$UserNAME.'</a></li>';
	    $h1title = $language['filmbox_of'].' '.$UserNAME;
		$pageURL = $web_link.'/account/film';
		$name = $UserID;
	   
        
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
    <div id="body-wrapper">
        <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;">
            
        </div>
        <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;">
           
        </div>
        <div class="content-wrapper">
            <div class="container fit">
                <div class="main col-lg-8 col-md-8 col-sm-7">
                    <div class="block">
                        <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
                       
                        <div class="block-body">
                            <div class="list-film row">
				<?php 
				 $boxphim = get_data('user_filmbox','user','user_id',$UserID );
				 
if($boxphim != ','){
$list =  substr($boxphim,1); // Cắt chuối con từ vị trí 1 đến hết chuỗi
        $list = substr($list,0,-1); //Cắt từ vị trí số 6 đếm từ cuối chuỗi đến hết chuỗi
	$page_size = PAGE_SIZE;
	if (!$page) $page = 1;
	$limit = ($page-1)*$page_size;
    $q = $mysql->query("SELECT * FROM ".DATABASE_FX."film WHERE film_id IN ($list) $order_sql LIMIT ".$limit.",".$page_size);
	$total = get_total("film","film_id","WHERE film_id IN ($list) $order_sql");
	$ViewPage = view_pages('film',$total,$page_size,$page,$pageURL,$rel);
while($row = $q->fetch(PDO::FETCH_ASSOC)){
$filmID = $row['film_id'];
$filmNAMEVN = $row['film_name'];
$filmNAMEEN = $row['film_name_real'];
$filmIMG = thumbimg($row['film_img'],200);
$filmSLUG = $row['film_slug'];
$filmURL = $web_link.'/phim/'.$filmSLUG.'-'.replace($filmID).'/';
$filmQUALITY = $row['film_tapphim'];
$filmSTATUS = str_replace('Hoàn tất','Full',$row['film_trangthai']);
	$filmVIEWED = number_format($row['film_viewed']);
	$filmLANG = film_lang($row['film_lang']);
if($row['film_lb'] == 0){
	    $Status = $filmQUALITY.'-'.$filmLANG;
	}else{
	    $Status = $filmSTATUS.'-'.$filmLANG;
	}
	
?>
                                <div class="item col-lg-3 col-md-3 col-sm-6 col-xs-6" id="listp-<?=$filmID;?>" data="<?=$filmNAMEVN;?>">
                                    <div class="inner">
                                        <a class="poster" href="<?=$filmURL;?>" title="<?=$filmNAMEVN;?> - <?=$filmNAMEEN;?>"> <img src="<?=$filmIMG;?>" alt="<?=$filmNAMEVN;?>">  </a> <span class="status"><?=$Status;?></span> <a class="name" href="<?=$filmURL;?>" title="<?=$filmNAMEVN;?> - <?=$filmNAMEEN;?>"><?=$filmNAMEVN;?></a> <dfn><?=$filmNAMEEN;?></dfn> <dfn><?=$filmYEAR;?></dfn><span class="movie-item-remove" onclick="BoxDel(<?=$filmID;?>);"></span> </div>
                                </div>
  <? } ?>
  </div> 
    <span class="page_nav">
							<?=$ViewPage;?>
							</span>
  <? }else{ ?>
<p class="bg-warning" style="padding: 20px">Chưa có dữ liệu, Bạn có thể sử dụng nút "Thích" lúc xem phim để lưu phim vào bộ nhớ nếu như muốn xem sau :D</p>
</div> 
<? } ?>                              
                          
                        </div>
                    </div>
                    <!--.block-->
                </div>
                <!--/.main-->
                 <div class="sidebar col-lg-4 col-md-4 col-sm-5">
                        <div class="block">


                            <div class="fb-page" data-href="https://www.facebook.com/phiimtv" data-width="100%" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                <div class="fb-xfbml-parse-ignore">
                                    <blockquote cite="https://www.facebook.com/phiimtv"><a href="https://www.facebook.com/phiimtv">Phim Lẻ</a></blockquote>
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
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plfilter.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
    <? require_once("footer.php");?>
</body>

</html>

<?}else header('Location: '.$web_link);  }else header('Location: '.$web_link.'/404');  ?>