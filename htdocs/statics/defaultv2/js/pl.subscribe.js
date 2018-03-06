jQuery(document).ready(function(){
    //-- Khi xác nhận đăng ký theo dõi
	jQuery('a#btn-subscribe').click(function(){
            $('html, body').animate({scrollTop:$("#subscribe-wrapper").offset().top},1000);
	    jQuery('#subscribe-button').slideUp('fast');
            jQuery('#subscribe-form').slideDown('fast');
		return false;
    });
    //-- Khi submit form theo dõi
	jQuery('form#form-film-subscribe').submit(function(){
               
			//--valid
			var email=jQuery.trim(jQuery('#subscribe-email').val());
			var fullname=jQuery.trim(jQuery('#subscribe-fullname').val());
			var verify=jQuery.trim(jQuery('#subscribe-verify').val());
			if(email=="")
			{
                Message("Bạn chưa nhập email của bạn!","danger");
				jQuery('#subscribe-email').focus();
				return false;
			}
			if(fullname=="")
			{
				Message("Bạn chưa nhập tên của bạn!","danger");
				jQuery('#subscribe-fullname').focus();
				return false;
			}
			if(jQuery('#subscribe-verify').val()=="")
			{
				Message("Bạn chưa nhập mã xác nhận!","danger");
				jQuery('#subscribe-verify').focus();
				return false;
			}
			$("#fxloading").css({"display": "block"});
			$.post(MAIN_URL+"/load/subscribe", {
			    subscribe: 1,email: email,fullname: fullname,filmId:filmInfo.filmID,captcha:verify
			},function(e) {
			    if(e == 1){
				    Message("Địa chỉ email không hợp lệ!","danger");
				    jQuery('#subscribe-email').focus();
                                    $("#fxloading").css({"display": "none"});
				}else if(e == 2){
				    Message("Mã xác nhận không chính xác!","danger");
				    jQuery('#subscribe-verify').focus();
                                    $("#fxloading").css({"display": "none"});
				}else if(e == 4){
				    Message("Bạn đã đăng ký theo dõi phim này trước đó!","info");
                                    $("#fxloading").css({"display": "none"});
				}else if(e == 3){
				    Message("Đã có lỗi trong quá trình xử lý yêu cầu!","danger");
                                    $("#fxloading").css({"display": "none"});
				}else{
				    Message("Đăng ký theo dõi phim thành công!","success");
					jQuery('#subscribe-form').slideUp('fast');
					jQuery('#subscribe-wrapper').append(e);// Hiện bảng thông báo hủy theo dõi
					$("#fxloading").css({"display": "none"});
				}
			});
			return false;
	});

         jQuery("#subscribe-wrapper").on("click","a#btn-unsubscribe", function(){
	        $("#fxloading").css({"display": "block"});
			var hash = $(this).attr("data-hash");
            $.post(MAIN_URL+"/load/subscribe", {
			    unsubscribe: 1,filmId:filmInfo.filmID,hash:hash
			},function(e) {
			    if(e == 1){
                    Message("Hủy theo dõi phim thành công!","success");	
					jQuery('#subscribe-unsubscribe').slideUp('fast',function(){
							jQuery(this).remove();
					});
					jQuery('#subscribe-button').slideDown('fast');
					$("#fxloading").css({"display": "none"});
                }else if(e==2){ Message("Rất tiếc, bạn chưa yêu cầu theo dõi phim này!","danger");$("#fxloading").css({"display": "none"});}
			});			
		return false;
    });
});