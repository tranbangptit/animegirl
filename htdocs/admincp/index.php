<?php
session_start();
error_reporting(E_ERROR);
//error_reporting(E_ALL ^ E_NOTICE);
header('Content-type: text/html;charset=utf-8');
ini_set('display_errors', 0);
ini_set('memory_limit', '512M'); // increase this if you catch "Allowed memory size..."
define('TRUNKSJJ', true);
define('TRUNKSJJ_ADMIN', true);
include("../includes/configurations.php");
require_once("../includes/phpfastcache.php");
$phpFastCache = phpFastCache();//Gọi hàm	
include("../includes/upload.php");
include("../includes/form.php");
include("admin_functions.php");
include("../includes/string.php");
if(isset($_SESSION['admin_level']))
$level = $_SESSION['admin_level'];
else $level = false;
$form = new HTMLForm;
if (!$level) { 
include("login-modal.php");
exit();
} 
require '../upanh/vendor/ChipVN/ClassLoader/Loader.php';

ChipVN_ClassLoader_Loader::registerAutoload();

$config = require '../upanh/includes/config.php';

$uploader = ChipVN_ImageUploader_Manager::make(ucfirst('picasanew'));

include("ImageResize.php");

$ImageResize = new SimpleImage();


$link = 'index.php';
if ($_SERVER["QUERY_STRING"]) $link .= '?'.$_SERVER["QUERY_STRING"];
if(isset($_GET['act']))
$act = $_GET['act'];
else $act = false;
?>
<!DOCTYPE html>
<html lang="en" class="app">
<head>
  <meta charset="utf-8" />
  <title><?=$web_title;?> - PhimLẻ[Tv] CONTROL PANEL</title>
  <meta name="description" content="app, web app, responsive, admin dashboard, admin, flat, flat ui, ui kit, off screen nav" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="template/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="template/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="template/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="template/css/font.css" type="text/css" />
  <link rel="stylesheet" href="template/js/calendar/bootstrap_calendar.css" type="text/css" />
  <link rel="stylesheet" href="template/css/app.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="template/js/ie/html5shiv.js"></script>
    <script src="template/js/ie/respond.min.js"></script>
    <script src="template/js/ie/excanvas.js"></script>
  <![endif]-->
  <script type="text/javascript" src="template/ckeditor/ckeditor.js"></script> 
</head>
<body>
  <section class="vbox">
    <? include("template/header.php");?>
    <section>
      <section class="hbox stretch">
        <!-- .aside -->
        <? include("template/aside.php");?>
        <!-- /.aside -->
        <section id="content">
            <?php
	switch($act){
	case "episode":				include("episode.php");break;
	case "video":				include("video.php");break;
	case "page":				include("page.php");break;
	case "cache":				include("cache.php");break;
	case "leech":				include("multi_add_leech.php");break;
	case "contact":				include("contact.php");break;
	
	case "mananhnho":			include("mananhnho.php");break;
	case "lech_dienvien":		include("lech_dienvien.php");break;
	case "multi_edit_episode":	include("multi_edit_episode.php");break;
	case "edit_episode":		include("edit_episode.php");break;
	case "cat":					include("cat.php");break;
	case "country":				include("country.php");break;
	case "dienvien":			include("dienvien.php");break;
	case "film":				include("film.php");break;
	case "multi_edit_film":		include("multi_edit_film.php");break;
	case "skin":				include("skin.php");break;
	case "ads":					include("ads.php");break;
	case "adspos":					include("adspos.php");break;
	case "user":				include("user.php");break;
	case "news":				include("news.php");break;
	case "config":				include("configures.php");break;
	case "comment":				include("comment.php");break;
	case "local":				include("local.php");break;
	case "trailer":				include("trailer.php");break;
        case "notif":				include("notif.php");break;
	case "request":				include("request.php");break;
	case "tags":				include("tags.php");break;
	case "player":				include("player.php");break;
	case "permission":			include("permission.php");break;
	case "text":    			include("textlink.php");break;
	case "main"	:				echo "<div class=title><b>Welcome to ".$web_title." Control Panel"; break;
	case "left"	:				include("left.php");break;
	case "addmulti"	:			include("multi_film.php");break;
	case "multi"	:			include("multi.php");break;
        case "multi_episode"	:			include("multi_episode.php");break;
        case "mvideo"	:			include("multi_video.php");break;
	case "webmail"	:			include("webmail.php");break;
	case "showtime"	:			include("showtime.php");break;
	case "notebook"	:			include("notebook.php");break;
	case "sendmail2"	:			include("sendmail2.php");break;
	default : include("dashboard.php");
	break;
	}
	?>
        </section>
        <aside class="bg-light lter b-l aside-md hide" id="notes">
          <div class="wrapper">Notification</div>
        </aside>
      </section>
    </section>
  </section>
  <script src="template/js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="template/js/bootstrap.js"></script>
  <!-- App -->
  <script src="template/js/app.js"></script>
  <script src="template/js/app.plugin.js"></script>
  <script src="template/js/slimscroll/jquery.slimscroll.min.js"></script>
    <script src="template/js/charts/easypiechart/jquery.easy-pie-chart.js"></script>
  <script src="template/js/charts/sparkline/jquery.sparkline.min.js"></script>
  <script src="template/js/charts/flot/jquery.flot.min.js"></script>
  <script src="template/js/charts/flot/jquery.flot.tooltip.min.js"></script>
  <script src="template/js/charts/flot/jquery.flot.resize.js"></script>
  <script src="template/js/charts/flot/jquery.flot.grow.js"></script>
  <script src="template/js/charts/flot/demo.js"></script>

  <script src="template/js/calendar/bootstrap_calendar.js"></script>
  <script src="template/js/calendar/demo.js"></script>

  <script src="template/js/sortable/jquery.sortable.js"></script>
  
    <script src="template/js/libs/underscore-min.js"></script>
<script src="template/js/libs/backbone-min.js"></script>
<script src="template/js/libs/backbone.localStorage-min.js"></script>  
<script src="template/js/libs/moment.min.js"></script>
<script src="template/js/apps/notes.js"></script>
<script src="template/js/admin.js"></script>

</body>
</html>