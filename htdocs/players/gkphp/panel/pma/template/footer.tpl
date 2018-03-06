<?php
// wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net

?>
</div>
<div class='footer'>
<?php
if($pma->host){
echo "<a href='files.php?".($db_name ? "db=".urlencode($db_name) : "")."'>$lang->Files</a> | ".(!$_SESSION['noimg'] ? "<a href='action.php?act=noimg'> $lang->hide_img </a>" : "<a href='action.php?act=img'> $lang->show_img </a>")." | <a href='action.php?act=logout'> $lang->logout </a>
<hr size='1px'>";
}
?>
01.03.2014 Mod & Fix by Tmc</div>
</body>
</html>