<?php
/*
© gkplugins 
Date : 28 Oct 2015
*/
require_once("gkpluginsModel.php");
require_once("gkpluginsLoader.php");
class gkplugins_mailru extends gkpluginsModel {
	//==========
	private $defaultQuality = "360p";//360p,480p,720p,1080p
	//==========
	
	public function gkplugins_mailru(){
		
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
		if(strpos($link,"://video.mail.ru/")!==false ||
			strpos($link,"://my.mail.ru/")!==false ||
			strpos($link,"://videoapi.my.mail.ru/")!==false
		){
			$rs = true;
		}
		return $rs;
	}
	
	private function startCore(){
		gkpluginsLoader::$type = "extensions";
		
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
				$this->getInfoCom($resp);
			}else if($posCom=="p1"){
				$this->loadDataComplete($resp);
			}else if($posCom=="p2"){
				$this->getUTTCom($resp);
			}
			return;
		}
		
		if(strpos($link,".html")===false){
			$this->getList();
			return;
		}
		
		$gkloader = new gkpluginsLoader($this);
		$gkloader->func = "ZnVuY3Rpb24oKXt2YXIgcmVzPSJlcnIiO3RyeXtpZihkdGEuaW5kZXhPZigiXCJ2aWRlb3NcIjoiKT4wKXtyZXM9ZHRhO31lbHNle3ZhciBmaWxlPWR0YS5zcGxpdCgvIm1ldGFVcmwiOlxzKiIvKVsxXS5zcGxpdCgiXCIiKVswXTtyZXM9IlwibWV0YVVybFwiOiBcIiIrZmlsZSsiXCIiO319Y2F0Y2goZSl7fW9ianBsdWdpbi5yZXNwb25zZT1yZXM7c2VuZERhdGFUb1N2KG9ianBsdWdpbik7fQ==";
		$gkloader->onComplete = array($this,"getInfoCom");
		$gkloader->posComID = "p0";
		$gkloader->load(array("url"=>$link));
	}
	
	public function getInfoCom($dta){
		try{
			$linkCF = explode('"metaUrl": "',$dta);
			if(!isset($linkCF[1])){
				throw new Exception();
			}
			$linkCF = explode('"',$linkCF[1]);
			$linkCF = $linkCF[0];
		}catch(Exception $e){
			if(strpos($dta,'"videos":')!==false){
				$this->loadDataComplete($dta);
			}else{
				$this->errorMsg = $this->fileNotFound;
				$this->pluginsFinish();
			}
			return;
		}
		
		$gkloader = new gkpluginsLoader($this);
		$gkloader->onComplete = array($this,"loadDataComplete");
		$gkloader->func = "ZnVuY3Rpb24oKXtvYmpwbHVnaW4ucmVzcG9uc2U9YnRvYShvYmpwbHVnaW4ucmVzcG9uc2UpO3NlbmREYXRhVG9TdihvYmpwbHVnaW4pO30=";
		$gkloader->posComID = "p1";
		$gkloader->load(array("url"=>$linkCF));
	}
	
	public function loadDataComplete($dta){
		$dta = base64_decode($dta);
		try{
			$ctv = explode('"videos":[',$dta);
			if(!isset($ctv[1])){
				throw new Exception();
			}
			$ctv = explode('}]',$ctv[1]);
			$ctv = $ctv[0];
		}catch(Exception $e){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		try{
			if(strpos($dta,'"posters":')!==false){
				$thumb = explode('"posters":',$dta);
				$thumb = $thumb[1];
				if(strpos($thumb,'"hd":"')!==false){
					$thumb = explode('"hd":"',$thumb);
					$thumb = explode('"',$thumb[1]);
					$thumb = $thumb[0];
				}else{
					$thumb = explode('"sd":"',$thumb);
					$thumb = explode('"',$thumb[1]);
					$thumb = $thumb[0];
				}
			}else{
				$thumb = explode('"poster":"',$dta);
				if(!isset($thumb[1])){
					throw new Exception();
				}
				$thumb = explode('"',$thumb[1]);
				$thumb = $thumb[0];
			}
			if($thumb){
				$this->requestObj["image"] = $thumb;
			}
		}catch(Exception $e){}
		
		$linkarr = array();
		$arr = explode("},{",$ctv);
		for($i=0;$i<count($arr);$i++){
			$cur = $arr[$i];
			$linki = explode('"url":"',$cur);
			$linki = explode('"',$linki[1]);
			$linki = $linki[0];
			$label = explode('"key":"',$cur);
			$label = explode('"',$label[1]);
			$label = $label[0];
			$arrcq = array("link"=>$linki,"label"=>$label,"type"=>"mp4");
			if($this->defaultQuality==$label){
				$arrcq["default"] = true;
			}
			$linkarr[] = $arrcq;
		}
		$linkarr = array_reverse($linkarr);
		
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
			$this->requestObj["startparam"] = "start";
			$this->pluginsFinish();
		}
	}
	
	private function getList(){
		$link = $this->requestLink;
		$req = array("url"=>$link);
		if(strpos($this->requestLink,"https://")!==false){
			$req["sslverify"]  = true;
		}
		$dta = $this->get_curl($req);
		
		$email = NULL;
		try{
			$email = explode('"email": "',$dta);
			if(!isset($email[1])){
				throw new Exception();
			}
			$email = explode('"',$email[1]);
			$email = $email[0];
			if($email==""){
				$email = explode('"email": "',$dta);
				$email = explode('"',$email[2]);
				$email = $email[0];
			}
		}catch(Exception $e){}
		
		if(!$email || $email==""){
			try{
				$email = explode('"journalEmail": "',$dta);
				if(!isset($email[1])){
					throw new Exception();
				}
				$email = explode('"',$email[1]);
				$email = $email[0];
			}catch(Exception $e){}
		}
		
		if(!$email){
			$this->errorMsg = $this->fileNotFound;
			$this->pluginsFinish();
			return;
		}
		
		$idd = explode("/video/",$link);
		$idd = explode("/",$idd[1]);
		$idd = $idd[0];
		$tm = time()*1000+rand(0,999);
		
		$linkG = "http://my.mail.ru/cgi-bin/my/ajax?user=".$email."&ajax_call=1&func_name=video.get_list&mna=&mnb=&encoding=windows-1251&arg_type=album_items&arg_album=".$idd."&arg_html=0&arg_offset=0&arg_limit=500&_=".$tm;
		
		$dta = $this->get_curl(array("url"=>$linkG));
		
		$list = array();
		$arr = explode('"ItemId":',$dta);
		for($i=1;$i<count($arr);$i++){
			$cur = $arr[$i];
			if(strpos($cur,'"Empty":"1"')!==false || strpos($cur,'"DurationFormat":"00:00"')!==false){
				continue;
			}
			$linki = explode('"UrlHtml":"',$cur);
			$linki = explode('"',$linki[1]);
			$linki = $linki[0];
			if($linki[0]=="/"){
				$linki = "http://my.mail.ru".$linki;
			}
			$thumb = explode('"ImageUrlI":"',$cur);
			$thumb = explode('"',$thumb[1]);
			$thumb = $thumb[0];
			$title = explode('"Title":"',$cur);
			$title = explode('"',$title[1]);
			$title = $title[0];
			$item = array("link"=>$linki,"title"=>$i.". ".$title,"image"=>$thumb);
			$list[] = $item;
		}
		
		if(count($list)==1){
			$this->requestLink = $list[0]["link"];
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