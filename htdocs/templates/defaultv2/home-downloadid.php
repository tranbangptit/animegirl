<?php
if($value[1]=='home-downloadid' && is_numeric($value[2])){
        $episodeId = (int)$value[2];
	$arr = $mysqldb->prepare("SELECT * FROM ".DATABASE_FX."episode WHERE episode_id = :id");
        $arr->execute(array('id' => $episodeId));
	$row = $arr->fetch();
	$filmId = $row['episode_film'];
        if($filmId){
        $page = explode("download=",URL_LOAD);
        $pagetoken = (int)substr($page[1],-4);
        
	$episodeName = $row['episode_name'];
	$filmNAMEVN = get_data("film_name","film","film_id",$filmId);
	$filmNAMEEN = get_data("film_name_real","film","film_id",$filmId);
	$filmQuality = get_data("film_tapphim","film","film_id",$filmId);
	$filmYear = get_data("film_year","film","film_id",$filmId);
	$filmURL = $web_link.'/phim/'.replace(strtolower($filmNAMEVN)).'-'.replace($filmId).'/';
        if($pagetoken == $_SESSION['captcha']){
	$fileName = str_replace(" ",".",upperFirstChar(strtolower(get_ascii($filmNAMEVN))).'-'.upperFirstChar(strtolower($filmNAMEEN)));
?>
<html>
<head><title> Tải Phim <?=$filmNAMEVN;?> - <?=$filmNAMEEN;?> Tập <?=$episodeName;?> <?=$filmQuality;?></title>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta property="og:url" content="<?=$filmURL;?>" />
	<meta property="og:title" content=" Tải Phim <?=$filmNAMEVN;?>" />
	<meta property="og:description" content="Phim lẻ vietsub HD,xem phim hd miễn phí,xem phim vietsub hd online miễn phí,phim hàn quốc vietsub,phim vietsub hd full,phim vietsub hay,web xem phim không quảng cáo, phimle.tv" />
	<meta property="og:image" content="<?=$web_link;?>/logo.png" />
	<meta property="og:site_name" content=" Tải Phim <?=$filmNAMEVN;?> Tập <?=$episodeName;?> <?=$filmQuality;?>" />
	 <meta property="fb:app_id" content="<?=$cf_fanpageid;?>"> 
 <meta property="fb:admins" content="<?=$cf_admin_id;?>" />
 <meta property="og:updated_time" content="<?=NOW;?>" />
 <meta property="og:type" content="website"/>
 <meta name="author" content="PhimLe.Tv">
 <base href="<?=$web_link;?>/">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <script type="text/javascript">
    var	MAIN_URL	=	'<?=$web_link;?>';
    var	AjaxURL	=	'<?=$web_link;?>/load/download';
</script>
<meta name="robots" content="noindex, nofollow">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<base href="<?=$web_link;?>" />
<link href="http://www.phimmoi.net/styles/vtlai/movie/css/download-v2.css" type="text/css" rel="stylesheet">
<style>.none{display:none!important;}</style>
<script  type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script>
	function loadUrlDown(episodeId,episodeTitle) {
    $.post(AjaxURL,{loadUrlDown:1,episodeId:episodeId,episodeTitle:episodeTitle},function(data){
			 if (data == 0){ alert("Error!");
		    }else{
		$("#btnX").addClass('none');
		$("#download-list").html(data);

		}
		});
    return false
}
</script>
<!-- PopAds.net Popunder Code for www.phimle.tv -->
<script type="text/javascript">
  var _pop = _pop || [];
  _pop.push(['siteId', 707943]);
  _pop.push(['minBid', 0.000000]);
  _pop.push(['popundersPerIP', 0]);
  _pop.push(['delayBetween', 0]);
  _pop.push(['default', false]);
  _pop.push(['defaultPerDay', 0]);
  _pop.push(['topmostLayer', false]);
  (function() {
    var pa = document.createElement('script'); pa.type = 'text/javascript'; pa.async = true;
    var s = document.getElementsByTagName('script')[0]; 
    pa.src = '//c1.popads.net/pop.js';
    pa.onerror = function() {
      var sa = document.createElement('script'); sa.type = 'text/javascript'; sa.async = true;
      sa.src = '//c2.popads.net/pop.js';
      s.parentNode.insertBefore(sa, s);
    };
    s.parentNode.insertBefore(pa, s);
  })();
</script>
<!-- PopAds.net Popunder Code End -->
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.4";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>
<script src="https://apis.google.com/js/platform.js" async defer></script>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-60905015-1', 'auto');
  ga('send', 'pageview');

</script>
</head>
<body>

   <script type="text/javascript">
        var settimmer = 0;
		var fileName = "PhimLe.Tv---<?=$fileName;?>-<?=$filmYear;?>.[Tap.<?=$episodeName;?>]";
		var fileID = parseInt('<?=$episodeId;?>');
        $(function(){
                window.setInterval(function() {
                    var timeCounter = $("#countdown-time").html();
                    var updateTime = eval(timeCounter)- eval(1);
                    $("#countdown-time").html(updateTime);
                    if(updateTime == 0){
					  $(".timedown").addClass('none');
					   $("#loading").fadeIn('fast');
                        loadUrlDown(fileID,fileName);
$("#download-list a").click(function(){
    var href = $(this).attr("href");
    alert(href);
});
                    }
                }, 1000);

        });
    </script>
	
<div class="container clearfix">
<span class="filename" id="fileinfo-filename" title="<?=$filmNAMEVN;?> - <?=$filmNAMEEN;?> (<?=$filmYear;?>) [Tập <?=$episodeName;?>]">PhimLe.Tv---<?=$fileName;?>-<?=$filmYear;?>.[Tap.<?=$episodeName;?>].<?=$filmQuality;?></span>
<div id="download-note" class="download-note" style="display:none;"></div>
<div class="ad-left"><?=showAds("downloadid_left");?></div>
<div class="ad-right"><?=showAds("downloadid_right");?></div>
<div style="" id="btnX" class="btn btn-blue">
<span class="text1" id="countdown-info">Đang lấy link download...</span>
<span class="timedown" id="countdown-time">5</span>
<div id="loading" class="loading" style="">
<div id="fadingBarsG">
<div id="fadingBarsG_1" class="fadingBarsG"></div>
<div id="fadingBarsG_2" class="fadingBarsG"></div>
<div id="fadingBarsG_3" class="fadingBarsG"></div>
<div id="fadingBarsG_4" class="fadingBarsG"></div>
</div></div></div>
<div class="download-list" id="download-list">

</div>
<!-- code facebook like share -->
<div class="fb-like" data-href="<?=$filmURL;?>" data-layout="button_count" data-action="like" data-show-faces="false" data-share="true"></div>

<!-- /code facebook like share -->
<div style="clear:both"></div>
<div class="ad-bottom"><!-- code ad bottom: 728 x 90 --></div>
<!-- code facebook comment --><br>
<div class="fb-comments" data-href="<?=$filmURL;?>" data-width="960" data-numposts="5" data-colorscheme="dark"></div>

<!-- /code facebook comment --></div>	
</body></html>
<? }else header('Location: '.$filmURL.'download.html'); }else header('Location: '.$web_link.'/404');  }else header('Location: '.$web_link.'/404');  ?>