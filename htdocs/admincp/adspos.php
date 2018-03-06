<?
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");

function time_encode_input($timestamp) {
	$date	= 	date('Y/m/d G:i',$timestamp);
	$html	=	'<input type="text" id="datetimepicker" class="form-control rounded" name="time" size="50" value="'.$date.'">';
	return $html;
}
if(isset($_GET['adspos_id']))
$adspos_id = (int)$_GET['adspos_id'];
else $adspos_id = false;
$edit_url = 'index.php?act=adspos&mode=edit';

$inp_arr = array(
		'name'	=> array(
			'table'	=>	'adspos_name',
			'name'	=>	'Tên Vị Trí',
			'type'	=>	'free',
		),
		'detail'	=> array(
			'table'	=>	'adspos_detail',
			'name'	=>	'Mô tả vị trí',
			'type'	=>	'texts',
		),
		'pos'	=> array(
			'table'	=>	'adspos_pos',
			'name'	=>	'Vị trí của ADS trên site',
			'type'	=>	'free',
		)
	);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Advertise Position</li>
              </ul>
<?	
##################################################
# ADD ADS
##################################################
if ($mode == 'add') {

	if (isset($_POST['submit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr);
		if (!$error_arr) {
		
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'adspos'),$inp_arr);
			eval('$mysql->query("'.$sql.'");');
			echo "ADD FINISH <meta http-equiv='refresh' content='0;url=$link'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('THÊM ADS',$inp_arr,$error_arr);
}
##################################################
# EDIT ADS
##################################################
if ($mode == 'edit') {
	if ($_POST['do']) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {
			
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."adspos WHERE ads_id IN (".$in_sql.")");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif ($adspos_id) {
	
		if (!$_POST['submit']) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."adspos WHERE adspos_id = '".$adspos_id."'");

			$r = $q->fetch(PDO::FETCH_ASSOC);
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'adspos','adspos_id','adspos_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('SỬA ADS',$inp_arr,$error_arr);
	}
	else {
	
       	$m_per_page = 30;
		if (!$pg) $pg = 1;
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."adspos ORDER BY adspos_id ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = get_total('adspos','adspos_id');
		if ($tt) {
			$html = '<section class="panel panel-default">
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
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['adspos_id'];
				$name = $r['adspos_name'];
				$key = $r['adspos_pos'];
				$detail = $r['adspos_detail'];
			
				$html .= "<tr>
				<td class=fr><input class=checkbox type=checkbox id=checkbox onclick=docheckone() name=checkbox[] value=$id></td>
				<td class=fr><i>#".$id."</i></td>
				<td class=fr><b><a title=\"".$name."\" href=?act=adspos&mode=edit&adspos_id=".$id.">".$name."</a></b></td>
				<td class=fr_2>".$detail."</td><td class=fr_2 align=center>".$key."</td>
				</tr>";
			}
			$html .= ' </tbody>
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
			echo $html;
		}
		else echo "Không có Vị trí ADS nào!";
	}
}
?>
 </section>
          </section>