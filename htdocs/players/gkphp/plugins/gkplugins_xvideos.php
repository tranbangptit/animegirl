<?php
/*
© 2015 gkplugins 
Date : 31 Oct 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_xvideos extends gkpluginsModel {
	//==========
	private $defaultQuality = "480p";//240p,360p,480p
	private $useFlashPlayer = true;//480p is flv, require flash player
	//==========
	
	public function gkplugins_xvideos(){
		
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
		if(strpos($link,"http://www.xvideos.com/")!==false ||
			strpos($link,"http://xvideos.com/")!==false ||
			strpos($link,"http://jp.xvideos.com/")!==false
		){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		$this->requestLink = str_replace("://xvideos.com","://www.xvideos.com",$this->requestLink);
		$link = $this->requestLink;
		$this->startP($link);
	}
	
	private function startP($link){
		$dta = $this->get_curl(array("url"=>$link,"showHeader"=>true));
		$this->loadDatacomplete($dta);
	}
	
	private $linkFLV;
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
			if($linkLoca[0]=="/"){
				$dm = explode("/",$this->requestLink);
				$dm = $dm[2];
				$linkLoca = "http://".$dm.$linkLoca;
			}
			$this->startP($linkLoca);
			return;
		}
		
		try{
			$linkF = explode("flv_url=",$dta);
			if(!isset($linkF[1])){
				throw new Exception();
			}
			$linkF = explode("&",$linkF[1]);
			$linkF = $linkF[0];
			$linkF = urldecode($linkF);
			if($linkF){
				$this->linkFLV = $linkF;
			}
		}catch(Exception $e){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		try{
			$thumb = explode("url_bigthumb=",$dta);
			if(!isset($thumb[1])){
				throw new Exception();
			}
			$thumb = explode("&",$thumb[1]);
			$thumb = $thumb[0];
			$thumb = urldecode($thumb);
			if($thumb){
				$this->requestObj["image"] = $thumb;
			}
		}catch(Exception $e){}
		
		$this->getMp4($this->requestLink);
	}
	
	private function getMp4($link){
		$dta = $this->get_curl(array("url"=>$link,"showHeader"=>true,"agent"=>"Mozilla/5.0 (iPhone; CPU iPhone OS 7_1_1 like Mac OS X) AppleWebKit/537.51.2 (KHTML, like Gecko) CriOS/39.0.2171.50 Mobile/11D201 Safari/9537.53"));
		$this->getMp4Com($dta);
	}
	
	private function getMp4Com($dta){
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
			if($linkLoca[0]=="/"){
				$dm = explode("/",$this->requestLink);
				$dm = $dm[2];
				$linkLoca = "http://".$dm.$linkLoca;
			}
			$this->getMp4($linkLoca);
			return;
		}
		
		$linkMp4Low = NULL;
		$linkmp4High = NULL;
		try{
			$linkMp4Low = explode("mobileReplacePlayerDivTwoQual(",$dta);
			if(!isset($linkMp4Low[1])){
				$linkMp4Low = NULL;
				throw new Exception();
			}
			$linkMp4Low = explode(", '",$linkMp4Low[1]);
			$linkMp4Low = explode("'",$linkMp4Low[1]);
			$linkMp4Low = $linkMp4Low[0];
			$linkmp4High = explode("mobileReplacePlayerDivTwoQual(",$dta);
			$linkmp4High = explode(", '",$linkmp4High[1]);
			if(!isset($linkmp4High[2])){
				$linkmp4High = NULL;
				throw new Exception();
			}
			$linkmp4High = explode("'",$linkmp4High[2]);
			$linkmp4High = $linkmp4High[0];
		}catch(Exception $e){}
		
		$linkarr = array();
		if($this->linkFLV){
			$label = "480p";
			$arrcq = array("link"=>$this->linkFLV,"label"=>$label,"type"=>"flv");
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if($linkmp4High){
			$label = "360p";
			$arrcq = array("link"=>$linkmp4High,"label"=>$label,"type"=>"mp4");
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if($linkMp4Low){
			$label = "240p";
			$arrcq = array("link"=>$linkMp4Low,"label"=>$label,"type"=>"mp4");
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		
		if(count($linkarr)==0){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
		}else{
			if(count($linkarr)==1){
				$this->requestObj["link"] = $linkarr[0]["link"];
				$this->requestObj["type"] = $linkarr[0]["type"];
			}else{
				$this->requestObj["link"] = $linkarr;
			}
			$this->requestObj["startparam"] = "fs";
			if($this->useFlashPlayer){
				$this->requestObj["useFlash"] = true;
			}
			$this->pluginsFinish();
		}
	}
}
?>