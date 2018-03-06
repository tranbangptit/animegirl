<?php
/*
© 2015 gkplugins 
Date : 27 Nov 2015
*/
require_once("gkpluginsModel.php");
require_once("gkpluginsLoader.php");
class gkplugins_docsgoogle extends gkpluginsModel {
	//==========
	private $useOriginal = false;//original for play by download link
	private $useExtensions = false;//get link normal by browser extensions
	private $useIPv6 = false;//get link normal by server ipv6 (require server support php ipv6)
	private $autoChangeMode = true;//if get normal fail => get original, if get original fail => get normal
	private $defaultQuality = "360p";//240p,360p,480p,720p,1080p
	//==========
	
	private $getOriginalFail;
	private $getNormalFail;
	private $downloadFileExtension;
	public function gkplugins_docsgoogle(){
		
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
		if(strpos($link,"://docs.google.com/")!==false || strpos($link,"://drive.google.com/")!==false){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		gkpluginsLoader::$type = "php";
		if($this->useExtensions){
			gkpluginsLoader::$type = "extensions";
		}
		
		$link = $this->requestLink;
		$idd;
		if(strpos($link,"?id=")!==false){
			$idd = explode("?id=",$link);
			$idd = explode("&",$idd[1]);
			$idd = $idd[0];
		}
		if(strpos($link,"/file/d/")!==false){
			$idd = explode("/file/d/",$link);
			$idd = explode("/",$idd[1]);
			$idd = $idd[0];
		}
		if(strpos($link,"/folderview?id=")!==false){
			$this->getFolder();
			return;
		}
		$this->requestLink = "http://sub1.phim3s.net/v3/plugins_player.php?url=https://drive.google.com/file/d/".$idd."/view?pli=1";
		$link = $this->requestLink;
		
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
				$this->loadComplete($resp);
			}
			return;
		}
		
		if($this->useOriginal){
			$this->getOriginal();
		}else{
			$this->getNormal();
		}
	}
	
	private function getNormal(){
		$gkloader = new gkpluginsLoader($this);
		$gkloader->func = base64_encode('function(){var ctQ="";try{if(dta.indexOf("\"fmt_stream_map\",\"")>0){ctQ=dta.split("\"fmt_stream_map\",\"")[1].split("\"")[0];ctQ="\"fmt_stream_map\",\""+ctQ+"\"";} else if(dta.indexOf("fmt_stream_map\":\"")>0){ctQ=dta.split("fmt_stream_map\":\"")[1].split("\"")[0];ctQ="fmt_stream_map\":\""+ctQ+"\"";} else {ctQ=dta.split("&fmt_stream_map=")[1].split("&")[0];ctQ="&fmt_stream_map="+_ctQ+"&";}} catch (err){}try{var status="";var thumb="";var rs="";var title=dta.split("\'title\': \'")[1].split("\'")[0];if(dta.indexOf("\"status\",\"")>0){status=dta.split("\"status\",\"")[1].split("\"")[0];status="\"status\",\""+status+"\"";}if(dta.indexOf("[,\""+title+"\",\"")>0){thumb=dta.split("[,\""+title+"\",\"")[1].split("\"")[0];rs += "[,\""+title+"\",\""+thumb+"\",";}rs += status+",\'title\': \'"+title+"\',"+ctQ;objplugin.response=escape(rs);} catch (err){objplugin.response="error";}sendDataToSv(objplugin);}');
		$gkloader->onComplete = array($this,"loadComplete");
		$gkloader->posComID = "p0";
		$request = array("url"=>$this->requestLink,"sslverify"=>true,"responseEscape"=>true);
		if($this->useIPv6){
			$request["ipv6"] = true;
		}
		$gkloader->load($request);
	}
	
	public function loadComplete($dta){
		if($this->useExtensions){
			$dta = urldecode($dta);
		}
		$link = $this->requestLink;
		$ctQ = NULL;
		try{
			if(strpos($dta,'"fmt_stream_map","')!==false){
				$ctt = explode('"fmt_stream_map","',$dta);
				$ctt = explode('"',$ctt[1]);
				$ctQ = $ctt[0];
			}else if(strpos($dta,'fmt_stream_map":"')!==false){
				$ctt = explode('fmt_stream_map":"',$dta);
				$ctt = explode('"',$ctt[1]);
				$ctQ = $ctt[0];
			}else{
				$ctt = explode('&fmt_stream_map=',$dta);
				if(!isset($ctt[1])){
					throw new Exception();
				}
				$ctt = explode('&',$ctt[1]);
				$ctQ = urldecode($ctt[0]);
			}
		}catch(Exception $e){}
		
		try{
			$title = explode("'title': '",$dta);
			if(!isset($title[1])){
				throw new Exception();
			}
			$title = explode("'",$title[1]);
			$title = $title[0];
			if($title){
				$this->downloadFileExtension = substr($title,-4);
			}
		}catch(Exception $e){}
		
		if(!$ctQ){
			$this->getNormalFail = true;
			$this->getFail();
			return;
		}
		
		$linkarr = array();
		$thumb;
		$status;
		try{
			$ctQ = str_replace("\\/","/",$ctQ);
			$arr = explode(",",$ctQ);
			for($i=0;$i<count($arr);$i++){
				$cur = $arr[$i];
				$cur = str_replace("\\u0026","&",$cur);
				$cur = str_replace("\\u003d","=",$cur);
				$itag;$linki;
				if(strpos($cur,'&url=')!==false){
					$itag = explode("itag=",$cur);
					$itag = explode("&",$itag[1]);
					$itag = $itag[0];
					$linki = explode("&url=",$cur);
					$linki = explode("&",$linki[1]);
					$linki = $linki[0];
				}else{
					$cur = explode("|",$cur);
					$itag = $cur[0];
					$linki = $cur[1];
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
			
			$thumb = explode('[,"'.$title.'","',$dta);
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0];
			$thumb = str_replace("\\u0026","&",$thumb);
			$thumb = str_replace("\\u003d","=",$thumb);
			$status = explode('"status","',$dta);
			$status = explode('"',$status[1]);
			$status = $status[0];
		}catch(Exception $e){}
		
		if(count($linkarr)==0){
			$this->getNormalFail = true;
			$this->getFail();
		}else{
			if(count($linkarr)==1){
				$this->requestObj["link"] = $linkarr[0]["link"];
				$this->requestObj["type"] = $linkarr[0]["type"];
			}else{
				$this->requestObj["link"] = $linkarr;
			}
			if($status){
				$this->requestObj["status"] = $status;
			}
			if($thumb){
				$this->requestObj["image"] = $thumb;
			}
			$this->pluginsFinish();
		}
	}
	
	private function getOriginal(){
		$link = $this->requestLink;
		$idd = explode("/file/d/",$link);
		$idd = explode("/",$idd[1]);
		$idd = $idd[0];
		$linkG = "https://docs.google.com/uc?export=download&confirm=no_antivirus&id=".$idd;
		$dta = $this->get_curl(array("url"=>$linkG,"sslverify"=>true,"showHeader"=>true));
		$ck = NULL;
		$confirm = NULL;
		try{
			$ckc = explode("download_warning_",$dta);
			if(!isset($ckc[1])){
				throw new Exception();
			}
			$ckc = explode(";",$ckc[1]);
			$ckc = $ckc[0];
			$ck = "download_warning_".$ckc;
			$confirm = explode("=",$ck);
			$confirm = $confirm[1];
			$fileExt = explode('class="uc-name-size"',$dta);
			$fileExt = explode('href="',$fileExt[1]);
			$fileExt = explode('>',$fileExt[1]);
			$fileExt = explode('<',$fileExt[1]);
			$fileExt = $fileExt[0];
			if($fileExt){
				$this->downloadFileExtension = substr($fileExt,-4);
			}
		}catch(Exception $e){}
		
		if(!$ck){
			if(strpos($dta,"ocation: ")!==false){
				$this->getDownloadComplete($dta);
			}else{
				$this->getOriginalFail = true;
				$this->getFail();
			}
			return;
		}
		
		$linkG = str_replace("&confirm=no_antivirus&","&confirm=".$confirm."&",$linkG);
		$dta = $this->get_curl(array("url"=>$linkG,"sslverify"=>true,"showHeader"=>true,"cookie"=>$ck));
		if(strpos($dta,"ocation: ")!==false){
			$this->getDownloadComplete($dta);
		}else{
			$this->getOriginalFail = true;
			$this->getFail();
		}
	}
	
	private function getDownloadComplete($dta){
		$linkD = NULL;
		try{
			$linkDownload = explode("ocation: ",$dta);
			if(!isset($linkDownload[1])){
				throw new Exception();
			}
			$linkDownload = explode("\r",$linkDownload[1]);
			$linkDownload = explode("\n",$linkDownload[0]);
			$linkD = $linkDownload[0];
		}catch(Exception $e){}
		
		if(!$linkD){
			$this->originalFalse = true;
			$this->getFail();
			return;
		}
		
		$this->requestObj["link"] = $linkD;
		if($this->downloadFileExtension && $this->downloadFileExtension==".mp3"){
			$this->requestObj["type"] = "mp3";
		}else{
			$this->requestObj["type"] = "mp4";
		}
		$this->pluginsFinish();
	}
	
	private function getFail(){
		if(!$this->autoChangeMode || ($this->getNormalFail && $this->getOriginalFail)){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		if(!$this->getOriginalFail){
			$this->getOriginal();
		}else{
			$this->getNormal();
		}
	}
	
	private function getFolder(){
		$dta = $this->get_curl(array("url"=>$this->requestLink,"sslverify"=>true));
		$arr = explode('id="entry-',$dta);
		$firstLink = NULL;
		$list = array();
		for($i=1;$i<count($arr);$i++){
			$cur = $arr[$i];
			$link = explode('"',$cur);
			$link = $link[0];
			$link = "https://drive.google.com/file/d/".$link."/view?pli=1";
			if($i==1){
				$firstLink = $link;
			}
			$link = $this->encryptLink($link);
			$thumb = explode('"flip-entry-thumb"',$cur);
			$thumb = explode('src="',$thumb[1]);
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0];
			$title = explode('"flip-entry-title">',$cur);
			$title = explode('<',$title[1]);
			$title = $title[0];
			$item = array("link"=>$link,"title"=>$i.". ".$title,"image"=>$thumb);
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