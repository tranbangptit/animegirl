<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$edit_url = 'index.php?act=country&mode=edit';
$link = 'index.php?act=country&mode=edit';
if(isset($_GET['country_id']))
$country_id = (int)$_GET['country_id'];
else $country_id = false;
$inp_arr = array(
		'name'	=> array(
			'table'	=>	'country_name',
			'name'	=>	'Tên quốc gia',
			'type'	=>	'free'
		),
		'name_ascii'	=>	array(
			'table'	=>	'country_name_ascii',
			'type'	=>	'hidden_value',
			'value'	=>	'',
			'change_on_update'	=>	true,
		),
		'name_title'	=> array(
			'table'	=>	'country_name_title',
			'name'	=>	'Thẻ Title',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
		),
		'name_key'	=> array(
			'table'	=>	'country_name_key',
			'name'	=>	'Thẻ Key',
			'type'	=>	'free',
			'can_be_empty'	=>	true,
		),	
		'order'	=> array(
			'table'	=>	'country_order',
			'name'	=>	'Thứ tự',
			'type'	=>	'number',
			'can_be_empty'	=>	true,
		),
		

);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Quốc gia</li>
              </ul>
              <div class="m-b-md">
                <h3 class="m-b-none">Quốc gia</h3>
              </div>
<?
##################################################
# ADD MEDIA COUNTRY
##################################################
if ($mode == 'add') {
    $error_arr = array();
	if (isset($_POST['submit'])) {
		
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
			$inp_arr['name_ascii']['value'] = strtolower(get_ascii($name));
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'country'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('Thêm quốc gia',$inp_arr,$error_arr);
}##################################################
# EDIT MEDIA COUNTRY
##################################################
if ($mode == 'edit') {	
    $error_arr = array();
	if ($country_id) {
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."country WHERE country_id = '$country_id'");
			$r	=	$q->fetch(PDO::FETCH_ASSOC);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$inp_arr['name_ascii']['value'] = strtolower(get_ascii($name));
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'country','country_id','country_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "Đã sửa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Sửa quốc gia',$inp_arr,$error_arr);
	}
	else {
		
		echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Quốc Gia
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
                        <th class="th-sortable" data-toggle="class">Tên Quốc Gia</th>
                        <th>Quản lý</th>
                        <th>Tool</th>
                
                      </tr>
                    </thead>
                    <tbody>';
		$country_query = $mysql->query("SELECT * FROM ".$tb_prefix."country ORDER BY country_order ASC");
		while ($country = $country_query->fetch(PDO::FETCH_ASSOC)) {
			$tt = get_total('film','film_id',"WHERE film_country = ".$country['country_id']."");
			$iz = $country['country_order'];
			
			echo ' <tr>
                        <td><input type="checkbox" name="post[]" value="'.$country['country_id'].'"></td>
                        <td><a class="active" href="'.$link.'&country_id='.$country['country_id'].'">'.$country['country_name'].'</td>
                        <td><a class="active" href="?act=film&mode=edit&country_id='.$country['country_id'].'">Tập phim</a></td>
                        <td><a class="active" href="?act=country&mode=del&country_id='.$country['country_id'].'">Xóa</a></td>
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
# DELETE MEDIA country
##################################################
if ($mode == 'del') {

	if ($country_id) {
		if (isset($_POST['submit'])) {
			$mysql->query("DELETE FROM ".$tb_prefix."film WHERE film_country = '".$country_id."'");
			$mysql->query("DELETE FROM ".$tb_prefix."country WHERE country_id = '".$country_id."'");
			echo "Đã xóa xong <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">Bạn có muốn xóa quốc gia này không ?<br><input value="Có" name=submit type=submit class=submit></form>
<?
	}
}
?>

            </section>
          </section>