 <meta property="fb:app_id" content="<?=$cf_fanpageid;?>"> 
 <meta property="fb:admins" content="<?=$cf_admin_id;?>" />
 <meta property="og:updated_time" content="<?=NOW;?>" />
 <meta property="og:site_name" content="PhimLe.Tv" />
 <meta property="og:type" content="website"/>
 <meta name="author" content="PhimLe.Tv">
 <base href="<?=$web_link;?>/">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
<!-- inject:css -->
 <link href="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/css/all.css" rel="stylesheet">
 <!-- endinject -->
 <script type="text/javascript">
    var	MAIN_URL	=	'<?=$web_link;?>';
    var	AjaxURL	=	'<?=$web_link;?>/ajax';
</script>
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/vi_VN/sdk.js#xfbml=1&version=v2.5";
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
<?=showAds("ads_popup_header");?>