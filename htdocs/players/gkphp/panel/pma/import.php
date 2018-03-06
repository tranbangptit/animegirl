<?php
require_once('../pma.php'); // wap phpmyadmin
// ionutvmi@gmail.com
// master-land.net
@ini_set('memory_limit', '100M');
@set_time_limit(0);
include "lib/settings.php";
include "lib/zip.php";
include "lib/pagination.class.php";
connect_db($db);
if(isset($_GET['db']) && $_GET['db'] !='') 
{
	$check = $db->query("SHOW DATABASES LIKE '".$db->real_escape_string($_GET['db'])."'");
	$check = $check->num_rows;

	$db_name=trim($_GET['db']);
	// if no db exit
	if($db_name == '' OR $check == 0) { 
		header("Location: main.php"); exit;}

	// select db
	$db->select_db($db_name);
}

//$file_name="data/-".date("d-M")."-".time().".sql";
$file_name='data/'.$_GET['db'].'-'.date("D-m-y_h-i-s").".sql";
if(isset($_POST['url']) && $_POST['url']!=='http://'){
	$_sql=file_get_contents($_POST['url']);
	// we assume that the file is sql
	$fp = fopen($file_name,"w");
	fwrite_stream($fp,$_sql);
	fclose($fp);
}elseif(isset($_GET['file'])){
	$_sql=file_get_contents($_GET['file']);
}else{
	move_uploaded_file($_FILES['file']['tmp_name'],$file_name);
	$_sql=file_get_contents($file_name);
}
if($_POST['sv']=='1' || $_GET['sv']=='1'){
			$fp = fopen($file_name,"w");
			fwrite_stream($fp,$_sql);
}
	// sending query
if($_POST['th']=='1' || $_GET['th']=='1'){
		//$_sql = htmlspecialchars($_sql,ENT_QUOTES);
	$result = $db->multi_query($_sql);
	if (!$result) {
		$_err[] = $db->error;
	} else {
		$_sql_nr=0;
			while ($db->next_result())
				$_sql_nr++;
			 if ($db->error) { 
		  $_err[] = $db->error; 
		} 
	}
}
	// end else
if($_GET['sv']!=='1'){
@unlink($file_name);
}
$isimport=true;
$pma->title=$lang->Import;
include $pma->tpl."header.tpl";
include $pma->tpl."import.tpl";
include $pma->tpl."footer.tpl";
