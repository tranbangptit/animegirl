$("form#form-film-download button").click(function(){
    var a = $("input[name='download-verify']").val();
	a = parseInt(a);
	$.post(MAIN_URL+"/load/download", {downloadLinklist: 1,captcha: a,filmId: filmInfo.filmID },function(e) {if(e == 0){
Message("Mã xác nhận không chính xác!","danger");
}else $("div#download-link-list").html(e);});
    return false;
});