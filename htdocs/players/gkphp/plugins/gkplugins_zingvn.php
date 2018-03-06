<?php
/*
© 2015 gkplugins 
Date : 19 Nov 2015
*/
require_once("gkpluginsModel.php");
class gkplugins_zingvn extends gkpluginsModel {
	//==========
	private $defaultQuality = "360p";//360p,480p,720p,1080p
	//==========
	
	private $getOriginalFail;
	private $getNormalFail;
	public function gkplugins_zingvn(){
		
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
		if(strpos($link,"http://mp3.zing.vn/")!==false || strpos($link,"http://tv.zing.vn/")!==false){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		$link = $this->requestLink;
		$dta = $this->getData($link);
		if(strpos($link,"://mp3.zing.vn")!==false){
			$this->getMP3($dta);
		}else{
			$this->getTV($dta);
		}
	}
	
	private function getData($link){
		return $this->get_curl(array("url"=>$link,"showHeader"=>true,"encoding"=>"gzip, deflate","agent"=>"Mozilla/5.0 (Windows NT 6.3; rv:36.0) Gecko/20100101 Firefox/36.0"));
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
	
	private function getMP3($dta){
		try{
			$linkxml = explode('xmlURL=',$dta);
			if(!isset($linkxml[1])){
				throw new Exception();
			}
			$linkxml = explode('&',$linkxml[1]);
			$linkxml = explode('"',$linkxml[0]);
			$linkxml = explode("'",$linkxml[0]);
			$linkxml = $linkxml[0];
		}catch(Exception $e){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		$dta = $this->getData($linkxml);
		
		try{
			$linkS = explode('<source>',$dta);
			if(!isset($linkS[1])){
				$linkS = NULL;
				throw new Exception();
			}
			$linkS = explode('</',$linkS[1]);
			$linkS = $linkS[0];
			$linkS = $this->removeCdata($linkS);
		}catch(Exception $e){}
		
		$linkarr = array();
		if(strpos($dta,'<f1080>')!==false){
			$linki = explode('<f1080>',$dta);
			$linki = explode('</',$linki[1]);
			$linki = $linki[0];
			$linki = $this->removeCdata($linki);
			if(strpos($linki,"://")!==false){
				$label = "1080p";
				$arrcq = array("link"=>$linki,"label"=>$label);
				if($this->defaultQuality==$label){
					$arrcq["default"] = true;
				}
				$linkarr[] = $arrcq;
			}
		}
		if(strpos($dta,'<f720>')!==false){
			$linki = explode('<f720>',$dta);
			$linki = explode('</',$linki[1]);
			$linki = $linki[0];
			$linki = $this->removeCdata($linki);
			if(strpos($linki,"://")!==false){
				$label = "720p";
				$arrcq = array("link"=>$linki,"label"=>$label);
				if($this->defaultQuality==$label){
					$arrcq["default"] = true;
				}
				$linkarr[] = $arrcq;
			}
		}
		if(strpos($dta,'<f480>')!==false){
			$linki = explode('<f480>',$dta);
			$linki = explode('</',$linki[1]);
			$linki = $linki[0];
			$linki = $this->removeCdata($linki);
			if(strpos($linki,"://")!==false){
				$label = "480p";
				$arrcq = array("link"=>$linki,"label"=>$label);
				if($this->defaultQuality==$label){
					$arrcq["default"] = true;
				}
				$linkarr[] = $arrcq;
			}
		}
		if(strpos($dta,'<f360>')!==false){
			$linki = explode('<f360>',$dta);
			$linki = explode('</',$linki[1]);
			$linki = $linki[0];
			$linki = $this->removeCdata($linki);
			if(strpos($linki,"://")!==false){
				$label = "360p";
				$arrcq = array("link"=>$linki,"label"=>$label);
				if($this->defaultQuality==$label){
					$arrcq["default"] = true;
				}
				$linkarr[] = $arrcq;
			}
		}
		if(count($linkarr)==2){
			$linkarr = array_reverse($linkarr);
		}
		if(count($linkarr)==0 && $linkS){
			$linkarr[] = array("link"=>$linkS,"type"=>"mp3");
		}
		
		try{
			$thumb = explode('<cover>',$dta);
			if(!isset($thumb[1])){
				throw new Exception();
			}
			$thumb = explode('</',$thumb[1]);
			$thumb = $thumb[0];
			$thumb = $this->removeCdata($thumb);
			if($thumb){
				$this->requestObj["image"] = $thumb;
			}
		}catch(Exception $e){}
		
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
			$this->pluginsFinish();
		}
	}
	
	private function getTV($dta){
		try{
			if(strpos($dta,'id="mediaId" value="')!==false){
				$zvid = explode('id="mediaId" value="',$dta);
				if(!isset($zvid[1])){
					throw new Exception();
				}
				$zvid = explode('"',$zvid[1]);
				$zvid = $zvid[0];
			}else{
				$zvid = preg_split("/'zvid':\s*'/",$dta);
				if(!isset($zvid[1])){
					throw new Exception();
				}
				$zvid = explode("'",$zvid[1]);
				$zvid = $zvid[0];
			}
		}catch(Exception $e){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		try{
			$thumb = preg_split("/\['poster'\]\s*=\s*\"/",$dta);
			if(!isset($thumb[1])){
				throw new Exception();
			}
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0];
			$thumb = $this->removeCdata($thumb);
			if($thumb){
				$this->requestObj["image"] = $thumb;
			}
		}catch(Exception $e){}
		
		//api key sigup with zing : http://mp3.zing.vn/huong-dan/developer, http://mp3.zing.vn/huong-dan/contact
		$linkE = "http://api.tv.zing.vn/2.0/media/info?api_key=d04210a70026ad9323076716781c223f&media_id=".$zvid;
		$dta = $this->getData($linkE);
		
		$linkarr = array();
		if(strpos($dta,'"Video1080":')!==false){
			$linki = preg_split('/"Video1080":\s*"/',$dta);
			$linki = explode('"',$linki[1]);
			$linki = $linki[0];
			$linki = str_replace("\\u003d","=",$linki);
			$linki = str_replace("\\u0026","&",$linki);
			if(strpos($linki,"://")===false){
				$linki = "http://".$linki;
			}
			$label = "1080p";
			$arrcq = array("link"=>$linki,"label"=>$label);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if(strpos($dta,'"Video720":')!==false){
			$linki = preg_split('/"Video720":\s*"/',$dta);
			$linki = explode('"',$linki[1]);
			$linki = $linki[0];
			$linki = str_replace("\\u003d","=",$linki);
			$linki = str_replace("\\u0026","&",$linki);
			if(strpos($linki,"://")===false){
				$linki = "http://".$linki;
			}
			$label = "720p";
			$arrcq = array("link"=>$linki,"label"=>$label);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if(strpos($dta,'"Video480":')!==false){
			$linki = preg_split('/"Video480":\s*"/',$dta);
			$linki = explode('"',$linki[1]);
			$linki = $linki[0];
			$linki = str_replace("\\u003d","=",$linki);
			$linki = str_replace("\\u0026","&",$linki);
			if(strpos($linki,"://")===false){
				$linki = "http://".$linki;
			}
			$label = "480p";
			$arrcq = array("link"=>$linki,"label"=>$label);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if(strpos($dta,'"file_url":')!==false){
			$linki = preg_split('/"file_url":\s*"/',$dta);
			$linki = explode('"',$linki[1]);
			$linki = $linki[0];
			$linki = str_replace("\\u003d","=",$linki);
			$linki = str_replace("\\u0026","&",$linki);
			if(strpos($linki,"://")===false){
				$linki = "http://".$linki;
			}
			$label = "360p";
			$arrcq = array("link"=>$linki,"label"=>$label);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if(count($linkarr)==2){
			$linkarr = array_reverse($linkarr);
		}
		
		if(count($linkarr)==0){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
		}else{
			if(count($linkarr)==1){
				$this->requestObj["link"] = $linkarr[0]["link"];
			}else{
				$this->requestObj["link"] = $linkarr;
			}
			$this->pluginsFinish();
		}
	}
}
?>