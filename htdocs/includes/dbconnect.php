<?php 
if (!defined('TRUNKSJJ')) die("Hack!");
class TrunksSQL {
    function connect($db_host, $db_username, $db_password, $db_name){
	try {
        $conn = new PDO('mysql:host=localhost;dbname='.$db_name, $db_username, $db_password);  
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $conn;
    } catch(PDOException $e) {
        die ('ERROR: ' . $e->getMessage());
    }   
	
	}
}
class mysql {
    function query($input){
	    global $mysqldb;
            $data = $mysqldb->query($input) or die();
            return $data;			         
	}
	function update($table,$query,$value){
	    global $mysqldb;
            $data = $mysqldb->query("UPDATE ".DATABASE_FX.$table." SET ".$query." WHERE ".$value."") or die();
            return $data;			         
	}
	function delete($table,$query,$value){
	    global $mysqldb;
            $data = $mysqldb->query("DELETE FROM ".DATABASE_FX.$table." WHERE ".$value."") or die();
            return $data;			         
	}
	function insert($table,$query,$value){
	    global $mysqldb;
            $data = $mysqldb->query("INSERT INTO ".DATABASE_FX.$table." (".$query.") VALUES (".$value.")") or die();
            return $data;			         
	}
}

class KZ_Crypt {
    public $_text = '';
    public $_key = 'f_pk_ZingTV_1_@z';
    public $_iv = 'f_iv_ZingTV_1_@z';
    
    public $_result = '';

    public function _encrypt(){
        if($this->_text != ''){
            $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv_size = mcrypt_enc_get_iv_size($cipher);
            if (mcrypt_generic_init($cipher, $this->_key, $this->_iv) != -1){
                $cipherText = mcrypt_generic($cipher,$this->_text);
                mcrypt_generic_deinit($cipher);
                $this->_result = bin2hex($cipherText);
                return true;
            }
        }else{
            return false;
        }
    }
    public function _decrypt(){
        if($this->_text != ''){
            $cipher = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_CBC, '');
            $iv_size = mcrypt_enc_get_iv_size($cipher);
            if(mcrypt_generic_init($cipher, $this->_key, $this->_iv) != -1){
                $cipherText = mdecrypt_generic($cipher,$this->_hexToString($this->_text));
                mcrypt_generic_deinit($cipher);
                $this->_result = $cipherText;
                return true;
            }else{
                return false;
            }
        }
    }
    protected function _hexToString($hex){
        if(!is_string($hex)){
            return null;
        }
        $char = '';
        for($i=0; $i<strlen($hex);$i+=2){
            $char .= chr(hexdec($hex{$i}.$hex{($i+1)}));
        }
        return $char;
    }
}

?>