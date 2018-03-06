<?php
define('TRUNKSJJ',true);
ob_start();
session_start();
include('includes/configurations.php');
$rssVersion = 2.0;
$cat = (int)$_GET['c'];
function replace1($string) {
	$string = get_ascii($string);
    $string = preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
        array('', '-', ''), htmlspecialchars_decode($string));
    return $string;
}
function replace($string) {
	$string = get_ascii($string);
    $string = preg_replace(array('/[^a-zA-Z0-9 -]/', '/[ -]+/', '/^-|-$/'),
        array('', '-', ''), htmlspecialchars_decode($string));
    return $string;
}
function get_ascii($str) {

	$chars = array(

		'a'	=>	array('ấ','ầ','ẩ','ẫ','ậ','Ấ','Ầ','Ẩ','Ẫ','Ậ','ắ','ằ','ẳ','ẵ','ặ','Ắ','Ằ','Ẳ','Ẵ','Ặ','á','à','ả','ã','ạ','â','ă','Á','À','Ả','Ã','Ạ','Â','Ă'),

		'e' 	=>	array('ế','ề','ể','ễ','ệ','Ế','Ề','Ể','Ễ','Ệ','é','è','ẻ','ẽ','ẹ','ê','É','È','Ẻ','Ẽ','Ẹ','Ê'),

		'i'	=>	array('í','ì','ỉ','ĩ','ị','Í','Ì','Ỉ','Ĩ','Ị'),

		'o'	=>	array('ố','ồ','ổ','ỗ','ộ','Ố','Ồ','Ổ','Ô','Ộ','ớ','ờ','ở','ỡ','ợ','Ớ','Ờ','Ở','Ỡ','Ợ','ó','ò','ỏ','õ','ọ','ô','ơ','Ó','Ò','Ỏ','Õ','Ọ','Ô','Ơ'),

		'u'	=>	array('ứ','ừ','ử','ữ','ự','Ứ','Ừ','Ử','Ữ','Ự','ú','ù','ủ','ũ','ụ','ư','Ú','Ù','Ủ','Ũ','Ụ','Ư'),

		'y'	=>	array('ý','ỳ','ỷ','ỹ','ỵ','Ý','Ỳ','Ỷ','Ỹ','Ỵ'),

		'd'	=>	array('đ','Đ'),

	);

	foreach ($chars as $key => $arr) 

		foreach ($arr as $val)

			$str = str_replace($val,$key,$str);

	return $str;

}
function clean_feed($input) {
	$original = array("<", ">", "&", '"');
	$replaced = array("&lt;", "&gt;", "&amp;", "&quot;");
	$newinput = str_replace($original, $replaced, $input);
	return $newinput;
}
$query_film = $mysql->query("SELECT film_id,film_name,film_slug FROM ".DATABASE_FX."film ORDER BY film_time_update DESC");
$query_cat = $mysql->query("SELECT cat_id,cat_name_ascii,cat_name_key FROM ".DATABASE_FX."cat WHERE cat_child= 0 AND cat_type = 0 ORDER BY cat_id ASC");
$query_country = $mysql->query("SELECT country_id,country_name_ascii,country_name_key FROM ".DATABASE_FX."country ORDER BY country_id ASC");
$query_tags = $mysql->query("SELECT tag_id,tag_name,tag_name_kd FROM ".DATABASE_FX."tags ORDER BY tag_view ASC");
$query_actor = $mysql->query("SELECT actor_id,actor_name_kd FROM ".DATABASE_FX."dienvien ORDER BY RAND()");
header("Content-Type: text/xml; charset=utf-8");
$time	= date('Y-m-d H:i:s \G\M\T',time());
$rss = "<?xml version=\"1.0\" encoding=\"utf-8\"?>\r\n";
$rss .= "<?xml-stylesheet href=\"sitemap.xsl\" type=\"text/xsl\"?>\r\n";
$rss .= "<urlset xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\" xsi:schemaLocation=\"http://www.sitemaps.org/schemas/sitemap/0.9 http://www.sitemaps.org/schemas/sitemap/0.9/sitemap.xsd\" xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\r\n";
$rss .= "<url>\r\n";
$rss .= "<loc>".$web_link."/</loc>\r\n";
$rss .= "<changefreq>daily</changefreq>\r\n";
$rss .= "<priority>1.0</priority>\r\n";
$rss .= "<lastmod>".date('c',time())."</lastmod>\r\n";
$rss .= "</url>\r\n";
while ($row = $query_film->fetch(PDO::FETCH_ASSOC)){
	$rss .= "<url>\r\n";
        $rss .= "<loc>".$web_link."/phim/".clean_feed($row['film_slug'])."-".$row['film_id']."/</loc>\r\n";
	$rss .= "<changefreq>daily</changefreq>\r\n";
	$rss .= "<priority>0.6</priority>\r\n";
	$rss .= "<lastmod>".date('c',time())."</lastmod>\r\n";
	$rss .= "</url>\r\n\r\n";
        $rss .= "<url>\r\n";
        $rss .= "<loc>".$web_link."/phim/".clean_feed(strtolower(replace($row['film_name'])))."-".$row['film_id']."/xem-phim.html</loc>\r\n";
	$rss .= "<changefreq>daily</changefreq>\r\n";
	$rss .= "<priority>0.6</priority>\r\n";
	$rss .= "<lastmod>".date('c',time())."</lastmod>\r\n";
	$rss .= "</url>\r\n\r\n";
}
while ($rs = $query_actor->fetch(PDO::FETCH_ASSOC))
{
	$film_link = $web_link.'/dien-vien/'.replace($rs['actor_name_kd']).'.html';
	$rss .= "   <url>\r\n";
	$rss .= "		<loc>".$film_link."</loc>\r\n";
	$rss .= "   		<changefreq>daily</changefreq>\r\n";
	$rss .= "   		<priority>0.9</priority>\r\n";
	$rss .= "   		<lastmod>".date('c',time())."</lastmod>\r\n";
	$rss .= "   </url>\r\n";
}
while ($rs = $query_cat->fetch(PDO::FETCH_ASSOC))
{
	$film_link = $web_link.'/the-loai/'.replace($rs['cat_name_key']).'/';
	$rss .= "   <url>\r\n";
	$rss .= "		<loc>".$film_link."</loc>\r\n";
	$rss .= "   		<changefreq>daily</changefreq>\r\n";
	$rss .= "   		<priority>0.8</priority>\r\n";
	$rss .= "   		<lastmod>".date('c',time())."</lastmod>\r\n";
	$rss .= "   </url>\r\n";
}
while ($rs = $query_country->fetch(PDO::FETCH_ASSOC))
{
	$film_link = $web_link.'/quoc-gia/'.replace($rs['country_name_key']).'/';
	$rss .= "   <url>\r\n";
	$rss .= "		<loc>".$film_link."</loc>\r\n";
	$rss .= "   		<changefreq>daily</changefreq>\r\n";
	$rss .= "   		<priority>0.9</priority>\r\n";
	$rss .= "   		<lastmod>".date('c',time())."</lastmod>\r\n";
	$rss .= "   </url>\r\n";
}
$rss .= "</urlset>";
echo $rss;
file_put_contents('sitemap.xml',$rss);
?>