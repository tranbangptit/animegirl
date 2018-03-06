<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}
$edit_url = 'index.php?act=skin&mode=edit';
if(isset($_GET['skin_id']))
$skin_id = (int)$_GET['skin_id'];
else $skin_id = false;
$inp_arr = array(
		'name'	=> array(
			'table'	=>	'skin_name',
			'name'	=>	'SKIN NAME',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'folder'	=> array(
			'table'	=>	'skin_folder',
			'name'	=>	'DIR NAME IS RECIPIENT',
			'type'	=>	'free'
		),
		'order'	=> array(
			'table'	=>	'skin_order',
			'name'	=>	'ORDER',
			'type'	=>	'number',
			'can_be_empty'	=> true,
		),
	);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Skin</li>
              </ul>
<?		
##################################################
# ADD
##################################################
if ($mode == 'add') {
	if (isset($_POST['submit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {			
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'skin'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "ADD FINISH <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);
	$form->createForm('ADD SKIN',$inp_arr,$error_arr);
}
##################################################
# EDIT
##################################################
if ($mode == 'edit') {
	if (!isset($skin_id)) {
	if (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {

			$in_sql = implode(',',$arr);
			
			$mysql->query("DELETE FROM ".$tb_prefix."skin WHERE skin_id IN (".$in_sql.")");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
		if ($_POST['sbm']) {
			$z = array_keys($_POST);
			$q = $mysql->query("SELECT skin_id FROM ".$tb_prefix."skin");
			for ($i=0;$i<$mysql->num_rows($q);$i++) {
				$id = split('o',$z[$i]);
				$ord = ${$z[$i]};
				$mysql->query("UPDATE ".$tb_prefix."skin SET skin_order = '$ord' WHERE skin_id = '".$id[1]."'");
			}
		}
		echo "<script>function check_del(id) {".
		"if (confirm('WOULD YOU LIKE TO SCRUB?')) location='?act=skin&mode=del&skin_id='+id;".
		"return false;}</script>";
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
                        <th width="20"><input type="checkbox"></th>
                        
                        <th>Name</th>
                        <th>Position</th>
                        <th>Detail</th>
                        <th>Key</th>
                   
                      
                
                      </tr>
                    </thead>
                    <tbody>';
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."skin ORDER BY skin_id ASC");
		while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
			echo "<tr><td align=center class=fr><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=".$r['skin_id']."></td><td class=fr_2><a href=# onclick=check_del(".$r['skin_id'].")>DELETE</a> - <a href=?act=skin&mode=set_default&skin_id=".$r['skin_id'].">SET DEFAULT</a> - <a href='$link&skin_id=".$r['skin_id']."'><b>".$r['skin_name']."</b></a></td></tr>";
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
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$film_per_page.'/'.$tt.' ADS</small>
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
	else {
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."skin WHERE skin_id = '$skin_id'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'skin','skin_id','skin_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('EDIT SKIN',$inp_arr,$error_arr);
	}
}
if ($mode == 'set_default' && is_numeric($skin_id)) {
	$name = $mysql->query("SELECT skin_id FROM ".$tb_prefix."skin WHERE skin_id = '$skin_id'");
	$namer = $name->fetch(PDO::FETCH_ASSOC);
	if($namer['skin_id']) {
		$mysql->query("UPDATE ".$tb_prefix."config SET cf_skin_default = '".$namer['skin_id']."' WHERE cf_id = 1");
		echo "SET SKIN DEFAULT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
	}
	else echo "BROKEN";
	
}
##################################################
# DELETE
##################################################
if ($mode == 'del') {
	if ($skin_id) {
		if ($_POST['submit'] && is_numeric($skin_id) && $act=='skin' && $mode == 'del') {
			$mysql->query("DELETE FROM ".$tb_prefix."skin WHERE skin_id = $skin_id");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">
		WOULD YOU LIKE TO SCRUB?<br>
		<input value="YES" name=submit type=submit class=submit>
		</form>
<?
	}
}
?>
 </section>
          </section>