<?php 
$datas = '<div id="header"> 
    <div class="container"> 
	    <div class="row">
		  
			<div class="col-lg-2 col-md-2 col-sm-3 col-xs-6"> <h1 id="logo"><a href="" title="Xem phim">Xem phim</a></h1> </div> 
			<div id="search" class="col-lg-6 col-md-6 col-sm-5 col-xs-6"> 
			    <form method="post" onsubmit="return false;" action="" class="style2" id="form-search">
      				<i class="icon"></i> 
					<input type="text" name="keyword" class="input keyword" placeholder="Tìm kiếm">
					<input type="submit" class="submit" value="">
				</form> 
				<ul class="autocomplete-list"></ul>
			</div>
			<div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">';
		$datas .= Logged();
		$datas .=	'</div> 
		</div> 
	</div>
</div>';
$data_cache_aside = $phpFastCache->get('phimletv-aside');
if($data_cache_aside != null){
	    $data = '<!---Use Cache phimletv-aside---->'.$data_cache_aside.'<!---/End Use Cache phimletv-aside---->'; 
	}else{
	
$data = '<div id="menu"> 
    <div class="container"> 
	    <ul> 
		    <li class="item"> <a href="javascript:;"><i class="icon-cate"></i> '.$language['genres'].'</a> 
			    <ul class="sub"> ';
			 $arr = $mysql->query("SELECT cat_name_key,cat_name FROM ".DATABASE_FX."cat WHERE cat_child = '0' AND cat_type = '0' ORDER BY cat_order ASC");
	while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	$catKEY = $row['cat_name_key'];
	$catURL = $web_link.'/the-loai/'.$catKEY.'/';
	$catNAME = $row['cat_name'];	
				$data .='<li><a href="'.$catURL.'" title="'.$catNAME.'">'.$catNAME.'</a></li> ';
	}  	
			$data .='</ul> </li> <li class="item"> <a href="javascript:;"><i class="icon-earth"></i> '.$language['country'].'</a> <ul class="sub"> ';
	$arr = $mysql->query("SELECT country_name_key,country_name FROM ".DATABASE_FX."country ORDER BY country_order ASC");
	while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	$countryKEY = $row['country_name_key'];
	$countryURL = $web_link.'/quoc-gia/'.$countryKEY.'/';
	$countryNAME = $row['country_name'];		
			$data .= '<li><a href="'.$countryURL.'" title="'.$countryNAME.'">'.$countryNAME.'</a></li>';
} 
			$data .= '</ul> </li> <li class="item"> <a href="'.$web_link.'/phim-le/" title="'.$language['moviesingle'].'"><i class="icon-dice"></i> '.$language['moviesingle'].'</a> <ul class="sub" style="width: 130px;"> ';
		$curYear = date("Y");
    for($i=$curYear;$i>=2012;$i--){
        $yearURL = $web_link.'/phim-le/'.$i.'/';	
			$data .= '<li><a href="'.$yearURL.'" title="Phim lẻ '.$i.'">Phim lẻ '.$i.'</a></li> ';
		} 

			$data .= '</ul> </li> <li class="item"> <a href="'.$web_link.'/phim-bo/" title="'.$language['movieserial'].'"><i class="icon-dice-2"></i> '.$language['movieserial'].'</a> <ul class="sub" style="width: 130px;">';
			
			 for($i=$curYear;$i>=2012;$i--){
        $yearURL = $web_link.'/phim-bo/'.$i.'/';	
			$data .= '<li><a href="'.$yearURL.'" title="Phim bộ '.$i.'">Phim bộ '.$i.'</a></li> ';
		} 

			$data .= '</ul> </li> <li class="item"> <a href="'.$web_link.'/phim-chieu-rap/" title="'.$language['movietheaters'].'"><i class="icon-dglass"></i> '.$language['movietheaters'].'</a> <ul class="sub" style="width: 170px;">'; 
			for($i=$curYear;$i>=2012;$i--){
        $yearURL = $web_link.'/phim-chieu-rap/'.$i.'/';	
			$data .= '<li><a href="'.$yearURL.'" title="Phim chiếu rạp '.$i.'">Phim chiếu rạp '.$i.'</a></li> ';
		} 
		$data .= ' </ul> </li> <li class="item"> <a href="phim-moi/" title="Phim mới"><i class="icon-newfilm"></i> Phim mới</a> <ul class="sub" style="width: 130px;">';

		for($i=$curYear;$i>=2012;$i--){
        $yearURL = $web_link.'/phim-'.$i.'/';	
			$data .= '<li><a href="'.$yearURL.'" title="Phim năm '.$i.'">Phim '.$i.'</a></li> ';
		} 
		$data .= '</ul> </li> <li class="item"> <a href="trailer/" title="Phim sắp chiếu"><i class="fa fa-volume-down"></i> Trailers</a> </li> <li class="item"> <a href="videos.html"><i class="icon-video"></i> Video Clip</a> </li> </ul> </div> </div>';
		if($data != '') $phpFastCache->set('phimletv-aside', $data, 86400);
				}
				echo $datas.$data;