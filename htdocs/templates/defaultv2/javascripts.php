<script type="text/javascript">
    var	MAIN_URL	=	'<?=$web_link;?>';
    var	AjaxURL	=	'<?=$web_link;?>/ajax';
</script>
<div id="fb-root"></div>
<script>
    (function(d, s, id) { 
     	var js, fjs = d.getElementsByTagName(s)[0]; 
		if (d.getElementById(id)) return;
		js = d.createElement(s);
		js.id = id; 
		js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.3&appId=547998618678011";
		fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));

</script>
<!--[if lt IE 9]>
    <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/respond.min.js"></script>
    <script src="<?=STATIC_URL;?>/templates/<?=$CurrentSkin;?>/js/excanvas.min.js"></script> 
    <![endif]-->
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery-2.1.0.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery-migrate.min.js" type="text/javascript"></script>
 
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/bootstrap.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/bootstrap-hover-dropdown.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/templates/<?=$CurrentSkin;?>/js/jquery.slimscroll.min.js" type="text/javascript"></script>
 
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/metronic.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/layout.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/star-rating.min.js" type="text/javascript"></script>
<script>
      	jQuery(document).ready(function() {    
        Metronic.init();
		Layout.init();
		
      	});
   	</script>
 
 
<script type="text/javascript" src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.mobile.customized.min.js"></script>
<script type="text/javascript" src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/camera.min.js"></script>
<script type="text/javascript" src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plscripts.js" ></script>