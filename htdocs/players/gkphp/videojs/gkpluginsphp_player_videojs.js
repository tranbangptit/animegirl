function(idDivVideo,objVideo){
	var addPlayer = function(id,a){
		var videojs_js = "videojs/video.js";
		var videojs_css = "videojs/video-js.css";
		var videojs_swf = "videojs/video-js.swf";
		
		var rdid = "videojsplayerid"+Math.floor(Math.random()*9999999);
		var divm = document.getElementById(id);
		var plW = divm.style.width;
		var plH = divm.style.height;
		var txt = '';
		var i;
		var txtautostart = "";
		if(a.autostart){
			txtautostart += ' autoplay';
		}
		txt += '<video id="'+rdid+'" class="video-js vjs-default-skin" controls'+txtautostart+' preload="none" style="width:'+plW+';height:'+plH+';" ';
		if(a.image){
			txt += 'poster="'+a.image+'" ';
		}
		txt += ' data-setup="{}">';
		if(a.link){
			var itype;
			if(a.link instanceof Array){
				for(i=0;i<a.link.length;i++){
					itype = "video/mp4";
					if(a.link[i].type){
						itype = "video/"+a.link[i].type;
					}
					txt += '<source src="'+a.link[i].link+'" type="'+itype+'" />';
				}
			}else{
				itype = "video/mp4";
				if(a.type){
					itype = "video/"+a.type;
				}
				txt += '<source src="'+a.link+'" type="'+itype+'" />';
			}
		}
		if(a.subtitle){
			if(typeof a.subtitle=="string"){
				txt += '<track kind="subtitles" src="'+a.subtitle+'" ></track>';
			}else{
				txt += '<track kind="'+a.subtitle.kind+'" src="'+a.subtitle.file+'" label="'+a.subtitle.label+'"></track>';
			}
		}
		if(a.subtitles){
			for(i=0;i<a.subtitles.length;i++){
				txt += '<track kind="'+a.subtitles[i].kind+'" src="'+a.subtitles[i].file+'" label="'+a.subtitles[i].label+'"></track>';
			}
		}
		txt += '</video>';
		
		var div = document.getElementById(id);
		div = div.childNodes[0];
		div.innerHTML = "";
		var cssjs = document.createElement("link");
		cssjs.type = 'text/css';
		cssjs.rel = 'stylesheet';
		cssjs.href = videojs_css;
		var sjs = document.createElement("script");
		sjs.type = 'text/javascript';
		sjs.src = videojs_js;
		var sjs1 = document.createElement("script");
		sjs1.type = 'text/javascript';
		sjs1.text = 'videojs.options.flash.swf = "'+videojs_swf+'";';
		
		var divp = document.createElement("div");
		divp.id = rdid;
		div.appendChild(divp);
		
		var sjssetup = document.createElement("div");
		sjssetup.style.width = plW;
		sjssetup.style.height = plH;
		sjssetup.innerHTML = txt;
		
		div.appendChild(cssjs);
		div.appendChild(sjssetup);
		
		var itv;
		if(typeof videojs=="undefined" || !videojs){
			div.appendChild(sjs);
			itv = setInterval(function(){
				if(typeof videojs!="undefined" && videojs){
					clearInterval(itv);
					div.appendChild(sjs1);
				}
			},100);
		}else{
			div.appendChild(sjs1);
		}
	}
	
	var addGkListBox = function(id,a){
		var div = document.getElementById(id);
		div = div.childNodes[1];
		var rd = Math.floor(Math.random()*9999999);
		var rdfid = "onchangegklist"+rd;
		var rdfidload = "loadgklistwithtype"+rd;
		var rdfidnext = "gklistnextonoff"+rd;
		var rdseid = "gklist"+rd;
		var rdsetupvar = "gklistsetupvar"+rd;
		var txt = '<select id="_rdseid" name="nexton" onchange="_rdfid_(this)">';
		for(var i=0;i<a.gklist.length;i++){
			var cur = a.gklist[i];
			var selectedop = "";
			if(cur.selected){
				selectedop = ' selected="selected"';
			}
			txt += '<option value="'+cur.link+'"'+selectedop+'>'+cur.title+'</option>';
		}
		txt += '</select>';
		txt += '<input type="button" value="AutoNext: On" onclick="_rdfidnext(this);" />';
		txt = txt.split("_rdseid").join(rdseid);
		txt = txt.split("_rdfid_").join(rdfid);
		txt = txt.split("_rdfidnext").join(rdfidnext);
		
		delete a.gklist;
		var gklistboxName = div.getAttribute("name");
		var txtf = "";
		txtf += 'var _rdsetupvar = '+JSON.stringify(a)+';';
		txtf += 'function '+gklistboxName+'(){return _rdfidload;}';
		txtf += 'function _rdfid_(a,atstart=true){var obj = Object.create(_rdsetupvar);obj.link = a.value;if(atstart){obj.autostart = true;}gkpluginsphp("'+id+'",obj);}';
		txtf += 'function _rdfidnext(bt){var se = document.getElementById("_rdseid");if(se.name=="nexton"){se.setAttribute("name","nextoff");bt.value = "AutoNext: Off";}else{se.setAttribute("name","nexton");bt.value = "AutoNext: On";}}';
		txtf += 'function _rdfidload(a){var atstart=false;var se = document.getElementById("_rdseid");if(a=="begin"){se.selectedIndex = 0;}if(a=="next"){if(se.selectedIndex>=se.length-1){return;}if(se.name=="nextoff"){return;}se.selectedIndex++;atstart=true;}_rdfid_(se,atstart);}';
		txtf += '_rdfidload("run");';
		txtf = txtf.split("_rdseid").join(rdseid);
		txtf = txtf.split("_rdfid_").join(rdfid);
		txtf = txtf.split("_rdfidload").join(rdfidload);
		txtf = txtf.split("_rdfidnext").join(rdfidnext);
		txtf = txtf.split("_rdsetupvar").join(rdsetupvar);
		var jsc = document.createElement("script");
		jsc.type = 'text/javascript';
		jsc.text = txtf;
		div.innerHTML = txt;
		div.appendChild(jsc);
	}
	
	var setup = function(){
		var mainDiv = document.getElementById(idDivVideo);
		var oldct = mainDiv.innerHTML;
		if(mainDiv.getAttribute("gkstatus")!="edited"){
			mainDiv.setAttribute("gkstatus","edited");
			mainDiv.innerHTML = "";
			var divPlayer = document.createElement("div");
			divPlayer.innerHTML = oldct;
			divPlayer.setAttribute("name","player");
			
			var rd = Math.floor(Math.random()*9999999);
			var divGkList = document.createElement("div");
			divGkList.setAttribute("name","gklistbox"+rd);
			mainDiv.appendChild(divPlayer);
			mainDiv.appendChild(divGkList);
		}
		if(objVideo.gklist){
			addGkListBox(idDivVideo,objVideo);
		}else{
			addPlayer(idDivVideo,objVideo);
		}
	}
	
	setup();
}
/* © 2015 gkplugins */