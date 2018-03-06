<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");

$edit_url = 'index.php?act=film&mode=edit';
$edit_del = 'index.php?act=film&mode=edit';
if(isset($_GET['film_id']))
$film_id = (int)$_GET['film_id'];
else $film_id = false;
$inp_arr = array(
		'uplaidate'	=> array(
			'name'	=>	'Up lại phim này',
			'type'	=>	'function::uplaidate::number'
		),
                'mailchecksend'	=> array(
			'name'	=>	'Send mail cho người theo dõi',
			'type'	=>	'function::mailchecksend::number'
		),
		'name'	=> array(
			'table'	=>	'film_name',
			'name'	=>	'TÊN PHIM',
			'type'	=>	'free'
		),
	        'name_real'	=> array(
			'table'	=>	'film_name_real',
			'name'	=>	'TÊN ENGLISH',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
                'trangthai'	=> array(
			'table'	=>	'film_trangthai',
			'name'	=>	'Trạng thái',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'img'	=> array(
			'table'	=>	'film_img',
			'name'	=>	'Ảnh phim',
			'type'	=>	'img5',
			'can_be_empty'	=> true,
		),
		'imgbn'	=> array(
			'table'	=>	'film_imgbn',
			'name'	=>	'Ảnh banner',
			'type'	=>	'imgbn',
			'can_be_empty'	=> true,
		),
        'imgkinhdien'	=> array(
			'table'	=>	'film_imgkinhdien',
			'name'	=>	'Ảnh banner kinh điển',
			'type'	=>	'imgkinhdien',
			'can_be_empty'	=> true,
		),
        'trailer'	=> array(
			'table'	=>	'film_trailer',
			'name'	=>	'Trailer phim',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
        'film_publish'	=> array(
			'table'	=>	'film_publish',
			'name'	=>	'Bản quyền',
			'type'	=>	'function::acp_publish::number',
			'can_be_empty'	=> true,
		),
		'cat'	=> array(
			'table'	=>	'film_cat',
			'name'	=>	'THỂ LOẠI',
			'type'	=>	'function::acp_cat::number',
			'can_be_empty'	=> true,
		),
		'country'	=> array(
			'table'	=>	'film_country',
			'name'	=>	'QUỐC GIA',
			'type'	=>	'function::acp_country::number',
			'can_be_empty'	=> true,
		),
		
		'director'	=> array(
			'table'	=>	'film_director',
			'name'	=>	'ĐẠO DIỄN',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'actor'	=> array(
			'table'	=>	'film_actor',
			'name'	=>	'DIỄN VIÊN',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
	    'area'	=> array(
			'table'	=>	'film_area',
			'name'	=>	'NHÀ SẢN XUẤT',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'time'	=> array(
			'table'	=>	'film_time',
			'name'	=>	'THỜI LƯỢNG',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'tapphim'	=> array(
			'table'	=>	'film_tapphim',
			'name'	=>	'Chất lượng',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'imdb'	=> array(
			'table'	=>	'film_imdb',
			'name'	=>	'Điểm IMDb',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'newepisode'	=> array(
			'table'	=>	'film_newepisode',
			'name'	=>	'Tập mới nhất(phim bộ)',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'year'	=> array(
			'table'	=>	'film_year',
			'name'	=>	'NĂM PHÁT HÀNH',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'film_lang'	=> array(
			'table'	=>	'film_lang',
			'name'	=>	'Ngôn ngữ',
			'type'	=>	'function::trang_thai::number',
			'can_be_empty'	=> true,
		),
		'film_rap'	=> array(
			'table'	=>	'film_chieurap',
			'name'	=>	'Chiếu rạp',
			'type'	=>	'function::phimrap::number',
			'can_be_empty'	=> true,
		),
                'film_kinhdien'	=> array(
			'table'	=>	'film_kinhdien',
			'name'	=>	'Kinh điển',
			'type'	=>	'function::phimkinhdien::number',
			'can_be_empty'	=> true,
		),
               
		'film_hot'	=> array(
			'table'	=>	'film_hot',
			'name'	=>	'Đề cử',
			'type'	=>	'function::phimhot::number',
			'can_be_empty'	=> true,
		),
		'film_18'	=> array(
			'table'	=>	'film_phim18',
			'name'	=>	'Phim 18+',
			'type'	=>	'function::phim18::number',
			'can_be_empty'	=> true,
		),
		'film_lang'	=> array(
			'table'	=>	'film_lang',
			'name'	=>	'Ngôn ngữ',
			'type'	=>	'function::trang_thai::number',
			'can_be_empty'	=> true,
		),
		
		'film_lb'	=> array(
			'table'	=>	'film_lb',
			'name'	=>	'PHIM LẺ/BỘ/TVSHOW',
			'type'	=>	'function::film_lb::number',
			'can_be_empty'	=> true,
		),
        'film_thongbao'	=>	array(
			'table'	=>	'film_thongbao',
			'name'	=>	'Thông báo',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
		),
        'film_info'	=>	array(
			'table'	=>	'film_info',
			'name'	=>	'THÔNG TIN',
			'type'	=>	'text',
			'can_be_empty'	=>	true,
		),
		
		'name_ascii'	=>	array(
			'table'	=>	'film_name_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
		'film_des'	=> array(
			'table'	=>	'film_des',
			'name'	=>	'DESCRIPTION',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'tag'	=> array(
			'table'	=>	'film_tag',
			'name'	=>	'TAG',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
               'slug'	=> array(
			'table'	=>	'film_slug',
			'name'	=>	'Slug(Chỉ được thay đổi khi bị google xóa link)',
			'type'	=>	'free',
		),
		'tag_ascii'	=>	array(
			'table'	=>	'film_tag_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
		'time_update'	=>	array(
			'table'	=>	'film_time_update',
			'type'	=>	'hidden_value',
			'change_on_update'	=>	true,

		),
);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Danh sách</li>
              </ul>
<?
##################################################
# EDIT FILM
##################################################
if ($mode == 'edit') {
	if (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {

			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."episode WHERE episode_film IN (".$in_sql.")");
			$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_id IN (".$in_sql.")");
   			$mysql->query("DELETE FROM ".$tb_prefix."comment WHERE comment_film IN (".$in_sql.")");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

		if ($_POST['selected_option'] == 'multi_edit') {
			$arr = implode(',',$arr);
				echo "Loading....<meta http-equiv='refresh' content='0;url=index.php?act=multi_edit_film&id=".$arr."'>";
	
		}

		if ($_POST['selected_option'] == 'normal') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_broken = 0 WHERE film_id IN (".$in_sql.")");
			$mysql->query("UPDATE ".$tb_prefix."episode SET episode_broken = 0 WHERE episode_film IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

		if ($_POST['selected_option'] == 'vote') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_hot = 1 WHERE film_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

		if ($_POST['selected_option'] == 'chieurap') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_chieurap = 1 WHERE film_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

		if ($_POST['selected_option'] == 'sapchieurap') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_chieurap = 0 WHERE film_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

		if ($_POST['selected_option'] == 'binhthuong') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_type = 0, film_hot = 0 WHERE film_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

		if ($_POST['selected_option'] == 'phimle') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_lb = 0 WHERE film_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		if ($_POST['selected_option'] == 'phimbo') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_lb = 1 WHERE film_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		if ($_POST['selected_option'] == 'update') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_date = '".NOW."' WHERE film_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

        if ($_POST['selected_option'] == 'cap3') {
		$in_sql = implode(',',$arr);
		$mysql->query("UPDATE ".$tb_prefix."film SET film_phim18 = 1 WHERE film_id IN (".$in_sql.")");
		echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		if ($_POST['selected_option'] == 'cap3huy') {
		$in_sql = implode(',',$arr);
		$mysql->query("UPDATE ".$tb_prefix."film SET film_phim18 = 0 WHERE film_id IN (".$in_sql.")");
		echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		exit();
	}	
	elseif ($film_id) {
$tt_ac = get_total('notif','notif_id',"WHERE notif_film = '".$film_id."' ORDER BY notif_id");
if($tt_ac) $t = ' / <font color="red">Phim này đang có <i>'.$tt_ac.'</i> người theo dõi!</font>'; else $t = '';
		$qq = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_id = '".$film_id."'");
			$rr = $qq->fetch(PDO::FETCH_ASSOC);
		if (!isset($_POST['submit'])) {
		    $error_arr = array();
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_id = '".$film_id."'");
			$r = $q->fetch(PDO::FETCH_ASSOC);			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
			    $inp_arr['name']['value'] = htmlchars(strtolower($name));
				$inp_arr['name_ascii']['value'] = htmlchars(strtolower(get_ascii($name)));
				$tag_ascii = htmlchars(strtolower(get_ascii($tag)));
				$inp_arr['tag_ascii']['value'] = htmlchars(strtolower(get_ascii($tag)));
				$tag = htmlchars(strtolower(($tag)));
                $inp_arr['tag']['value'] = htmlchars(strtolower(($tag)));
				$name = htmlchars(stripslashes($name));
				$name = ($_POST['name']);
				$name_real = UNIstr($_POST['name_real']);
				$name_seo = UNIstr($_POST['name_seo']);
				//New MulTi Cat
				$cat = ','.join_value($_POST['selectcat']);
				$country = ','.join_value($_POST['selectcountry']);				
				$trang_thai=$_POST['trang_thai'];
				if($uplaidate == 2) {
				$inp_arr['time_update']['value'] = "".NOW."";
				}else{
				$inp_arr['time_update']['value'] = $rr['film_time_update'];
				}

				
				
				$inp_arr['trang_thai']['value'] = $trang_thai;
				$inp_arr['country']['value'] = $country;
				$inp_arr['cat']['value'] = $cat;
                $server_img		=	$_POST['server_img'];
			    $server_imgbn		=	$_POST['server_imgbn'];
			    $server_imgkd		=	$_POST['server_imgkinhdien'];
			        if($server_img == 1) {
				        $img = $img;
			        }elseif($server_img == 2) {
				        $img = Picasa_Upload($img,1);	
			        }elseif($server_img == 3){
			         //   if($_FILES["phimimg"]['name']!=""){   $img	=	ipupload("phimimg","film",replace(get_ascii($name_real))); }elseif($img){ $img = uploadurl($img,replace(get_ascii($name_real)),'film'); }else{ $img = "http://www.phimle.tv/images/ava.jpg";	}	
					 $img = "http://www.phimle.tv/images/ava.jpg";
			        }elseif($server_img == 4){
					     $img = Imgur_Upload($img,1);	
					}
		            if($server_imgbn == 1) {
				        $imgbn = $imgbn;
			        }elseif($server_imgbn == 2) {
			            $imgbn = Picasa_Upload($imgbn,2);
					}elseif($server_imgbn == 3){
			        //    if($_FILES["phimimgbn"]['name']!=""){  // $imgbn	=	ipupload("phimimgbn","info",replace(get_ascii($name_real)));  }elseif($imgbn){//  $imgbn = uploadurl($imgbn,replace(get_ascii($name_real)),'info'); }else{  $imgbn = "http://www.phimle.tv/players/no-banner.jpg";	}	
					 $imgbn = "http://www.phimle.tv/players/no-banner.jpg";	
			        }elseif($server_imgbn == 4){
					    $imgbn = Imgur_Upload($imgbn,2);	
					}
					if($$server_imgkd == 1) {
				        $imgkinhdien = $imgkinhdien;
			        }elseif($server_imgkd == 2) {
			            $imgkinhdien = Picasa_Upload($imgkinhdien,2);
					}elseif($server_imgkd == 3){
			           // if($_FILES["phimimgkinhdien"]['name']!=""){  // $imgkinhdien	=	ipupload("phimimgkinhdien","info",replace(get_ascii($name_real)));  }elseif($imgkinhdien){ // $imgkinhdien = uploadurl($imgkinhdien,replace(get_ascii($name_real)),'info'); }else{ $imgkinhdien = "http://www.phimle.tv/players/no-banner.jpg";	}	
					   $imgkinhdien = "http://www.phimle.tv/players/no-banner.jpg";	
			        }elseif($server_imgkd == 4){
					    $imgkinhdien = Imgur_Upload($imgkinhdien,2);	
					}
			/* end upload images*/
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'film','film_id','film_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
if($mailchecksend == 1){
$mysql->query("UPDATE ".DATABASE_FX."notif SET notif_send = 1 WHERE notif_film = '".$film_id."'");
echo 'Chuẩn bị gửi mail...<meta http-equiv="refresh" content="2;url=sendmail.php?filmId='.$film_id.'">';}else{echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
}

			 	
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		//$name = UNIstr($name);
		$form->createForm('EDIT FILM '.$t,$inp_arr,$error_arr);
	}
	else {
		$userup = $_SESSION['admin_id'];
		if($level == 2){
		$sql_u = "film_upload = ".$userup;
		}else $sql_u = "film_upload <> 0";
		$film_per_page = 30;
		$order ='ORDER BY film_time_update DESC';
		if (!isset($pg)) $pg = 1;
		if(isset($_GET['xsearch']))
		$xsearch = strtolower(get_ascii(urldecode($_GET['xsearch'])));
		else $xsearch = false;
        if($xsearch){
		$extra = "(film_name_ascii LIKE '%".sqlescape($xsearch)."%' OR film_name_real LIKE '%".sqlescape($xsearch)."%' OR film_id LIKE '%".sqlescape($xsearch)."%')";
		}else{
		$extra = $sql_u;
		}
		if (isset($cat_id)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_cat = '".$cat_id."' ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id',"WHERE film_cat = '".$cat_id."'".(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($country_id)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_country = '".$country_id."' ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id',"WHERE film_country = '".$country_id."'".(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($show_film18)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_phim18 = '1' ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id',"WHERE film_phim18 = '1'".(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($show_filmnotepid)) {
       $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_id NOT IN (SELECT episode_film FROM ".$tb_prefix."episode) ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id',"WHERE film_id NOT IN (SELECT episode_film FROM ".$tb_prefix."episode) ".(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($show_broken)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_broken = 1 ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id','WHERE film_broken = 1 '.(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($show_film_lb)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_lb = ".$show_film_lb." ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id','WHERE film_lb = '.$show_film_lb.' '.(($extra)?"AND ".$extra." ":''));
		}	
		elseif (isset($show_film_type)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_hot = ".$show_film_type." ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id','WHERE film_hot = '.$show_film_type.' '.(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($show_film_chieurap)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_chieurap = ".$show_film_chieurap." ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id','WHERE film_chieurap = '.$show_film_chieurap.' '.(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($show_film_incomplete)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_incomplete = ".$show_film_incomplete." ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id','WHERE film_lb = '.$show_film_incomplete.' '.(($extra)?"AND ".$extra." ":''));
		}
		elseif (isset($film_upload)) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_upload = ".$film_upload." ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id','WHERE film_upload = '.$film_upload.' '.(($extra)?"AND ".$extra." ":''));
		}
        else {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."film ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY film_time_update DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id',"".(($extra)?"WHERE ".$extra." ":'')."");
        }
			if (isset($tt)) {
			if (isset($xsearch)) {
				$link2 = preg_replace("#&xsearch=(.*)#si","",$link);
			}
			else $link2 = $link;
		
			
			echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Phim
                </header>
                <div class="row wrapper">
                  <div class="col-sm-3">
                    <div class="input-group">
                      <input type="text" class="input-sm form-control" id="xsearch" placeholder="Search" value="'.$xsearch.'">
                      <span class="input-group-btn">
                        <button class="btn btn-sm btn-default" type="button" onclick="window.location.href = \''.$link2.'&xsearch=\'+document.getElementById(\'xsearch\').value;">Go!</button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
				<form name="media_list" method=post action='.$link.' onSubmit="return check_checkbox();">
                  <table class="table table-striped b-t b-light">
                    <thead>
                      <tr>
                        <th width="20"><input type="checkbox"></th>
                        <th class="th-sortable" data-toggle="class">ID</th>
                        <th>Poster</th>
                        <th>Tên phim</th>
                        <th>Số tập</th>
                        <th>Quản lý</th>
                        <th>Lỗi</th>
                
                      </tr>
                    </thead>
                    <tbody>';
			
			
			
			
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['film_id'];
				$id2=$id;
				$kname = strtolower(replace($r['film_name']));
				$film_link = $web_link.'/phim/'.$kname.'-'.$id.'/';
				$film = htmlchars(stripslashes($r['film_name'])).' <font color=\"green\">'.$r['film_tapphim']."</font> <i><font color=\"red\">(".$r['film_trangthai'].")</font></i>";
$filmreal = htmlchars(stripslashes($r['film_name_real']));
$filmyear = htmlchars(stripslashes($r['film_year']));
				$totalepisodes_of_film = get_total('episode','episode_id',"WHERE episode_film = ".$id."");
			
				
				$img_src = thumbimg($r['film_img'],80); 
				$img ='<img src="'.$img_src.'" width="50" height="60">'; 
				
				$broken = ($r['film_broken'])?'<font color=red><b>X</b></font>':'';
				if (isset($show_broken)) $id .='&show_broken=1';
                // Multi Cat
				$cat=explode(',',$r['film_cat']);
				$num=count($cat);
				$cat_name="";
				for ($i=0; $i<$num;$i++) $cat_name .= '| <i><font color="blue">'.(get_data('cat_name','cat','cat_id',$cat[$i])).'</font></i> ';
				echo '<tr>
                            <td>
                                                   <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'">
                                                </td>
                                                <td>#'.$id.'</td>
                                               <td align="center">'.$img.'</td>
											   <td><b><a style="color:#555;" href=?act=film&mode=edit&film_id='.$id.'>'.$film.'<br />'.$filmreal.' ('.$filmyear.')</a></b><br><a style="color:#555;" href="'.$film_link.'" target="_blank"><font class="sub">&raquo;</font> <i><font color="green">Thông Tin</font></i></a>'.$cat_name.'</a></td>
                                               
												<td><b>'.$totalepisodes_of_film.'</b></td>
                                                <td><span style="float:left;padding-left:10px;">
				<a href="?act=episode&mode=edit&film_id='.$id.'" style="color:#555;"><b>Tập Phim</b></a></span><span style="float:right;padding-right:10px;"><a href="?act=episode&mode=multi_add&film_id='.$id.'" style="color:#555;"><b>Thêm</b></a></span><span style="float:right;padding-right:10px;"><a href="?act=multi_episode&film_id='.$id.'" style="color:#555;"><b>Multi Edit</b></a></span></td>
                                               <td class=fr_2 align=center>'.$broken.'</td>
                                            </tr>';
				
			}
			
			echo ' </tbody>
                  </table>
                </div>
                <footer class="panel-footer">
                  <div class="row">
                    <div class="col-sm-4 hidden-xs">
               	<select name="selected_option" class="input-sm form-control input-s-sm inline v-middle">
				<option value="multi_edit">Sửa</option>
				<option value="del">Xóa</option>
				<option value="normal">Thôi báo lỗi</option>
				<option value="chieurap">ĐANG CHIẾU RẠP</option>
				<option value="sapchieurap">BỎ CHIẾU RẠP</option>
				<option value="binhthuong">BỎ ĐỀ CỬ</option>
				<option value="cap3">PHIM Cấp 3</option>
				<option value="cap3huy">BỎ CẤP 3</option>
				<option value="vote">ĐỀ CỬ</option>
				<option value="phimle">Phim lẻ</option>
				<option value="phimbo">Phim bộ</option>
				<option value="update">Update</option></select>
                      <button type="submit" class="btn btn-sm btn-default" name="do">Apply</button>       
                 </form>					  
                    </div>
                    <div class="col-sm-4 text-center">
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' phim</small>
                    </div>
                    <div class="col-sm-4 text-right text-center-xs">                
                      <ul class="pagination pagination-sm m-t-none m-b-none">
                        '.admin_viewpages($tt,$film_per_page,$pg).'
                      </ul>
                    </div>
                  </div>
                </footer>
              </section>';
		}
		else echo "THERE IS NO FILMS";
	}
}
?>
</section>
          </section>