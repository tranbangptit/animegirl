<?
function VNIname($str){
	return str_replace(
	Array('ỵ','ỹ','ỷ','ỳ','ý',
	'ự','ữ','ử','ừ','ứ','ư',
	'ụ','ũ','ủ','ù','ú',
	'ợ','ỡ','ở','ờ','ớ','ơ',
	'ộ','ỗ','ổ','ồ','ố','ô',
	'ọ','õ','ỏ','ò','ó',
	'ị','ĩ','ỉ','ì','í',
	'ệ','ễ','ể','ề','ế','ê',
	'ẹ','ẽ','ẻ','è','é',
	'đ',
	'ậ','ẫ','ẩ','ầ','ấ','â',
	'ặ','ẵ','ẳ','ằ','ắ','ă',
	'ạ','ã','ả','à','á',
	'Ỵ','Ỹ','Ỷ','Ỳ','Ý',
	'Ự','Ữ','Ử','Ừ','Ứ','Ư',
	'Ụ','Ũ','Ủ','Ù','Ú',
	'Ợ','Ỡ','Ở','Ờ','Ớ','Ơ',
	'Ộ','Ỗ','Ổ','Ồ','Ố','Ô',
	'Ọ','Õ','Ỏ','Ò','Ó',
	'Ị','Ĩ','Ỉ','Ì','Í',
	'Ệ','Ễ','Ể','Ề','Ế','Ê',
	'Ẹ','Ẽ','Ẻ','È','É',
	'Đ',
	'Ậ','Ẫ','Ẩ','Ầ','Ấ','Â',
	'Ặ','Ẵ','Ẳ','Ằ','Ắ','Ă',
	'Ạ','Ã','Ả','À','Á'),

	Array('y5','y4','y3','y2','y1',
	'u75','u74','u73','u72','u71','u70',
	'u5','u4','u3','u2','u1',
	'o75','o74','o73','o72','o71','o70',
	'o65','o64','o63','o62','o61','o60',
	'o5','o4','o3','o2','o1',
	'i5','i4','i3','i2','i1',
	'e65','e64','e63','e62','e61','e60',
	'e5','e4','e3','e2','e1',
	'd9',
	'a65','a64','a63','a62','a61','a60',
	'a85','a84','a83','a82','a81','a80',
	'a5','a4','a3','a2','a1',
	'Y5','Y4','Y3','Y2','Y1',
	'U75','U74','U73','U72','U71','U70',
	'U5','U4','U3','U2','U1',
	'O75','O74','O73','O72','O71','O70',
	'O65','O64','O63','O62','O61','O60',
	'O5','O4','O3','O2','O1',
	'I5','I4','I3','I2','I1',
	'E65','E64','E63','E62','E61','E60',
	'E5','E4','E3','E2','E1',
	'D9',
	'A65','A64','A63','A62','A61','A60',
	'A85','A84','A83','A82','A81','A80',
	'A5','A4','A3','A2','A1'),
	$str
	);
}


function UVNIname($str){
	return  str_replace(
	Array('y5','y4','y3','y2','y1',
	'u75','u74','u73','u72','u71','u70',
	'u5','u4','u3','u2','u1',
	'o75','o74','o73','o72','o71','o70',
	'o65','o64','o63','o62','o61','o60',
	'o5','o4','o3','o2','o1',
	'i5','i4','i3','i2','i1',
	'e65','e64','e63','e62','e61','e60',
	'e5','e4','e3','e2','e1',
	'd9',
	'a65','a64','a63','a62','a61','a60',
	'a85','a84','a83','a82','a81','a80',
	'a5','a4','a3','a2','a1',
	'Y5','Y4','Y3','Y2','Y1',
	'U75','U74','U73','U72','U71','U70',
	'U5','U4','U3','U2','U1',
	'O75','O74','O73','O72','O71','O70',
	'O65','O64','O63','O62','O61','O60',
	'O5','O4','O3','O2','O1',
	'I5','I4','I3','I2','I1',
	'E65','E64','E63','E62','E61','E60',
	'E5','E4','E3','E2','E1',
	'D9',
	'A65','A64','A63','A62','A61','A60',
	'A85','A84','A83','A82','A81','A80',
	'A5','A4','A3','A2','A1'),
	
	Array('ỵ','ỹ','ỷ','ỳ','ý',
	'ự','ữ','ử','ừ','ứ','ư',
	'ụ','ũ','ủ','ù','ú',
	'ợ','ỡ','ở','ờ','ớ','ơ',
	'ộ','ỗ','ổ','ồ','ố','ô',
	'ọ','õ','ỏ','ò','ó',
	'ị','ĩ','ỉ','ì','í',
	'ệ','ễ','ể','ề','ế','ê',
	'ẹ','ẽ','ẻ','è','é',
	'đ',
	'ậ','ẫ','ẩ','ầ','ấ','â',
	'ặ','ẵ','ẳ','ằ','ắ','ă',
	'ạ','ã','ả','à','á',
	'Ỵ','Ỹ','Ỷ','Ỳ','Ý',
	'Ự','Ữ','Ử','Ừ','Ứ','Ư',
	'Ụ','Ũ','Ủ','Ù','Ú',
	'Ợ','Ỡ','Ở','Ờ','Ớ','Ơ',
	'Ộ','Ỗ','Ổ','Ồ','Ố','Ô',
	'Ọ','Õ','Ỏ','Ò','Ó',
	'Ị','Ĩ','Ỉ','Ì','Í',
	'Ệ','Ễ','Ể','Ề','Ế','Ê',
	'Ẹ','Ẽ','Ẻ','È','É',
	'Đ',
	'Ậ','Ẫ','Ẩ','Ầ','Ấ','Â',
	'Ặ','Ẵ','Ẳ','Ằ','Ắ','Ă',
	'Ạ','Ã','Ả','À','Á'),
	$str
	);

}

function BIGstr($data){
	$data=str_replace("Q","q",$data);
	$data=str_replace("W","w",$data);
	$data=str_replace("E","e",$data);
	$data=str_replace("R","r",$data);
	$data=str_replace("T","t",$data);
	$data=str_replace("Y","y",$data);
	$data=str_replace("U","u",$data);
	$data=str_replace("I","i",$data);
	$data=str_replace("O","o",$data);
	$data=str_replace("P","p",$data);
	$data=str_replace("A","a",$data);
	$data=str_replace("S","s",$data);
	$data=str_replace("D","d",$data);
	$data=str_replace("F","f",$data);
	$data=str_replace("G","g",$data);
	$data=str_replace("H","h",$data);
	$data=str_replace("J","j",$data);
	$data=str_replace("K","k",$data);
	$data=str_replace("L","l",$data);
	$data=str_replace("Z","z",$data);
	$data=str_replace("X","x",$data);
	$data=str_replace("C","c",$data);
	$data=str_replace("V","v",$data);
	$data=str_replace("B","b",$data);
	$data=str_replace("N","n",$data);
	$data=str_replace("M","m",$data);
	return $data;
}

function smallSTR($data){

	$data=str_replace("q","Q",$data);
	$data=str_replace("w","W",$data);
	$data=str_replace("e","E",$data);
	$data=str_replace("r","R",$data);
	$data=str_replace("t","T",$data);
	$data=str_replace("y","Y",$data);
	$data=str_replace("u","U",$data);
	$data=str_replace("i","I",$data);
	$data=str_replace("o","O",$data);
	$data=str_replace("p","P",$data);
	$data=str_replace("a","A",$data);
	$data=str_replace("s","S",$data);
	$data=str_replace("d","D",$data);
	$data=str_replace("f","F",$data);
	$data=str_replace("g","G",$data);
	$data=str_replace("h","H",$data);
	$data=str_replace("j","J",$data);
	$data=str_replace("k","K",$data);
	$data=str_replace("l","L",$data);
	$data=str_replace("z","Z",$data);
	$data=str_replace("x","X",$data);
	$data=str_replace("c","C",$data);
	$data=str_replace("v","V",$data);
	$data=str_replace("b","B",$data);
	$data=str_replace("n","N",$data);
	$data=str_replace("m","M",$data);
	return $data;
}

function UNIstr($str){
	$str=VNIname($str);
	$strR=explode(" ",$str);
	$strINFO="";
	for($i=0; $i<count($strR); $i++) {
	for($j=0; $j<strlen($strR[$i]); $j++) 
	{
	if($j==0)$strINFO.=" ".smallSTR($strR[$i][$j]); 
	else $strINFO.="".BIGstr($strR[$i][$j]);
	}
	}
	return trim(UVNIname($strINFO));
}
?>