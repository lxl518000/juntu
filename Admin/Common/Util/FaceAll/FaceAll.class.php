<?php
namespace Common\Util\FaceAll;
class FaceAll
{
    private $api_key;
    private $api_secret;
    private $version;
    private $host = "http://api.faceall.cn:80/";
    private $url;    

    function __construct($api_key, $api_secret, $version)
    {
        $this->api_key    = $api_key;
        $this->api_secret = $api_secret;
        $this->version    = $version;
        $this->url        =$this->host.$version.'/';
    }
    
    public function request($method, $data) {
        
        $data['api_key']    = $this->api_key;
        $data['api_secret'] = $this->api_secret;
        $curl_handle = curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $this->url.$method);
        curl_setopt($curl_handle, CURLOPT_FILETIME, true);
        curl_setopt($curl_handle, CURLOPT_FRESH_CONNECT, false);
        if (class_exists('\CURLFile')) {
            curl_setopt($curl_handle, CURLOPT_SAFE_UPLOAD, true);
        } else {
            if (defined('CURLOPT_SAFE_UPLOAD')) {
                curl_setopt($curl_handle, CURLOPT_SAFE_UPLOAD, false);
            }
        }
        curl_setopt($curl_handle, CURLOPT_MAXREDIRS, 5);
        curl_setopt($curl_handle, CURLOPT_HEADER, false);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl_handle, CURLOPT_TIMEOUT, 3600);
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 120);
        curl_setopt($curl_handle, CURLOPT_NOSIGNAL, true);
        
        if (extension_loaded('zlib')) {
            curl_setopt($curl_handle, CURLOPT_ENCODING, '');
        }
        
        if (array_key_exists('img_file', $data)) {
            if (class_exists('\CURLFile'))
            {   
                $data['img_file'] = new \CURLFile(realpath($data['img_file']));
            }
            else $data['img_file'] = '@'. realpath($data['img_file']);
        } else {
            $data = http_build_query($data);
        }
        curl_setopt($curl_handle, CURLOPT_POSTFIELDS, $data);
        $response_text      = curl_exec($curl_handle); 
        $httpcode = curl_getinfo($curl_handle, CURLINFO_HTTP_CODE);
        curl_close($curl_handle);
        return $response_text;
    }
}
