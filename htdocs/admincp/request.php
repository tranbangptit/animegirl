<?
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");

$view_url = 'index.php?act=request&mode=edit';
if(isset($_GET['request_id']))
$request_id = (int)$_GET['request_id'];
if(isset($_GET['reply_id']))
$reply_id = (int)$_GET['reply_id'];
$inp_arr = array(
         'request_content'	=> array(
			'table'	=>	'request_content',
			'name'	=>	'Nội dung yêu cầu',
			'type'	=>	'texts'
		),
         'request_type'	=> array(
			'table'	=>	'request_type',
			'name'	=>	'Trả lời cho ID (if "0" = main request)',
			'type'	=>	'free'
		),
		
);
$inp_arr_rep = array(
         'request_content'	=> array(
			'table'	=>	'request_content',
			'name'	=>	'Nội dung yêu cầu',
			'type'	=>	'texts'
		),
		'request_type'	=> array(
			'table'	=>	'request_type',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'request_time'	=> array(
			'table'	=>	'request_time',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
		'request_title'	=> array(
			'table'	=>	'request_title',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		),
'request_url'	=> array(
			'table'	=>	'request_url',
			'type'	=>	'hidden_value',
			'can_be_empty'	=> true,
		)
		
);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Request</li>
              </ul>
<?
##################################################
# UPDATE REQUEST
##################################################
if ($mode == 'edit') {
	if (isset($request_del_id)) {	
		if ($_POST['submit']) {
			$mysql->query("DELETE FROM ".$tb_prefix."request WHERE request_id = '".$request_del_id."'");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
			exit();
		}
		?>
		<form method="post">Bạn có muốn thực hiện<br><input value="YES" name=submit type=submit class=submit></form>
		<?
	}
	elseif (isset($_POST['do'])) {
		$arr = $_POST['checkbox'];
		if (!count($arr)) die('BROKEN');
		if ($_POST['selected_option'] == 'del') {
			$in_sql = implode(',',$arr);
			$mysql->query("DELETE FROM ".$tb_prefix."request WHERE request_id IN (".$in_sql.")");
			echo "DEL FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
		}

	}
	elseif (isset($request_id)) {
                $error_arr = array();
		if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."request WHERE request_id = '$request_id'");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'request','request_id','request_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$view_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('VIEW INFO OF REQUEST',$inp_arr,$error_arr);
	}
	elseif (isset($reply_id)) {

		if (isset($_POST['submit'])) {
		$error_arr = array();
		$error_arr = $form->checkForm($inp_arr_rep);
		if (!($error_arr)) {
            $request_title = get_data("user_name","user","user_id",$_SESSION['admin_id']);
			$inp_arr_rep['request_title']['value'] = $request_title;
			$request_time = NOW;
			$inp_arr_rep['request_time']['value'] = $request_time;
			$request_type = $reply_id;
			$inp_arr_rep['request_type']['value'] = $request_type;
			$sql = $form->createSQL(array('INSERT',$tb_prefix.'request'),$inp_arr_rep);
			eval('$mysql->query("'.$sql.'");');
			echo "<BR><BR><BR><B><font size=3 color=blue>THÊM REQUEST THÀNH CÔNG</font></B> <meta http-equiv='refresh' content='0;url=$view_url'>";
			exit();
		}
	}
	$warn = $form->getWarnString($error_arr);

	$form->createForm('REPLY THAT REQUEST',$inp_arr_rep,$error_arr);
	}
	else {
		$request_per_page = 30;
		if (!$pg) $pg = 1;
		$search = strtolower(get_ascii(urldecode($_GET['xsearch'])));
		$extra = (($search)?"request_content LIKE '%".$search."%' ":'');
		$q = $mysql->query("SELECT * FROM ".$tb_prefix."request ".(($extra)?"WHERE ".$extra." ":'')."ORDER BY request_id DESC LIMIT ".(($pg-1)*$request_per_page).",".$request_per_page);
		$tt = get_total('request','request_id',"".(($extra)?"WHERE ".$extra." ":'')."");
		if ($tt) {
			if ($search) {
				$link2 = preg_replace("#&xsearch=(.*)#si","",$link);
			}
			else $link2 = $link;
			echo '<section class="panel panel-default">
                <header class="panel-heading">
                  Danh sách Yêu Cầu
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
				<form name="media_list" method="post" action="'.$link.'" onSubmit="return check_checkbox();">
                  <table class="table table-striped b-t b-light">
                    <thead>
                      <tr>
                        <th width="20px"><input type="checkbox"></th>
                        <th class="th-sortable" data-toggle="class">Tên</th>
                        <th width="200px">Nội dung</th>
                        <th>Time</th>
                        <th>TYPE</th>
                        <th>Confirm</th>
                        <th>Tools</th>
                        <th>IP</th>
                       <th>URL</th>
                
                      </tr>
                    </thead>
                    <tbody>';
			while ($r = $q->fetch(PDO::FETCH_ASSOC)) {
				$id 		= $r['request_id'];
                $title		= $r['request_title'];
				$contents 	= $r['request_content'];
				$type 	= $r['request_type'];
				if($type == 0) $types = 'Hỏi'; else $types = "Trả lời";
				$rtime 		= date('G:i:s d/m/Y',$r['request_time']);
				echo '<tr>
						<td align=center>
							<input class="checkbox" type="checkbox" id="checkbox" name="checkbox[]" value="'.$id.'">
						</td>
<td>
							<b><a href='.$link.'&request_id='.$id.'>'.$title.'</a></b>
						</td>
						<td>
							<b><a href='.$link.'&request_id='.$id.'>'.$contents.'</a></b>
						</td>
						<td>
							'.$rtime.'
						</td>
						<td>
							'.$types.'
						</td>
<td>
							'.$r['request_confirm'].'
						</td>
						<td>
							<a href="index.php?act=request&mode=edit&reply_id='.$id.'">Trả lời</a>
						</td>
<td>
							'.$r['request_ip'].'
						</td>
<td>
							'.$r['request_url'].'
						</td>
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
                      <small class="text-muted inline m-t-sm m-b-sm">Trang '.$pg.' - Hiển thị '.$request_per_page.'/'.$tt.' video</small>
                    </div>
                    <div class="col-sm-4 text-right text-center-xs">                
                      <ul class="pagination pagination-sm m-t-none m-b-none">
                        '.admin_viewpages($tt,$request_per_page,$pg).'
                      </ul>
                    </div>
                  </div>
                </footer>
              </section>';
		}
		else echo "CHƯA CÓ YÊU CẦU NÀO";
    }
}
?>
 </section>
          </section>