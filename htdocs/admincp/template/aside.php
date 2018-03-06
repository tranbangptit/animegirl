<?php 
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$menu_arr = array(
	'cat'	=>	array(
		'THỂ LOẠI',
		array(
			'edit'	=>	array('Danh Sách Thể Loại','act=cat&mode=edit'),
			'add'	=>	array('Thêm Thể Loại','act=cat&mode=add'),
		),
		'fa-list-alt',
		'bg-danger',
	),
	'country'	=>	array(
		'QUỐC GIA',
		array(
			'edit'	=>	array('Danh Sách Quốc Gia','act=country&mode=edit'),
			'add'	=>	array('Thêm Quốc Gia','act=country&mode=add'),
		),
		'fa-globe',
		'bg-warning',
	),
	'film'	=>	array(
		'DANH SÁCH PHIM',
		array(
			'add_episode'	=>	array('Thêm Phim','act=episode&mode=multi_add'),
			'add_episodes'	=>	array('Multi Phim','act=multi'),
			'edit'	        =>	array('Danh Sách Phim','act=film&mode=edit'),
			'edit_broken'	=>	array('Phim Lỗi','act=film&mode=edit&show_broken=1'),
			'edit_phimle'	=>	array('Phim Lẻ','act=film&mode=edit&show_film_lb=0'),
			'edit_phimbo'	=>	array('Phim Bộ hoàn thành','act=film&mode=edit&show_film_lb=1'),
			'edit_phimbocht'	=>	array('Phim Bộ chưa hoàn thành','act=film&mode=edit&show_film_lb=2'),
			'edit_dangchieurap'	=>	array('PHIM ĐANG CHIẾU RẠP','act=film&mode=edit&show_film_chieurap=1'),
			'edit_sapchieurap'	=>	array('PHIM SẮP CHIẾU RẠP','act=film&mode=edit&show_film_chieurap=2'),
			'edit_decu'	=>	array('Phim Đề Cử','act=film&mode=edit&show_film_type=1'),
			'edit_phimnotepid'	=>	array('Phim chưa có Episode','act=film&mode=edit&show_filmnotepid=1'),
			'edit_phim18'	=>	array('Phim 18+','act=film&mode=edit&show_film18=1'),
			'add_request'	=>	array('Yêu Cầu Phim','act=request&mode=edit'),
		),
		'fa-film',
		'bg-success',
	),
'request'	=>	array(
		'YÊU CẦU',
		array(
			'add_request'	=>	array('Yêu Cầu Phim','act=request&mode=edit'),
			
		),
		'fa-pencil',
		'bg-info',
	),
	'video'	=>	array(
		'VIDEO CLIP',
		array(
			'list'	=>	array('Danh sách video clip','act=video&mode=edit'),
			'add'	=>	array('Thêm Video Clip','act=video&mode=add'),
                        'multi'	=>	array('Multi clip(Ytb)','act=mvideo'),
		),
		'fa-play-circle',
		'bg-primary',
	),
       'notif'	=>	array(
		'THEO DÕI',
		array(
			'list'	=>	array('Danh sách theo dõi','act=notif&mode=edit'),
			
		),
		'fa-play-circle',
		'bg-primary',
	),
	'page'	=>	array(
		'PAGE/ NEWS',
		array(
			'list'	=>	array('Danh sách Page/News','act=page&mode=edit'),
			'add'	=>	array('Thêm Page/News','act=page&mode=add'),
		),
		'fa-book',
		'bg-warning dker',
	),
	'cache'	=>	array(
		'CẬP NHẬT CACHE',
		array(
			'update'	=>	array('Cập nhật Cache','act=cache&mode=update'),
		),
		'fa-dashboard',
		'bg-success dker',
	),
	'showtime'	=>	array(
		'LỊCH CHIẾU PHIM',
		array(
			'list'	=>	array('Danh sách phim có lịch','act=showtime&mode=edit'),
			'add'	=>	array('Thêm Lịch Cho Phim','act=showtime&mode=add'),
		),
		'fa-dashboard',
		'bg-primary dker',
	),
	'dienvien'	=>	array(
		'DIỄN VIÊN',
		array(
			'lech'	=>	array('Lech Diễn Viên','act=lech_dienvien'),
			'edit'	=>	array('Danh Sách Diễn Viên','act=dienvien&mode=edit'),
			'add'	=>	array('Thêm Diễn Viên','act=dienvien&mode=add'),
		),
		'fa-group',
		'bg-info',
	),
	'user'	=>	array(
		'THÀNH VIÊN',
		array(
			'edit'	=>	array('Danh Sách Thành Viên','act=user&mode=edit'),
			'edit_ban'	=>	array('Danh Sách Đen','act=user&mode=edit&user_ban=1'),
			'edit_level'	=>	array('Danh sách bậc thành viên','act=user&mode=edit_level'),
			'add'	=>	array('Thêm Thành Viên','act=user&mode=add'),
			'add_level'	=>	array('Thêm bậc thành viên','act=user&mode=add_level'),
		),
		'fa-user',
		'bg-info dker',
	),
	'link'	=>	array(
		'LIÊN KẾT - ADS',
		array(
			'edit'	=>	array('Danh Sách Quảng Cáo','act=ads&mode=edit'),
			'add'	=>	array('Thêm Quảng Cáo','act=ads&mode=add'),
			'adsposadd'	=>	array('Thêm Vị Trí Quảng Cáo','act=adspos&mode=add'),
			'adsposlist'	=>	array('Danh Sách Vị Trí Quảng Cáo','act=adspos&mode=edit'),
		),
		'fa-link',
		'bg-danger dker',
	),
	'skin'	=>	array(
		'GIAO DIỆN',
		array(
			'edit'	=>	array('Danh Sách Giao Diện','act=skin&mode=edit'),
			'add'	=>	array('Thêm Giao Diện','act=skin&mode=add'),
		),
		'fa-th',
		'bg-warning dker',
	),
	'config'	=>	array(
		'CẤU HÌNH',
		array(
			'config'		=>	array('Cấu Hình','act=config'),
			'permission'	=>	array('Quyền Hạn','act=permission'),
			'local'			=>	array('Server','act=local'),
			'Mod_ponit'		=>	array('Kiểm soát','act=user&mode=edit&point=yes'),	
		),
		'fa-cogs',
		'bg-success dker',
	)
);
?>
<aside class="bg-dark lter aside-md hidden-print" id="nav">          
          <section class="vbox">
            <!--<header class="header bg-primary lter text-center clearfix">
              <div class="btn-group">
                <button type="button" class="btn btn-sm btn-dark btn-icon" title="New project"><i class="fa fa-plus"></i></button>
                <div class="btn-group hidden-nav-xs">
                  <button type="button" class="btn btn-sm btn-primary dropdown-toggle" data-toggle="dropdown">
                    Switch Project
                    <span class="caret"></span>
                  </button>
                  <ul class="dropdown-menu text-left">
                    <li><a href="#">Project</a></li>
                    <li><a href="#">Another Project</a></li>
                    <li><a href="#">More Projects</a></li>
                  </ul>
                </div>
              </div>
            </header>-->
            <section class="w-f scrollable">
              <div class="slim-scroll" data-height="auto" data-disable-fade-out="true" data-distance="0" data-size="5px" data-color="#333333">
                
                <!-- nav -->
                <nav class="nav-primary hidden-xs">
                  <ul class="nav">
<?php 
foreach ($menu_arr as $key => $arr) {
    echo '<li><a href="#" ><i class="fa '.$arr[2].' icon"><b class="'.$arr[3].'"></b></i><span class="pull-right"><i class="fa fa-angle-down text"></i><i class="fa fa-angle-up text-active"></i></span><span>'.$arr[0].'</span></a>
			<ul class="nav lt">';
	foreach ($arr[1] as $m_key => $m_val) {
		echo ' <li><a href="index.php?'.$m_val[1].'" ><i class="fa fa-angle-right"></i><span>'.$m_val[0].'</span></a></li>';
	}
	echo '</ul></li>';
}
?>             <li >
                      <a href="index.php?act=notebook">
                        <i class="fa fa-pencil icon">
                          <b class="bg-info"></b>
                        </i>
                        <span>Notes</span>
                      </a>
                    </li>       
                  </ul>
                </nav>
                <!-- / nav -->
              </div>
            </section>
            
            <footer class="footer lt hidden-xs b-t b-dark">
              <a href="#nav" data-toggle="class:nav-xs" class="pull-right btn btn-sm btn-dark btn-icon">
                <i class="fa fa-angle-left text"></i>
                <i class="fa fa-angle-right text-active"></i>
              </a>
              <div class="btn-group hidden-nav-xs">
                <button type="button" title="Homepage" onClick="parent.location='/'" class="btn btn-icon btn-sm btn-dark"><i class="fa fa-home"></i></button>
                <button type="button" title="Facebook" onClick="parent.location='https://www.facebook.com/phiimtv'" class="btn btn-icon btn-sm btn-dark"><i class="fa fa-facebook"></i></button>
              </div>
            </footer>
          </section>
        </aside>