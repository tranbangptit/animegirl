<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
$filmId = (int)$_GET['film_id'];
if (!$filmId) die('ERROR: NONE OF FilmID');
if(!$_POST['submit']){
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Episode</li>
              </ul>
 
 <section class="panel panel-default">
                <header class="panel-heading font-bold">
                  SỬA NHIỀU TẬP PHIM
                </header>
                <div class="panel-body">
                <form method="post">
<table class="border" cellpadding="2" cellspacing="0" width="100%" align="center">

    <tbody>
	<tr>
	<td>Chọn server cần chỉnh sửa: </td>
	    <td class="fr" align="center" style="">
       <?=acp_film_ep_slt(1);?></td>
    </tr>
	<tr style="margin-top:5px;">
    	<td class="fr" colspan="5" align="center">
        	<font color="red">Demo: Name||link:</font><br />
			<blink><font color="red">Chú ý: Chỉ sử dụng cho phim bộ!</font></blink>
			
        </td>
    </tr>
    <tr>
		<td class="fr" align="center" colspan="2">
        	<p><textarea name="multilink" id="multilink" class="form-control" style="background:white;width:100%;height:250px"></textarea></p>
        </td>
	</tr>

    <tr>
    	<td class="fr" align="center" colspan="2">
			<input class="btn btn-primary" type="submit" name="submit">
		</td>
	</tr><tr>
  	
    </tr>
</tbody></table>
</form>
                </div>
              </section>




 </section>
          </section>
<?
}else{
    $serverId = (int)$_POST['server_ep_slt'];
	$total_url = $_POST['multilink'];
    $url = explode("\n", $total_url);
	for($i=0;$i<count($url);$i++){
	    $filter = explode("||",$url[$i]);
		$name = trim($filter[0]);
		$link = trim($filter[1]);
		$c_name = get_data_multi("episode_id","episode","episode_name = '".$name."' AND episode_servertype = '".$serverId."' AND episode_film = '".$filmId."'");
		if($c_name)
		echo "UPDATE<br />";
		else echo "INSERT<br />";
	   // echo $serverId.'-'.$filmId.'-Episode '.$name.'-'.$link.'<br />';
	}
}		  
?>