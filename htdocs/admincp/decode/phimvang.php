<?php

Class Phimvang
{
	var $key = "cdfphimvangd62b";

	function keycode($key){
		$mnum = "";
		for($i=0;$i<strlen($key);$i++){
			$mnum .= ord(substr($key,$i,1));
		}
		return $mnum;
	}
	
	function texcode($tc){
		$num = 0;
		$count = 0;

		//0<42
		for($ti=0;$ti<strlen($tc);$ti++){
			$count++;
			$ns = ord(substr($tc,$ti,1));
			$nts = 0;
			for($tj=0;$tj<strlen($ns);$tj++){
				$nts+=substr($ns,$tj,1);
			}
			$ns = $nts.$ns.$nts;
			$num+=$ns*$count;
		}
		$num+=strlen($tc);
		$ktn = 0;
		$ktnc = 0;
		for($gi=0;$gi<strlen($num);$gi++){
			$ktnc+=4;
			$ktn+=(substr($num,$gi,1)*$ktnc+($ktnc*$ktnc));
		}		
		return $ktn;
	}

	function encode($tex){
	
		//9910010211210410510911897110103100545098
		$kcode = $this->keycode($this->key);

		//5396
		$ktex = $this->texcode($tex);

		$dd = 0;
		$hoanchinh = "";
		for($ii=0;$ii<strlen($tex);$ii++){
			$dd+=2;
			
			$aa = ord(substr($tex,$ii,1))*3+16+($dd*$dd)+substr($kcode,$ii%strlen($kcode),1)+$ktex;

			$aa = dechex($aa);

			if(strlen($aa)<=3){$aa = "0".$aa;}
			if(strlen($aa)<=2){$aa = "0".$aa;}
			if(strlen($aa)<=1){$aa = "0".$aa;}
			$hoanchinh.=$aa;
			
		}

		$qktex = $ktex;
		if(strlen($qktex)==3){$qktex="0".$ktex;}
		$hoanchinh = $hoanchinh.$qktex;
		return $hoanchinh;
	}
	
	function decode($str)
	{
		$kcode = $this->keycode($this->key);

		//chia ra từng phần, mỗi phần 4 octect
		$nstr = array();
		$d = 0;
		for ($i=0;$i<strlen($str);++$i)
		{
			$nstr[$d] .= $str[$i];
			if (($i+1)%4==0)
			{
				$nstr[$d] = hexdec($nstr[$d]);
				$d++;
			}
		}
		
		//tách key và chuỗi ra
		$key = dechex($nstr[count($nstr)-1]);
		array_pop($nstr);

		//giải mã từng aa
		$dd = 0;
		$link = array();
		for ($i=0;$i<count($nstr);++$i)
		{
			$dd+=2;
			$a = chr(($nstr[$i]-( 16+ ($dd*$dd)+substr($kcode,$i%strlen($kcode),1)+$key) )/3);
			$link[] = $a;
		}

		return join('', $link);
	}

}


$phimvang = new Phimvang();

?>