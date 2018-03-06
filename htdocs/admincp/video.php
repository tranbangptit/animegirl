<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=video&mode=edit';
if(isset($_GET['video_id']))
$video_id = (int)$_GET['video_id'];
else $video_id = false;
$inp_arr = array(
		
		'video_name'	=> array(
			'table'	=>	'video_name',
			'name'	=>	'Tên Video',
			'type'	=>	'free',
			'can_be_empty'	=>	true
		),
		'video_cat'	=> array(
			'table'	=>	'video_cat',
			'name'	=>	'THỂ LOẠI',
			'type'	=>	'function::acp_cat_video::number',
			'can_be_empty'	=> true,
		),
		'video_url'	=> array(
			'table'	=>	'video_url',
			'name'	=>	'Video URL (Only Youtube Url)',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'video_upload'	=> array(
			'table'	=>	'video_upload',
			'name'	=>	'Người POST',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'video_duration'	=> array(
			'table'	=>	'video_duration',
			'name'	=>	'Video Duration',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'video_time'	=> array(
			'table'	=>	'video_time',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'video_key'	=> array(
			'table'	=>	'video_key',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'video_name_ascii'	=> array(
			'table'	=>	'video_name_ascii',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		

);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Video</li>
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
			$video_time = NOW;
            $inp_arr['video_time']['value'] = $video_time;
			
			$video_key = replace(strtolower($video_name));
			$inp_arr['video_key']['value'] = $video_key;
			
			$video_name_ascii = htmlchars(strtolower(get_ascii($video_name)));
			$inp_arr['video_name_ascii']['value'] = $video_name_ascii;
			
			$video_cat = ','.join_value($_POST['selectcat']);
			$inp_arr['video_cat']['value'] = $video_cat;
			
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'video'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "<BR><BR><BR><B><font size=3 color=blue>THÊM THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('THÊM VIDEO CLIPS',$inp_arr,$error_arr);
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
		
			$mysql->query("DELETE FROM ".$tb_prefix."video WHERE video_id IN (".$in_sql.")");

			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
		
		exit();
	}	
	elseif ($video_id) {
		$qq = $mysql->query("SELECT * FROM ".$tb_prefix."video WHERE video_id = '".$video_id."'");
			$rr = $qq->fetch(PDO::FETCH_ASSOC);
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."video WHERE video_id = '".$video_id."'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
			  $video_time = NOW;
            $inp_arr['video_time']['value'] = $video_time;
			
			$video_key = replace(strtolower($video_name));
			$inp_arr['video_key']['value'] = $video_key;
			
			$video_name_ascii = htmlchars(strtolower(get_ascii($video_name)));
			$inp_arr['video_name_ascii']['value'] = $video_name_ascii;
			
			$video_cat = ','.join_value($_POST['selectcat']);
			$inp_arr['video_cat']['value'] = $video_cat;
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'video','video_id','video_id'),$inp_arr);
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
		$order ='ORDER BY video_id DESC';
		if (!$pg) $pg = 1;
		$xsearch = (int)strtolower(get_ascii(urldecode($_GET['xsearch'])));
		$extra = (($xsearch)?"video_id = '".sqlescape($xsearch)."' ":'');		
		if ($cat_id) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."video WHERE video_cat LIKE '%,".$cat_id.",%' ".(($extra)?"AND ".$extra." ":'')."ORDER BY video_time DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('video','video_id',"WHERE video_cat LIKE '%,".$cat_id.",%'".(($extra)?"AND ".$extra." ":''));
		}
        else {
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."video ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY video_id DESC LIMIT ".(($pg-1)*$film_per_page).",".$film_per_page);
		$tt = get_total('video','video_id',"".(($extra)?"WHERE ".$extra." ":'')."");
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
				$id = $r['video_id'];
				$id2 = $id;
				$video_name = $r['video_name'];
		
				// Multi Cat
				$cat=explode(',',$r['video_cat']);
				$num=count($cat);
				$cat_name="";
				for ($i=1; $i<$num-1;$i++) $cat_name .= '| <i><font color="blue">'.(get_data('cat_name','cat','cat_id',$cat[$i])).'</font></i> ';

				echo '<tr>
                            <td> <input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id2.'"></td>
                            <td>#'.$id.'</td>
                            <td align="center"><img src="http://i.ytimg.com/vi/'.get_idyoutube($r['video_url']).'/2.jpg" width="90" height="54"></td>
							<td><b><a style="color:#555;" href=?act=video&mode=edit&video_id='.$id.'>'.$video_name.'</a></b></a></td>
                            <td><b>'.$r['video_url'].'</b></td>
                            <td><span style="float:left;padding-left:10px;"><b>'.$cat_name.'</b></span></td>
                            <td class=fr_2 align=center><b>'.$r['video_upload'].'</b></td>
                            <td class=fr_2 align=center><b>'.date('Y-m-d h:i:sa',$r['video_time']).'</b></td>
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
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' video</small>
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
		else echo "THERE IS NO VIDEO";
	}
}	
//getDuration_ytb()
if($mode == 'multi'){
if (!$_POST['submit']) {
?>
<section class="panel panel-default">
                <header class="panel-heading font-bold">
                  Multi Grab
                </header>
                <div class="panel-body">
<form enctype="multipart/form-data" method="post" class="form-horizontal">
<?
//GOOGLE_API
$z = 1;
if ($_POST['webgrab'] == 'playlist') {
    //https://www.youtube.com/playlist?list=PLEyKu1JwbU4u_b5osaEIik_yyOFXyEnYH
	//https://www.youtube.com/watch?v=RowaBT-4UPM
	$playlistUrl = $_POST['urlgrab'];
	$playlistId = explode('list=',$playlistUrl);
    $urlgrab = xem_web('https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId='.$playlistId[1].'&key='.GOOGLE_API.'&maxResults='.$_POST['maxitem']);
	$VidDuration =json_decode($urlgrab, true);
	foreach ($VidDuration['items'] as $vidTime) {
 $Vidt= $vidTime['snippet']['resourceId']['videoId'];
 $videoUrl = 'https://www.youtube.com/watch?v='.$Vidt;
$title = $vidTime['snippet']['title'];

?>
			
<div class="form-group">
<label class="col-sm-2 control-label" style="font-weight:bold;">Video <?=$z;?></label>
                      <div class="col-sm-8"><input class="form-control rounded" onclick="this.select()" type="text" name="video-title[<?=$z;?>]" value="<?=$title;?>"> <br /><input class="form-control rounded" onclick="this.select()" type="text" name="video-url[<?=$z;?>]" value="<?=$videoUrl;?>"></div>
					  <div class="col-sm-2">
					<input type="text" class="form-control rounded" style="width:100%;" name="video-duration[<?=$z;?>]" value="<?=getDuration_ytb($Vidt);?>"><br />
					<?=cat_video_show_id($_POST['danhmuc'],$z);?>
					  </div>
					 
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<?
$z = $z + 1;
}

}elseif($_POST['webgrab'] == 'search'){
$keysearch = str_replace(" ","+",$_POST['urlgrab']);
    $urlgrab = xem_web('https://www.googleapis.com/youtube/v3/search?part=id,snippet&q='.$keysearch.'&key='.GOOGLE_API.'&maxResults='.$_POST['maxitem'].'&type=video');
	$VidDuration =json_decode($urlgrab, true);
	foreach ($VidDuration['items'] as $vidTime) {
 $Vidt= $vidTime['id']['videoId'];
 $videoUrl = 'https://www.youtube.com/watch?v='.$Vidt;
$title = $vidTime['snippet']['title'];

?>
<div class="form-group">
<label class="col-sm-2 control-label" style="font-weight:bold;">Video <?=$z;?></label>
                      <div class="col-sm-8"><input class="form-control rounded" onclick="this.select()" type="text" name="video-title[<?=$z;?>]" value="<?=$title;?>"> <br /><input class="form-control rounded" onclick="this.select()" type="text" name="video-url[<?=$z;?>]" value="<?=$videoUrl;?>"></div>
					  <div class="col-sm-2">
					<input type="text" class="form-control rounded" style="width:100%;" name="video-duration[<?=$z;?>]" value="<?=getDuration_ytb($Vidt);?>"><br />
					<?=cat_video_show_id($_POST['danhmuc'],$z);?>
					  </div>
					 
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<?
$z = $z + 1;
}

}
?>
<input type=submit name=submit class="btn btn-danger" value="Send">
				
					</form>
					</div>
					</section>
<?
}else{
for ($i=1;$i<=50;$i++){
        $now = time();
		$v_url = $_POST['video-url'][$i];
		$v_title = htmlchars(stripslashes($_POST['video-title'][$i]));
		$v_title_ascii = htmlchars(strtolower(get_ascii($v_title)));
		$v_time = $_POST['video-duration'][$i];
		$v_cat = $_POST['video-cat'][$i];
		$v_key = replace(strtolower($v_title));
		$v_update = $now;
		//lech sub
		if ($v_title && $v_url) {
		$mysql->query("INSERT INTO ".$tb_prefix."video (video_name,video_name_ascii,video_cat,video_url,video_key,video_duration,video_upload,video_time) VALUES ('".$v_title."','".$v_title_ascii."',',".$v_cat.",','".$v_url."','".$v_key."','".$v_time."','".$_SESSION['admin_id']."','".$v_update."')");
		}

	}
	echo "Đã thêm xong <meta http-equiv='refresh' content='1;url=index.php?act=mvideo'>";
}
}
##################################################
# DELETE MEDIA SHOWTIME
##################################################
if ($mode == 'del') {
	//acp_check_permission_mod('del_country');
	if ($video_id) {
		if ($_POST['submit']) {
			//$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$actor_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."video WHERE video_id = '".$video_id."'");
			echo "<BR><BR><BR><B><font size=3 color=blue>XÓA THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">BẠN CÓ MUỐN XÓA VIDEO NÀY KHÔNG ?<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>
 </section>
          </section>