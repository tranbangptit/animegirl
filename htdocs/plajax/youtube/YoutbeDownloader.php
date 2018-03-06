<?php

/**
 * Project : YoutbeDownloader
 * User: thuytien
 * Date: 10/5/2014
 * Time: 11:11 PM
 * Using code from https://github.com/jeckman/YouTube-Downloader
 */
class YoutbeDownloader
{
    private static $endpoint = "http://www.youtube.com/get_video_info";

    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    public function getLink($id)
    {
		preg_match('#https://www.youtube.com/watch?v=(.*)#', $id, $Match);
        $API_URL    = self::$endpoint . "?&video_id=" . $Match[1];
        $video_info = $this->curlGet($API_URL);

        $url_encoded_fmt_stream_map = '';
        parse_str($video_info);
        if(isset($reason))
        {
            return $reason;
        }
        if (isset($url_encoded_fmt_stream_map)) {
            $my_formats_array = explode(',', $url_encoded_fmt_stream_map);
        } else {
            return 'No encoded format stream found.';
        }
        if (count($my_formats_array) == 0) {
            return 'No format stream map found - was the video id correct?';
        }
        $avail_formats[] = '';
        $i = 0;
        $ipbits = $ip = $itag = $sig = $quality = $type = $url = '';
        $expire = time();
        foreach ($my_formats_array as $format) {
            parse_str($format);
            $avail_formats[$i]['itag'] = $itag;
            $avail_formats[$i]['quality'] = $quality;
            $type = explode(';', $type);
            $avail_formats[$i]['type'] = $type[0];
            $avail_formats[$i]['url'] = urldecode($url) . '&signature=' . $sig;
            parse_str(urldecode($url));
            $avail_formats[$i]['expires'] = date("G:i:s T", $expire);
            $avail_formats[$i]['ipbits'] = $ipbits;
            $avail_formats[$i]['ip'] = $ip;
            $i++;
        }
        if (is_string($avail_formats)) {
            echo $avail_formats;
        } else {
            foreach ($avail_formats as $video) {
                if (strpos($video['url'], 'itag=22')) {
                    $m22 = $video['url'];
                } elseif (strpos($video['url'], 'itag=18')) {
                    $m18 = $video['url'];
                }
            }
            if (isset($m22, $m18)) {
             
$js = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$m18.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a><a class="btn btn-green btn-download " id="btn-download-720" href="'.$m22.'"><span class="btn-text"><b>DOWNLOAD 720p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';

            } elseif (isset($m18)) {
             $js = '<a class="btn btn-green btn-download " id="btn-download-360" href="'.$m18.'"><span class="btn-text"><b>DOWNLOAD 360p</b><br>(<span class="resolution-name file-size"></span> - <span class="file-type">MP4</span>)</span></a>';
            } else {
                $js = 'Not support';
            }
            return $js;
        }
    }

    function curlGet($URL)
    {
        $ch = curl_init();
        $timeout = 3;
        curl_setopt($ch, CURLOPT_URL, $URL);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
        $tmp = curl_exec($ch);
        curl_close($ch);
        return $tmp;
    }
}