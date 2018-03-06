<?php

function download_file($url, $destination)
{
    $url = strtr(trim(urldecode($url)), ' ', '%20');
    if ($data = fopen($url, "rb")) {
        $newfile = fopen($destination, "w");
        while ($buff = fread($data, 1024*8)) {
            fwrite($newfile, $buff);
        }
        fclose($data);
        fclose($newfile);

        return true;
    }

    return false;
}

function response_json(array $result)
{
    echo json_encode($result);
    exit;
}

function random_element($array)
{
    if (is_array($array) && null !== $key = array_rand($array)) {
        return $array[$key];
    }

    return null;
}

function write_flickr_token($token_file, $username, $token, $secret)
{
    if (file_exists($token_file)) {
        $existing = array();
    } else {
        $existing = include $config['token_file'];
    }

    $data = array(
        strtolower($username) => array(
            'token'  => $token,
            'secret' => $secret,
        )
    );

    $exported = var_export(array_merge($data, $existing), true);

    return file_put_contents($token_file, '<?php' . PHP_EOL .'return ' . $exported . ';');
}
