<?php
include 'vendor/ChipVN/ClassLoader/Loader.php';
$config = require 'includes/config.php';
ChipVN_ClassLoader_Loader::registerAutoLoad();
$uploader = ChipVN_ImageUploader_Manager::make('Picasanew');
$server = $config['picasanew'];
$uploader->login('phimletv2015@gmail.com', 'duynghia119494');
$uploader->setApi($server['API']['ID']); // register in console.developers.google.com
$uploader->setSecret($server['API']['secret']);
if (!$uploader->hasValidToken()) {
   echo $uploader->getOAuthToken('http://www.phimle.tv/upanh/gettoken.php');
}else{
echo 'OK!!!<br/>';
print_r($uploader->getToken());
}