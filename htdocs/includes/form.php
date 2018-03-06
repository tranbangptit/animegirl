<?php
if (!defined('TRUNKSJJ')) die("Hacking attempt");
class HTMLForm {
	var $error_color = array(
		'empty'		=>	'#FCB222',
		'number'	=>	'#7EBA01',
		'>0'		=>	'#47A2CB',
		'>=0'		=>	'#585CFE',
		'url'		=>	'#202020',
	);
	function createSQL($config_arr,$inp_arr) {
		if ($config_arr[0] == 'INSERT') {
		    $s1 = false;
		    $s2 = false;
			foreach ($inp_arr as $key=>$arr) {
				if (!$arr['table']) continue;
				$s1 .= '`'.$arr['table'].'`,';
				if ($arr['type'] == 'hidden_value')	$s2 .= '\"'.$arr['value'].'\",';
				else $s2 .= '\'$'.$key.'\',';
			}
			$s1 = substr($s1,0,-1);
			$s2 = substr($s2,0,-1);
			$sql = "INSERT INTO ".$config_arr[1]." (".$s1.") VALUES (".$s2.")";
		}
		elseif ($config_arr[0] == 'UPDATE') {
		    $s1 = false;
		    $s2 = false;
			foreach ($inp_arr as $key=>$arr) {
				global $$key;
				if (!$arr['table']) continue;
				if (isset($arr['update_if_true']) && !eval('return ('.$arr['update_if_true'].');')) continue;
				
				if ($arr['type'] == 'hidden_value' && !$arr['change_on_update']) continue;
				if ($arr['type'] == 'hidden_value')	$s1 .= $arr['table'].' = \''.$arr['value'].'\', ';
				else $s1 .= $arr['table'].' = \"$'.$key.'\", ';
			}
			$s1 = substr($s1,0,-2);
			if ($config_arr[2] && $config_arr[3]) $sql = "UPDATE ".$config_arr[1]." SET ".$s1." WHERE ".$config_arr[2]." = '\$".$config_arr[3]."'";
			else $sql = "UPDATE ".$config_arr[1]." SET ".$s1."";
		}
		return $sql;
	}
	
	function getWarnString($error_arr) {
		if (!$error_arr) return;
		$warn = false;
		if (in_array('empty',$error_arr)) $warn .= "<b style='color:".$this->error_color['empty']."'>*</b> : Chưa nhập dữ liệu<br>";
		if (in_array('number',$error_arr)) $warn .= "<b style='color:".$this->error_color['number']."'>*</b> : Dữ liệu phải là số<br>";
		if (in_array('>0',$error_arr)) $warn .= "<b style='color:".$this->error_color['>0']."'>*</b> : Dữ liệu phải lớn hơn 0<br>";
		if (in_array('>=0',$error_arr)) $warn .= "<b style='color:".$this->error_color['>=0']."'>*</b> : Dữ liệu phải lớn hơn hoặc bằng 0<br>";
		if (in_array('url',$error_arr)) $warn .= "<b style='color:".$this->error_color['url']."'>*</b> : Dữ liệu phải là URL<br>";
		return substr($warn,0,-4);
	}
	function checkForm($inp_arr) {
		$error_arr = array();
		foreach ($inp_arr as $key=>$arr) {
			if ($arr['type'] == 'hidden_value') continue;
			global $$key;
		}
		foreach ($inp_arr as $key=>$arr) {
			if (!isset($$key) && isset($arr['can_be_empty'])) continue;
			if ($arr['type'] == 'hidden_value') continue;
			if (isset($arr['check_if_true']) && !eval('return ('.$arr['check_if_true'].');')) continue;
			
			$$key = htmlspecialchars($_POST[$key]);
			if ($arr['type'] == 'text' && $$key == '&lt;br&gt;') { $$key = ''; }
			if ($arr['type'] == 'text' && $$key == '&lt;pre&gt;&lt;/pre&gt;') { $$key = ''; }
			if ($arr['type'] == 'text' && $$key == '&lt;PRE&gt;&lt;/PRE&gt;') { $$key = ''; }
			if ($$key == '' && !$arr['can_be_empty']) $error_arr[$key] = 'empty';
			if (@ereg("^function::*::*",$arr['type'])) { $z = explode('::',$arr['type']); $type = $z[1]; }
			else $type = $arr['type'];
			if (!isset($error_arr[$key])) {
				if ($type == 'number' && !is_numeric($$key)) $error_arr[$key] = 'number';
				elseif ($type == 'number' && isset($arr['>0']) && $$key <= 0 ) $error_arr[$key] = '>0';
				elseif ($type == 'number' && isset($arr['>=0']) && $$key < 0 ) $error_arr[$key] = '>=0';
				elseif ($type == 'url' && !ereg("[http|mms|ftp|rtsp]://[a-z0-9_-]+\.[a-z0-9_-]+",$$key)) $error_arr[$key] = 'url';
			}
		}
		return $error_arr;
	}
	function createForm($title,$inp_arr,$error_arr) {
		global $warn;
		echo '<section class="panel panel-default">
                <header class="panel-heading font-bold">
                  '.$title.'
                </header>
                <div class="panel-body">
                  <form class="form-horizontal" method="post" enctype="multipart/form-data">';
		if($warn) echo '<div class="form-group">
                      <label class="col-sm-2 control-label">Lỗi</label>
                      <div class="col-sm-10">
                        '.$warn.'                        
                      </div>
                    </div>';
		
		
		foreach($inp_arr as $key=>$arr) {
			if ($arr['type'] == 'hidden_value') continue;
			global $$key;
			if (isset($arr['always_empty'])) $$key = '';
			if (@ereg("^function::*::*",$arr['type'])) {
			
				$ex_arr = explode('::',$arr['type']);
				$str = $ex_arr[1]($$key);
				$type = 'function';
			}
			else $type = $arr['type'];

			echo ' <div class="form-group">
                      <label class="col-sm-2 control-label">'.$arr['name'].((isset($arr['desc']))?"<br>".$arr['desc']:'').'</label>
					  <div class="col-sm-10">';
			$value = ($$key != '')?un_htmlchars(stripslashes($$key)):'';
			switch ($type) {
				case 'number' : echo "<input type=text name=\"".$key."\" size=10 value=\"".$value."\" class=\"form-control\">"; break;
				case 'type_number' : echo "<select name=\"".$key."\"><option value=\"1\">Tin Tức</option><option value=\"2\">Giải Trí</option></select>"; break;
				case 'free' : echo "<input type=text name=\"".$key."\" size=50 value=\"".$value."\" class=\"form-control rounded\">"; break;
				case 'free2' : echo "<input type=text name=\"".$key."\" value=\"".$value."\" class=\"form-control rounded\">"; break;
				case 'img' : echo "<input type=text name=\"".$key."\" size=50 value=\"".$value."\" class=\"form-control rounded\">
				                   <input type=file name=\"".$key."\" size=47 value=\"".$value."\">"; break;
				case 'srtup' : echo "<input class=\"form-control rounded\" type=text name=\"".$key."\" size=50 value=\"".$value."\">	<br>	
				<input class=\"filestyle\" size=\"50\" name=\"srtsub\" id=\"srtsub\" type=\"file\"><br>		
				Server chứa SUB: <input type=\"radio\" value=\"1\" checked name=\"server_srt\"> Ko Up	
				<input type=\"radio\" value=\"2\" name=\"server_srt\"> Up lại<br>"; break;	
				case 'img2' : echo "<input class=\"form-control rounded\" type=text name=\"".$key."\" size=50 value=\"".$value."\">
									Server chứa ảnh:
									<input type=\"radio\" value=\"1\" name=\"server_img\"> Ko Up
									<input type=\"radio\" value=\"2\" checked name=\"server_img\"> Picasa>"; break;	
				case 'img3' : echo "<input class=\"form-control rounded\" type=text name=\"".$key."\" size=50 value=\"".$value."\">
									Server chứa ảnh:
									<input type=\"radio\" value=\"1\" name=\"server_img\"> Ko Up
									<input type=\"radio\" value=\"2\" checked name=\"server_img\"> Picasa"; break;
				case 'img5' : echo "<input class=\"form-control rounded\" type=text name=\"".$key."\" size=50 value=\"".$value."\">
					                <input class=\"form-control rounded\" size=\"50\" name=\"phimimg\" id=\"phimimg\" type=\"file\">
									Server chứa ảnh:
									<input type=\"radio\" value=\"1\" checked name=\"server_img\"> Không Up
									<input type=\"radio\" value=\"2\" name=\"server_img\"> Picasa
									<input type=\"radio\" value=\"3\" name=\"server_img\"> Local
									<input type=\"radio\" value=\"4\" name=\"server_img\"> ImgUr
									";

									break;	
				case 'imgbn' : echo "<input class=\"form-control rounded\" type=text name=\"".$key."\" size=50 value=\"".$value."\">
				                     <input size=\"50\" name=\"phimimgbn\" id=\"phimimgbn\" type=\"file\">
									Server chứa ảnh:
									<input type=\"radio\" value=\"1\" checked name=\"server_imgbn\"> Không Up
									<input type=\"radio\" value=\"2\" name=\"server_imgbn\"> Picasa
									<input type=\"radio\" value=\"3\" name=\"server_imgbn\"> Local
									<input type=\"radio\" value=\"4\" name=\"server_imgbn\"> ImgUr"; break;
case 'imgkinhdien' : echo "<input class=\"form-control rounded\" type=text name=\"".$key."\" size=50 value=\"".$value."\">
				                     <input size=\"50\" name=\"phimimgkinhdien\" id=\"phimimgkinhdien\" type=\"file\">
									Server chứa ảnh:
									<input type=\"radio\" value=\"1\" checked name=\"server_imgkinhdien\"> Không Up
									<input type=\"radio\" value=\"2\" name=\"server_imgkinhdien\"> Picasa
									<input type=\"radio\" value=\"3\" name=\"server_imgkinhdien\"> Local
									<input type=\"radio\" value=\"4\" name=\"server_imgkinhdien\"> ImgUr"; break;
				case 'uplaidate' : echo "<input type=\"radio\" value=\"1\" checked name=\"update_time\"> Ko Up
									<input type=\"radio\" value=\"2\" name=\"update_time\"> Up Lại"; break;
				case 'password' : echo "<input class=\"form-control rounded\" type=password name=\"".$key."\" size=50 value=\"".$value."\">"; break;
				case 'url' : echo "<input class=\"form-control rounded\" type=text name=\"".$key."\" size=50 value=\"".$value."\">"; break;
				case 'function' : echo $str.""; break;
				case 'text' : echo "<textarea rows=8 class=\"form-control valid parsley-validated\" cols=70 id=\"".$key."\" name=\"".$key."\">".$value."</textarea><script>CKEDITOR.replace('".$key."'); </script>"; break;
				case 'texts' : echo "<textarea rows=8 class=\"form-control valid parsley-validated\" cols=70 id=\"".$key."\" name=\"".$key."\">".$value."</textarea>"; break;
				case 'checkbox'	:	echo "<input value=1".(($arr['checked'])?' checked':'')." type=checkbox class=checkbox name=".$key.">"; break;
			}
			if (isset($error_arr[$key])) {
				echo ' ';
				switch ($error_arr[$key]) {
					case 'empty'	:	echo "<b style='color:".$this->error_color['empty']."'>*</b>";	break;
					case 'number'	:	echo "<b style='color:".$this->error_color['number']."'>*</b>";	break;
					case '>0'		:	echo "<b style='color:".$this->error_color['>0']."'>*</b>";		break;
					case '>=0'		:	echo "<b style='color:".$this->error_color['>=0']."'>*</b>";	break;
					case 'url'		:	echo "<b style='color:".$this->error_color['url']."'>*</b>";	break;
				}
			}
			echo '</div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>';
		}
		
		echo '<div class="form-group">
                      <div class="col-sm-4 col-sm-offset-2">
                        <button type="submit" name="submit" class="btn btn-primary">Save changes</button>
                      </div>
                    </div>
                  </form>
                </div>
              </section>';
	}
}
?>