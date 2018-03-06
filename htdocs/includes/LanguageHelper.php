<?php
/**
* Hiển thị giao diện đa ngôn ngữ của website
* @author Lưu Văn Phong <luuvanphong@outlook.com>
* @link http://phpgo.wordpress.com Tất cả các vấn đề liên quan đến PHP
*/

class LanguageHelper {
    /**
    * Kiểm tra xem user chọn hiển thị ngôn ngữ gì từ Dropdownlist
    * Nếu chọn Tiếng Việt thì hiển thị Tiếng Việt, ngược lại thì hiển thị Tiếng Anh
    * Mặc định ban đầu là Tiếng Việt
    */
    function checkLang()
    {
	    if(isset($_COOKIE['lang']))
        {        
			$lang = $_COOKIE['lang'];
			$_SESSION['lang'] = $lang;
			$lang = strip_tags($lang);
            return "languages/lang.$lang.php";
        }
        else{
        $_SESSION['lang'] = 'vn';
		return "languages/lang.vn.php";
		}
    }
}
?>
