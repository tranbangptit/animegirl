<?php 
if (!defined('TRUNKSJJ')) die("Hack!");
class Temp{
    function template(){
	    global $mysqldb;
		$skinActive = get_data('cf_skin_default','config','cf_id',1);
		$skinFolder = get_data('skin_folder','skin','skin_id',$skinActive);
		return $skinFolder;
	}
}
?>