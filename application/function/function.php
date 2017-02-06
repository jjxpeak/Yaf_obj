<?php
/**
 * Created by PhpStorm.
 * User: peak
 * Date: 2016/7/8
 * Time: 10:42
 */

/**
 * 字符串加密
 * @param $input
 * @param $key
 * @return string
 */
function encrypt($input, $key)
{
    $size = mcrypt_get_block_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_ECB);
    $input = pkcs5_pad($input, $size);
    $td = mcrypt_module_open(MCRYPT_RIJNDAEL_128, '', MCRYPT_MODE_ECB, '');
    $iv = mcrypt_create_iv(mcrypt_enc_get_iv_size($td), MCRYPT_RAND);
    mcrypt_generic_init($td, $key, $iv);
    $data = mcrypt_generic($td, $input);
    mcrypt_generic_deinit($td);
    mcrypt_module_close($td);
    $data = base64_encode($data);
    return $data;
}


function pkcs5_pad($text, $blocksize)
{
    $pad = $blocksize - (strlen($text) % $blocksize);
    return $text . str_repeat(chr($pad), $pad);
}


/**
 * 字符串解密
 * @param $sStr
 * @param $sKey
 * @return string
 */
function decrypt($sStr, $sKey)
{
    $decrypted = mcrypt_decrypt(
        MCRYPT_RIJNDAEL_128,
        $sKey,
        base64_decode($sStr),
        MCRYPT_MODE_ECB
    );
    $dec_s = strlen($decrypted);
    $padding = ord($decrypted[$dec_s - 1]);
    $decrypted = substr($decrypted, 0, -$padding);
    return $decrypted;
}

/**
 * ajax 数据返回
 * @param $data
 */
function ajax_message($data)
{
    if (is_array($data)) {
        $massage = json_encode($data);
    } else {
        $massage = $data;
    }
    exit($massage);
}

/**
 * 获取当前IP
 * @return string
 */
function getIp()
{
    $realip = '';
    $unknown = 'unknown';
    if (isset($_SERVER)) {
        if (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR']) && strcasecmp($_SERVER['HTTP_X_FORWARDED_FOR'], $unknown)) {
            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
            foreach ($arr as $ip) {
                $ip = trim($ip);
                if ($ip != 'unknown') {
                    $realip = $ip;
                    break;
                }
            }
        } else if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP']) && strcasecmp($_SERVER['HTTP_CLIENT_IP'], $unknown)) {
            $realip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (isset($_SERVER['REMOTE_ADDR']) && !empty($_SERVER['REMOTE_ADDR']) && strcasecmp($_SERVER['REMOTE_ADDR'], $unknown)) {
            $realip = $_SERVER['REMOTE_ADDR'];
        } else {
            $realip = $unknown;
        }
    } else {
        if (getenv('HTTP_X_FORWARDED_FOR') && strcasecmp(getenv('HTTP_X_FORWARDED_FOR'), $unknown)) {
            $realip = getenv("HTTP_X_FORWARDED_FOR");
        } else if (getenv('HTTP_CLIENT_IP') && strcasecmp(getenv('HTTP_CLIENT_IP'), $unknown)) {
            $realip = getenv("HTTP_CLIENT_IP");
        } else if (getenv('REMOTE_ADDR') && strcasecmp(getenv('REMOTE_ADDR'), $unknown)) {
            $realip = getenv("REMOTE_ADDR");
        } else {
            $realip = $unknown;
        }
    }
    $realip = preg_match("/[\d\.]{7,15}/", $realip, $matches) ? $matches[0] : $unknown;
    return $realip;
}

/**
 * 获取ip的地址
 * @param string $ip
 * @return bool|mixed
 */
function GetIpLookup($ip = ''){
    if(empty($ip)){
        $ip = GetIp();
    }
    $res = @file_get_contents('http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js&ip=' . $ip);
    if(empty($res)){ return false; }
    $jsonMatches = array();
    preg_match('#\{.+?\}#', $res, $jsonMatches);
    if(!isset($jsonMatches[0])){ return false; }
    $json = json_decode($jsonMatches[0], true);
    if(isset($json['ret']) && $json['ret'] == 1){
        $json['ip'] = $ip;
        unset($json['ret']);
    }else{
        return false;
    }
    return $json;
}

/**
 * @param $filename csv 文件名
 * @param $data csv 数据
 */
function export_csv($filename,$data)
{
    header("Content-type:text/csv");
    header("Content-Disposition:attachment;filename=".$filename);
    header('Cache-Control:must-revalidate,post-check=0,pre-check=0');
    header('Expires:0');
    header('Pragma:public');
    echo $data;
}

/**
 * curl提交参数
 * @param string $url
 * @param array $data
 */
function Curl($url,$type,$data=array()){
    $type = strtoupper($type);
    $url = curl_init($url);
    curl_setopt($url,CURLOPT_HEADER,0);
    curl_setopt($url,CURLOPT_RETURNTRANSFER,1);
    curl_setopt($url,CURLOPT_CUSTOMREQUEST,$type);
    curl_setopt($url, CURLOPT_SSL_VERIFYPEER, false);
    if($type == "POST"){
        curl_setopt($url,CURLOPT_POSTFIELDS,$data);
    }
    $content = curl_exec($url);
    curl_close($url);
    return $content;
}

/**
 * token b610567eb6973f337061a6b68cd76575
 */
function wuchouzhijia(){

}
