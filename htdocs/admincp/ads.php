<?
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");

function time_encode_input($timestamp) {
	$date	= 	date('Y/m/d G:i',$timestamp);
	$html	=	'<input type="text" id="datetimepicker" class="form-control rounded" name="time" size="50" value="'.$date.'">';
	return $html;
}

$edit_url = 'index.php?act=ads&mode=edit';

$inp_arr = array(
		'name'	=> array(
			'table'	=>	'ads_name',
			'name'	=>	'Tên Website',
			'type'	=>	'free',
		),
                'ads_close'	=> array(
			'table'	=>	'ads_close',
			'name'	=>	'Tắt quảng cáo',
			'type'	=>	'function::ads_close::number',
		),	
                'ads_mobile'	=> array(
			'table'	=>	'ads_mobile',
			'name'	=>	'Hiện trên mobile',
			'type'	=>	'function::ads_mobile::number',
		),	
                'ads_login'	=> array(
			'table'	=>	'ads_login',
			'name'	=>	'Hiện khi đăng nhập',
			'type'	=>	'function::ads_login::number',
		),
		'pos'	=> array(
			'table'	=>	'ads_pos',
			'name'	=>	'Vị trí của ADS trên site',
			'type'	=>	'function::acp_ads_pos::number',
		),		
		'embed'	=> array(
			'table'	=>	'ads_embed',
			'name'	=>	'Mã Nhúng',
			'type'	=>	'texts',
			'can_be_empty'	=> true,
		)
	);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Advertise</li>
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
			$time	=	strtotime($time);
		     $img = $_POST['img'];
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'ads'),$inp_arr);
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
	if (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if (isset($_POST['selected_option']) && $_POST['selected_option'] == 'del') {
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."ads WHERE ads_id IN (".$in_sql.")");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}
	}
	elseif (isset($ads_id)) {
	
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."ads WHERE ads_id = '$ads_id'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {

				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'ads','ads_id','ads_id'),$inp_arr);
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
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."ads ORDER BY ads_id ASC LIMIT ".(($pg-1)*$m_per_page).",".$m_per_page);
		$tt = get_total('ads','ads_id');
		if ($tt) {
				$html = '<section class="panel panel-default">
                <div class="table-responsive">
				<form name="media_list" method=post action='.$link.' onSubmit="return check_checkbox();">
                  <table class="table table-striped b-t b-light">
                    <thead>
                      <tr>
                       
                        <th class="th-sortable" data-toggle="class">#</th>
                        <th>ID</th>
                        <th>Tên QC</th>
                        <th>Vị trí(key)</th>
                        <th>Mobile</th>
                        <th>Login</th>
                        <th>Tình trạng</th>
                      </tr>
                    </thead>
                    <tbody>';
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id = $r['ads_id'];
				$pos = get_data("adspos_pos","adspos","adspos_id",$r['ads_pos']);
				if($r['ads_mobile'] == 1) $mobile = 'Tắt'; else $mobile = 'Bật';
				if($r['ads_login'] == 1) $login = 'Tắt'; else $login = 'Bật';
				if($r['ads_close'] == 1) $close = '<font color="red">Đóng</font>'; else $close = '<font color="green">Mở</font>';
				$html .= '<tr>
				<td class=fr><input class="checkbox" type="checkbox" id="checkbox" onclick="docheckone()" name="checkbox[]" value="'.$id.'"></td>
				<td class=fr>#'.$id.'</td>
				<td class=fr><b><a href="?act=ads&mode=edit&ads_id='.$id.'">'.$r["ads_name"].'</a></b></td>
				<td class=fr_2 align="center">'.$pos.'</td>
				<td class=fr_2 align="center">'.$mobile.'</td>
				<td class=fr_2 align="center">'.$login.'</td>
				<td class=fr_2 align="center">'.$close.'</td>
				</tr>';
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
		else echo "Không có ADS nào!";
	}
}
?>
 </section>
          </section>