<?php
/*
© 2015 gkplugins 
Date : 19 Nov 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_plusgoogle extends gkpluginsModel {
	//==========
	private $defaultQuality = "360p";//240p,360p,480p,720p,1080p
	//==========
	
	public function gkplugins_plusgoogle(){
		
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
		if(strpos($link,"://plus.google.com/")!==false){
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
			$this->requestLink = $linkLoca;
			$this->startP($linkLoca);
			return;
		}
		
		$link = $this->requestLink;
		$pid = NULL;
		if(strpos($link,"pid=")!==false){
			$pid = explode("pid=",$link);
			$pid = explode("&",$pid[1]);
			$pid = $pid[0];
		}else{
			$arrL = explode("?",$link);
			$arrL = explode("/",$arrL[0]);
			$pid = isset($arrL[7])?$arrL[7]:NULL;
			if($pid==""){
				$pid = NULL;
			}
		}
		
		$numA = "102368023";
		try{
			$ct = explode("AF_initDataCallback(",$dta);
			if(!isset($ct[1])){
				throw new Exception();
			}
			$ct = explode(',true,[{"',$ct[1]);
			if(!isset($ct[1])){
				throw new Exception();
			}
			$ct = explode('"',$ct[1]);
			$ct = $ct[0];
			if($ct){
				$numA = $ct;
			}
		}catch(Exception $e){}
		
		$list = array();
		$arr = explode('"'.$numA.'":',$dta);
		for($i=1;$i<count($arr);$i++){
			$cur = $arr[$i];
			if($pid && strpos($cur,'"'.$pid.'"')===false){
				continue;
			}
			$item = $this->fetchDataItem($cur,$arr[$i-1]);
			if($item){
				if(isset($item["title"])){
					$item["title"] = $i.". ".$item["title"];
				}else{
					$item["title"] = "".$i;
				}
				$list[] = $item;
			}
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
	
	private function fetchDataItem($dta,$dtaP){
		$objItem = NULL;
		$linkarr = array();
		
		try{
			$arrp = explode(',"url\\u003d',$dta);
			if(!isset($arrp[1])){
				throw new Exception();
			}
			$arrp = explode('"',$arrp[1]);
			$arrp = explode(",url\\u003d",$arrp[0]);
			if(!$arrp){
				$arrp = array();
			}
			for($i=0;$i<count($arrp);$i++){
				$cur = $arrp[$i];
				$cur = str_replace("\\u003d","=",$cur);
				$cur = str_replace("\\u0026","&",$cur);
				$linki = explode('&',$cur);
				$linki = urldecode($linki[0]);
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
			if(count($linkarr)==2){
				$linkarr = array_reverse($linkarr);
			}
		}catch(Exception $e){}
		
		if(count($linkarr)==0){
			return $objItem;
		}
		
		$objItem = array();
		
		if(count($linkarr)==1){
			$objItem["link"] = $linkarr[0]["link"];
			$objItem["type"] = $linkarr[0]["type"];
		}else{
			$objItem["link"] = $linkarr;
		}
		
		try{
			$thumb = explode(',[[',$dtaP);
			$thumb = $thumb[count($thumb)-1];
			$thumb = explode(',"',$thumb);
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0];
			if($thumb){
				$objItem["image"] = $thumb."=w1920-k-no";
			}
			$title = explode(',"',$dtaP);
			$title = $title[count($title)-1];
			$title = explode('"',$title);
			$title = $title[0];
			if($title && $title!=""){
				$objItem["title"] = $title;
			}
		}catch(Exception $e){}
		
		return $objItem;
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