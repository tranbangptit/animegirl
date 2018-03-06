<?php
/*
© 2015 gkplugins 
Date : 14 Dec 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_nhaccuatui extends gkpluginsModel {
	//==========
	private $defaultQuality = "720p";//360p,480p,720p
	//==========
	
	private $getOriginalFail;
	private $getNormalFail;
	public function gkplugins_nhaccuatui(){
		
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
		if(strpos($link,"http://www.nhaccuatui.com/")!==false || strpos($link,"http://v.nhaccuatui.com/")!==false){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		$link = $this->requestLink;
		$this->startP($link);
	}
	
	private function startP($link){
		$dta = $this->getData($link);
		$this->loadDatacomplete($dta);
	}
	
	private function getData($link){
		$dta = $this->get_curl(array("url"=>$link,"showHeader"=>true,"agent"=>"Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"));
		return $dta;
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
		
		try{
			$linkxml = explode('file=',$dta);
			if(!isset($linkxml[1])){
				$linkxml = NULL;
				throw new Exception();
			}
			$linkxml = explode('"',$linkxml[1]);
			$linkxml = explode("'",$linkxml[0]);
			$linkxml = $linkxml[0];
		}catch(Exception $e){}
		
		if(!$linkxml && strpos($this->requestLink,"://v.nhaccuatui.com/")!==false && strpos($this->requestLink,"key=")!==false){
			$key = explode("key=",$this->requestLink);
			$key = explode("&",$key[1]);
			$key = $key[0];
			try{
				$xmlid = explode('id="playVideo_'.$key.'"',$dta);
				if(!isset($xmlid[1])){
					throw new Exception();
				}
				$xmlid = explode('play_key="',$xmlid[1]);
				$xmlid = explode('"',$xmlid[1]);
				$xmlid = $xmlid[0];
				if($xmlid){
					$linkxml = "http://v.nhaccuatui.com/flash/xml?key=".$xmlid;
				}
			}catch(Exception $e){}
		}
		
		if(!$linkxml){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		$dta = $this->getData($linkxml);
		if(strpos($dta,"<recommend>")!==false){
			$rec = explode("<recommend>",$dta);
			$rec = explode("</recommend>",$rec[1]);
			$rec = $rec[0];
			$dta = str_replace($rec,"",$dta);
		}
		
		$list = array();
		if(strpos($dta,"<item>")===false){
			$arr = explode("<track>",$dta);
			for($i=1;$i<count($arr);$i++){
				$item = $this->fetchDataItem($arr[$i]);
				if($item["title"]){
					$item["title"] = $i.". ".$item["title"];
				}
				$list[] = $item;
			}
		}else{
			$arr = explode("<item>",$dta);
			for($i=1;$i<count($arr);$i++){
				$item = $this->fetchDataItem($arr[$i]);
				if($item["title"]){
					$item["title"] = $i.". ".$item["title"];
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
	
	private function fetchDataItem($dta){
		$objItem = NULL;
		try{
			$linkF = explode('<location>',$dta);
			if(!isset($linkF[1])){
				$linkF = NULL;
				throw new Exception();
			}
			$linkF = explode('</',$linkF[1]);
			$linkF = $linkF[0];
			$linkF = $this->removeCdata($linkF);
		}catch(Exception $e){}
		
		try{
			$linkL = explode('<lowquality>',$dta);
			if(!isset($linkL[1])){
				$linkL = NULL;
				throw new Exception();
			}
			$linkL = explode('</',$linkL[1]);
			$linkL = $linkL[0];
			$linkL = $this->removeCdata($linkL);
		}catch(Exception $e){}
		
		try{
			$linkH = explode('<highquality>',$dta);
			if(!isset($linkH[1])){
				$linkH = NULL;
				throw new Exception();
			}
			$linkH = explode('</',$linkH[1]);
			$linkH = $linkH[0];
			$linkH = $this->removeCdata($linkH);
		}catch(Exception $e){}
		
		$linkarr = array();
		if($linkH){
			$label = "720p";
			$arrcq = array("link"=>$linkH,"label"=>$label);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if($linkF){
			$label = "480p";
			$arrcq = array("link"=>$linkF,"label"=>$label);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if($linkL){
			$label = "360p";
			$arrcq = array("link"=>$linkL,"label"=>$label);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if(count($linkarr)==2){
			$linkarr = array_reverse($linkarr);
		}
		
		if(count($linkarr)==0){
			return $objItem;
		}
		
		try{
			$thumb = explode('<image>',$dta);
			if(!isset($thumb[1])){
				$thumb = NULL;
				throw new Exception();
			}
			$thumb = explode('</',$thumb[1]);
			$thumb = $thumb[0];
			$thumb = $this->removeCdata($thumb);
			if($thumb){
				$objItem["image"] = $thumb;
			}
		}catch(Exception $e){}
		
		try{
			$title = explode('<title>',$dta);
			if(!isset($title[1])){
				$title = NULL;
				throw new Exception();
			}
			$title = explode('</',$title[1]);
			$title = $title[0];
			$title = $this->removeCdata($title);
			if($title){
				$objItem["title"] = $title;
			}
		}catch(Exception $e){}
		
		
		if(count($linkarr)==1){
			$objItem["link"] = $linkarr[0]["link"];
		}else{
			$objItem["link"] = $linkarr;
		}
		return $objItem;
	}
	
	private function removeCdata($ct){
		if(strpos($ct,"<![CDATA[")!==false){
			$ct = explode("<![CDATA[",$ct);
			$ct = explode("]]>",$ct[1]);
			$ct = $ct[0];
		}
		$ct = str_replace("&amp;","&",$ct);
		return $ct;
	}
}
?>