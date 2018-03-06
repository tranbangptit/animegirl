<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=page&mode=edit';
if(isset($_GET['page_id']))
$page_id = (int)$_GET['page_id'];
else $page_id = false;
$inp_arr = array(
		
		'page_name'	=> array(
			'table'	=>	'page_name',
			'name'	=>	'PAGE NAME',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'page_img'	=> array(
			'table'	=>	'page_img',
			'name'	=>	'PAGE IMG',
			'type'	=>	'imgbn',
			'can_be_empty'	=> true,
		),
		'page_url'	=> array(
			'table'	=>	'page_url',
			'name'	=>	'PAGE URL',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'page_cat'	=> array(
			'table'	=>	'page_cat',
			'name'	=>	'THỂ LOẠI',
			'type'	=>	'function::acp_cat::number',
			'can_be_empty'	=> true,
		),
		'page_country'	=> array(
			'table'	=>	'page_country',
			'name'	=>	'QUỐC GIA',
			'type'	=>	'function::acp_country::number',
			'can_be_empty'	=> true,
		),
		'page_tags_ascii'	=> array(
			'table'	=>	'page_tags_ascii',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'page_name_ascii'	=> array(
			'table'	=>	'page_name_ascii',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'page_time'	=> array(
			'table'	=>	'page_time',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'page_time_update'	=> array(
			'table'	=>	'page_time_update',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'page_poster'	=> array(
			'table'	=>	'page_poster',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'page_info'	=> array(
			'table'	=>	'page_info',
			'name'	=>	'PAGE INFO',
			'type'	=>	'text',
			'can_be_empty'	=> true,
		),
		'page_tags'	=> array(
			'table'	=>	'page_tags',
			'name'	=>	'TAGS',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		

);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">PAGE</li>
              </ul>
<?
##################################################
# ADD MEDIA COUNTRY
##################################################
if ($mode == 'add') {
	if (isset($_POST['submit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$page_time = NOW;
            $inp_arr['page_time']['value'] = $page_time;
            $inp_arr['page_time_update']['value'] = $page_time;
			
			$page_url = replace(strtolower($page_name));
			$inp_arr['page_url']['value'] = $page_url;
			
			$page_poster = $_SESSION['admin_id'];
			$inp_arr['page_poster']['value'] = $page_poster;
			
			$page_name_ascii = htmlchars(strtolower(get_ascii($page_name)));
			$inp_arr['page_name_ascii']['value'] = $page_name_ascii;
			
			$page_cat = ','.join_value($_POST['selectcat']);
			$inp_arr['page_cat']['value'] = $page_cat;
			
			$page_country = ','.join_value($_POST['selectcountry']);
			$inp_arr['page_country']['value'] = $page_country;
			
			$server_imgbn		=	$_POST['server_imgbn'];
			
			if($server_imgbn == 1) {
				        $page_img = $page_img;
			}elseif($server_imgbn == 2) {
			            $page_img = Picasa_Upload($page_img,2);
			}elseif($server_imgbn == 3){
			            if($_FILES["phimimgbn"]['name']!=""){ 
	                        $page_img	=	ipupload("phimimgbn","info",replace(get_ascii($name_real)));
	                    }elseif($page_img){
	                        $page_img = uploadurl($page_img,replace(get_ascii($name_real)),'info');
	                    }else{ 
	                        $page_img = "http://www.phimle.tv/images/playbg.jpg";	}	
			}elseif($server_imgbn == 4){
					    $page_img = Imgur_Upload($page_img,2);	
			}
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'pages'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "<BR><BR><BR><B><font size=3 color=blue>THÊM THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('THÊM PAGE',$inp_arr,$error_arr);
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
		
			$mysql->query("DELETE FROM ".$tb_prefix."pages WHERE page_id IN (".$in_sql.")");

			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		
		exit();
	}	
	elseif ($page_id) {
		$qq = $mysql->query("SELECT * FROM ".$tb_prefix."pages WHERE page_id = '".$page_id."'");
			$rr = $qq->fetch(PDO::FETCH_ASSOC);
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."pages WHERE page_id = '".$page_id."'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
			$page_time_update = NOW;
            $inp_arr['page_time_update']['value'] = $page_time_update;
			
			
			
			$page_name_ascii = htmlchars(strtolower(get_ascii($page_name)));
			$inp_arr['page_name_ascii']['value'] = $page_name_ascii;
			
			$page_cat = ','.join_value($_POST['selectcat']);
			$inp_arr['page_cat']['value'] = $page_cat;
			
			$page_country = ','.join_value($_POST['selectcountry']);
			$inp_arr['page_country']['value'] = $page_country;
			
			if($server_imgbn == 1) {
				        $page_img = $page_img;
			}elseif($server_imgbn == 2) {
			            $page_img = Picasa_Upload($page_img,2);
			}elseif($server_imgbn == 3){
			            if($_FILES["phimimgbn"]['name']!=""){ 
	                        $page_img	=	ipupload("phimimgbn","info",replace(get_ascii($name_real)));
	                    }elseif($page_img){
	                        $page_img = uploadurl($page_img,replace(get_ascii($name_real)),'info');
	                    }else{ 
	                        $page_img = "http://www.phimle.tv/images/playbg.jpg";	}	
			}elseif($server_imgbn == 4){
					    $page_img = Imgur_Upload($page_img,2);	
			}
			
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'pages','page_id','page_id'),$inp_arr);
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
		$order ='ORDER BY page_id DESC';
		if (!$pg) $pg = 1;
		$xsearch = (int)strtolower(get_ascii(urldecode($_GET['xsearch'])));
		$extra = (($xsearch)?"page_id = '".sqlescape($xsearch)."' ":'');		
		if ($cat_id) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."pages WHERE page_cat LIKE '%,".$cat_id.",%' ".(($extra)?"AND ".$extra." ":'')."ORDER BY page_time DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('pages','page_id',"WHERE page_cat LIKE '%,".$cat_id.",%'".(($extra)?"AND ".$extra." ":''));
		}
        else {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."pages ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY page_id DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('pages','page_id',"".(($extra)?"WHERE ".$extra." ":'')."");
        }
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
                        <th>Thumb</th>
                        <th>Tên</th>
                        <th>URL</th>
                        <th>Thể Loại</th>
                        <th>Uploader</th>
                        <th>Time post</th>
                
                      </tr>
                    </thead>
                    <tbody>';			
			
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['page_id'];
				$id2 = $id;
				$page_name = $r['page_name'];
		
				// Multi Cat
				$cat=explode(',',$r['page_cat']);
				$num=count($cat);
				$cat_name="";
				for ($i=1; $i<$num-1;$i++) $cat_name .= '| <i><font color="blue">'.(get_data('cat_name','cat','cat_id',$cat[$i])).'</font></i> ';

				echo '<tr>
                            <td> <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'"></td>
                            <td>#'.$id.'</td>
                            <td align="center"><img src="'.$r['page_img'].'" width="90" height="54"></td>
							<td><b><a style="color:#555;" href=?act=page&mode=edit&page_id='.$id.'>'.$page_name.'</a></b></a></td>
                            <td><b>'.$r['page_url'].'</b></td>
                            <td><span style="float:left;padding-left:10px;"><b>'.$cat_name.'</b></span></td>
                            <td class=fr_2 align=center><b>'.get_data('user_name','user','user_id',$r['page_poster']).'</b></td>
                            <td class=fr_2 align=center><b>'.date('Y-m-d h:i:sa',$r['page_time']).'</b></td>
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
	if ($page_id) {
		if ($_POST['submit']) {
			//$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."pages WHERE page_id = '".$page_id."'");
			echo "<BR><BR><BR><B><font size=3 color=blue>XÓA THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">BẠN CÓ MUỐN XÓA PAGE NÀY KHÔNG ?<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>
 </section>
          </section>