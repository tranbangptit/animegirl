var Vautonext = true;
if (window.jQuery) {
    $(function() {

        $('ul.tabs li.tab-title a').on('click', function(e) {
            $('ul.tabs li.tab-title a').removeClass("active");
            $(this).addClass("active");
            $("div.tabs-content section").removeClass("active");
            var tabId = $(this).attr("data");
            $("div.tabs-content section" + tabId).addClass("active");
            e.presentDefault();
            return false;
        });
        $("#v-embed").on('click', function(e) {
            $("#video-embed").slideToggle(100);
        });
    });
}
var next = $.cookie("player-next");
if (!next) $.cookie("player-next", "0", {
    expires: 365,
    path: '/'
});

function next_act() {
    var next = $.cookie("player-next");
    if (next == 0) {} else {}
}

function next_on() {
    $.cookie("player-next", "0", {
        expires: 365,
        path: '/'
    });
    console.log($.cookie("player-next"));
    $('#tgl-next').addClass('on');
}

function next_off() {
    $.cookie("player-next", "1", {
        expires: 365,
        path: '/'
    });
    console.log($.cookie("player-next"));
    $('#tgl-next').removeClass('on');
}

function next_switch() {
    var next = $.cookie("player-next");
    if (next == 0) {
        next_off();
    } else {
        next_on();
    }
}
jQuery(function($) {
    if (next == 0 || !next) {
        $('#tgl-next').addClass('on');
    } else {
        $('#tgl-next').removeClass('on');
    }
});
function Phimle_videoplayer(url) {
    
    jwplayer.key = 'N8zhkmYvvRwOhz4aTGkySoEri4x+9pQwR7GHIQ==';
    jwplayer('phimletv_player').setup({
        file: url,
        tracks: [{
            file: '',
            label: 'Tiáº¿ng Viá»‡t',
            default: true
        }],
        captions: {
            back: false,
            color: 'ffffff',
            fontsize: 18
        },
        autostart: true,
        width: '100%',
        primary: 'html5',
        skin: 'bekle',
        abouttext: 'PhimLe.Tv player',
        aboutlink: 'http://www.phimle.tv',
        height: '100%',
        events: {
            onComplete: function() {
                var autonext = $.cookie("player-next");
                if (autonext == 0 || !autonext) {
                    $.post(AjaxURL, {
                            nextVideo: 1,
                            vid: vId,
                            vcat: vCat
                        },
                        function(e) {
                            window.location.replace(e);
                        });
                }
            },
            onReady: function (){
		        this.addButton("http://i.imgur.com/t4ameNm.png","Chuyển video kế tiếp",function() {$.post(AjaxURL, {nextVideo: 1,vid: vId,vcat: vCat},function(e) {window.location.replace(e);});}, "button2");
                this.addButton("http://i.imgur.com/NLa2FRE.png","Thích Clip này!",function() {$.post(AjaxURL, {videoLike: 1,videoId: vId},function(c) {
				if(c==1){
				Message("Bạn vui lòng đăng nhập để sử dụng chức năng này!","danger");
				}else if(c==2){
				Message("Video clip này đã có trong BST yêu thích của bạn!","info");
				}else if(c==3){
				Message("Đã thêm Video clip này vào yêu thích của bạn!","success");
				}
				});}, "button3");

	        }
        }
    });
    $('html, body').animate({
        scrollTop: $(".block-title.breadcrumb").offset().top
    }, 1000);
}