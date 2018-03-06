<?php
/////////////////////////////
require_once('bootstrap.php');
////////////////////////////
echo Registry::get('top');
$f = $_GET['f'];
$l = $_GET['l'];
$p = $_GET['p'];
$noidung = $_POST['noidung'];
if(isset($l) && isset($f)){
/////////////////////////////////////////////////
if(!file_exists($f)){ echo 'Tệp không tồn tại!'. Registry::get('foot');
; exit;}
/////////////////////////////////
$nd = file($f);
$sodong =  count($nd);
if(filesize($f)>1024000){
echo '<div class="title">File quá lớn</div>
<div class="list1">Tệp này quá lớn! Đề nghị chỉnh sửa bằng các công cụ sau để tránh lỗi!<br/>
<img src="vtr/img/Pen.png" alt="+"/> <a href="edit.php?'.$f.'">Gmanager</a></br>
</div>';
}else{
$nd = file($f);
$sodong =  count($nd);
if(empty($p)){ $p = 1;}
$sotrang = ceil($sodong/$l);
$batdau = ($p-1)*$l; $ketthuc = $batdau+$l;
/////////////////////////////////
//Phan tich URL
$u = explode("/",$f);
$su = count($u);
$url = str_replace($u[$su-1],'',$f);
/////////////////////////////////
echo '<div class="title">Sửa văn bản</div>
<div class="list1">Trang: '.$p.'/'.$sotrang.', Dòng: '.($sodong<$l ? $sodong:$l*$p).'/'.$sodong.'</div>';
echo '<div class="list1">';
echo '<img src="vtr/img/quay.png" alt=""/>  <a href="index.php?'.$url.'">'.$url.'</a>';
echo '</div>';
if(!empty($_COOKIE['luu']) || !empty($_SESSION['luu'])){
	echo '<h1 align="center"><font color="green">Thành công</font></h1>';
	setcookie('luu','',time()+24); $_SESSION['luu']='';
}
if(isset($_POST['save']) && strlen($noidung)>0){$op = fopen($f,"w+"); 
	for ($i=0;$i<$batdau;$i++){ fwrite($op, $nd[$i]);}
		if(fwrite($op,$noidung)){
			setcookie('luu','ok',time()+3600); $_SESSION['luu']='ok';
		}
	for ($i=$ketthuc;$i<$sodong+1;$i++){ fwrite($op, $nd[$i]);}
			echo '<meta http-equiv="refresh" content="0;'.$_SERVER['REQUEST_URI'].'">';
}

echo '<form method="post" action="suavb.php?f='.$f.'&l='.$l.'&p='.$p.'">';
echo '<textarea name="noidung" cols="80" rows="20">';
for($i=$batdau;$i<$ketthuc;$i++){ echo  htmlspecialchars($nd[$i], ENT_QUOTES, 'UTF-8');}
echo '</textarea><br/>';
echo '<div class="title" align="center"><input type="submit" name="save" value="lưu lại"/></div></form></div>';
if($sotrang>1){
$toi = $p+1; $lui = $p-1;
echo '<div class="title">Nhảy trang</div>
'.($p>'1' ? '<a href="suavb.php?f='.$f.'&l='.$l.'&p='.$lui.'">Trang trước</a> | ':'').'
'.($p < $sotrang ? '<a href="suavb.php?f='.$f.'&l='.$l.'&p='.$toi.'">Trang sau</a><br/>':'').'
<form method="get" action="suavb.php">
<input type="hidden" name="f" value="'.$f.'"/>
<input type="number" name="p" value="'.(isset($p) ? $p:'1').'"/>
<input type="hidden" name="l" value="'.$l.'"/>
<input type="submit" value="Đi"/>
</form>';
}}
}else{
echo '<div class="title">Sửa văn bản</div>
<div class="list1">
<form method="get" action="suavb.php">
Địa chỉ tệp:<br/>
<input type="text" name="f" value="'.$f.'"/><br/>
Số dòng mỗi trang:<br/>
<input type="number" name="l" size="10" value="'.$setdong.'"/><br/>
<input type="submit" value="Chỉnh sửa"/></form></div>';
}


echo  Registry::get('foot');
;exit;