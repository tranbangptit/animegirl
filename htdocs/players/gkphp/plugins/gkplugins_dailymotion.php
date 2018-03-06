<?php
/*
© 2015 gkplugins 
Date : 11 Nov 2015
*/
require_once("gkpluginsModel.php");
require_once("gkpluginsLoader.php");
class gkplugins_dailymotion extends gkpluginsModel {
	//==========
	private $useExtensions = true;//get link by browser extensions
	private $defaultQuality = "720p";//240p,380p,480p,720p
	//==========
	
	public function gkplugins_dailymotion(){
		
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
		if(strpos($link,"://www.dailymotion.com/")!==false || strpos($link,"://dailymotion.com/")!==false){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		gkpluginsLoader::$type = "php";
		if($this->useExtensions){
			gkpluginsLoader::$type = "extensions";
		}
		
		$posCom = isset($this->requestObj["poscom"])?$this->requestObj["poscom"]:NULL;
		if($posCom){
			$resp = isset($this->requestObj["response"])?$this->requestObj["response"]:NULL;
			unset($this->requestObj["poscom"]);
			unset($this->requestObj["response"]);
			if(isset($this->requestObj["autoCookie"])){
				gkpluginsLoader::loadCookie(isset($this->requestObj["cookie"])?$this->requestObj["cookie"]:NULL,$resp);
				unset($this->requestObj["autoCookie"]);
				unset($this->requestObj["cookie"]);
			}
			if($posCom=="p0"){
				$this->getNormalCom($resp);
			}
			return;
		}
		
		$link = $this->requestLink;
		if(strpos($link,"/playlist/")!==false){
			$this->getList();
		}else{
			$this->getNormal();
		}
	}
	
	private function getNormal(){
		$link = $this->requestLink;
		$req = array();
		$req["url"] = $link;
		$req["showHeader"] = true;
		$req["responseEscape"] = true;
		if(strpos($link,"https://")!==false){
			$req["sslverify"] = true;
		}
		
		$gkloader = new gkpluginsLoader($this);
		$gkloader->func = "ZnVuY3Rpb24oKXt2YXIgbWg9ImVyciI7dHJ5e3ZhciBtZXRhZD1kdGEuc3BsaXQoIlwibWV0YWRhdGFcIjp7IilbMV0uc3BsaXQoIn19KTsiKVswXTttaD1tZXRhZDt9Y2F0Y2goZSl7fW9ianBsdWdpbi5yZXNwb25zZT1taDtzZW5kRGF0YVRvU3Yob2JqcGx1Z2luKTt9";
		$gkloader->onComplete = array($this,"getNormalCom");
		$gkloader->posComID = "p0";
		$gkloader->load($req);
	}
	
	public function getNormalCom($dta){
		if(strpos($dta,"404 Not Found")!==false){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		$infoQ = NULL;
		try{
			$infoQ = explode('"qualities":{',$dta);
			if(!isset($infoQ[1])){
				$infoQ = NULL;
				throw new Exception();
			}
			$infoQ = explode('}]}',$infoQ[1]);
			$infoQ = $infoQ[0];
		}catch(Exception $e){}
		
		if(!$infoQ){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		$linkarr = array();
		$arr = explode("],",$infoQ);
		for($i=0;$i<count($arr);$i++){
			$cur = $arr[$i];
			$cur = str_replace("\\/","/",$cur);
			$label = explode('"',$cur);
			$label = $label[1];
			if($label!="auto"){
				$label .= "p";
			}
			$type = explode('"type":"',$cur);
			$type = explode('"',$type[1]);
			$type = $type[0];
			if($type=="video/mp4"){
				$type = "mp4";
			}else if($type=="application/x-mpegURL"){
				$type = "hls";
			}
			$linki = explode('"url":"',$cur);
			$linki = explode('"',$linki[1]);
			$linki = $linki[0];
			$arrcq = array("link"=>$linki,"label"=>$label,"type"=>$type);
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		if(count($linkarr)>3){
			$linkarr = array_reverse($linkarr);
		}
		
		try{
			$thumb = explode('"poster_url":"',$dta);
			if(!isset($thumb[1])){
				throw new Exception();
			}
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0];
			$thumb = str_replace("\\/","/",$thumb);
			if($thumb){
				$this->requestObj["image"] = $thumb;
			}
		}catch(Exception $e){}
		try{
			$filmstrip = explode('"filmstrip_url":"',$dta);
			if(!isset($filmstrip[1])){
				throw new Exception();
			}
			$filmstrip = explode('"',$filmstrip[1]);
			$filmstrip = $filmstrip[0];
			$filmstrip = str_replace("\\/","/",$filmstrip);
			if($filmstrip){
				$this->requestObj["thumbnails"] = $filmstrip;
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
	
	private $arrListItemID;
	private function getList($page = 1){
		$link = $this->requestLink;
		$idList = explode("/playlist/",$link);
		$idList = explode("_",$idList[1]);
		$idList = $idList[0];
		
		if(!$this->arrListItemID){
			$this->arrListItemID = array();
		}
		
		$dta = $this->get_curl(array("url"=>"https://api.dailymotion.com/playlist/".$idList."/videos?limit=100&page=".$page,"showHeader"=>true,"sslverify"=>true));
		
		if(strpos($dta,'"error"')!==false){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		try{
			$ct = explode('"list":[',$dta);
			if(!isset($ct[1])){
				throw new Exception();
			}
			$ct = explode(']',$ct[1]);
			$ct = $ct[0];
		}catch(Exception $e){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		$arr = explode("{",$ct);
		for($i=1;$i<count($arr);$i++){
			$cur = $arr[$i];
			$id = explode('"id":"',$cur);
			$id = explode('"',$id[1]);
			$id = $id[0];
			$title = explode('"title":"',$cur);
			$title = explode('"',$title[1]);
			$title = $title[0];
			$title = '{"t":"'.$title.'"}';
			$title = json_decode($title,true);
			$title = $title["t"];
			$item = array("id"=>$id,"title"=>$title);
			$this->arrListItemID[] = $item;
		}
		
		$total = explode('"total":',$dta);
		$total = explode(",",$total[1]);
		$total = (int)$total[0];
		$maxcur = $page*100;
		if($total>$maxcur){
			$this->getList($page+1);
			return;
		}
		
		$selectedID = NULL;
		if(strpos($link,"#video=")!==false){
			$selectedID = explode("#video=",$link);
			$selectedID = $selectedID[1];
		}
		
		$firstLink = NULL;
		$list = array();
		for($i=0;$i<count($this->arrListItemID);$i++){
			$id = $this->arrListItemID[$i]["id"];
			$title = $this->arrListItemID[$i]["title"];
			$linki = "http://www.dailymotion.com/video/".$id."_video";
			if($i==0){
				$firstLink = $linki;
			}
			$linki = $this->encryptLink($linki);
			$item = array("link"=>$linki,"title"=>($i+1).". ".$title);
			if($selectedID && $id==$selectedID){
				$item["selected"] = true;
			}
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
			$this->requestObj["gklist"] = $list;
		}
		$this->pluginsFinish();
	}
}
?>