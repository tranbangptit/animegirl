if(typeof gkpluginsphp=="undefined"){
var gkpluginsphp = function(mainDivID,objVideo){
	this.urlphp = MAIN_URL+"/players/gkphp/plugins/gkpluginsphp.php";
	this.urlplayer = MAIN_URL+"/players/gkphp/plugins/gkpluginsphp_player_jw6.js";
	this.urlFlashSwf = MAIN_URL+"/players/gkphp/plugins/plugins_player.swf";
	this.msgLoadingPlayer = "<center><img src='"+MAIN_URL+"/players/pl_load.gif' width='100%' height='auto' /></center>";
	this.msgLoadingPlugins = "<center><img src='"+MAIN_URL+"/players/pl_load.gif' width='100%' height='auto' /></center>";
	this.msgInstallExtensions = "1. Click here to install <b>Extensions</b> : <a href='http://gkplugins.com/extensions/' target='_blank'>http://gkplugins.com/extensions/</a><br/>2. Refresh Browser and continue to view.";
	this.dataPlayerFormat;
	
	
	var loadPlugins = function(){
		if(!objVideo){
			responseToDiv("Error init loadPlugins");
			return;
		}
		if(!objVideo.link && !objVideo.gklist && !objVideo.list){
			responseToDiv("empty video link/gklist");
			return;
		}
		
		if(objVideo.link){
			if(objVideo.link!=""){
				loadSingle();
			}else{
				responseToDiv("empty video link");
			}
		}else if(objVideo.gklist){
			if(objVideo.gklist!=""){
				loadGkList();
			}else{
				responseToDiv("empty video gklist");
			}
		}else if(objVideo.list){
			if(objVideo.list!=""){
				loadList();
			}else{
				responseToDiv("empty video list");
			}
		}
	}
	
	var parseResponseFromSv = function(data){
		var objplugin;
		try{
			if((typeof data)=="string"){
				objplugin = JSON.parse(data);
			}else{
				objplugin = data;
			}
		}catch(err){
			responseToDiv("parse data error: "+err);
			return;
		}
		if(objplugin.requesttype){
			loadRequestType(objplugin);
			return;
		}
		if(objplugin.error){
			responseToDiv(objplugin.error);
		}else{
			fetchDataToPlayer(objplugin);
		}
	}
	
	var loadSingle = function(){
		var err = function(reqObj){
			responseToDiv("load error : "+reqObj.url);
		}
		responseToDiv(msgLoadingPlugins);
		var linkdatap = objVideo.link.replace(/&/g, '%26');
		request({method:"POST",url:urlphp,data:"link="+linkdatap},parseResponseFromSv,err);
	}
	
	var sendDataToSv = function(obj){
		var data = JSON.stringify(obj);
		data = btoa(data);
		var err = function(reqObj){
			responseToDiv("load error : "+reqObj.url);
		}
		responseToDiv(msgLoadingPlugins);
		request({method:"POST",url:urlphp,data:"data="+data},parseResponseFromSv,err);
	}
	
	var loadRequestType = function(objplugin){
		if(objplugin.requesttype=="flash"){
			loadRequestTypeFlash(objplugin);
		}else if(objplugin.requesttype=="extensions"){
			loadRequestTypeExtensions(objplugin);
		}else if(objplugin.requesttype=="none"){
			loadRequestTypeNone(objplugin);
		}
	}
	
	var loadRequestTypeNone = function(objplugin){
		var func;
		if(objplugin.func){
			func = atob(objplugin.func);
			func = eval("func="+func);
		}
		delete objplugin.requesttype;
		delete objplugin.request;
		delete objplugin.func;
		if(func){
			func();
		}
	}
	
	var loadRequestTypeFlash = function(objplugin){
		var cInterval,cIntervalE,divnum,responseEscape,responseEscapeM,responseB64;
		var addDivReq = function(dta){
			var txt = '<object id="flashplayer" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" width="1" height="1">';
			txt += '<param name="movie" value="'+urlFlashSwf+'" />';
			txt += '<param name="allowFullScreen" value="true" />';
			txt += '<param name="allowScriptAccess" value="always" />';
			txt += '<param name="FlashVars" value="data='+dta+'" />';
			txt += '<embed name="flashplayer" src="'+urlFlashSwf+'" FlashVars="data='+dta+'" type="application/x-shockwave-flash" allowfullscreen="true" allowScriptAccess="always" width="1" height="1" />';
			txt += '</object>';
			
			var div = document.createElement("div");
			div.id = "gkpluginflash"+divnum;
			div.innerHTML = txt;
			document.body.appendChild(div);
		}
		var deleteDivReq = function(){
			var div1 = document.getElementById("gkpluginflash"+divnum);
			document.body.removeChild(div1);
		}
		var checkResponse = function(){
			var kq = ";;empty;;";
			var txtres = document.getElementById("gkpluginflashres"+divnum);
			if(txtres!=null && (typeof txtres.value)!="undefined"){kq = txtres.value;}
			return kq;
		}
		var deleteResponse = function(){
			var txtres = document.getElementById("gkpluginflashres"+divnum);
			document.body.removeChild(txtres);
		}
		var randomText = function(){
			var len = 10;
			var c = ["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","m","n","o","p","q","r","s","t"];
			var total = c.length
			var kq = ""
			for(var i=0;i<len;i++){
				var numRD = Math.floor(Math.random()*total);
				kq += c[numRD]
			}
			return kq;
		}
		var doAddDivReq = function(reqObj){
			responseEscape = reqObj.responseEscape;
			responseEscapeM = reqObj.responseEscapeM;
			responseB64 = reqObj.responseB64;
			
			var objE = {};
			objE.request = reqObj;
			objE.flashresid = "gkpluginflashres"+divnum;
			var objtxt = JSON.stringify(objE);
			var objB64 = btoa(objtxt);
			addDivReq(objB64);
			
			cInterval = setInterval(checkResponseData,100);
		}
		var checkResponseData = function(){
			var dta = checkResponse();
			if(dta==";;empty;;"){
				return;
			}
			clearInterval(cInterval);
			clearTimeout(cIntervalE);
			deleteResponse();
			deleteDivReq();
			
			if(responseEscape || responseEscapeM){
				dta = unescape(dta);
			}else if(responseB64){
				dta = atob(dta);
			}
			
			var func;
			if(objplugin.func){
				func = atob(objplugin.func);
				func = eval("func="+func);
			}
			delete objplugin.requesttype;
			delete objplugin.request;
			delete objplugin.func;
			objplugin.response = dta;
			if(func){
				func();
			}
		}
		var checkFlashFile = function(){
			var oncom = function(a){};
			var onerr = function(a){
				responseToDiv("not found : "+urlFlashSwf);
			};
			request({method:"GET",url:urlFlashSwf},oncom,onerr);
		}
		var checkFlashAvailable = function(){
			var available = false;
			if(navigator.plugins && navigator.plugins.length>0){
				var type = 'application/x-shockwave-flash';
				var mimeTypes = navigator.mimeTypes;
				if(mimeTypes && mimeTypes[type] && mimeTypes[type].enabledPlugin && mimeTypes[type].enabledPlugin.description){
					var version = mimeTypes[type].enabledPlugin.description;
					available = true;
				}
			}else if(navigator.appVersion.indexOf("Mac")==-1 && window.execScript){
				var version = -1;
				for(var i=0; i<activeXDetectRules.length && version==-1; i++){
					var obj = getActiveXObject(activeXDetectRules[i].name);
					if(!obj.activeXError){
						available = true;
					}
				}
			}
			return available;
		}
		var getPostField = function(ts){
			var m;
			var arr = [];
			for(var i in ts){
				if(!ts[i]){
					arr.push(i);
				}else{
					arr.push(i+"="+ts[i]);
				}
			}
			if(arr.length>0){
				m = arr.join("&");
			}
			return m;
		}
		var getRequestHeader = function(arr){
			var m = {};
			if(!arr){
				return m;
			}
			for(var i=0;i<arr.length;i++){
				var itemh = arr[i];
				m[itemh.name] = itemh.value;
			}
			return m;
		}
		var loadReq = function(){
			divnum = randomText();
			var flashok = checkFlashAvailable();
			if(!flashok){
				responseToDiv("Flash unavailable in browser");
				return;
			}
			doAddDivReq(objplugin.request);
			cIntervalE = setTimeout(checkFlashFile,10000);
		}
		loadReq();
	}
	
	var loadRequestTypeExtensions = function(objplugin){
		var installInterval,cInterval,divnum,responseEscape,responseB64,showHeader;
		var addDivListReq = function(){
			var divlistc = document.getElementById("gkpluginsExtListReq");
			if(divlistc==null){
				var divlist = document.createElement("div");
				divlist.id = "gkpluginsExtListReq";
				divlist.style.display = "none";
				document.body.appendChild(divlist);
			}
		}
		var addDivReq = function(dta){
			var divreq = document.createElement("div");
			divreq.style.display = "none";
			divreq.innerHTML = dta;
			var divlist = document.getElementById("gkpluginsExtListReq");
			divlist.appendChild(divreq);
		}
		var fcheckDivListReqReady = function(){
			var kq = "empty";
			var divlist = document.getElementById("gkpluginsExtListReq");
			if(divlist.title=="ready"){kq = "ok";}
			return kq;
		}
		var checkDivListReqReady = function(){
			var kq = fcheckDivListReqReady();
			if(kq!="ok"){
				clearInterval(cInterval);
				buildGuide();
			}
		}
		var checkResponse = function(id){
			var kq = ";;empty;;";
			var txtres = document.getElementById(id);
			if(txtres!=null && (typeof txtres.value)!="undefined"){kq = txtres.value;}
			return kq;
		}
		var deleteResponse = function(id){
			var txtres = document.getElementById(id);
			document.body.removeChild(txtres);
		}
		var buildGuide = function(){
			responseToDiv(msgInstallExtensions);
		}
		var randomText = function(){
			var len = 10;
			var c = ["0","1","2","3","4","5","6","7","8","9","a","b","c","d","e","f","g","h","i","j","k","m","n","o","p","q","r","s","t"];
			var total = c.length
			var kq = ""
			for(var i=0;i<len;i++){
				var numRD = Math.floor(Math.random()*total);
				kq += c[numRD]
			}
			return kq;
		}
		var doAddDivReq = function(reqObj){
			var agent = reqObj.agent;
			var contentType = reqObj.contentType;
			var referer = reqObj.referer;
			var cookie = reqObj.cookie;
			var encoding = reqObj.encoding;
			var requestHeader = reqObj.requestHeaders;
			if(requestHeader && requestHeader instanceof Array){
				requestHeader = getRequestHeader(requestHeader);
			}
			var postfield = getPostField(reqObj.data);
			if(!agent){
				agent = navigator.userAgent;
			}
			if(!contentType && postfield){
				contentType = "application/x-www-form-urlencoded";
			}
			divnum = "gkpluginsextreqreturnid"+randomText();
			responseEscape = reqObj.responseEscape;
			responseB64 = reqObj.responseB64;
			showHeader = reqObj.showHeader;
			
			var reqH = {};
			reqH["Accept"] = "text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8";
			reqH["Accept-Language"] = "en-us,en;q=0.5";
			reqH["Accept-Charset"] = "ISO-8859-1,utf-8;q=0.7,*;q=0.7";
			reqH["Connection"] = "keep-alive";
			reqH["User-Agent"] = agent;
			
			if(contentType!=null){
				reqH["Content-Type"] = contentType;
			}
			if(referer!=null){
				reqH["Referer"] = referer;
			}
			if(cookie!=null){
				reqH["Cookie"] = cookie;
			}
			if(encoding!=null){
				reqH["Accept-Encoding"] = encoding;
			}
			for(var m in requestHeader){
				reqH[m] = requestHeader[m];
			}
			
			var objRequestGM = {};
			objRequestGM.url = reqObj.url;
			objRequestGM.method = reqObj.method;
			objRequestGM.headers = reqH;
			objRequestGM.extreqid = divnum;
			objRequestGM.nobody = reqObj.nobody;
			if(postfield){
				objRequestGM.data = postfield;
			}
			if(responseEscape){
				objRequestGM.returndtaescape = true;
			}else if(responseB64){
				objRequestGM.returndtab64 = true;
			}
			
			var objRequestGMTxt = JSON.stringify(objRequestGM);
			var objRequestGMTxtB64 = btoa(objRequestGMTxt);
			addDivReq(objRequestGMTxtB64);
			cInterval = setInterval(checkResponseData,100);
		}
		var checkResponseData = function(){
			var dta = checkResponse(divnum);
			if(dta==";;empty;;"){
				return;
			}
			clearInterval(cInterval);
			clearTimeout(installInterval);
			deleteResponse(divnum);
			
			var txtbreakrnrn = "\n\n";
			if(responseEscape){
				dta = unescape(dta);
				txtbreakrnrn = "\r\n\r\n";
			}else if(responseB64){
				dta = atob(dta);
				txtbreakrnrn = "\r\n\r\n";
			}
			
			var dtaHeader;
			var breakrnrn = dta.indexOf(txtbreakrnrn);
			if(breakrnrn>=0){
				dtaHeader = dta.slice(0,breakrnrn);
				if(!showHeader){
					dta = dta.substr(breakrnrn + txtbreakrnrn.length);
				}
			}
			
			var func;
			if(objplugin.func){
				func = atob(objplugin.func);
				func = eval("func="+func);
			}
			delete objplugin.requesttype;
			delete objplugin.request;
			delete objplugin.func;
			objplugin.response = dta;
			if(func){
				func();
			}
		}
		var getPostField = function(ts){
			var m;
			var arr = [];
			for(var i in ts){
				if(!ts[i]){
					arr.push(i);
				}else{
					arr.push(i+"="+ts[i]);
				}
			}
			if(arr.length>0){
				m = arr.join("&");
			}
			return m;
		}
		var getRequestHeader = function(arr){
			var m = {};
			if(!arr){
				return m;
			}
			for(var i=0;i<arr.length;i++){
				var itemh = arr[i];
				m[itemh.name] = itemh.value;
			}
			return m;
		}
		var loadReq = function(){
			addDivListReq();
			installInterval = setTimeout(checkDivListReqReady,1500);
			doAddDivReq(objplugin.request);
		}
		loadReq();
	}
	
	var loadGkList = function(){
		if(objVideo.gklist instanceof Array){
			fetchDataToPlayer(objVideo);
			return;
		}
		var com = function(data){
			var list;
			try{
				list = parseListXML(data);
			}catch(err){
				responseToDiv("parse list file error: "+err);
				return;
			}
			objVideo.gklist = list;
			fetchDataToPlayer(objVideo);
		}
		var err = function(reqObj){
			responseToDiv("load error : "+objVideo.gklist);
		}
		responseToDiv(msgLoadingPlugins);
		request({method:"GET",url:objVideo.gklist},com,err);
	}
	
	var parseListXML = function(data){
		var list = [];
		var i;
		var xmlDoc = parseXML(data);
		xmlDoc = xmlDoc.documentElement;
		var trackList;
		for(i=0;i<xmlDoc.childNodes.length;i++){
			if(xmlDoc.childNodes[i].nodeType==1){
				trackList = xmlDoc.childNodes[i];
				break;
			}
		}
		for(i=0;i<trackList.childNodes.length;i++){
			var tract = trackList.childNodes[i];
			if(tract.nodeType!=1){
				continue;
			}
			var trackObj = {};
			for(var j=0;j<tract.childNodes.length;j++){
				var att = tract.childNodes[j];
				if(att.nodeType!=1){
					continue;
				}
				var iName = att.nodeName;
				var iVal = att.childNodes[0].nodeValue;
				if(iName=="location"){
					iName = "link";
				}
				trackObj[iName] = iVal;
			}
			list.push(trackObj);
		}
		return list;
	}
	
	var loadList = function(){
		fetchDataToPlayer(objVideo);
	}
	
	var loadPlayerFormat = function(){
		var div = document.getElementById(mainDivID);
		if(!div){
			console.log("gkpluginsphp error: not exist div id "+mainDivID);
			return;
		}
		if(urlplayer && urlplayer!=""){
			var com = function(data){
				dataPlayerFormat = data;
				loadPlugins();
			}
			var err = function(reqObj){
				responseToDiv("load error : "+urlplayer);
			}
			responseToDiv(msgLoadingPlayer);
			request({method:"GET",url:urlplayer},com,err);
		}else{
			responseToDiv("urlplayer empty");
		}
	}
	
	var fetchDataToPlayer = function(objplugin){
		if(!objVideo.image){
			objVideo.image = objplugin.image;
		}
		delete objplugin.image;
		for(var m in objplugin){
			objVideo[m] = objplugin[m];
		}
		var fplayer;
		eval("fplayer="+dataPlayerFormat);
		return fplayer(mainDivID,objVideo);
	}
	
	var responseToDiv = function(data){
		var div = document.getElementById(mainDivID);
		if(div.getAttribute("gkstatus")=="edited"){
			div = div.childNodes[0];
		}
		var old = div.innerHTML;
		div.innerHTML = data;
		return old;
	}
	
	var request = function(reqObj,onCom,onErr){
		var xhttp;
		if (window.XMLHttpRequest) {
			xhttp = new XMLHttpRequest()
		}else{
			xhttp = new ActiveXObject("Microsoft.XMLHTTP")
		}
		xhttp.onreadystatechange = function(){
			if(xhttp.readyState == 4) {
				if(xhttp.status == 200){
					if(onCom){onCom(xhttp.responseText)}
				}else{
					if(onErr){onErr(reqObj)}
				}
			}
		}
		var async = true;
		if(reqObj.async!=null){
			async = reqObj.async;
		}
		xhttp.open(reqObj.method, reqObj.url, async);
		if(reqObj.data){
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send(reqObj.data);
		}else{
			xhttp.send();
		}
	}
	
	var parseXML = function(val){
		var xmlDoc;
		if(window.DOMParser){
			parser = new DOMParser();
			xmlDoc = parser.parseFromString(val,"text/xml");
		}else{
			xmlDoc = new ActiveXObject("Microsoft.XMLDOM");
			xmlDoc.loadXML(val);
		}
		return xmlDoc ;
	}
	
	loadPlayerFormat();
}
}
/* © 2015 gkplugins */