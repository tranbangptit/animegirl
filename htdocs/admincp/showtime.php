<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=showtime&mode=edit';
if(isset($_GET['showtime_id']))
$showtime_id = (int)$_GET['showtime_id'];
else $showtime_id = false;
$inp_arr = array(
		
		'showtime_film'	=> array(
			'table'	=>	'showtime_film',
			'name'	=>	'ID phim',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'showtime_notice'	=>	array(
			'table'	=>	'showtime_notice',
			'name'	=>	'Nội dung thông báo',
			'type'	=>	'text'
		),
		'showtime_week'	=> array(
			'table'	=>	'showtime_week',
			'name'	=>	'Thứ có lịch',
			'type'	=>	'function::acp_showtimeday::number',
			'can_be_empty'	=> true,
		),
		
		

);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Lịch chiếu</li>
              </ul>
<?
##################################################
# ADD MEDIA COUNTRY
##################################################
if ($mode == 'add') {
	
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$stime			= join_value($_POST['selectstime']);
            $showtime_week = ','.$stime;
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'showtime'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "<BR><BR><BR><B><font size=3 color=blue>THÊM THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('THÊM LỊCH CHIẾU PHIM',$inp_arr,$error_arr);
}
##################################################
# EDIT MEDIA SHOWTIME
##################################################
if ($mode == 'edit') {
	if (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {
			$in_sql = implode(',',$arr);
		
			$mysql->query("DELETE FROM ".$tb_prefix."showtime WHERE showtime_id IN (".$in_sql.")");

			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		
		exit();
	}	
	elseif ($showtime_id) {
		$qq = $mysql->query("SELECT * FROM ".$tb_prefix."showtime WHERE showtime_id = '".$showtime_id."'");
			$rr = $qq->fetch(PDO::FETCH_ASSOC);
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."showtime WHERE showtime_id = '".$showtime_id."'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
			    $stime			= join_value($_POST['selectstime']);
                $showtime_week = ','.$stime;
				$inp_arr['showtime_week']['value'] = $showtime_week;
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'showtime','showtime_id','showtime_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
			 	echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		//$name = UNIstr($name);
		$form->createForm('EDIT FILM',$inp_arr,$error_arr);
	}
	else {
		if($level == 2)	acp_check_permission_mod('edit_showtime');
		$film_per_page = 30;
		$order ='ORDER BY showtime_id DESC';
		if (!$pg) $pg = 1;
		$xsearch = (int)strtolower(get_ascii(urldecode($_GET['xsearch'])));
		$extra = (($xsearch)?"showtime_id = '".sqlescape($xsearch)."' ":'');		
		if ($cat_id) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_cat = '".$cat_id."' ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_date DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id',"WHERE film_cat = '".$cat_id."'".(($extra)?"AND ".$extra." ":''));
		}
		elseif ($country_id) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."film WHERE film_country = '".$country_id."' ".(($extra)?"AND ".$extra." ":'')."ORDER BY film_date DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('film','film_id',"WHERE film_country = '".$country_id."'".(($extra)?"AND ".$extra." ":''));
		}
        else {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."showtime ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY showtime_id DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('showtime','showtime_id',"".(($extra)?"WHERE ".$extra." ":'')."");
        }
			if ($tt) {
			if ($xsearch) {
				$link2 = preg_replace("#&xsearch=(.*)#si","",$link);
			}
			else $link2 = $link;
		
			
			echo '<section class="panel panel-default">
                <header class="panel-heading">
                  LỊCH CHIẾU
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
                        <th>Thumb</th>
                        <th>Phim</th>
                        <th>Message</th>
                        <th>Ngày/Tuần</th>
                        <th>Time post</th>
                
                      </tr>
                    </thead>
                    <tbody>';	
			
			
			
			
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['showtime_id'];
				$id2=$id;
				$kname = strtolower((get_data('film_name','film','film_id',$r['showtime_film'])));
				$film_name = get_data('film_name','film','film_id',$r['showtime_film']);
				$film_link = $web_link.'/phim/'.$kname.'-'.$r['showtime_film'].'/';
				$img ="<img src=".get_data('film_img','film','film_id',$r['showtime_film'])." width=50 height=60>";
					// Multi Cat
				$day= explode(',',$r['showtime_week']);
				$num= count($day);
				$day_name="";
				for ($i=1; $i<$num-1;$i++) $day_name .= '<i><font color="green">'.acp_showtime($day[$i]).'</font></i>|';

				echo '<tr>
                            <td>
                                                   <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'">
                                                </td>
                                                <td>#'.$id.'</td>
                                               <td align="center">'.$img.'</td>
											   <td><b><a style="color:#555;" href=?act=showtime&mode=edit&showtime_id='.$id.'>'.$film_name.'</a></b></a></td>
                                               
												<td><b>'.text_tidy1($r['showtime_notice']).'</b></td>
                                                <td><span style="float:left;padding-left:10px;"><b>'.$day_name.'</b></span></td>
                                               <td class=fr_2 align=center><b>'.$r['showtime_time'].'</b></td>
                                            </tr>';
				
			}
			
			echo ' </tbody>
                  </table>
                </div>
                <footer class="panel-footer">
                  <div class="row">
                    <div class="col-sm-4 hidden-xs">
               	<select name="selected_option" class="input-sm form-control input-s-sm inline v-middle">
				<option value=del>Xóa</option></select>
                      <button type="submit" class="btn btn-sm btn-default" name="do">Apply</button>       
                 </form>					  
                    </div>
                    <div class="col-sm-4 text-center">
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' showtime</small>
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
		else echo "THERE IS NO SHOWTIME";
	}
}	
##################################################
# DELETE MEDIA SHOWTIME
##################################################
if ($mode == 'del') {
	//acp_check_permission_mod('del_country');
	if ($showtime_id) {
		if ($_POST['submit']) {
			//$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."showtime WHERE showtime_id = '".$showtime_id."'");
			echo "<BR><BR><BR><B><font size=3 color=blue>XÓA THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">BẠN CÓ MUỐN XÓA LỊCH CHIẾU NÀY KHÔNG ?<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>
 </section>
          </section>