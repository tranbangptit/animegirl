<?php

require_once('bootstrap.php');

if(isset($_GET['f'])){
$f = $_GET['f'];
$d = $_GET['d'];
$ok = $_GET['ok'];
echo Registry::get('top');
if(isset($d)){
if(isset($ok)){
echo '<div class="title">Xóa đi phần tử?</div>';
echo '<div class="list1">';
$url = $_SESSION['url'];
if(empty($url)){ $url = 'index.php';}
if(@unlink($f)){ echo 'Xóa thành công! <meta http-equiv="refresh" content="1;'.$url.'">';}else{ echo 'Xảy ra lỗi! <meta http-equiv="refresh" content="3;'.$url.'">';}
echo '</div>';
header("location: ".$url);
echo  Registry::get('foot');
;exit;
}
echo '<div class="title">Xóa đi phần tử?</div>';
echo '<div class="list1">
Phần tử <a href="index.php?'.$f.'">'.$f.'</a><br/>
<form method="get" action="tuychon.php">
<input type="hidden" name="f" value="'.$f.'"/>
<input type="hidden" name="d" value="xoa"/>
<input type="hidden" name="ok" value="ok"/>
<input type="submit" value="Xóa ngay"/></form>
</div>';
echo  Registry::get('foot');
;exit;
}
$_SESSION['url'] = $_SERVER['HTTP_REFERER'];
if(preg_match(".",$f)){ $k = 'f';}else{ $k = 'd';}
echo '<div class="title">Tuỳ chọn</div>';
echo'<div class="list1"><img src="vtr/img/quay.png" alt="back"/> <a href="'.$_SERVER['HTTP_REFERER'].'">Quay lại</a></div>';
echo '<div class="list1">
'.(is_file($f) ? '<img src="vtr/img/Pen.png" alt="+"/> <a href="edit.php?'.$f.'">Chỉnh sửa</a><br/>
<img src="vtr/img/Write.png" alt="+"/> <a href="edit.php?lineEditor=1&c='.$f.'">Sửa từng dòng</a><br/>
<img src="vtr/img/Pen.png" alt="+"/> <a href="suavb.php?f='.$f.'">Sửa theo dòng </a>(<font color=red>New+Hot</font>)<br/>
<img src="vtr/img/Recycle.png" alt="+"/> <a href="change.php?'.$f.'">Copy/Di chuyển </a><br/>
<img src="vtr/img/Key.png" alt="+"/> <a href="change.php?go=chmod&c='.$f.'">Chmod </a><br/>
<img src="vtr/img/Trash.png" alt="+"/> <a href="tuychon.php?d=xoa&f='.$f.'">Xóa </a><br/>
':'<img src="vtr/img/FolderUp.png" alt="+"/> <a href="index.php?c='.$f.'">Mở thư mục </a><br/>
<img src="vtr/img/Recycle.png" alt="+"/> <a href="change.php?'.$f.'">Copy/Di chuyển </a><br/>
<img src="vtr/img/Key.png" alt="+"/> <a href="change.php?go=chmod&c='.$f.'">Chmod </a><br/>
').'
 <a href="change.php?go=mod&c='.$f.'">... Nhiều hơn ...</a><br/>
</div>';


echo  Registry::get('foot');
;
}else{
header("location: index.php");
}
