<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$tt_film = get_total('film','film_id',"ORDER BY film_id");
$tt_ac = get_total('notif','notif_id',"ORDER BY notif_id");
$tt_ep = get_total('episode','episode_id',"ORDER BY episode_id");
$tt_u = get_total('user','user_id',"ORDER BY user_id");
?>
<section class="vbox">          
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.html"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Dashboard</li>
              </ul>
              <div class="m-b-md">
                <h3 class="m-b-none">Thống kê</h3>
                <small>Điểm qua một số thống kê PhimLẻ[Tv]</small>
              </div>
              <section class="panel panel-default">
                <div class="row m-l-none m-r-none bg-light lter">
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-info"></i>
                      <i class="fa fa-film fa-stack-1x text-white"></i>
					  <span class="easypiechart pos-abt" data-percent="0" data-line-width="4" data-track-Color="#fff" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="3000" data-target="#films" data-update="5000"></span>
                    </span>
                    <a class="clear" href="#">
                      <span class="h3 block m-t-xs"><strong id="films"><?=($tt_film);?></strong></span>
                      <small class="text-muted text-uc">Bộ phim</small>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-warning"></i>
                      <i class="fa fa-group fa-stack-1x text-white"></i>
                      <span class="easypiechart pos-abt" data-percent="0" data-line-width="4" data-track-Color="#fff" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="3000" data-target="#bugs" data-update="5000"></span>
                    </span>
                    <a class="clear" href="#">
                      <span class="h3 block m-t-xs"><strong id="bugs"><?=($tt_ac);?></strong></span>
                      <small class="text-muted text-uc">Theo dõi</small>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light">                     
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x text-danger"></i>
                      <i class="fa fa-play fa-stack-1x text-white"></i>
                      <span class="easypiechart pos-abt" data-percent="0" data-line-width="4" data-track-Color="#f5f5f5" data-scale-Color="false" data-size="50" data-line-cap='butt' data-animate="3000" data-target="#firers" data-update="5000"></span>
                    </span>
                    <a class="clear" href="#">
                      <span class="h3 block m-t-xs"><strong id="firers"><?=$tt_ep;?></strong></span>
                      <small class="text-muted text-uc">Tập phim</small>
                    </a>
                  </div>
                  <div class="col-sm-6 col-md-3 padder-v b-r b-light lt">
                    <span class="fa-stack fa-2x pull-left m-r-sm">
                      <i class="fa fa-circle fa-stack-2x icon-muted"></i>
                      <i class="fa fa-user fa-stack-1x text-white"></i>
					  <span class="easypiechart pos-abt" data-percent="0" data-line-width="1" data-track-Color="#fff" data-scale-Color="false" data-size="1" data-line-cap='butt' data-animate="3000" data-target="#mems" data-update="5000"></span>
                    </span>
                    <a class="clear" href="#">
                      <span class="h3 block m-t-xs"><strong id="mems"><?=($tt_u);?></strong></span>
                      <small class="text-muted text-uc">Thành viên</small>
                    </a>
                  </div>
                </div>
              </section>
              
          
            </section>
          </section>
          <a href="#" class="hide nav-off-screen-block" data-toggle="class:nav-off-screen" data-target="#nav"></a>