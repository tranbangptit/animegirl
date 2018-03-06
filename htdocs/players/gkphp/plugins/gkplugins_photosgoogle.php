<?php
/*
© 2015 gkplugins 
Date : 4 Nov 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_photosgoogle extends gkpluginsModel {
	//==========
	private $useOriginal = false;//original for play by download link
	private $autoChangeMode = true;//if get normal fail => get original, if get original fail => get normal
	private $defaultQuality = "360p";//240p,360p,480p,720p,1080p
	//==========
	
	private $getOriginalFail;
	private $getNormalFail;
	public function gkplugins_photosgoogle(){
		
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
		if(strpos($link,"://photos.google.com/")!==false){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		$link = $this->requestLink;
		$this->startP($link);
	}
	
	private function startP($link){
		$dta = $this->get_curl(array("url"=>$link,"sslverify"=>true,"showHeader"=>true,"agent"=>"Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"));
		$this->loadDatacomplete($dta);
	}
	
	private function loadDatacomplete($dta){
		if(strpos($dta,"404 Not Found")!==false){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		if(strpos($dta,"ocation: ")!==false){
			$linkLoca = explode("ocation: ",$dta);
			$linkLoca = explode("\r",$linkLoca[1]);
			$linkLoca = explode("\n",$linkLoca[0]);
			$linkLoca = $linkLoca[0];
			$this->startP($linkLoca);
			return;
		}
		
		if(strpos($this->requestLink,"/photo/")===false){
			$this->getFolder($dta);
			return;
		}
		
		if($this->useOriginal){
			$this->getOriginal($dta);
		}else{
			$this->getNormal($dta);
		}
	}
	
	private function getNormal($dta){
		try{
			$infoQ = explode(':[[[',$dta);
			if(!isset($infoQ[1])){
				$infoQ = NULL;
				throw new Exception();
			}
			$infoQ = explode(']',$infoQ[1]);
			$infoQ = explode(',"',$infoQ[0]);
			$infoQ = explode('"',$infoQ[2]);
			$infoQ = $infoQ[0];
		}catch(Exception $e){}
		
		if(!$infoQ){
			$this->getNormalFail = true;
			$this->getFail($dta);
			return;
		}
		
		$linkarr = array();
		$arr = explode(",",$infoQ);
		for($i=0;$i<count($arr);$i++){
			$cur = $arr[$i];
			$cur = str_replace("\\u003d","=",$cur);
			$cur = str_replace("\\u0026","&",$cur);
			$linki = explode("url=",$cur);
			$linki = explode("&",$linki[1]);
			$linki = urldecode($linki[0]);
			if(strpos($linki,"itag=")!==false){
				$itag = explode("itag=",$linki);
				$itag = explode("&",$itag[1]);
				$itag = $itag[0];
			}else{
				$itag = explode("=m",$linki);
				$itag = explode("?",$itag[1]);
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
		
		try{
			$thumb = explode("AF_initDataCallback(",$dta);
			if(!isset($thumb[1])){
				$thumb = NULL;
				throw new Exception();
			}
			$thumb = explode(',["',$thumb[1]);
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0]."=w1920-k-no";
		}catch(Exception $e){}
		
		if(count($linkarr)==0){
			$this->getNormalFail = true;
			$this->getFail($dta);
		}else{
			if(count($linkarr)==1){
				$this->requestObj["link"] = $linkarr[0]["link"];
				$this->requestObj["type"] = $linkarr[0]["type"];
			}else{
				$this->requestObj["link"] = $linkarr;
			}
			if($thumb){
				$this->requestObj["image"] = $thumb;
			}
			$this->pluginsFinish();
		}
	}
	
	private function getOriginal($dta){
		try{
			if(strpos($dta,'"76647426":')!==false){
				$linkd = explode('"76647426":',$dta);
				if(!isset($linkd[1])){
					$linkd = NULL;
					throw new Exception();
				}
				$linkd = explode(',"',$linkd[1]);
				$linkd = explode('"',$linkd[1]);
				$linkd = $linkd[0];
			}else{
				$linkd = explode('video.googleusercontent.com/',$dta);
				if(!isset($linkd[1])){
					$linkd = NULL;
					throw new Exception();
				}
				$linkd = explode('"',$linkd[1]);
				$linkd = "https://video.googleusercontent.com/".$linkd[0];
			}
		}catch(Exception $e){}
		
		if(!$linkd){
			$this->getOriginalFail = true;
			$this->getFail($dta);
			return;
		}
		
		try{
			$thumb = explode("AF_initDataCallback(",$dta);
			if(!isset($thumb[1])){
				$thumb = NULL;
				throw new Exception();
			}
			$thumb = explode(',["',$thumb[1]);
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0]."=w1920-k-no";
		}catch(Exception $e){}
		
		$this->requestObj["link"] = $linkd;
		$this->requestObj["type"] = "mp4";
		if($thumb){
			$this->requestObj["image"] = $thumb;
		}
		$this->pluginsFinish();
	}
	
	private function getFolder($dta){
		$link = $this->requestLink;
		$shareid = explode("/share/",$link);
		$shareid = explode("/",$shareid[1]);
		$shareid = explode("?",$shareid[0]);
		$shareid = $shareid[0];
		$key = explode("?key=",$link);
		$key = explode("&",$key[1]);
		$key = $key[0];
		
		try{
			$ct = explode("initDataCallback(",$dta);
			if(!isset($ct[1])){
				throw new Exception();
			}
			$ct = explode("</script>",$ct[1]);
			$ct = $ct[0];
		}catch(Exception $e){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		$list = array();
		
		$fid = explode('return',$ct);
		$fid = explode('["',$fid[1]);
		$fid = explode('"',$fid[1]);
		$fid = $fid[0];
		$fthumb = explode('return',$ct);
		$fthumb = explode('["',$fthumb[1]);
		$fthumb = explode('"',$fthumb[2]);
		$fthumb = $fthumb[0];
		if($fid){
			$item = array("link"=>"https://photos.google.com/share/".$shareid."/photo/".$fid."?key=".$key,"title"=>"1","image"=>$fthumb);
			$list[] = $item;
		}
		
		$firstLink = NULL;
		$arr = explode('"76647426":',$ct);
		for($i=1;$i<count($arr)-1;$i++){
			$itemID = explode(',["',$arr[$i]);
			$itemID = explode('"',$itemID[1]);
			$itemID = $itemID[0];
			$thumb = explode(',["',$arr[$i]);
			$thumb = explode('"',$thumb[2]);
			$thumb = $thumb[0];
			$linki = "https://photos.google.com/share/".$shareid."/photo/".$itemID."?key=".$key;
			if($i==1){
				$firstLink = $linki;
			}
			$linki = $this->encryptLink($linki);
			$item = array("link"=>$linki,"title"=>"".($i+1),"image"=>$thumb);
			$list[] = $item;
		}
		
		if(count($list)==1){
			$this->requestLink = $firstLink;
			$this->startCore();
			return;
		}
		
		if(count($list)==0){
			$this->errorMsg = $this->fileNotFound;
		}else{
			unset($this->requestObj["link"]);
			$list[0]["link"] = $this->encryptLink($list[0]["link"]);
			$this->requestObj["gklist"] = $list;
		}
		$this->pluginsFinish();
	}
	
	private function getFail($dta){
		if(!$this->autoChangeMode || ($this->getNormalFail && $this->getOriginalFail)){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		if(!$this->getOriginalFail){
			$this->getOriginal($dta);
		}else{
			$this->getNormal($dta);
		}
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