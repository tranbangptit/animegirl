<?php

function get_type($filename) {
	$start = explode(".",$filename);
	$count = count($start)-1;
	return $start[$count];
}

// thu nho anh
function imagesthumb($target, $newcopy, $ext,$w=250,$h=500) {
    list($w_orig, $h_orig) = getimagesize($target);
    $scale_ratio = $w_orig / $h_orig;
    if (($w / $h) > $scale_ratio) {
           $w = $h * $scale_ratio;
    } else {
           $h = $w / $scale_ratio;
    }
    $img = "";
    $ext = strtolower($ext);
    if ($ext == "gif"){ 
    $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
    $img = imagecreatefrompng($target);
    } else { 
    $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    // imagecopyresampled(dst_img, src_img, dst_x, dst_y, src_x, src_y, dst_w, dst_h, src_w, src_h)
    imagecopyresampled($tci, $img, 0, 0, 0, 0, $w, $h, $w_orig, $h_orig);
    if ($ext == "gif"){ 
        @imagegif($tci, $newcopy);
    } else if($ext =="png"){ 
        @imagepng($tci, $newcopy);
    } else { 
        @imagejpeg($tci, $newcopy, 100);
    }
}
// cat anh
function cropimages($target, $newcopy, $ext,$w=300,$h=400) {
    list($w_orig, $h_orig) = getimagesize($target);
    $src_x = ($w_orig / 2) - ($w / 2);
    $src_y = ($h_orig / 2) - ($h / 2);
    $ext = strtolower($ext);
    $img = "";
    if ($ext == "gif"){ 
    $img = imagecreatefromgif($target);
    } else if($ext =="png"){ 
    $img = imagecreatefrompng($target);
    } else { 
    $img = imagecreatefromjpeg($target);
    }
    $tci = imagecreatetruecolor($w, $h);
    imagecopyresampled($tci, $img, 0, 0, $src_x, $src_y, $w, $h, $w, $h);
    if ($ext == "gif"){ 
        imagegif($tci, $newcopy);
    } else if($ext =="png"){ 
        imagepng($tci, $newcopy);
    } else { 
        imagejpeg($tci, $newcopy, 100);
    }
}
function uploadCurl($arr = array(),$path=""){
        if(empty($arr) || empty($path)){ return false; } 
		$curl = curl_init();
        curl_setopt($curl, CURLOPT_URL,$path);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($curl, CURLOPT_POST,true);
        curl_setopt($curl, CURLOPT_POSTFIELDS,$arr);
        $result = curl_exec($curl); 
		curl_close($curl); 
		return $result;
}
// upload ảnh
function ipupload($name,$foder,$fileoner,$array_ext = array('jpg','jpeg','png','gif','JPG','JPEG','PNG','GIF','srt','SRT')) {
	$folderst	=	$foder;
	if($_FILES["$name"]['name']!="") {
		$fileupload			=	NOW.$_FILES["$name"]['name'];
		$file_EXT			=	get_type($fileupload);
		if(!in_array($file_EXT, $array_ext)) {
			return	1;
		}else {
			$fileupload			=	$fileoner.'.'.$file_EXT;
			$uploaddir			=	UPLOAD_DIR."/".$foder."/".$fileupload;
			$uploaddirTHUMB			=	UPLOAD_DIR."/".$foder."/thumb/".$fileupload;
			// tiến hành upload ảnh
			if(@move_uploaded_file ($_FILES["$name"]['tmp_name'],$uploaddir)) {
				
				
				if($folderst == "film") {
					imagesthumb($uploaddir, $uploaddirTHUMB,$file_EXT,193,271);
					watermark($uploaddir, $uploaddir);
					$images	=	UPLOAD_FOLDER."/".$foder."/".$fileupload;
				}
				elseif($folderst == "info") {
				    imagesthumb($uploaddir, $uploaddirTHUMB, $file_EXT,300,126);	
					watermark($uploaddir, $uploaddir);
					$images	=	UPLOAD_FOLDER."/".$foder."/".$fileupload;
				}
				else {
					$images	=	UPLOAD_FOLDER."/".$foder."/".$fileupload;
				}
			
			
			
			}
				return	WEB_URL."/".$images;
		}
		
	}
}

// gắn watermark cho ảnh
function watermark($file, $destination, $overlay = "http://www.phiim.tv/images/watermark.png", $X = 10, $Y = 100){
$watermark =    imagecreatefrompng($overlay);

$source_mime = get_type($file);

if($source_mime == "png"){
$image = imagecreatefrompng($file);
}else if($source_mime == "jpg"){
$image = imagecreatefromjpeg($file);
}else if($source_mime == "gif"){
$image = imagecreatefromgif($file);
}
imagecopy($image, $watermark, imagesx($image)-imagesx($watermark)-10, imagesy($image)-imagesy($watermark)-5, 0, 0, imagesx($watermark), imagesy($watermark));
imagepng($image, $destination);
return $destination;
}
function uploadurl($url,$ipid,$fodernew) {
	if ($url) {
		$folderst	=	$fodernew;
		$name	=	basename($url);
		$fileupload			=	strtolower(substr(strrchr($name, '.'), 1));
		$foder	=	UPLOAD_DIR."/".$folderst."/".$ipid.".".$fileupload;
		$upload = file_put_contents($foder,file_get_contents($url));	
		$uploaddir			=	UPLOAD_DIR."/".$fodernew."/".$ipid.".".$fileupload;
		$uploaddirTHUMB = UPLOAD_DIR."/".$fodernew."/thumb/".$ipid.".".$fileupload;
		if($folderst == "info") {
		    imagesthumb($uploaddir, $uploaddirTHUMB, $fileupload,300,126);	
			watermark($uploaddir, $uploaddir);
        }elseif($folderst == "film"){
		
		imagesthumb($uploaddir, $uploaddir, $fileupload,225,300);	
		watermark($uploaddir, $uploaddir);
		}else{
		watermark($uploaddir, $uploaddir);
		}
		
		$urlshow = WEB_URL."/".UPLOAD_FOLDER."/".$fodernew."/".$ipid.".".$fileupload;
		
	}
	else {$urlshow = '';}
	return $urlshow;
}
function SRTupload($name,$foder,$fileoner,$array_ext = array('srt','SRT')) {
	$folderst	=	$foder;
	if($_FILES["$name"]['name']!="") {
		$fileupload			=	NOW.$_FILES["$name"]['name'];
		$file_EXT			=	get_type($fileupload);
		if(!in_array($file_EXT, $array_ext)) {
			return	1;
		}else {
			$fileupload			=	$fileoner.'.'.$file_EXT;
			$uploaddir			=	UPLOAD_DIR."/".$foder."/".$fileupload;
			
			// tiến hành upload ảnh
			if(@move_uploaded_file ($_FILES["$name"]['tmp_name'],$uploaddir)) {
				
				
				if($folderst == "sub") {
					$images	=	WEB_URL."/".UPLOAD_FOLDER."/".$foder."/".$fileupload;
				}
				else {
					$images	=	WEB_URL."/player/subwcome.srt";
				}
			
			
			
			}
				return $images;
		}
		
	}
}
?>


