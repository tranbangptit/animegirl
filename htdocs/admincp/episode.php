<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");

$edit_url = 'javascript:history.go(-2)';
$edit_del = 'javascript:history.go(-1)';
if(isset($_GET['episode_id']))
$episode_id = (int)$_GET['episode_id'];
else $episode_id = false;
$inp_arr = array(
        'uplaidate'	=> array(
			'name'	=>	'Up lại cache (only link direct)',
			'type'	=>	'function::uplaidate::number'
		),
		'name'		=> array(
			'table'	=>	'episode_name',
			'name'	=>	'TẬP SỐ',
			'type'	=>	'free',
		),
		'film'		=> array(
			'table'	=>	'episode_film',
			'name'	=>	'TÊN PHIM',
			'type'	=>	'function::acp_film::number',
		),
		'file_type'	=> array(
			'table'	=>	'episode_servertype',
			'name'	=>	'SERVER',
			'desc'	=>	'If not already known in order to wear think of',
			'type'	=>	'function::set_type::number',
			'change_on_update'	=>	true,
		),
		'url'		=> array(
			'table'	=>	'episode_url',
			'name'	=>	'ĐƯỜNG DẪN',
			'type'	=>	'free',
		),
		'episode_urlsub'	 => array(
            'table'    =>    'episode_urlsub',
            'name'    =>    'Subtitle',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
        ),
		'episode_message'	 => array(
            'table'    =>    'episode_message',
            'name'    =>    'Message',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
        ),
		'new_film'	=>	array(
			'name'	=>	'THÊM PHIM NHANH',
			'type'	=>	'function::acp_quick_add_film_form::free',
			'desc'	=>	'If database ised havent Web is gently self-made',
			'can_be_empty'	=>	true,
		),
		'time_cache'	=>	array(
			'table'	=>	'episode_cache_time',
			'type'	=>	'hidden_value',
			'change_on_update'	=>	true,

		),
);
$inp_arr_edit = array(
        'uplaidate'	=> array(
			'name'	=>	'Up lại cache (only link direct)',
			'type'	=>	'function::uplaidate::number'
		),
		'name'		=> array(
			'table'	=>	'episode_name',
			'name'	=>	'TẬP SỐ',
			'type'	=>	'free',
		),
		'film'		=> array(
			'table'	=>	'episode_film',
			'name'	=>	'TÊN PHIM',
			'type'	=>	'function::acp_film::number',
		),
		'file_type'	=> array(
			'table'	=>	'episode_servertype',
			'name'	=>	'SERVER',
			'desc'	=>	'If not already known in order to wear think of',
			'type'	=>	'function::set_type::number',
			'change_on_update'	=>	true,
		),
		'episode_urlsub'	 => array(
            'table'    =>    'episode_urlsub',
            'name'    =>    'Subtitle',
			'type'	=>	'srtup',
			'can_be_empty'	=>	true,
        ),
		'episode_message'	 => array(
            'table'    =>    'episode_message',
            'name'    =>    'Message',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
        ),
		'url'		=> array(
			'table'	=>	'episode_url',
			'name'	=>	'ĐƯỜNG DẪN',
			'type'	=>	'free',
		),
		'time_cache'	=>	array(
			'table'	=>	'episode_cache_time',
			'type'	=>	'hidden_value',
			'change_on_update'	=>	true,

		),
);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Episode</li>
              </ul>
 
<?
##################################################
# ADD EPISODE
##################################################
if ($mode == 'multi_add') {
	include('multi_add_episode.php');
}
##################################################
# EDIT EPISODE
##################################################

if ($mode == 'remove') {
	$serverid	=	@$_GET['serverid'];
	$filmid		=	@$_GET['filmid'];
	if($serverid && $filmid) {
		$mysql->query("DELETE FROM ".$tb_prefix."episode WHERE episode_film = '$filmid' AND episode_servertype = '$serverid'");	
		echo 'EDIT FINISH <script>top.window.location = "'.$edit_del.'";</script>';
	}
	//exit();
}


if ($mode == 'edit') {
	if ($_POST['do']) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {
		$in_sql = implode(',',$arr);
		$mysql->query("DELETE FROM ".$tb_prefix."episode WHERE episode_id IN (".$in_sql.")");			
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_del."'>";
		}		
		if ($_POST['selected_option'] == 'multi_edit') {
			$arr = implode(',',$arr);
			echo "Loading... <meta http-equiv='refresh' content='0;url=index.php?act=multi_edit_episode&id=".$arr."'>";
		}
		elseif ($_POST['selected_option'] == 'normal') {
			$in_sql = implode(',',$arr);
			$mysql->query("UPDATE ".$tb_prefix."episode SET episode_broken = 0 WHERE episode_id IN (".$in_sql.")");
			$broken_fix = $mysql->fetch_array($mysql->query("SELECT episode_film FROM ".$tb_prefix."episode WHERE episode_id IN (".$in_sql.")"));
			$mysql->query("UPDATE ".$tb_prefix."film SET film_broken = 0 WHERE film_id = '".$broken_fix['episode_film']."'");
			echo 'EDIT FINISH <script>top.window.location = "'.$edit_url.'";</script>';
		}
		exit();
	}
	elseif ($episode_id) {

		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."episode WHERE episode_id = '$episode_id' ORDER BY episode_name ASC");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			if (!$r['episode_id']) {
				echo "THERE IS NO EPISODE";
				exit();
			}
			
				
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				if($file_type == 0) $file_type = acp_type($url);
				if ($new_film) {
				if(move_uploaded_file ($_FILES['upload_img']['tmp_name'],'../'.$img_film_folder."/".$_FILES['upload_img']['name']))
				$new_film_img = $img_film_folder."/".$_FILES['upload_img']['name'];
				else $new_film_img = $_POST['url_img'];
				$film = acp_quick_add_film($new_film,$new_film_img,$actor,$year,$time,$area,$director,$cat,$info,$country);
			    }
			$server_srt		=	$_POST['server_srt'];	
			if($server_srt == 1) {
				$episode_urlsub = $episode_urlsub;
			}elseif($server_srt == 2) {	
				$episode_urlsub	=	SRTupload("srtsub","sub","sub-".$episode_id);
			}
			    if($uplaidate == 2) {
				 $Key1 = 'html5-'.$episode_id;
                                $Key2 = 'flash-'.$episode_id;
				$phpFastCache->delete($Key1);
                                $phpFastCache->delete($Key2);
				}
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'episode','episode_id','episode_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo 'EDIT FINISH <script>top.window.location = "'.$edit_url.'";</script>';
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('EDIT EPISODE',$inp_arr_edit,$error_arr);
	}
	else {
			
		$episode_per_page = 30;
		if (!$pg) $pg = 1;
		if($server)	$extra=" episode_servertype=".$server;
		if($show_broken)	$extra=" episode_broken=".$show_broken;
		if ($film_id) {
        $q = $mysql->query("SELECT * FROM ".$tb_prefix."episode WHERE episode_film='".$film_id."' ".(($extra)?"AND ".$extra." ":'')."  ORDER BY episode_id ASC LIMIT ".(($pg-1)*$episode_per_page).",".$episode_per_page);
		$tt = get_total('episode','episode_id',"WHERE episode_film = '".$film_id."' ".(($extra)?"AND ".$extra." ":''));
		}
		if ($tt) {
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['episode_id'];
				$episode_name = $r['episode_name'];
				$film_name = (get_data("film_name","film","film_id",$r['episode_film']));
				$film_name_ascii =get_ascii($film_name);
				$server_name="<a href='index.php?act=episode&mode=edit&film_id=".$film_id."&server=".$r['episode_servertype']."'><b> ".acp_text_type($r['episode_servertype'])."</b>";
				$broken = ($r['episode_broken'])?'<font color=red><b>X</b></font>':'';
				$sub_yes = ($r['episode_urlsub'])?"<font color=\"green\"><b>[Sub]</b></font>":"";
				$message_yes = ($r['episode_message'])?"<font color=\"red\"><b>[Message]</b></font>":"";
	            if($r['episode_local']) $url = get_data('local_link','local','local_id',$r['episode_local']).$r['episode_url'];
                else $url = $r['episode_url'];
				$main_html .="<tr><td class=fr><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td><td class=fr><a href='index.php?act=episode&mode=edit&episode_id=".$id."'><b>Episode ".$episode_name."</b></a> ||".$server_name." </a><br/><input type='text' value='".$url."' size='30' class='form-control' />".$sub_yes." ".$message_yes."</td><td class=fr_2 align=center><b><a href=?act=film&mode=edit&film_id=".$r['episode_film'].">".$film_name."</a></b></td><td  class=fr_2 align=center>".$broken."</td><td class=fr align=center><a href='index.php?act=episode&mode=edit&episode_id=".$id."' target=_blank >Sửa</a> </td><td class=fr align=center><a href='../phim/".strtolower(replace($film_name))."-".$r['episode_film']."/".$id.".html' target=_blank >Xem phim</a> </td></tr>";
			}
			$server_list=str_replace(array('<select name=file_type class="form-control m-b">','value=0','DEFAULT'),array('<select name=file_type onchange="load_again(this.value)" class="form-control m-b">','value=""','Tất cả'),set_type($server));
			echo '<script>function load_again(url){window.location="index.php?act=episode&mode=edit&film_id='.$film_id.'&server="+url;}</script>
			<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Episode
                </header>
				<div class="row wrapper">
                  <div class="col-sm-5 m-b-xs">
                    
					  <span class="">
                        '.$server_list.' 
                      </span>             
                  </div>
				  
                  <div class="col-sm-4 m-b-xs">
				  <div class="input-group">
                  Hiển thị Episode ở Server 
				  </div>
                  </div>
                  <div class="col-sm-3">
                    <div class="input-group">
					 <input type="text" id="server_id" name="server_id" class="input-sm form-control" placeholder="Nhập ID server để xóa">
                      <span class="input-group-btn">
					  <input type="submit" class="btn btn-sm btn-default" value="Xóa" onclick="return removeserver();">
                      </span>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped b-t b-light">
				  <form name=media_list method=post action='.$link.' onSubmit="return check_checkbox();">
                    <thead>
                      <tr>
                        <th width="20"><input class=checkbox type=checkbox name=chkall id=chkall onclick=docheck(document.media_list.chkall.checked,0) value=checkall></th>
                        <th class="th-sortable" data-toggle="class">TẬP || ĐƯỜNG DẨN</th>
                        <th>TÊN PHIM</th>
                        <th>Lỗi</th>
                        <th>Sửa</th>
                        <th>Kiểm tra</th>';
			
			echo $main_html;
			echo '</tbody>
                  </table>
                </div>
                <footer class="panel-footer">
                  <div class="row">
                    <div class="col-sm-4 hidden-xs">
               	<select name="selected_option" class="input-sm form-control input-s-sm inline v-middle">
				  <option value=multi_edit>Sửa</option>
			    <option value=del>Xóa</option>
				<option value=normal>Thôi báo lỗi</option></select> 
                      <input type="submit" name="do" class=submit class="btn btn-sm btn-default" value="Apply">					  
                 </form>					  
                    </div>
                    <div class="col-sm-4 text-center">
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' phim</small>
                    </div>
                    <div class="col-sm-4 text-right text-center-xs">                
                      <ul class="pagination pagination-sm m-t-none m-b-none">
                        '.admin_viewpages($tt,$episode_per_page,$pg).'
                      </ul>
                    </div>
                  </div>
                </footer>
              </section>';
			 		
			}
		else echo "Phần này không có dữ liệu";
	}
}
?>
 </section>
          </section>
		  <script>
		  function removeserver(){

						window.location='index.php?act=episode&mode=remove&filmid=<?=$film_id;?>&serverid='+document.getElementById('server_id').value;
						return false;
					}
		  </script>