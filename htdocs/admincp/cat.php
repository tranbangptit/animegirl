<?php 
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=cat&mode=edit';
$link = 'index.php?act=cat&mode=edit';
if(isset($_GET['cat_id']))
$cat_id = (int)$_GET['cat_id'];
else $cat_id = false;
$inp_arr = array(
		'name'	=> array(
			'table'	=>	'cat_name',
			'name'	=>	'Tên thể loại',
			'type'	=>	'free'
		),
		'name_ascii'	=>	array(
			'table'	=>	'cat_name_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
		'name_title'	=> array(
			'table'	=>	'cat_name_title',
		                     	'name'	=>	'Thể loại (title)',
			'type'	=>	'free'
		),
                'cat_child'	=> array(
			'table'	=>	'cat_child',
			'name'	=>	'Category child of',
			'type'	=>	'function::cat_child_of::number',
'can_be_empty'	=> true
		),
'cat_type'	=> array(
			'table'	=>	'cat_type',
			'name'	=>	'Category show',
			'type'	=>	'function::cat_show::number',
'can_be_empty'	=> true
		),
		'name_key'	=> array(
			'table'	=>	'cat_name_key',
			                     'name'	=>	'Thẻ Key',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
		),	
		'order'	=> array(
			'table'	=>	'cat_order',
			'name'	=>	'Thứ tự(nếu khác CẤP 1 thì nên để bằng ORDER của CẤP 1)',
			'type'	=>	'number',
			'can_be_empty'	=>	true,
		),

);
?>

<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Thể loại</li>
              </ul>
             
<?
##################################################
# ADD MEDIA CAT
##################################################
if ($mode == 'add') {
    $error_arr = array();
	if (isset($_POST['submit'])) {
		
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$inp_arr['name_ascii']['value'] = strtolower(get_ascii($name));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'cat'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm thể loại',$inp_arr,$error_arr);
}
##################################################
# EDIT MEDIA CAT
##################################################
if ($mode == 'edit') {	
	if ($cat_id) {
		if (!(isset($_POST['submit']))) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."cat WHERE cat_id = '".$cat_id."'");
			$r	=	$q->fetch(PDO::FETCH_ASSOC);
			$error_arr = array();
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
			
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['name_ascii']['value'] = strtolower(get_ascii($name));
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'cat','cat_id','cat_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa thể loại',$inp_arr,$error_arr);
	}
	else {
		

		echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Thể Loại
                </header>
                <div class="row wrapper">
                  <div class="col-sm-3" style="display:none;">
                    <div class="input-group">
                      <input type="text" class="input-sm form-control" placeholder="Search">
                      <span class="input-group-btn">
                        <button class="btn btn-sm btn-default" type="button">Go!</button>
                      </span>
                    </div>
                  </div>
                </div>
                <div class="table-responsive">
                  <table class="table table-striped b-t b-light">
                    <thead>
                      <tr>
                        <th width="20"><input type="checkbox"></th>
                        <th class="th-sortable" data-toggle="class">Tên thể loại</th>
                        <th>Quản lý</th>
                        <th>Level</th>
                        <th>Tool</th>
                
                      </tr>
                    </thead>
                    <tbody>';
		$cat_query = $mysql->query("SELECT * FROM ".$tb_prefix."cat ORDER BY cat_child,cat_order ASC");
		while ($cat = $cat_query->fetch(PDO::FETCH_ASSOC)) {
			$tt = get_total('film','film_id',"WHERE film_cat LIKE '%,".$cat['cat_id'].",%'");
			$iz = $cat['cat_order'];
$zz = $cat['cat_child'];
if($zz == 0) $cat_child = 'Cấp 1'; else{
$cat_child = get_data('cat_name','cat','cat_id',$zz);
}
			echo ' <tr>
                        <td><input type="checkbox" name="post[]" value="'.$cat['cat_id'].'"></td>
                        <td><a class="active" href="'.$link.'&cat_id='.$cat['cat_id'].'">'.$cat['cat_name'].'</td>
                        <td><a class="active" href="?act=film&mode=edit&cat_id='.$cat['cat_id'].'">Tập phim</a></td>
                        <td>'.$cat_child.'</td>
                        <td><a class="active" href="?act=cat&mode=del&cat_id='.$cat['cat_id'].'">Xóa</a></td>
                    </tr>';
			
		}
		echo ' </tbody>
                  </table>
                </div>
                <footer class="panel-footer">
                  <div class="row">
                    <div class="col-sm-4 hidden-xs" style="display:none;">
                      <select class="input-sm form-control input-s-sm inline v-middle">
                        <option value="0">Bulk action</option>
                        <option value="1">Delete selected</option>
                        <option value="2">Bulk edit</option>
                        <option value="3">Export</option>
                      </select>
                      <button class="btn btn-sm btn-default">Apply</button>                  
                    </div>
                    <div class="col-sm-4 text-center" style="display:none;">
                      <small class="text-muted inline m-t-sm m-b-sm">showing 20-30 of 50 items</small>
                    </div>
                    <div class="col-sm-4 text-right text-center-xs" style="display:none;">                
                      <ul class="pagination pagination-sm m-t-none m-b-none">
                        <li><a href="#"><i class="fa fa-chevron-left"></i></a></li>
                        <li><a href="#">1</a></li>
                        <li><a href="#">2</a></li>
                        <li><a href="#">3</a></li>
                        <li><a href="#">4</a></li>
                        <li><a href="#">5</a></li>
                        <li><a href="#"><i class="fa fa-chevron-right"></i></a></li>
                      </ul>
                    </div>
                  </div>
                </footer>
              </section>';

	}
}
##################################################
# DELETE MEDIA CAT
##################################################
if ($mode == 'del') {

	if ($cat_id) {
		if (isset($_POST['submit'])) {
			$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_cat = '".$cat_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."cat WHERE cat_id = '".$cat_id."'");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">Bạn có muốn xóa thể loại này không ?<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>


            </section>
          </section>