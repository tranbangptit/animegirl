<?php
/*
© 2015 gkplugins 
Date : 4 Nov 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_picasaweb extends gkpluginsModel {
	//==========
	private $defaultQuality = "360p";//240p,360p,480p,720p,1080p
	//==========
	
	public function gkplugins_picasaweb(){
		
	}
	
	public function beginPlugins(){
		$this->requestLink = isset($this->requestObj["link"])?$this->requestObj["link"]:NULL;
		if(is_string($this->requestLink) && $this->validLink($this->requestLink)){
			$this->startCore();
		}else{
			$this->pluginsFinish();
		}
	}
	
	private function validLink($link){
		$rs = false;
		if(strpos($link,"://picasaweb.google.com/")!==false){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		$link = $this->requestLink;
		$dta = $this->get_curl(array("url"=>$link,"sslverify"=>true,"agent"=>"Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"));
		
		$text = $dta;
		$CT = $text;
		if(strpos($link,"/lh/photo/")===false && strpos($link,"#")===false){
			$this->getAlbum($text);
			return;
		}
		
		$objItem = NULL;
		try{
			if(strpos($link,"#")!==false){
				$idItemAlbum = explode("#",$link);
				$idItemAlbum = $idItemAlbum[1];
				$m1 = '"gphoto$id":"'.$idItemAlbum;
				$CT = explode($m1,$text);
				if(!isset($CT[1])){
					throw new Exception();
				}
				$CT = explode('"ccOverride"',$CT[1]);
				$CT = $CT[0];
			}else{
				$CT = explode('"gd$kind":"photos#photo"',$text);
				if(!isset($CT[1])){
					throw new Exception();
				}
				$CT = explode('"ccOverride"',$CT[1]);
				$CT = $CT[0];
			}
			$objItem = $this->fetchDataItem($CT);
		}catch(Exception $e){}
		
		if(!$objItem){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		unset($objItem["title"]);
		$this->requestObj = $objItem;
		$this->pluginsFinish();
	}
	
	private function fetchDataItem($dta){
		$objItem = NULL;
		$linkarr = array();
		try{
			$arrp = explode('content":[{',$dta);
			if(!isset($arrp[1])){
				throw new Exception();
			}
			$arrp = explode('thumbnail":[{',$arrp[1]);
			$arrp = explode(',{"url":"',$arrp[0]);
			for($i=1;$i<count($arrp);$i++){
				$cur = $arrp[$i];
				$linki = explode('"',$cur);
				$linki = $linki[0];
				$itag;
				if(strpos($linki,"itag=")!==false){
					$itag = explode('itag=',$linki);
					$itag = explode('&',$itag[1]);
					$itag = $itag[0];
				}else{
					$itag = explode('=m',$linki);
					$itag = explode('?',$itag[1]);
					$itag = $itag[0];
				}
				$infoQ = $this->itagMap($itag);
				if(!$infoQ["quality"]){
					continue;
				}
				$label = $infoQ["quality"]."p";
				$type = $infoQ["type"];
				$arrcq = array("link"=>$linki,"label"=>$label,"type"=>$type);
				if($this->defaultQuality==$label){
					$arrcq["default"] = true;
				}
				$linkarr[] = $arrcq;
			}
			$linkarr = array_reverse($linkarr);
		}catch(Exception $e){}
		
		if(count($linkarr)==0){
			return $objItem;
		}
		if(count($linkarr)==2){
			$linkarr = array_reverse($linkarr);
		}
		
		$objItem = array();
		
		if(count($linkarr)==1){
			$objItem["link"] = $linkarr[0]["link"];
			$objItem["type"] = $linkarr[0]["type"];
		}else{
			$objItem["link"] = $linkarr;
		}
		
		try{
			$thumb = explode('content":[{"url":"',$dta);
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0];
			if($thumb){
				$objItem["image"] = $thumb;
			}
			$title = explode('"title":"',$dta);
			$title = explode('"',$title[1]);
			$title = $title[0];
			if($title){
				$objItem["title"] = $title;
			}
		}catch(Exception $e){}
		
		if(strpos($dta,'"videostatus":"')!==false){
			$status = explode('"videostatus":"',$dta);
			$status = explode('"',$status[1]);
			$status = $status[0];
			$objItem["status"] = $status;
		}
		return $objItem;
	}
	
	private function getAlbum($dta){
		$arr = explode('"gd$kind":"photos#photo"',$dta);
		$list = array();
		for($i=1;$i<count($arr);$i++){
			$item = $this->fetchDataItem($arr[$i]);
			if($item["title"]){
				$item["title"] = $i.". ".$item["title"];
			}
			$list[] = $item;
		}
		if(count($list)==1){
			unset($list[0]["title"]);
			$this->requestObj = $list[0];
			$this->pluginsFinish();
			return;
		}
		
		if(count($list)==0){
			$this->errorMsg = $this->fileNotFound;
		}else{
			unset($this->requestObj["link"]);
			$this->requestObj["list"] = $list;
		}
		$this->pluginsFinish();
	}
	
	private function itagMap($itag){
		$itag = (int)$itag;
		$quality = NULL;
		$type = NULL;
		
		//flv
		if($itag==5){
			$quality = 240;
			$type = "flv";
		}else if($itag==34){
			$quality = 360;
			$type = "flv";
		}else if($itag==35){
			$quality = 480;
			$type = "flv";
		}else
		
		//mp4
		if($itag==18){
			$quality = 360;
			$type = "mp4";
		}if($itag==59){
			$quality = 480;
			$type = "mp4";
		}else if($itag==22){
			$quality = 720;
			$type = "mp4";
		}else if($itag==37){
			$quality = 1080;//1920 x 1080
			$type = "mp4";
		}else if($itag==38){
			$quality = 1080;//2048 x 1080
			$type = "mp4";
		}else
		
		//webm
		if($itag==43){
			$quality = 360;
			$type = "webm";
		}else if($itag==44){
			$quality = 480;
			$type = "webm";
		}else if($itag==45){
			$quality = 720;
			$type = "webm";
		}else if($itag==46){
			$quality = 1080;
			$type = "webm";
		}
		
		return array("quality"=>$quality,"type"=>$type);
	}
}
?>