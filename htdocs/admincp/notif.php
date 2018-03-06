<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=notif&mode=edit';
if(isset($_GET['notif_id']))
$page_id = (int)$_GET['notif_id'];
else $page_id = false;
$inp_arr = array(
		
		'notif_username'	=> array(
			'table'	=>	'notif_username',
			'name'	=>	'Notif NAME',
			'type'	=>	'free',
		
		),
		'notif_email'	=> array(
			'table'	=>	'notif_email',
			'name'	=>	'Notif Email',
			'type'	=>	'free',
			
		),
		'film'	=> array(
			'table'	=>	'notif_film',
			'name'	=>	'Notif Film',
			'type'	=>	'function::acp_film::number',
			
		)
		

);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Notification</li>
              </ul>
<?

##################################################
# EDIT MEDIA SHOWTIME
##################################################
if ($mode == 'edit') {
	if (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {
			$in_sql = implode(',',$arr);
		
			$mysql->query("DELETE FROM ".$tb_prefix."notif WHERE notif_id IN (".$in_sql.")");

			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		
		exit();
	}	
	elseif ($notif_id) {
		$qq = $mysql->query("SELECT * FROM ".$tb_prefix."notif WHERE notif_id = '".$notif_id."'");
			$rr = $qq->fetch(PDO::FETCH_ASSOC);
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."notif WHERE notif_id = '".$notif_id."'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
			
			
			
			
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'notif','notif_id','notif_id'),$inp_arr);
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
		$film_per_page = 30;
		$order ='ORDER BY notif_id DESC';
		if (!$pg) $pg = 1;
		$xsearch = (int)strtolower(get_ascii(urldecode($_GET['xsearch'])));
		$extra = (($xsearch)?"notif_id = '".sqlescape($xsearch)."' ":'');		
		
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."notif ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY notif_id DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('notif','notif_id',"".(($extra)?"WHERE ".$extra." ":'')."");
        
			if ($tt) {
			if ($xsearch) {
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
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Film</th>
                        <th>Thời gian</th>
                
                      </tr>
                    </thead>
                    <tbody>';			
			
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['notif_id'];
				$id2 = $id;
				$notif_name = $r['notif_username'];
		$filmname = get_data("film_name","film","film_id",$r['notif_film']);
$filmnamereal = get_data("film_name_real","film","film_id",$r['notif_film']);
				// Multi Cat
				echo '<tr>
                            <td> <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'"></td>
                            <td>#'.$id.'</td>
                            <td align="center"><a style="color:#555;" href=?act=notif&mode=edit&notif_id='.$id.'>'.$notif_name.'</a></td>
							<td><b>'.$r['notif_email'].'</a></td>
                            <td><b><a href="index.php?act=film&mode=edit&film_id='.$r['notif_film'].'" target="_blank">'.$filmname.'/'.$filmnamereal.'</a></b></td>
                            <td><span style="float:left;padding-left:10px;"><b>'.RemainTime($r['notif_time']).'</b></span></td>
                          
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
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' page</small>
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
		else echo "THERE IS NO PAGE";
	}
}	
##################################################
# DELETE MEDIA SHOWTIME
##################################################
if ($mode == 'del') {
	//acp_check_permission_mod('del_country');
	if ($notif_id) {
		if ($_POST['submit']) {
			//$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."notif WHERE notif_id = '".$page_id."'");
			echo "<BR><BR><BR><B><font size=3 color=blue>XÓA THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">BẠN CÓ MUỐN XÓA NOTIF NÀY KHÔNG ?<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>
 </section>
          </section>