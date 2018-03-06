<?php
/*****
* Code by TrunksJj
* YH: imkingst - Email: duynghia95@gmail.com
*****/
ob_start();
session_start();
//error_reporting(E_ALL);
//ini_set('display_errors', 1);
ini_set('memory_limit', '512M'); // increase this if you catch "Allowed memory size..."
define('TRUNKSJJ',true);
include_once('../includes/configurations.php');
include_once('../includes/functions.php');
include_once('../includes/AllTemplates.php');
include_once('../includes/facebook/facebook.php');
$isLogin = checkLogin();
if(!$isLogin){


    if(strpos(URL_LOAD , '?tranfer=') !== false){
        $href = explode("?tranfer=",URL_LOAD);
        $href = $href[1];
    }else{ 
    $href = "http://www.phimle.tv";
    } 
    
    ######### Facebook API Configuration ##########
    $appId = '547998618678011'; //Facebook App ID
    $appSecret = 'd1b21ba7653df567b98561d9066f750c'; // Facebook App Secret
    $homeurl = "http://www.phimle.tv/account/login/facebook/";  //return to home
    $fbPermissions = 'email';  //Required facebook permissions
    //Call Facebook API
    $facebook = new Facebook(array('appId'  => $appId,'secret' => $appSecret));
    $fbuser = $facebook->getUser();
    if(!$fbuser){ // Nếu chưa đăng nhập
	    $fbuser = null;
	    $loginUrl = $facebook->getLoginUrl(array('redirect_uri'=>$homeurl,'scope'=>$fbPermissions));	
            $_SESSION["user_href"] = $href;
   
	    // echo '<a href="'.$loginUrl.'"><img src="images/fb_login.png"></a>'; 	
        header("Location: ".$loginUrl);
    }else{
	    $href = $_SESSION["user_href"];
	    $user_profile = $facebook->api('/me?fields=id,first_name,last_name,email,gender,locale,picture');
		$email = $user_profile['email'];
		$name = $user_profile['first_name'].' '.$user_profile['last_name'];
		$oauth_provider = 'facebook';
		$oauth_uid = $user_profile['id'];
		if($email == ''){
			$email = $oauth_uid.'@phimle.tv';
		}
		$check_fbid 			= get_data('user_id','user','user_fb_oauth_uid',$oauth_uid);
		$check_fbemail 			= get_data('user_id','user','user_email',$email);
		
		if(isset($check_fbid) && isset($check_fbemail)){
		    $arr = $mysqldb->prepare("SELECT user_id,user_name,user_email,user_level,user_fb_oauth_uid FROM ".DATABASE_FX."user WHERE user_fb_oauth_uid = :fbid AND user_email =:email");
            $arr->execute(array('fbid' => $oauth_uid,'email' => $email));
	        $row = $arr->fetch();
			
		        $id = $row['user_id'];
			    $username = $row['user_name'];
			    $userlevel = $row['user_level'];
		        $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $username;
                $_SESSION["user_group"] = $userlevel;
		        
				$mysql->query("UPDATE ".DATABASE_FX."user SET user_fb_oauth_uid = '".$oauth_uid."',user_time_last = '".NOW."' WHERE user_email = '".$email."'");
                setcookie('user_id', $id, time() + (86400 * 30 * 12), "/");
			    unset($_SESSION["user_href"]);
				header("Location: ".$href);
			
		}elseif(isset($check_fbemail) && !$check_fbid){   
		    $arr = $mysqldb->prepare("SELECT user_id,user_name,user_email,user_level,user_fb_oauth_uid FROM ".DATABASE_FX."user WHERE user_email =:email");
            $arr->execute(array('email' => $email));
	        $row = $arr->fetch();
			
		        $id = $row['user_id'];
			    $username = $row['user_name'];
			    $userlevel = $row['user_level'];
		        $_SESSION["user_id"] = $id;
                $_SESSION["user_name"] = $username;
                $_SESSION["user_group"] = $userlevel;
		        
				$mysql->query("UPDATE ".DATABASE_FX."user SET user_fb_oauth_uid = '".$oauth_uid."',user_time_last = '".NOW."' WHERE user_email = '".$email."'");
                setcookie('user_id', $id, time() + (86400 * 30 * 12), "/");
			    unset($_SESSION["user_href"]);
				header("Location: ".$href);
        }else{
		
            $password	=	md5("123456");
		    $mysqldb->query("INSERT INTO ".DATABASE_FX."user (user_name,user_password,user_email,user_time,user_fb_oauth_uid) VALUES ('".$name."','".$password."','".$email."','".NOW."','".$oauth_uid."')");
			
			$arr = $mysqldb->prepare("SELECT user_id,user_name,user_email,user_level,user_fb_oauth_uid FROM ".DATABASE_FX."user WHERE user_email = :email");
            $arr->execute(array('email' => $email));
	        $row = $arr->fetch();
		        $_SESSION["user_id"] = $row['user_id'];
                $_SESSION["user_name"] = $row['user_name'];
                $_SESSION["user_group"] = 1;
		        setcookie('user_id', $row['user_id'], time() + (86400 * 30 * 12), "/");
				unset($_SESSION["user_href"]);
				header("Location: ".$href);
		}
	
}
}else header('Location: '.$web_link.'/account/info'); // end check login

?>