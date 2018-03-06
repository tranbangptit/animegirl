<?php
if(isset($_GET['url']) && $_GET['url'] != ''){
    $url = trim($_GET['url']);
        if(strpos($url , 'dailymotion') !== false){
	        $player = '<video id="phimle_playertv" class="video-js vjs-default-skin" controls preload="auto" width="100%" height="100%" data-setup=\'{ "techOrder": ["dailymotion"], "dmControls" : "0", "src": "'.$url.'" }\'></video>';
        }else $player = 'Lỗi!';

?>
<html>
<head>
<link href="http://www.phimle.tv/players/videojs_style.css" rel="stylesheet">
<script src="http://www.phimle.tv/players/player_script.js" type="text/javascript"></script> 
<script src="http://www.phimle.tv/players/player_dailymotion.js"></script>
</head>
<body style="margin:0;">
<? echo $player;
}
?>
</body>
</html>