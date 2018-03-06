<?php
session_start();
error_reporting(E_ALL ^ E_NOTICE);

header('Content-type: text/html;charset=utf-8');
error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('memory_limit', '512M'); // increase this if you catch "Allowed memory size..."

require 'includes/functions.php';
require 'vendor/ChipVN/ClassLoader/Loader.php';
require 'vendor/PhpThumb/ThumbLib.inc.php';

ChipVN_ClassLoader_Loader::registerAutoload();

$config = require 'includes/config.php';
$options = $config['options'];


$defaults = array();
foreach ($options as $name => $option) {
    $defaults[$name] = $option['default'];
}

if(!empty($_GET)){
	extract(array_map('trim', $_GET) + $defaults + array('type' => '', 'url' => ''), EXTR_PREFIX_ALL, 'data');
}elseif(isset($_POST)){
	extract(array_map('trim', $_POST) + $defaults + array('type' => '', 'url' => ''), EXTR_PREFIX_ALL, 'data');
}


if (in_array($data_type, array('upload', 'transload'))) {
    // Validate
    foreach (array_keys($defaults) as $name) {
        $varname = 'data_' . $name;
        if (!in_array(${$varname}, array_keys($options[$name]['options']))) {
            response_json(array(
                'error'   => true,
                'message' => 'The value of "' . $name . '" is invalid.'
            ));
        }
    }
    $tempFile = $config['temp_dir'] . '/' . uniqid() . '.jpg';
    // remove comment under if you want to keep original file name
    // some service will force file name to their name (eg: imgur)
    //
    // if (isset($_FILES['files']['name'][0])) {
    //     $tempFile = $config['temp_dir'] . '/' . $_FILES['files']['name'][0];
    // }

    try {
        if ($data_type == 'upload' && !empty($_FILES['files'])) {
            $file = array(
                'name'     => $_FILES['files']['name'][0],
                'size'     => $_FILES['files']['size'][0],
                'type'     => $_FILES['files']['type'][0],
                'tmp_name' => $_FILES['files']['tmp_name'][0],
            );
            if (!$imageSize = getimagesize($file['tmp_name'])) {
                throw new Exception('The file is not an image.');
            }
            if ($file['size'] > $config['upload']['max_file_size']) {
                throw new Exception('The image is too large.');
            }
            $phpThumb = PhpThumbFactory::create($file['tmp_name']);

        } elseif ($data_type == 'transload' && parse_url($data_url, PHP_URL_HOST)) {
            if (download_file($data_url, $tempFile)) {
                if (!$imageSize = getimagesize($tempFile)) {
                    throw new Exception('The url is not an image.');
                }
            } else {
                throw new Exception('Cannot download the url.');
            }
            $phpThumb = PhpThumbFactory::create($tempFile);

        } else {
            throw new Exception('Data is invalid.');
        }
        $phpThumb->setOptions(array(
            'resizeUp'              => false,
            'correctPermissions'    => false,
            // 'preserveAlpha'         => false,
            // 'preserveTransparency'  => false,
        ));
        $logo    = $config['logo_dir'] . '/' . $data_watermark_logo . '.png';
        $minSize = explode('x', $config['watermark_minimum_size']);
        if (
            $data_watermark
            && file_exists($logo)
            && ( empty($config['watermark_minimum_size'])
                || (count($minSize) == 2
                    && $minSize[0] <= $imageSize[0]
                    && $minSize[1] <= $imageSize[1]
                )
            )
        ) {
            $phpThumb
                ->resize($data_resize)
                ->createWatermark($logo, $data_watermark_position, 0);
        } else {
            $phpThumb->resize($data_resize);
        }
        $phpThumb->save($tempFile);

    } catch (Exception $e) {
        if (file_exists($tempFile)) {
            unlink($tempFile);
        }
        response_json(array(
            'error'   => true,
            'message' => $e->getMessage()
        ));
    }

    $server = strtolower($data_server);
    // setup general
    $uploader = ChipVN_ImageUploader_Manager::make(ucfirst($server));
    $uploader->useCurl($config['use_curl']);
    $uploader->setCache($config['cache_adapter']);

    $result = array();
    try {
        $serverConfig = $config[$server];
        switch ($server) {
            case 'imgur':
                if ($account = random_element($serverConfig['accounts'])) {
                    $uploader->login($account['username'], $account['password']);
                }
                break;

            case 'imageshack':
                $account = random_element($serverConfig['accounts']);
                $apiKey  = random_element($serverConfig['api_keys']);

                $uploader->login($account['username'], $account['password']);
                $uploader->setApi($apiKey);
                break;

            case 'picasa':
                $account = random_element($serverConfig['accounts']);
                $albumId = random_element($account['album_ids']);

                $uploader->login($account['username'], $account['password']);
                $uploader->setAlbumId($albumId);
                break;

            case 'picasanew':

                $account = random_element($serverConfig['accounts']);
                $albumId = random_element($account['album_ids']);

                $uploader->login($account['username'], $account['password']);
				$uploader->setApi($serverConfig['API']['ID']);
				$uploader->setSecret($serverConfig['API']['secret']);
                $uploader->setAlbumId($albumId);
                break;

            case 'flickr':
                $api   = random_element($serverConfig['api_keys']);
                $token = random_element(require $serverConfig['token_file']);

                $uploader->setApi($api['key']);
                $uploader->setSecret($api['secret']);

                if (!$token) {
                    $account = random_element($serverConfig['accounts']);
                    $uploader->login($account['username'], $account['password']);

                    $token  = $uploader->getAccessToken();
                    $secret = $uploader->getAccessSecret();
                    // write token to re-use
                    write_flickr_token($serverConfig['token_file'], $account['username'], $token, $secret);

                    $uploader->setAccessToken($token, $secret);

                } else {
                    $uploader->setAccessToken($token['token'], $token['secret']);
                }
                break;

            case 'postimage':
                if ($account = random_element($serverConfig['accounts'])) {
                   $uploader->login($account['username'], $account['password']);
                }
                break;
        }
        // group cache identifier is made by plugin name, username
        // so we should call this after call login();
        $uploader->getCache()->garbageCollect();
        $url = $uploader->upload($tempFile);
        $result = array(
            'error' => false,
            'url'   => $url,
        );
    } catch (Exception $e) {
        $result = array(
            'error'   => true,
            'message' => $e->getMessage()
        );
    }

    unlink($tempFile);


    response_json($result);
}

