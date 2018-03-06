<?php 
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
if(isset($_POST['submitcache'])){
    $Key = 'phimletv-'.$_POST['cache_key'];
	$Time = (int)$_POST['timecache'];
    if($_POST['cacheTYPE'] == 1)
	$phpFastCache->delete($Key);
	elseif($_POST['cacheTYPE'] == 2)
	$phpFastCache->clean();
elseif($_POST['cacheTYPE'] == 3){
$ServerArray = explode('|',$web_cache_key);
for($i=0;$i<count($ServerArray);$i++){		
$Key = 'phimletv-'.$ServerArray[$i];
$phpFastCache->delete($Key);
		}	
}
}
?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">CACHE</li>
              </ul>
             
			  <section class="panel panel-default">
                <header class="panel-heading font-bold">
                  CACHE SITE
                </header>
                <div class="panel-body">
                  <form class="form-horizontal" method="post">
				  <div class="form-group">
                      <label class="col-sm-2 control-label">UPDATE TIME( second)</label>
					  <div class="col-sm-10">Xóa cache <input type="radio" value="1"  name="cacheTYPE">
					  Xóa toàn bộ <input type="radio" value="2" name="cacheTYPE">
                                          Xóa trong list <input type="radio" value="3" checked name="cacheTYPE">
					 </div>
                    </div>
				  <div class="form-group">
                      <label class="col-sm-2 control-label">Danh sách</label>
					  <div class="col-sm-10"> 
					   <?=acp_cache_key();?>
					   </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div> 
					<div class="form-group">
                      <label class="col-sm-2 control-label">UPDATE TIME( second)</label>
					  <div class="col-sm-10"><input type="number" name="timecache" size="50" value="" class="form-control rounded"></div>
                    </div>
                   
                    <div class="line line-dashed line-lg pull-in"></div><div class="form-group">
                      <div class="col-sm-4 col-sm-offset-2">
                        <button type="submit" name="submitcache" class="btn btn-primary">Update</button>
                      </div>
                    </div>
                  </form>
                </div>
              </section>
			 </section>
          </section>