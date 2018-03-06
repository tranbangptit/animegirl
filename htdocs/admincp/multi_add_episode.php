<?php
error_reporting(E_ERROR| E_PARSE);
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
function sql_escape($value)
  {
      
      if(get_magic_quotes_gpc())
        {
            $value=stripcslashes($value);    
        }
        
      if(function_exists("mysql_real_escape_string"))
       {
           $value=mysql_real_escape_string($value);   
       }else{
              $value=addslashes($value);
            }
     return $value;    
     
     /** 
       - Phải dùng câu truy vấn giống thế này mới phát huy hàm này 
         $qr2="SELECT * FROM tin WHERE idLT= '".$id."'";
         Hoặc
         $qr2="SELECT * FROM tin WHERE idLT= '$id' ";
       - Tuyệt đối không sử dụng câu truy vấn như thế này
         $qr2="SELECT * FROM tin WHERE idLT= $id ";
      Tóm lại; tất cả các tham số khi truy vấn phải để trong nháy đơn.     
     **/        
  }
 
if (!$_POST['ok'] && !$_POST['submit'])  {

?>
 <section class="panel panel-default">
                <header class="panel-heading font-bold">
                  THÊM TẬP PHIM MỚI
                </header>
                <div class="panel-body">
                <form method="post">
<table class="border" cellpadding="2" cellspacing="0" width="100%" align="center">

    <tr>
        <td class="fr" align="center" width="50%">
            <input name="episode_begin" class="form-control rounded" value="Tập bắt đầu" onclick="this.select()"/>
        </td>
    
        <td class="fr" align="center">
            <input name="episode_end" class="form-control rounded" value="Tập kết thúc" onclick="this.select()"/>
        </td>
    </tr>
	<tr>
    	<td class="fr" colspan="2" align="center">
        	<font color="red">Chú ý</font>
        </td>
    </tr>
	<tr>
    	<td class="fr" align="left" style="padding-left:50px;">
        	<input type="radio" name="is_full" id="is_full[]" value="Full" /><label>&nbsp;Sửa "Full" cho tập đầu</label>&nbsp;<br/>
            <input type="radio" name="is_full" id="is_full[]" value="Download" /><label>&nbsp;Sửa "Download" cho tập đầu</label>&nbsp;
        </td>
		<td class="fr" align="left" style="padding-left:50px;">
        	<input type="checkbox" name="end" id="end"  /><label>&nbsp;Thêm "End" tập kết thúc</label><br />
            <input type="radio" name="is_full" id="is_full[]" value="Trailer" /><label>&nbsp;Sửa "Trailer" cho tập đầu</label>&nbsp;

        </td>
    <tr>
    	<td class="fr" align="left" style="padding-left:50px;">
            <input type="radio" name="is_sort" id="is_sort[]" value="0" checked="checked" /><label>&nbsp;Dạng  1,2,3,4,5,6,7,8,9</label>&nbsp;
        </td>
		<td class="fr" align="left" style="padding-left:50px;">
			<input type="radio" name="is_sort" id="is_sort[]" value="1" /><label>&nbsp;Dạng  1a,1b,2a,2b,1a,1b,2a,2b</label>&nbsp;
        </td>
    </tr>
	<tr>
	<td>Chọn server cần add: </td>
	    <td class="fr" align="center" style="">
       <?=acp_film_ep_slt(1);?>
        </td>
    </tr>
    <tr>
		<td class=fr align=center>
        	Số Server: <input name="part_per_ep" class="form-control rounded" value="Số server" onclick="this.select()">
        </td>
		<td class=fr align=center>
        	Số tập nhỏ: <input name="part_per_ep2" class="form-control rounded" value="S&#7889; ph&#7847;n/t&#7853;p" onclick="this.select()">
        </td>
    </tr>

	<tr style="margin-top:5px;">
    	<td class="fr" colspan="5" align="center" >
        	<font color="red">Nhập link vào khung bên dưới:</font>
        </td>
    </tr>
    <tr>
		<td class=fr align=center colspan=2>
        	<p><textarea name="multilink" id="multilink" class="form-control" style="background:white;width:100%;height:250px"></textarea></p>
        </td>
	</tr>

    <tr>
    	<td class="fr" align="center" colspan="2">
			<input  class="btn btn-primary" type="submit" name="ok" class="submit">
		</td>
	<tr>
  	
    </tr>
</table>
</form>
                </div>
              </section>




<?php

}

else

{

$url = $_POST['multilink'];

$url_clip = explode("\n", $url);

$begin = $_POST['episode_begin'];

$end = $_POST['episode_end'];
if(!$film_id)
$add_new_film = '<div class="form-group">
                      <label class="col-sm-2 control-label">Tên Phim</label>
					  <div class="col-sm-10">
					  <input name="new_film" class="form-control rounded" size="40">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
                      <label class="col-sm-2 control-label">Tên English</label>
					  <div class="col-sm-10">
					  <input name="name_real" class="form-control rounded" size="40">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Trạng Thái</label>
					  <div class="col-sm-10">
					 <input name="trang_thai" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Poster</label>
					  <div class="col-sm-10">
					<input name="url_img" class="form-control rounded" size="50">
					<input class="filestyle" size="50" name="phimimg" id="phimimg" type="file" >
					<br />
					Server chứa ảnh:
		<input type="radio" value="1" name="server_img"> Không Up
		<input type="radio" value="3" checked name="server_img"> Picasa
		<input type="radio" value="2" name="server_img"> Local
		<input type="radio" value="4" name="server_img"> Imgur
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
					<div class="form-group">
                      <label class="col-sm-2 control-label">Banner</label>
					  <div class="col-sm-10">
					 <input name="url_imgbn" class="form-control rounded" size="50">
					 <input class="filestyle" size="50" name="phimimgbn" id="phimimgbn" type="file">
					 Server chứa ảnh:
		<input type="radio" value="1" name="server_imgbn"> Không up
		<input type="radio" value="3" checked name="server_imgbn"> Picasa
		<input type="radio" value="2" name="server_imgbn"> Local
		<input type="radio" value="4" name="server_imgbn"> Imgur
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
                      <label class="col-sm-2 control-label">Đạo Diễn</label>
					  <div class="col-sm-10">
					<input name="director" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Diễn Viên</label>
					  <div class="col-sm-10">
					<input name="actor" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Sản Xuất</label>
					  <div class="col-sm-10">
					<input name="area" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Năm Sản Xuất</label>
					  <div class="col-sm-10">
					<input name="year" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Ngôn Ngữ</label>
					  <div class="col-sm-10">
					'.trang_thai(NULL).'
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Thời Lượng</label>
					  <div class="col-sm-10">
					<input name="time" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Chất lượng</label>
					  <div class="col-sm-10">
					<input name="tapphim" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Điểm IMDb</label>
					  <div class="col-sm-10">
					<input name="imdb" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>			
<div class="form-group">
                      <label class="col-sm-2 control-label">Trailer (Youtube)</label>
					  <div class="col-sm-10">
					<input name="trailer" class="form-control rounded" size="50">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Hình thức Phim</label>
					  <div class="col-sm-10">
					'.film_lb(0).'
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>			
<div class="form-group">
                      <label class="col-sm-2 control-label">Quốc Gia</label>
					  <div class="col-sm-10">
					'.acp_country().'
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Thể Loại</label>
					  <div class="col-sm-10">
					'.acp_cat().'
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>		
<div class="form-group">
                      <label class="col-sm-2 control-label">Thông Tin Phim</label>
					  <div class="col-sm-10">
					<textarea  class="form-control" name="info" class="ckeditor" id="editor1" cols="50" rows="6"></textarea>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Từ Khóa Phim</label>
					  <div class="col-sm-10">
					<textarea  class="form-control" name="tag" class="ckeditor" id="editor1" cols="50" rows="6"></textarea>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>						';


else $add_new_film = '<div class="form-group">
                      <label class="col-sm-2 control-label">Từ Khóa Phim</label>
					  <div class="col-sm-10">
					'.acp_film($film_id).'
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>		';
////BEGIN CHECK EPISODE

if(!is_numeric($begin) && !is_numeric($end)){
    $episode_begin = 1;

    $episode_end = 10;
}
elseif(!is_numeric($begin)){
    $episode_begin = $episode_end = $end;

}

else{
    $episode_begin = $begin; 

	$episode_end = $end;
}

////END CHECK EPISODE

if (!$_POST['submit']) {

?>
<section class="panel panel-default">
                <header class="panel-heading font-bold">
                  Thêm Phim Mới
                </header>
                <div class="panel-body">
				  <form class="form-horizontal" enctype="multipart/form-data" method="post">

<?php	if(!$film_id) {	?>

<table class="border" cellpadding="2" cellspacing="0" width="80%" align="center">


<tr>

	<td class="fr" width="10%"><b>Lựa chọn</b></td>

	<td class="fr_2"><?php echo acp_film();?></td>
</tr>

<tr>
	<td class=fr width="10%">
		<b>THÊM PHIM NHANH</b>
		<br>If database ised havent Web is gently self-made</td>
	<td class=fr_2 >
<?=$add_new_film?>




 
<?php

$is_end = $_POST['end'];
$is_full = $_POST['is_full'];
$is_ep_end = 0;

$part_ep = $_POST['part_per_ep'];
$part_ep_2 = $_POST['part_per_ep2'];
if (!is_numeric($part_ep)) $part_ep=1;
if (!is_numeric($part_ep_2)) $part_ep_2=1;
$m=0;
if ($_POST['is_sort']==0)
{
	for ($i=$episode_begin;$i<=$episode_end;$i++) 
	{
		if ($i<10) $j= ''.$i;
		elseif ($i>9 && $i<100) $j=''.$i;
		else $j = $i;
		$b=range('`','z');
		for ($e=1;$e<=$part_ep_2;$e++)
		{
			if ($part_ep_2>1) $ep = $i.$b[$e];
			else $ep = $i;
			if ( $is_end =="on" && $i==$episode_end && $e==$part_ep_2) $ep .= "-End";  
			for ($s =1; $s<=$part_ep;$s++,$m++)
				{	
					
					$is_ep_end++;
					if ($ep<10) $j= ''.$ep;
					elseif ($ep>9 && $ep<100) $j=''.$ep;
					else $j = $ep;
					if ( $is_full && $i==$episode_begin) $j = $is_full;
					$dmcc = $url_clip[$m];
					$xxxx	=	explode("||",$dmcc);
					$link	=	trim($xxxx[1]);
					$subk	=	$xxxx[0];
					if($link){
					$link_s = $link;
					$jjj	= $xxxx[0];
					}else{
					$link_s = $xxxx[0];
					$jjj	= $j;
					}
					
?>
<div class="form-group">
                      <label class="col-sm-2 control-label">Tập <input onclick="this.select()" type="text" name="name[<?php echo $is_ep_end;?>]" value="<?php echo $jjj;?>" size=2 style="text-align:center"></label>
					  <div class="col-sm-10">
					<input type="text" class="form-control rounded" style="width:100%;" name="url[<?=$is_ep_end;?>]" value="<?=$link_s;?>"><br />
					<input type="text" class="form-control rounded" style="width:100%;" name="sub[<?=$is_ep_end;?>]" value="" /><br />
					<?=acp_film_ep($_POST['server_ep_slt'],$is_ep_end);?>
					  </div>
					 
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>

<?php
			}
		}
	}
}
else
{
	$total_ep =($episode_end-$episode_begin +1)*$part_ep*$part_ep_2;
	for ($i=1,$s= $episode_begin,$p=1,$p_e= $episode_begin;$i<=$total_ep;$i++,$s++,$p++) 
	{
		if ($i<10) $j= ''.$i;
		elseif ($i>9 && $i<100) $j=''.$i;
		else $j = $i;
		$b=range('`','z');
		if($part_ep > 0)
		{
			if ($part_ep_2>1)
			{
				if ($p>$part_ep_2)
				{	
					$p=1;
					$p_e++;
				}
				if ($p_e > $episode_end) $p_e= $episode_begin;
				$ep = $p_e.$b[$p];
			}
			else
			{
				if($s>$episode_end)
				{
					$ep_end =true;
					$s = $episode_begin;
				}
				$ep = $s;
			}
		}
		else $ep=$i;	
		if ( $is_end =="on" && ( $ep_end || ($p_e==$episode_end && $p==$part_ep_2))) $ep .= "-End"; 
		$xxxx	=	explode("||",$url_clip[$i-1]);
		$link	=	trim($xxxx[0]);
		$subk	=	$xxxx[1];
		$is_ep_end++;
		if ($ep<10) $j= ''.$ep;
		elseif ($ep>9 && $ep<100) $j=''.$ep;
		else $j = $ep;
		if ( $is_full && ( $s==$episode_begin || ($p_e==$episode_begin && $p==1))) $j = $is_full;
?>
<div class="form-group">
                      <label class="col-sm-2 control-label">Tập <input onclick="this.select()" type="text" name="name[<?php echo $is_ep_end;?>]" value="<?php echo $jjj;?>" size=2 style="text-align:center"></label>
					  <div class="col-sm-10">
					<input type="text" class="form-control rounded" style="width:100%;" name="url[<?=$is_ep_end;?>]" value="<?=trim($link);?>"><br />
					<input type="text" class="form-control rounded" style="width:100%;" name="sub[<?=$is_ep_end;?>]" value="" /><br />
					<?=acp_film_ep($_POST['server_ep_slt'],$is_ep_end);?>
					  </div>
					 
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>

<?php
	}
}
?>
	</td>
</tr>
<tr><td class="fr" colspan="2" align="center">

<input type="hidden" name="episode_begin" value="1">

<input type="hidden" name="episode_end" value="<?php echo $is_ep_end;?>">

<input type="hidden" name="ok" value="SUBMIT">

<input class="btn btn-primary" type="submit" name="submit" class="submit"></td></tr>

<script>

var total = <?php echo $is_ep_end;?>;

function check_local(id){

    for(i=1;i<=total;i++)

           document.getElementById("local_url["+i+"]").value=id;
}

</script> 

</table>	

<?php	} else { ?>

<table class="border" cellpadding="2" cellspacing="0" width="95%">

<div class="form-group">
                      <label class="col-sm-2 control-label">Phim</label>
					  <div class="col-sm-10">
					<?php echo acp_film($film_id);?>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	




<?php

$is_end = $_POST['end'];
$is_ep_end = 0;

$part_ep = $_POST['part_per_ep'];
$part_ep_2 = $_POST['part_per_ep2'];
if (!is_numeric($part_ep)) $part_ep=1;
if (!is_numeric($part_ep_2)) $part_ep_2=1;
$m=0;
if ($_POST['is_sort']==0)
{
	for ($i=$episode_begin;$i<=$episode_end;$i++) 
	{
		if ($i<10) $j= ''.$i;
		elseif ($i>9 && $i<100) $j=''.$i;
		else $j = $i;
		$b=range('`','z');
		for ($e=1;$e<=$part_ep_2;$e++)
		{
			if ($part_ep_2>1) $ep = $i.$b[$e];
			else $ep = $i;
			if ( $is_end =="on" && $i==$episode_end && $e==$part_ep_2) $ep .= "-End"; 
			for ($s =1; $s<=$part_ep;$s++,$m++)
				{	
					//$xxxx	=	explode("|",$url_clip[$m]);
					//$link	=	trim($xxxx[0]);
					//$subk	=	$xxxx[1];
					$is_ep_end++;
					if ($ep<10) $j= ''.$ep;
					elseif ($ep>9 && $ep<100) $j=''.$ep;
					else $j = $ep;
					if ( $is_full && $i==$episode_begin) $j = $is_full;
					$dmcc = $url_clip[$m];
					$xxxx	=	explode("||",$dmcc);
					$link	=	trim($xxxx[1]);
					if($link){
					$link_s = $link;
					$jjj	= $xxxx[0];
					}else{
					$link_s = $xxxx[0];
					$jjj	= $j;
					}
					
?>
<div class="form-group">
                      <label class="col-sm-2 control-label">Tập <input onclick="this.select()" type="text" name="name[<?php echo $is_ep_end;?>]" value="<?php echo $jjj;?>" size=2 style="text-align:center"></label>
					  <div class="col-sm-10">
					Link: <input type="text" class="form-control rounded" style="width:100%;" name="url[<?=$is_ep_end;?>]" value="<?=$link_s;?>"><br />
					Sub: <input type="text" class="form-control rounded" style="width:100%;" name="sub[<?=$is_ep_end;?>]" value="" /><br />
				
					Server: <?=acp_film_ep($_POST['server_ep_slt'],$is_ep_end);?>
					  </div>
					 
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<?php
			}
		}
	}
}
else
{
	$total_ep =($episode_end-$episode_begin +1)*$part_ep*$part_ep_2;
	for ($i=1,$s= $episode_begin,$p=1,$p_e= $episode_begin;$i<=$total_ep;$i++,$s++,$p++) 
	{
		if ($i<10) $j= ''.$i;
		elseif ($i>9 && $i<100) $j=''.$i;
		else $j = $i;
		$b=range('`','z');
		if($part_ep > 0)
		{
			if ($part_ep_2>1)
			{
				if ($p>$part_ep_2)
				{	
					$p=1;
					$p_e++;
				}
				if ($p_e > $episode_end) $p_e= $episode_begin;
				$ep = $p_e.$b[$p];
			}
			else
			{
				if($s>$episode_end)
				{
					$ep_end =true;
					$s = $episode_begin;
				}
				$ep = $s;
			}
		}
		else $ep=$i;	
		if ( $is_end =="on" && ( $ep_end || ($p_e==$episode_end && $p==$part_ep_2))) $ep .= "-End";
		$xxxx	=	explode("||",$url_clip[$i-1]);
		$link	=	trim($xxxx[0]);
		$subk	=	$xxxx[1];
		$is_ep_end++;
		if ($ep<10) $j= ''.$ep;
		elseif ($ep>9 && $ep<100) $j=''.$ep;
		else $j = $ep;
		if ( $is_full && ( $s==$episode_begin || ($p_e==$episode_begin && $p==1))) $j = $is_full;
?>
<div class="form-group">
                      <label class="col-sm-2 control-label">Tập <input onclick="this.select()" type="text" name="name[<?php echo $is_ep_end;?>]" value="<?php echo $j;?>" size=2 style="text-align:center"></label>
					  <div class="col-sm-10">
					Link: <input type="text" class="form-control rounded" style="width:100%;" name="url[<?=$is_ep_end;?>]" value="<?=trim($link);?>"><br />
					Sub: <input type="text" class="form-control rounded" style="width:100%;" name="sub[<?=$is_ep_end;?>]" value="" /><br />
					
					Server: <?=acp_film_ep($_POST['server_ep_slt'],$is_ep_end);?>
					  </div>
					 
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<?php
	}
}

?>


<input type="hidden" name="episode_begin" value="1">

<input type="hidden" name="episode_end" value="<?php echo $is_ep_end;?>">

<input type="hidden" name="ok" value="SUBMIT">


<div class="form-group">
                      <div class="col-sm-4 col-sm-offset-2">
					  <input type="submit" name="submit" class="btn btn-primary" value="Save changes">
      
                      </div>
                    </div>
<script type="text/javascript">

var total = <?php echo $is_ep_end;?>;

function check_local(id){

    for(i=1;i<=total;i++)

           document.getElementById("local_url["+i+"]").value=id;

}

</script> 

</table>

<?php	} ?>


                  </form>
                </div>
              </section>
<?php

}

else {	
    $new_film = sqlescape($_POST['new_film']);
   
	if ($new_film) {
		/* begin upload images*/
	 $name_real = sqlescape($_POST['name_real']);
    $trang_thai = sqlescape($_POST['trang_thai']);
    $director = sqlescape($_POST['director']);
    $actor = sqlescape($_POST['actor']);
    $area = sqlescape($_POST['area']);
    $year = sqlescape($_POST['year']);
    $film_lang = sqlescape($_POST['film_lang']);
    $time = sqlescape($_POST['time']);
    $tapphim = sqlescape($_POST['tapphim']);
    $imdb = sqlescape($_POST['imdb']);
    $trailer = sqlescape($_POST['trailer']);
    $film_lb = sqlescape($_POST['film_lb']);
    $info = sqlescape($_POST['info']);
	
		$cat=join_value($_POST['selectcat']);
		$country=join_value($_POST['selectcountry']);
		$imgbn = $_POST['url_imgbn'];
		$_SESSION["imgbnn"] = $imgbn;
		$film = acp_quick_add_film2(($new_film),($name_real),$tapphim,($actor),$year,$time,$area,($director),$cat,($info),$country,$file_type,$bo_le,$key,$des,$imgbn,$tag,$trang_thai,$_POST['imdb']);
		$mysql->query("UPDATE ".$tb_prefix."film SET film_date = '".NOW."',film_trailer = '".$trailer."',film_lang = '".$film_lang."',film_lb = '".$film_lb."' WHERE film_id = ".$film."");
	}	

	$t_film = $film;
    $tif_name = '';
    for ($i=$episode_begin;$i<=$episode_end;$i++){
		$is_exit="";
		$t_url = $_POST['url'][$i];
		$t_name = $_POST['name'][$i];
		$t_sub = $_POST['sub'][$i];
	
		// server post	
		$t_serep	= 	$_POST['server_ep'][$i];
		// end
		if ($_POST['check_link']=="on") $is_exit = get_data('episode_id','episode','episode_url',$t_url,1);
		//lech sub
		if (substr_count($t_sub,"phiim.tv")==0 && $t_sub!="")
		{
			$filesub[$i]	=	replace($t_film.'-'.$t_name).'.srt';
			if(copy($t_sub,'../sub/'.$filesub[$i])) {
				$t_sub 	= 	'sub/'.$filesub[$i];
			}
		}
		
		$tif_name .= $t_name.',';
		//lech sub
		if ($t_url && $t_name && $is_exit == "") {
		$mysql->query("INSERT INTO ".$tb_prefix."episode (episode_film,episode_url,episode_urlsub,episode_servertype,episode_name) VALUES ('".$t_film."','".$t_url."','".$t_sub."','".$t_serep."','".$t_name."')");
		$totalepisodes_of_film = get_total('episode','episode_id',"WHERE episode_film = ".$t_film."");
		$mysql->query("UPDATE ".$tb_prefix."film SET film_time_update = '".NOW."',film_newepisode = '".$totalepisodes_of_film."' WHERE film_id = ".$t_film."");
		

		}

	}
	
if($new_film){
	$server_img		=	$_POST['server_img'];
	$server_imgbn		=	$_POST['server_imgbn'];
		if($server_img == 1) {
			$new_film_img = $_POST['url_img'];
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");

		}elseif($server_img == 2) {
		   if($_FILES["phimimg"]['name']!=""){ 
	   $new_film_img	=	ipupload("phimimg","film",replace(get_ascii($name_real)));
	   }elseif($_POST['url_img']){
	   $new_film_img = uploadurl($_POST['url_img'],replace(get_ascii($name_real)),'film');
	  }else{ 
	  $new_film_img = "http://www.phimle.tv/images/playbg.jpg";	}		
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");
				}elseif($server_img == 3){
				$new_film_img =	Picasa_Upload($_POST['url_img'],1);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");
				}elseif($server_img == 4){
				$new_film_img =	Imgur_Upload($_POST['url_img'],1);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");
				}
		if($server_imgbn == 1) {
			$new_film_imgbn = $_SESSION["imgbnn"];
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");

		}elseif($server_imgbn == 2) {
		   if($_FILES["phimimgbn"]['name']!=""){ 
	   $new_film_imgbn	=	ipupload("phimimgbn","info",replace(get_ascii($name_real)));
	   }elseif($_POST['url_imgbn']){
	   $new_film_imgbn = uploadurl($_SESSION["imgbnn"],replace(get_ascii($name_real)),'info');
	  }else{ 
	  $new_film_imgbn = "http://www.phimle.tv/images/playbg.jpg";	}		
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");
				}elseif($server_imgbn == 3){
				$new_film_imgbn =	Picasa_Upload($_SESSION["imgbnn"],2);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");
				}elseif($server_imgbn == 4){
				$new_film_imgbn =	Imgur_Upload($_SESSION["imgbnn"],2);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");
				}			
			
			/* end upload images*/

}
   unset($_SESSION["imgbnn"]);
	echo "Đã thêm xong <meta http-equiv='refresh' content='0;url=?act=episode&mode=multi_add'>";

  }

}

?>