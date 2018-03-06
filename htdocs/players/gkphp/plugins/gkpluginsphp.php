<?php
$pluginslist = array();
$pluginslist[] = "gkplugins_picasaweb.php";
$pluginslist[] = "gkplugins_dailymotion.php";
$pluginslist[] = "gkplugins_docsgoogle.php";
$pluginslist[] = "gkplugins_mailru.php";
$pluginslist[] = "gkplugins_nhaccuatui.php";
$pluginslist[] = "gkplugins_plusgoogle.php";
$pluginslist[] = "gkplugins_photosgoogle.php";
$pluginslist[] = "gkplugins_xvideos.php";
$pluginslist[] = "gkplugins_zingvn.php";
$domainAllowXHR = array();
$domainAllowXHR[] = "http://localhost";
for($i=0;$i<count($domainAllowXHR);$i++){
	if($domainAllowXHR[$i]=="*"){
		header("Access-Control-Allow-Origin: *");
		break;
	}
	if(isset($_SERVER["HTTP_ORIGIN"]) && $_SERVER["HTTP_ORIGIN"]==$domainAllowXHR[$i]){
		header("Access-Control-Allow-Origin: ".$domainAllowXHR[$i]);
		break;
	}
}
for($i=0;$i<count($pluginslist);$i++){
	if(!file_exists($pluginslist[$i])){
		exit(json_encode(array("error"=>$pluginslist[$i]." does not exist")));
	}
	require_once($pluginslist[$i]);
}

$link = isset($_POST['link'])?$_POST['link']:NULL;
$data = isset($_POST['data'])?$_POST['data']:NULL;

$gkphp;
if($link || $data){
	$gkphp = new gkpluginsphp();
	$gkphp->pluginsList = $pluginslist;
	$gkphp->onComplete = "returnToClient";
	if($link){
		$gkphp->requestLink = $link;
	}
	if($data){
		$gkphp->requestData = $data;
	}
	$gkphp->begin();
}

function returnToClient($obj){
	global $gkphp;
	if($gkphp->errorMsg && $gkphp->errorMsg!=""){
		$rse = array("error"=>$gkphp->errorMsg);
		echo json_encode($rse);
	}else{
		echo json_encode($obj);
	}
}



class gkpluginsphp {
	public $pluginsList;
	public $requestLink;
	public $requestData;
	public $onComplete;
	public $errorMsg;
	
	private $countPluginRun;
	private $curPlugin;
	private $curReqObj;
	
	public function gkpluginsphp(){
		$this->countPluginRun = 0;
	}
	
	public function encryptLink($link){
		return $link;
	}
	
	public function decryptLink($link){
		return $link;
	}
	
	public function begin(){
		if(count($this->pluginsList)==0){
			$this->errorMsg = "no plugin in pluginslist";
			$this->returnToClient();
			return;
		}
		$link = $this->requestLink;
		$data = $this->requestData;
		if($data){
			$data = str_replace(" ","+",$data);
			$data = base64_decode($data);
			$data = json_decode($data,true);
			if(strpos($data["link"],"://")===false){
				$data["link"] = $this->decryptLink($data["link"]);
			}
			$this->curReqObj = $data;
			$this->runPlugins();
		}else if($link){
			$link = str_replace(" ","+",$link);
			if(strpos($link,"://")===false){
				$link = $this->decryptLink($link);
			}
			$this->curReqObj = array("link"=>$link);
			$this->runPlugins();
		}else{
			$this->returnToClient();
		}
	}
	
	private function runPlugins(){
		$this->curPlugin = $this->pluginsList[$this->countPluginRun];
		$this->curPlugin = substr($this->curPlugin,0,-4);
		$this->curPlugin = new $this->curPlugin();
		$this->curPlugin->onFinish = array($this,"onFinish");
		$this->curPlugin->funcEncrypt = array($this,"encryptLink");
		$this->curPlugin->errorMsg = $this->errorMsg;
		$this->curPlugin->requestObj = $this->curReqObj;
		$this->curPlugin->beginPlugins();
	}
	
	public function onFinish(){
		$this->curReqObj = $this->curPlugin->requestObj;
		$this->errorMsg = $this->curPlugin->errorMsg;
		$this->countPluginRun++;
		if($this->countPluginRun>=count($this->pluginsList)){
			$this->returnToClient();
			return;
		}
		$this->runPlugins();
	}
	
	private function returnToClient(){
		call_user_func($this->onComplete,$this->curReqObj);
	}
}
/* © 2015 gkplugins */
?>