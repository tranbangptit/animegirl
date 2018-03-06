<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=dienvien&mode=edit';
$inp_arr = array(
		'actor_name'	=> array(
			'table'	=>	'actor_name',
			'name'	=>	'Tên diễn viên',
			'type'	=>	'free'
		),
		'actor_name1'	=> array(
			'table'	=>	'actor_name1',
			'name'	=>	'Tên Khác',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'actor_name_kd'	=>	array(
			'table'	=>	'actor_name_kd',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
		'actor_birthday'	=> array(
			'table'	=>	'actor_birthday',
			'name'	=>	'Ngày sinh',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
		),
		'actor_location'	=> array(
			'table'	=>	'actor_location',
			'name'	=>	'Nơi sinh',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'actor_height'	=> array(
			'table'	=>	'actor_height',
			'name'	=>	'Chiều cao',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'actor_movie'	=> array(
			'table'	=>	'actor_movie',
			'name'	=>	'Vai diễn đáng chú ý',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'img'	=> array(
			'table'	=>	'actor_img',
			'name'	=>	'Hình đại diện',
			'type'	=>	'img3',
			'can_be_empty'	=>	true,
		),
		'actor_info'	=>	array(
			'table'	=>	'actor_info',
			'name'	=>	'Thông tin chi tiết',
			'type'	=>	'text'
		),
		'actor_order'	=> array(
			'table'	=>	'actor_order',
			'name'	=>	'Thứ tự hiện thị',
			'type'	=>	'number'
		),

);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Diễn Viên</li>
              </ul>
<?
##################################################
# ADD ACTOR
##################################################
if ($mode == 'add') {

	if (isset($_POST['submit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$inp_arr['actor_name_kd']['value'] = strtolower(replace($actor_name));
			
			/* begin upload images*/
				$server_img		=	$_POST['server_img'];
				if($server_img == 2) {
					$fileupload		=	strtolower(UPLOAD_TB.replace($name)).'.jpg';
					if(move_uploaded_file ($_FILES['img']['tmp_name'],"./upload/tmp/".$fileupload)){
						$new_film_img = "./upload/tmp/".$fileupload;
					}
					else {
						$new_film_img = "./upload/tmp/".$fileupload;
						@copy($_POST['img'],$new_film_img);
					}

						define('DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
						include_once("./upload/inc/class_image.php");
						include_once("./upload/inc/class_image_uploader.php");
						$imgtranload	=	$new_film_img;
						// picasa

							$service 	= 'picasa';
							$uploader 	= c_Image_Uploader::factory($service);
							$uploader->login(GNAME,GPASS);
							$uploader->setAlbumID(ABUMID);
							$new_film_img	= $uploader->upload($imgtranload);
							$new_film_img= 	explode('.com/',$new_film_img);
							$actor_img	=	'http://2.bp.blogspot.com/'.$new_film_img[1];

						@unlink($imgtranload);
				}
				/* end upload images*/

			
			
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'dienvien'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "<BR><BR><BR><B><font size=3 color=blue>THÊM THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('THÊM DIỄN VIÊN',$inp_arr,$error_arr);
}
##################################################
# EDIT MEDIA COUNTRY
##################################################
if ($mode == 'edit') {	

	if ($actor_id) {
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."dienvien WHERE actor_id = '$actor_id'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['actor_name_kd']['value'] = strtolower(replace($actor_name));
				
				/* begin upload images*/
		$fileupload		=	strtolower(UPLOAD_TB.replace($actor_name)).'.jpg';
	    if(move_uploaded_file ($_FILES['img']['tmp_name'],"./upload/tmp/".$fileupload)){
			$new_film_img = "./upload/tmp/".$fileupload;
		}
		else {
			$new_film_img = "./upload/tmp/".$fileupload;
			@copy($_POST['img'],$new_film_img);
		}
		$server_img		=	$_POST['server_img'];
		if($server_img) {
			if($server_img == 1) {
				$img = $img;
			}else {
				define('DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);
				include_once(DIR . "./upload/inc/class_image.php");
				include_once(DIR . "./upload/inc/class_image_uploader.php");
				$imgtranload	=	$img;
				// picasa
				if($server_img == 2) {
					$service 	= 'picasa';
					$uploader 	= c_Image_Uploader::factory($service);
					$uploader->login(GNAME,GPASS);
					$uploader->setAlbumID(ABUMID);
					$img	= $uploader->upload($imgtranload);
					//$img	= 	explode('.com/',$img);
					//$img	=	'http://2.bp.blogspot.com/'.$img[1];
				}
				// imageshack
				elseif($server_img == 3) {
					$service 		= 	'imageshack';
					$uploader 		= 	c_Image_Uploader::factory($service);
					// nếu cấu hình tài khoản imageshack thì bỏ 2 dấu // ở dưới --> ok
					//$uploader->login(INAME,IPASS);
					//$uploader->set_api(IKEY);
					$img 	= 	$uploader->upload($imgtranload);
				}
				@unlink($imgtranload);
			}
			/* end upload images*/
		}

				
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'dienvien','actor_id','actor_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "<BR><BR><BR><B><font size=3 color=blue>SỬA THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('SỬA DIỄN VIÊN',$inp_arr,$error_arr);
	}elseif (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."dienvien WHERE actor_id IN (".$in_sql.")");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	else {
		$m_per_page = 20;
		if (!$pg) $pg = 1;
		$xsearch = strtolower(get_ascii(urldecode($_GET['xsearch'])));
		$skey = str_replace('%20',' ',$xsearch);
		$skeys = str_replace('%20',' ',urldecode($_GET['xsearch']));
		$extra = (($xsearch)?"WHERE actor_name LIKE '%".$skeys."%' OR actor_name1 LIKE '%".$skey."%' OR actor_name_ascii LIKE '%".$skey."%' OR actor_id LIKE '%".$skey."%' ":'');	
		if ($xsearch) {
				$link2 = preg_replace("#&xsearch=(.*)#si","",$link);
			}
			else $link2 = $link;
		if($_GET['xsearch']){$sql_order = $extra;}else{$sql_order='';}
		if ($_POST['sbm']) {
			$z = array_keys($_POST);
			$q = $mysql->query("SELECT actor_id FROM ".$tb_prefix."dienvien ORDER BY actor_order ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
			for ($i=0;$i<$mysql->num_rows($q);$i++) {
				$id = @split('o',$z[$i]);
				$od = ${$z[$i]};
				$mysql->query("UPDATE ".$tb_prefix."dienvien SET actor_order = '$od' WHERE actor_id = '".$id[1]."'");
			}
		}
		echo "<script>function check_del(id) {".
		"if (confirm('BẠN CÓ MUỐN XÓA DIỄN VIÊN NÀY KHÔNG ?')) locountryion='?act=dienvien&mode=del&actor_id='+id;".
		"return false;}</script>";
		echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Diễn Viên
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
                        <th>ID</th>
                        <th>Thumb</th>
                        <th>Tên</th>
                        <th>Birthday</th>
                        <th>Location</th>
                        <th>Height</th>
                        
                
                      </tr>
                    </thead>
                    <tbody>';	
		$actor_query = $mysql->query("SELECT * FROM ".$tb_prefix."dienvien $sql_order ORDER BY actor_order ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = get_total('dienvien','actor_id');
		while ($actor = $actor_query->fetch(PDO::FETCH_ASSOC)) {
			$iz = $actor['actor_order'];
			echo '<tr>
                            <td> <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$actor['actor_id'].'"></td>
                            <td>#'.$actor['actor_id'].'</td>
                            <td><img src="'.$actor['actor_img'].'" width="50" height="60"></td>
							<td><b><a style="color:#555;" href=$link&actor_id='.$actor['actor_id'].'>'.$actor['actor_name'].'</a></b></a></td>
							 <td>'.$actor['actor_birthday'].'</td>
							 <td>'.$actor['actor_location'].'</td>
							 <td>'.$actor['actor_height'].'</td>
                            
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
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$m_per_page.'/'.$tt.' video</small>
                    </div>
                    <div class="col-sm-4 text-right text-center-xs">                
                      <ul class="pagination pagination-sm m-t-none m-b-none">
                        '.admin_viewpages($tt,$m_per_page,$pg).'
                      </ul>
                    </div>
                  </div>
                </footer>
              </section>';
	}
}	
##################################################
# DELETE MEDIA country
##################################################
if ($mode == 'del') {
	if ($actor_id) {
		if (isset($_POST['submit'])) {
			//$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."dienvien WHERE actor_id = '".$actor_id."'");
			echo "<BR><BR><BR><B><font size=3 color=blue>XÓA THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">BẠN CÓ MUỐN XÓA DIỄN VIÊN NÀY KHÔNG ?<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>
 </section>
          </section>