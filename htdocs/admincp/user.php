<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "Ban khong du quyen de truy cap vao phan nay.";
	exit();
}
$edit_url = 'index.php?act=user&mode=edit';

$inp_arr = array(
		'name'		=> array(
			'table'	=>	'user_name',
			'name'	=>	'USER NAME',
			'type'	=>	'free',
		),
		'password'	=> array(
			'table'	=>	'user_password',
			'name'	=>	'PASSWORD',
			'type'	=>	'free',
			'always_empty'	=>	true,
			'update_if_true'	=>	'trim($password) != ""',
			'can_be_empty'	=>	true,
		),
		'fullname'	=> array(
			'table'	=>	'user_fullname',
			'name'	=>	'Full Name',
			'type'	=>	'free',
		),
		'email'	=> array(
			'table'	=>	'user_email',
			'name'	=>	'Email',
			'type'	=>	'free',
		),
		'avatar'	=> array(
			'table'	=>	'user_avatar',
			'name'	=>	'Avatar',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
		),
		'ban'	=> array(

			'table'	=>	'user_ban',
			'name'	=>	'Ban Nick',
			'type'	=>	"function::acp_user_ban",
		),
		'level'	=> array(
			'table'	=>	'user_level',
			'name'	=>	'PERMISSION',
			'type'	=>	'function::acp_user_level::number',
		),
		
);
$inp_arr_level =array(
		'level_name'	=> array(
			'table'	=>	'user_level_name',
			'name'	=>	'Tên cấp bậc',
			'type'	=>	'free',
		),
		'level_color'	=> array(
			'table'	=>	'user_level_color',
			'name'	=>	'Màu sắc phân biệt',
			'type'	=>	'free',
		),
		'level_group'	=> array(
			'table'	=>	'user_level_group',
			'name'	=>	'Ảnh nhóm phân biệt',
			'type'	=>	'free',
		),
		/*
		'level_own'	=> array(
			'table'	=>	'user_level_own',
			'name'	=>	'Server ưu tiên cho cấp bậc này. Mỗi server cách nhau bởi dấu phẩy<br/>(Nếu giá trị là 0 sẽ xem được tất cả các server, Server được thêm nên được cấp phép là server dành cho thành viên trước khi thêm vào đây. Cấp phép tại Cấu Hình Hệ Thống)',
			'type'	=>	'free',
		),*/
	);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Members</li>
              </ul>
<?	
##################################################
# ADD LEVEL
##################################################
if ($mode == 'add_level') {
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr_level);
		if (!$error_arr) {
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'user_level'),$inp_arr_level);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('ADD LEVEL',$inp_arr_level,$error_arr);
}
##################################################
# EDIT LEVEL
##################################################
if ($mode == 'edit_level') {
	if ($user_level_type) {
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."user_level WHERE user_level_type = '$user_level_type'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			
			foreach ($inp_arr_level as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr_level);
			if (!$error_arr) {
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'user_level','user_level_type','user_level_type'),$inp_arr_level);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa cấp bậc thành viên',$inp_arr_level,$error_arr);
	}
	else
	{
		echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Level
                </header>
                <div class="row wrapper">
             
                </div>
                <div class="table-responsive">
				<form name="media_list" method=post action='.$link.' onSubmit="return check_checkbox();">
                  <table class="table table-striped b-t b-light">
                    <thead>
                      <tr>
                       
                        <th class="th-sortable" data-toggle="class">Tên Level</th>
                        <th>Mã màu</th>
                        <th>Member</th>
                      
                
                      </tr>
                    </thead>
                    <tbody>';	
			$level_query = $mysql->query("SELECT user_level_type,user_level_name,user_level_color,user_level_group FROM ".$tb_prefix."user_level ORDER BY user_level_type ASC");
			while ($level = $level_query->fetch(PDO::FETCH_ASSOC)) {
				$tt = get_total('user','user_id',"WHERE user_level = ".$level['user_level_type']."");
				echo "<tr><td class=fr_2 width=50%><img src=".$level['user_level_group']." width=15 height=15 border=0> - <a href='$link&user_level_type=".$level['user_level_type']."'><b>".$level['user_level_name']."</b></a></td><td class=fr_2 width=50%>Màu ".$level['user_level_color']." - <font style='color:".$level['user_level_color']."'>".$level['user_level_color']."</font></td><td class=fr_2 width='5%' align=center>".$tt."</td></tr>";
			}
			echo ' </tbody>
                  </table>
                </div>
                <footer class="panel-footer">
                  <div class="row">
                    <div class="col-sm-4 hidden-xs">
               	<select name="selected_option" class="input-sm form-control input-s-sm inline v-middle">
				<option value="del">Xóa</option></select>
                      <button type="submit" class="btn btn-sm btn-default" name="do">Apply</button>       
                 </form>					  
                    </div>
                    <div class="col-sm-4 text-center">
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' video</small>
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
# ADD USER
##################################################
if ($mode == 'add') {
	if ($_POST['submit']) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$password = md5(stripslashes($_POST['password']));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'user'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "ADD FINISH <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('ADD USER',$inp_arr,$error_arr);
}
##################################################
# EDIT USER
##################################################
if ($mode == 'edit') {
	if (isset($us_del_id)) {
		if (isset($_POST['submit'])) {
			$mysql->query("DELETE FROM ".$tb_prefix."user WHERE user_id = ".$us_del_id);
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">
		WOULD YOU LIKE TO SCRUB?<br>
		<input value="YES" name=submit type=submit class=submit>
		</form>
		<?
	}
	elseif (isset($_POST['do'])) {
	    if(isset($_POST['checkbox']))
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if (isset($_POST['selected_option']) && $_POST['selected_option'] == 'del') {
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."user WHERE user_id IN (".$in_sql.")");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		elseif (isset($_POST['selected_option']) &&  $_POST['selected_option'] == 'ban') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."user SET user_ban = 1, user_ban_time = '".NOW."' WHERE user_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		elseif (isset($_POST['selected_option']) &&  $_POST['selected_option'] == 'no_ban') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."user SET user_ban = 0, user_ban_time = '' WHERE user_id IN (".$in_sql.")");
			echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif(isset($us_id)) {
		if(!isset($_POST['submit'])){
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_id = '$us_id'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			
			foreach ($inp_arr as $key=>$arr) $$key = (($r[$arr['table']]));
			
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				if(isset($_POST['password'])) 
				$password = md5(stripslashes($_POST['password']));
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'user','user_id','us_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('EDIT USER',$inp_arr,$error_arr);
	}
	else {
		$m_per_page = 30;
		if (!$pg) $pg = 1;
		if ($user_ban) {
        	$q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_ban = 1 ".(($extra)?"AND ".$extra." ":'')." ".$order." LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
			$extra = " user_ban = 1";
		}elseif ($point){
			$extra = " user_level = 2 OR user_level = 3";
			 $q = $mysql->query("SELECT * FROM ".$tb_prefix."user WHERE user_level = 2 OR user_level = 3 ".(($extra)?"AND ".$extra." ":'')." ".$order." LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		}else{
			$search = trim(urldecode($_GET['search']));
			$extra = (($search)?"user_name LIKE '%".$search."%' ":'');
			
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."user ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY user_id DESC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		}
		$tt = get_total('user','user_id',"".(($extra)?"WHERE ".$extra." ":'')."");
		if ($tt) {
			if ($search) {
				$link2 = preg_replace("#&search=(.*)#si","",$link);
			}
			else $link2 = $link;
			
			echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Member
                </header>
                <div class="row wrapper">
                  <div class="col-sm-3">
                    <div class="input-group">
                      <input type="text" class="input-sm form-control" id="xsearch" placeholder="Search" value="'.$search.'">
                      <span class="input-group-btn">
                        <button class="btn btn-sm btn-default" type="button" onclick="window.location.href = \''.$link2.'&search=\'+document.getElementById(\'xsearch\').value;">Go!</button>
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
                        <th>Username</th>
                        <th>Chức vụ</th>
                        <th>Phim</th>
                        <th>Banned</th>
                        <th>Ngày Đăng ký</th>
                <th>Fb ID</th>
                      </tr>
                    </thead>
                    <tbody>';	
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$banned_date="";
			    $id = $r['user_id'];
				$name = $r['user_name'];
				$post = get_total('film','film_id',"WHERE film_upload = ".$id."");
				$banned  = ($r['user_ban'])?'<font color=red><b>X</b></font>':'';
				if ($r['user_time']!="") $user_date= RemainTime($r['user_time']);
	            $level=get_data('user_level_name','user_level','user_level_type',$r['user_level']);
				echo "<tr>
				<td><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td>
				<td class=fr>#".$id."</td>
				<td class=fr><a href='$link&us_id=".$id."'><b>".$name."</b></a></td>
				<td class=fr_2 align=center>".$level."</td>
				<td class=fr align=center>".$post." <a href='index.php?act=film&mode=edit&film_upload=".$id."'>(Tìm)</></td>
				<td class=fr align=center>".$banned."</td>
				<td class=fr_2 align=center>".$user_date."</td>
<td class=fr_2 align=center>".$r['user_fb_oauth_uid']."</td>
				</tr>";
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
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' video</small>
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
		else echo "THERE IS NO USERS";
	}
	
}
?>
 </section>
          </section>