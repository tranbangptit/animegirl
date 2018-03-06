<?php
	

set_time_limit(0);



?>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Content</title>

<style>
.left { float: left;}.right { float: right; }.clr { clear: both;}
</style>
</head>
<script language="JavaScript" type="text/JavaScript">
<!--
function onover(obj,cls){obj.className=cls;}
function onout(obj,cls){obj.className=cls;}
function ondown(obj,url,cls){obj.className=cls; window.location=url;}
//-->
</script>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Multi Grab</li>
              </ul>
			  <section class="panel panel-default">
                <header class="panel-heading font-bold">
                  Multi Grab
                </header>
                <div class="panel-body">
<?php

if ($_POST['webgrab'] == 'phimmoi') {
	$info_url_html = xem_web($_POST['urlgrab']);
	$total_links = $_POST['urlgrab'].'xem-phim.html';
    $url = get_by_curl($total_links);
	preg_match("#episodeJson='(.*?)'#", $url, $list);
	$grab = str_replace("/",'/',$list[1]);
	$url_song = explode(',"url"',$grab);
	
	
	$total_play = count($url_song);
	$total_plays = $total_play-1;    
	$url_imgs = explode('<div class="prpt">', $info_url_html);
	
	$info_img = explode("filmInfo.previewUrl='", $info_url_html);
	$info_img = explode('preview.thumb.jpg', $info_img[1]);
	$info_img = $info_img[0].'poster.medium.jpg';
	
	$info_imgbn = explode("filmInfo.previewUrl='", $info_url_html);
	$info_imgbn = explode('preview.thumb.jpg', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0].'preview.medium.jpg';
	
	$info_name = explode('<span class="title-1">', $info_url_html);
	$info_name = explode('</span>', $info_name[1]);
	
	$info_name_en = explode('<span class="title-2">', $info_url_html);
	$info_name_en = explode('</span>', $info_name_en[1]);
	
	$info_daodien = explode('<dd class="movie-dd dd-director">', $info_url_html);
	$info_daodien = explode('</dd>', $info_daodien[1]);
	
	
	
	$dienvien = explode('<span class="actor-name-a">',$info_url_html);
	for($i=1;$i<count($dienvien);$i++){
	$dvien = explode('</span>',$dienvien[$i]);
	$dienviens .= $dvien[0].','; 
	}
	
	

	$info_dienvien = $dienviens;
	
	$info_nam = explode('Năm:</dt><dd class="movie-dd">', $info_url_html);
	$info_nam = explode('</dd>', $info_nam[1]);
	
	$info_sx = explode('Công ty SX:</dt><dd class="movie-dd">', $info_url_html);
	$info_sx = explode('</dd>', $info_sx[1]);
	
	$info_trangthai = explode('<dd class="movie-dd status">', $info_url_html);
	$info_trangthai = explode('</dd>', $info_trangthai[1]);
		
	$info_imdb = explode('<dd class="movie-dd imdb">', $info_url_html);
	$info_imdb = explode('</dd>', $info_imdb[1]);
	
	$info_time = explode('<dt class="movie-dt">Thời lượng:</dt><dd class="movie-dd">', $info_url_html);
	$info_time = explode('</dd>', $info_time[1]);
	
	$info_trailer = explode("filmInfo.trailerUrl='", $info_url_html);
	$info_trailer = explode("'", $info_trailer[1]);
	
	$info_tt = explode('<div class="content" id="film-content">', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	($info_name[0].", ".$info_name_en[0].",".get_ascii($info_name[0]).",download ".$info_name[0].",phim ".$info_name[0]." full hd,".$info_name[0]." 2015,".$info_name[0]." miễn phí,".$info_name[0]." vietsub,".$info_name_en[0]." hd online,".$info_name_en[0]." full hd, download ".$info_name_en[0].", tải phim ".$info_name[0]."");
	
	$info_theloai = explode('<dd class="movie-dd dd-cat">', $info_url_html);
	$info_theloai = explode('</dd>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Phim hành động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phim phiêu lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Phim kinh dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Phim viễn tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Phim hài hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Phim hình sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Phim tâm lý") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Phim hoạt hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Phim chiến tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Phim thần thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Phim võ thuật") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Phim cổ trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Phim gia đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Phim Thể thao-Âm nhạc") $list_category[$i] = 27;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<dd class="movie-dd dd-country">', $info_url_html);
	$country = explode('</dd>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Mỹ") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Hồng Kông") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'phimmedia') {
	$info_url_html = xem_web($_POST['urlgrab']);
	$total_links = $_POST['urlgrab'].'xem-phim.html';
    $url = get_by_curl($total_links);
	preg_match("#episodeJson='(.*?)'#", $url, $list);
	$grab = str_replace("/",'/',$list[1]);
	$url_song = explode(',"url"',$grab);
	
	
	$total_play = count($url_song);
	$total_plays = $total_play-1;    
	$url_imgs = explode('<div class="prpt">', $info_url_html);
	
	$info_img = explode('<meta property="og:image" content="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = $info_img[0];
	
	$info_imgbn = explode("filmInfo.previewUrl='", $info_url_html);
	$info_imgbn = explode('preview.thumb.jpg', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0];
	
	$info_name = explode('<h2 class="title fr">', $info_url_html);
	$info_name = explode('</h2>', $info_name[1]);
	
	$info_name_en = explode('<h3>', $info_url_html);
	$info_name_en = explode('</h3>', $info_name_en[1]);
	
	$info_daodien = explode('Đạo diễn:', $info_url_html);
	$info_daodien = explode('</div>', $info_daodien[1]);
	
	$info_dienvien = explode('Nghệ sĩ:', $info_url_html);
	$info_dienvien = explode('</div>', $info_dienvien[1]);
	$info_dienvien = $info_dienvien[0];
	
	$info_nam = explode('<span class="year">(', $info_url_html);
	$info_nam = explode(')', $info_nam[1]);
	
	$info_sx = explode('Công ty SX:</dt><dd class="movie-dd">', $info_url_html);
	$info_sx = explode('</dd>', $info_sx[1]);
	
	$info_trangthai = explode('<dd class="movie-dd status">', $info_url_html);
	$info_trangthai = explode('</dd>', $info_trangthai[1]);
		
	$info_imdb = explode('<dt>IMDb</dt>
<dd>', $info_url_html);
	$info_imdb = explode('</dd>', $info_imdb[1]);
	
	$info_time = explode('<dt>Thời lượng:</dt>
<dd>', $info_url_html);
	$info_time = explode('</dd>', $info_time[1]);
	
	$info_trailer = explode("filmInfo.trailerUrl='", $info_url_html);
	$info_trailer = explode("'", $info_trailer[1]);
	
	$info_tt = explode('<div class="detail-content-main">', $info_url_html);
	$info_tt = explode('<div class="detail">', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	trim($info_name[0]).", ".trim($info_name_en[0]);
	
	$info_theloai = explode('<dd class="movie-dd dd-cat">', $info_url_html);
	$info_theloai = explode('</dd>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Phim hành động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phim phiêu lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Phim kinh dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Phim viễn tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Phim hài hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Phim hình sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Phim tâm lý") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Phim hoạt hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Phim chiến tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Phim thần thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Phim võ thuật") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Phim cổ trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Phim gia đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Phim Thể thao-Âm nhạc") $list_category[$i] = 27;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<dt>Quốc gia:</dt>
<dd>', $info_url_html);
	$country = explode('</dd>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Mỹ") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Hồng Kông") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'phimnhanh') {
	$info_url_html = xem_web($_POST['urlgrab']);
	
	$info_img = explode('<link rel="image_src" href="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = $info_img[0];
	
	$info_imgbn = explode("filmInfo.previewUrl='", $info_url_html);
	$info_imgbn = explode('preview.thumb.jpg', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0];
	
	$info_name = explode('<h1 class="movie-title">', $info_url_html);
	$info_name = explode('(', $info_name[1]);
	
	$info_name_en = explode('<p class="realname">', $info_url_html);
	$info_name_en = explode('(', $info_name_en[1]);
	
	$info_daodien = explode('<p>Đạo diễn: <span class="author">', $info_url_html);
	$info_daodien = explode('</span>', $info_daodien[1]);
	
	
	
	$dienvien = explode('<p>Diễn viên: <span class="ingredient">',$info_url_html);
	$dienvien = explode('</span>',$dienvien[1]);
	$info_dienvien = $dienvien[0];
	
	$info_nam = explode('<p>Năm sản xuất: <span>', $info_url_html);
	$info_nam = explode('</span>', $info_nam[1]);
	
	$info_sx = explode('Công ty SX:</dt><dd class="movie-dd">', $info_url_html);
	$info_sx = explode('</dd>', $info_sx[1]);
	
	$info_trangthai = explode('<dd class="movie-dd status">', $info_url_html);
	$info_trangthai = explode('</dd>', $info_trangthai[1]);
		
	$info_imdb = explode('<span class="m-label imdb">IMDb', $info_url_html);
	$info_imdb = explode('</span>', $info_imdb[1]);
	
	$info_time = explode('<span class="m-label ep">', $info_url_html);
	$info_time = explode('</span>', $info_time[1]);
	
	$info_trailer = explode("file: '", $info_url_html);
	$info_trailer = explode("'", $info_trailer[1]);
	
	$info_tt = explode('data-colorscheme="dark"></div>', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	trim($info_name[0]).", ".trim($info_name_en[0]);
	
	$info_theloai = explode('<p>Thể loại: <span>', $info_url_html);
	$info_theloai = explode('</span>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Hành Động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phiêu Lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Kinh Dị - Ma") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Viễn Tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Hài Hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Phim hình sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Tâm Lý - Tình Cảm ") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Hoạt Hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Chiến Tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Thần Thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Võ Thuật - Kiếm Hiệp ") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Phim cổ trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Phim gia đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Thể Thao - Âm Nhạc") $list_category[$i] = 27;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<p>Quốc gia: <span>', $info_url_html);
	$country = explode('</span>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Mỹ - Châu Âu") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Hồng Kông") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'biphim') {
	$info_url_html = xem_web($_POST['urlgrab']);
	
	
	$info_img = explode("<img src='", $info_url_html);
	$info_img = explode("'", $info_img[1]);
	$info_img = $info_img[0];
	
	$info_imgbn = explode("filmInfo.previewUrl='", $info_url_html);
	$info_imgbn = explode('preview.thumb.jpg', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0];
	
	$info_name = explode('<h2 class="title fr">', $info_url_html);
	$info_name = explode('</h2>', $info_name[1]);
	
	$info_name_en = explode('<div class="name2 fr"><h3>', $info_url_html);
	$info_name_en = explode('</h3>', $info_name_en[1]);
	
	$info_daodien = explode('<dt>Đạo diễn:</dt><dd>', $info_url_html);
	$info_daodien = explode('</dd>', $info_daodien[1]);
	
	
	
	
	
	$info_dienvien = explode('<dt>Diễn viên:</dt><dd>', $info_url_html);
	$info_dienvien = explode('</dd>', $info_dienvien[1]);

	$info_dienvien = $info_dienvien[0];
	
	$info_nam = explode('<span class="year">(', $info_url_html);
	$info_nam = explode(')', $info_nam[1]);
	
	$info_sx = explode('Công ty SX:</dt><dd class="movie-dd">', $info_url_html);
	$info_sx = explode('</dd>', $info_sx[1]);
	
	$info_trangthai = explode('<dd class="status">', $info_url_html);
	$info_trangthai = explode('</dd>', $info_trangthai[1]);
		
	$info_imdb = explode('<dd class="movie-dd imdb">', $info_url_html);
	$info_imdb = explode('</dd>', $info_imdb[1]);
	
	$info_time = explode('<dt>Thời lượng:</dt><dd>', $info_url_html);
	$info_time = explode('</dd>', $info_time[1]);
	
	$info_trailer = explode('file: "', $info_url_html);
	$info_trailer = explode('"', $info_trailer[1]);
	
	$info_tt = explode('<div class="tab text">', $info_url_html);
	$info_tt = explode('<p> Trailer phim', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	$info_name[0].", ".$info_name_en[0];
	
	$info_theloai = explode('<dt>Thể loại:</dt><dd>', $info_url_html);
	$info_theloai = explode('</dd>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Phim Hành Động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phim Phiêu Lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Phim Kinh Dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Phim Viễn Tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Phim Hài") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Phim Hình Sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Phim Tâm Lý") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Phim Hoạt Hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Phim Chiến Tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Phim Thần Thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Phim Võ Thuật") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Phim Cổ Trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Phim Gia Đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Phim Âm Nhạc") $list_category[$i] = 27;
		elseif ($list_category[$i] == "Phim Tình cảm") $list_category[$i] = 4;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<dt>Quốc gia:</dt><dd>', $info_url_html);
	$country = explode('</dd>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Mỹ") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Hồng Kong") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'megabox') {
	$info_url_html = xem_web($_POST['urlgrab']);
	
    $url = get_by_curl($total_links);
	preg_match("#episodeJson='(.*?)'#", $url, $list);
	$grab = str_replace("/",'/',$list[1]);
	$url_song = explode(',"url"',$grab);
	
	
	$total_play = count($url_song);
	$total_plays = $total_play-1;    
	$url_imgs = explode('<div class="prpt">', $info_url_html);
	
	$info_img = explode('scr="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = $info_img[0];
	
	$info_imgbn = explode("filmInfo.previewUrl='", $info_url_html);
	$info_imgbn = explode('preview.thumb.jpg', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0].'preview.medium.jpg';
	
	$info_name = explode('<h1 class="H1title">', $info_url_html);
	$info_name = explode('</h1>', $info_name[1]);
	
	$info_name_en = explode('<span class="explain">(', $info_url_html);
	$info_name_en = explode(')</span>', $info_name_en[1]);
	
	$info_daodien = explode('<dd class="movie-dd dd-director">', $info_url_html);
	$info_daodien = explode('</dd>', $info_daodien[1]);
	
	
	
	$dienvien = explode('<li><a class="thumb"',$info_url_html);
	for($i=1;$i<count($dienvien);$i++){
	$dvien = explode('<div class="name">',$dienvien[$i]);
	$dvien = explode('</div>',$dvien[1]);
	$dienviens .= htmltxt(trim($dvien[0])).','; 
	}
	
	

	$info_dienvien = $dienviens;
	
	$info_nam = explode('Năm phát hành:</span>', $info_url_html);
	$info_nam = explode('</li>', $info_nam[1]);
	
	$info_sx = explode('Công ty SX:</dt><dd class="movie-dd">', $info_url_html);
	$info_sx = explode('</dd>', $info_sx[1]);
	
	$info_trangthai = explode('<dd class="movie-dd status">', $info_url_html);
	$info_trangthai = explode('</dd>', $info_trangthai[1]);
		
	$info_imdb = explode('<dd class="movie-dd imdb">', $info_url_html);
	$info_imdb = explode('</dd>', $info_imdb[1]);
	
	$info_time = explode('Thời lượng:</span>', $info_url_html);
	$info_time = explode('</li>', $info_time[1]);
	
	$info_trailer = explode("filmInfo.trailerUrl='", $info_url_html);
	$info_trailer = explode("'", $info_trailer[1]);
	
	$info_tt = explode('<div class="intro mCustomScroll">', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	(trim($info_name[0]).", ".trim($info_name_en[0])."");
	
	$info_theloai = explode('Thể loại:</span>', $info_url_html);
	$info_theloai = explode('</li>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Hành động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phiêu lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Ma kinh dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Phim viễn tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Hài") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Hình sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Tâm lý") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Hoạt hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Chiến tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Phim thần thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Võ thuật") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Cổ trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Gia đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Âm nhạc") $list_category[$i] = 27;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('Quốc gia:</span>', $info_url_html);
	$country = explode('</li>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Mỹ") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Hồng Kông") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'phim14') {
	$info_url_html = xem_web($_POST['urlgrab']);
	$total_links = $_POST['urlgrab'].'xem-phim.html';
    $url = get_by_curl($total_links);
	preg_match("#episodeJson='(.*?)'#", $url, $list);
	$grab = str_replace("/",'/',$list[1]);
	$url_song = explode(',"url"',$grab);
	
	
	$total_play = count($url_song);
	$total_plays = $total_play-1;    
	$url_imgs = explode('<div class="prpt">', $info_url_html);
	
	$info_img = explode('<meta property="og:image" content="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = $info_img[0];
	
	$info_imgbn = explode("filmInfo.previewUrl='", $info_url_html);
	$info_imgbn = explode('preview.thumb.jpg', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0];
	
	$info_name = explode('<div class="alt2">Tên phim: <font color="white">', $info_url_html);
	$info_name = explode(' - ', $info_name[1]);
	
	$info_name_en = explode('</font>', $info_name[1]);
//	$info_name_en = explode('</span>', $info_name_en[1]);
	
	$info_daodien = explode('<div class="alt1">Đạo diễn:', $info_url_html);
	$info_daodien = explode('</div>', $info_daodien[1]);
	
	
	
	$dienvien = explode('<div class="alt2">Diễn viên:',$info_url_html);

	$dvien = explode('</div>',$dienvien[1]);

	
	
	

	$info_dienvien = htmltxt($dvien[0]);
	$info_dienvien = str_replace('  ','',$info_dienvien);
	
	$info_nam = explode('<div class="alt2">Năm phát hành:', $info_url_html);
	$info_nam = explode('</div>', $info_nam[1]);
	
	$info_sx = explode('Công ty SX:</dt><dd class="movie-dd">', $info_url_html);
	$info_sx = explode('</dd>', $info_sx[1]);
	
	$info_trangthai = explode('<div class="alt2">Status: <font color="red">', $info_url_html);
	$info_trangthai = explode('</font>', $info_trangthai[1]);
		
	$info_imdb = explode('<dd class="movie-dd imdb">', $info_url_html);
	$info_imdb = explode('</dd>', $info_imdb[1]);
	
	$info_time = explode('<div class="alt1">Thời lượng:', $info_url_html);
	$info_time = explode('</div>', $info_time[1]);
	
	$info_trailer = explode("filmInfo.trailerUrl='", $info_url_html);
	$info_trailer = explode("'", $info_trailer[1]);
	
	$info_tt = explode('<div class="message">', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	($info_name[0].", ".$info_name_en[0].",".get_ascii($info_name[0]).",download ".$info_name[0].",phim ".$info_name[0]." full hd,".$info_name[0]." 2015,".$info_name[0]." miễn phí,".$info_name[0]." vietsub,".$info_name_en[0]." hd online,".$info_name_en[0]." full hd, download ".$info_name_en[0].", tải phim ".$info_name[0]."");
	
	$info_theloai = explode('<div class="alt1">Thể loại:', $info_url_html);
	$info_theloai = explode('</div>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Phim Hành Động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phim Phiêu Lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Phim Kinh Dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Phim Viễn Tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Phim Hài Hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Phim Hình Sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Phim Tâm Lý") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Phim Hoạt Hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Phim Chiến Tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Phim Thần Thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Phim Võ Thuật") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Phim Cổ trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Phim Gia Đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Phim Thể thao-Âm nhạc") $list_category[$i] = 27;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<div class="alt2">Quốc Gia:', $info_url_html);
	$country = explode('</div>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Phim Mỹ") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Phim Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Phim Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Phim Hồng Kông") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Phim Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Phim Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Phim Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Phim Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Phim Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Phim Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Phim Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Phim Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Phim Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Phim Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'phimhdvn') {
	$info_url_html = xem_web($_POST['urlgrab']);
	$total_links = $_POST['urlgrab'].'xem-phim.html';
    $url = get_by_curl($total_links);
	preg_match("#episodeJson='(.*?)'#", $url, $list);
	$grab = str_replace("/",'/',$list[1]);
	$url_song = explode(',"url"',$grab);
	
	
	$total_play = count($url_song);
	$total_plays = $total_play-1;    

	
	$info_img = explode('<link rel="image_src" href="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = $info_img[0];
	
	$info_imgbn = explode('<div class="img"><img src="', $info_url_html);
	$info_imgbn = explode('"', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0];
	
	$info_name = explode('<p>Tên phim:	<span class="fn">', $info_url_html);
	$info_name = explode(' - ', $info_name[1]);
	
	$info_name_en = explode(' - ', $info_name[1]);
//	$info_name_en = explode('</span>', $info_name_en[1]);
	
	$info_daodien = explode('<p>Đạo diễn: 	<span>', $info_url_html);
	$info_daodien = explode('</span></p>', $info_daodien[1]);
	
	
	
	$dienvien = explode('<p>Diễn viên: 	<span>',$info_url_html);

	$dvien = explode('</span></p>',$dienvien[1]);

	
	
	

	$info_dienvien = htmltxt($dvien[0]);
	$info_dienvien = str_replace('  ','',$info_dienvien);
	
	$info_nam = explode('<p>Năm phát hành: 	<span>', $info_url_html);
	$info_nam = explode('</span></p>', $info_nam[1]);
	
	$info_sx = explode('<p>Nhà sản xuất: 	<span>', $info_url_html);
	$info_sx = explode('</span></p>', $info_sx[1]);
	
	$info_trangthai = explode('<div class="alt2">Status: <font color="red">', $info_url_html);
	$info_trangthai = explode('</font>', $info_trangthai[1]);
		
	$info_imdb = explode('<span class="average">', $info_url_html);
	$info_imdb = explode('</span>', $info_imdb[1]);
	
	$info_time = explode('<p>Thời lượng: 	<span>', $info_url_html);
	$info_time = explode('</span>', $info_time[1]);
	
	$info_trailer = explode("filmInfo.trailerUrl='", $info_url_html);
	$info_trailer = explode("'", $info_trailer[1]);
	
	$info_tt = explode('<div class="entry">', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	$info_name[0].",".$info_name_en[0];
	
	$info_theloai = explode('<span id="bs_category">', $info_url_html);
	$info_theloai = explode('</span>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Phiêu Lưu - Hành Động") $list_category[$i] = '1,2';
		elseif ($list_category[$i] == "Kinh Dị - Ma") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Viễn Tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Hài Hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Phim Hình Sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Tâm Lý - Tình Cảm") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Hoạt Hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Chiến Tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Thần Thoại - Lịch Sử") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Võ Thuật - Kiếm Hiệp") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Phim Cổ trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Phim Gia Đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Thể Thao - Âm Nhạc") $list_category[$i] = 27;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<span id="bs_country">', $info_url_html);
	$country = explode('</span>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Mỹ - Châu Âu") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Hong Kong") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Phim Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Phim Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Phim Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Phim Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'phimh') {
	$info_url_html = xem_web($_POST['urlgrab']);
	
	$info_url = explode('<p class="bt"><a href="', $info_url_html);
	$info_url = explode('"', $info_url[1]);
	
	$total_links = trim("http://vnhd.net/".$info_url[0]);
	
	$url = xem_web($total_links);
	
	
	$url_play = explode('&nbsp;&nbsp;&nbsp;<a',$url);
	
	$total_play = count($url_play);
	$total_plays = $total_play-1;    
	// $url_imgs = explode('<div class="prpt">', $info_url_html);
	
	$info_img = explode('<img src="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = $info_img[0];
	
	$info_imgbn = explode("filmInfo.previewUrl='", $info_url_html);
	$info_imgbn = explode('preview.thumb.jpg', $info_imgbn[1]);
	$info_imgbn = $info_imgbn[0];
	
	$info_name = explode('<h1>»', $info_url_html);
	$info_name = explode('</h1>', $info_name[1]);
	
	$info_name_en = explode('<p class="tt-en"><b>»', $info_url_html);
	$info_name_en = explode('</b>', $info_name_en[1]);
	
	$info_daodien = explode('Đạo diễn</b>:', $info_url_html);
	$info_daodien = explode('</p>', $info_daodien[1]);
	
	
	

	$info_dienvien = explode('Diễn viên</b>:', $info_url_html);
	$info_dienvien = explode('</p>', $info_dienvien[1]);
	$info_dienvien = $info_dienvien[0];
	


	
	$info_nam = explode('Năm San Xuất</b>:', $info_url_html);
	$info_nam = explode('</p>', $info_nam[1]);
	
	$info_sx = explode('<span>Hãng sản xuất: </span>', $info_url_html);
	$info_sx = explode('<br/>', $info_sx[1]);
	
	$info_trangthai = explode('<dd class="movie-dd status">', $info_url_html);
	$info_trangthai = explode('</dd>', $info_trangthai[1]);
		
	$info_imdb = explode('<dd class="movie-dd imdb">', $info_url_html);
	$info_imdb = explode('</dd>', $info_imdb[1]);
	
	$info_time = explode('<span>Độ dài: </span>', $info_url_html);
	$info_time = explode('<br/>', $info_time[1]);
	
	$info_trailer = explode('value="file=', $info_url_html);
	$info_trailer = explode("&", $info_trailer[1]);
	
	$info_tt = explode('<div class="entry movie_description" id="movie_description">', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	trim($info_name[0].", ".$info_name_en[0]."");
	
	$info_theloai = explode('Thể loại</b>:', $info_url_html);
	$info_theloai = explode('</p>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Hành động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phiêu lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Kinh dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Viễn tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Hài hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Hình Sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Tình Cảm") $list_category[$i] = 4;
		elseif ($list_category[$i] == "Tâm lý") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Hoạt hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Chiến tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Thần thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Võ Thuật") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Cổ trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Phim Gia đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Phim Thể thao - Âm nhạc") $list_category[$i] = 27;
		elseif ($list_category[$i] == "Tivi Show - Khác") $list_category[$i] = 23;
			else $list_category[$i] = '';
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('Quốc gia</b>:', $info_url_html);
	$country = explode('</p>', $country[1]);
	$country = trim(htmltxt($country[0]));
	$list_country 	= explode(',',$country);
	for($i=0;$i<count($list_country);$i++) {

		$list_country[$i] = trim(str_replace(",", "", $list_country[$i]));
		if ($list_country[$i] == "Mỹ - Châu Âu") $list_country[$i] = 7;
		elseif ($list_country[$i] == "Nhật Bản") $list_country[$i] = 6;
		elseif ($list_country[$i] == "Hàn Quốc") $list_country[$i] = 3;
		elseif ($list_country[$i] == "Hồng Kông") $list_country[$i] = 5;
		elseif ($list_country[$i] == "Việt Nam") $list_country[$i] = 1;
		elseif ($list_country[$i] == "Trung Quốc") $list_country[$i] = 2;
		elseif ($list_country[$i] == "Đài Loan") $list_country[$i] = 4;
		elseif ($list_country[$i] == "Thái Lan") $list_country[$i] = 8;
		elseif ($list_country[$i] == "Phim Anh") $list_country[$i] = 72;
		elseif ($list_country[$i] == "Phim Pháp") $list_country[$i] = 73;
		elseif ($list_country[$i] == "Ấn Độ") $list_country[$i] = 9;
		elseif ($list_country[$i] == "Phim Úc") $list_country[$i] = 74;
		elseif ($list_country[$i] == "Phim Canada") $list_country[$i] = 75;
		elseif ($list_country[$i] == "Châu Á") $list_country[$i] = 10;
		else $list_country[$i] = '';
		$htmls .= ','.$list_country[$i];
	}
	
	$quocgia = str_replace(',,',',',$htmls);

}elseif ($_POST['webgrab'] == 'phimvipvn') {
	$info_url_html = xem_web($_POST['urlgrab']);
$link = $_POST['urlgrab'];
   $file = getPage(str_replace('phimvipvn.net','m.phimvipvn.net',$link),'','30','1');
		$link = explode('<a itemprop="url" href="',$file);
		$link = explode('"',$link[1]);
		$link = $link[0];
	
	
	$file = getPage($link,'','30','1');
	$ten = explode('<!-- BEGIN RELATE EPISODE -->',trim($file));
	$ten1 = explode('<!-- END TOTAL EPISODE -->',$ten[1]);
	preg_match_all('/href="([^"]+).*?[>](.*?[^<]+)/', $ten1[0],$id);
	
	$idd = $id[1];
	$total_play = count($id[1]);
	$total_plays = $total_play-1;    

	
	$info_img = explode('<div class="thumbnail"><img src="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = $info_img[0];
	
	$info_name = explode('<meta property="og:title" content="Phim ', $info_url_html);
	$info_name = explode(' - ', $info_name[1]);
	
	$info_name_en = explode(' (', $info_name[1]);
//	$info_name_en = explode('</span>', $info_name_en[1]);
	
	$info_daodien = explode('<dt>Đạo diễn:', $info_url_html);
	$info_daodien = explode('<dt>', $info_daodien[1]);
	
	
		$info_dienvien = explode('<dt>Diễn viên:', $info_url_html);
	$info_dienvien = explode('<dt>', $info_dienvien[1]);
	$info_dienvien = $info_dienvien[0];
	
	
	
	$info_nam = explode('<dt>Năm phát hành:', $info_url_html);
	$info_nam = explode('<dt>', $info_nam[1]);
	
	$info_sx = explode('Công ty SX:</dt><dd class="movie-dd">', $info_url_html);
	$info_sx = explode('</dd>', $info_sx[1]);
	
	$info_trangthai = explode('<dd class="movie-dd status">', $info_url_html);
	$info_trangthai = explode('</dd>', $info_trangthai[1]);
		
	$info_imdb = explode('<dd class="movie-dd imdb">', $info_url_html);
	$info_imdb = explode('</dd>', $info_imdb[1]);
	
	$info_time = explode('<dt class="movie-dt">Thời lượng:</dt><dd class="movie-dd">', $info_url_html);
	$info_time = explode('</dd>', $info_time[1]);
	
	$info_trailer = explode("filmInfo.trailerUrl='", $info_url_html);
	$info_trailer = explode("'", $info_trailer[1]);
	
	$info_tt = explode('<div class="message" itemprop="description">', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);	
	$info_tt = $info_tt[0];
	
	$info_tag	=	($info_name[0].", ".$info_name_en[0].",".get_ascii($info_name[0]).",download ".$info_name[0].",phim ".$info_name[0]." full hd,".$info_name[0]." 2015,".$info_name[0]." miễn phí,".$info_name[0]." vietsub,".$info_name_en[0]." hd online,".$info_name_en[0]." full hd, download ".$info_name_en[0].", tải phim ".$info_name[0]."");
	
	$info_theloai = explode('<dt>Thể loại:', $info_url_html);
	$info_theloai = explode('<dt>', $info_theloai[1]);
	
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Hành Động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phiêu Lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Kinh Dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Viễn Tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Hài Hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Hình Sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Hoạt Hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Chiến Tranh") $list_category[$i] = 22;
		elseif ($list_category[$i] == "Thần Thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Võ Thuật") $list_category[$i] = 6;
		elseif ($list_category[$i] == "Cổ Trang") $list_category[$i] = 12;
		elseif ($list_category[$i] == "Gia Đình") $list_category[$i] = 114;
		elseif ($list_category[$i] == "Âm nhạc") $list_category[$i] = 27;
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<dt>Quốc gia:', $info_url_html);
	$country = explode('<dt>', $country[1]);
	$country = trim(htmltxt($country[0]));
		if ($country == "Âu Mỹ") $countryid = 7;
		elseif ($country == "Nhật Bản") $countryid = 6;
		elseif ($country == "Hàn Quốc") $countryid = 3;
		elseif ($country == "Hồng Kong") $countryid = 5;
		elseif ($country == "Việt Nam") $countryid = 1;
		elseif ($country == "Trung Quốc") $countryid = 2;
		elseif ($country == "Đài Loan") $countryid = 4;
		elseif ($country == "Thái Lan") $countryid = 8;
		elseif ($country == "Châu Á") $countryid = 10;
	$quocgia = $countryid;

}elseif ($_POST['webgrab'] == 'phimtructuyenhd') {
	$info_url_html = xem_web($_POST['urlgrab']);
    $info_link = explode('<a href="http://phimtructuyenhd.com/xem-phim/', $info_url_html);
	$info_link = explode('"', $info_link[1]);
	
	$url_play_phims = "http://phimtructuyenhd.com/xem-phim/".$info_link[0];
	$url_play_phim = xem_web($url_play_phims);
	
	$url_play = explode('<a id="ep_', $url_play_phim);
	$total_play = count($url_play);
	$total_plays = $total_play-1;    

	$info_img = explode('<img class="thumb" src="', $info_url_html);
	$info_img = explode('"', $info_img[1]);
	$info_img = 'http://phimtructuyenhd.com/'.$info_img[0];
	
	$info_name = explode('<h2 class="title fr">', $info_url_html);
	$info_name = explode('</h2>', $info_name[1]);
	
	$info_name_en = explode('<div class="name2 fr"><h3>', $info_url_html);
	$info_name_en = explode('</h3>', $info_name_en[1]);

	
	//$info_name_en = explode('">', $info_name_en[1]);
	$info_daodien = explode('<dt>Đạo diễn:</dt><dd>', $info_url_html);
	$info_daodien = explode('</dd>', $info_daodien[1]);
	
	$info_dienvien = explode('<dt>Diễn viên:</dt><dd>', $info_url_html);
	$info_dienvien = explode('</dd>', $info_dienvien[1]);
	$info_dienvien = $info_dienvien[0];
	
	$info_nam = explode('<span class="year">(', $info_url_html);
	$info_nam = explode(')', $info_nam[1]);
	
	$info_sx = explode('sadasd', $info_url_html);
	$info_sx = explode('</span>', $info_sx[1]);
	
	$info_time = explode('Thời lượng:</dt><dd>', $info_url_html);
	$info_time = explode('</dd>', $info_time[1]);
	
	$info_tt = explode('<div style="display: block;" class="tab text">', $info_url_html);
	$info_tt = explode('</div>', $info_tt[1]);
	$info_tt = $info_tt[0];
	
	$info_tag	=	($info_name[0].", ".$info_name_en[0].",".get_ascii($info_name[0]).",download ".$info_name[0].",phim ".$info_name[0]." full hd,".$info_name[0]." 2015,".$info_name[0]." miễn phí,".$info_name[0]." vietsub,".$info_name_en[0]." hd online,".$info_name_en[0]." full hd, download ".$info_name_en[0].", tải phim ".$info_name[0]."");
	
	$info_theloai = explode('<dt>Thể loại:</dt><dd>', $info_url_html);
	$info_theloai = explode('</dd>', $info_theloai[1]);
	$category = htmltxt($info_theloai[0]); // Phim Hành Động, Phim Hình Sự
	$list_category 	= explode(',',$category);
	for($i=0;$i<count($list_category);$i++) {
		//$list_category[$i] = ','.$list_category[$i];
		$list_category[$i] = trim(str_replace(",", "", $list_category[$i]));
		if ($list_category[$i] == "Hành Động") $list_category[$i] = 1;
		elseif ($list_category[$i] == "Phiêu Lưu") $list_category[$i] = 2;
		elseif ($list_category[$i] == "Kinh Dị") $list_category[$i] = 3;
		elseif ($list_category[$i] == "Viễn Tưởng") $list_category[$i] = 10;
		elseif ($list_category[$i] == "Hài Hước") $list_category[$i] = 7;
		elseif ($list_category[$i] == "Phim Hình Sự") $list_category[$i] = 25;
		elseif ($list_category[$i] == "Tâm Lý") $list_category[$i] = 9;
		elseif ($list_category[$i] == "Hoạt Hình") $list_category[$i] = 5;
		elseif ($list_category[$i] == "Phim Truyền Hình") $list_category[$i] = 23;
		elseif ($list_category[$i] == "Tình Cảm") $list_category[$i] = 31;
		elseif ($list_category[$i] == "Thần Thoại") $list_category[$i] = 11;
		elseif ($list_category[$i] == "Võ Thuật") $list_category[$i] = 6;
		$html .= ','.$list_category[$i];
	}
	$theloai = $html;
	$country = explode('<dt>Quốc gia:</dt><dd>', $info_url_html);
	$country = explode('</dd>', $country[1]);
	$country = trim(htmltxt($country[0]));
		if ($country == "Phim Mỹ") $countryid = 7;
		elseif ($country == "Nhật Bản") $countryid = 6;
		elseif ($country == "Hàn Quốc") $countryid = 3;
		elseif ($country == "Hồng Kong") $countryid = 5;
		elseif ($country == "Việt Nam") $countryid = 1;
		elseif ($country == "Trung Quốc") $countryid = 2;
		elseif ($country == "Đài Loan") $countryid = 4;
		elseif ($country == "Thái Lan") $countryid = 8;
		elseif ($country == "Châu Á") $countryid = 10;
		elseif ($country == "Phim Pháp") $countryid = 73;
	$quocgia = $countryid;

}
?>
<?php
$begin = $_POST['episode_begin'];
$end = $_POST['episode_end'];
////BEGIN CHECK EPISODE
if(!is_numeric($begin) && !is_numeric($end)){

$episode_begin = 1;
if($total_plays == 0){
$episode_end =1;
}else{
$episode_end = $total_plays;
}

if($_POST['webgrab'] == 'phimvang') $episode_begin =2;
if($_POST['webgrab'] == 'phimvipvn' || $_POST['webgrab'] == 'phimdata' || $_POST['webgrab'] == 'phim4v' || $_POST['webgrab'] == 'anime47') $episode_begin =0;



}elseif(!is_numeric($begin)){
$episode_begin = $episode_end = $end;
}else{
$episode_begin = $begin; $episode_end = $end;
}
////END CHECK EPISODE
if (!$_POST['submit']) {
?>
<script>
var total = <?=$total_links?>;
<? for ($z=1; $z<=$total_sv; $z++) { ?>
    function check_local_<?=$z?>(status){
        for(i=1;i<=total;i++)
            document.getElementById("local_url_<?=$z?>_"+i).checked=status;
    }
<? } ?>
</script>
<form enctype="multipart/form-data" method="post" class="form-horizontal">
<div class="form-group">
                      <label class="col-sm-2 control-label">Tên Phim</label>
					  <div class="col-sm-10">
					  <input name="new_film" class="form-control rounded" size="40" value="<?=htmltxt(trim($info_name[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
                      <label class="col-sm-2 control-label">Tên English</label>
					  <div class="col-sm-10">
					  <input name="tienganh" class="form-control rounded" size="40" value="<?=htmltxt(trim($info_name_en[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Trạng Thái</label>
					  <div class="col-sm-10">
					 <input name="trang_thai" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_trangthai[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Poster</label>
					  <div class="col-sm-10">
					<input name="url_img" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_img));?>">
					<input class="filestyle" size="50" name="phimimg" id="phimimg" type="file" >
					<br />
					Server chứa ảnh:
		<input type="radio" value="1" checked name="server_img"> Không Up
		<input type="radio" value="2" name="server_img"> Picasa
		<input type="radio" value="3" name="server_img"> Local
                <input type="radio" value="4" name="server_img"> Imgur
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
					<div class="form-group">
                      <label class="col-sm-2 control-label">Banner</label>
					  <div class="col-sm-10">
					 <input name="urlimgbn" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_imgbn));?>">
					 <input class="filestyle" size="50" name="phimimgbn" id="phimimgbn" type="file">
					 Server chứa ảnh:
		<input type="radio" value="1" checked name="server_imgbns"> Không Up
		<input type="radio" value="2" name="server_imgbns"> Picasa
		<input type="radio" value="3" name="server_imgbns"> Local
                <input type="radio" value="4" name="server_imgbns"> Imgur
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
				<div class="form-group">
                      <label class="col-sm-2 control-label">Đạo Diễn</label>
					  <div class="col-sm-10">
					<input name="phim_daodien" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_daodien[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Diễn Viên</label>
					  <div class="col-sm-10">
					<input name="phim_dienvien" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_dienvien))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Sản Xuất</label>
					  <div class="col-sm-10">
					<input name="nhasx" class="form-control rounded" size="50" value="<?=trim(htmltxt($info_sx[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Năm Sản Xuất</label>
					  <div class="col-sm-10">
					<input name="phim_nam" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_nam[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
<div class="form-group">
                      <label class="col-sm-2 control-label">Ngôn Ngữ</label>
					  <div class="col-sm-10">
					<?=trang_thai(NULL);?>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Thời Lượng</label>
					  <div class="col-sm-10">
					<input name="phim_thoigian" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_time[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Tập Phim</label>
					  <div class="col-sm-10">
					<input name="tapphim" class="form-control rounded" size="50" value="HD">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Điểm IMDb</label>
					  <div class="col-sm-10">
					<input name="phim_IMDb" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_imdb[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>			
<div class="form-group">
                      <label class="col-sm-2 control-label">Trailer (Youtube)</label>
					  <div class="col-sm-10">
					<input name="trailer" class="form-control rounded" size="50" value="<?=htmltxt(trim($info_trailer[0]))?>">
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Hình thức Phim</label>
					  <div class="col-sm-10">
					<?=film_lb(0);?>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>			
<div class="form-group">
                      <label class="col-sm-2 control-label">Quốc Gia</label>
					  <div class="col-sm-10">
					<?=acp_country($quocgia)?>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Thể Loại</label>
					  <div class="col-sm-10">
					<?=acp_cat($theloai)?>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>		
<div class="form-group">
                      <label class="col-sm-2 control-label">Thông Tin Phim</label>
					  <div class="col-sm-10">
					<textarea  class="form-control" name="phim_info" id="phim_info" class="ckeditor" cols="50" rows="6"><?=trim($info_tt);?></textarea><script>CKEDITOR.replace('phim_info'); </script>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	
<div class="form-group">
                      <label class="col-sm-2 control-label">Từ Khóa Phim</label>
					  <div class="col-sm-10">
					<textarea  class="form-control" name="tagseo" class="ckeditor" id="editor1" cols="50" rows="6"><?=$info_tag;?></textarea>
					  </div>
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>	

					<?php
                    for ($i=$episode_begin;$i<=$episode_end;$i++) {
						if ($_POST['webgrab'] == 'phimvang') {
							$play_url = explode('"', $url_play[$i]);
							$play_url = 'http://phim7.com/xem-phim/'.$play_url[0];
							$html_link_play = xem_web($play_url);
							$link_phim = explode("{'link':'", $html_link_play);
							$link_phim = explode("'}", $link_phim[1]);
							if($link_phim[0]){
							$play_embed[$i] = $link_phim[0];
							}else{
							$link_phim = explode("'file' : '", $html_link_play);
							$link_phim = explode("'", $link_phim[1]);
							$play_embed[$i] = $link_phim[0];
							}
							$name = explode('">', $url_play[$i]);
							$name = explode('</a>', $name[1]);
							if($name[0] == "1-End") $name[0] = "Full";
						}elseif($_POST['webgrab'] == 'phimvipvn'){
						    $hd = getPage($idd[$i],'80','30','1');
				            $l = explode('file: "',$hd);
				            $l1 = explode('",',$l[1]);
				            $link = $l1[0];
                            $play_embed[$i] = trim($link);
	                        $name[0] = replacexph($id[2][$i]);			
                        }elseif ($_POST['webgrab'] == 'phimtructuyenhd') {
							$play_url = explode('"', $url_play[$i]);
							$play_url = 'http://phimtructuyenhd.com/xml/'.$play_url[0].'.xml';
							$html_link_play = xem_web($play_url);
							$link_phim = explode("<location>", $html_link_play);
							$link_phim = explode("</location>", $link_phim[1]);
							
							$play_embed[$i] = $link_phim[0];
							
							$name = explode('<title>', $html_link_play);
							$name = explode('</title>', $name[1]);
							if($name[0] == "Fuil HD") $name[0] = "Full";
						}
                    ?>		
<div class="form-group">
                      <label class="col-sm-2 control-label">Tập <input onclick="this.select()" type="text" name="name[<?=$i?>]" value="<?=trim($name[0])?>" size=2 style="text-align:center" class="form-control rounded"></label>
					  <div class="col-sm-10">
					<input type="text" class="form-control rounded" style="width:100%;" name="url[<?=$i?>]" value="<?=trim($play_embed[$i])?>"><br />
					
					<?=acp_film_ep($server,$i);?>
					  </div>
					 
                    </div>
                    <div class="line line-dashed line-lg pull-in"></div>
                    <?php
                    }
                    ?>
				
					<input type="hidden" name="episode_begin" value="<?=$episode_begin?>">
                    <input type="hidden" name="episode_end" value="<?=$episode_end?>">
					<input type=hidden name=ok value=Submit>
					<input type=submit name=submit class="btn btn-danger" value="Send">
				
					</form>
					 </div>
              </section>
<?php
}
else {
	$actor			= sqlescape($_POST['phim_dienvien']);
	$cat			= join_value($_POST['selectcat']);
	//$cat		 	= implode(',',$_POST['cat']);
	$new_film   	= sqlescape($_POST['new_film']);
	$name_real   	= sqlescape($_POST['tienganh']);
	$trailer	   	= $_POST['trailer'];
	$info		  	= sqlescape2($_POST['phim_info']);
	$time		    = $_POST['phim_thoigian'];
	$year		   	= $_POST['phim_nam'];
	$director	    = sqlescape($_POST['phim_daodien']);
	$country		= join_value($_POST['selectcountry']);
	$tapphim		= $_POST['tapphim'];
	$tag			= strtolower(sqlescape($_POST['tagseo']));
	$bo_le			= $_POST['phimbole'];
	$imgbn			= $_POST['urlimgbn'];
	$_SESSION["imgbnn"] = $imgbn;
	$t_singer   	= $actor;
	$area			= sqlescape($_POST['nhasx']);
        $slug = replace(strtolower($new_film));
	// add film
	
	if ($name_real && $new_film) {
		$film_id =  acp_quick_add_film2($new_film,$name_real,$tapphim,$actor,$year,$time,$area,$director,$cat,$info,$country,$file_type,$bo_le,$key,$des,$imgbn,$tag,$_POST['trang_thai'],$_POST['phim_IMDb'],$_POST['phim_newepisode']);
			}
	$t_film = $film_id;
	if($t_film) $mysql->query("UPDATE ".$tb_prefix."film SET film_date = '".NOW."',film_phim18 = '".$_POST['phim18']."',film_lb = '".$_POST['film_lb']."',film_chieurap = '".$_POST['phimrap']."',film_hot = '".$_POST['phimhot']."',film_time_update = '".NOW."',film_lang = '".$_POST['film_lang']."',film_trailer = '".$trailer."',film_slug = '".$slug."' WHERE film_id = ".$t_film."");
	for ($i=$episode_begin;$i<=$episode_end;$i++){
		$t_url = $_POST['url'][$i];
		$t_name = $_POST['name'][$i];
		$t_sub = $_POST['sub'][$i];
		$t_message = $_POST['message'][$i];
		$t_serep	= 	$_POST['server_ep'][$i];

		//lech sub
		if ($t_url && $t_name) {
		$mysql->query("INSERT INTO ".$tb_prefix."episode (episode_film,episode_url,episode_servertype,episode_name) VALUES ('".$t_film."','".$t_url."','".$t_serep."','".$t_name."')");
	
		

		}

	}

	// upload ảnh img
			$server_img		=	$_POST['server_img'];
			$server_imgbn		=	$_POST['server_imgbns'];
				
		if($server_img == 1) {
			$new_film_img = $_POST['url_img'];
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");

		}elseif($server_img == 3) {
		  // if($_FILES["phimimg"]['name']!=""){  $new_film_img	=	ipupload("phimimg","film",replace(get_ascii($name_real))); }elseif($_POST['url_img']){ $new_film_img = uploadurl($_POST['url_img'],replace(get_ascii($name_real)),'film'); }else{  $new_film_img = "http://vuiphim.net/images/ava.jpg";    }   
          $new_film_img = ""; 		  
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");
		}elseif($server_img == 4){
				$new_film_img =	Imgur_Upload($_POST['url_img'],1);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");
		}elseif($server_img == 2){
				$new_film_img =	Picasa_Upload($_POST['url_img'],1);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_img = '".$new_film_img."' WHERE film_id = ".$t_film."");
				}
		// upload ảnh info
		if($server_imgbn == 1) {
			$new_film_imgbn = $_SESSION["imgbnn"];
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");

		}elseif($server_imgbn == 2) {
		  		$new_film_imgbn =	Picasa_Upload(trim($_SESSION["imgbnn"]),2);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");
		
		}elseif($server_imgbn == 3){
		
		// if($_FILES["phimimgbn"]['name']!=""){  $new_film_imgbn	=	ipupload("phimimgbn","info",replace(get_ascii($name_real)));}elseif($_POST['urlimgbn']){ $new_film_imgbn = uploadurl($_SESSION["imgbnn"],replace(get_ascii($name_real)),'info');}	
		$new_film_imgbn = '';
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");
		
		
		}elseif($server_imgbn == 4) {
		  		$new_film_imgbn =	Imgur_Upload(trim($_SESSION["imgbnn"]),2);
			$mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = '".$new_film_imgbn."' WHERE film_id = ".$t_film."");
		
		}else{
                $mysql->query("UPDATE ".$tb_prefix."film SET film_imgbn = 'http://vuiphim.net/images/playbg.jpg' WHERE film_id = ".$t_film."");
				}	
	
		unset($_SESSION["imgbnn"]);
			echo "Đã thêm xong ".$server_imgbn." <meta http-equiv='refresh' content='1;url=index.php?act=clipvn'>";

}
?>
   </section>
          </section>
