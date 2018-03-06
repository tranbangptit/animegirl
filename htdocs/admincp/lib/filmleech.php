<?php
	class filmleech{
		public static function check_replace($url)
		{
			if(strpos($url,'picasaweb.google.com/lh/photo') !== false)
			{
				$html = explode('<div class="gphoto-canonicalscaledimage gphoto-photocaption">',self::curl(str_replace('https','http',$url)));
				$html = explode('htmlCaption',$html[1]);
				$url1 = explode('<p><a href="',$html[0]);
				$url1 = explode('">',$url1[1]);
				$url1 = $url1[0];
				$url2 = explode('/photoid/',$html[0]);
				$url2 = explode('?alt=jsonm',$url2[1]);
				$url2 = $url2[0];
				$url = $url1.'#'.$url2;
			}return $url;
		}
		public static function curl($url){
			$headers = array(
			"User-Agent: googlebot",
			"Content-Type: application/x-www-form-urlencoded",
			"Contect-Type: application/xml",
			"Referer: ".$url,
			);
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
			curl_setopt($ch, CURLOPT_HEADER, 1);
			curl_setopt($ch, CURLOPT_REFERER, $referer);
			curl_setopt($ch, CURLOPT_COOKIE, $cookie );
			curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
			if($var) {
			curl_setopt($ch, CURLOPT_POST, 1);        
			curl_setopt($ch, CURLOPT_POSTFIELDS, $var);
			}
			curl_setopt($ch, CURLOPT_URL,$url);
			return curl_exec($ch);
		}
		public function dielink($url)
		{
			if(strlen($url) == 1)
			{
				echo 'link die';
			}
		}
	}
?>