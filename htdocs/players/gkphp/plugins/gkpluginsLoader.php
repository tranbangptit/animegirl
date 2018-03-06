<?php
class gkpluginsLoader {
	public static $type;
	public $typePhpDebug;
	public $onComplete;
	public $posComID;
	public $func;
	public $autoCookie;
	public $browserExtensionsAllow = "chrome,firefox,opera";
	private $objPlugin;
	private static $objCookie;
	
	
	public function gkpluginsLoader($plugin){
		$this->objPlugin = $plugin;
	}
	
	public function load($req){
		if(!isset($req["method"])){
			$req["method"] = "GET";
		}
		if($this->autoCookie){
			$req["showHeader"] = true;
		}
		
		$rsType = self::$type;
		if($rsType=="extensions" && !$this->checkBrowserExtensionsAllow()){
			$this->objPlugin->errorMsg = "please view on desktop browser ".$this->browserExtensionsAllow;
			$this->objPlugin->pluginsFinish();
			return;
		}
		
		if(!$this->func){
			$this->func = "ZnVuY3Rpb24oKXtzZW5kRGF0YVRvU3Yob2JqcGx1Z2luKTt9";
		}
		
		if($rsType=="flash"){
			$this->loadByFlash($req);
		}else if($rsType=="extensions"){
			$this->loadByExtensions($req);
		}else if($rsType=="php"){
			$this->loadByPhp($req);
		}
	}
	
	private function checkBrowserExtensionsAllow(){
		$rs = true;
		$isChrome = false;
		$isFirefox = false;
		$isOpera = false;
		$browser = $_SERVER['HTTP_USER_AGENT'];
		if(strpos($browser,"Chrome")!==false && strpos($browser,"OPR/")===false){
			$isChrome = true;
		}
		if(strpos($browser,"Firefox")!==false){
			$isFirefox = true;
		}
		if(strpos($browser,"Chrome")!==false && strpos($browser,"OPR/")!==false){
			$isOpera = true;
		}
		
		if(!$isChrome && !$isFirefox && !$isOpera){
			$rs = false;
		}else{
			if($isChrome && strpos($this->browserExtensionsAllow,"chrome")===false){
				$rs = false;
			}
			if($isFirefox && strpos($this->browserExtensionsAllow,"firefox")===false){
				$rs = false;
			}
			if($isOpera && strpos($this->browserExtensionsAllow,"opera")===false){
				$rs = false;
			}
		}
		return $rs;
	}
	
	private function loadByPhp($req){
		if($this->autoCookie){
			$req["cookie"] = self::cookieToString();
		}
		$data = $this->objPlugin->get_curl($req);
		if($this->autoCookie){
			self::headerToCookie($data);
		}
		if($this->onComplete){
			call_user_func($this->onComplete,$data);
		}
	}
	
	public function loadByNone(){
		$this->setExternal();
		$this->objPlugin->requestObj["requesttype"] = "none";
		$this->objPlugin->pluginsFinish();
	}
	
	private function loadByFlash($req){
		$resEsc = isset($req["responseEscape"])?$req["responseEscape"]:NULL;
		$resEscM = isset($req["responseEscapeM"])?$req["responseEscapeM"]:NULL;
		$resB64 = isset($req["responseB64"])?$req["responseB64"]:NULL;
		if(!$resEsc && !$resEscM && !$resB64){
			$req["responseEscape"] = true;
		}
		$this->setExternal($req);
		$this->objPlugin->requestObj["requesttype"] = "flash";
		$this->objPlugin->pluginsFinish();
	}
	
	private function loadByExtensions($req){
		if(isset($req['nobody']) && $req['nobody'] && $req['method']=="GET"){
			$req['method'] = "HEAD";
		}
		$this->setExternal($req);
		$this->objPlugin->requestObj["requesttype"] = "extensions";
		$this->objPlugin->pluginsFinish();
	}
	
	private function setExternal($req = NULL){
		$this->objPlugin->requestObj["func"] = $this->func;
		if($this->posComID){
			$this->objPlugin->requestObj["poscom"] = $this->posComID;
		}
		if($this->autoCookie){
			$this->objPlugin->requestObj["autoCookie"] = true;
			$ck = self::cookieToString();
			if($ck!=""){
				$this->objPlugin->requestObj["cookie"] = self::$objCookie;
			}
		}
		if($req){
			$this->objPlugin->requestObj["request"] = $req;
		}
	}
	
	private function getRequestHeaderJava($arr){
		$arrd = array();
		foreach($arr as $key => $value){
			$arrd[] = $key.":".$value;
		}
		$kq = implode(",",$arrd);
		return $kq;
	}
	
	private function getPostField($arr){
		$arrd = array();
		foreach($arr as $key => $value){
			$arrd[] = $key."=".$value;
		}
		$kq = implode("&",$arrd);
		return $kq;
	}
	
	public static function setCookie($name,$value){
		if(!self::$objCookie){
			self::$objCookie = array();
		}
		self::$objCookie[$name] = $value;
	}
	
	public static function getCookie($name){
		return self::$objCookie[$name] = $value;
	}
	
	public static function removeCookie($name){
		unset(self::$objCookie[$name]);
	}
	
	public static function removeAllCookieWithValue($val){
		foreach(self::$objCookie as $key => $value){
			if($value==$val){
				unset(self::$objCookie[$key]);
			}
		}
	}
	
	public static function cookieToEmpty(){
		self::$objCookie = array();
	}
	
	public static function cookieToString(){
		$arrCk = self::$objCookie;
		if(!$arrCk || count($arrCk)==0){
			return "";
		}
		$arr = array();
		foreach($arrCk as $key => $value){
			$arr[] = $key."=".$value;
		}
		return implode("; ",$arr);
	}
	
	public static function loadCookie($curCookie,$header){
		if($curCookie && $curCookie!=""){
			self::$objCookie = $curCookie;
		}
		self::headerToCookie($header);
	}
	
	private static function headerToCookie($header){
		if(!self::$objCookie){
			self::$objCookie = array();
		}
		$ckName = "Set-Cookie: ";
		if(strpos($header,"Set-cookie: ")!==false){
			$ckName = "Set-cookie: ";
		}
		$arrCk = explode($ckName,$header);
		for($i=1;$i<count($arrCk);$i++){
			$cur = $arrCk[$i];
			$cur = explode(";",$cur);
			$cur = explode("\r",$cur[0]);
			$cur = explode("\n",$cur[0]);
			$cur = $cur[0];
			$vt = strpos($cur,"=");
			$cName = substr($cur,0,$vt);
			$cValue = substr($cur,$vt+1);
			self::$objCookie[$cName] = $cValue;
		}
	}
}
/* © 2015 gkplugins */
?>