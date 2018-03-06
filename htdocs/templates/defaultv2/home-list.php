<?php 
if($value[1]=='home-list'){
if (in_array($value[2], array('the-loai','quoc-gia','danh-sach','tag','tim-kiem','trailer','dien-vien'))) {
    $page = explode("trang-",URL_LOAD);
	$page = explode(".html",$page[1]);
	$page =	(int)($page[0]);
	$rel = explode("?rel=",URL_LOAD);
	$rel = explode(".html",$rel[1]);
	$rel =	sql_escape(trim($rel[0]));
	if(strpos(URL_LOAD , 'rel=new') !== false || strpos(URL_LOAD , 'rel=popular') !== false || strpos(URL_LOAD , 'rel=year') !== false  || strpos(URL_LOAD , 'rel=name') !== false){
		    if(strpos(URL_LOAD , 'rel=popular') !== false){
			    $order_sql = "ORDER BY film_viewed DESC";
			}elseif(strpos(URL_LOAD , 'rel=new') !== false){
			    $order_sql = "ORDER BY film_id DESC";
			}elseif(strpos(URL_LOAD , 'rel=year') !== false){
			    $order_sql = "ORDER BY film_year DESC";
			}elseif(strpos(URL_LOAD , 'rel=name') !== false){
			    $order_sql = "ORDER BY film_name ASC";
			}
			
		}else{
		    $order_sql = "ORDER BY film_time_update DESC";   
		}
    if ($value[2]=='tim-kiem') {
	
		$kw = strip_tags(urldecode(trim($value[3])));
		$kw = htmlchars(stripslashes(str_replace('+',' ',$kw)));
	    $keyword = htmlchars(stripslashes(urldecode(injection($kw))));
		$keyacsii = strtolower(get_ascii($keyword));
		$kws = str_replace(' ','-',$keyacsii);
		
		if(search_stop_query($keyacsii) == true || dvd_is_stop_query($keyword)){
		$where_sql = "WHERE (film_name_ascii LIKE \"%".$keyacsii."%\" OR film_name LIKE \"%".$keyword."%\" OR film_name_real LIKE \"%".$keyword."%\" OR film_tag LIKE \"%".$keyword."%\" OR film_tag_ascii LIKE \"%".$keyacsii."%\") AND film_publish = 0";
		}else{
		$where_sql = "WHERE (MATCH (film_name,film_name_real,film_name_ascii,film_tag,film_tag_ascii) AGAINST ('".text_preg_replace_search($keyacsii.' '.$keyword)."' IN BOOLEAN MODE)) AND film_publish = 0";
		$order_sql = "ORDER BY film_name LIKE \"%".$keyword."%\" OR film_name_real LIKE \"%".$keyword."%\" DESC";
		}
		
		$web_keywords = 'xem phim '.$keyword.' full hd, phim '.$keyword.' online, phim '.$keyword.' vietsub, phim '.$keyword.' thuyet minh, phim  long tieng, phim '.$keyword.' tap cuoi';
	    $web_des = 'Phim '.$keyword.' hay tuyển tập, phim '.$keyword.' mới nhất, tổng hợp phim '.$keyword.', '.$keyword.' full HD, '.$keyword.' vietsub, xem '.$keyword.' online';
	    $web_title = $keyword.' | Phim '.$keyword.' hay | Tuyển tập '.$keyword.' mới nhất 2015';
		$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['search'].'"><span itemprop="title">'.$language['search'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="#" title="Tìm kiếm phim '.$keyword.'">'.ucfirst($keyword).'</a></li>';
	    $h1title = '<i class="icon-magnifier font-purple-seance"></i>'.$language['search_result'].': '.$keyword;
		$pageURL = $web_link.'/tim-kiem/'.replacesearch($value[3]).'';
		$name = $keyword;
	}elseif($value[2]=='tag'){
	    $kw = strip_tags(urldecode(trim($value[3])));
		$kw = htmlchars(stripslashes(str_replace('-',' ',$kw)));
	    $keyword = htmlchars(stripslashes(urldecode(injection($kw))));
		$keyacsii = strtolower(get_ascii($keyword));
		$kws = str_replace(' ','-',$keyacsii);
		
		$where_sql = "WHERE (film_name_ascii LIKE \"%".$keyacsii."%\" OR film_name LIKE \"%".$keyword."%\" OR film_name_real LIKE \"%".$keyword."%\" OR film_tag LIKE \"%".$keyword."%\" OR film_tag_ascii LIKE \"%".$keyacsii."%\") AND film_publish = 0";
		
		$web_keywords = 'xem phim '.$keyword.' full hd, phim '.$keyword.' online, phim '.$keyword.' vietsub, phim '.$keyword.' thuyet minh, phim  long tieng, phim '.$keyword.' tap cuoi';
	    $web_des = 'Phim '.$keyword.' hay tuyển tập, phim '.$keyword.' mới nhất, tổng hợp phim '.$keyword.', '.$keyword.' full HD, '.$keyword.' vietsub, xem '.$keyword.' online';
	    $web_title = $keyword.' | Phim '.$keyword.' hay | Tuyển tập '.$keyword.' mới nhất 2015';
		$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['tags'].'"><span itemprop="title">'.$language['tags'].' <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="#" title="Từ khóa phim '.$keyword.'">'.ucfirst($keyword).'</a></li>';
	    $h1title = '<i class="icon-tag font-purple-seance"></i>'.$language['tags_result'].': '.$keyword;
		$pageURL = $web_link.'/tag/'.replacetag($value[3]).'';
		$name = $keyword;
	}elseif($value[2]=='dien-vien'){
	    $kw = strip_tags(urldecode(trim($value[3])));
		$kw = htmlchars(stripslashes(str_replace('-',' ',$kw)));
	    $keyword = htmlchars(stripslashes(urldecode(injection($kw))));
		$keyacsii = strtolower(get_ascii($keyword));
		$kws = str_replace(' ','-',$keyacsii);
		
		$where_sql = "WHERE (film_actor LIKE \"%".$keyword."%\" OR film_actor_ascii LIKE \"%".$keyacsii."%\") AND film_publish = 0";
		
		$web_keywords = 'trailer phim, xem phim của '.$keyword.' full hd, phim của '.$keyword.' online, phim của '.$keyword.' vietsub, phim của '.$keyword.' thuyet minh, phim  long tieng, phim của '.$keyword.' tap cuoi';
	    $web_des = 'Phim '.$keyword.' hay nhất 2015';
	    $web_title = 'Phim '.$keyword.' hay nhất 2015';
		$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		
	   $breadcrumbs .= '<li><a itemprop="url" href="#" title="Diễn viên"><span itemprop="title">Diễn viên <i class="fa fa-angle-right"></i></span></a></li>';
	    $breadcrumbs .= '<li><a class="current" href="#" title="'.upperFirstChar($keyword).'">'.upperFirstChar($keyword).'</a></li>';
	    $h1title = '<i class="icon-tag font-purple-seance"></i>Phim '.$keyword.': '.$keyword;
		$pageURL = $web_link.'/dien-vien/'.$value[3].'/';
		$name = $keyword;
	}elseif($value[2]=='trailer'){
	    
		$where_sql = "WHERE film_trailer <> '' AND film_publish = 0 AND film_lb = 3";
		
		$web_keywords = 'trailer phim, xem phim '.$keyword.' full hd, phim '.$keyword.' online, phim '.$keyword.' vietsub, phim '.$keyword.' thuyet minh, phim  long tieng, phim '.$keyword.' tap cuoi';
	    $web_des = 'Trailer phim mới - Trailer phim hay 2015, Trailer phim mới | Trailer phim hay sắp chiếu | Trailer phim bom tấn 2015';
	    $web_title = 'Trailer phim mới | Trailer phim hay sắp chiếu | Trailer phim bom tấn 2015';
		$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		
	    $breadcrumbs .= '<li><a class="current" href="#" title="Trailers phim sắp chiếu">Trailers phim mới</a></li>';
	    $h1title = '<i class="icon-tag font-purple-seance"></i>'.$language['tags_result'].': '.$keyword;
		$pageURL = $web_link.'/trailer';
		$name = $keyword;
	}elseif($value[2]=='danh-sach'){
	    $ipid = explode('/',URL_LOAD);
		if(count($ipid) == 3){
		    $Key1 = sql_escape($ipid[1]);
			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
            if($Key1 == 'phim-le'){
			    $where_sql = "WHERE film_lb = 0";
				$h1title = $language['moviesingle'];
				
			}elseif($Key1 == 'phim-bo'){
			    $where_sql = "WHERE film_lb IN (1,2)";
				$h1title = $language['movieserial'];
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql = "WHERE film_chieurap = 1";
				$h1title = $language['movietheaters'];
			}elseif($Key1 == 'phim-hot'){
			    $where_sql = "WHERE film_hot = 1";
				$h1title = $language['movieshot'];
			}elseif($Key1 == 'phim-moi'){
			    $where_sql = "WHERE film_publish = 0";
				$h1title = $language['movienew'];
			}elseif($Key1 == 'phim-18'){
			    $where_sql = "WHERE film_phim18 = 1";
				$h1title = $language['movie18'];
			}elseif(is_numeric($Year)){
			    $where_sql = "WHERE film_year = ".$Year;
				$h1title = 'Phim năm '.$Year;
				$YearKey = $Year;
			}else header('Location: '.$web_link.'/404');
			$TypeKey = $Key1;
			$keyword = $h1title;
			$web_keywords = 'xem phim '.$keyword.' full hd, phim '.$keyword.' online, phim '.$keyword.' vietsub, phim '.$keyword.' thuyet minh, phim  long tieng, phim '.$keyword.' tap cuoi';
	        $web_des = 'Phim '.$keyword.' hay tuyển tập, phim '.$keyword.' mới nhất, tổng hợp phim '.$keyword.', '.$keyword.' full HD, '.$keyword.' vietsub, xem '.$keyword.' online';
	        $web_title = $keyword.' | '.$keyword.' hay | Tuyển tập '.$keyword.' mới nhất 2015';
            $breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
            $breadcrumbs .= '<li><a class="current" href="'.$web_link.'/'.$Key1.'/" title="'.$h1title.'">'.$h1title.'</a></li>';			
		    $pageURL = $web_link.'/'.$Key1;
			$name = $Key1;
		}elseif(count($ipid) == 4){
		    $Key1 = sql_escape($ipid[1]);
		    $Key2 = sql_escape($ipid[2]);
		    
			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
			// Check $Key1
            if($Key1 == 'phim-le'){
			    $where_sql1 = "WHERE film_lb = 0";
				$h1title1 = $language['moviesingle'];
			}elseif($Key1 == 'phim-bo'){
			    $where_sql1 = "WHERE film_lb IN (1,2)";
				$h1title1 = $language['movieserial'];
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql1 = "WHERE film_chieurap = 1";
				$h1title1 = $language['movietheaters'];
			}elseif($Key1 == 'phim-hot'){
			    $where_sql1 = "WHERE film_hot = 1";
				$h1title1 = $language['movieshot'];
			}elseif($Key1 == 'phim-18'){
			    $where_sql1 = "WHERE film_phim18 = 1";
				$h1title1 = $language['movie18'];
			}elseif($Key1 == 'phim-moi'){
			    $where_sql1 = "WHERE film_publish = 0";
				$h1title1 = $language['movienew'];
			}elseif(is_numeric($Year)){
			    $where_sql1 = "WHERE film_year = ".$Year;
				$h1title1 = 'Phim năm '.$Year;
				$YearKey = $Year;
			}else header('Location: '.$web_link.'/404');
			$TypeKey = $Key1;
			// Check $Key2
			$CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			$CountryID = get_data('country_id','country','country_name_key',$Key2);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key2);
			    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title2 = $CatNAME;
				$CatKey = $Key2;
				
			}elseif($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key2);
			    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title2 = $CountryNAME;
				$CountryKey = $Key2;
			}elseif(is_numeric($Key2)){
			    $where_sql2 = " AND film_year = ".$Key2;
				$h1title2 = 'Năm '.$Key2;
				$YearKey = $Key2;
			}else header('Location: '.$web_link.'/404');
			$h1title = $h1title1.' '.$h1title2;
			$where_sql = $where_sql1.$where_sql2;
			$keyword = $h1title;
			$web_keywords = 'xem phim '.$keyword.' full hd, phim '.$keyword.' online, phim '.$keyword.' vietsub, phim '.$keyword.' thuyet minh, phim  long tieng, phim '.$keyword.' tap cuoi';
	        $web_des = 'Phim '.$keyword.' hay tuyển tập, phim '.$keyword.' mới nhất, tổng hợp phim '.$keyword.', '.$keyword.' full HD, '.$keyword.' vietsub, xem '.$keyword.' online';
	        $web_title = $keyword.' | '.$keyword.' hay | Tuyển tập '.$keyword.' mới nhất 2015';
            $breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
            $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/'.$Key1.'/" title="'.$h1title1.'"><span itemprop="title">'.$h1title1.' <i class="fa fa-angle-right"></i></span></a></li>';
			$breadcrumbs .= '<li><a class="current" href="'.$web_link.'/'.$Key2.'/" title="Phim '.$h1title2.'">'.$h1title2.'</a></li>';			
		    $pageURL = $web_link.'/'.$Key1.'/'.$Key2;
			$name = $Key1.'|'.$Key2;
		}elseif(count($ipid) == 5){
		    $Key1 = sql_escape($ipid[1]);
		    $Key2 = sql_escape($ipid[2]);
		    $Key3 = sql_escape($ipid[3]);

			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
			// Check $Key1
            if($Key1 == 'phim-le'){
			    $where_sql1 = "WHERE film_lb = 0";
				$h1title1 = $language['moviesingle'];
			}elseif($Key1 == 'phim-bo'){
			    $where_sql1 = "WHERE film_lb IN (1,2)";
				$h1title1 = $language['movieserial'];
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql1 = "WHERE film_chieurap = 1";
				$h1title1 = $language['movietheaters'];
			}elseif($Key1 == 'phim-hot'){
			    $where_sql1 = "WHERE film_hot = 1";
				$h1title1 = $language['movieshot'];
			}elseif($Key1 == 'phim-18'){
			    $where_sql1 = "WHERE film_phim18 = 1";
				$h1title1 = $language['movie18'];
			}elseif($Key1 == 'phim-moi'){
			    $where_sql1 = "WHERE film_publish = 0";
				$h1title1 = $language['movienew'];
			}elseif(is_numeric($Year)){
			    $where_sql1 = "WHERE film_year = ".$Year;
				$h1title1 = 'Phim năm '.$Year;
				$YearKey = $Year;
			}else header('Location: '.$web_link.'/404');
			$TypeKey = $Key1;
			// Check $Key2
			$CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			$CountryID = get_data('country_id','country','country_name_key',$Key2);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key2);
			    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title2 = $CatNAME;
				$Key2URL = $web_link.'/the-loai/'.$Key2.'/';
				$CatKey = $Key2;
			}elseif($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key2);
			    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title2 = $CountryNAME;
				$Key2URL = $web_link.'/quoc-gia/'.$Key2.'/';
				$CountryKey = $Key2;
			}elseif(is_numeric($Key2)){
			    $where_sql2 = " AND film_year = ".$Key2;
				$h1title2 = 'Năm '.$Key2;
				$Key2URL = $web_link.'/phim-'.$Key2.'/';
				$YearKey = $Key2;
			}else header('Location: '.$web_link.'/404');
			// Check $Key3
			$CatID = get_data('cat_id','cat','cat_name_key',$Key3);
			$CountryID = get_data('country_id','country','country_name_key',$Key3);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key3);
			    $where_sql3 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title3 = $CatNAME;
				$Key3URL = $web_link.'/the-loai/'.$Key3.'/';
				$CatKey = $Key3;
			}elseif($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key3);
			    $where_sql3 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title3 = $CountryNAME;
				$Key3URL = $web_link.'/quoc-gia/'.$Key3.'/';
				$CountryKey = $Key3;
			}elseif(is_numeric($Key3)){
			    $where_sql3 = " AND film_year = ".$Key3;
				$h1title3 = 'Năm '.$Key3;
				$Key3URL = $web_link.'/phim-'.$Key3.'/';
				$YearKey = $Key3;
			}else header('Location: '.$web_link.'/404');
			$h1title = $h1title1.' '.$h1title2.' '.$h1title3;
			$where_sql = $where_sql1.$where_sql2.$where_sql3;
			$keyword = $h1title;
			$web_keywords = 'xem phim '.$keyword.' full hd, phim '.$keyword.' online, phim '.$keyword.' vietsub, phim '.$keyword.' thuyet minh, phim  long tieng, phim '.$keyword.' tap cuoi';
	        $web_des = 'Phim '.$keyword.' hay tuyển tập, phim '.$keyword.' mới nhất, tổng hợp phim '.$keyword.', '.$keyword.' full HD, '.$keyword.' vietsub, xem '.$keyword.' online';
	        $web_title = $keyword.' | '.$keyword.' hay | Tuyển tập '.$keyword.' mới nhất 2015';
            $breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
            $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/'.$Key1.'/" title="'.$h1title1.'"><span itemprop="title">'.$h1title1.' <i class="fa fa-angle-right"></i></span></a></li>';
            $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$Key2URL.'" title="'.$h1title2.'"><span itemprop="title">'.$h1title2.' <i class="fa fa-angle-right"></i></span></a></li>';
			$breadcrumbs .= '<li><a class="current" href="'.$Key3URL.'" title="Phim '.$h1title3.'">'.$h1title3.'</a></li>';			
		    $pageURL = $web_link.'/'.$Key1.'/'.$Key2.'/'.$Key3;
			$name = $Key1.'|'.$Key2.'|'.$Key3;
		}elseif(count($ipid) == 6){
		    $Key1 = sql_escape($ipid[1]);
		    $Key2 = sql_escape($ipid[2]);
		    $Key3 = sql_escape($ipid[3]);
		    $Key4 = sql_escape($ipid[4]);

			$Year = explode('phim-',$Key1);
			$Year = (int)$Year[1];
			// Check $Key1
            if($Key1 == 'phim-le'){
			    $where_sql1 = "WHERE film_lb = 0";
				$h1title1 = $language['moviesingle'];
			}elseif($Key1 == 'phim-bo'){
			    $where_sql1 = "WHERE film_lb IN (1,2)";
				$h1title1 = $language['movieserial'];
			}elseif($Key1 == 'phim-chieu-rap'){
			    $where_sql1 = "WHERE film_chieurap = 1";
				$h1title1 = $language['movietheaters'];
			}elseif($Key1 == 'phim-hot'){
			    $where_sql1 = "WHERE film_hot = 1";
				$h1title1 = $language['movieshot'];
			}elseif($Key1 == 'phim-18'){
			    $where_sql1 = "WHERE film_phim18 = 1";
				$h1title1 = $language['movie18'];
			}elseif($Key1 == 'phim-moi'){
			    $where_sql1 = "WHERE film_publish = 0";
				$h1title1 = $language['movienew'];
			}elseif(is_numeric($Year)){
			    $where_sql1 = "WHERE film_year = ".$Year;
				$h1title1 = 'Phim năm '.$Year;
				$YearKey = $Year;
			}else header('Location: '.$web_link.'/404');
			$TypeKey = $Key1;
			// Check $Key2
			$CatID = get_data('cat_id','cat','cat_name_key',$Key2);
			$CountryID = get_data('country_id','country','country_name_key',$Key2);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key2);
			    $where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title2 = $CatNAME;
				$Key2URL = $web_link.'/the-loai/'.$Key2.'/';
				$CatKey = $Key2;
			}elseif($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key2);
			    $where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title2 = $CountryNAME;
				$Key2URL = $web_link.'/quoc-gia/'.$Key2.'/';
				$CountryKey = $Key2;
			}elseif(is_numeric($Key2)){
			    $where_sql2 = " AND film_year = ".$Key2;
				$h1title2 = 'Năm '.$Key2;
				$Key2URL = $web_link.'/phim-'.$Key2.'/';
				$YearKey = $Key2;
			}else header('Location: '.$web_link.'/404');
			// Check $Key3
			$CatID = get_data('cat_id','cat','cat_name_key',$Key3);
			$CountryID = get_data('country_id','country','country_name_key',$Key3);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key3);
			    $where_sql3 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title3 = $CatNAME;
				$Key3URL = $web_link.'/the-loai/'.$Key3.'/';
				$CatKey = $Key3;
			}elseif($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key3);
			    $where_sql3 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title3 = $CountryNAME;
				$Key3URL = $web_link.'/quoc-gia/'.$Key3.'/';
				$CountryKey = $Key3;
			}elseif(is_numeric($Key3)){
			    $where_sql3 = " AND film_year = ".$Key3;
				$h1title3 = 'Năm '.$Key3;
				$Key3URL = $web_link.'/phim-'.$Key3.'/';
				$YearKey = $Key3;
			}else header('Location: '.$web_link.'/404');
			// Check $Key4
			$CatID = get_data('cat_id','cat','cat_name_key',$Key4);
			$CountryID = get_data('country_id','country','country_name_key',$Key4);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key4);
			    $where_sql4 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title4 = $CatNAME;
				$Key4URL = $web_link.'/the-loai/'.$Key4.'/';
				$CatKey = $Key4;
			}elseif($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key4);
			    $where_sql4 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title4 = $CountryNAME;
				$Key4URL = $web_link.'/quoc-gia/'.$Key4.'/';
				$CountryKey = $Key4;
			}elseif(is_numeric($Key4)){
			    $where_sql4 = " AND film_year = ".$Key4;
				$h1title4 = 'Năm '.$Key4;
				$Key4URL = $web_link.'/phim-'.$Key4.'/';
				$YearKey = $Key4;
			}else header('Location: '.$web_link.'/404');
			$h1title = $h1title1.' '.$h1title2.' '.$h1title3.' '.$h1title4;
			$where_sql = $where_sql1.$where_sql2.$where_sql3.$where_sql4;
			$keyword = $h1title;
			$web_keywords = 'xem phim '.$keyword.' full hd, phim '.$keyword.' online, phim '.$keyword.' vietsub, phim '.$keyword.' thuyet minh, phim  long tieng, phim '.$keyword.' tap cuoi';
	        $web_des = 'Phim '.$keyword.' hay tuyển tập, phim '.$keyword.' mới nhất, tổng hợp phim '.$keyword.', '.$keyword.' full HD, '.$keyword.' vietsub, xem '.$keyword.' online';
	        $web_title = $keyword.' | '.$keyword.' hay | Tuyển tập '.$keyword.' mới nhất 2015';
            $breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
            $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/'.$Key1.'/" title="'.$h1title1.'"><span itemprop="title">'.$h1title1.' <i class="fa fa-angle-right"></i></span></a></li>';
            $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$Key2URL.'" title="'.$h1title2.'"><span itemprop="title">'.$h1title2.' <i class="fa fa-angle-right"></i></span></a></li>';
            $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$Key3URL.'" title="'.$h1title3.'"><span itemprop="title">'.$h1title3.' <i class="fa fa-angle-right"></i></span></a></li>';
			$breadcrumbs .= '<li><a class="current" href="'.$Key4URL.'" title="Phim '.$h1title4.'">'.$h1title4.'</a></li>';			
		    $pageURL = $web_link.'/'.$Key1.'/'.$Key2.'/'.$Key3.'/'.$Key4;
			$name = $Key1.'|'.$Key2.'|'.$Key3.'|'.$Key4;
		}
	    $relTYPE = $Key1;

	}elseif($value[2]=='quoc-gia'){
	    $ipid = explode('/',URL_LOAD);
		if(count($ipid) == 4){
		    $CountryKey = sql_escape($ipid[2]);
			$CountryID = get_data('country_id','country','country_name_key',$CountryKey);
			
			if($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$CountryKey);
			    $where_sql = "WHERE film_country LIKE '%,".$CountryID.",%'";
				$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		        $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['country'].'"><span itemprop="title">'.$language['country'].' <i class="fa fa-angle-right"></i></span></a></li>';
	            $breadcrumbs .= '<li><a class="current" href="'.$web_link.'/quoc-gia/'.$CountryKey.'/" title="Phim '.$CountryNAME.'">'.$CountryNAME.'</a></li>';
				$h1title = 'Phim '.$CountryNAME;
				$pageURL = $web_link.'/quoc-gia/'.$CountryKey;
				$web_keywords = 'xem phim '.$CountryNAME.' full hd, phim '.$CountryNAME.' online, phim '.$CountryNAME.' vietsub, phim '.$CountryNAME.' thuyet minh, phim  long tieng, phim '.$CountryNAME.' tap cuoi';
	            $web_des = 'Phim '.$CountryNAME.' hay tuyển tập, phim '.$CountryNAME.' mới nhất, tổng hợp phim '.$CountryNAME.', '.$CountryNAME.' full HD, '.$CountryNAME.' vietsub, xem '.$CountryNAME.' online';
	            $web_title = 'Phim '.$CountryNAME.' hay | Phim '.$CountryNAME.' mới | Tuyển tập phim '.$CountryNAME.' mới nhất 2015';
			    $name = 'quoc-gia|'.$CountryID;
			}else header('Location: '.$web_link.'/404');
			
		}elseif(count($ipid) == 5){
		    $CountryKey = sql_escape($ipid[2]);
			$Key3 = sql_escape($ipid[3]);
			$CountryID = get_data('country_id','country','country_name_key',$CountryKey);
			if($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$CountryKey);
			if(is_numeric($Key3)){
			    $where_sql1 = " AND film_year = ".$Key3;
				$h1title1 = 'Phim năm '.$Key3;
				$h1title2 = 'Năm '.$Key3;
				$Key3URL = $web_link.'/phim-'.$Key3.'/';
				$YearKey = $Key3;
			}else{
			    $CatID = get_data('cat_id','cat','cat_name_key',$Key3);
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key3);
				$where_sql1 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title1 = 'Phim '.$CatNAME;
				$h1title2 = $CatNAME;
				$Key3URL = $web_link.'/the-loai/'.$Key3.'/';
				$CatKey = $Key3;
			}
			    $where_sql = "WHERE film_country LIKE '%,".$CountryID.",%'".$where_sql1;
				$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		        $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['country'].'"><span itemprop="title">'.$language['country'].' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/quoc-gia/'.$CountryKey.'/" title="'.$CountryNAME.'"><span itemprop="title">'.$CountryNAME.' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li><a class="current" href="'.$Key3URL.'" title="Phim '.$h1title2.'">'.$h1title2.'</a></li>';
				$h1title = 'Phim '.$CountryNAME.' '.$h1title2;
				$pageURL = $web_link.'/quoc-gia/'.$CountryKey.'/'.$Key3;
				$web_keywords = 'xem phim '.$CatNAME.' '.$h1title2.' full hd, phim '.$CountryNAME.' '.$h1title2.' online, phim '.$CountryNAME.' '.$h1title2.' vietsub, phim '.$CountryNAME.' '.$h1title2.' thuyet minh, phim  long tieng, phim '.$CountryNAME.' '.$h1title2.' tap cuoi';
	            $web_des = 'Phim '.$CountryNAME.' hay tuyển tập, phim '.$CountryNAME.' '.$h1title2.' mới nhất, tổng hợp phim '.$CountryNAME.' '.$h1title2.', '.$CountryNAME.' '.$h1title2.' full HD, '.$CountryNAME.' '.$h1title2.' vietsub, xem '.$CountryNAME.' '.$h1title2.' online';
	            $web_title = 'Phim '.$CountryNAME.' '.$h1title2.' hay | Phim '.$CountryNAME.' '.$h1title2.' mới | Tuyển tập phim '.$CountryNAME.' '.$h1title2.' mới nhất 2015';
			    $name = 'quoc-gia|'.$CountryID.'|'.$Key3;
			}else header('Location: '.$web_link.'/404');
			    
		}elseif(count($ipid) == 6){
		    $CountryKey = sql_escape($ipid[2]);
			$Key3 = sql_escape($ipid[3]);
			$Key4 = sql_escape($ipid[4]);
			$CountryID = get_data('country_id','country','country_name_key',$CountryKey);
			if($CountryID){
			    $CountryNAME = get_data('country_name','country','country_name_key',$CountryKey);
			if(is_numeric($Key3)){
			    $where_sql1 = " AND film_year = ".$Key3;
				$h1title1 = 'Phim năm '.$Key3;
				$h1title2 = 'Năm '.$Key3;
				$Key3URL = $web_link.'/phim-'.$Key3.'/';
				$YearKey = $Key3;
			}else{
			    $CatID = get_data('cat_id','cat','cat_name_key',$Key3);
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key3);
				$where_sql1 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title1 = 'Phim '.$CatNAME;
				$h1title2 = $CatNAME;
				$Key3URL = $web_link.'/the-loai/'.$Key3.'/';
				$CatKey = $Key3;
			}
			
			if(is_numeric($Key4)){
			    $where_sql2 = " AND film_year = ".$Key4;
				$h1title3 = 'Phim năm '.$Key4;
				$h1title4 = 'Năm '.$Key4;
				$Key4URL = $web_link.'/phim-'.$Key4.'/';
				$YearKey = $Key4;
			}else{
			    $CatID = get_data('cat_id','cat','cat_name_key',$Key4);
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$Key4);
				$where_sql2 = " AND film_cat LIKE '%,".$CatID.",%'";
				$h1title3 = 'Phim '.$CatNAME;
				$h1title4 = $CatNAME;
				$Key4URL = $web_link.'/the-loai/'.$Key4.'/';
				$CatKey = $Key4;
			}
			    $where_sql = "WHERE film_country LIKE '%,".$CountryID.",%'".$where_sql1.$where_sql2;
				$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		        $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['country'].'"><span itemprop="title">'.$language['country'].' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/quoc-gia/'.$CountryKey.'/" title="'.$CountryNAME.'"><span itemprop="title">'.$CountryNAME.' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$Key3URL.'" title="Phim '.$h1title2.'"><span itemprop="title">'.$h1title2.' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li><a class="current" href="'.$Key4URL.'" title="Phim '.$h1title4.'">'.$h1title4.'</a></li>';
				$h1title = 'Phim '.$CountryNAME.' '.$h1title2.' '.$h1title4;
				$pageURL = $web_link.'/quoc-gia/'.$CountryKey.'/'.$Key3.'/'.$Key4;
				$web_keywords = 'xem phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.' full hd, phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.' online, phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.' vietsub, phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.'  thuyet minh, phim  long tieng, phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.' tap cuoi';
	            $web_des = 'Phim '.$CountryNAME.' hay tuyển tập, phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.' mới nhất, tổng hợp phim '.$CountryNAME.' '.$h1title2.', '.$CountryNAME.' '.$h1title2.' '.$h1title4.' full HD, '.$CountryNAME.' '.$h1title2.' '.$h1title4.' vietsub, xem '.$CountryNAME.' '.$h1title2.' '.$h1title4.' online';
	            $web_title = 'Phim '.$CountryNAME.' '.$h1title2.' hay | Phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.' mới | Tuyển tập phim '.$CountryNAME.' '.$h1title2.' '.$h1title4.' mới nhất 2015';
			    $name = 'quoc-gia|'.$CountryID.'|'.$Key3.'|'.$Key4;
				
			}else header('Location: '.$web_link.'/404');
		}
		
	}elseif($value[2]=='the-loai'){
	    $ipid = explode('/',URL_LOAD);
		if(count($ipid) == 4){
		    $CatKey = sql_escape($ipid[2]);
			$CatID = get_data('cat_id','cat','cat_name_key',$CatKey);
			
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$CatKey);
			    $where_sql = "WHERE film_cat LIKE '%,".$CatID.",%'";
				$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		        $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['genres'].'"><span itemprop="title">'.$language['genres'].' <i class="fa fa-angle-right"></i></span></a></li>';
	            $breadcrumbs .= '<li><a class="current" href="'.$web_link.'/the-loai/'.$CatKey.'/" title="Phim '.$CatNAME.'">'.$CatNAME.'</a></li>';
				$h1title = 'Phim '.$CatNAME;
				$pageURL = $web_link.'/the-loai/'.$CatKey;
				$web_keywords = 'xem phim '.$CatNAME.' full hd, phim '.$CatNAME.' online, phim '.$CatNAME.' vietsub, phim '.$CatNAME.' thuyet minh, phim  long tieng, phim '.$CatNAME.' tap cuoi';
	            $web_des = 'Phim '.$CatNAME.' hay tuyển tập, phim '.$CatNAME.' mới nhất, tổng hợp phim '.$CatNAME.', '.$CatNAME.' full HD, '.$CatNAME.' vietsub, xem '.$CatNAME.' online';
	            $web_title = 'Phim '.$CatNAME.' hay | Phim '.$CatNAME.' mới | Tuyển tập phim '.$CatNAME.' mới nhất 2015';
			    $name = 'the-loai|'.$CatID;
			}else header('Location: '.$web_link.'/404');
			
		}elseif(count($ipid) == 5){
		    $CatKey = sql_escape($ipid[2]);
			$Key3 = sql_escape($ipid[3]);
			$CatID = get_data('cat_id','cat','cat_name_key',$CatKey);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$CatKey);
			if(is_numeric($Key3)){
			    $where_sql1 = " AND film_year = ".$Key3;
				$h1title1 = 'Phim năm '.$Key3;
				$h1title2 = 'Năm '.$Key3;
				$Key3URL = $web_link.'/phim-'.$Key3.'/';
				$YearKey = $Key3;
			}else{
			    $CountryID = get_data('country_id','country','country_name_key',$Key3);
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key3);
				$where_sql1 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title1 = 'Phim '.$CountryNAME;
				$h1title2 = $CountryNAME;
				$Key3URL = $web_link.'/quoc-gia/'.$Key3.'/';
				$CountryKey = $Key3;
			}
			    $where_sql = "WHERE film_cat LIKE '%,".$CatID.",%'".$where_sql1;
				$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		        $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['genres'].'"><span itemprop="title">'.$language['genres'].' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/the-loai/'.$CatKey.'/" title="'.$CatNAME.'"><span itemprop="title">'.$CatNAME.' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li><a class="current" href="'.$Key3URL.'" title="Phim '.$h1title2.'">'.$h1title2.'</a></li>';
				$h1title = 'Phim '.$CatNAME.' '.$h1title2;
				$pageURL = $web_link.'/the-loai/'.$CatKey.'/'.$Key3;
				$web_keywords = 'xem phim '.$CatNAME.' '.$h1title2.' full hd, phim '.$CatNAME.' '.$h1title2.' online, phim '.$CatNAME.' '.$h1title2.' vietsub, phim '.$CatNAME.' '.$h1title2.' thuyet minh, phim  long tieng, phim '.$CatNAME.' '.$h1title2.' tap cuoi';
	            $web_des = 'Phim '.$CatNAME.' hay tuyển tập, phim '.$CatNAME.' '.$h1title2.' mới nhất, tổng hợp phim '.$CatNAME.' '.$h1title2.', '.$CatNAME.' '.$h1title2.' full HD, '.$CatNAME.' '.$h1title2.' vietsub, xem '.$CatNAME.' '.$h1title2.' online';
	            $web_title = 'Phim '.$CatNAME.' '.$h1title2.' hay | Phim '.$CatNAME.' '.$h1title2.' mới | Tuyển tập phim '.$CatNAME.' '.$h1title2.' mới nhất 2015';
			    $name = 'the-loai|'.$CatID.'|'.$Key3;
			}else header('Location: '.$web_link.'/404');
		}elseif(count($ipid) == 6){
		    $CatKey = sql_escape($ipid[2]);
			$Key3 = sql_escape($ipid[3]);
			$Key4 = sql_escape($ipid[4]);
			$CatID = get_data('cat_id','cat','cat_name_key',$CatKey);
			if($CatID){
			    $CatNAME = get_data('cat_name','cat','cat_name_key',$CatKey);
			if(is_numeric($Key3)){
			    $Key3 = (int)$Key3;
			    $where_sql1 = " AND film_year = ".$Key3;
				$h1title1 = 'Phim năm '.$Key3;
				$h1title2 = 'Năm '.$Key3;
				$Key3URL = $web_link.'/phim-'.$Key3.'/';
			}else{
			    $CountryID = get_data('country_id','country','country_name_key',$Key3);
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key3);
				$where_sql1 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title1 = 'Phim '.$CountryNAME;
				$h1title2 = $CountryNAME;
				$Key3URL = $web_link.'/quoc-gia/'.$Key3.'/';
				$CountryKey = $Key3;
			}
			
			if(is_numeric($Key4)){
			    $Key4 = (int)$Key4;
			    $where_sql2 = " AND film_year = ".$Key4;
				$h1title3 = 'Phim năm '.$Key4;
				$h1title4 = 'Năm '.$Key4;
				$Key4URL = $web_link.'/phim-'.$Key4.'/';
				$YearKey = $Key4;
			}else{
			    $CountryID = get_data('country_id','country','country_name_key',$Key4);
			    $CountryNAME = get_data('country_name','country','country_name_key',$Key4);
				$where_sql2 = " AND film_country LIKE '%,".$CountryID.",%'";
				$h1title3 = 'Phim '.$CountryNAME;
				$h1title4 = $CountryNAME;
				$Key4URL = $web_link.'/quoc-gia/'.$Key4.'/';
				$CountryKey = $Key4;
			}
			    $where_sql = "WHERE film_cat LIKE '%,".$CatID.",%'".$where_sql1.$where_sql2;
				$breadcrumbs = '<li><a itemprop="url" href="/" title="'.$language['home'].'"><span itemprop="title"><i class="fa fa-home"></i> '.$language['home'].' <i class="fa fa-angle-right"></i></span></a></li>';
		        $breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="#" title="'.$language['genres'].'"><span itemprop="title">'.$language['genres'].' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$web_link.'/the-loai/'.$CatKey.'/" title="'.$CatNAME.'"><span itemprop="title">'.$CatNAME.' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><a itemprop="url" href="'.$Key3URL.'" title="Phim '.$h1title2.'"><span itemprop="title">'.$h1title2.' <i class="fa fa-angle-right"></i></span></a></li>';
				$breadcrumbs .= '<li><a class="current" href="'.$Key4URL.'" title="Phim '.$h1title4.'">'.$h1title4.'</a></li>';
				$h1title = 'Phim '.$CatNAME.' '.$h1title2.' '.$h1title4;
				$pageURL = $web_link.'/the-loai/'.$CatKey.'/'.$Key3.'/'.$Key4;
				$web_keywords = 'xem phim '.$CatNAME.' '.$h1title2.' '.$h1title4.' full hd, phim '.$CatNAME.' '.$h1title2.' '.$h1title4.' online, phim '.$CatNAME.' '.$h1title2.' '.$h1title4.' vietsub, phim '.$CatNAME.' '.$h1title2.' '.$h1title4.'  thuyet minh, phim  long tieng, phim '.$CatNAME.' '.$h1title2.' '.$h1title4.' tap cuoi';
	            $web_des = 'Phim '.$CatNAME.' hay tuyển tập, phim '.$CatNAME.' '.$h1title2.' '.$h1title4.' mới nhất, tổng hợp phim '.$CatNAME.' '.$h1title2.', '.$CatNAME.' '.$h1title2.' '.$h1title4.' full HD, '.$CatNAME.' '.$h1title2.' '.$h1title4.' vietsub, xem '.$CatNAME.' '.$h1title2.' '.$h1title4.' online';
	            $web_title = 'Phim '.$CatNAME.' '.$h1title2.' hay | Phim '.$CatNAME.' '.$h1title2.' '.$h1title4.' mới | Tuyển tập phim '.$CatNAME.' '.$h1title2.' '.$h1title4.' mới nhất 2015';
			    $name = 'the-loai|'.$CatID.'|'.$Key3.'|'.$Key4;
			}else header('Location: '.$web_link.'/404');
		}
		
	}
	$relCAT = $CatKey;
	$relCOUNTRY = $CountryKey;
	$relYEAR = $YearKey;
	$relTYPE = $TypeKey;
	$page_size = PAGE_SIZE;
	if (!$page) $page = 1;
	$limit = ($page-1)*$page_size;
    $q = $mysql->query("SELECT * FROM ".DATABASE_FX."film $where_sql $order_sql LIMIT ".$limit.",".$page_size);
	$total = get_total("film","film_id","$where_sql $order_sql");
	$ViewPage = view_pages('film',$total,$page_size,$page,$pageURL,$rel,"defaultv2");
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta http-equiv="content-language" content="vi" />
<title><?=$web_title;?></title>
<meta name="description" content="<?=$web_des;?>"/>
<meta name="keywords" content="<?=$web_keywords;?>"/>
<meta property="og:site_name" content="<?=$web_title;?>"/>
<? require_once("styles.php");?>
<? if($relTYPE == 'phim-18' || $relCAT == 'adult'){ ?><script data-cfasync="false" type="text/javascript" src="http://www.pureadexchange.com/a/display.php?r=1016509"></script><? } ?>
</head>

<body>
    
   <? require_once("header.php");?>
    <div id="body-wrapper">
        <div class="ad_location container desktop hidden-sm hidden-xs" style="padding-top: 0px; margin-bottom: 15px;">
            
        </div>
        <div class="ad_location container mobile hidden-lg hidden-md" style="padding-top: 0px; margin-bottom: 15px;">
           
        </div>
        <div class="content-wrapper">
            <div class="container fit">
 <div class="block-title breadcrumb"> <?=$breadcrumbs;?> </div>
                <div class="main col-lg-8 col-md-8 col-sm-7">
                    <div class="block update">
                       
						<h1 class="hidden"><?=$h1title;?></h1>
						<h2 class="hidden"><?=$web_title;?></h2>
                        <form class="filters" method="get" action="">
						    <div class="dropdown">
                                <select class="form-control selectpicker" id="filter-eptype" name="types">
                                    <option value="">Hình thức</option>
									
                                    <option value="phim-moi" <? if($relTYPE == 'phim-moi'){?>selected<?}?>>Phim mới</option>
                                    <option value="phim-le" <? if($relTYPE == 'phim-le'){?>selected<?}?>>Phim lẻ</option>
                                    <option value="phim-bo" <? if($relTYPE == 'phim-bo'){?>selected<?}?>>Phim bộ</option>
                                    <option value="phim-chieu-rap" <? if($relTYPE == 'phim-chieu-rap'){?>selected<?}?>>Phim rạp</option>
                                    <option value="phim-hot" <? if($relTYPE == 'phim-hot'){?>selected<?}?>>Phim hot</option>
                                    <option value="phim-18" <? if($relTYPE == 'phim-18'){?>selected<?}?>>Phim 18+</option>
                                   	 
                                </select>
                            </div>
                            <div class="dropdown">
                                <select class="form-control selectpicker" id="filter-category" name="category_id">
                                    <option value="">Thể loại</option>
									<?php 
            $arr = $mysql->query("SELECT cat_id,cat_name_key,cat_name FROM ".DATABASE_FX."cat WHERE cat_child = '0' AND cat_type = '0' ORDER BY cat_order ASC");
	        while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	            $catKEY = $row['cat_name_key'];
	            $catID = $row['cat_id'];
	            $catNAME = $row['cat_name'];
				if($catKEY == $relCAT) $select = "selected"; else $select = "";
        ?>
                                    <option value="<?=$catKEY;?>" <?=$select;?>><?=$catNAME;?></option>
                                    <?	}  ?>	 
                                </select>
                            </div>
                            <div class="dropdown">
                                <select class="form-control selectpicker" id="filter-country" name="country_id">
                                    <option value="">Quốc gia</option>
									<?php 
            $arr = $mysql->query("SELECT country_name_key,country_name,country_id FROM ".DATABASE_FX."country ORDER BY country_order ASC");
	        while($row = $arr->fetch(PDO::FETCH_ASSOC)){
	            $countryKEY = $row['country_name_key'];
	            $countryURL = $web_link.'/quoc-gia/'.$countryKEY.'/';
	            $countryNAME = $row['country_name'];
				if($countryKEY == $relCOUNTRY) $select = "selected"; else $select = "";
        ?>
                                    <option value="<?=$countryKEY;?>" <?=$select;?>><?=$countryNAME;?></option>
                                   <?	}  ?>	
                                </select>
                            </div>
                            <div class="dropdown">
                                <select class="form-control selectpicker" id="filter-year" name="year">
                                    <option value="">Năm</option>
									<?php 
            $curYear = date("Y");
            for($i=$curYear;$i>=2009;$i--){
			if($i == $relYEAR) $select = "selected"; else $select = "";
        ?>
                                    <option value="<?=$i;?>" <?=$select;?>><?=$i;?></option>
                                     <?	}  ?>
                                </select>
                            </div>
                            <div class="dropdown">
                                <select class="form-control selectpicker" id="filter-sort" name="order_by">
                                    <option value="">Sắp xếp</option>
                                    <option value="popular">Lượt xem</option>
                                    <option value="year">Năm</option>
                                    <option value="name">Tên phim</option>
                                    <option value="new">Mới đăng</option>

                                </select>
                            </div>
                            <input type="submit" class="btn btn-info" value="Lọc phim"> </form>
                        <div class="block-body">
                            <div class="list-film row">
							<?php 
if($total){
while($row = $q->fetch(PDO::FETCH_ASSOC)){
$filmID = $row['film_id'];
$filmNAMEVN = $row['film_name'];
$filmNAMEEN = $row['film_name_real'];
$filmYEAR = $row['film_year'];
$filmIMG = thumbimg($row['film_img'],200);
$filmSLUG = $row['film_slug'];
$filmURL = $web_link.'/phim/'.$filmSLUG.'-'.replace($filmID).'/';
$filmQUALITY = $row['film_tapphim'];
$filmSTATUS = str_replace('Hoàn tất','Full',$row['film_trangthai']);
	$filmVIEWED = number_format($row['film_viewed']);
	$filmLANG = film_lang($row['film_lang']);
if($row['film_lb'] == 0){
	    $Status = $filmQUALITY.'-'.$filmLANG;
	}else{
	    $Status = $filmSTATUS.'-'.$filmLANG;
	}
	
?>
                                <div class="item col-lg-3 col-md-3 col-sm-6 col-xs-6">
                                    <div class="inner">
                                        <a class="poster" href="<?=$filmURL;?>" title="<?=$filmNAMEVN;?> - <?=$filmNAMEEN;?>"> <img src="<?=$filmIMG;?>" alt="<?=$filmNAMEVN;?>">  </a> <span class="status"><?=$Status;?></span> <a class="name" href="<?=$filmURL;?>" title="<?=$filmNAMEVN;?> - <?=$filmNAMEEN;?>"><?=$filmNAMEVN;?></a> <dfn><?=$filmNAMEEN;?></dfn> <dfn><?=$filmYEAR;?></dfn> </div>
                                </div>
  <? } }else{ ?>
<p class="bg-warning" style="padding: 20px">Chưa có dữ liệu</p>

<? } ?>                              
                            </div> <span class="page_nav">
							<?=$ViewPage;?>
							</span>
                        </div>
						<div style="width:100%;overflow:hidden;"><?=ShowAds("list_below_list");?></div>
                    </div>
                    <!--.block-->
                </div>
                <!--/.main-->
                <div class="sidebar col-lg-4 col-md-4 col-sm-5">
                       <div class="block announcement">
                            <div class="widget-title">
     							<h3 class="title">Thông báo</h3> 
								</div> 
                            <div class="block-body">
                                <div class="announcement-list"><?=strip_tags(text_tidy1($announcement),'<a><b><i><u><br>');?></div>
                            </div>
                        </div>
<div class="block ad_location" id="ads_location">
                              <?=showAds('right_below_fanpage');?>
                        </div>
						<div class="block chatting">
						<div class="widget-title">
						<span class="tabs"><div class="tab" data-name="request_list" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Yêu cầu/ tán gẫu</a></div></div>
							<div class="tab active" data-name="request_post" data-target=".block.chatting .content"><div class="name"><a title="Phim lẻ" href="javascript:void(0)">Gửi yêu cầu</a></div></div>	
								 </span>
						</div> 
						
						<div class="block-body">
<span class="rtips">Nhấn vào nút "Trả lời" để reply bình luận đó!</span>
						<div class="content hidden" data-name="request_list" id="request_list_show">
						     <?=ShowRequest("WHERE request_type = 0","ORDER BY request_time",10,'showrequest_templates');?>
                        </div>
						<div class="content" data-name="request_post">
						     <div class="chat-form" style="margin-bottom:10px">
							 <span id="chat-error" style="display:none;"></span>	
							 <?=chatForm();?></div>
                        </div>
                        </div>
                        </div>
                        <div class="block interested">
						<div class="widget-title">
     							<h3 class="title">Phim hot tuần</h3> 
								<span class="tabs"><div class="tab active" data-name="lew" data-target=".block.interested .content"><div class="name"><a title="Phim lẻ" href="phim-le/">Phim lẻ</a></div></div>
								<div class="tab" data-name="bow" data-target=".block.interested .content"><div class="name"><a title="Phim bộ" href="phim-bo/">Phim bộ</a></div></div>
								 </span></div> 
								
                          
                            <div class="block-body">
                                <div class="content" data-name="lew">
                                    <div class="list-film-simple">
                                        <?=ShowFilm("WHERE film_lb = 0","ORDER BY film_viewed_w",10,'showfilm_right_home','phimle_hotw');?>


                                    </div>
                                </div>
                                <div class="content hidden" data-name="bow">
                                    <div class="list-film-simple">

                                        <?=ShowFilm("WHERE film_lb IN (1,2)","ORDER BY film_viewed_w",10,'showfilm_right_home','phimbo_hotw');?>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--/.block-->
                         <div class="block fanpage">
                            <div class="fb-page" data-href="https://www.facebook.com/phiimtv" data-width="100%" data-small-header="false" data-adapt-container-width="true" data-hide-cover="false" data-show-facepile="true" data-show-posts="false">
                                <div class="fb-xfbml-parse-ignore">
                                    <blockquote cite="https://www.facebook.com/phiimtv"><a href="https://www.facebook.com/phiimtv">Phim Lẻ</a></blockquote>
                                </div>
                            </div>

                        </div>
                        <div class="block ad_location mobile hidden-lg hidden-md">

                        </div>
                        <div class="block tagcloud">
                            <div class="widget-title">
     							<h3 class="title">Từ khóa phổ biến</h3> 
								</div> 
                            <div class="block-body">
                                <ul>

                                    <? require_once("hot_tags_home.php");?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!--.sidebar-->
            </div>
        </div>
    </div>
	
	 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery-2.1.0.min.js" type="text/javascript"></script>
	 <script type="text/javascript" src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/123movies.min.js?v=1.5"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.bootstrap-growl.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.magnific-popup.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/owl.carousel.min.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.notie.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
 <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/pl.public.js" type="text/javascript"></script>
	<script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/plfilter.js" type="text/javascript"></script>
        <script src="<?=STATIC_URL;?>/<?=$CurrentSkin;?>/js/jquery.cookie.js" type="text/javascript"></script>
    <? require_once("footer.php");?>
</body>
</html>
<? }else header('Location: '.$web_link.'/404'); }else header('Location: '.$web_link.'/404'); ?>