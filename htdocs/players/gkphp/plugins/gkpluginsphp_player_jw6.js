function(idDivVideo,objVideo){
	var addPlayer = function(id,a){
		var jwplayerjs = MAIN_URL+"/players/gkphp/jw6/jwplayer.js";
		var jwplayerSwf = MAIN_URL+"/players/gkphp/jw6/jwplayer.flash.swf";
		var jwplayerKey = "PGgFJsWEYKNtcZg83ecIc3A2ps5xjxJ1PeByACleTpY=";
		var jwplayerSkin = "bekle";
		
		var rdid = "jw6playerid"+Math.floor(Math.random()*9999999);
		var dsetupjw = function(){
			var i,j;
			if(jwplayerKey && jwplayerKey!=""){
				jwplayer.key = jwplayerKey;
			}
			var obs = {};
			if(a.list){
				var nlist = [];
				for(i=0;i<a.list.length;i++){
					var nili = {};
					if(a.list[i].link){
						if(a.list[i].link instanceof Array){
							nili.sources = a.list[i].link;
							for(j=0;j<nili.sources.length;j++){
								nili.sources[j].file = nili.sources[j].link;
								delete nili.sources[j].link;
							}
						}else{
							nili.file = a.list[i].link;
						}
					}
					if(a.list[i].type){
						nili.type = a.list[i].type;
					}
					if(a.list[i].image){
						nili.image = a.list[i].image;
					}
					if(a.list[i].title){
						nili.title = a.list[i].title;
					}
					nlist.push(nili);
				}
				obs.playlist = nlist;
				obs.listbar = {position:"right",size:200};
			}else if(a.link){
				if(a.link instanceof Array){
					for(i=0;i<a.link.length;i++){
						a.link[i].file = a.link[i].link;
						delete a.link[i].link;
					}
					obs.sources = a.link;
				}else{
					obs.file = a.link;
				}
			}
			if(a.type){
				obs.type = a.type;
			}
			if(a.image){
				obs.image = a.image;
			}
			if(a.autostart){
				//obs.autostart = a.autostart;
                              obs.autostart = true;
			}
			if(a.startparam){
				obs.startparam = a.startparam;
			}
			if(a.subtitle){
				if(typeof a.subtitle=="string"){
					obs.tracks = [{file:a.subtitle}];
				}else{
					obs.tracks = a.subtitle;
				}
			}
			if(a.subtitles){
				obs.tracks = a.subtitles;
			}
			if(a.thumbnails){
				if(obs.tracks){
					obs.tracks.push({file:a.thumbnails,kind:"thumbnails"});
				}else{
					obs.tracks = [{file:a.thumbnails,kind:"thumbnails"}];
				}
			}
			if(jwplayerSkin && jwplayerSkin!=""){
				obs.skin = jwplayerSkin;
			}
			if(a.useFlash){
				obs.primary = "flash";
			}
			var divm = document.getElementById(id);
			var plW = divm.style.width;
			var plH = divm.style.height;
			plW = plW.split("px").join("");
			plH = plH.split("px").join("");
			obs.flashplayer = jwplayerSwf;
			obs.width = plW;
			obs.height = plH;
			
			jwplayer(rdid).setup(obs);
			var gklistboxName = document.getElementById(id).childNodes[1].getAttribute("name");
			jwplayer(rdid).onPlaylistComplete(function(){var fload;eval('fload='+gklistboxName+'();');if(!fload){return;}fload("next");});
		}
		
		var div = document.getElementById(id);
		div = div.childNodes[0];
		div.innerHTML = "";
		var sjw = document.createElement("script");
		sjw.type = 'text/javascript';
		sjw.src = jwplayerjs;
		
		var divp = document.createElement("div");
		divp.id = rdid;
		div.appendChild(divp);
		
		var itv;
		if(typeof jwplayer=="undefined" || !jwplayer){
			div.appendChild(sjw);
			itv = setInterval(function(){
				if(typeof jwplayer!="undefined" && jwplayer){
					clearInterval(itv);
					dsetupjw();
				}
			},100);
		}else{
			dsetupjw();
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
		
		var oobjf = 'var _rdsetupvar = '+JSON.stringify(a)+';';
		delete a.gklist;
		var gklistboxName = div.getAttribute("name");
		var txtf = "";
		txtf += oobjf;
		txtf += 'function '+gklistboxName+'(){return _rdfidload;}';
		txtf += 'function _rdfid_(a,atstart){if(typeof atstart=="undefined"){atstart=true};var obj = _rdsetupvar.gklist[a.selectedIndex];if(_rdsetupvar.autostart){obj.autostart=_rdsetupvar.autostart};obj.link = a.value;if(atstart){obj.autostart = true;}gkpluginsphp("'+id+'",obj);}';
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
			divPlayer.style.width = mainDiv.style.width;
			divPlayer.style.height = mainDiv.style.height;
			
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