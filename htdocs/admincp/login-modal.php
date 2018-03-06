<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
if(isset($_GET['error']))
$error = $_GET['error'];
else $error = false;
?>
<!DOCTYPE html>
<html lang="en" class="bg-dark">
<head>
  <meta charset="utf-8" />
  <title>Đăng nhập | PhimLẻ[Tv]</title>
  <meta name="description" content="web control of phimle.tv" />
  <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1" /> 
  <link rel="stylesheet" href="template/css/bootstrap.css" type="text/css" />
  <link rel="stylesheet" href="template/css/animate.css" type="text/css" />
  <link rel="stylesheet" href="template/css/font-awesome.min.css" type="text/css" />
  <link rel="stylesheet" href="template/css/font.css" type="text/css" />
    <link rel="stylesheet" href="template/css/app.css" type="text/css" />
  <!--[if lt IE 9]>
    <script src="template/js/ie/html5shiv.js"></script>
    <script src="template/js/ie/respond.min.js"></script>
    <script src="template/js/ie/excanvas.js"></script>
  <![endif]-->
</head>
<body>
  <section id="content" class="m-t-lg wrapper-md animated fadeInUp">    
    <div class="container aside-xxl">
      <a class="navbar-brand block" href="/">PhimLẻ[Tv]</a>
      <section class="panel panel-default bg-white m-t-lg">
        <header class="panel-heading text-center">
          <strong>Đăng nhập</strong>
        </header>
        <form action="login.php" class="panel-body wrapper-lg" method="post">
		<div class="form-group" style="display:<? if($error=="u"){ ?>block<? }else{ ?>none<? } ?>">
            <label class="control-label">Lỗi</label>
            <? if($error=="u"){ ?>Sai mật khẩu<? }else{ ?>Điền đầy đủ thông tin đăng nhập<? } ?>
          </div>
          <div class="form-group">
            <label class="control-label">Email</label>
            <input type="email" placeholder="email@phimle.tv" class="form-control input-lg" name="email">
          </div>
          <div class="form-group">
            <label class="control-label">Password</label>
            <input type="password" id="inputPassword" placeholder="Password" class="form-control input-lg" name="password">
          </div>
          <div class="checkbox">
            <label>
              <input type="checkbox"> Keep me logged in
            </label>
          </div>
          <a href="#" class="pull-right m-t-xs"><small>Forgot password?</small></a>
          <button type="submit" class="btn btn-primary" name="submit">Đăng nhập</button>
        </form>
      </section>
    </div>
  </section>
  <!-- footer -->
  <footer id="footer">
    <div class="text-center padder">
      <p>
        <small>PhimLẻ[Tv] Control Panel<br>&copy; 2015</small>
      </p>
    </div>
  </footer>
  <!-- / footer -->
  <script src="template/js/jquery.min.js"></script>
  <!-- Bootstrap -->
  <script src="template/js/bootstrap.js"></script>
  <!-- App -->
  <script src="template/js/app.js"></script>
  <script src="template/js/app.plugin.js"></script>
  <script src="template/js/slimscroll/jquery.slimscroll.min.js"></script>
  
</body>
</html>