<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
if (!$_GET['id']) die('ERROR');
$id = $_GET['id'];
$cut = explode(",",$id);
$filmId = get_data("episode_film","episode","episode_id",$cut[0]);
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Episode</li>
              </ul>
 
<section class="panel panel-default">
                <header class="panel-heading font-bold">
                  Sửa tập phim
                </header>
                <div class="panel-body">
				  <form class="form-horizontal" enctype="multipart/form-data" method="post">


<div class="form-group">
                      <label class="col-sm-2 control-label">Phim</label>
					  <div class="col-sm-10">
					  <?php echo acp_film(NULL,1);?>
									  </div>
                    </div><div class="line line-dashed line-lg pull-in"></div>
					<? for($i=0;$i<count($cut)-1;$i++){
					    $episodeId = (int)$cut[$i];
					    $episodeUrl = get_data("episode_url","episode","episode_id",$episodeId);
					    $episodeName = get_data("episode_name","episode","episode_id",$episodeId);
					    $episodeServer = get_data("episode_servertype","episode","episode_id",$episodeId);
					?>
					<div class="form-group">
                      <label class="col-sm-2 control-label">Tập <input onclick="this.select()" type="text" name="name[<?php echo $episodeId;?>]" value="<?php echo $episodeName;?>" size=2 style="text-align:center"></label>
					  <div class="col-sm-10">
					Link: <input type="text" class="form-control rounded" style="width:100%;" name="url[<?=$episodeId;?>]" value="<?=trim($episodeUrl);?>">
					
					  </div>
					 
                    </div><div class="line line-dashed line-lg pull-in"></div>
					<? } ?>
					<div class="form-group">
                      <div class="col-sm-4 col-sm-offset-2">
					  <input type="submit" name="submit" class="btn btn-primary" value="Save changes">
      
                      </div>
                    </div><table class="border" cellpadding="2" cellspacing="0" width="95%">

</table>



                  </form>
                </div>
              </section>
 </section>
          </section>
<?
if(isset($_POST["submit"])){
   for($i=0;$i<count($cut)-1;$i++){
	   $episodeId = (int)$cut[$i];
	   $t_url = $_POST['url'][$episodeId];
	   $t_name = $_POST['name'][$episodeId];
		if($t_url != '' && $t_name != ''){
		    $mysql->query("UPDATE ".$tb_prefix."episode SET episode_url = '".$t_url."',episode_name = '".$t_name."' WHERE episode_id = ".$episodeId."");
		}			   
    
}
echo "Đã Sửa xong ~> Chuyển về danh sách tập phim <meta http-equiv='refresh' content='0;url=?act=episode&mode=edit&film_id=".$filmId."'>";

}
?>		  