<?php
if (!defined('TRUNKSJJ_ADMIN')) die("Hacking attempt");
if ($level != 3) {
	echo "Bạn không có quyền vào trang này.";
	exit();
}
$edit_url = 'index.php?act=config';

$inp_arr = array(
		'cf_web_name'	=> array(
			'table'	=>	'cf_web_name',
			'name'	=>	'WEB NAME',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'cf_web_link'	=> array(
			'table'	=>	'cf_web_link',
			'name'	=>	'WEB LINK',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'cf_web_keywords'	=> array(
			'table'	=>	'cf_web_keywords',
			'name'	=>	'WEB KEYS',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'cf_web_des'	=> array(
			'table'	=>	'cf_web_des',
			'name'	=>	'WEB DES',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'cf_site_off'	=> array(
			'table'	=>	'cf_site_off',
			'name'	=>	'WEB CLOSE',
			'type'	=>	'function::acp_site_off::number',
			'can_be_empty'	=> true,
			
		),
		'cf_fanpage_fbid'	=> array(
			'table'	=>	'cf_fanpage_fbid',
			'name'	=>	'APP ID (FB)',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'cf_fanpage_adid'	=> array(
			'table'	=>	'cf_fanpage_adid',
			'name'	=>	'ADMIN ID (FB)',
			'type'	=>	'free',
			'can_be_empty'	=> true,
		),
		'cf_announcement'	=> array(
			'table'	=>	'cf_announcement',
			'name'	=>	'WEB ANNOUNCEMENT',
			'type'	=>	'texts',
			'can_be_empty'	=> true,
		),
		'cf_server_post'	=> array(
			'table'	=>	'cf_server_post',
			'name'	=>	'WEB SERVERS',
			'type'	=>	'texts',
			'can_be_empty'	=> true,
		),
		'cf_textlink'	=> array(
			'table'	=>	'cf_textlink',
			'name'	=>	'WEB TEXTLINKS',
			'type'	=>	'texts',
			'can_be_empty'	=> true,
		),
		'cf_tags'	=> array(
			'table'	=>	'cf_tags',
			'name'	=>	'WEB TAGS',
			'type'	=>	'texts',
			'can_be_empty'	=> true,
		),
		'cf_web_cache'	=> array(
			'table'	=>	'cf_web_cache',
			'name'	=>	'WEB CACHE KEY',
			'type'	=>	'texts',
			'can_be_empty'	=> true,
		),
                'cf_web_gkphp'	=> array(
			'table'	=>	'cf_web_gkphp',
			'name'	=>	'WEB KEY GkPHP',
			'type'	=>	'texts',
			'can_be_empty'	=> true,
		),
	);

?>
<section class="vbox">
            <section class="scrollable padder">
              <ul class="breadcrumb no-border no-radius b-b b-light pull-in">
                <li><a href="index.php"><i class="fa fa-home"></i> Home</a></li>
                <li class="active">Config</li>
              </ul>
	<?php 
        $cf_id = 1;
	if (!isset($_POST['submit'])) {
			$q = $mysql->query("SELECT * FROM ".$tb_prefix."config WHERE cf_id = 1");
			$r = $q->fetch(PDO::FETCH_ASSOC);
			
			foreach ($inp_arr as $key=>$arr) $$key = $r[$arr['table']];
		}
		else {
			$error_arr = array();
			$error_arr = $form->checkForm($inp_arr);
			if (!$error_arr) {
			
				$sql = $form->createSQL(array('UPDATE',$tb_prefix.'config','cf_id','cf_id'),$inp_arr);
				eval('$mysql->query("'.$sql.'");');
				echo "EDIT FINISH <meta http-equiv='refresh' content='0;url=".$edit_url."'>";
				exit();
			}
		}
		$warn = $form->getWarnString($error_arr);
		$form->createForm('Cấu hình website',$inp_arr,$error_arr);
?>	
			   </section>
          </section>