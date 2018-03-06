<?php 
if(isset($_GET['url']) && $_GET['url'] != '')
$ytUrl = trim($_GET['url']);
else $ytUrl = 'https://www.youtube.com/watch?v=329rn4BThFk';
?>
<meta charset="UTF-8" />
<link href="css/video-js.min.css" rel="stylesheet">
<script src="js/jquery.js" type="text/javascript"></script>
<script src="js/jquery-ui.js" type="text/javascript"></script>
<div style="width:100%;height:100%;display:block;position:relative;">
<video id="playerayer" class="video-js hxplayer-skin" controls autoplay width="100%" height="100%" tabindex="-1" data-setup="">
<?php
require_once 'YoutbeDownloader.php';
$qualitys = YoutbeDownloader::getInstance()->getLink($ytUrl);
if(is_string($qualitys))
{
    echo    $qualitys;
}
else {
    foreach ($qualitys as $video) {
        echo $js;
    }
}
?>
</video> 
</div>
 <script type="text/javascript">
	hxplayer("#playerayer", {
		autoplay: true,
		ended: '360p',
	});
</script>	