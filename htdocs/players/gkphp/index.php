<h1>Player Gkphp</h1>
<form method="POST">
    <input size="70" type="text" name="url" value="" />
    <input type="submit" value="Get link" />
</form>
<br /> 
Link demo: <br />
+ https://picasaweb.google.com/112880659605268162978/KSTVNAkumuTantei2006SDKSTJMuxed?authkey=Gv1sRgCLXP7dmP-PumyQE<br />
+ https://www.youtube.com/watch?feature=player_embedded&v=x2mM8Ugt4E8<br />
+ http://tv.zing.vn/video/Thai-Tu-Phi-Thang-Chuc-Ky-Tap-32/IWZBDI9Z.html<br />
+ https://docs.google.com/file/d/0BzUY9W8jdmrVTUREVGJiWFAzdFE/view <br />
<?php 
if(isset($_POST['url']) && $_POST['url'] != ''){
    $url = trim($_POST['url']);
?>
<script type="text/javascript" src="plugins/gkpluginsphp.js"></script>
<div id="player1" style="width:600px;height:400px;background-color:#999"></div>
<script type="text/javascript">gkpluginsphp("player1",{link:"<?=$url;?>"});</script>
<? } 